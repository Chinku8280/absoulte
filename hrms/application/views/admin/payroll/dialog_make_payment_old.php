<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payment' && $_GET['type'] == 'monthly_payment_again') { ?>
	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime($this->input->get('pay_date'));
	$pay_date = $this->input->get('pay_date');
	// office shift
	$office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);
	// echo $this->input->get('pay_date');
	$p_month = date('F Y', strtotime('01-' . $this->input->get('pay_date')));
	$pa_month = date('Y-m', strtotime('01-' . $this->input->get('pay_date')));

	if ($wages_type == 1) {
		if ($system[0]->is_half_monthly == 1) {
			//if($half_deduct_month==2){
			$basic_salary = $basic_salary / 2;
			//} else {
			//$basic_salary = $basic_salary;
			//}
		} else {
			$basic_salary = $basic_salary;
		}
	} else {
		$basic_salary = $daily_wages;
	}
	//    rt logic for penchuanng
	// $pay = $this->Employees_model->get_basic_pay($user_id, $pay_date);
	// $appeacial_allowance = $this->Employees_model->get_appeacial_allowance($user_id, $pay_date);
	// $apeacial_allowance_amount = $appeacial_allowance['total_amount'];

	// $overt = $this->Employees_model->get_overtime($user_id, $pay_date);
	// $all_overtime_P = $overt['all_overtime_P'];
	// $all_overtime_t = $overt['all_overtime'];
	// $restday_pay1 = $this->Employees_model->restday_pay($user_id, $pay_date);
	// $get_cliam_data = $this->Employees_model->get_cliam_data($r->user_id, $pay_date);

	// $restday_pay = $restday_pay1['rest_daya_amount'];
	// //    rt logic for penchuanng
	// $LeaveEnchashment = $this->Employees_model->get_leave_Encashment($user_id, $pay_date);
	// $encamoun = $LeaveEnchashment['ench_amount'];




	// $basic_salary = $pay['pay'];











	$user = $this->Xin_model->read_user_info($user_id);
	$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id, $pay_date);

	$deduction_amount = 0;
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
	?>
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


	// new add
	$ordinary_wage = 0;
	$ow = 0;
	$ow_cpf_employer = 0;
	$ow_cpf_employee = 0;
	$aw = 0;
	$aw_cpf_employer = 0;
	$aw_cpf_employee = 0;
	// end new add




	$g_ordinary_wage = 0;
	$g_additional_wage = 0;
	$g_shg = 0;
	$g_sdl = 0;

	$g_ordinary_wage += $basic_salary;
	$g_shg += $basic_salary;
	$g_sdl += $basic_salary;

	// $g_ordinary_wage += $apeacial_allowance_amount;
	// $g_shg += $apeacial_allowance_amount;
	// $g_sdl += $apeacial_allowance_amount;

	$g_ordinary_wage -= $deduction_amount;
	$g_shg -= $deduction_amount;
	$g_sdl -= $deduction_amount;



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
	// $allowance_amount += $apeacial_allowance_amount;


	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	// $month_start_date = new DateTime($pay_date . '-01');
	$month_start_date = new DateTime('01-' . $pay_date);
	$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
	$month_end_date->modify('+1 day');
	$interval = new DateInterval('P1D');
	$holiday_array_new = array();
	$period = new DatePeriod($month_start_date, $interval, $month_end_date);
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
			// $pay_date_month = new DateTime($pay_date . '-01');
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
			// if(count($leave_period) > 0) {
			// 	if($l->is_half_day == 0) {
			// 		$leaves_taken_count += count($leave_period);
			// 	}else {
			// 		$leaves_taken_count += count($leave_period)  / 2;
			// 	}
			// }
		}
	}

	$no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count)) + $holidays_count;
	$gross_pay = round((($basic_salary + $gross_allowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
	$unpaid_leave_amount = ($basic_salary + $gross_allowance_amount) - $gross_pay;

	// echo 'Working days : '. $no_of_working_days . '<br>';
	// echo 'Holidays : '. $holidays_count . '<br>';
	// echo 'Leaves : '. $leaves_taken_count . '<br>';
	// echo 'Leave Days : ('. implode(", " , $leave_period)  . ')<br>';
	// echo 'Days Worked : '. $no_of_days_worked . '<br>';
	// echo 'Basic Pay : '. $g_ordinary_wage . '<br>';
	// echo 'Gross Pay : '. $gross_pay . '<br>';
	$g_ordinary_wage -= $unpaid_leave_amount;
	// echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
	// echo '<hr>';

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
	// 4: other payment
	$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
	$other_payments_amount = 0;
	if (!is_null($other_payments)) :
		foreach ($other_payments->result() as $sl_other_payments) {
			if ($system[0]->is_half_monthly == 1) {
				if ($system[0]->half_deduct_month == 2) {
					$epayments_amount = $sl_other_payments->payments_amount / 2;
				} else {
					$epayments_amount = $sl_other_payments->payments_amount;
				}
			} else {
				$epayments_amount = $sl_other_payments->payments_amount;
			}
			$other_payments_amount += $epayments_amount;
		}
	endif;


	// 5: commissions
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
	// print_r($share_options);exit;


	// 6: statutory deductions
	$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
	if (!is_null($statutory_deductions)) :
		$statutory_deductions_amount = 0;
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $basic_salary;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				//$statutory_deductions_amount += $st_amount;
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

	// 7: overtime
	// $salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
	// $count_overtime = $this->Employees_model->count_employee_overtime($user_id);
	// $overtime_amount = 0;
	// if($count_overtime > 0) {
	// 	foreach($salary_overtime as $sl_overtime){
	// 		if($system[0]->is_half_monthly==1){
	// 			if($system[0]->half_deduct_month==2){
	// 				$eovertime_hours = $sl_overtime->overtime_hours/2;
	// 				$eovertime_rate = $sl_overtime->overtime_rate/2;
	// 			} else {
	// 				$eovertime_hours = $sl_overtime->overtime_hours;
	// 				$eovertime_rate = $sl_overtime->overtime_rate;
	// 			}
	// 		} else {
	// 			$eovertime_hours = $sl_overtime->overtime_hours;
	// 			$eovertime_rate = $sl_overtime->overtime_rate;
	// 		}
	// 		$overtime_amount += $eovertime_hours * $eovertime_rate;
	// 		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
	// 		//$overtime_amount += $overtime_total;
	// 	}
	// } else {
	// 	$overtime_amount = 0;
	// }
	$overtime_amount = 0;
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
			$week_hours = 0;
			if ($office_shift_id) {
				$shift = $this->Employees_model->read_shift_information($office_shift_id);
				if ($shift) {
					if ($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						$time1 = $shift[0]->monday_in_time;
						$time2 = $shift[0]->monday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						$time1 = $shift[0]->tuesday_in_time;
						$time2 = $shift[0]->tuesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						$time1 = $shift[0]->wednesday_in_time;
						$time2 = $shift[0]->wednesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						$time1 = $shift[0]->thursday_in_time;
						$time2 = $shift[0]->thursday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						$time1 = $shift[0]->friday_in_time;
						$time2 = $shift[0]->friday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						$time1 = $shift[0]->saturday_in_time;
						$time2 = $shift[0]->saturday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						$time1 = $shift[0]->sunday_in_time;
						$time2 = $shift[0]->sunday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
				}
			} else {
				$week_hours = 40;
			}
			$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
		}
		if ($ot_hrs > 0) {
			$overtime_amount = round($ot_hrs * $rate, 2);
		}
		// $overtime_amount = $all_overtime_P;
		if ($system[0]->is_half_monthly == 1) {
			if ($system[0]->half_deduct_month == 2) {
				$overtime_amount = $overtime_amount / 2;
			}
		}
	}
	$g_ordinary_wage += $overtime_amount;
	$g_ordinary_wage += $other_payments_amount;

	// all other payment
	$all_other_payment = $other_payments_amount + $share_options_amount;

	$gross_salary = $basic_salary + $allowance_amount + $commissions_amount + $overtime_amount + $all_other_payment;
	// add amount
	$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount;
	// add amount
	$net_salary_default = $add_salary - floatval($loan_de_amount) - $statutory_deductions_amount - $unpaid_leave_amount - $deduction_amount;
	$sta_salary = $allowance_amount + $basic_salary;

	$estatutory_deductions = $statutory_deductions_amount;
	// net salary + statutory deductions
	$net_salary = $net_salary_default;
	// print_r($all_overtime_P);die;

	/**
	 * Author : Syed Anees
	 * Sub Functionality : CPF on Gross Salary
	 */
	$emp_dob = $dob;
	$dob = new DateTime($emp_dob);
	// $today = new DateTime($this->input->get('pay_date'));
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
	if ($age_year < $age_upto) {
		$age_from = null;
		$age_to = $age_year;
	} elseif ($age_year > $age_above) {
		$age_from = $age_year;
		$age_to = null;
	} elseif ($age_year > $age_upto && $age_year <= $age_above) {
		$age_from = $age_year;
		$age_to = $age_year;
	} else {
		$age_from = null;
		$age_to = null;
	}

	$im_status = $this->Employees_model->getEmployeeImmigrationStatus($user_id);
	$cpf_contribution = '';
	if ($im_status) {
		$immigration_id = $im_status->immigration_id;
		if ($immigration_id == 2) {
			$issue_date = $im_status->issue_date;
			$i_date = new DateTime($issue_date);
			$today = new DateTime();
			$pr_age = $i_date->diff($today);
			$pr_age_year = $pr_age->y;
			$pr_age_month = $pr_age->m;
			//echo $pr_age_year;exit;
			// if($pr_age_year == 0 && $pr_age_month > 0) {
			if ($pr_age_year == 0 && ($pr_age_month > 0 || $pr_age_month == 0)) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} else if ($pr_age_year == 1 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} elseif ($pr_age_year == 1 && $pr_age_month > 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			} elseif ($pr_age_year == 2 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			}
			// elseif($pr_age_year >= 2) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,1);
			// }
			elseif ($pr_age_year >= 2) {
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
			}
		} elseif ($immigration_id == 1) {
			// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
			$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
		}
		if ($immigration_id == 1 || $immigration_id == 2) {
			if ($cpf_contribution) {
				$employee_contribution = $cpf_contribution->contribution_employee;
				$employer_contribution = $cpf_contribution->contribution_employer;
				$total_cpf_contribution = $cpf_contribution->total_cpf;

				// $ordinary_wage = ($allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount ) - (floatval($loan_de_amount) + $statutory_deductions_amount);
				$ordinary_wage = $g_ordinary_wage;

				if ($ordinary_wage > $ordinary_wage_cap) {
					$ow = $ordinary_wage_cap;
				} else {
					$ow = $ordinary_wage;
				}

				// $cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
				// $ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
				// $ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;


				// $aw = $g_additional_wage;
				// $cpf_total_aw = round(($total_cpf_contribution * $aw) / 100);
				// $aw_cpf_employee = floor(($employee_contribution * $aw) / 100);
				// $aw_cpf_employer = $cpf_total_aw - $aw_cpf_employee;


				// $cpf_employee = $ow_cpf_employee + $aw_cpf_employee;
				// $cpf_employer = $ow_cpf_employer + $aw_cpf_employer;



				// $cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				// $cpf_employer = number_format((float)$cpf_employer, 2, '.', '');

				/*immigration id 1 cpf count start */
				if ($immigration_id == 1) {
					$tw = $aw + $ow;
					if ($age_year <= 55) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round((17 / 100 * $tw) - $cpf_employee);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.6 * ($tw - 500));
							$cpf_employer = round((17 / 100 * $tw) + ($cpf_employee) - $cpf_employee);
						} else if ($tw > 750) {
							$cpf_employee = floor(20 / 100 * $ow + 20 / 100 * $aw);
							$cpf_employer = round((37 / 100 * $ow + 37 / 100 * $aw) - $cpf_employee);
							// if ($count_cpf_employer > 2220) {
							// 	$cpf_employer = 2220;
							// } else {
							// 	$cpf_employer = $count_cpf_employer;
							// }
							// if ($count_cpf_employee > 1200) {
							// 	$cpf_employee = 1200;
							// } else {
							// 	$cpf_employee = $count_cpf_employee;
							// }
						}
					} else if ($age_year > 55 && $age_year <= 60) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round((15 / 100 * $tw) - $cpf_employee);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.48 * ($tw - 500));
							$cpf_employer = round((15 / 100 * ($tw) + 0.48 * ($tw - 500)) - $cpf_employee);
							// print_r($cpf_employer);
						} else if ($tw > 750) {
							$cpf_employee = floor(16 / 100 * $tw);
							// + 15 / 100 * $aw;
							$cpf_employer = round(31 / 100 * ($tw) - $cpf_employee);
							// + 29.5 / 100 * ($aw);
							// if ($count_cpf_employer > 1770) {
							// 	$cpf_employer = 1770;
							// } else {
							// 	$cpf_employer = $count_cpf_employer;
							// }
							// if ($count_cpf_employee > 900) {
							// 	$cpf_employee = 900;
							// } else {
							// 	$cpf_employee = $count_cpf_employee;
							// }
						}
					} else if ($age_year > 60 && $age_year <= 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(11 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee = floor(0.285 * ($tw - 500));
							$cpf_employer = round((11 / 100 * $tw + 0.285 * ($tw - 500)) - $cpf_employee);
						} else if ($tw > 750) {
							$cpf_employee = floor(10.5 / 100 * $tw);
							$cpf_employer = round((22 / 100 * $tw) - $cpf_employee);

							// if ($count_cpf_employer > 1230) {
							// 	$cpf_employer = 1230;
							// } else {
							// 	$cpf_employer = $count_cpf_employer;
							// }
							// if ($count_cpf_employee > 570) {
							// 	$cpf_employee = 570;
							// } else {
							// 	$cpf_employee = $count_cpf_employee;
							// }
						}
					} else if ($age_year > 65 && $age_year <= 70) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(9 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							// (9% x $750.00) + 0.225($750.00 - $500.00);

							$cpf_employee = floor(0.225 * ($tw - 500));
							$cpf_employer = round((9 / 100 * $tw + 0.225 * ($tw - 500)) - $cpf_employee);
						} else if ($tw > 750) {
							$cpf_employee = floor(7.5 / 100 * $tw);
							$cpf_employer = round((16.5 / 100 * $tw) - $cpf_employee);

							// if ($count_cpf_employer > 930) {
							// 	$cpf_employer = 930;
							// } else {
							// 	$cpf_employer = $count_cpf_employer;
							// }
							// if ($count_cpf_employee > 420) {
							// 	$cpf_employee = 420;
							// } else {
							// 	$cpf_employee = $count_cpf_employee;
							// }
						}
					} else if ($age_year > 70) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(7.5 / 100 * $tw);
						} else if ($tw > 500 && $tw <= 750) {
							$cpf_employee =  floor(0.15 * ($tw - 500));
							$cpf_employer = round((7.5 / 100 * $tw) + 0.15 * ($tw - 500) - $cpf_employee);
						} else if ($tw > 750) {

							$cpf_employee = floor(5 / 100 * $tw);
							$cpf_employer = round((12.5 / 100 * $tw) - $cpf_employee);

							// if ($count_cpf_employer > 750) {
							// 	$cpf_employer = 750;
							// } else {
							// 	$cpf_employer = $count_cpf_employer;
							// }
							// if ($count_cpf_employee > 300) {
							// 	$cpf_employee = 300;
							// } else {
							// 	$cpf_employee = $count_cpf_employee;
							// }
						}
					}
				}

				/* immigration id 1 cpf count end */
				if ($im_status->issue_date) {
					$issue_date = $im_status->issue_date;
					if ($issue_date != "") {
						$i_date = new DateTime($issue_date);

						$today = new DateTime();
						$pr_age = $i_date->diff($today);
						$pr_age_year = $pr_age->y;
						$pr_age_month = $pr_age->m;
						/* new CPF calculation Start*/
						$tw = $ow + $aw;
						if ($pr_age_year == 1) {

							if ($age_year <= 55) {
								if ($tw > 50 && $tw < 500) {
									$cpf_employee = 0;
									$cpf_employer = round(4 / 100 * $tw);
								} else if ($tw > 500 && $tw < 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$cpf_employer = round((4 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor((5 / 100) * $ow + (5 / 100) * $aw);
									$cpf_employer = round(((9 / 100) * $ow + (9 / 100) * $aw) - $cpf_employee);

									// if ($count_cpf_employee > 300) {
									// 	$cpf_employee = 300;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
									// if ($count_cpf_employer > 540) {
									// 	$cpf_employer = 540;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw > 50 && $tw < 500) {
									$cpf_employee = 0;
									$cpf_employer = round(4 / 100 * $tw);
								} else if ($tw > 500 && $tw < 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$cpf_employer = round((4 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor((5 / 100) * $ow + (5 / 100) * $aw);
									$cpf_employer = round(((9 / 100) * $ow + (9 / 100) * $aw) - $cpf_employee);

									// if ($count_cpf_employee > 300) {
									// 	$cpf_employee = 300;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
									// if ($count_cpf_employer > 540) {
									// 	$cpf_employer = 540;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw > 50 && $tw < 500) {
									$cpf_employee = 0;
									$cpf_employer = round(3.5 / 100 * $tw);
								} else if ($tw > 500 && $tw < 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$cpf_employer = round(3.5 / 100 * $tw + 0.15 * ($tw - 500));
								} else if ($tw > 750) {
									$cpf_employee = floor(5 / 100 * $tw);
									$cpf_employer = round(((8.5 / 100) * $tw) - $cpf_employee);

									// if ($count_cpf_employee > 300) {
									// 	$cpf_employee = 300;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
									// if ($count_cpf_employer > 510) {
									// 	$cpf_employer = 510;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
								}
							} else if ($age_year > 65) {
								if ($tw > 50 && $tw < 500) {
									$cpf_employee = 0;
									$cpf_employer = round(3.5 / 100 * $tw);
								} else if ($tw > 500 && $tw < 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$cpf_employer = round((3.5 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor((5 / 100) * $ow + (5 / 100) * $aw);
									$cpf_employer = round(((8.5 / 100) * $ow + (8.5 / 100) * $aw) - $cpf_employee);

									// if ($count_cpf_employee > 300) {
									// 	$cpf_employee = 300;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
									// if ($count_cpf_employer > 510) {
									// 	$cpf_employer = 510;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
								}
							}
						}
						if ($pr_age_year == 2) {
							if ($age_year <= 55) {

								if ($tw <= 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0 * $tw;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.45 * ($tw - 500));
									$cpf_employer = round((9 / 100 * $tw + 0.45 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(15 / 100 * $tw);
									$cpf_employer = round(24 / 100 * $tw) - $cpf_employee;
									// if ($count_cpf_employer > 1440) {
									// 	$cpf_employer = 1440;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 900) {
									// 	$cpf_employee = 900;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw <= 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(6 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.375 * ($tw - 500));
									$cpf_employer = round((6 / 100 * $tw + 0.375 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(7.5 / 100 * $tw);
									$cpf_employer = round((11 / 100 * $tw) - $cpf_employee);
									// if ($count_cpf_employer > 1110) {
									// 	$cpf_employer = 1110;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 750) {
									// 	$cpf_employee = 750;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw <= 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(3.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.225 * ($tw - 500));
									$cpf_employer = round((3.5 / 100 * $tw + 0.225 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(7.5 / 100 * $tw);
									$cpf_employer = round((11 / 100 * $tw) - $cpf_employee);
									// if ($count_cpf_employer > 1110) {
									// 	$cpf_employer = 1110;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 750) {
									// 	$cpf_employee = 750;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 65) {
								if ($tw <= 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(3.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$cpf_employer = round((3.5 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(5 / 100 * $tw);
									$cpf_employer = round((8.5 / 100 * $tw) - $cpf_employee);
									// if ($count_cpf_employer > 1110) {
									// 	$cpf_employer = 1110;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 750) {
									// 	$cpf_employee = 750;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
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
									$cpf_employer = round((17 / 100 * $tw) - $cpf_employee);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.6 * ($tw - 500));
									$cpf_employer = round((17 / 100 * $tw) + ($cpf_employee) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(20 / 100 * $ow + 20 / 100 * $aw);
									$cpf_employer = round((37 / 100 * $ow + 37 / 100 * $aw) - $cpf_employee);
									// if ($count_cpf_employer > 2220) {
									// 	$cpf_employer = 2220;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 1200) {
									// 	$cpf_employee = 1200;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round((15 / 100 * $tw) - $cpf_employee);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.48 * ($tw - 500));
									$cpf_employer = round((15 / 100 * ($tw) + 0.48 * ($tw - 500)) - $cpf_employee);
									// print_r($cpf_employer);
								} else if ($tw > 750) {
									$cpf_employee = floor(16 / 100 * $tw);
									// + 15 / 100 * $aw;
									$cpf_employer = round(31 / 100 * ($tw) - $cpf_employee);
									// + 29.5 / 100 * ($aw);
									// if ($count_cpf_employer > 1770) {
									// 	$cpf_employer = 1770;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 900) {
									// 	$cpf_employee = 900;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(11 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.285 * ($tw - 500));
									$cpf_employer = round((11 / 100 * $tw + 0.285 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(10.5 / 100 * $tw);
									$cpf_employer = round((22 / 100 * $tw) - $cpf_employee);

									// if ($count_cpf_employer > 1230) {
									// 	$cpf_employer = 1230;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 570) {
									// 	$cpf_employee = 570;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 65 && $age_year <= 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									// (9% x $750.00) + 0.225($750.00 - $500.00);

									$cpf_employee = floor(0.225 * ($tw - 500));
									$cpf_employer = round((9 / 100 * $tw + 0.225 * ($tw - 500)) - $cpf_employee);
								} else if ($tw > 750) {
									$cpf_employee = floor(7.5 / 100 * $tw);
									$cpf_employer = round((16.5 / 100 * $tw) - $cpf_employee);

									// if ($count_cpf_employer > 930) {
									// 	$cpf_employer = 930;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 420) {
									// 	$cpf_employee = 420;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							} else if ($age_year > 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(7.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee =  floor(0.15 * ($tw - 500));
									$cpf_employer = round((7.5 / 100 * $tw) + 0.15 * ($tw - 500) - $cpf_employee);
								} else if ($tw > 750) {

									$cpf_employee = floor(5 / 100 * $tw);
									$cpf_employer = round((12.5 / 100 * $tw) - $cpf_employee);

									// if ($count_cpf_employer > 750) {
									// 	$cpf_employer = 750;
									// } else {
									// 	$cpf_employer = $count_cpf_employer;
									// }
									// if ($count_cpf_employee > 300) {
									// 	$cpf_employee = 300;
									// } else {
									// 	$cpf_employee = $count_cpf_employee;
									// }
								}
							}
						}
					}
				}

				/* new CPF calculation End*/
				$net_salary = $net_salary - $cpf_employee;
				$cpf_total = $cpf_employee + $cpf_employer;

				// $net_salary = number_format((float)$net_salary, 2, '.', '');
				$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
			}
		} else {
			$ordinary_wage = $g_ordinary_wage;
			$ow = $ordinary_wage;
			$ow_cpf_employee = 0;
			$ow_cpf_employer = 0;

			$aw = $g_additional_wage;
			$aw_cpf_employee = 0;
			$aw_cpf_employer = 0;
			$cpf_employer = 0;
			$cpf_employee = 0;
			$cpf_total = 0;
		}
	}
	// echo $emp_dob. ' Date of Birth <br>';
	// echo $age_month . ' Age Month <br>';
	// echo $age_year . ' Age year <br>';
	// echo $age_upto . ' Age Upto <br>';
	// echo $age_above . ' Age Above <br>';
	// echo $age_from . ' Age from <br>';
	// echo $age_to . ' Age To <br>';
	// exit;


	//leave deduction
	// $net_salary = $net_salary - $ashg_fund_deduction_amount;

	$shg_fund_deduction_amount = 0;
	//Other Fund Contributions
	$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);


	if ($employee_contributions) {
		$gross_s = $g_shg;
		$contribution_id = $employee_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
		$shg_fund_name = $contribution_type[0]->contribution;
		$shg_fund_deduction_amount += $contribution_amount;
		$net_salary = $net_salary - $shg_fund_deduction_amount;
	}
	$ashg_fund_deduction_amount = 0;

	$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
	if ($employee_ashg_contributions) {
		$gross_s = $g_shg;

		$contribution_id = $employee_ashg_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
		$ashg_fund_name = $contribution_type[0]->contribution;

		$ashg_fund_deduction_amount += $contribution_amount;
		$net_salary = $net_salary - $ashg_fund_deduction_amount;
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
	// $net_salary = $net_salary - $sdl_total_amount;

	$fund_deduction_amount = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
	// $net_salary = round($net_salary, 2);

	// check
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
	$backup_data = $this->db->where('user_id', $user_id)->order_by('id', 'DESC')->limit(1)->get('salary_backup_table')->row();

	$net_salary = (float)$backup_data->total_allowances + (float) $backup_data->gross_salary + (float) $backup_data->General_amount + (float) $backup_data->motivation_amount - (float)$backup_data->total_loan - (float)$backup_data->total_employee_deduction + (float)$backup_data->employee_claim + (float) $backup_data->total_overtime - (float)$backup_data->leave_deductions + (float)$backup_data->total_commissions + (float)$backup_data->total_statutory_deductions + (float)$backup_data->total_other_payments + (float) $backup_data->total_share + (float)$backup_data->additional_allowances - (float) $backup_data->deduction_amount;
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
		<?php echo form_open('admin/payroll/add_pay_monthly_again/', $attributes, $hidden); ?>

		<!-- <div style="display: flex; justify-content: space-between;">
				<div class="left">
					<h3><img src="<?php //echo base_url().'uploads/company/'.$company[0]->logo; 
									?>" height="100px" width="100px"></h3>
				</div>

				<div class="right">
					<h3><?php //echo $company[0]->name; 
						?></h3>
					
				</div>
			</div> -->

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
			<div id="tabs_rk">
				<ul>
					<li><a href="#tabs_rk-1">Details</a></li>
					<li><a href="#tabs_rk-2">Allowances & Deductions</a></li>

				</ul>
				<div id="tabs_rk-1">
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
								<input type="hidden" name="designation_id" value="<?php echo $designation_id; ?>" />
								<input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
								<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />
								<label for="name"><?php echo $this->lang->line('xin_payroll_basic_salary'); ?></label>
								<input type="checkbox" <?= ($backup_data->gross_salary > 0) ? 'checked' : 'disabled' ?> name="chk_gross_salary" id="chk_gross_salary" class="bc_checked_class" data-in_id="gross_salary_bc" data-ou_id="gross_salary">
								<input type="hidden" name="gross_salary_bc" id="gross_salary_bc">

								<input type="text" name="gross_salary" id="gross_salary" class="form-control " value="<?php echo $basic_salary; ?>" <?= ($backup_data->gross_salary > 0) ? '' : 'disabled' ?>>
								<input type="hidden" id="emp_id" value="<?php echo $user_id ?>" name="emp_id">
								<input type="hidden" id="u_id" value="<?php echo $user_id; ?>" name="u_id">
								<input type="hidden" value="<?php echo $basic_salary ?? 0; ?>" name="basic_salary">
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
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="leave_deductions">Leave Deductions</label>
								<input type="checkbox" name="chk_leave_deductions" <?= ($backup_data->leave_deductions > 0) ? 'checked' : 'disabled' ?> id="chk_leave_deductions" class="bc_checked_class" data-in_id="leave_deductions_bc" data-ou_id="leave_deductions">
								<input type="hidden" name="leave_deductions_bc" id="leave_deductions_bc">
								<input type="text" name="leave_deductions" id="leave_deductions" class="form-control" value="<?php echo $unpaid_leave_amount; ?>" <?= ($backup_data->leave_deductions > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
								<input type="checkbox" <?= ($backup_data->total_allowances > 0) ? 'checked' : 'disabled' ?> name="chk_total_allowances" id="chk_total_allowances" class="bc_checked_class" data-in_id="total_allowances_bc" data-ou_id="total_allowances">
								<input type="hidden" name="total_allowances_bc" id="total_allowances_bc">
								<input type="text" name="total_allowances" id="total_allowances" class="form-control ontim_data_change" value="<?php echo $allowance_amount; ?>" <?= ($backup_data->total_allowances > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
								<input type="checkbox" name="chk_total_commissions" <?= ($backup_data->total_commissions > 0) ? 'checked' : 'disabled' ?> id="chk_total_commissions" class="bc_checked_class" data-in_id="total_commissions_bc" data-ou_id="total_commissions">
								<input type="hidden" name="total_commissions_bc" id="total_commissions_bc">
								<input type="text" name="total_commissions" id="total_commissions" class="form-control ontim_data_change" value="<?php echo $commissions_amount; ?>" <?= ($backup_data->total_commissions > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;display:none">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">General Allowance</label>
								<input type="checkbox" name="chk_General_amount" id="chk_General_amount" class="bc_checked_class" data-in_id="General_amount_bc" data-ou_id="General_amount" <?= ($backup_data->General_amount > 0) ? 'checked' : 'disabled' ?>>
								<input type="hidden" name="General_amount_bc" id="General_amount_bc">
								<input type="text" id="General_amount" name="General_amount" class="form-control" value="" <?= ($backup_data->General_amount > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Motivation Incentive</label>
								<input type="checkbox" name="chk_motivation_amount" id="chk_motivation_amount" class="bc_checked_class" data-in_id="motivation_amount_bc" data-ou_id="motivation_amount" <?= ($backup_data->motivation_amount > 0) ? 'checked' : 'disabled' ?>>
								<input type="hidden" name="motivation_amount_bc" id="motivation_amount_bc">
								<input type="text" id="motivation_amount" name="motivation_amount" class="form-control" value="" <?= ($backup_data->motivation_amount > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
								<input type="checkbox" name="chk_total_loan" id="chk_total_loan" <?= ($backup_data->total_loan > 0) ? 'checked' : 'disabled' ?> class="bc_checked_class" data-in_id="total_loan_bc" data-ou_id="total_loan">
								<input type="hidden" name="total_loan_bc" id="total_loan_bc">
								<input type="text" name="total_loan" id="total_loan" class="form-control" value="<?php echo $loan_de_amount; ?>" <?= ($backup_data->total_loan > 0) ? '' : 'disabled' ?>>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
								<input type="checkbox" name="chk_total_overtime" id="chk_total_overtime" class="bc_checked_class" data-in_id="total_overtime_bc" data-ou_id="total_overtime" <?= ($backup_data->total_overtime > 0) ? 'checked' : 'disabled' ?>>
								<input type="hidden" name="total_overtime_bc" id="total_overtime_bc">
								<!-- <input type="text" name="total_overtime" id="total_overtime" class="form-control" value="<?= $all_overtime_t ?>" onkeyup="change_paid_amount();"> -->
								<input type="text" name="total_overtime" class="form-control " <?= ($backup_data->total_overtime > 0) ? 'checked' : 'disabled' ?> value="<?php echo $overtime_amount; ?>" id="total_overtime" <?= ($backup_data->total_overtime > 0) ? '' : 'disabled' ?>>
								<input type="hidden" name="total_overtime_amount" class="form-control" value="<?php echo $overtime_amount; ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
								<input type="checkbox" name="chk_total_statutory_deductions" <?= ($backup_data->total_statutory_deductions > 0) ? 'checked' : 'disabled' ?> id="chk_total_statutory_deductions" class="bc_checked_class" data-in_id="total_statutory_deductions_bc" data-ou_id="total_statutory_deductions">
								<input type="hidden" name="total_statutory_deductions_bc" id="total_statutory_deductions_bc">
								<input type="text" name="total_statutory_deductions" id="total_statutory_deductions" class="form-control" value="<?php echo $estatutory_deductions; ?>" <?= ($backup_data->total_statutory_deductions > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
								<input type="checkbox" name="chk_total_other_payments" <?= ($backup_data->total_other_payments > 0) ? 'checked' : 'disabled' ?> id="chk_total_other_payments" class="bc_checked_class" data-in_id="total_other_payments_bc" data-ou_id="total_other_payments">
								<input type="hidden" name="total_other_payments_bc" id="total_other_payments_bc">
								<input type="text" id="total_other_payments" name="total_other_payments" class="form-control ontim_data_change" value="<?php echo $all_other_payment; ?>" <?= ($backup_data->total_other_payments > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>

					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employee">CPF Employee</label>

								<input type="text" name="total_cpf_employee" id="total_cpf_employee" class="form-control" value="<?= $cpf_employee ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_employee_deduction">Employee Deduction</label>
								<input class="bc_checked_class" type="checkbox" <?= ($backup_data->deduction_amount > 0) ? 'checked' : 'disabled' ?> name="chk_total_employee_deduction" id="chk_total_employee_deduction" data-in_id="total_employee_deduction_bc" data-ou_id="total_employee_deduction">
								<input type="hidden" name="total_employee_deduction_bc" id="total_employee_deduction_bc">
								<input type="text" name="total_employee_deduction" id="total_employee_deduction" class="form-control" value="<?php echo $deduction_amount; ?>" <?= ($backup_data->deduction_amount > 0) ? '' : 'disabled' ?>>
							</div>
						</div>

					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf">CPF Employer</label>

								<input type="text" name="total_cpf_employer" id="total_cpf_employer" class="form-control" value="<?= $cpf_employer ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf">Total CPF</label>
								<!-- <input type="checkbox" name="chk_total_cpf" id="chk_total_cpf"> -->
								<input type="text" name="total_cpf" id="total_cpf" class="form-control" value="<?= $cpf_total ?>" readonly>
							</div>
						</div>
						<?php
						$total_contribution = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
						?>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employer">Employee Claim</label>
								<input type="checkbox" <?= ($backup_data->employee_claim > 0) ? 'checked' : 'disabled' ?> name="chk_leave_deductions" id="chk_leave_deductions" class="bc_checked_class" data-in_id="employee_claim_bc" data-ou_id="employee_claim">
								<input type="hidden" name="employee_claim_bc" id="employee_claim_bc">
								<input type="text" name="employee_claim" id="employee_claim" class="form-control ontim_data_change" value="<?php echo $claim_amount; ?>" <?= ($backup_data->employee_claim > 0) ? '' : 'disabled' ?>>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="total_fund_contribution">Contribution Funds <small>(MBMF, SINDA, CDAC, ECF)</small></label>
								<input type="text" name="total_fund_contribution" id="total_fund_contribution" class="form-control" value="">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_share">Share Options Amount</label>
								<input type="checkbox" <?= ($backup_data->total_share > 0) ? 'checked' : '' ?> name="chk_leave_deductions" id="chk_total_share" class="bc_checked_class" data-in_id="total_share_bc" data-ou_id="total_share">
								<input type="hidden" name="total_share_bc" id="total_share_bc">
								<input type="text" name="total_share" id="total_share" class="form-control ontim_data_change" value="<?php echo $share_options_amount; ?>" <?= ($backup_data->total_share > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Additional Allowance</label>
								<input type="checkbox" <?= ($backup_data->additional_allowances > 0) ? 'checked' : 'disabled' ?> name="chk_leave_deductions" id="chk_leave_deductions" class="bc_checked_class" data-in_id="additional_allowances_bc" data-ou_id="additional_allowances">
								<input type="hidden" name="additional_allowances_bc" id="additional_allowances_bc">
								<input type="text" name="additional_allowances" id="additional_allowances" class="form-control ontim_data_change" value="<?php echo $gross_allowance_amount; ?>" <?= ($backup_data->additional_allowances > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>

					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_net_salary'); ?></label>
								<input type="text" id="net_salary_s" name="net_salary" class="form-control" value="<?php echo $backup_data->net_salary; ?>" readonly>
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
								<input type="text" id="b_am" name="b_am" class="form-control" value="0" readonly>
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


				<div id="tabs_rk-2">
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
			$("#tabs_rk").tabs();
		});
	</script>

	<script type="text/javascript">
		$('.bc_checked_class').on("click", function(e) {
			let input_i = $(this).data('in_id');
			let out_id = $(this).data('ou_id');

			let out_val = $("#" + out_id).val();
			let in_val = $("#" + input_i).val();
			let balance = $("#b_am").val();
			let net_salary_s = $("#net_salary_s").val();
			let payment_amount_s = $("#payment_amount_s").val();
			var isChecked = $(this).is(':checked');
			if (isChecked) {

				// var sum = parseFloat(net_salary_s) + parseFloat(in_val);
				var sum2 = parseFloat(payment_amount_s) + parseFloat(in_val);
				var sum3 = parseFloat(balance) - parseFloat(in_val);
				// $("#net_salary_s").val(sum.toFixed(2));
				$("#payment_amount_s").val(sum2.toFixed(2));
				$("#" + out_id).val(in_val)
				$("#b_am").val(sum3.toFixed(2));

				// $("#" + input_i).val("0")
			} else {
				// var sum = parseFloat(net_salary_s) - parseFloat(out_val);
				var sum2 = parseFloat(payment_amount_s) - parseFloat(out_val);
				var sum3 = parseFloat(balance) + parseFloat(out_val);
				// $("#net_salary_s").val(sum.toFixed(2));
				$("#payment_amount_s").val(sum2.toFixed(2));
				$("#b_am").val(sum3.toFixed(2));
				$("#" + input_i).val(out_val)
				// $("#" + out_id).val("0")
			}

		})

		$(document).ready(function() {
			//$('.bc_checked_class').prop('checked', true);


			// Form submission handling
			$("#pay_monthly").submit(function(e) {
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');

				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form=" + action,
					cache: false,
					success: function(JSON) {
						console.log(JSON);

						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.emo_monthly_pay_again').modal('toggle');

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


<?php }
if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payment' && $_GET['type'] == 'monthly_payment') { ?>
	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime($this->input->get('pay_date'));
	$pay_date = $this->input->get('pay_date');
	// office shift
	$office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);
	// echo $this->input->get('pay_date');
	$p_month = date('F Y', strtotime('01-' . $this->input->get('pay_date')));
	$pa_month = date('Y-m', strtotime('01-' . $this->input->get('pay_date')));

	if ($wages_type == 1) {
		if ($system[0]->is_half_monthly == 1) {
			//if($half_deduct_month==2){
			$basic_salary = $basic_salary / 2;
			//} else {
			//$basic_salary = $basic_salary;
			//}
		} else {
			$basic_salary = $basic_salary;
		}
	} else {
		$basic_salary = $daily_wages;
	}

	$main_salary = $basic_salary;
	$user = $this->Xin_model->read_user_info($user_id);



	//    rt logic for penchuanng
	// $pay = $this->Employees_model->get_basic_pay($user_id, $pay_date);
	// $multi_overtime = $this->Employees_model->multi_overtime($user_id, $pay_date);

	// $appeacial_allowance = $this->Employees_model->get_appeacial_allowance($user_id, $pay_date);
	$apeacial_allowance_amount = 0;
	// $pay = $basic_salary;

	// $overt = $this->Employees_model->get_overtime($user_id, $pay_date);
	// print_r($overt);die;
	// $all_overtime_P = $overt['all_overtime_P'];
	// $all_overtime_t = $overt['all_overtime'];
	// $restday_pay1 = $this->Employees_model->restday_pay($user_id, $pay_date);
	// $get_cliam_data = $this->Employees_model->get_cliam_data($r->user_id, $pay_date);

	$restday_pay = 0;
	$all_overtime_P = 0;
	//    rt logic for penchuanng




	// $basic_salary = 0;

	// print_R($basic_salary);die;









	$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id, $pay_date);
	// print_r($employee_deduction);die;
	$deduction_amount = 0;
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
	?>
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


	// new add
	$ordinary_wage = 0;
	$ow = 0;
	$ow_cpf_employer = 0;
	$ow_cpf_employee = 0;
	$aw = 0;
	$aw_cpf_employer = 0;
	$aw_cpf_employee = 0;
	// end new add




	$g_ordinary_wage = 0;
	$g_additional_wage = 0;
	$g_shg = 0;
	$g_sdl = 0;

	$g_ordinary_wage += $apeacial_allowance_amount;
	$g_shg += $apeacial_allowance_amount;
	$g_sdl += $apeacial_allowance_amount;
	$g_ordinary_wage += $basic_salary;
	$g_shg += $basic_salary;
	$g_sdl += $basic_salary;
	$g_ordinary_wage += $all_overtime_P;
	$g_shg += $all_overtime_P;
	$g_sdl += $all_overtime_P;

	$g_ordinary_wage -= $deduction_amount;
	$g_shg -= $deduction_amount;
	$g_sdl -= $deduction_amount;



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


	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	// $month_start_date = new DateTime($pay_date . '-01');
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



	// $basic_salary = 
	// $exit_date = date('m-Y', strtotime($e_date));
	// end new logic by Debasis



	//unpaid leave
	$unpaid_leave_amount = 0;
	$leaves_taken_count = 0;
	$leave_period = array();
	$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($user_id, $pay_date);
	if ($unpaid_leaves) {
		foreach ($unpaid_leaves as $k => $l) {
			// $pay_date_month = new DateTime($pay_date . '-01');
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
			// if(count($leave_period) > 0) {
			// 	if($l->is_half_day == 0) {
			// 		$leaves_taken_count += count($leave_period);
			// 	}else {
			// 		$leaves_taken_count += count($leave_period)  / 2;
			// 	}
			// }
		}
	}


	// new logic by Debasis
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

		$no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
	} else {
		// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count)) + $holidays_count;
		$no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
	}

	// echo $no_of_days_worked;
	// end new logic by debasis



	// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count)) + $holidays_count;
	$gross_pay = round((($basic_salary + $gross_allowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
	$unpaid_leave_amount = round(($basic_salary + $gross_allowance_amount) - $gross_pay, 2);
	// $basic_salary = $gross_pay;
	// echo $gross_pay;

	$g_shg = $gross_pay;
	$g_sdl = $gross_pay;

	// echo 'Working days : '. $no_of_working_days . '<br>';
	// echo 'Holidays : '. $holidays_count . '<br>';
	// echo 'Leaves : '. $leaves_taken_count . '<br>';
	// echo 'Leave Days : ('. implode(", " , $leave_period)  . ')<br>';
	// echo 'Days Worked : '. $no_of_days_worked . '<br>';
	// echo 'Basic Pay : '. $g_ordinary_wage . '<br>';
	// echo 'Gross Pay : '. $gross_pay . '<br>';
	$g_ordinary_wage -= $unpaid_leave_amount;
	// echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
	// echo '<hr>';

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

					if($sl_other_payments->cpf_applicable == 1){
						$g_additional_wage += $epayments_amount;
						$g_shg += $other_payments_amount;
						$g_sdl += $other_payments_amount;
					}
					// else{
					// 	$g_additional_wage +=  $epayments_amount;
					// }

					$other_payments_amount += $epayments_amount;
				}
			} else {
				if ($system[0]->is_half_monthly == 1) {
					if ($system[0]->half_deduct_month == 2) {
						$epayments_amount = $sl_other_payments->payments_amount / 2;
					} else {
						$epayments_amount = $sl_other_payments->payments_amount;
					}
				} else {
					$epayments_amount = $sl_other_payments->payments_amount;
				}

				if($sl_other_payments->cpf_applicable == 1){
					$g_additional_wage += $epayments_amount;
					$g_shg += $other_payments_amount;
					$g_sdl += $other_payments_amount;
				}
				// else{
				// 	$g_additional_wage +=  $epayments_amount;
				// }

				$other_payments_amount += $epayments_amount;
			}
			// end new logic

		}
	endif;

	// $g_ordinary_wage += $other_payments_amount;
	// $g_shg += $other_payments_amount;
	// $g_sdl += $other_payments_amount;
	// 5: commissions
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
	// print_r($share_options);exit;


	// 6: statutory deductions
	$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
	if (!is_null($statutory_deductions)) :
		$statutory_deductions_amount = 0;
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $basic_salary;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				//$statutory_deductions_amount += $st_amount;
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

	$overtime_amount = 0;
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
			$week_hours = 0;
			if ($office_shift_id) {
				$shift = $this->Employees_model->read_shift_information($office_shift_id);
				if ($shift) {
					if ($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						$time1 = $shift[0]->monday_in_time;
						$time2 = $shift[0]->monday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						$time1 = $shift[0]->tuesday_in_time;
						$time2 = $shift[0]->tuesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						$time1 = $shift[0]->wednesday_in_time;
						$time2 = $shift[0]->wednesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						$time1 = $shift[0]->thursday_in_time;
						$time2 = $shift[0]->thursday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						$time1 = $shift[0]->friday_in_time;
						$time2 = $shift[0]->friday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						$time1 = $shift[0]->saturday_in_time;
						$time2 = $shift[0]->saturday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						$time1 = $shift[0]->sunday_in_time;
						$time2 = $shift[0]->sunday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
				}
			} else {
				$week_hours = 40;
			}
			$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
		}
		if ($ot_hrs > 0) {
			$overtime_amount = round($ot_hrs * $rate, 2);
		}
		// $overtime_amount = $all_overtime_P;

		if ($system[0]->is_half_monthly == 1) {
			if ($system[0]->half_deduct_month == 2) {
				$overtime_amount = $overtime_amount / 2;
			}
		}
		$all_overtime_P = $overtime_amount;
	}

	// $LeaveEnchashment = $this->Employees_model->get_leave_Encashment($user_id, $pay_date);
	$encamoun = 0;


	// add new logic for other benifit by debasis
	$other_benefit_mount = 0;
	$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($user_id,$pay_date);
	foreach($other_benefit_list->result() as $benefit_list){
		$other_benefit_mount += $benefit_list->other_benefit_cost;
	}

	$deduction_amount += $other_benefit_mount;

	// end new logic for other benifit by debasis





	// all other payment
	$all_other_payment = $other_payments_amount + $share_options_amount;

	$gross_salary = $basic_salary + $allowance_amount + $commissions_amount + $overtime_amount + $all_other_payment + $encamoun;
	// add amount
	$add_salary = $allowance_amount + $basic_salary + $all_overtime_P + $apeacial_allowance_amount + $all_other_payment + $commissions_amount + $encamoun;
	// add amount
	$net_salary_default = $add_salary - floatval($loan_de_amount) - $statutory_deductions_amount - $unpaid_leave_amount - $deduction_amount;
	$sta_salary = $allowance_amount + $basic_salary;

	$estatutory_deductions = $statutory_deductions_amount;
	// net salary + statutory deductions
	$net_salary = $net_salary_default;
	// echo $unpaid_leave_amount;
	// print_r($all_overtime_P);die;
	// $allowance_amount += $apeacial_allowance_amount;
	/**
	 * Author : Syed Anees
	 * Sub Functionality : CPF on Gross Salary
	 */
	$emp_dob = $dob;
	$dob = new DateTime($emp_dob);
	// $today = new DateTime($this->input->get('pay_date'));
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
	if ($age_year < $age_upto) {
		$age_from = null;
		$age_to = $age_year;
	} elseif ($age_year > $age_above) {
		$age_from = $age_year;
		$age_to = null;
	} elseif ($age_year > $age_upto && $age_year <= $age_above) {
		$age_from = $age_year;
		$age_to = $age_year;
	} else {
		$age_from = null;
		$age_to = null;
	}

	$im_status = $this->Employees_model->getEmployeeImmigrationStatus($user_id);
	$cpf_contribution = '';
	if ($im_status) {
		$immigration_id = $im_status->immigration_id;
		if ($immigration_id == 2) {
			$issue_date = $im_status->issue_date;
			$i_date = new DateTime($issue_date);
			$today = new DateTime();
			$pr_age = $i_date->diff($today);
			$pr_age_year = $pr_age->y;
			$pr_age_month = $pr_age->m;
			//echo $pr_age_year;exit;
			// if($pr_age_year == 0 && $pr_age_month > 0) {
			if ($pr_age_year == 0 && ($pr_age_month > 0 || $pr_age_month == 0)) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} else if ($pr_age_year == 1 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} elseif ($pr_age_year == 1 && $pr_age_month > 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			} elseif ($pr_age_year == 2 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			}
			// elseif($pr_age_year >= 2) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,1);
			// }
			elseif ($pr_age_year >= 2) {
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
			}
		} elseif ($immigration_id == 1) {
			// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
			$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
		}
		if ($immigration_id == 1 || $immigration_id == 2) {
			if ($cpf_contribution) {
				$employee_contribution = $cpf_contribution->contribution_employee;
				$employer_contribution = $cpf_contribution->contribution_employer;
				$total_cpf_contribution = $cpf_contribution->total_cpf;

				// $ordinary_wage = ($allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount ) - ($loan_de_amount + $statutory_deductions_amount);
				// $ordinary_wage = $g_ordinary_wage;

				// if ($ordinary_wage > $ordinary_wage_cap) {
				// 	$ow = $ordinary_wage_cap;
				// } else {
				// 	$ow = $ordinary_wage;
				// }

				// $cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
				// $ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
				// $ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;


				// $aw = $g_additional_wage;
				// $cpf_total_aw = round(($total_cpf_contribution * $aw) / 100);
				// $aw_cpf_employee = floor(($employee_contribution * $aw) / 100);
				// $aw_cpf_employer = $cpf_total_aw - $aw_cpf_employee;


				// $cpf_employee = $ow_cpf_employee + $aw_cpf_employee;
				// $cpf_employer = $ow_cpf_employer + $aw_cpf_employer;



				// $cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				// $cpf_employer = number_format((float)$cpf_employer, 2, '.', '');

				// done my other developer
				// /*immigration id 1 cpf count start */
				// if ($immigration_id == 1) {
				// 	$tw = $aw + $ow;
				// 	if ($age_year <= 55) {
				// 		if ($tw < 50) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = 0;
				// 		} else if ($tw > 50 && $tw <= 500) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = round((17 / 100 * $tw) - $cpf_employee);
				// 		} else if ($tw > 500 && $tw <= 750) {
				// 			$cpf_employee = floor(0.6 * ($tw - 500));
				// 			$cpf_employer = round((17 / 100 * $tw) + ($cpf_employee) - $cpf_employee);
				// 		} else if ($tw > 750) {
				// 			$cpf_employee = floor(20 / 100 * $ow + 20 / 100 * $aw);
				// 			$cpf_employer = round((37 / 100 * $ow + 37 / 100 * $aw) - $cpf_employee);
				// 			// if ($count_cpf_employer > 2220) {
				// 			// 	$cpf_employer = 2220;
				// 			// } else {
				// 			// 	$cpf_employer = $count_cpf_employer;
				// 			// }
				// 			// if ($count_cpf_employee > 1200) {
				// 			// 	$cpf_employee = 1200;
				// 			// } else {
				// 			// 	$cpf_employee = $count_cpf_employee;
				// 			// }
				// 		}
				// 	} else if ($age_year > 55 && $age_year <= 60) {
				// 		if ($tw < 50) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = 0;
				// 		} else if ($tw > 50 && $tw <= 500) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = round((15 / 100 * $tw) - $cpf_employee);
				// 		} else if ($tw > 500 && $tw <= 750) {
				// 			$cpf_employee = floor(0.48 * ($tw - 500));
				// 			$cpf_employer = round((15 / 100 * ($tw) + 0.48 * ($tw - 500)) - $cpf_employee);
				// 			// print_r($cpf_employer);
				// 		} else if ($tw > 750) {
				// 			$cpf_employee = floor(16 / 100 * $tw);
				// 			// + 15 / 100 * $aw;
				// 			$cpf_employer = round(31 / 100 * ($tw) - $cpf_employee);
				// 			// + 29.5 / 100 * ($aw);
				// 			// if ($count_cpf_employer > 1770) {
				// 			// 	$cpf_employer = 1770;
				// 			// } else {
				// 			// 	$cpf_employer = $count_cpf_employer;
				// 			// }
				// 			// if ($count_cpf_employee > 900) {
				// 			// 	$cpf_employee = 900;
				// 			// } else {
				// 			// 	$cpf_employee = $count_cpf_employee;
				// 			// }
				// 		}
				// 	} else if ($age_year > 60 && $age_year <= 65) {
				// 		if ($tw < 50) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = 0;
				// 		} else if ($tw > 50 && $tw <= 500) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = round(11 / 100 * $tw);
				// 		} else if ($tw > 500 && $tw <= 750) {
				// 			$cpf_employee = floor(0.285 * ($tw - 500));
				// 			$cpf_employer = round((11 / 100 * $tw + 0.285 * ($tw - 500)) - $cpf_employee);
				// 		} else if ($tw > 750) {
				// 			$cpf_employee = floor(10.5 / 100 * $tw);
				// 			$cpf_employer = round((22 / 100 * $tw) - $cpf_employee);

				// 			// if ($count_cpf_employer > 1230) {
				// 			// 	$cpf_employer = 1230;
				// 			// } else {
				// 			// 	$cpf_employer = $count_cpf_employer;
				// 			// }
				// 			// if ($count_cpf_employee > 570) {
				// 			// 	$cpf_employee = 570;
				// 			// } else {
				// 			// 	$cpf_employee = $count_cpf_employee;
				// 			// }
				// 		}
				// 	} else if ($age_year > 65 && $age_year <= 70) {
				// 		if ($tw < 50) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = 0;
				// 		} else if ($tw > 50 && $tw <= 500) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = round(9 / 100 * $tw);
				// 		} else if ($tw > 500 && $tw <= 750) {
				// 			// (9% x $750.00) + 0.225($750.00 - $500.00);

				// 			$cpf_employee = floor(0.225 * ($tw - 500));
				// 			$cpf_employer = round((9 / 100 * $tw + 0.225 * ($tw - 500)) - $cpf_employee);
				// 		} else if ($tw > 750) {
				// 			$cpf_employee = floor(7.5 / 100 * $tw);
				// 			$cpf_employer = round((16.5 / 100 * $tw) - $cpf_employee);

				// 			// if ($count_cpf_employer > 930) {
				// 			// 	$cpf_employer = 930;
				// 			// } else {
				// 			// 	$cpf_employer = $count_cpf_employer;
				// 			// }
				// 			// if ($count_cpf_employee > 420) {
				// 			// 	$cpf_employee = 420;
				// 			// } else {
				// 			// 	$cpf_employee = $count_cpf_employee;
				// 			// }
				// 		}
				// 	} else if ($age_year > 70) {
				// 		if ($tw < 50) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = 0;
				// 		} else if ($tw > 50 && $tw <= 500) {
				// 			$cpf_employee = 0;
				// 			$cpf_employer = round(7.5 / 100 * $tw);
				// 		} else if ($tw > 500 && $tw <= 750) {
				// 			$cpf_employee =  floor(0.15 * ($tw - 500));
				// 			$cpf_employer = round((7.5 / 100 * $tw) + 0.15 * ($tw - 500) - $cpf_employee);
				// 		} else if ($tw > 750) {

				// 			$cpf_employee = floor(5 / 100 * $tw);
				// 			$cpf_employer = round((12.5 / 100 * $tw) - $cpf_employee);

				// 			// if ($count_cpf_employer > 750) {
				// 			// 	$cpf_employer = 750;
				// 			// } else {
				// 			// 	$cpf_employer = $count_cpf_employer;
				// 			// }
				// 			// if ($count_cpf_employee > 300) {
				// 			// 	$cpf_employee = 300;
				// 			// } else {
				// 			// 	$cpf_employee = $count_cpf_employee;
				// 			// }
				// 		}
				// 	}
				// }

				// /* immigration id 1 cpf count end */
				// if ($im_status->issue_date) {
				// 	$issue_date = $im_status->issue_date;
				// 	if ($issue_date != "") {
				// 		$i_date = new DateTime($issue_date);

				// 		$today = new DateTime();
				// 		$pr_age = $i_date->diff($today);
				// 		$pr_age_year = $pr_age->y;
				// 		$pr_age_month = $pr_age->m;
				// 		/* new CPF calculation Start*/
				// 		$tw = $ow + $aw;
				// 		if ($pr_age_year == 1) {

				// 			if ($age_year <= 55) {
				// 				if ($tw > 50 && $tw < 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(4 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw < 750) {
				// 					$cpf_employee = floor(0.15 * ($tw - 500));
				// 					$cpf_employer = round((4 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor((5 / 100) * $ow + (5 / 100) * $aw);
				// 					$cpf_employer = round(((9 / 100) * $ow + (9 / 100) * $aw) - $cpf_employee);

				// 					// if ($count_cpf_employee > 300) {
				// 					// 	$cpf_employee = 300;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 					// if ($count_cpf_employer > 540) {
				// 					// 	$cpf_employer = 540;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 				}
				// 			} else if ($age_year > 55 && $age_year <= 60) {
				// 				if ($tw > 50 && $tw < 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(4 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw < 750) {
				// 					$cpf_employee = floor(0.15 * ($tw - 500));
				// 					$cpf_employer = round((4 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor((5 / 100) * $ow + (5 / 100) * $aw);
				// 					$cpf_employer = round(((9 / 100) * $ow + (9 / 100) * $aw) - $cpf_employee);

				// 					// if ($count_cpf_employee > 300) {
				// 					// 	$cpf_employee = 300;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 					// if ($count_cpf_employer > 540) {
				// 					// 	$cpf_employer = 540;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 				}
				// 			} else if ($age_year > 60 && $age_year <= 65) {
				// 				if ($tw > 50 && $tw < 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(3.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw < 750) {
				// 					$cpf_employee = floor(0.15 * ($tw - 500));
				// 					$cpf_employer = round(3.5 / 100 * $tw + 0.15 * ($tw - 500));
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(5 / 100 * $tw);
				// 					$cpf_employer = round(((8.5 / 100) * $tw) - $cpf_employee);

				// 					// if ($count_cpf_employee > 300) {
				// 					// 	$cpf_employee = 300;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 					// if ($count_cpf_employer > 510) {
				// 					// 	$cpf_employer = 510;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 				}
				// 			} else if ($age_year > 65) {
				// 				if ($tw > 50 && $tw < 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(3.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw < 750) {
				// 					$cpf_employee = floor(0.15 * ($tw - 500));
				// 					$cpf_employer = round((3.5 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor((5 / 100) * $ow + (5 / 100) * $aw);
				// 					$cpf_employer = round(((8.5 / 100) * $ow + (8.5 / 100) * $aw) - $cpf_employee);

				// 					// if ($count_cpf_employee > 300) {
				// 					// 	$cpf_employee = 300;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 					// if ($count_cpf_employer > 510) {
				// 					// 	$cpf_employer = 510;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 				}
				// 			}
				// 		}
				// 		if ($pr_age_year == 2) {
				// 			if ($age_year <= 55) {

				// 				if ($tw <= 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0 * $tw;
				// 					$cpf_employer = round(9 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.45 * ($tw - 500));
				// 					$cpf_employer = round((9 / 100 * $tw + 0.45 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(15 / 100 * $tw);
				// 					$cpf_employer = round(24 / 100 * $tw) - $cpf_employee;
				// 					// if ($count_cpf_employer > 1440) {
				// 					// 	$cpf_employer = 1440;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 900) {
				// 					// 	$cpf_employee = 900;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 55 && $age_year <= 60) {
				// 				if ($tw <= 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(6 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.375 * ($tw - 500));
				// 					$cpf_employer = round((6 / 100 * $tw + 0.375 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(7.5 / 100 * $tw);
				// 					$cpf_employer = round((11 / 100 * $tw) - $cpf_employee);
				// 					// if ($count_cpf_employer > 1110) {
				// 					// 	$cpf_employer = 1110;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 750) {
				// 					// 	$cpf_employee = 750;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 60 && $age_year <= 65) {
				// 				if ($tw <= 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(3.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.225 * ($tw - 500));
				// 					$cpf_employer = round((3.5 / 100 * $tw + 0.225 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(7.5 / 100 * $tw);
				// 					$cpf_employer = round((11 / 100 * $tw) - $cpf_employee);
				// 					// if ($count_cpf_employer > 1110) {
				// 					// 	$cpf_employer = 1110;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 750) {
				// 					// 	$cpf_employee = 750;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 65) {
				// 				if ($tw <= 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(3.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.15 * ($tw - 500));
				// 					$cpf_employer = round((3.5 / 100 * $tw + 0.15 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(5 / 100 * $tw);
				// 					$cpf_employer = round((8.5 / 100 * $tw) - $cpf_employee);
				// 					// if ($count_cpf_employer > 1110) {
				// 					// 	$cpf_employer = 1110;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 750) {
				// 					// 	$cpf_employee = 750;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			}
				// 		}
				// 		if ($pr_age_year == 3 || $pr_age_year > 3) {
				// 			if ($age_year <= 55) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round((17 / 100 * $tw) - $cpf_employee);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.6 * ($tw - 500));
				// 					$cpf_employer = round((17 / 100 * $tw) + ($cpf_employee) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(20 / 100 * $ow + 20 / 100 * $aw);
				// 					$cpf_employer = round((37 / 100 * $ow + 37 / 100 * $aw) - $cpf_employee);
				// 					// if ($count_cpf_employer > 2220) {
				// 					// 	$cpf_employer = 2220;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 1200) {
				// 					// 	$cpf_employee = 1200;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 55 && $age_year <= 60) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round((15 / 100 * $tw) - $cpf_employee);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.48 * ($tw - 500));
				// 					$cpf_employer = round((15 / 100 * ($tw) + 0.48 * ($tw - 500)) - $cpf_employee);
				// 					// print_r($cpf_employer);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(16 / 100 * $tw);
				// 					// + 15 / 100 * $aw;
				// 					$cpf_employer = round(31 / 100 * ($tw) - $cpf_employee);
				// 					// + 29.5 / 100 * ($aw);
				// 					// if ($count_cpf_employer > 1770) {
				// 					// 	$cpf_employer = 1770;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 900) {
				// 					// 	$cpf_employee = 900;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 60 && $age_year <= 65) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(11 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.285 * ($tw - 500));
				// 					$cpf_employer = round((11 / 100 * $tw + 0.285 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(10.5 / 100 * $tw);
				// 					$cpf_employer = round((22 / 100 * $tw) - $cpf_employee);

				// 					// if ($count_cpf_employer > 1230) {
				// 					// 	$cpf_employer = 1230;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 570) {
				// 					// 	$cpf_employee = 570;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 65 && $age_year <= 70) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(9 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					// (9% x $750.00) + 0.225($750.00 - $500.00);

				// 					$cpf_employee = floor(0.225 * ($tw - 500));
				// 					$cpf_employer = round((9 / 100 * $tw + 0.225 * ($tw - 500)) - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$cpf_employee = floor(7.5 / 100 * $tw);
				// 					$cpf_employer = round((16.5 / 100 * $tw) - $cpf_employee);

				// 					// if ($count_cpf_employer > 930) {
				// 					// 	$cpf_employer = 930;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 420) {
				// 					// 	$cpf_employee = 420;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			} else if ($age_year > 70) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(7.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee =  floor(0.15 * ($tw - 500));
				// 					$cpf_employer = round((7.5 / 100 * $tw) + 0.15 * ($tw - 500) - $cpf_employee);
				// 				} else if ($tw > 750) {

				// 					$cpf_employee = floor(5 / 100 * $tw);
				// 					$cpf_employer = round((12.5 / 100 * $tw) - $cpf_employee);

				// 					// if ($count_cpf_employer > 750) {
				// 					// 	$cpf_employer = 750;
				// 					// } else {
				// 					// 	$cpf_employer = $count_cpf_employer;
				// 					// }
				// 					// if ($count_cpf_employee > 300) {
				// 					// 	$cpf_employee = 300;
				// 					// } else {
				// 					// 	$cpf_employee = $count_cpf_employee;
				// 					// }
				// 				}
				// 			}
				// 		}
				// 	}
				// }

				// /* new CPF calculation End*/
				// $net_salary = $net_salary - $cpf_employee;
				// $cpf_total = $cpf_employee + $cpf_employer;

				// // $net_salary = number_format((float)$net_salary, 2, '.', '');
				// $cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				// $cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				// $cpf_total    = number_format((float)$cpf_total, 2, '.', '');

				// end code done by other developer

				/* new CPF calculation Start*/
				// $ordinary_wage = $gross_salary;

				// $ordinary_wage = $net_salary;
				$ordinary_wage = $g_ordinary_wage;
				if ($ordinary_wage > $ordinary_wage_cap) {
					$ow = $ordinary_wage_cap;
				} else {
					$ow = $ordinary_wage;
				}
				
				$aw = $g_additional_wage;

				$tw = $ow + $aw;
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
				
				if ($ow > 500) {
					$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				} else {
					$cpf_employee = 0;
				}
				$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				$net_salary = $net_salary - $cpf_employee;
				$cpf_total = $cpf_employee + $cpf_employer;

				$cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
				$ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
				$ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;

				$cpf_total_aw = round(($total_cpf_contribution * $aw) / 100);
				$aw_cpf_employee = floor(($employee_contribution * $aw) / 100);
				$aw_cpf_employer = $cpf_total_aw - $aw_cpf_employee;



				/* new CPF calculation End*/
			}
		} else {
			$ordinary_wage = $g_ordinary_wage;
			$ow = $ordinary_wage;
			$ow_cpf_employee = 0;
			$ow_cpf_employer = 0;

			$aw = $g_additional_wage;
			$aw_cpf_employee = 0;
			$aw_cpf_employer = 0;
		}
	}
	// $LeaveEnchashment = $this->Employees_model->get_leave_Encashment($user_id, $pay_date);
	$encamoun = 0;


	// echo $emp_dob. ' Date of Birth <br>';
	// echo $age_month . ' Age Month <br>';
	// echo $age_year . ' Age year <br>';
	// echo $age_upto . ' Age Upto <br>';
	// echo $age_above . ' Age Above <br>';
	// echo $age_from . ' Age from <br>';
	// echo $age_to . ' Age To <br>';
	// exit;


	//leave deduction
	// $net_salary = $net_salary - $ashg_fund_deduction_amount;

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
		$net_salary = $net_salary - $shg_fund_deduction_amount;
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
		$net_salary = $net_salary - $ashg_fund_deduction_amount;
	}
	// echo $net_salary;
	$sdl_total_amount = 0;
	if ($g_sdl > 1 && $g_sdl <= 800) {
		$sdl_total_amount += 2;
	} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
		$sdl_amount = (0.25 * $g_sdl) / 100;
		$sdl_total_amount += $sdl_amount;
	} elseif ($g_sdl > 4500) {
		$sdl_total_amount += 11.25;
	}
	// $net_salary = $net_salary - $sdl_total_amount;
	
	$fund_deduction_amount = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
	// $net_salary = round($net_salary, 2);
	// echo $shg_fund_deduction_amount;
	// check
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

		<!-- <div style="display: flex; justify-content: space-between;">
				<div class="left">
					<h3><img src="<?php //echo base_url().'uploads/company/'.$company[0]->logo; 
									?>" height="100px" width="100px"></h3>
				</div>

				<div class="right">
					<h3><?php //echo $company[0]->name; 
						?></h3>
					
				</div>
			</div> -->

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
						<div class="col-md-6">
							<div class="form-group">
								<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
								<input type="hidden" name="designation_id" value="<?php echo $designation_id; ?>" />
								<input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
								<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />
								<label for="name"><?php echo $this->lang->line('xin_payroll_basic_salary'); ?></label>
								<input type="checkbox" name="chk_gross_salary" id="chk_gross_salary" class="bc_checked_class" data-in_id="gross_salary_bc" data-ou_id="gross_salary">
								<input type="hidden" name="gross_salary_bc" id="gross_salary_bc">

								<input type="text" name="gross_salary" id="gross_salary" class="form-control ontim_data_change" value="<?php echo $main_salary; ?>">
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
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="leave_deductions">Leave Deductions</label>
								<input type="checkbox" name="chk_leave_deductions" id="chk_leave_deductions" class="bc_checked_class" data-in_id="leave_deductions_bc" data-ou_id="leave_deductions">
								<input type="hidden" name="leave_deductions_bc" id="leave_deductions_bc">
								<input type="text" name="leave_deductions" id="leave_deductions" class="form-control ontim_data_change" value="<?php echo $unpaid_leave_amount; ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
								<input type="checkbox" name="chk_total_allowances" id="chk_total_allowances" class="bc_checked_class" data-in_id="total_allowances_bc" data-ou_id="total_allowances">
								<input type="hidden" name="total_allowances_bc" id="total_allowances_bc">
								<input type="text" name="total_allowances" id="total_allowances" class="form-control ontim_data_change" value="<?php echo $allowance_amount; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
								<input type="checkbox" name="chk_total_commissions" id="chk_total_commissions" class="bc_checked_class" data-in_id="total_commissions_bc" data-ou_id="total_commissions">
								<input type="hidden" name="total_commissions_bc" id="total_commissions_bc">
								<input type="text" name="total_commissions" id="total_commissions" class="form-control ontim_data_change" value="<?php echo $commissions_amount; ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;display:none">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">General Allowance</label>
								<input type="checkbox" name="chk_General_amount" id="chk_General_amount" class="bc_checked_class" data-in_id="General_amount_bc" data-ou_id="General_amount">
								<input type="hidden" name="General_amount_bc" id="General_amount_bc">
								<input type="text" id="General_amount" name="General_amount" class="form-control" value="0">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Motivation Incentive</label>
								<input type="checkbox" name="chk_motivation_amount" id="chk_motivation_amount" class="bc_checked_class" data-in_id="motivation_amount_bc" data-ou_id="motivation_amount">
								<input type="hidden" name="motivation_amount_bc" id="motivation_amount_bc">
								<input type="text" id="motivation_amount" name="motivation_amount" class="form-control" value="0">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
								<input type="checkbox" name="chk_loan_de_amount" id="chk_loan_de_amount" class="bc_checked_class" data-in_id="loan_de_amount_bc" data-ou_id="total_loan">
								<input type="hidden" name="loan_de_amount_bc" id="loan_de_amount_bc">
								<input type="text" name="total_loan" id="total_loan" class="form-control" value="<?php echo $loan_de_amount; ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
								<input type="checkbox" name="chk_total_overtime" id="chk_total_overtime" class="bc_checked_class" data-in_id="total_overtime_bc" data-ou_id="total_overtime">
								<input type="hidden" name="total_overtime_bc" id="total_overtime_bc">
								<!-- <input type="text" name="total_overtime" id="total_overtime" class="form-control" value="<?= $all_overtime_t ?>" > -->
								<input type="text" name="total_overtime" class="form-control ontim_data_change" value="<?php echo $all_overtime_P; ?>" id="total_overtime">
								<input type="hidden" name="total_overtime_amount" class="form-control" value="<?php echo $all_overtime_P; ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
								<input type="checkbox" name="chk_total_statutory_deductions" id="chk_total_statutory_deductions" class="bc_checked_class" data-in_id="total_statutory_deductions_bc" data-ou_id="total_statutory_deductions">
								<input type="hidden" name="total_statutory_deductions_bc" id="total_statutory_deductions_bc">
								<input type="text" name="total_statutory_deductions" id="total_statutory_deductions" class="form-control" value="<?php echo ($estatutory_deductions > 0 ? $estatutory_deductions : 0); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
								<input type="checkbox" name="chk_total_other_payments" id="chk_total_other_payments" class="bc_checked_class" data-in_id="total_other_payments_bc" data-ou_id="total_other_payments">
								<input type="hidden" name="total_other_payments_bc" id="total_other_payments_bc">
								<input type="text" id="total_other_payments" name="total_other_payments" class="form-control ontim_data_change" value="<?php echo $all_other_payment; ?>">
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
								<input type="hidden" name="total_employee_deduction_bc" id="total_employee_deduction_bc">
								<input type="text" name="total_employee_deduction" id="total_employee_deduction" class="form-control" value="<?php echo (isset($deduction_amount) && $deduction_amount > 0 ? $deduction_amount : 0); ?>">
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
								<!-- <input type="checkbox" name="chk_total_cpf" id="chk_total_cpf"> -->
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
								<input type="hidden" name="employee_claim_bc" id="employee_claim_bc">
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
								<input type="hidden" name="total_share_bc" id="total_share_bc">
								<input type="text" name="total_share" id="total_share" class="form-control ontim_data_change" value="<?php echo ($share_options_amount > 0 ? $share_options_amount : 0); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Additional Allowance</label>
								<input type="checkbox" name="chk_additional_allowances" id="chk_additional_allowances" class="bc_checked_class" data-in_id="additional_allowances_bc" data-ou_id="additional_allowances">
								<input type="hidden" name="additional_allowances_bc" id="additional_allowances_bc">
								<input type="text" name="additional_allowances" id="additional_allowances" class="form-control ontim_data_change" value="0">
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
								<input type="text" id="b_am" name="b_am" class="form-control" value="0" readonly>
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
								$other_benefit = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($user_id,$pay_date);

								foreach($other_benefit->result() as $benefit){
							?>
								<div class="col-md-6">
									<label><?php echo $benefit->other_benefit ?> </label>
								</div>
								<div class="col-md-6">
								<input type="text" readonly  name="other_benefit_amount[]" class="form-control" value="<?php echo $benefit->other_benefit_cost ?>">
									 
								</div>
							<?php }?>

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


			// Initial calculation of payment amount

			// Handle change in checkboxes
			$('.bc_checked_class').prop('checked', true);

			$('.bc_checked_class').on("click", function(e) {
				let input_i = $(this).data('in_id');
				let out_id = $(this).data('ou_id');
				let out_val = $("#" + out_id).val();
				let in_val = $("#" + input_i).val();
				let balance = $("#b_am").val();
				let net_salary_s = $("#net_salary_s").val();
				let payment_amount_s = $("#payment_amount_s").val();
				var isChecked = $(this).is(':checked');
				if (isChecked) {

					// var sum = parseFloat(net_salary_s) + parseFloat(in_val);
					var sum2 = parseFloat(payment_amount_s) + parseFloat(in_val);
					var sum3 = parseFloat(balance) - parseFloat(in_val);
					// $("#net_salary_s").val(sum.toFixed(2));
					$("#payment_amount_s").val(sum2.toFixed(2));
					// $("#" + out_id).val(in_val)
					$("#b_am").val(sum3.toFixed(2));

					$("#" + input_i).val("0")
				} else {
					// var sum = parseFloat(net_salary_s) - parseFloat(out_val);
					var sum2 = parseFloat(payment_amount_s) - parseFloat(out_val);
					var sum3 = parseFloat(balance) + parseFloat(out_val);
					// $("#net_salary_s").val(sum.toFixed(2));
					$("#payment_amount_s").val(sum2.toFixed(2));
					$("#b_am").val(sum3.toFixed(2));
					$("#" + input_i).val(out_val)
					// $("#" + out_id).val("0")
				}

			})
			// Form submission handling
			$("#pay_monthly").submit(function(e) {
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');

				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form=" + action,
					cache: false,
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


<?php } else if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'hourly_payment' && $_GET['type'] == 'fhourly_payment') { ?>
	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime('01-' . $this->input->get('pay_date'));
	$p_month = date('F Y', $payment_month);

	$pay_date = $this->input->get('pay_date');



	$basic_salary = $basic_salary;
	$hourly_rate = $basic_salary;


	?>
	<?php

	// office shift
	$office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);

	//overtime request
	$overtime_count = $this->Overtime_request_model->get_overtime_request_count($user_id, $this->input->get('month_year'));
	$re_hrs_old_int1 = 0;
	$re_hrs_old_seconds = 0;
	$re_pcount = 0;
	foreach ($overtime_count as $overtime_hr) {
		// total work			
		$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
		$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
		$re_interval_late = $request_clock_in->diff($request_clock_out);
		$re_hours_r  = $re_interval_late->format('%h');
		$re_minutes_r = $re_interval_late->format('%i');
		$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

		$re_str_time = $re_total_time;

		$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

		sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

		$re_hrs_old_int1 += $re_hrs_old_seconds;

		$re_pcount = gmdate("H", $re_hrs_old_int1);
	}
	$result = $this->Payroll_model->total_hours_worked($user_id, $pay_date);

	$hrs_old_int1 = 0;
	$pcount = 0;
	$Trest = 0;
	$total_time_rs = 0;
	$hrs_old_int_res1 = 0;
	foreach ($result->result() as $hour_work) {
		// total work			
		$clock_in =  new DateTime($hour_work->clock_in);
		$clock_out =  new DateTime($hour_work->clock_out);
		$interval_late = $clock_in->diff($clock_out);
		$hours_r  = $interval_late->format('%h');
		$minutes_r = $interval_late->format('%i');
		$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

		$str_time = $total_time;

		$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

		$hrs_old_int1 += $hrs_old_seconds;

		$pcount = gmdate("H", $hrs_old_int1);
	}
	$pcount = $pcount + $re_pcount;


	// 1: salary type
	if ($wages_type == 1) {
		// $wages_type = $this->lang->line('xin_payroll_basic_salary');
		if ($system[0]->is_half_monthly == 1) {
			$basic_salary = $basic_salary / 2;
		} else {
			$basic_salary = $basic_salary;
		}
		$p_class = 'emo_monthly_pay';
		$view_p_class = 'payroll_template_modal';
	} else if ($wages_type == 2) {
		// $wages_type = $this->lang->line('xin_employee_daily_wages');
		if ($pcount > 0) {
			$basic_salary = $pcount * $basic_salary;
		} else {
			$basic_salary = $pcount;
		}
		$p_class = 'emo_hourly_pay';
		$view_p_class = 'hourlywages_template_modal';
	} else {
		// $wages_type = $this->lang->line('xin_payroll_basic_salary');
		if ($system[0]->is_half_monthly == 1) {
			$basic_salary = $basic_salary / 2;
		} else {
			$basic_salary = $basic_salary;
		}
		$p_class = 'emo_monthly_pay';
		$view_p_class = 'payroll_template_modal';
	}

	//    rt logic for penchuanng
	// $pay_basic = $this->Employees_model->get_basic_pay($user_id, $pay_date);


	// $appeacial_allowance = $this->Employees_model->get_appeacial_allowance($user_id, $pay_date);
	$apeacial_allowance_amount = 0;



	// $pay = $pay_basic['pay'];
	// $pay_rate = $pay_basic['rate'];
	// $total_work_h = $pay_basic['total_work_h'];
	// $overt = $this->Employees_model->get_overtime($user_id, $pay_date);
	$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id, $pay_date);

	$deduction_amount = 0;
	if ($employee_deduction) {
		foreach ($employee_deduction as $deduction) {
			if ($deduction->type_id == 1) {
				$deduction_amount +=  $deduction->amount;
			}
			if ($deduction->type_id == 2) {
				$from_month_year = date('Y-m', strtotime($deduction->from_date));
				$to_month_year = date('Y-m-d', strtotime($deduction->to_date));
				$pa_month = date('Y-m', strtotime('01-' . $pay_date));


				if ($from_month_year != "" && $to_month_year != "") {
					if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
						$deduction_amount +=  $deduction->amount;
					}
				}
			}
		}
	}
	// die;
	$all_overtime_P = 0;
	$all_overtime_t = 0;
	// $restday_pay1 = $this->Employees_model->restday_pay($user_id, $pay_date);
	// $get_cliam_data = $this->Employees_model->get_cliam_data($r->user_id, $pay_date);
	// print_r($all_overtime_P);die;
	$restday_pay = 0;
	$pay = 0;
	// $hourly_rate = $pay;
	// $basic_salary = $pay;
	//    rt logic for penchuanng
	// new add
	$ordinary_wage = 0;
	$ow = 0;
	$ow_cpf_employer = 0;
	$ow_cpf_employee = 0;
	$aw = 0;
	$aw_cpf_employer = 0;
	$aw_cpf_employee = 0;
	// end new add




	$g_ordinary_wage = 0;
	$g_additional_wage = 0;
	$g_shg = 0;
	$g_sdl = 0;

	$g_ordinary_wage += $basic_salary;
	$g_shg += $basic_salary;
	$g_sdl += $basic_salary;

	// $g_ordinary_wage += $apeacial_allowance_amount;
	// $g_shg += $apeacial_allowance_amount;
	// $g_sdl += $apeacial_allowance_amount;


	$g_ordinary_wage += $all_overtime_P;
	$g_shg += $all_overtime_P;
	$g_sdl += $all_overtime_P;

	$g_ordinary_wage -= $deduction_amount;
	$g_shg -= $deduction_amount;
	$g_sdl -= $deduction_amount;


	$pay_date = $this->input->get('pay_date');
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
	// $allowance_amount += $apeacial_allowance_amount;

	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	// $month_start_date = new DateTime($pay_date . '-01');
	$month_start_date = new DateTime('01-' . $pay_date);
	$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
	$month_end_date->modify('+1 day');
	$holiday_array_new = array();

	$interval = new DateInterval('P1D');
	$period = new DatePeriod($month_start_date, $interval, $month_end_date);
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
			// $pay_date_month = new DateTime($pay_date . '-01');
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
			// $holiday_array_new = array();
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
			// if(count($leave_period) > 0) {
			// 	if($l->is_half_day == 0) {
			// 		$leaves_taken_count += count($leave_period);
			// 	}else {
			// 		$leaves_taken_count += count($leave_period)  / 2;
			// 	}
			// }
		}
	}

	$no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count)) + $holidays_count;
	$gross_pay = round((($basic_salary + $gross_allowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
	$unpaid_leave_amount = ($basic_salary + $gross_allowance_amount) - $gross_pay;


	// echo 'Working days : '. $no_of_working_days . '<br>';
	// echo 'Holidays : '. $holidays_count . '<br>';
	// echo 'Leaves : '. $leaves_taken_count . '<br>';
	// echo 'Leave Days : ('. implode(", " , $leave_period)  . ')<br>';
	// echo 'Days Worked : '. $no_of_days_worked . '<br>';
	// echo 'Basic Pay : '. $g_ordinary_wage . '<br>';
	// echo 'Gross Pay : '. $gross_pay . '<br>';
	$g_ordinary_wage -= $unpaid_leave_amount;
	// echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
	// echo '<hr>';

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
	// 4: other payment
	$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
	$other_payments_amount = 0;
	if (!is_null($other_payments)) :
		foreach ($other_payments->result() as $sl_other_payments) {
			if ($system[0]->is_half_monthly == 1) {
				if ($system[0]->half_deduct_month == 2) {
					$epayments_amount = $sl_other_payments->payments_amount / 2;
				} else {
					$epayments_amount = $sl_other_payments->payments_amount;
				}
			} else {
				$epayments_amount = $sl_other_payments->payments_amount;
			}
			$other_payments_amount += $epayments_amount;
		}
	endif;
	// print_r($other_payments);die;
	$g_ordinary_wage += $other_payments_amount;
	$g_shg += $other_payments_amount;
	$g_sdl += $other_payments_amount;
	// 5: commissions
	$commissions_amount = 0;
	$commissions = $this->Employees_model->getEmployeeMonthlyCommission($user_id, $pay_date);
	// print_r($commissions);die;

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

	// 6: statutory deductions
	$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
	if (!is_null($statutory_deductions)) :
		$statutory_deductions_amount = 0;
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $basic_salary;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				//$statutory_deductions_amount += $st_amount;
				// print_r($sta_salary);exit;
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

	// 7: overtime
	// $salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
	// $count_overtime = $this->Employees_model->count_employee_overtime($user_id);
	// $overtime_amount = 0;
	// if($count_overtime > 0) {
	// 	foreach($salary_overtime as $sl_overtime){
	// 		if($system[0]->is_half_monthly==1){
	// 			if($system[0]->half_deduct_month==2){
	// 				$eovertime_hours = $sl_overtime->overtime_hours/2;
	// 				$eovertime_rate = $sl_overtime->overtime_rate/2;
	// 			} else {
	// 				$eovertime_hours = $sl_overtime->overtime_hours;
	// 				$eovertime_rate = $sl_overtime->overtime_rate;
	// 			}
	// 		} else {
	// 			$eovertime_hours = $sl_overtime->overtime_hours;
	// 			$eovertime_rate = $sl_overtime->overtime_rate;
	// 		}
	// 		$overtime_amount += $eovertime_hours * $eovertime_rate;
	// 		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
	// 		//$overtime_amount += $overtime_total;
	// 	}
	// } else {
	// 	$overtime_amount = 0;
	// }
	$overtime_amount = 0;
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
			$week_hours = 0;
			if ($office_shift_id) {
				$shift = $this->Employees_model->read_shift_information($office_shift_id);
				if ($shift) {
					if ($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						$time1 = $shift[0]->monday_in_time;
						$time2 = $shift[0]->monday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						$time1 = $shift[0]->tuesday_in_time;
						$time2 = $shift[0]->tuesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						$time1 = $shift[0]->wednesday_in_time;
						$time2 = $shift[0]->wednesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						$time1 = $shift[0]->thursday_in_time;
						$time2 = $shift[0]->thursday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						$time1 = $shift[0]->friday_in_time;
						$time2 = $shift[0]->friday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						$time1 = $shift[0]->saturday_in_time;
						$time2 = $shift[0]->saturday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						$time1 = $shift[0]->sunday_in_time;
						$time2 = $shift[0]->sunday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
				}
			} else {
				$week_hours = 40;
			}
			$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
		}

		if ($ot_hrs > 0) {
			$overtime_amount = round($ot_hrs * $rate, 2);
		}
		if ($system[0]->is_half_monthly == 1) {
			if ($system[0]->half_deduct_month == 2) {
				$overtime_amount = $overtime_amount / 2;
			}
		}
		$g_ordinary_wage += $overtime_amount;
	}

	$overtime_amount = $overtime_amount;
	// $LeaveEnchashment = $this->Employees_model->get_leave_Encashment($user_id, $pay_date);
	$encamoun = 0;

	// all other payment
	$all_other_payment = $other_payments_amount + $share_options_amount;

	$gross_salary = $basic_salary + $allowance_amount + $restday_pay + $commissions_amount + $overtime_amount + $all_other_payment;
	// add amount
	$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount;
	// add amount
	$net_salary_default = $add_salary - floatval($loan_de_amount) - $statutory_deductions_amount - $unpaid_leave_amount - $deduction_amount;
	$sta_salary = $allowance_amount + $basic_salary;

	$estatutory_deductions = $statutory_deductions_amount;
	// net salary + statutory deductions
	$net_salary = $net_salary_default;

	// print_r($deduction_amount);die;

	/**
	 * Author : Syed Anees
	 * Sub Functionality : CPF on Gross Salary
	 */
	$emp_dob = $date_of_birth;
	$dob = new DateTime($emp_dob);
	$today = new DateTime("01-" . $pay_date);
	$age = $dob->diff($today);
	$age_year = $age->y;
	$age_month = $age->m;

	$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
	$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
	$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

	if ($age_month > 0) {
		$age_year = $age_year + 1;
	}
	if ($age_year < $age_upto) {
		$age_from = null;
		$age_to = $age_year;
	} elseif ($age_year > $age_above) {
		$age_from = $age_year;
		$age_to = null;
	} elseif ($age_year > $age_upto && $age_year <= $age_above) {
		$age_from = $age_year;
		$age_to = $age_year;
	} else {
		$age_from = null;
		$age_to = null;
	}
	$immigration_id = 0;
	$im_status = $this->Employees_model->getEmployeeImmigrationStatus($user_id);
	$cpf_contribution = '';
	if ($im_status) {
		$immigration_id = $im_status->immigration_id;
		if ($immigration_id == 2) {
			$issue_date = $im_status->issue_date;
			$i_date = new DateTime($issue_date);
			$today = new DateTime();
			$pr_age = $i_date->diff($today);
			$pr_age_year = $pr_age->y;
			$pr_age_month = $pr_age->m;
			// if($pr_age_year == 0 && $pr_age_month > 0) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
			// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
			// 	// print_r($pr_age_month);exit;
			// }elseif($pr_age_year >= 2) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,1);
			// }
			// if($pr_age_year == 0 && $pr_age_month > 0) {
			if ($pr_age_year == 0 && ($pr_age_month > 0 || $pr_age_month == 0)) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} else if ($pr_age_year == 1 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} elseif ($pr_age_year == 1 && $pr_age_month > 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			} elseif ($pr_age_year == 2 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			}
			// elseif($pr_age_year >= 2) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,1);
			// }
			elseif ($pr_age_year >= 2) {
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
			}
		} elseif ($immigration_id == 1) {
			// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
			$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
		}
		if ($immigration_id == 1 || $immigration_id == 2) {
			if ($cpf_contribution) {
				$employee_contribution = $cpf_contribution->contribution_employee;
				$employer_contribution = $cpf_contribution->contribution_employer;
				$total_cpf_contribution = $cpf_contribution->total_cpf;

				// $ordinary_wage = ($allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount ) - ($loan_de_amount + $statutory_deductions_amount);
				$ordinary_wage = $g_ordinary_wage;

				if ($ordinary_wage > $ordinary_wage_cap) {
					$ow = $ordinary_wage_cap;
				} else {
					$ow = $ordinary_wage;
				}

				$cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
				$ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
				$ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;


				$aw = $g_additional_wage;
				$cpf_total_aw = round(($total_cpf_contribution * $aw) / 100);
				$aw_cpf_employee = floor(($employee_contribution * $aw) / 100);
				$aw_cpf_employer = $cpf_total_aw - $aw_cpf_employee;


				$cpf_employee = $ow_cpf_employee + $aw_cpf_employee;
				$cpf_employer = $ow_cpf_employer + $aw_cpf_employer;


				if ($ow > 100) {
					$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				} else {
					$cpf_employee = 0;
				}
				$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				$net_salary = $net_salary - $cpf_employee;
				$cpf_total = $cpf_employee + $cpf_employer;
			}
		} else {
			$ordinary_wage = $g_ordinary_wage;
			$ow = $ordinary_wage;
			$ow_cpf_employee = 0;
			$ow_cpf_employer = 0;

			$aw = $g_additional_wage;
			$aw_cpf_employee = 0;
			$aw_cpf_employer = 0;
		}
	}
	// echo $emp_dob. ' Date of Birth <br>';
	// echo $age_month . ' Age Month <br>';
	// echo $age_year . ' Age year <br>';
	// echo $age_upto . ' Age Upto <br>';
	// echo $age_above . ' Age Above <br>';
	// echo $age_from . ' Age from <br>';
	// echo $age_to . ' Age To <br>';
	// exit;

	//leave deduction
	// $net_salary = $net_salary - $ashg_fund_deduction_amount;

	$shg_fund_deduction_amount = 0;
	//Other Fund Contributions
	$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);
	if ($employee_contributions) {
		$gross_s = $g_shg;
		$contribution_id = $employee_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$shg_fund_deduction_amount += $contribution_amount;
		$net_salary = $net_salary - $shg_fund_deduction_amount;
	}

	$ashg_fund_deduction_amount = 0;

	$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
	if ($employee_ashg_contributions) {
		$gross_s = $g_shg;

		$contribution_id = $employee_ashg_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$ashg_fund_deduction_amount += $contribution_amount;
		$net_salary = $net_salary - $ashg_fund_deduction_amount;
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
	// $net_salary = $net_salary - $sdl_total_amount;

	$fund_deduction_amount = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
	$net_salary = number_format((float)$net_salary, 2, '.', '');

	// check
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
	$user = $this->Xin_model->read_user_info($user_id);
	$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
	if (!is_null($_des_name)) {
		$_designation_name = $_des_name[0]->designation_name;
	} else {
		$_designation_name = '';
	}
	// print_r($g_ordinary_wage);
	// die;
	?>

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
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><strong><?php echo $this->lang->line('xin_payment_for'); ?></strong>
			<?php echo $p_month; ?></h4>
	</div>
	<div class="modal-body" style="overflow:auto; height:530px;">
		<?php $attributes = array('name' => 'pay_hourly', 'id' => 'pay_hourly', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
		<?php $hidden = array('_method' => 'ADD'); ?>
		<?php echo form_open('admin/payroll/add_pay_hourly/', $attributes, $hidden); ?>
		<div class="sender-reciver">
			<div>
				<div class="row" style="margin-left:20px;">
					<h4>Employee Detail</h4>
					<table class="m-1">
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
			<div id="tabs_vk">
				<ul>
					<li><a href="#tabs_vk-1">Details</a></li>
					<li><a href="#tabs_vk-2">Allowances & Deductions</a></li>

				</ul>
				<div id="tabs_vk-1">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
								<input type="hidden" name="designation_id" value="<?php echo $designation_id; ?>" />
								<input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
								<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />

								<label for="name"><?php echo $this->lang->line('xin_payroll_hourly_rate'); ?></label>
								<input type="text" class="form-control" value="<?php echo $hourly_rate; ?>">


								<input type="hidden" id="emp_id" value="<?php echo $user_id ?>" name="emp_id">
								<input type="hidden" value="<?php echo $user_id; ?>" name="u_id">
								<input type="hidden" value="<?php echo $basic_salary; ?>" name="basic_salary">
								<input type="hidden" value="<?php echo $hourly_rate; ?>" name="gross_salary">
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
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_hours_worked_total'); ?></label>
								<input type="text" name="hours_worked" class="form-control" value="<?php echo $pcount; ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Basic Salary</label>
								<input type="checkbox" name="chk_gross_salary" id="chk_gross_salary" class="bc_checked_class" data-in_id="gross_salary_bc" data-ou_id="gross_salary">

								<input type="text" id="gross_salary" name="basic_salary" class="form-control" value="<?php echo $basic_salary; ?>">
								<input type="hidden" name="gross_salary_bc" id="gross_salary_bc">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
								<input type="checkbox" name="chk_total_allowances" id="chk_total_allowances" class="bc_checked_class" data-in_id="total_allowances_bc" data-ou_id="total_allowances">
								<input type="hidden" name="total_allowances_bc" id="total_allowances_bc">

								<input type="text" name="total_allowances" class="form-control" value="<?php echo $allowance_amount; ?>" id="total_allowances">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
								<input type="checkbox" name="chk_total_commissions" id="chk_total_commissions" class="bc_checked_class" data-in_id="total_commissions_bc" data-ou_id="total_commissions">
								<input type="hidden" name="total_commissions_bc" id="total_commissions_bc">

								<input type="text" id="total_commissions" name="total_commissions" class="form-control" value="<?php echo $commissions_amount; ?>">
							</div>
						</div>

					</div>
					<div class="row " style="display:none;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">General Allowance</label>
								<input type="checkbox" name="chk_General_amount" id="chk_General_amount" class="bc_checked_class" data-in_id="General_amount_bc" data-ou_id="General_amount">
								<input type="hidden" name="General_amount_bc" id="General_amount_bc">
								<input type="text" id="General_amount" name="General_amount" class="form-control" value="01">
							</div>
						</div>
						<div class="col-md-6 d-none">
							<div class="form-group">
								<label for="name">Motivation Incentive</label>
								<input type="checkbox" name="chk_motivation_amount" id="chk_motivation_amount" class="bc_checked_class" data-in_id="motivation_amount_bc" data-ou_id="motivation_amount">
								<input type="hidden" name="motivation_amount_bc" id="motivation_amount_bc">
								<input type="text" id="motivation_amount" name="motivation_amount" class="form-control" value="0">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
								<input type="checkbox" name="chk_loan_de_amount" id="chk_loan_de_amount" class="bc_checked_class" data-in_id="loan_de_amount_bc" data-ou_id="total_loan">
								<input type="hidden" name="loan_de_amount_bc" id="loan_de_amount_bc">

								<input type="text" name="total_loan" class="form-control" value="<?php echo $loan_de_amount; ?>" id="total_loan">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
								<input type="checkbox" name="chk_total_overtime" id="chk_total_overtime" class="bc_checked_class" data-in_id="total_overtime_bc" data-ou_id="total_overtime">
								<input type="hidden" name="total_overtime_bc" id="total_overtime_bc">

								<input type="text" name="total_overtime" class="form-control" value="<?php echo $all_overtime_P; ?>">
								<input type="hidden" name="total_overtime_amount" class="form-control" value="<?php echo $all_overtime_P; ?>" id="total_overtime">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
								<input type="checkbox" name="chk_total_statutory_deductions" id="chk_total_statutory_deductions" class="bc_checked_class" data-in_id="total_statutory_deductions_bc" data-ou_id="total_statutory_deductions">
								<input type="hidden" name="total_statutory_deductions_bc" id="total_statutory_deductions_bc">

								<input type="text" name="total_statutory_deductions" class="form-control" value="<?php echo $statutory_deductions_amount; ?>" id="total_statutory_deductions">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
								<input type="checkbox" name="chk_total_other_payments" id="chk_total_other_payments" class="bc_checked_class" data-in_id="total_other_payments_bc" data-ou_id="total_other_payments">
								<input type="hidden" name="total_other_payments_bc" id="total_other_payments_bc">

								<input type="text" name="total_other_payments" class="form-control" value="<?php echo $all_other_payment; ?>" id="total_other_payments">
							</div>
						</div>
					</div>
					<?php if (($immigration_id == 1 || $immigration_id == 2) && $cpf_contribution) { ?>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="total_cpf_employee">CPF Employee</label>
									<input type="text" name="total_cpf_employee" class="form-control" value="<?php echo $cpf_employee; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="total_cpf_employer">CPF Employer</label>
									<input type="text" name="total_cpf_employer" class="form-control" value="<?php echo $cpf_employer; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="total_cpf">Total CPF</label>
									<input type="text" name="total_cpf" class="form-control" value="<?php echo $cpf_total; ?>">
								</div>
							</div>
						</div>
					<?php }

					?>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="total_employee_deduction">Employee Deduction</label>
								<input type="checkbox" name="chk_total_employee_deduction" id="chk_total_employee_deduction" class="bc_checked_class" data-in_id="total_employee_deduction_bc" data-ou_id="total_employee_deduction">
								<input type="hidden" name="total_employee_deduction_bc" id="total_employee_deduction_bc">
								<input type="text" name="total_employee_deduction" id="total_employee_deduction" class="form-control" value="<?php echo (isset($deduction_amount) && $deduction_amount > 0 ? $deduction_amount : 0); ?>">
							</div>
						</div>
					</div>
					<?php if ($employee_contributions || $fund_deduction_amount > 0) { ?>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="total_fund_contribution">Contribution Funds <small>(MBMF, SINDA, CDAC, ECF)</small></label>
									<!-- <input type="text" name="total_fund_contribution" class="form-control" value="<?php echo $fund_deduction_amount; ?>"> -->
									<input type="text" name="total_fund_contribution" class="form-control" value="<?php echo $contribution_amount; ?>">
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_net_salary'); ?></label>
								<input type="text" name="net_salary" class="form-control" value="<?php echo $net_salary; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
								<input type="text" name="payment_amount" class="form-control" value="<?php echo $net_salary; ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Balance Amount</label>
								<input type="text" id="b_am" name="b_am" class="form-control" value="0" readonly autocomplete="off">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
								<input type="text" id="payment_amount_s" name="payment_amount" class="form-control" value="<?= $net_salary ?>" readonly>
							</div>
						</div>
						<div class="col-md-4">
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
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<span><strong>NOTE:</strong>
									<?php echo $this->lang->line('xin_payroll_total_allowance'); ?>,<?php echo $this->lang->line('xin_hr_commissions'); ?>,<?php echo $this->lang->line('xin_payroll_total_loan'); ?>,<?php echo $this->lang->line('xin_payroll_total_overtime'); ?>,<?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?>,<?php echo $this->lang->line('xin_employee_set_other_payment'); ?>
									are not editable.</span>
							</div>
						</div>
					</div>
				</div>
				<div id="tabs_vk-2">


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
										<div class="col-md-12 d-none">
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




							<!-- thhh -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions box-footer">
			<?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_pay'))); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$("#tabs_vk").tabs();
		});


		$(document).ready(function() {
			$('.bc_checked_class').prop('checked', true);


			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			$('.bc_checked_class').on("click", function(e) {
				// Get IDs from data attributes
				let input_i = $(this).data('in_id');
				let out_id = $(this).data('ou_id');

				// Retrieve input values and ensure they are valid numbers, fallback to 0 if not
				let out_val = parseFloat($("#" + out_id).val()) || 0;
				let in_val = parseFloat($("#" + input_i).val()) || 0;


				// Retrieve balance and other relevant values, ensuring they're numbers
				let balance = parseFloat($("#b_am").val()) || 0;
				let payment_amount_s = parseFloat($("#payment_amount_s").val()) || 0;

				// Check if the checkbox is checked
				var isChecked = $(this).is(':checked');

				if (isChecked) {
					// When checked: Increase payment amount and decrease balance
					let updatedPaymentAmount = payment_amount_s + in_val;
					let updatedBalance = balance - in_val;

					// Update fields with new values
					$("#payment_amount_s").val(updatedPaymentAmount.toFixed(2));
					$("#b_am").val(updatedBalance.toFixed(2));
					// alert("gg"+out_val)
					// Reset the input value to 0
					$("#" + input_i).val("0");
				} else {
					// When unchecked: Decrease payment amount and increase balance
					let updatedPaymentAmount = payment_amount_s - out_val;
					let updatedBalance = balance + out_val;

					// Update fields with new values
					$("#payment_amount_s").val(updatedPaymentAmount.toFixed(2));
					$("#b_am").val(updatedBalance.toFixed(2));
					// alert("gg"+out_val)
					// Restore the input value to its original value
					$("#" + input_i).val(out_val);
				}
			});

			// On page load: datatable					
			$("#pay_hourly").submit(function(e) {

				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				//$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=11&data=hourly&add_type=add_pay_hourly&form=" +
						action,
					cache: false,
					success: function(JSON) {
						// console.log(JSON);
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.emo_hourly_pay').modal('toggle');

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
					}
				});
			});
		});
	</script>

<?php } else if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'hourly_payment' && $_GET['type'] == 'emo_hourly_pay_again') { ?>
	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime('01-' . $this->input->get('pay_date'));
	$p_month = date('F Y', $payment_month);

	$pay_date = $this->input->get('pay_date');



	$basic_salary = $basic_salary;
	$hourly_rate = $basic_salary;


	?>
	<?php

	// office shift
	$office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);

	//overtime request
	$overtime_count = $this->Overtime_request_model->get_overtime_request_count($user_id, $this->input->get('month_year'));
	$re_hrs_old_int1 = 0;
	$re_hrs_old_seconds = 0;
	$re_pcount = 0;
	foreach ($overtime_count as $overtime_hr) {
		// total work			
		$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
		$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
		$re_interval_late = $request_clock_in->diff($request_clock_out);
		$re_hours_r  = $re_interval_late->format('%h');
		$re_minutes_r = $re_interval_late->format('%i');
		$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

		$re_str_time = $re_total_time;

		$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

		sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

		$re_hrs_old_int1 += $re_hrs_old_seconds;

		$re_pcount = gmdate("H", $re_hrs_old_int1);
	}
	$result = $this->Payroll_model->total_hours_worked($user_id, $pay_date);

	$hrs_old_int1 = 0;
	$pcount = 0;
	$Trest = 0;
	$total_time_rs = 0;
	$hrs_old_int_res1 = 0;
	foreach ($result->result() as $hour_work) {
		// total work			
		$clock_in =  new DateTime($hour_work->clock_in);
		$clock_out =  new DateTime($hour_work->clock_out);
		$interval_late = $clock_in->diff($clock_out);
		$hours_r  = $interval_late->format('%h');
		$minutes_r = $interval_late->format('%i');
		$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

		$str_time = $total_time;

		$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

		$hrs_old_int1 += $hrs_old_seconds;

		$pcount = gmdate("H", $hrs_old_int1);
	}
	$pcount = $pcount + $re_pcount;


	// 1: salary type
	if ($wages_type == 1) {
		// $wages_type = $this->lang->line('xin_payroll_basic_salary');
		if ($system[0]->is_half_monthly == 1) {
			$basic_salary = $basic_salary / 2;
		} else {
			$basic_salary = $basic_salary;
		}
		$p_class = 'emo_monthly_pay';
		$view_p_class = 'payroll_template_modal';
	} else if ($wages_type == 2) {
		// $wages_type = $this->lang->line('xin_employee_daily_wages');
		if ($pcount > 0) {
			$basic_salary = $pcount * $basic_salary;
		} else {
			$basic_salary = $pcount;
		}
		$p_class = 'emo_hourly_pay';
		$view_p_class = 'hourlywages_template_modal';
	} else {
		// $wages_type = $this->lang->line('xin_payroll_basic_salary');
		if ($system[0]->is_half_monthly == 1) {
			$basic_salary = $basic_salary / 2;
		} else {
			$basic_salary = $basic_salary;
		}
		$p_class = 'emo_monthly_pay';
		$view_p_class = 'payroll_template_modal';
	}

	//    rt logic for penchuanng

	// $pay_rate = $pay_basic['rate'];
	// $total_work_h = $pay_basic['total_work_h'];
	// $overt = $this->Employees_model->get_overtime($user_id, $pay_date);

	// $all_overtime_P = $overt['all_overtime_P'];
	// $all_overtime_t = $overt['all_overtime'];
	// $restday_pay1 = $this->Employees_model->restday_pay($user_id, $pay_date);
	$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id, $pay_date);

	$deduction_amount = 0;
	if ($employee_deduction) {
		foreach ($employee_deduction as $deduction) {
			if ($deduction->type_id == 1) {
				$deduction_amount +=  $deduction->amount;
			}
			if ($deduction->type_id == 2) {
				$from_month_year = date('Y-m', strtotime($deduction->from_date));
				$to_month_year = date('Y-m-d', strtotime($deduction->to_date));
				$pa_month = $pay_date;

				if ($from_month_year != "" && $to_month_year != "") {
					if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
						$deduction_amount +=  $deduction->amount;
					}
				}
			}
		}
	}
	// $get_cliam_data = $this->Employees_model->get_cliam_data($r->user_id, $pay_date);
	// print_r($all_overtime_P);die;
	// $restday_pay = $restday_pay1['rest_daya_amount'];
	// $hourly_rate = $pay;
	// $basic_salary = $pay;
	//    rt logic for penchuanng
	// new add
	$ordinary_wage = 0;
	$ow = 0;
	$ow_cpf_employer = 0;
	$ow_cpf_employee = 0;
	$aw = 0;
	$aw_cpf_employer = 0;
	$aw_cpf_employee = 0;
	// end new add




	$g_ordinary_wage = 0;
	$g_additional_wage = 0;
	$g_shg = 0;
	$g_sdl = 0;

	$g_ordinary_wage += $basic_salary;
	$g_shg += $basic_salary;
	$g_sdl += $basic_salary;

	// $g_ordinary_wage += $apeacial_allowance_amount;
	// $g_shg += $apeacial_allowance_amount;
	// $g_sdl += $apeacial_allowance_amount;

	$g_ordinary_wage -= $deduction_amount;
	$g_shg -= $deduction_amount;
	$g_sdl -= $deduction_amount;


	$pay_date = $this->input->get('pay_date');
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
	// $allowance_amount += $apeacial_allowance_amount;

	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	// $month_start_date = new DateTime($pay_date . '-01');
	$month_start_date = new DateTime('01-' . $pay_date);
	$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
	$month_end_date->modify('+1 day');
	$interval = new DateInterval('P1D');
	$holiday_array_new = array();

	$period = new DatePeriod($month_start_date, $interval, $month_end_date);
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
			// $pay_date_month = new DateTime($pay_date . '-01');
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
			// $holiday_array_new = array();

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
			// if(count($leave_period) > 0) {
			// 	if($l->is_half_day == 0) {
			// 		$leaves_taken_count += count($leave_period);
			// 	}else {
			// 		$leaves_taken_count += count($leave_period)  / 2;
			// 	}
			// }
		}
	}

	$no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count)) + $holidays_count;
	$gross_pay = round((($basic_salary + $gross_allowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
	$unpaid_leave_amount = ($basic_salary + $gross_allowance_amount) - $gross_pay;


	// echo 'Working days : '. $no_of_working_days . '<br>';
	// echo 'Holidays : '. $holidays_count . '<br>';
	// echo 'Leaves : '. $leaves_taken_count . '<br>';
	// echo 'Leave Days : ('. implode(", " , $leave_period)  . ')<br>';
	// echo 'Days Worked : '. $no_of_days_worked . '<br>';
	// echo 'Basic Pay : '. $g_ordinary_wage . '<br>';
	// echo 'Gross Pay : '. $gross_pay . '<br>';
	$g_ordinary_wage -= $unpaid_leave_amount;
	// echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
	// echo '<hr>';

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
	// 4: other payment
	$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
	// print_r($other_payments);die;

	$other_payments_amount = 0;
	if (!is_null($other_payments)) :
		foreach ($other_payments->result() as $sl_other_payments) {
			if ($system[0]->is_half_monthly == 1) {
				if ($system[0]->half_deduct_month == 2) {
					$epayments_amount = $sl_other_payments->payments_amount / 2;
				} else {
					$epayments_amount = $sl_other_payments->payments_amount;
				}
			} else {
				$epayments_amount = $sl_other_payments->payments_amount;
			}
			$other_payments_amount += $epayments_amount;
		}
	endif;
	// print_r($other_payments);die;
	$g_ordinary_wage += $other_payments_amount;
	$g_shg += $other_payments_amount;
	$g_sdl += $other_payments_amount;


	// 5: commissions
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

	// 6: statutory deductions
	$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
	if (!is_null($statutory_deductions)) :
		$statutory_deductions_amount = 0;
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $basic_salary;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				//$statutory_deductions_amount += $st_amount;
				// print_r($sta_salary);exit;
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


	// 7: overtime
	// $salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
	// $count_overtime = $this->Employees_model->count_employee_overtime($user_id);
	// $overtime_amount = 0;
	// if($count_overtime > 0) {
	// 	foreach($salary_overtime as $sl_overtime){
	// 		if($system[0]->is_half_monthly==1){
	// 			if($system[0]->half_deduct_month==2){
	// 				$eovertime_hours = $sl_overtime->overtime_hours/2;
	// 				$eovertime_rate = $sl_overtime->overtime_rate/2;
	// 			} else {
	// 				$eovertime_hours = $sl_overtime->overtime_hours;
	// 				$eovertime_rate = $sl_overtime->overtime_rate;
	// 			}
	// 		} else {
	// 			$eovertime_hours = $sl_overtime->overtime_hours;
	// 			$eovertime_rate = $sl_overtime->overtime_rate;
	// 		}
	// 		$overtime_amount += $eovertime_hours * $eovertime_rate;
	// 		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
	// 		//$overtime_amount += $overtime_total;
	// 	}
	// } else {
	// 	$overtime_amount = 0;
	// }
	$overtime_amount = 0;
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
			$week_hours = 0;
			if ($office_shift_id) {
				$shift = $this->Employees_model->read_shift_information($office_shift_id);
				if ($shift) {
					if ($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						$time1 = $shift[0]->monday_in_time;
						$time2 = $shift[0]->monday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						$time1 = $shift[0]->tuesday_in_time;
						$time2 = $shift[0]->tuesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						$time1 = $shift[0]->wednesday_in_time;
						$time2 = $shift[0]->wednesday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						$time1 = $shift[0]->thursday_in_time;
						$time2 = $shift[0]->thursday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						$time1 = $shift[0]->friday_in_time;
						$time2 = $shift[0]->friday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						$time1 = $shift[0]->saturday_in_time;
						$time2 = $shift[0]->saturday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
					if ($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						$time1 = $shift[0]->sunday_in_time;
						$time2 = $shift[0]->sunday_out_time;
						$time1 = explode(':', $time1);
						$time2 = explode(':', $time2);
						$hours1 = $time1[0];
						$hours2 = $time2[0];
						$mins1 = $time1[1];
						$mins2 = $time2[1];
						$hours = $hours2 - $hours1;
						$mins = 0;
						if ($hours < 0) {
							$hours = 24 + $hours;
						}
						if ($mins2 >= $mins1) {
							$mins = $mins2 - $mins1;
						} else {
							$mins = ($mins2 + 60) - $mins1;
							$hours--;
						}
						if ($mins > 0) {
							$hours += round($mins / 60, 2);
						}
						$week_hours += $hours;
					}
				}
			} else {
				$week_hours = 40;
			}
			$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
		}

		if ($ot_hrs > 0) {
			$overtime_amount = round($ot_hrs * $rate, 2);
		}
		if ($system[0]->is_half_monthly == 1) {
			if ($system[0]->half_deduct_month == 2) {
				$overtime_amount = $overtime_amount / 2;
			}
		}
	}
	// $overtime_amount = $all_overtime_P;
	// $g_ordinary_wage += $all_overtime_P;
	// all other payment
	// $LeaveEnchashment = $this->Employees_model->get_leave_Encashment($user_id, $pay_date);
	// $encamoun = $LeaveEnchashment['ench_amount'];

	$all_other_payment = $other_payments_amount + $share_options_amount;

	$gross_salary = $basic_salary + $allowance_amount + $commissions_amount + $overtime_amount + $all_other_payment;
	// add amount
	$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount;
	// add amount
	$net_salary_default = $add_salary - floatval($loan_de_amount) - $statutory_deductions_amount - $unpaid_leave_amount - $deduction_amount;
	$sta_salary = $allowance_amount + $basic_salary;

	$estatutory_deductions = $statutory_deductions_amount;
	// net salary + statutory deductions
	$net_salary = $net_salary_default;



	/**
	 * Author : Syed Anees
	 * Sub Functionality : CPF on Gross Salary
	 */
	$emp_dob = $date_of_birth;
	$dob = new DateTime($emp_dob);
	$today = new DateTime("01-" . $pay_date);
	$age = $dob->diff($today);
	$age_year = $age->y;
	$age_month = $age->m;

	$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
	$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
	$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

	if ($age_month > 0) {
		$age_year = $age_year + 1;
	}
	if ($age_year < $age_upto) {
		$age_from = null;
		$age_to = $age_year;
	} elseif ($age_year > $age_above) {
		$age_from = $age_year;
		$age_to = null;
	} elseif ($age_year > $age_upto && $age_year <= $age_above) {
		$age_from = $age_year;
		$age_to = $age_year;
	} else {
		$age_from = null;
		$age_to = null;
	}
	$immigration_id = 0;
	$im_status = $this->Employees_model->getEmployeeImmigrationStatus($user_id);
	$cpf_contribution = '';
	if ($im_status) {
		$immigration_id = $im_status->immigration_id;
		if ($immigration_id == 2) {
			$issue_date = $im_status->issue_date;
			$i_date = new DateTime($issue_date);
			$today = new DateTime();
			$pr_age = $i_date->diff($today);
			$pr_age_year = $pr_age->y;
			$pr_age_month = $pr_age->m;
			// if($pr_age_year == 0 && $pr_age_month > 0) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
			// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
			// 	// print_r($pr_age_month);exit;
			// }elseif($pr_age_year >= 2) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,1);
			// }
			// if($pr_age_year == 0 && $pr_age_month > 0) {
			if ($pr_age_year == 0 && ($pr_age_month > 0 || $pr_age_month == 0)) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} else if ($pr_age_year == 1 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
				// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
			} elseif ($pr_age_year == 1 && $pr_age_month > 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			} elseif ($pr_age_year == 2 && $pr_age_month == 0) {
				// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
				// print_r($pr_age_month);exit;
			}
			// elseif($pr_age_year >= 2) {
			// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
			// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,1);
			// }
			elseif ($pr_age_year >= 2) {
				$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
			}
		} elseif ($immigration_id == 1) {
			// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
			$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 1, $age_from, $age_to);
		}
		if ($immigration_id == 1 || $immigration_id == 2) {
			if ($cpf_contribution) {
				$employee_contribution = $cpf_contribution->contribution_employee;
				$employer_contribution = $cpf_contribution->contribution_employer;
				$total_cpf_contribution = $cpf_contribution->total_cpf;

				// $ordinary_wage = ($allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount ) - ($loan_de_amount + $statutory_deductions_amount);
				$ordinary_wage = $g_ordinary_wage;

				if ($ordinary_wage > $ordinary_wage_cap) {
					$ow = $ordinary_wage_cap;
				} else {
					$ow = $ordinary_wage;
				}

				$cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
				$ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
				$ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;


				$aw = $g_additional_wage;
				$cpf_total_aw = round(($total_cpf_contribution * $aw) / 100);
				$aw_cpf_employee = floor(($employee_contribution * $aw) / 100);
				$aw_cpf_employer = $cpf_total_aw - $aw_cpf_employee;


				$cpf_employee = $ow_cpf_employee + $aw_cpf_employee;
				$cpf_employer = $ow_cpf_employer + $aw_cpf_employer;


				if ($ow > 100) {
					$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				} else {
					$cpf_employee = 0;
				}
				$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				$net_salary = $net_salary - $cpf_employee;
				$cpf_total = $cpf_employee + $cpf_employer;
			}
		} else {
			$ordinary_wage = $g_ordinary_wage;
			$ow = $ordinary_wage;
			$ow_cpf_employee = 0;
			$ow_cpf_employer = 0;

			$aw = $g_additional_wage;
			$aw_cpf_employee = 0;
			$aw_cpf_employer = 0;
		}
	}
	// echo $emp_dob. ' Date of Birth <br>';
	// echo $age_month . ' Age Month <br>';
	// echo $age_year . ' Age year <br>';
	// echo $age_upto . ' Age Upto <br>';
	// echo $age_above . ' Age Above <br>';
	// echo $age_from . ' Age from <br>';
	// echo $age_to . ' Age To <br>';
	// exit;

	//leave deduction
	// $net_salary = $net_salary - $ashg_fund_deduction_amount;

	$shg_fund_deduction_amount = 0;
	//Other Fund Contributions
	$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);
	if ($employee_contributions) {
		$gross_s = $g_shg;
		$contribution_id = $employee_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$shg_fund_deduction_amount += $contribution_amount;
		$net_salary = $net_salary - $shg_fund_deduction_amount;
	}

	$ashg_fund_deduction_amount = 0;

	$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
	if ($employee_ashg_contributions) {
		$gross_s = $g_shg;

		$contribution_id = $employee_ashg_contributions->contribution_id;
		$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
		$ashg_fund_deduction_amount += $contribution_amount;
		$net_salary = $net_salary - $ashg_fund_deduction_amount;
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
	// $net_salary = $net_salary - $sdl_total_amount;

	$fund_deduction_amount = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
	$net_salary = number_format((float)$net_salary, 2, '.', '');

	// check
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
	$backup_data = $this->db->where('user_id', $user_id)->order_by('id', 'DESC')->limit(1)->get('salary_backup_table')->row();

	$net_balance = (float)$backup_data->total_allowances + (float) $backup_data->gross_salary - (float)$backup_data->total_loan - (float)$backup_data->total_employee_deduction + (float)$backup_data->employee_claim + (float) $backup_data->total_overtime - (float)$backup_data->leave_deductions + (float)$backup_data->total_commissions + (float)$backup_data->total_statutory_deductions + (float)$backup_data->total_other_payments + (float) $backup_data->total_share + (float)$backup_data->additional_allowances - (float) $backup_data->deduction_amount;

	// echo $gross_salary;exit;

	?>
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



	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><strong><?php echo $this->lang->line('xin_payment_for'); ?></strong>
			<?php echo $p_month; ?></h4>
	</div>
	<div class="modal-body" style="overflow:auto; height:530px;">
		<?php $attributes = array('name' => 'pay_hourly', 'id' => 'pay_hourly', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
		<?php $hidden = array('_method' => 'ADD'); ?>
		<?php echo form_open('admin/payroll/add_pay_hourly_again/', $attributes, $hidden); ?>
		<div class="sender-reciver">
			<hr>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Details</a></li>
					<li><a href="#tabs-2">Allowances & Deductions</a></li>

				</ul>
				<div id="tabs-1">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
								<input type="hidden" name="designation_id" value="<?php echo $designation_id; ?>" />
								<input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
								<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />

								<label for="name"><?php echo $this->lang->line('xin_payroll_hourly_rate'); ?></label>
								<input type="text" value="<?php echo $hourly_rate; ?>" class="form-control" name="pay_rate">


								<input type="hidden" id="emp_id" value="<?php echo $user_id ?>" name="emp_id">
								<input type="hidden" value="<?php echo $user_id; ?>" name="u_id">
								<input type="hidden" value="<?php echo $basic_salary; ?>" name="basic_salary">
								<input type="hidden" value="<?php echo $hourly_rate; ?>" name="gross_salary">
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
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_hours_worked_total'); ?></label>
								<input type="text" name="hours_worked" class="form-control" value="<?php echo $pcount; ?>">
							</div>
						</div>
						<div class="col-md-4">
							<label for="name">Basic Salary</label>
							<input type="checkbox" <?= ($backup_data->gross_salary > 0) ? 'checked' : 'disabled' ?> name="chk_gross_salary" id="chk_gross_salary" class="bc_checked_class" data-in_id="gross_salary_bc" data-ou_id="gross_salary">

							<input type="text" id="basic_salary" name="gross_salary" class="form-control " value="<?php echo $basic_salary; ?>" <?= ($backup_data->gross_salary > 0) ? '' : 'disabled' ?>>
							<input type="hidden" name="gross_salary_bc" id="gross_salary_bc">
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
								<input type="checkbox" name="chk_total_allowances" id="chk_total_allowances" class="bc_checked_class" <?= ($backup_data->total_allowances > 0) ? 'checked' : 'disabled' ?> data-in_id="total_allowances_bc" data-ou_id="total_allowances">
								<input type="hidden" name="total_allowances_bc" id="total_allowances_bc">

								<input type="text" name="total_allowances" class="form-control ontim_data_change" value="<?php echo $allowance_amount; ?>" <?= ($backup_data->total_allowances > 0) ? '' : 'disabled' ?> id="total_allowances">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
								<input type="checkbox" name="chk_total_commissions" id="chk_total_commissions" <?= ($backup_data->total_commissions > 0) ? 'checked' : 'disabled' ?> class="bc_checked_class" data-in_id="total_commissions_bc" data-ou_id="total_commissions">
								<input type="hidden" name="total_commissions_bc" id="total_commissions_bc">

								<input type="text" id="total_commissions" name="total_commissions" class="form-control ontim_data_change" value="<?php echo $commissions_amount; ?>" <?= ($backup_data->total_commissions > 0) ? '' : 'disabled' ?>>
							</div>
						</div>

					</div>
					<div class="row " style="display:none;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">General Allowance</label>
								<input type="checkbox" name="chk_General_amount" id="chk_General_amount" class="bc_checked_class" data-in_id="General_amount_bc" data-ou_id="General_amount" <?= ($backup_data->General_amount > 0) ? 'checked' : 'disabled' ?>>
								<input type="hidden" name="General_amount_bc" id="General_amount_bc">
								<input type="text" id="General_amount" name="General_amount" class="form-control" value="" <?= ($backup_data->General_amount > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Motivation Incentive</label>
								<input type="checkbox" name="chk_motivation_amount" id="chk_motivation_amount" class="bc_checked_class" data-in_id="motivation_amount_bc" data-ou_id="motivation_amount" <?= ($backup_data->motivation_amount > 0) ? 'checked' : 'disabled' ?>>
								<input type="hidden" name="motivation_amount_bc" id="motivation_amount_bc">
								<input type="text" id="motivation_amount" name="motivation_amount" class="form-control" value="" <?= ($backup_data->motivation_amount > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
								<input type="checkbox" name="chk_loan_de_amount" <?= ($backup_data->total_loan > 0) ? 'checked' : 'disabled' ?> id="chk_loan_de_amount" class="bc_checked_class" data-in_id="loan_de_amount_bc" data-ou_id="total_loan">
								<input type="hidden" name="loan_de_amount_bc" id="loan_de_amount_bc">

								<input type="text" name="total_loan" class="form-control" <?= ($backup_data->total_loan > 0) ? '' : 'disabled' ?> value="<?php echo $loan_de_amount; ?>" id="total_loan">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
								<input type="checkbox" name="chk_total_overtime" <?= ($backup_data->total_overtime > 0) ? 'checked' : 'disabled' ?> id="chk_total_overtime" class="bc_checked_class" data-in_id="total_overtime_bc" data-ou_id="total_overtime">
								<input type="hidden" name="total_overtime_bc" id="total_overtime_bc">

								<input type="text" name="total_overtime" class="form-control ontim_data_change" value="<?php echo $overtime_amount; ?>">
								<input type="hidden" name="total_overtime_amount" class="form-control" value="<?php echo $overtime_amount; ?>" id="total_overtime" <?= ($backup_data->total_overtime > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
								<input type="checkbox" name="chk_total_statutory_deductions" id="chk_total_statutory_deductions" class="bc_checked_class" <?= ($backup_data->total_statutory_deductions > 0) ? 'checked' : 'disabled' ?> data-in_id="total_statutory_deductions_bc" data-ou_id="total_statutory_deductions">
								<input type="hidden" name="total_statutory_deductions_bc" id="total_statutory_deductions_bc">

								<input type="text" name="total_statutory_deductions" class="form-control" value="<?php echo $statutory_deductions_amount; ?>" id="total_statutory_deductions" <?= ($backup_data->total_statutory_deductions > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
								<input type="checkbox" name="chk_total_other_payments" id="chk_total_other_payments" <?= ($backup_data->total_other_payments > 0) ? 'checked' : 'disabled' ?> class="bc_checked_class" data-in_id="total_other_payments_bc" data-ou_id="total_other_payments">
								<input type="hidden" name="total_other_payments_bc" id="total_other_payments_bc">

								<input type="text" name="total_other_payments" class="form-control ontim_data_change" value="<?php echo $all_other_payment; ?>" id="total_other_payments" <?= ($backup_data->total_other_payments > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<?php if (($immigration_id == 1 || $immigration_id == 2) && $cpf_contribution) { ?>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="total_cpf_employee">CPF Employee</label>
									<input type="text" name="total_cpf_employee" class="form-control" value="<?php echo $cpf_employee; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="total_cpf_employer">CPF Employer</label>
									<input type="text" name="total_cpf_employer" class="form-control" value="<?php echo $cpf_employer; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="total_cpf">Total CPF</label>
									<input type="text" name="total_cpf" class="form-control" value="<?php echo $cpf_total; ?>">
								</div>
							</div>
						</div>
					<?php }

					?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="total_employee_deduction">Employee Deduction</label>
								<input class="bc_checked_class" type="checkbox" <?= ($backup_data->deduction_amount > 0) ? 'checked' : 'disabled' ?> name="chk_total_employee_deduction" id="chk_total_employee_deduction" data-in_id="total_employee_deduction_bc" data-ou_id="total_employee_deduction">
								<input type="hidden" name="total_employee_deduction_bc" id="total_employee_deduction_bc">
								<input type="text" name="total_employee_deduction" id="total_employee_deduction" class="form-control" value="<?php echo $deduction_amount; ?>" <?= ($backup_data->deduction_amount > 0) ? '' : 'disabled' ?>>
							</div>
						</div>
					</div>
					<?php if ($employee_contributions || $fund_deduction_amount > 0) { ?>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="total_fund_contribution">Contribution Funds <small>(MBMF, SINDA, CDAC, ECF)</small></label>
									<!-- <input type="text" name="total_fund_contribution" class="form-control" value="<?php echo $fund_deduction_amount; ?>"> -->
									<input type="text" name="total_fund_contribution" class="form-control" value="<?php echo $contribution_amount; ?>">
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_net_salary'); ?></label>
								<input type="text" name="net_salary" class="form-control" value="<?php echo $net_salary; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
								<input type="text" name="payment_amount" class="form-control" value="<?php echo $net_balance; ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Balance Amount</label>
								<input type="text" id="b_am" name="b_am" class="form-control" value="0" readonly>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
								<input type="text" id="payment_amount_s" name="payment_amount" class="form-control" value="<?= $net_salary ?>" readonly>
							</div>
						</div>
						<div class="col-md-4">
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
										<div class="col-md-12">
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
		<script type="text/javascript">
			$(function() {
				$("#tabs").tabs();
			});
			$(document).ready(function() {
				$('.bc_checked_class').prop('checked', true);

				$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
				$('[data-plugin="select_hrm"]').select2({
					width: '100%'
				});

				$('.bc_checked_class').on("click", function(e) {
					// Get IDs from data attributes
					let input_i = $(this).data('in_id');
					let out_id = $(this).data('ou_id');

					// Retrieve input values
					let out_val = parseFloat($("#" + out_id).val()) || 0;
					let in_val = parseFloat($("#" + input_i).val()) || 0;

					// Retrieve balance and other relevant values
					let balance = parseFloat($("#b_am").val()) || 0;
					let payment_amount_s = parseFloat($("#payment_amount_s").val()) || 0;

					// Check if the checkbox is checked
					var isChecked = $(this).is(':checked');

					if (isChecked) {
						// When checked: Increase payment amount and decrease balance
						let updatedPaymentAmount = payment_amount_s + in_val;
						let updatedBalance = balance - in_val;

						// Update fields with new values
						$("#payment_amount_s").val(updatedPaymentAmount.toFixed(2));
						$("#b_am").val(updatedBalance.toFixed(2));

						// Reset the input value to 0
						$("#" + input_i).val("0");
					} else {
						// When unchecked: Decrease payment amount and increase balance
						let updatedPaymentAmount = payment_amount_s - out_val;
						let updatedBalance = balance + out_val;

						// Update fields with new values
						$("#payment_amount_s").val(updatedPaymentAmount.toFixed(2));
						$("#b_am").val(updatedBalance.toFixed(2));

						// Restore the input value to its original value
						$("#" + input_i).val(out_val);
					}
				});


				// On page load: datatable					
				$("#pay_hourly").submit(function(e) {

					/*Form Submit*/
					e.preventDefault();
					var obj = $(this),
						action = obj.attr('name');
					//$('.save').prop('disabled', true);
					$.ajax({
						type: "POST",
						url: e.target.action,
						data: obj.serialize() + "&is_ajax=11&data=hourly&add_type=add_pay_hourly&form=" +
							action,
						cache: false,
						success: function(JSON) {
							// console.log(JSON);
							if (JSON.error != '') {
								toastr.error(JSON.error);
								$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
								$('.save').prop('disabled', false);
							} else {
								$('.emo_hourly_pay_again').modal('toggle');

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
						}
					});
				});
			});
		</script>
	<?php } ?>