<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payment' && $_GET['type'] == 'monthly_payment') { ?>
	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime($this->input->get('pay_date'));
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
	$user = $this->Xin_model->read_user_info($user_id);
	$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id);
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



	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	// $month_start_date = new DateTime($pay_date . '-01');
	$month_start_date = new DateTime('01-' . $pay_date);
	$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
	$month_end_date->modify('+1 day');
	$interval = new DateInterval('P1D');
	$period = new DatePeriod($month_start_date, $interval, $month_end_date);
	foreach ($period as $p) {
		$p_day = $p->format('l');
		$p_date = $p->format('Y-m-d');

		//holidays in a month
		$is_holiday = $this->Timesheet_model->is_holiday_on_date($company_id, $p_date);
		if ($is_holiday) {
			$holidays_count += 1;
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
					if ($office_shift[0]->monday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Tuesday') {
					if ($office_shift[0]->tuesday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Wednesday') {
					if ($office_shift[0]->wednesday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Thursday') {
					if ($office_shift[0]->thursday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Friday') {
					if ($office_shift[0]->friday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Saturday') {
					if ($office_shift[0]->saturday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Sunday') {
					if ($office_shift[0]->sunday_in_time != '') {
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
	$rate = 0;
	$holiday_arr = array();
	$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($user_id, $pay_date);
	if ($overtime) {
		$ot_hrs = 0;
		$ot_mins = 0;
		$overtime_date = array();
		foreach ($overtime as $ot) {
			$total_hours = explode(':', $ot->total_hours);
			$ot_hrs = $total_hours[0];
			$ot_mins = $total_hours[1];
			$overtime_date[]=$ot->overtime_date;  

			// // my code start for multiple ot
			if ($ot_mins > 0) {
				$ot_hrs = round($ot_mins / 60, 2);
			}
			// echo $ot_hrs."<br>";
			$get_day = strtotime($ot->overtime_date);
			$day = date('l', $get_day);

			$h_date_chck = $this->Timesheet_model->holiday_date_check($ot->overtime_date);
			$holiday_arr = array();
			if($h_date_chck->num_rows() == 1){
				$h_date = $this->Timesheet_model->holiday_date($ot->overtime_date);
				$begin = new DateTime( $h_date[0]->start_date );
				$end = new DateTime( $h_date[0]->end_date);
				$end = $end->modify( '+1 day' ); 
				
				$interval = new DateInterval('P1D');
				$daterange = new DatePeriod($begin, $interval ,$end);
				
				foreach($daterange as $date){
					$holiday_arr[] =  $date->format("d-m-Y");
				}
			} else {
				$holiday_arr[] = '99-99-99';
			}
			$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id,2);
			// echo (in_array($ot->overtime_date,$holiday_arr));
			if(in_array($ot->overtime_date,$holiday_arr)) { // holiday
				$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id,3);
				$rate = $overtime_rate->overtime_pay_rate;
			}
			else if($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
				$rate = $overtime_rate->overtime_pay_rate;	
			} else if($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
				$rate = $overtime_rate->overtime_pay_rate;
			} else if($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
				$rate = $overtime_rate->overtime_pay_rate;
			} else if($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
				$rate = $overtime_rate->overtime_pay_rate;
			} else if($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
				$rate = $overtime_rate->overtime_pay_rate;
			} else if($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
				$rate = $overtime_rate->overtime_pay_rate;
			} else if($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
				$rate = $overtime_rate->overtime_pay_rate;
			} else{
				$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id,1);
				$rate = $overtime_rate->overtime_pay_rate;
			}
			if ($ot_hrs > 0) {
				$overtime_amount += round($ot_hrs * $rate, 2);
			}
			// end code
		}
		
		// foreach($overtime_date as $ov){
		// 	$get_day = strtotime($ov);
		// 	$day = date('l', $get_day);

		// 	$h_date_chck = $this->Timesheet_model->holiday_date_check($ov);
		// 	$holiday_arr = array();
		// 	if($h_date_chck->num_rows() == 1){
		// 		$h_date = $this->Timesheet_model->holiday_date($ov);
		// 		$begin = new DateTime( $h_date[0]->start_date );
		// 		$end = new DateTime( $h_date[0]->end_date);
		// 		$end = $end->modify( '+1 day' ); 
				
		// 		$interval = new DateInterval('P1D');
		// 		$daterange = new DatePeriod($begin, $interval ,$end);
				
		// 		foreach($daterange as $date){
		// 			$holiday_arr[] =  $date->format("d-m-Y");
		// 		}
		// 	} else {
		// 		$holiday_arr[] = '99-99-99';
		// 	}
		// 	$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id,2);
		// 	// echo (in_array($ov,$holiday_arr));
		// 	if(in_array($ov,$holiday_arr)) { // holiday
		// 		$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id,3);
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	}
		// 	else if($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;	
		// 	} else if($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	} else if($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	} else if($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	} else if($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	} else if($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	} else if($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	} else{
		// 		$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id,1);
		// 		$rate = $overtime_rate->overtime_pay_rate;
		// 	}	
		// }
		// my code end for multiple ot



		//overtime rate
		// $overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($user_id);
		// if ($overtime_rate) {
		// 	$rate = $overtime_rate->overtime_pay_rate;
		// } else {
		// 	$week_hours = 0;
		// 	if ($office_shift_id) {
		// 		$shift = $this->Employees_model->read_shift_information($office_shift_id);
		// 		if ($shift) {
		// 			if ($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
		// 				$time1 = $shift[0]->monday_in_time;
		// 				$time2 = $shift[0]->monday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 			if ($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
		// 				$time1 = $shift[0]->tuesday_in_time;
		// 				$time2 = $shift[0]->tuesday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 			if ($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
		// 				$time1 = $shift[0]->wednesday_in_time;
		// 				$time2 = $shift[0]->wednesday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 			if ($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
		// 				$time1 = $shift[0]->thursday_in_time;
		// 				$time2 = $shift[0]->thursday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 			if ($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
		// 				$time1 = $shift[0]->friday_in_time;
		// 				$time2 = $shift[0]->friday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 			if ($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
		// 				$time1 = $shift[0]->saturday_in_time;
		// 				$time2 = $shift[0]->saturday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 			if ($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
		// 				$time1 = $shift[0]->sunday_in_time;
		// 				$time2 = $shift[0]->sunday_out_time;
		// 				$time1 = explode(':', $time1);
		// 				$time2 = explode(':', $time2);
		// 				$hours1 = $time1[0];
		// 				$hours2 = $time2[0];
		// 				$mins1 = $time1[1];
		// 				$mins2 = $time2[1];
		// 				$hours = $hours2 - $hours1;
		// 				$mins = 0;
		// 				if ($hours < 0) {
		// 					$hours = 24 + $hours;
		// 				}
		// 				if ($mins2 >= $mins1) {
		// 					$mins = $mins2 - $mins1;
		// 				} else {
		// 					$mins = ($mins2 + 60) - $mins1;
		// 					$hours--;
		// 				}
		// 				if ($mins > 0) {
		// 					$hours += round($mins / 60, 2);
		// 				}
		// 				$week_hours += $hours;
		// 			}
		// 		}
		// 	} else {
		// 		$week_hours = 40;
		// 	}
		// 	$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
		// }



		// if ($ot_hrs > 0) {
		// 	$overtime_amount = round($ot_hrs * $rate, 2);
		// }
		if ($system[0]->is_half_monthly == 1) {
			if ($system[0]->half_deduct_month == 2) {
				$overtime_amount = $overtime_amount / 2;
			}
		}
		$g_ordinary_wage += $overtime_amount;
	}

	// all other payment
	$all_other_payment = $other_payments_amount + $share_options_amount;

	$gross_salary = $basic_salary + $allowance_amount + $commissions_amount + $overtime_amount + $all_other_payment;
	// add amount
	$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount;
	// add amount
	$net_salary_default = $add_salary - $loan_de_amount - $statutory_deductions_amount - $unpaid_leave_amount;
	$sta_salary = $allowance_amount + $basic_salary;

	$estatutory_deductions = $statutory_deductions_amount;
	// net salary + statutory deductions
	$net_salary = $net_salary_default;

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
			// if ($cpf_contribution) {
			// 	$employee_contribution = $cpf_contribution->contribution_employee;
			// 	$employer_contribution = $cpf_contribution->contribution_employer;
			// 	$total_cpf_contribution = $cpf_contribution->total_cpf;

				// $ordinary_wage = ($allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount ) - ($loan_de_amount + $statutory_deductions_amount);
				$ordinary_wage = $gross_salary;

				if ($ordinary_wage > $ordinary_wage_cap) {
					$ow = $ordinary_wage_cap;
				} else {
					$ow = $ordinary_wage;
				}
				
				// $cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
				// $ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
				// $ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;


				$aw = $g_additional_wage;
				// $cpf_total_aw = round(($total_cpf_contribution * $aw) / 100);
				// $aw_cpf_employee = floor(($employee_contribution * $aw) / 100);
				// $aw_cpf_employer = $cpf_total_aw - $aw_cpf_employee;


				// $cpf_employee = $ow_cpf_employee + $aw_cpf_employee;
				// $cpf_employer = $ow_cpf_employer + $aw_cpf_employer;
				/* new CPF calculation Start*/
				$tw = $ow + $aw;

				if ($im_status->issue_date != "") {
					if ($pr_age_year == 1) {

						if ($age_year <= 55) {
							if ($tw > 50 && $tw < 500) {
								$cpf_employer = round(4 / 100 * $tw);
								$cpf_employee = 0;
							} else if ($tw > 500 && $tw < 750) {
								$cpf_employee = floor(0.15 * ($tw - 500));
								$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
								$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

								if ($count_cpf_employee > 340) {
									$cpf_employee = 340;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								if ($count_total_cpf > 612) {
									$total_cpf = 612;
								} else {
									$total_cpf = $count_total_cpf;
								}
								$cpf_employer = round($total_cpf - $cpf_employee);

							}
						} else if ($age_year > 55 && $age_year <= 60) {
							if ($tw > 50 && $tw < 500) {
								$cpf_employer = round(4 / 100 * $tw);
								$cpf_employee = 0;
							} else if ($tw > 500 && $tw < 750) {
								$cpf_employee = floor(0.15 * ($tw - 500));
								$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
								$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

								if ($count_cpf_employee > 340) {
									$cpf_employee = 340;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								if ($count_total_cpf > 612) {
									$total_cpf = 612;
								} else {
									$total_cpf = $count_total_cpf;
								}
								$cpf_employer = round($total_cpf - $cpf_employee);

							}
						} else if ($age_year > 60 && $age_year <= 65) {
							if ($tw > 50 && $tw < 500) {
								$cpf_employer = round(3.5 / 100 * $tw);
								$cpf_employee = 0;
							} else if ($tw > 500 && $tw < 750) {
								$cpf_employee = floor(0.15 * ($tw - 500));
								$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
								$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

								if ($count_cpf_employee > 340) {
									$cpf_employee = 340;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								if ($count_total_cpf > 578) {
									$total_cpf = 578;
								} else {
									$total_cpf = $count_total_cpf;
								}
								$cpf_employer = round($total_cpf - $cpf_employee);

							}
						} else if ($age_year > 65) {
							if ($tw > 50 && $tw < 500) {
								$cpf_employer = round(3.5 / 100 * $tw);
								$cpf_employee = 0;
							} else if ($tw > 500 && $tw < 750) {
								$cpf_employee = floor(0.15 * ($tw - 500));
								$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
								$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

								if ($count_cpf_employee > 340) {
									$cpf_employee = 340;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								if ($count_total_cpf > 578) {
									$total_cpf = 578;
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
								$cpf_employer = round(9/100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.45 * ($tw - 500));
								$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
								$cpf_employer = round($total_cpf-$cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
								$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
								if ($count_total_cpf > 1632) {
									$total_cpf = 1632;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 1020) {
									$cpf_employee = 1020;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer = round($total_cpf-$cpf_employee);
							}
						} else if ($age_year > 55 && $age_year <= 60) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(6 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.375 * ($tw - 500));
								$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
								$cpf_employer = round($total_cpf-$cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
								$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
								if ($count_total_cpf > 1258) {
									$total_cpf = 1258;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 850) {
									$cpf_employee = 850;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer = round($total_cpf-$cpf_employee);
							}
						} else if ($age_year > 60 && $age_year <= 65) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(3.5 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.225 * ($tw - 500));
								$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
								$cpf_employer = round($total_cpf-$cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
								$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
								if ($count_total_cpf > 748) {
									$total_cpf = 748;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 510) {
									$cpf_employee = 510;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer =round($total_cpf-$cpf_employee);
							}
						} else if ($age_year > 65) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(3.5 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.15 * ($tw - 500));
								$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
								$cpf_employer = round($total_cpf-$cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
								$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
								if ($count_total_cpf >578) {
									$total_cpf = 578;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 340) {
									$cpf_employee =340;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer = round($total_cpf-$cpf_employee);
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

							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.6 * ($tw - 500));
								$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
								$cpf_employer =round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
								$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
								if ($count_total_cpf > 2516) {
									$total_cpf = 2516;
								} else {
									$total_cpf= $count_total_cpf;
								}
								if ($count_cpf_employee > 1360) {
									$cpf_employee = 1360;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer= $total_cpf - $cpf_employee;
							}
						} else if ($age_year > 55 && $age_year <= 60) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(15 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.48 * ($tw - 500));
								$total_cpf = 15 / 100 * ($tw) + 0.48 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 16 / 100 * $ow + 16 / 100 * $aw;
								$count_total_cpf = 31 / 100 * ($ow) + 31 / 100 * ($aw);
								if ($count_total_cpf > 2108) {
									$total_cpf = 2108;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 1088) {
									$cpf_employee = 1088;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer= round($total_cpf - $cpf_employee);
							}
						} else if ($age_year > 60 && $age_year <= 65) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(11.5 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.315 * ($tw - 500));
								$total_cpf = 11.5 / 100 * $tw + 0.315 * ($tw - 500);
								$cpf_employer =round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 10.5 / 100 * $ow + 10.5 / 100 * $aw;
								$count_total_cpf = 22 / 100 * $ow + 22 / 100 * $aw;

								if ($count_total_cpf > 1496) {
									$total_cpf = 1496;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 714) {
									$cpf_employee = 714;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer =round($total_cpf - $cpf_employee);
							}
						} else if ($age_year > 65 && $age_year <= 70) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(9 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.225 * ($tw - 500));
								$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
								$cpf_employer =round($total_cpf - $cpf_employee);

							} else if ($tw > 750) {
								$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
								$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

								if ($count_total_cpf > 1122) {
									$total_cpf = 1122;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 510) {
									$cpf_employee = 510;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer =round($total_cpf - $cpf_employee);

							}
						} else if ($age_year > 70) {
							if ($tw < 50) {
								$cpf_employee = 0;
								$cpf_employer = 0;
							} else if ($tw > 50 && $tw <= 500) {
								$cpf_employee = 0;
								$cpf_employer = round(7.5 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.15 * ($tw - 500));
								$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
								$cpf_employer =round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
								$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

								if ($count_total_cpf > 850) {
									$total_cpf = 850;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 340) {
									$cpf_employee = 340;
								} else {
									$cpf_employee = floor($count_cpf_employee);
								}
								$cpf_employer =round($total_cpf - $cpf_employee);

							}
						} 
					}
				}
				if($immigration_id == 1){
					
					if ($age_year <= 55) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(17 / 100 * $tw);
						} else if ($tw < 500 && $tw <= 750) {
							$cpf_employee = floor(0.6 * ($tw - 500));
							$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
							$cpf_employer=round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
							$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
							if ($count_total_cpf > 2516) {
								$count_total_cpf =2516;
							} else {
								$total_cpf = $count_total_cpf;
							}
							

							if ($count_cpf_employee > 1360) {
								$cpf_employee = 1360;
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
							$cpf_employer = round(15 / 100 * $tw);
						} else if ($tw < 500 && $tw <= 750) {
							$cpf_employee = floor(0.48 * ($tw - 500));
							$total_cpf = 15 / 100 * ($tw) + 0.48 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 16 / 100 * $ow + 16 / 100 * $aw;
							
							$count_total_cpf = 31 / 100 * ($ow) + 31 / 100 * ($aw);
							if ($count_total_cpf > 2108) {
								$count_total_cpf = 2108;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 1088) {
								$cpf_employee = 1088;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf -$cpf_employee);
						}
					} else if ($age_year > 60 && $age_year <= 65) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(11.5 / 100 * $tw);
						} else if ($tw < 500 && $tw <= 750) {
							$cpf_employee = floor(0.315 * ($tw - 500));
							$total_cpf = 11.5 / 100 * $tw + 0.315 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 10.5 / 100 * $ow + 10.5 / 100 * $aw;
							$count_total_cpf = 22 / 100 * $ow + 22 / 100 * $aw;

							if ($count_total_cpf > 1496) {
								$total_cpf = 1496;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 714) {
								$cpf_employee = 714;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer = round($total_cpf-$cpf_employee);
						}
					}  else if ($age_year > 65 && $age_year <= 70) {
						if ($tw < 50) {
							$cpf_employee = 0;
							$cpf_employer = 0;
						} else if ($tw > 50 && $tw <= 500) {
							$cpf_employee = 0;
							$cpf_employer = round(9 / 100 * $tw);
						} else if ($tw < 500 && $tw <= 750) {
							$cpf_employee = floor(0.225 * ($tw - 500));
							$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
							$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

							if ($count_total_cpf > 1122) {
								$total_cpf = 1122;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 510) {
								$cpf_employee = 510;
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
						} else if ($tw < 500 && $tw <= 750) {
							$cpf_employee = floor(0.15 * ($tw - 500));
							$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
							$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

							if ($count_total_cpf > 850) {
								$total_cpf = 850;
							} else {
								$total_cpf = $count_total_cpf;
							}
							if ($count_cpf_employee > 340) {
								$cpf_employee = 340;
							} else {
								$cpf_employee = floor($count_cpf_employee);
							}
							$cpf_employer=round($total_cpf-$cpf_employee);
						}
					}
				}
				/* new CPF calculation End*/


				if ($ow > 500) {
					$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				} else {
					$cpf_employee = 0;
				}
				$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				//$net_salary = $net_salary - $cpf_employee;
				$cpf_total = $cpf_employee + $cpf_employer;
			//}
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
	/* CPF END */
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
	$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
	if (!is_null($_des_name)) {
		$_designation_name = $_des_name[0]->designation_name;
	} else {
		$_designation_name = '';
	}
	$company = $this->Xin_model->read_company_info($company_id);

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
								<span><?php echo $user[0]->date_of_birth; ?></span>
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
								<span><?php echo $user[0]->date_of_joining; ?></span>
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
								<input type="text" name="gross_salary" id="gross_salary" class="form-control" value="<?php echo $basic_salary; ?>" onkeyup="change_paid_amount()">
								<input type="hidden" id="emp_id" value="<?php echo $user_id ?>" name="emp_id">
								<input type="hidden" id="u_id" value="<?php echo $user_id; ?>" name="u_id">
								<input type="hidden" value="<?php echo $basic_salary; ?>" name="basic_salary">
								<!-- <input type="hidden" value="<?php echo $gross_salary; ?>" name="gross_salary"> -->
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
								<input type="text" name="leave_deductions" id="leave_deductions" class="form-control" value="<?php echo ($unpaid_leave_amount > 0 ? $unpaid_leave_amount : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
								<input type="text" name="total_allowances" id="total_allowances" class="form-control" value="<?php echo ($allowance_amount > 0 ? $allowance_amount : 0); ?>" onkeyup="change_paid_amount()" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
								<input type="text" name="total_commissions" id="total_commissions" class="form-control" value="<?php echo ($commissions_amount > 0 ? $commissions_amount : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
								<input type="text" name="total_loan" id="total_loan" class="form-control" value="<?php echo ($loan_de_amount > 0 ? $loan_de_amount : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
								<input type="text" name="total_overtime" id="total_overtime" class="form-control" value="<?php echo ($overtime_amount > 0 ? $overtime_amount : 0); ?>" onkeyup="change_paid_amount();">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
								<input type="text" name="total_statutory_deductions" id="total_statutory_deductions" class="form-control" value="<?php echo ($estatutory_deductions > 0 ? $estatutory_deductions : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
								<input type="text" id="total_other_payments" name="total_other_payments" class="form-control" value="<?php echo ($all_other_payment > 0 ? $all_other_payment : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
					</div>
					<?php //if(($immigration_id == 1 || $immigration_id == 2) && $cpf_contribution) {
					?>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employee">CPF Employee</label>
								<input type="text" name="total_cpf_employee" id="total_cpf_employee" class="form-control" value="<?php echo (isset($cpf_employee) && $cpf_employee > 0 ? $cpf_employee : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_employee_deduction">Employee Deduction</label>
								<input type="text" name="total_employee_deduction" id="total_employee_deduction" class="form-control" value="<?php echo (isset($deduction_amount) && $deduction_amount > 0 ? $deduction_amount : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employer">CPF Employer</label>
								<input type="text" name="cpf_employer" id="cpf_employer" class="form-control" value="<?php echo (isset($cpf_employer) && $cpf_employer > 0 ? $cpf_employer : 0); ?>" onkeyup="change_paid_amount()">
							</div>

						</div>
						<div class="col-md-6">
							<label for="total_cpf_employer">Total CPF</label>
							<input type="text" name="total_cpf_employer" id="total_cpf_employer" class="form-control" value="<?php echo (isset($cpf_total) && $cpf_total > 0 ? $cpf_total : 0); ?>">
						</div>
					</div>
					<div class="row" style="margin: 5px;">

						<div class="col-md-6" style="display: none;">
							<div class="form-group">
								<label for="total_cpf">Total CPF</label>
								<input type="text" name="total_cpf" id="total_cpf" class="form-control" value="<?php echo (isset($cpf_total) && $cpf_total > 0 ? $cpf_total : 0); ?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin: 5px;">
						<!-- </div> -->
						<?php //} 
						?>

						<?php
						$total_contribution = $shg_fund_deduction_amount + $ashg_fund_deduction_amount;
						?>
						<!-- <div class="row" style="margin: 5px;"> -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_cpf_employer">Employee Claim</label>
								<input type="text" name="employee_claim" id="employee_claim" class="form-control" value="<?php echo (isset($claim_amount) && $claim_amount > 0 ? $claim_amount : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_fund_contribution">Contribution Funds <small>(MBMF, SINDA, CDAC, ECF)</small></label>
								<input type="text" name="total_fund_contribution" id="total_fund_contribution" class="form-control" value="<?php echo ($total_contribution > 0 ? $total_contribution : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
						<!-- </div> -->
						<?php //}
						?>
						<?php // if($share_options_amount || $share_options_amount > 0) {
						?>
						<!-- <div class="row" style="margin: 5px;"> -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_share">Share Options Amount</label>
								<input type="text" name="total_share" id="total_share" class="form-control" value="<?php echo ($share_options_amount > 0 ? $share_options_amount : 0); ?>" onkeyup="change_paid_amount()">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Additional Allowance</label>
								<input type="text" name="additional_allowances" id="additional_allowances" class="form-control" value="0" onkeyup="change_paid_amount()">
							</div>
						</div>
					</div>
					<?php //}
					?>
					<div class="row" style="margin: 5px;">
						<input type="hidden" name="total_cpf_employer" id="total_cpf_employer" class="form-control" value="<?php echo (isset($cpf_employer) && $cpf_employer > 0 ? $cpf_employer : 0); ?>" onkeyup="change_paid_amount()">

						<!-- <div class="col-md-6">
					<div class="form-group">
						<label for="total_cpf_employer">CPF Employer</label>
						<input type="text" name="total_cpf_employer" id="total_cpf_employer" class="form-control" value="<?php echo (isset($cpf_employer) && $cpf_employer > 0 ? $cpf_employer : 0); ?>" onkeyup="change_paid_amount()">
					</div>
					</div> -->
					</div>
					<div class="row" style="margin: 5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_net_salary'); ?></label>
								<input type="text" id="net_salary" name="net_salary" class="form-control" value="<?php echo $net_salary; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
								<input type="text" id="payment_amount" name="payment_amount" class="form-control" value="<?php echo $net_salary; ?>">
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
					$employee_deduction = $this->Payroll_model->get_deduction_detail($user_id);

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
			$("#tabs").tabs();
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			if ($("#total_overtime").val() != "") {
				var overtime = $("#total_overtime").val();
			} else {
				var overtime = 0;
			}
			var total_amount = $("#payment_amount").val();
			var net_salary = $("#net_salary").val();
			var gross_salary = $("#gross_salary").val();
			if ($("#total_other_payments").val() != "") {
				var extra_payment = $("#total_other_payments").val();
			} else {
				var extra_payment = 0;
			}
			if ($("#leave_deductions").val() != "") {
				var leave_deductions = $("#leave_deductions").val();
			} else {
				var leave_deductions = 0;
			}
			if ($("#total_allowances").val() != "") {
				var total_allowances = $("#total_allowances").val();
			} else {
				var total_allowances = 0;
			}
			if ($("#total_commissions").val() != "") {
				var total_commissions = $("#total_commissions").val();
			} else {
				var total_commissions = 0;
			}
			if ($("#total_loan").val() != "") {
				var total_loan = $("#total_loan").val();
			} else {
				var total_loan = 0;
			}
			if ($("#total_statutory_deductions").val() != "") {
				var total_statutory_deductions = $("#total_statutory_deductions").val();
			} else {
				var total_statutory_deductions = 0;
			}
			if ($("#total_cpf_employee").val() != "") {
				var total_cpf_employee = $("#total_cpf_employee").val();
			} else {
				var total_cpf_employee = 0;
			}
			// if($("#total_cpf_employer").val() !=""){
			// var total_cpf_employer =$("#total_cpf_employer").val();
			// }else{
			// 	var total_cpf_employer =0;
			// }
			if ($("#total_employee_deduction").val() != "") {
				var total_employee_deduction = $("#total_employee_deduction").val();
			} else {
				var total_employee_deduction = 0;
			}

			if ($("#total_fund_contribution").val() != "") {
				var total_fund_contribution = $("#total_fund_contribution").val();
			} else {
				var total_fund_contribution = 0;
			}
			if ($("#total_share").val() != "") {
				var total_share = $("#total_share").val();
			} else {
				var total_share = 0;
			}

			if ($("#employee_claim").val() != "") {
				var claim_amount = $("#employee_claim").val();
			} else {
				var claim_amount = 0;
			}
			let new_amount = parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(extra_payment) - parseFloat(leave_deductions) + parseFloat(total_allowances) + parseFloat(total_commissions) - parseFloat(total_loan) - parseFloat(total_statutory_deductions) - parseFloat(total_cpf_employee) - parseFloat(total_employee_deduction) - parseFloat(total_fund_contribution) + parseFloat(total_share) + parseFloat(claim_amount);
			$("#payment_amount").val(new_amount);
			$("#net_salary").val(new_amount);

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});

			// On page load: datatable					
			$("#pay_monthly").submit(function(e) {

				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				//$('.save').prop('disabled', true);
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


		function add_extra_amount() {
			var extra_payment = $("#total_other_payments").val();
			var total_amount = $("#payment_amount").val();
			var net_salary = $("#net_salary").val();
			var gross_salary = $("#gross_salary").val();
			var overtime = $("#total_overtime").val();

			if (extra_payment != "") {
				let new_amount = parseFloat(gross_salary) + parseFloat(extra_payment) + parseFloat(overtime);
				$("#payment_amount").val(new_amount);
				$("#net_salary").val(new_amount);

			} else {
				let new_amount = parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(0);
				$("#payment_amount").val(new_amount);
				$("#net_salary").val(new_amount);

				//$("#total_other_payments").val(0);
			}
		}
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'hourly_payment' && $_GET['type'] == 'fhourly_payment') { ?>
	<?php
	$system = $this->Xin_model->read_setting_info(1);
	$payment_month = strtotime('01-' . $this->input->get('pay_date'));
	$p_month = date('F Y', $payment_month);
	$basic_salary = $basic_salary;
	$hourly_rate = $basic_salary;

	$pay_date = $this->input->get('pay_date');

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

	//3: Gross rate of pay (unpaid leave deduction)
	$holidays_count = 0;
	$no_of_working_days = 0;
	// $month_start_date = new DateTime($pay_date . '-01');
	$month_start_date = new DateTime('01-' . $pay_date);
	$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
	$month_end_date->modify('+1 day');
	$interval = new DateInterval('P1D');
	$period = new DatePeriod($month_start_date, $interval, $month_end_date);
	foreach ($period as $p) {
		$p_day = $p->format('l');
		$p_date = $p->format('Y-m-d');

		//holidays in a month
		$is_holiday = $this->Timesheet_model->is_holiday_on_date($company_id, $p_date);
		if ($is_holiday) {
			$holidays_count += 1;
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
					if ($office_shift[0]->monday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Tuesday') {
					if ($office_shift[0]->tuesday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Wednesday') {
					if ($office_shift[0]->wednesday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Thursday') {
					if ($office_shift[0]->thursday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Friday') {
					if ($office_shift[0]->friday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Saturday') {
					if ($office_shift[0]->saturday_in_time != '') {
						$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
						if ($l->is_half_day == 0) {
							$leaves_taken_count += 1;
						} else {
							$leaves_taken_count += 0.5;
						}
					}
				} else if ($p_day == 'Sunday') {
					if ($office_shift[0]->sunday_in_time != '') {
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
	// all other payment
	$all_other_payment = $other_payments_amount + $share_options_amount;

	$gross_salary = $basic_salary + $allowance_amount + $commissions_amount + $overtime_amount + $all_other_payment;
	// add amount
	$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount;
	// add amount
	$net_salary_default = $add_salary - $loan_de_amount - $statutory_deductions_amount - $unpaid_leave_amount;
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

	// echo $gross_salary;exit;

	?>
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
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
					<input type="hidden" name="designation_id" value="<?php echo $designation_id; ?>" />
					<input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
					<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />
					<label for="name"><?php echo $this->lang->line('xin_payroll_hourly_rate'); ?></label>
					<input type="text" name="gross_salary" class="form-control" value="<?php echo $hourly_rate; ?>">
					<input type="hidden" id="emp_id" value="<?php echo $user_id ?>" name="emp_id">
					<input type="hidden" value="<?php echo $user_id; ?>" name="u_id">
					<input type="hidden" value="<?php echo $hourly_rate; ?>" name="basic_salary">
					<input type="hidden" value="<?php echo $gross_salary; ?>" name="gross_salary">
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
			<div class="col-md-6">
				<div class="form-group">
					<label for="name"><?php echo $this->lang->line('xin_payroll_hours_worked_total'); ?></label>
					<input type="text" name="hours_worked" class="form-control" value="<?php echo $pcount; ?>">
				</div>
			</div>
		</div>
		<?php

		?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance'); ?></label>
					<input type="text" name="total_allowances" class="form-control" value="<?php echo $allowance_amount; ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="name"><?php echo $this->lang->line('xin_hr_commissions'); ?></label>
					<input type="text" name="total_commissions" class="form-control" value="<?php echo $commissions_amount; ?>">
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="name"><?php echo $this->lang->line('xin_payroll_total_loan'); ?></label>
					<input type="text" name="total_loan" class="form-control" value="<?php echo $loan_de_amount; ?>>
            </div>
        </div>
        <div class=" col-md-6" style="margin: 5px;">
					<div class="form-group">
						<label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime'); ?></label>
						<input type="text" name="total_overtime" class="form-control" value="<?php echo $overtime_amount; ?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></label>
						<input type="text" name="total_statutory_deductions" class="form-control" value="<?php echo $statutory_deductions_amount; ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></label>
						<input type="text" name="total_other_payments" class="form-control" value="<?php echo $all_other_payment; ?>">
					</div>
				</div>
			</div>
			<?php if (($immigration_id == 1 || $immigration_id == 2) && $cpf_contribution) { ?>
				<div class="row" style="margin: 5px;">
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
			<?php if ($employee_contributions || $fund_deduction_amount > 0) { ?>
				<div class="row" style="margin: 5px;">
					<div class="col-md-12">
						<div class="form-group">
							<label for="total_fund_contribution">Contribution Funds <small>(MBMF, SINDA, CDAC, ECF)</small></label>
							<!-- <input type="text" name="total_fund_contribution" class="form-control" value="<?php echo $fund_deduction_amount; ?>"> -->
							<input type="text" name="total_fund_contribution" class="form-control" value="<?php echo $contribution_amount; ?>">
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row" style="margin: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="name"><?php echo $this->lang->line('xin_payroll_net_salary'); ?></label>
						<input type="text" name="net_salary" class="form-control" value="<?php echo $net_salary; ?>">
					</div>
				</div>
				<div class="col-md-6" style="margin: 5px;">
					<div class="form-group">
						<label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount'); ?></label>
						<input type="text" name="payment_amount" class="form-control" value="<?php echo $net_salary; ?>">
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
			<div class="form-actions box-footer">
				<?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_pay'))); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
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
<?php } ?>