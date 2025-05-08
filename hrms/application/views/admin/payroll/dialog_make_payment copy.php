<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payment' && $_GET['type'] == 'monthly_payment') { ?>
	<style>
		.tcpdf-div {
			border: 1px solid #000;
			padding: 10px;
		}

		body {
			font-family: Arial, sans-serif;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		th,
		td {
			border: 1px solid #000;
			padding: 10px;
			text-align: left;
		}

		th {
			background-color: #f2f2f2;
		}

		.total {
			font-weight: bold;
		}
	</style>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime($this->input->get('pay_date'));
	$pay_date = $this->input->get('pay_date');
	// office shift
	$office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);
	$p_month = date('F Y', strtotime('01-' . $this->input->get('pay_date')));
	$pa_month = date('Y-m', strtotime('01-' . $this->input->get('pay_date')));

	if ($wages_type == 1) {
		if ($system[0]->is_half_monthly == 1) {
			$basic_salary = $basic_salary / 2;
		} else {
			$basic_salary = $basic_salary;
		}
	} else {
		$basic_salary = $daily_wages;
	}

	$main_salary = $basic_salary;

	// user information
	$user = $this->Xin_model->read_user_info($user_id);
	?>

	<?php

	$ordinary_wage = 0;
	$ow = 0;
	$ow_cpf_employer = 0;
	$ow_cpf_employee = 0;
	$aw = 0;
	$aw_cpf_employer = 0;
	$aw_cpf_employee = 0;
	$g_ordinary_wage = 0;
	$g_additional_wage = 0;
	$g_shg = 0;
	$g_sdl = 0;


	$check = $this->Payroll_model->check_make_payment_payslip_as_desc($user_id, $pay_date);
	$check_payment = explode(',', $check[0]->check_id);


	// employee deduction
	$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id, $pay_date);
	$deduction_amount = 0;
	if (in_array('chk_total_employee_deduction', $check_payment)) {
		if ($employee_deduction) {
			foreach ($employee_deduction as $deduction) {
				if ($deduction->type_id == 1) {
					$deduction_amount +=  $deduction->amount;
				}
				if ($deduction->type_id == 2) {
					$from_month_year = date('Y-m', strtotime($deduction->from_date));
					$to_month_year = date('Y-m-d', strtotime($deduction->to_date));


					if ($from_month_year != "" && $to_month_year != "") {
						if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
							$deduction_amount +=  $deduction->amount;
						}
					}
				}
			}
		}
	}



	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	$month_start_date = new DateTime('01-' . $pay_date);
	$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
	$month_end_date->modify('+1 day');
	$holiday_array_new = array();
	$interval = new DateInterval('P1D');
	$period = new DatePeriod($month_start_date, $interval, $month_end_date);
	$holiday_array_new = array();
	foreach ($period as $p) {
		$p_day = $p->format('l');
		$p_date = $p->format('Y-m-d');
		$p_date_n = $p->format('Y-m-d');

		//holidays in a month

		$is_holiday = $this->Timesheet_model->is_holiday_on_date($company_id, $p_date);
		if ($is_holiday) {
			$holidays_count += 1;
			$holiday_array_new[] = $p_date_n;
		}

		//working days excluding holidays based on office shift
		if ($p_day == 'Monday') {
			if ($office_shift[0]->monday_in_time != '') {
				$no_of_working_days += 1;
			}
		} else if ($p_day == 'Tuesday') {
			if ($office_shift[0]->tuesday_in_time != '') {
				$no_of_working_days += 1;
			}
		} else if ($p_day == 'Wednesday') {
			if ($office_shift[0]->wednesday_in_time != '') {
				$no_of_working_days += 1;
			}
		} else if ($p_day == 'Thursday') {
			if ($office_shift[0]->thursday_in_time != '') {
				$no_of_working_days += 1;
			}
		} else if ($p_day == 'Friday') {
			if ($office_shift[0]->friday_in_time != '') {
				$no_of_working_days += 1;
			}
		} else if ($p_day == 'Saturday') {
			if ($office_shift[0]->saturday_in_time != '') {
				$no_of_working_days += 1;
			}
		} else if ($p_day == 'Sunday') {
			if ($office_shift[0]->sunday_in_time != '') {
				$no_of_working_days += 1;
			}
		}
	}

	//unpaid leave
	$unpaid_leave_amount = 0;
	$leaves_taken_count = 0;
	$leave_period = array();
	$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($user_id, $pay_date);
	if ($unpaid_leaves) {
		foreach ($unpaid_leaves as $k => $l) {
			$pay_date_month = new DateTime('01-' . $pay_date);
			$l_from_date = new DateTime($l->from_date);
			$l_to_date = new DateTime($l->to_date);

			if ($l_from_date->format('m') == $l_to_date->format('m')) {
				$start_date = $l_from_date;
				$end_date = $l_to_date;
			} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
				$start_date = $l_from_date;
				$end_date = new DateTime($start_date->format('Y-m-t'));
			} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
				$start_date = $pay_date_month;
				$end_date = $l_to_date;
			}
			$end_date->modify('+1 day');
			$interval = new DateInterval('P1D');
			$period = new DatePeriod($start_date, $interval, $end_date);

			foreach ($period as $d) {
				$p_day = $d->format('l');
				if ($p_day == 'Monday') {

					if ($office_shift[0]->monday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Tuesday') {
					if ($office_shift[0]->tuesday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Wednesday') {
					if ($office_shift[0]->wednesday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Thursday') {
					if ($office_shift[0]->thursday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Friday') {
					if ($office_shift[0]->friday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Saturday') {
					if ($office_shift[0]->saturday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Sunday') {
					if ($office_shift[0]->sunday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				}
			}
			$leave_period[$k]['is_half'] = $l->is_half_day;
		}
	}

	// if joining date and pay date same then this logic work
	$month_date_join = date('m-Y', strtotime($user[0]->date_of_joining));
	$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
	$month_last_date = date('m-Y', strtotime($lastday));
	$first_date =  new DateTime($user[0]->date_of_joining);

	$no_of_days_worked = 0;
	$same_month_holidays_count = 0;
	if ($month_date_join == $pay_date) {
		$interval = new DateInterval('P1D');
		$period = new DatePeriod($first_date, $interval, $month_end_date);
		foreach ($period as $p) {
			$p_day = $p->format('l');
			$p_date = $p->format('Y-m-d');

			//holidays in a month

			$is_holiday = $this->Timesheet_model->is_holiday_on_date($company_id, $p_date);
			if ($is_holiday) {
				$same_month_holidays_count += 1;
			}

			//working days excluding holidays based on office shift
			if ($p_day == 'Monday') {
				if ($office_shift[0]->monday_in_time != '') {
					$no_of_days_worked += 1;
				}
			} else if ($p_day == 'Tuesday') {
				if ($office_shift[0]->tuesday_in_time != '') {
					$no_of_days_worked += 1;
				}
			} else if ($p_day == 'Wednesday') {
				if ($office_shift[0]->wednesday_in_time != '') {
					$no_of_days_worked += 1;
				}
			} else if ($p_day == 'Thursday') {
				if ($office_shift[0]->thursday_in_time != '') {
					$no_of_days_worked += 1;
				}
			} else if ($p_day == 'Friday') {
				if ($office_shift[0]->friday_in_time != '') {
					$no_of_days_worked += 1;
				}
			} else if ($p_day == 'Saturday') {
				if ($office_shift[0]->saturday_in_time != '') {
					$no_of_days_worked += 1;
				}
			} else if ($p_day == 'Sunday') {
				if ($office_shift[0]->sunday_in_time != '') {
					$no_of_days_worked += 1;
				}
			}
		}
		// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
		$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
	} else {
		// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
		$no_of_days_worked = $no_of_working_days -  $holidays_count;
	}


	if ($month_date_join == $pay_date) {
		$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
	} else {
		$show_main_salary = $basic_salary;
	}

	$g_ordinary_wage += $show_main_salary;
	$g_shg += $show_main_salary;
	$g_sdl += $show_main_salary;

	$g_ordinary_wage -= $deduction_amount;
	$g_shg -= $deduction_amount;
	$g_sdl -= $deduction_amount;

	// echo $g_shg;
	// $not_working_day_amount = round(($basic_salary) - $gross_pay, 2);

	// if ($unpaid_leaves) {
	// 	$unpaid_leave_amount = round(($basic_salary) - $gross_pay, 2);
	// }

	// $g_ordinary_wage = $gross_pay;
	// $g_shg = $gross_pay;
	// $g_sdl = $gross_pay;

	// $g_ordinary_wage -= $unpaid_leave_amount;


	// 3: all loan/deductions
	$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($user_id);
	$count_loan_deduction = $this->Employees_model->count_employee_deductions($user_id);
	$loan_de_amount = 0;
	if ($count_loan_deduction > 0) {
		foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
			if ($system[0]->is_half_monthly == 1) {
				if ($system[0]->half_deduct_month == 2) {
					$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
				} else {
					$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
				}
			} else {
				$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
			}
			$loan_de_amount += $er_loan;
		}
	} else {
		$loan_de_amount = 0;
	}
	$loan_de_amount = number_format(floatval($loan_de_amount), 2);



	//Allowance
	$allowance_amount = 0;
	$gross_allowance_amount = 0;
	$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($user_id, $pay_date);
	if ($salary_allowances) {
		foreach ($salary_allowances as $sa) {
			if ($system[0]->is_half_monthly == 1) {
				if ($system[0]->half_deduct_month == 2) {
					$eallowance_amount = $sa->allowance_amount / 2;
				} else {
					$eallowance_amount = $sa->allowance_amount;
				}
			} else {
				$eallowance_amount = $sa->allowance_amount;
			}
			if (!empty($sa->salary_month)) {
				$g_additional_wage += $eallowance_amount;
			} else {
				// for no of working day
				if ($month_date_join == $pay_date) {
					$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
				}
				$g_ordinary_wage += $eallowance_amount;
				if ($sa->id == 2) {
					$gross_allowance_amount = $eallowance_amount;
				}
			}

			if ($sa->sdl == 1) {
				$g_sdl += $eallowance_amount;
			}
			if ($sa->shg == 1) {
				$g_shg += $eallowance_amount;
			}

			$allowance_amount += $eallowance_amount;
		}
	}
	$gross_allowance_amount = $allowance_amount;


	// commissions
	$commissions_amount = 0;
	$commissions = $this->Employees_model->getEmployeeMonthlyCommission($user_id, $pay_date);
	if ($commissions) {
		foreach ($commissions as $c) {
			if ($system[0]->is_half_monthly == 1) {
				if ($system[0]->half_deduct_month == 2) {
					$ecommissions_amount = $c->commission_amount / 2;
				} else {
					$ecommissions_amount = $c->commission_amount;
				}
			} else {
				$ecommissions_amount = $c->commission_amount;
			}

			if ($c->commission_type == 9) {
				$g_ordinary_wage += $ecommissions_amount;
			} elseif ($c->commission_type == 10) {
				$g_additional_wage += $ecommissions_amount;
			}

			if ($c->sdl == 1) {
				$g_sdl += $ecommissions_amount;
			}
			if ($c->shg == 1) {
				$g_shg += $ecommissions_amount;
			}

			$commissions_amount += $ecommissions_amount;
		}
	}

	//share options
	$share_options_amount = 0;
	$share_options = $this->Employees_model->getEmployeeShareOptions($user_id, $pay_date);
	if ($share_options) {
		$eebr_amount = 0;
		$eris_amount = 0;
		foreach ($share_options as $s) {
			$scheme = $s->so_scheme;
			if ($scheme == 1) {
				$price_doe = $s->price_date_of_excercise;
				$price_ex = $s->excercise_price;
				$no_shares = $s->no_of_shares;
				$amount = ($price_doe - $price_ex) * $no_shares;
				$eebr_amount += $amount;
			} else {
				$price_doe = $s->price_date_of_excercise;
				$price_dog = $s->price_date_of_grant;
				$price_ex = $s->excercise_price;
				$no_shares = $s->no_of_shares;

				$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
				$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
				$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
			}
		}
		$share_options_amount = round($eebr_amount + $eris_amount, 2);
		$g_additional_wage += $share_options_amount;
		$g_sdl += $share_options_amount;
		$g_shg += $share_options_amount;
	}


	// 4: other payment
	$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
	$other_payments_amount = 0;
	if (!is_null($other_payments)) :
		foreach ($other_payments->result() as $sl_other_payments) {
			if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
				if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
					if ($system[0]->is_half_monthly == 1) {
						if ($system[0]->half_deduct_month == 2) {
							$epayments_amount = $sl_other_payments->payments_amount / 2;
						} else {
							$epayments_amount = $sl_other_payments->payments_amount;
						}
					} else {
						$epayments_amount = $sl_other_payments->payments_amount;
					}

					if ($sl_other_payments->cpf_applicable == 1) {
						$g_additional_wage += $epayments_amount;
						$g_shg += $epayments_amount;
						$g_sdl += $epayments_amount;
					}

					$other_payments_amount += $epayments_amount;
				}
			} else {
				$first_date = new DateTime($sl_other_payments->date);
				if ($first_date->format('m-Y') == $pay_date) {
					$first_date =  new DateTime($sl_other_payments->date);
				} else {
					$first_date = new DateTime('01-' . $pay_date);
				}

				$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

				if (!empty($sl_other_payments->end_date)) {
					$last_date = new DateTime($sl_other_payments->end_date);
					if ($last_date->format('m-Y') == $pay_date) {
						$last_date = new DateTime($sl_other_payments->end_date);
					} else if ($last_date->format('m-Y') >= $pay_date) {
						$last_date = $month_end_date_for_other;
					} else {
						$last_date = '';
					}
				} else {
					$last_date = $month_end_date_for_other;
				}

				if (!empty($last_date)) {
					$last_date->modify('+1 day');
					$final_last_day = new DateTime($last_date->format('d-m-Y'));
					if ($final_last_day->format('m-Y') >= $pay_date) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$epayments_amount = $sl_other_payments->payments_amount / 2;
							} else {
								$epayments_amount = $sl_other_payments->payments_amount;
							}
						} else {
							$epayments_amount = $sl_other_payments->payments_amount;
						}


						// it for no of working day
						$no_of_days_worked_for_other_payment = 0;
						$same_month_holidays_count_for_other_payment = 0;
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($first_date, $interval, $last_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$p_date = $p->format('Y-m-d');

							//holidays in a month

							$is_holiday = $this->Timesheet_model->is_holiday_on_date($company_id, $p_date);
							if ($is_holiday) {
								$same_month_holidays_count_for_other_payment += 1;
							}

							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_days_worked_for_other_payment += 1;
								}
							}
						}

						$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
						$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);


						if ($sl_other_payments->cpf_applicable == 1) {
							$g_additional_wage += $epayments_amount;
							$g_shg += $epayments_amount;
							$g_sdl += $epayments_amount;
						}
						$other_payments_amount += $epayments_amount;
					}
				}
			}
		}
	endif;


	$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);

	if ($unpaid_leaves) {
		$unpaid_leave_amount = round(($gross_pay / $no_of_days_worked) * $leaves_taken_count, 2);
	}


	// $g_ordinary_wage = $gross_pay;
	$g_shg = $gross_pay;
	$g_sdl = $gross_pay;

	$g_ordinary_wage -= $unpaid_leave_amount;




	// other benefit
	$other_benefit_mount = 0;
	$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($user_id, $pay_date);
	foreach ($other_benefit_list->result() as $benefit_list) {
		$other_benefit_mount += $benefit_list->other_benefit_cost;
	}


	// 6: statutory deductions
	$statutory_deductions_amount = 0;
	$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
	if (!is_null($statutory_deductions)) :
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $gross_pay;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				if ($system[0]->is_half_monthly == 1) {
					if ($system[0]->half_deduct_month == 2) {
						$single_sd = $st_amount / 2;
					} else {
						$single_sd = $st_amount;
					}
				} else {
					$single_sd = $st_amount;
				}
				$statutory_deductions_amount += $single_sd;
			else :
				if ($system[0]->is_half_monthly == 1) {
					if ($system[0]->half_deduct_month == 2) {
						$single_sd = $sl_statutory_deductions->deduction_amount / 2;
					} else {
						$single_sd = $sl_statutory_deductions->deduction_amount;
					}
				} else {
					$single_sd = $sl_statutory_deductions->deduction_amount;
				}
				$statutory_deductions_amount += $single_sd;
			endif;
		}
	endif;

	// overtime
	$overtime_amount = 0;
	$total_overtime_time = 0;
	$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($user_id, $pay_date);
	if ($overtime) {
		$ot_hrs = 0;
		$ot_mins = 0;

		foreach ($overtime as $ot) {
			$total_hours = explode(':', $ot->total_hours);
			$ot_hrs += $total_hours[0];
			$ot_mins += $total_hours[1];
		}
		if ($ot_mins > 0) {
			$ot_hrs += round($ot_mins / 60, 2);
		}

		//overtime rate
		$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($user_id);
		if ($overtime_rate) {
			$rate = $overtime_rate->overtime_pay_rate;
		} else {
			$week_hours = 44;
			$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
			$rate = $rate * 1.5;
		}

		if ($ot_hrs > 0) {
			$overtime_amount = round($ot_hrs * $rate, 2);
		}
		$total_overtime_time += $ot_hrs;
		if ($system[0]->is_half_monthly == 1) {
			if ($system[0]->half_deduct_month == 2) {
				$overtime_amount = $overtime_amount / 2;
			}
		}

		$g_ordinary_wage += $overtime_amount;
		$g_sdl += $overtime_amount;
	}



	// employee accommodations
	$employee_accommodations = 0;
	$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($user_id, $pay_date);
	foreach ($get_employee_accommodations as $get_employee_accommodation) {
		$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
		$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
		if ($period_from == $pay_date || $period_to == $pay_date) {
			if (!empty($get_employee_accommodation->rent_paid)) {
				$employee_accommodations += $get_employee_accommodation->rent_paid;
			}
		}
	}

	// employee claims
	$claim_amount = 0;
	$get_employee_claims = $this->Employees_model->getEmployeeClaim($user_id);
	foreach ($get_employee_claims->result() as $claims) {
		$date 	= 	date('m-Y', strtotime($claims->date));
		if ($date == $pay_date) {
			$claim_amount += $claims->amount;
		}
	}


	$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
	$total_deduction = floatval($loan_de_amount) + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
	$total_net_salary = ($total_earning + $claim_amount)  - $total_deduction - $unpaid_leave_amount;



	// cpf calculation
	$emp_dob = $dob;
	$dob = new DateTime($emp_dob);

	$today = new DateTime('01-' . $this->input->get('pay_date'));
	$age = $dob->diff($today);
	$age_year = $age->y;
	$age_month = $age->m;

	$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
	$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
	$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

	if ($age_month > 0) {
		$age_year = $age_year + 1;
	}


	$im_status = $this->Employees_model->getEmployeeImmigrationStatus($user_id);

	$cpf_employee 	= 	0;
	$cpf_employer	=	0;
	$im_status = $this->Employees_model->getEmployeeImmigrationStatus($user_id);
	if ($im_status) {
		$immigration_id = $im_status->immigration_id;
		if ($immigration_id == 2) {
			$issue_date = $im_status->issue_date;
			$i_date = new DateTime($issue_date);
			$today = new DateTime();
			$pr_age = $i_date->diff($today);
			$pr_age_year = $pr_age->y;
			$pr_age_month = $pr_age->m;
		}

		if ($immigration_id == 1 || $immigration_id == 2) {

			$ordinary_wage = $g_ordinary_wage;
			if ($ordinary_wage > $ordinary_wage_cap) {
				$ow = $ordinary_wage_cap;
			} else {
				$ow = $ordinary_wage;
			}
			// echo $ow;
			//additional wage
			$additional_wage = $g_additional_wage;
			$aw = $g_additional_wage;
			$tw = $ow + $additional_wage;
			if ($im_status->issue_date != "") {
				if ($pr_age_year == 1) {

					if ($age_year <= 55) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw < 500) {
							$cpf_employer = round(4 / 100 * $tw);
							$cpf_employee = 0;
						} else if ($tw > 500 && $tw < 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
							$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

							if ($count_cpf_employee > 370) {
								$cpf_employee = 370;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							if ($count_total_cpf > 666) {
								$total_cpf = 666;
							} else {
								$total_cpf = $count_total_cpf;
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 55 && $age_year <= 60) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw < 500) {
							$cpf_employer = round(4 / 100 * $tw);
							$cpf_employee = 0;
						} else if ($tw > 500 && $tw < 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
							$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

							if ($count_cpf_employee > 370) {
								$cpf_employee = 370;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							if ($count_total_cpf > 666) {
								$total_cpf = 666;
							} else {
								$total_cpf = $count_total_cpf;
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 60 && $age_year <= 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw < 500) {
							$cpf_employer = round(3.5 / 100 * $tw);
							$cpf_employee = 0;
						} else if ($tw > 500 && $tw < 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
							$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

							if ($count_cpf_employee > 370) {
								$cpf_employee = 370;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							if ($count_total_cpf > 629) {
								$total_cpf = 629;
							} else {
								$total_cpf = $count_total_cpf;
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw < 500) {
							$cpf_employer = round(3.5 / 100 * $tw);
							$cpf_employee = 0;
						} else if ($tw > 500 && $tw < 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
							$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

							if ($count_cpf_employee > 370) {
								$cpf_employee = 370;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							if ($count_total_cpf > 629) {
								$total_cpf = 629;
							} else {
								$total_cpf = $count_total_cpf;
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					}
				}
				if ($pr_age_year == 2) {
					if ($age_year <= 55) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(9 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.45 * ($tw - 500));
							$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
							$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
							if ($count_total_cpf > 1776) {
								$total_cpf = 1776;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 1110) {
								$cpf_employee = 1110;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 55 && $age_year <= 60) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(6 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.375 * ($tw - 500));
							$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
							$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
							if ($count_total_cpf > 1369) {
								$total_cpf = 1369;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 925) {
								$cpf_employee = 925;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 60 && $age_year <= 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(3.5 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.225 * ($tw - 500));
							$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
							$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
							if ($count_total_cpf > 814) {
								$total_cpf = 814;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 555) {
								$cpf_employee = 555;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(3.5 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
							$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
							if ($count_total_cpf > 629) {
								$total_cpf = 629;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 370) {
								$cpf_employee = 370;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					}
				}
				if ($pr_age_year == 3 || $pr_age_year > 3) {
					if ($age_year <= 55) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(17 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.6 * ($tw - 500));
							$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
							$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
							if ($count_total_cpf > 2738) {
								$total_cpf = 2738;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 1480) {
								$cpf_employee = 1480;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = $total_cpf - $cpf_employee;
						}
					} else if ($age_year > 55 && $age_year <= 60) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(15.5 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.51 * ($tw - 500));
							$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
							$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
							if ($count_total_cpf > 2405) {
								$total_cpf = 2405;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 1258) {
								$cpf_employee = 1258;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 60 && $age_year <= 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(12 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.345 * ($tw - 500));
							$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
							$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

							if ($count_total_cpf > 1739) {
								$total_cpf = 1739;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 851) {
								$cpf_employee = 851;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 65 && $age_year <= 70) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(9 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.225 * ($tw - 500));
							$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
							$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

							if ($count_total_cpf > 1221) {
								$total_cpf = 1221;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 555) {
								$cpf_employee = 555;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					} else if ($age_year > 70) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(7.5 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
							$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

							if ($count_total_cpf > 925) {
								$total_cpf = 925;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 370) {
								$cpf_employee = 370;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					}
				}
			}



			if ($immigration_id == 1) {

				if ($age_year <= 55) {
					if ($tw < 50) {
						$cpf_employee = 0;
						$cpf_employer = 0;
					} else if ($tw > 50 && $tw <= 500) {
						$cpf_employee = 0;
						$cpf_employer = round(17 / 100 * $tw);
					} else if ($tw > 500 && $tw <= 750) {
						$cpf_employee = floor(0.6 * ($tw - 500));
						$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
						$cpf_employer = round($total_cpf - $cpf_employee);
					} else if ($tw > 750) {
						$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
						$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
						if ($count_total_cpf > 2738) {
							$count_total_cpf = 2738;
						} else {
							$total_cpf = $count_total_cpf;
						}


						if ($count_cpf_employee > 1480) {
							$cpf_employee = 1480;
						} else {
							$cpf_employee = floor($count_cpf_employee);
						}
						$cpf_employer = round($total_cpf - $cpf_employee);
					}
				} else if ($age_year > 55 && $age_year <= 60) {
					if ($tw < 50) {
						$cpf_employee = 0;
						$cpf_employer = 0;
					} else if ($tw > 50 && $tw <= 500) {
						$cpf_employee = 0;
						$cpf_employer = round(15.5 / 100 * $tw);
					} else if ($tw > 500 && $tw <= 750) {
						$cpf_employee = floor(0.51 * ($tw - 500));
						$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
						$cpf_employer = round($total_cpf - $cpf_employee);
					} else if ($tw > 750) {
						$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;

						$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
						if ($count_total_cpf > 2405) {
							$count_total_cpf = 2405;
						} else {
							$total_cpf = $count_total_cpf;
						}
						if ($count_cpf_employee > 1258) {
							$cpf_employee = 1258;
						} else {
							$cpf_employee = floor($count_cpf_employee);
						}
						$cpf_employer = round($total_cpf - $cpf_employee);
					}
				} else if ($age_year > 60 && $age_year <= 65) {
					if ($tw < 50) {
						$cpf_employee = 0;
						$cpf_employer = 0;
					} else if ($tw > 50 && $tw <= 500) {
						$cpf_employee = 0;
						$cpf_employer = round(12 / 100 * $tw);
					} else if ($tw > 500 && $tw <= 750) {
						$cpf_employee = floor(0.345 * ($tw - 500));
						$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
						$cpf_employer = round($total_cpf - $cpf_employee);
					} else if ($tw > 750) {
						$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
						$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

						if ($count_total_cpf > 1739) {
							$total_cpf = 1739;
						} else {
							$total_cpf = $count_total_cpf;
						}
						if ($count_cpf_employee > 851) {
							$cpf_employee = 851;
						} else {
							$cpf_employee = floor($count_cpf_employee);
						}
						$cpf_employer = round($total_cpf - $cpf_employee);
					}
				} else if ($age_year > 65 && $age_year <= 70) {
					if ($tw < 50) {
						$cpf_employee = 0;
						$cpf_employer = 0;
					} else if ($tw > 50 && $tw <= 500) {
						$cpf_employee = 0;
						$cpf_employer = round(9 / 100 * $tw);
					} else if ($tw > 500 && $tw <= 750) {
						$cpf_employee = floor(0.225 * ($tw - 500));
						$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
						$cpf_employer = round($total_cpf - $cpf_employee);
					} else if ($tw > 750) {
						$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
						$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

						if ($count_total_cpf > 1221) {
							$total_cpf = 1221;
						} else {
							$total_cpf = $count_total_cpf;
						}
						if ($count_cpf_employee > 555) {
							$cpf_employee = 555;
						} else {
							$cpf_employee = floor($count_cpf_employee);
						}
						$cpf_employer = round($total_cpf - $cpf_employee);
					}
				} else if ($age_year > 70) {
					if ($tw < 50) {
						$cpf_employee = 0;
						$cpf_employer = 0;
					} else if ($tw > 50 && $tw <= 500) {
						$cpf_employee = 0;
						$cpf_employer = round(7.5 / 100 * $tw);
					} else if ($tw > 500 && $tw <= 750) {
						$cpf_employee = floor(0.15 * ($tw - 500));
						$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
						$cpf_employer = round($total_cpf - $cpf_employee);
					} else if ($tw > 750) {
						$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
						$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

						if ($count_total_cpf > 925) {
							$total_cpf = 925;
						} else {
							$total_cpf = $count_total_cpf;
						}
						if ($count_cpf_employee > 370) {
							$cpf_employee = 370;
						} else {
							$cpf_employee = floor($count_cpf_employee);
						}
						$cpf_employer = round($total_cpf - $cpf_employee);
					}
				}
			}

			$total_net_salary = $total_net_salary - $cpf_employee;
			$cpf_total = $cpf_employee + $cpf_employer;


			$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
			$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
			$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
		}
	}


	$shg_fund_deduction_amount = 0;
	//Other Fund Contributions
	$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);

	if ($employee_contributions && $g_shg > 0) {
		$gross_s = $g_shg;
		$contribution_id = $employee_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
		$shg_fund_name = $contribution_type[0]->contribution;
		$shg_fund_deduction_amount += $contribution_amount;
		$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
	}
	$ashg_fund_deduction_amount = 0;

	$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
	if ($employee_ashg_contributions  && $g_shg > 0) {
		$gross_s = $g_shg;
		$contribution_id = $employee_ashg_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
		$ashg_fund_name = $contribution_type[0]->contribution;

		$ashg_fund_deduction_amount += $contribution_amount;
		$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
	}


	$sdl_total_amount = 0;
	if ($g_sdl > 1 && $g_sdl <= 800) {
		$sdl_total_amount += 2;
	} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
		$sdl_amount = (0.25 * $g_sdl) / 100;
		$sdl_total_amount += $sdl_amount;
	} elseif ($g_sdl > 4500) {
		$sdl_total_amount += 11.25;
	}


	$net_salary = number_format((float)$total_net_salary, 2, '.', '');
	$basic_salary = number_format((float)$basic_salary, 2, '.', '');



	$half_title = '1';
	if ($system[0]->is_half_monthly == 1) {
		$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($user_id, $this->input->get('pay_date'));
		if ($payment_check->num_rows() > 1) {
			$half_title = '';
		} else if ($payment_check->num_rows() > 0) {
			$half_title = '(' . $this->lang->line('xin_title_second_half') . ')';
		} else {
			$half_title = '(' . $this->lang->line('xin_title_first_half') . ')';
		}
		$half_title = $half_title;
	} else {
		$half_title = '';
	}
	$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
	if (!is_null($_des_name)) {
		$_designation_name = $_des_name[0]->designation_name;
	} else {
		$_designation_name = '';
	}
	$company = $this->Xin_model->read_company_info($company_id);
	// print_r($ow);die;
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><strong><?php echo $this->lang->line('xin_payment_for'); ?></strong>
			<?php echo $half_title; ?> <?php echo $p_month; ?></h4>
	</div>
	<div class="modal-body" style="overflow:auto; height:530px;">

		<?php $attributes = array('name' => 'pay_monthly', 'id' => 'pay_monthly', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
		<?php $hidden = array('_method' => 'ADD'); ?>
		<?php echo form_open('admin/payroll/add_pay_monthly/', $attributes, $hidden); ?>


		<div class="sender-reciver">
			<div>
				<div class="row" style="margin-left:20px;">
					<h4>Employee Detail</h4>
					<table>
						<tr>
							<td style="border: none;">
								<b>Employee Name:</b>
							</td>
							<td style="border: none;">
								<span><?php echo $user[0]->first_name . ' ' . $user[0]->last_name; ?></span>
							</td>

							<td style="border: none;">
								<b>Employee NRIC/FIN:</b>
							</td>
							<td style="border: none;">
								<span> <?php echo $user[0]->id_no; ?></span>
							</td>
						</tr>
						<tr>
							<td style="border: none;">
								<b>Employee DOB:</b>
							</td>
							<td style="border: none;">
								<span><?php echo date('d-m-Y', strtotime($user[0]->date_of_birth)); ?></span>
							</td>


							<td style="border: none;">
								<b>Designation:</b>
							</td>
							<td style="border: none;">
								<span> <?php echo $_designation_name; ?></span>
							</td>
						</tr>
						<tr>
							<td style="border: none;">
								<b>Reference:</b>
							</td>
							<td style="border: none;">
								<span> </span>
							</td>

							<td style="border: none;">
								<b>Date of Joining:</b>
							</td>
							<td style="border: none;">
								<span><?php echo date('d-m-Y', strtotime($user[0]->date_of_joining)); ?></span>
							</td>
						</tr>
					</table>
				</div>

				<div class="row" style="margin-left: 20px;">
					<h4>Payslip Detail</h4>

					<table>

						<tr>
							<td style="border: none;">
								<b>Payslip Month:</b>
							</td>
							<td style="border: none;">
								<span><?php echo $p_month; ?></span>
							</td>

							<td style="border: none;">
								<b>Salary Period:</b>
							</td>
							<td style="border: none;">
								<span><?php echo date("01-m-Y", strtotime('01-' . $p_month)) . ' To ' . date("t-m-Y", strtotime('01-' . $p_month)); ?></span>
							</td>
						</tr>
						<tr>
							<td style="border: none;">
								<b>Payment Status:</b>
							</td>
							<td style="border: none;">
								<span> Pending</span>
							</td>

							<td style="border: none;">
								<b>Payment Date:</b>
							</td>
							<td style="border: none;">
								<span><?php echo date('d-m-Y'); ?> </span>
							</td>
						</tr>
					</table>
				</div>

			</div>
			<hr>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Details</a></li>
					<li><a href="#tabs-2">Allowances & Deductions</a></li>

				</ul>
				<div id="tabs-1">
					<div class="row" style="margin: 5px;">

						<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
						<input type="hidden" name="designation_id" value="<?php echo $designation_id; ?>" />
						<input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
						<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />
						<input type="hidden" id="emp_id" value="<?php echo $user_id ?>" name="emp_id">
						<input type="hidden" id="u_id" value="<?php echo $user_id; ?>" name="u_id">
						<input type="hidden" value="<?php echo $main_salary; ?>" name="basic_salary">
						<input type="hidden" value="<?php echo $wages_type; ?>" name="wages_type">
						<input type="hidden" value="<?php echo $office_shift_id; ?>" name="office_shift_id">
						<input type="hidden" value="<?php echo $this->input->get('pay_date'); ?>" name="pay_date" id="pay_date">
						<input type="hidden" value="<?php echo $ordinary_wage; ?>" name="ow_paid">
						<input type="hidden" value="<?php echo $ow; ?>" name="ow_cpf">
						<input type="hidden" value="<?php echo $ow_cpf_employer; ?>" name="ow_cpf_employer">
						<input type="hidden" value="<?php echo $ow_cpf_employee; ?>" name="ow_cpf_employee">
						<input type="hidden" value="<?php echo $aw; ?>" name="aw_paid">
						<input type="hidden" value="<?php echo $aw; ?>" name="aw_cpf">
						<input type="hidden" value="<?php echo $aw_cpf_employer; ?>" name="aw_cpf_employer">
						<input type="hidden" value="<?php echo $aw_cpf_employee; ?>" name="aw_cpf_employee">
						<input type="hidden" value="<?php echo $emp_dob; ?>" name="emp_dob" id="emp_dob">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_basic_salary'); ?></label>
								<input type="checkbox" name="chk_gross_salary" id="chk_gross_salary" class="bc_checked_class" data-in_id="gross_salary_bc" data-ou_id="gross_salary">
								<input type="text" name="gross_salary" id="gross_salary" class="form-control ontim_data_change" value="<?php echo $show_main_salary; ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="leave_deductions">Unpaid Leave Deduction</label>
								<input type="checkbox" name="chk_leave_deductions" id="chk_leave_deductions" class="bc_checked_class" data-in_id="leave_deductions_bc" data-ou_id="leave_deductions">
								<input type="text" name="leave_deductions" id="leave_deductions" class="form-control ontim_data_change" value="<?php echo $unpaid_leave_amount; ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
								<input type="checkbox" name="chk_total_allowances" id="chk_total_allowances" class="bc_checked_class" data-in_id="total_allowances_bc" data-ou_id="total_allowances">
								<input type="text" name="total_allowances" id="total_allowances" class="form-control ontim_data_change" value="<?php echo $allowance_amount; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
								<input type="checkbox" name="chk_total_commissions" id="chk_total_commissions" class="bc_checked_class" data-in_id="total_commissions_bc" data-ou_id="total_commissions">
								<input type="text" name="total_commissions" id="total_commissions" class="form-control ontim_data_change" value="<?php echo $commissions_amount; ?>">
							</div>
						</div>
					</div>

					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
								<input type="checkbox" name="chk_loan_de_amount" id="chk_loan_de_amount" class="bc_checked_class" data-in_id="loan_de_amount_bc" data-ou_id="total_loan">
								<input type="text" name="total_loan" id="total_loan" class="form-control" value="<?php echo $loan_de_amount; ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
								<input type="checkbox" name="chk_total_overtime" id="chk_total_overtime" class="bc_checked_class" data-in_id="total_overtime_bc" data-ou_id="total_overtime">
								<input type="text" name="total_overtime" class="form-control ontim_data_change" value="<?php echo $overtime_amount; ?>" id="total_overtime">
								<input type="hidden" name="total_overtime_time" value="<?php echo $total_overtime_time ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
								<input type="checkbox" name="chk_total_statutory_deductions" id="chk_total_statutory_deductions" class="bc_checked_class" data-in_id="total_statutory_deductions_bc" data-ou_id="total_statutory_deductions">
								<input type="text" name="total_statutory_deductions" id="total_statutory_deductions" class="form-control" value="<?php echo ($statutory_deductions_amount > 0 ? $statutory_deductions_amount : 0); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
								<input type="checkbox" name="chk_total_other_payments" id="chk_total_other_payments" class="bc_checked_class" data-in_id="total_other_payments_bc" data-ou_id="total_other_payments">
								<input type="text" id="total_other_payments" name="total_other_payments" class="form-control ontim_data_change" value="<?php echo $other_payments_amount; ?>">
							</div>
						</div>
					</div>

					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employee">CPF Employee</label>
								<input type="text" name="total_cpf_employee" id="total_cpf_employee" class="form-control" value="<?php echo (isset($cpf_employee) && $cpf_employee > 0 ? $cpf_employee : 0); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_employee_deduction">Employee Deduction</label>
								<input type="checkbox" name="chk_total_employee_deduction" id="chk_total_employee_deduction" class="bc_checked_class" data-in_id="total_employee_deduction_bc" data-ou_id="total_employee_deduction">
								<input type="text" name="total_employee_deduction" id="total_employee_deduction" class="form-control" value="<?php echo (isset($total_deduction) && $total_deduction > 0 ? $total_deduction : 0); ?>">
							</div>
						</div>

					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf">CPF Employer</label>
								<input type="text" name="total_cpf_employer" id="total_cpf_employer" class="form-control" value="<?php echo (isset($cpf_employer) && $cpf_employer > 0 ? $cpf_employer : 0); ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf">Total CPF</label>
								<input type="text" name="total_cpf" id="total_cpf" class="form-control" value="<?php echo (isset($cpf_total) && $cpf_total > 0 ? $cpf_total : 0); ?>" readonly>
							</div>
						</div>
						<?php
						$total_contribution = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
						?>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employer">Employee Claim</label>
								<input type="checkbox" name="chk_employee_claim" id="chk_employee_claim" class="bc_checked_class" data-in_id="employee_claim_bc" data-ou_id="employee_claim">
								<input type="text" name="employee_claim" id="employee_claim" class="form-control ontim_data_change" value="<?php echo (isset($claim_amount) && $claim_amount > 0 ? $claim_amount : 0); ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="total_fund_contribution">Contribution Funds <small>(MBMF, SINDA, CDAC, ECF)</small></label>
								<input type="text" name="total_fund_contribution" id="total_fund_contribution" class="form-control" value="<?php echo ($total_contribution > 0 ? $total_contribution : 0); ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_share">Share Options Amount</label>
								<input type="checkbox" name="chk_total_share" id="chk_total_share" class="bc_checked_class" data-in_id="total_share_bc" data-ou_id="total_share">
								<input type="text" name="total_share" id="total_share" class="form-control ontim_data_change" value="<?php echo ($share_options_amount > 0 ? $share_options_amount : 0); ?>">
							</div>
						</div>
					</div>

					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_net_salary'); ?></label>
								<input type="text" id="net_salary_s" name="net_salary" class="form-control" value="<?php echo $net_salary; ?>" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
								<input type="text" id="payment_amount_s" name="payment_amount" class="form-control" value="<?= $net_salary ?>" readonly>
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Balance Amount</label>
								<input type="text" id="balance_amount" name="balance_amount" class="form-control" value="0" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">payment mode</label>
								<select class="form-control" name="payment_mode" id="payment_mode">
									<option value="cash">Cash</option>
									<option value="account">Bank</option>
									<option value="Cheque">Cheque</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-12">
							<div class="form-group">
								<span><strong>NOTE:</strong>
									<?php echo $this->lang->line('xin_payroll_total_allowance'); ?>,<?php echo $this->lang->line('xin_hr_commissions'); ?>,<?php echo $this->lang->line('xin_payroll_total_loan'); ?>,<?php echo $this->lang->line('xin_payroll_total_overtime'); ?>,<?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?>,<?php echo $this->lang->line('xin_employee_set_other_payment'); ?>
									are not editable.</span>
							</div>
						</div>
					</div>
				</div>


				<div id="tabs-2">
					<?php
					//echo "<pre>";print_r($shg_fund_deduction_amount);exit;

					$allowance_data = $this->Employees_model->get_employee_allowances($user_id);

					$contribution_data = $this->Employees_model->set_employee_contribution($user_id);

					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
					$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id, $pay_date);

					//$allowances = $this->Employees_model->set_employee_allowances_payslip($payment[0]->payslip_id);
					?>
					<div class="row" style="margin: 5px;">
						<div class="col-md-12">
							<h4>Allowances</h4>
							<?php
							$a = 1;

							foreach ($allowance_data as $allowance) {
								if ($allowance->salary_month == "") {
							?>

									<div class="col-md-12">
										<div class="form-group">
											<label for="name"><?php echo $allowance->allowance_title; ?></label>
											<input type="hidden" id="allowance_type_<?php echo $a; ?>" name="allowance_type[]" class="form-control" value="<?php echo $allowance->allowance_title; ?>">
											<input type="text" id="allowance_amount_<?php echo $a; ?>" name="allowance_amount[]" class="form-control" value="<?php echo $allowance->allowance_amount; ?>">
										</div>
									</div>
									<?php
									$a++;
								} else {
									if ($allowance->salary_month == '01-' . $this->input->get('pay_date')) {
									?>
										<div class="col-md-12">
											<div class="form-group">
												<label for="name"><?php echo $allowance->allowance_title; ?></label>
												<input type="hidden" id="allowance_type_<?php echo $a; ?>" name="allowance_type[]" class="form-control" value="<?php echo $allowance->allowance_title; ?>">
												<input type="text" id="allowance_amount_<?php echo $a; ?>" name="allowance_amount[]" class="form-control" value="<?php echo $allowance->allowance_amount; ?>">
											</div>
										</div>
							<?php
										$a++;
									}
								}
							} ?>


						</div>

					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-12">
							<h4>Commissions</h4>
							<?php
							$a = 1;
							if ($commissions):
								foreach ($commissions as $k => $c): ?>


									<div class="col-md-12">
										<div class="form-group">
											<label for="name"><?php echo $c->payment_deduction_name; ?></label>
											<input type="hidden" id="allowance_type_<?php echo $k; ?>" name="allowance_type[]" class="form-control" value="<?php echo $c->payment_deduction_name; ?>">
											<input type="text" id="allowance_amount_<?php echo $k; ?>" name="allowance_amount[]" class="form-control" value="<?php echo $c->commission_amount; ?>">
										</div>
									</div>

							<?php
								endforeach;
							endif;
							?>


						</div>

					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-12">
							<h4>Claims</h4>
							<?php
							$get_employee_claims = $this->Employees_model->getEmployeeClaim($user_id);
							if ($get_employee_claims):

								foreach ($get_employee_claims->result() as $claims) :
									$date 	= 	date('m-Y', strtotime($claims->date));
									if ($date == $pay_date) :
							?>
										<div class="col-md-12">
											<div class="form-group">
												<label for="name"><?php echo $claims->name; ?></label>
												<input type="text" class="form-control" value="<?php echo $claims->amount; ?>">
											</div>
										</div>

							<?php
									endif;
								endforeach;
							endif;
							?>


						</div>

					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-12">
							<h4>Others Payments</h4>
							<?php
							$a = 1;
							if ($other_payments):
								foreach ($other_payments->result() as $k => $p): ?>


									<div class="col-md-12">
										<div class="form-group">
											<label for="name"><?php echo $p->payments_title; ?></label>
											<input type="hidden" id="allowance_type_<?php echo $k; ?>" name="allowance_type[]" class="form-control" value="<?php echo $p->payments_amount; ?>">
											<input type="text" id="allowance_amount_<?php echo $k; ?>" name="allowance_amount[]" class="form-control" value="<?php echo $p->payments_amount; ?>">
										</div>
									</div>

							<?php
								endforeach;
							endif;
							?>


						</div>

					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-12">
							<h4>Deductions</h4>
							<?php
							$other_benefit = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($user_id, $pay_date);

							foreach ($other_benefit->result() as $benefit) {
							?>
								<div class="col-md-6">
									<label><?php echo $benefit->other_benefit ?> </label>
								</div>
								<div class="col-md-6">
									<input type="text" readonly name="other_benefit_amount[]" class="form-control" value="<?php echo $benefit->other_benefit_cost ?>">

								</div>
							<?php } ?>

							<?php
							$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($user_id, $pay_date);

							foreach ($get_employee_accommodations as $get_employee_accommodation) {
								$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
								$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
								if ($period_from == $pay_date || $period_to == $pay_date) {
							?>
									<div class="col-md-6">
										<label><?php echo $get_employee_accommodation->title ?> </label>
									</div>
									<div class="col-md-6">
										<input type="text" readonly name="employee_accommodation[]" class="form-control" value="<?php echo $get_employee_accommodation->rent_paid ?>">

									</div>
							<?php }
							} ?>

							<?php
							if ($shg_fund_deduction_amount > 0) {
							?>

								<div class="col-md-12" id="shg_fund_deduction_amount">
									<div class="form-group" id="shg_fund_deduction_amount1">
										<label for="name"><?php echo $shg_fund_name; ?></label>
										<input type="hidden" id="loan_title_1" name="loan_title[]" class="form-control" value="<?php echo  $shg_fund_name; ?>">
										<input type="text" id="loan_amount_1" name="loan_amount[]" class="form-control" value="<?php echo $shg_fund_deduction_amount; ?>">
									</div>
								</div>
							<?php
							}

							?>
							<?php
							if ($ashg_fund_deduction_amount > 0) {
							?>

								<div class="col-md-12" id="ashg_fund_deduction_amount">
									<div class="form-group" id="ashg_fund_deduction_amount1">
										<label for="name"><?php echo $ashg_fund_name; ?></label>
										<input type="hidden" id="loan_title_1" name="loan_title[]" class="form-control" value="<?php echo  $ashg_fund_name; ?>">
										<input type="text" id="loan_amount_1" name="loan_amount[]" class="form-control" value="<?php echo $ashg_fund_deduction_amount; ?>">
									</div>
								</div>
							<?php
							}

							?>
							<?php
							if (count($statutory_deductions->result()) > 0) {
								$c = 1;
								foreach ($statutory_deductions->result() as $sd) {
							?>

									<div class="col-md-12">
										<div class="form-group">
											<label for="name"><?php echo $sd->deduction_title; ?></label>
											<input type="hidden" id="statutory_amount_<?php echo $c; ?>" name="statutory_title[]" class="form-control" value="<?php echo $sd->deduction_title; ?>">
											<input type="text" id="statutory_amount_<?php echo $c; ?>" name="statutory_amount[]" class="form-control" value="<?php echo $sd->deduction_amount; ?>">
										</div>
									</div>
									<?php
									$c++;
								}
							}
							if ($employee_deduction) {
								$d = 1;

								foreach ($employee_deduction as $deduction) {
									if ($deduction->type_id == 1) {
									?>
										<div class="col-md-12">
											<div class="form-group">
												<label for="name"><?php echo $deduction->deduction_type; ?></label>
												<input type="hidden" id="deduction_type_<?php echo $d; ?>" name="deduction_type[]" class="form-control" value="<?php echo $deduction->deduction_type; ?>">
												<input type="text" id="deduction_amount_<?php echo $d; ?>" name="deduction_amount[]" class="form-control" value="<?php echo $deduction->amount; ?>">
											</div>
										</div>
									<?php
									}
									if ($deduction->type_id == 2) {
										$from_month_year = date('Y-m', strtotime($deduction->from_date));
										$to_month_year = date('Y-m-d', strtotime($deduction->to_date));


										if ($from_month_year != "" && $to_month_year != "") {
											if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
											}
										}
									?>
										<divm class="col-md-12">
											<div class="form-group">
												<label for="name"><?php echo $deduction->deduction_type; ?></label>
												<input type="hidden" id="deduction_type_<?php echo $d; ?>" name="deduction_type[]" class="form-control" value="<?php echo $deduction->deduction_type; ?>">
												<input type="text" id="deduction_amount_<?php echo $d; ?>" name="deduction_amount[]" class="form-control" value="<?php echo $deduction->amount; ?>">
											</div>
						</div>
			<?php
									}
									$d++;
								}
							}

			?>
					</div>
				</div>
			</div>
			<div class="form-actions box-footer">
				<?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_pay'))); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<script>
		$(function() {
			$("#tabs").tabs();
		});
	</script>

	<script type="text/javascript">
		$("body").on("click", '.ontim_data_change', function() {

		})

		$(document).ready(function() {

			$('.bc_checked_class').click(function() {
				let check_id = [];
				let uncheck_id = [];
				$('.bc_checked_class').each(function(index, data) {
					if ($(this).prop('checked')) {
						check_id.push($(this).prop('id'));
					} else {
						uncheck_id.push($(this).prop('id'));
					}
				})
				// console.log(check_id);
				let check_value = $(this).prop('checked') ? 1 : 0;
				$.ajax({
					url: "check_payment",
					type: 'get',
					dataType: 'json',
					data: {
						check: check_value,
						user_id: "<?php echo $user_id ?>",
						pay_date: "<?php echo $pay_date ?>",
						check_id: check_id,
						uncheck_id: uncheck_id,
					},
					success: function(data) {
						console.log(data)
						$('#leave_deductions').val(data.unpaid_leave_amount);
						$('#total_employee_deduction').val(data.total_deduction);
						$('#total_cpf_employee').val(data.total_cpf_employee);
						$('#total_cpf_employer').val(data.total_cpf_employer);
						$('#total_cpf').val(data.cpf_total);
						$('#total_fund_contribution').val(data.contribution)
						$('#payment_amount_s').val(data.net_salary);
						$('#net_salary_s').val(data.net_salary);
						$('#balance_amount').val(data.balance);
					},
					error: function(error) {
						console.log(error)
					}
				});
			});


			// Initial calculation of payment amount

			// Handle change in checkboxes
			$('.bc_checked_class').prop('checked', true);

			// $('.bc_checked_class').on("click", function(e) {
			// 	// let input_i = $(this).data('in_id');
			// 	// let out_id = $(this).data('ou_id');
			// 	// let out_val = $("#" + out_id).val();
			// 	// let in_val = $("#" + input_i).val();
			// 	// let balance = $("#b_am").val();
			// 	// let net_salary_s = $("#net_salary_s").val();
			// 	// let payment_amount_s = $("#payment_amount_s").val();
			// 	// var isChecked = $(this).is(':checked');
			// 	// if (isChecked) {

			// 	// 	// var sum = parseFloat(net_salary_s) + parseFloat(in_val);
			// 	// 	var sum2 = parseFloat(payment_amount_s) + parseFloat(in_val);
			// 	// 	var sum3 = parseFloat(balance) - parseFloat(in_val);
			// 	// 	// $("#net_salary_s").val(sum.toFixed(2));
			// 	// 	$("#payment_amount_s").val(sum2.toFixed(2));
			// 	// 	// $("#" + out_id).val(in_val)
			// 	// 	$("#b_am").val(sum3.toFixed(2));

			// 	// 	$("#" + input_i).val("0")
			// 	// } else {
			// 	// 	// var sum = parseFloat(net_salary_s) - parseFloat(out_val);
			// 	// 	var sum2 = parseFloat(payment_amount_s) - parseFloat(out_val);
			// 	// 	var sum3 = parseFloat(balance) + parseFloat(out_val);
			// 	// 	// $("#net_salary_s").val(sum.toFixed(2));
			// 	// 	$("#payment_amount_s").val(sum2.toFixed(2));
			// 	// 	$("#b_am").val(sum3.toFixed(2));
			// 	// 	$("#" + input_i).val(out_val)
			// 	// 	// $("#" + out_id).val("0")
			// 	// }

			// 	$(this).each(function(index, data) {
			// 		// console.log($(this).siblings('input').val());
			// 		let amount = $(this).siblings('input').val();
			// 		let net_salary_s = $('#net_salary_s').val();
			// 		let payment_amount_s = $('#payment_amount_s').val();
			// 		let balance_amount = $("#balance_amount").val();
			// 		if ($(this).prop("checked")) {
			// 			$("#balance_amount").val(parseFloat(balance_amount) + parseFloat(amount));
			// 		} else {
			// 			$("#balance_amount").val(parseFloat(payment_amount_s) - parseFloat(amount));
			// 		}

			// 	});




			// })
			// Form submission handling
			$("#pay_monthly").submit(function(e) {
				e.preventDefault();
				var fd = new FormData(this);
				var obj = $(this),
					action = obj.attr('name');

				let check_id = [];
				let uncheck_id = [];
				$('.bc_checked_class').each(function(index, data) {
					if ($(this).prop('checked')) {
						check_id.push($(this).prop('id'));
					} else {
						uncheck_id.push($(this).prop('id'));
					}
				})

				fd.append("is_ajax", 11);
				fd.append("add_type", 'add_monthly_payment');
				fd.append("data", 'monthly');
				fd.append("form", action);
				fd.append("check_id", check_id);

				$.ajax({
					type: "POST",
					url: e.target.action,
					data: fd,
					contentType: false,
					cache: false,
					processData: false,
					success: function(JSON) {
						console.log(JSON);

						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.emo_monthly_pay').modal('toggle');

							var xin_table = $('#xin_table').dataTable({
								"bDestroy": true,
								"ajax": {
									url: "<?php echo site_url("admin/payroll/payslip_list") ?>?employee_id=0&company_id=<?php echo $company_id; ?>&month_year=<?php echo $this->input->get('pay_date'); ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					},
					error: function(eData) {
						console.log(eData);
					}
				});
			});
		});


		// Checkbox updateInputValue

		// function add_extra_amount() {
		// 	var extra_payment = $("#total_other_payments").val();
		// 	var total_amount = $("#payment_amount").val();
		// 	var net_salary = $("#net_salary").val();
		// 	var gross_salary = $("#gross_salary").val();
		// 	var overtime = $("#total_overtime").val();

		// 	if (extra_payment != "") {
		// 		let new_amount = parseFloat(gross_salary) + parseFloat(extra_payment) + parseFloat(overtime);
		// 		$("#payment_amount").val(new_amount);
		// 		$("#net_salary").val(new_amount);

		// 	} else {
		// 		let new_amount = parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(0);
		// 		$("#payment_amount").val(new_amount);
		// 		$("#net_salary").val(new_amount);

		// 		//$("#total_other_payments").val(0);
		// 	}
		// }
	</script>




<?php } ?>