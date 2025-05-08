<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the HRSALE License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.hrsale.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hrsalesoft@gmail.com so we can send you a copy immediately.
 *
 * @author   HRSALE
 * @author-email  hrsalesoft@gmail.com
 * @copyright  Copyright © hrsale.com. All Rights Reserved
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Pdf');
		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
		$this->load->model("Timesheet_model");
		$this->load->model("Overtime_request_model");
		$this->load->model("Company_model");
		$this->load->model("Finance_model");
		$this->load->model("Cpf_options_model");
		$this->load->model("Cpf_percentage_model");
		$this->load->model("Cpf_payslip_model");
		$this->load->model("Contribution_fund_model");
		$this->load->model("PaymentDeduction_Model");

		$this->load->helper('string');
	}

	/*Function to set JSON output*/
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	// payroll templates
	public function templates()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('left_payroll_templates') . ' | ' . $this->Xin_model->site_title();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = $this->lang->line('left_payroll_templates');
		$data['path_url'] = 'payroll_templates';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (in_array('34', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/payroll/templates", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// generate payslips
	public function generate_payslip()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('left_generate_payslip') . ' | ' . $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = $this->lang->line('left_generate_payslip');
		$data['path_url'] = 'generate_payslip';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		// echo '<pre>'; print_r($data); exit;
		if (in_array('36', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/payroll/generate_payslip", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// payment history
	public function payment_history()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_payslip_history');
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = $this->lang->line('xin_payslip_history');
		$data['path_url'] = 'payment_history';
		$data['get_all_companies'] = $this->Xin_model->get_companies();
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (in_array('37', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/payroll/payment_history", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// payslip > employees
	public function payslip_list()
	{

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/generate_payslip", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		// date and employee id/company id
		$p_date = $this->input->get("month_year");
		// echo $p_date;
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);

		if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) {
			if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") == 0) {
				$payslip = $this->Employees_model->get_employees_payslip($p_date);
			} else if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_comp_template($this->input->get("company_id"), 0, $p_date);
			} else if ($this->input->get("employee_id") != 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_employee_comp_template($this->input->get("company_id"), $this->input->get("employee_id"), $p_date);
			} else {
				$payslip = $this->Employees_model->get_employees_payslip($p_date);
			}
		} else {
			$payslip = $this->Payroll_model->get_employee_comp_template($user_info[0]->company_id, $session['user_id']);
		}
		$system = $this->Xin_model->read_setting_info(1);
		$data = array();

		foreach ($payslip->result() as $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('Y-m', strtotime($e_date));
				$present_date =  date('Y-m', strtotime("01-" . $p_date));
				if ($exit_date >= $present_date) {
					// user full name
					$emp_name = $r->first_name . ' ' . $r->last_name;
					$full_name = '<a target="_blank" class="text-primary" href="' . site_url() . 'admin/employees/detail/' . $r->user_id . '">' . $emp_name . '</a>';

					// get total hours > worked > employee
					$pay_date = $this->input->get('month_year');

					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
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
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);

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

					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}

					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;

					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						$p_class = 'emo_hourly_pay';
						$view_p_class = 'hourlywages_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}

					$g_ordinary_wage += $basic_salary;
					$g_shg += $basic_salary;
					$g_sdl += $basic_salary;

					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
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
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}

					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
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
					// echo 'Leave Days : <pre>'; print_r($leave_period);
					// echo 'Days Worked : '. $no_of_days_worked . '<br>';
					// echo 'Basic Pay : '. $basic_salary . '<br>';
					// echo 'Gross Pay : '. $gross_pay . '<br>';
					$g_ordinary_wage -= $unpaid_leave_amount;
					// echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
					// echo '<hr>';


					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
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

					// commissions
					$commissions_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
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
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
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

					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if ($count_other_payments > 0) {
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
					} else {
						$other_payments_amount = 0;
					}

					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') :
								$sta_salary = $basic_salary;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
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
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							endif;
						}
					} else {
						$statutory_deductions_amount = 0;
					}

					// 5: overtime
					// $salary_overtime = $this->Employees_model->read_salary_overtime($r->user_id);
					// $count_overtime = $this->Employees_model->count_employee_overtime($r->user_id);
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
					// 		$overtime_total = $eovertime_hours * $eovertime_rate;
					// 		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
					// 		$overtime_amount += $overtime_total;
					// 	}
					// } else {
					// 	$overtime_amount = 0;
					// }

					$overtime_amount = 0;
					$rate = 0;
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						$overtime_date = array();
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
							$overtime_date[] = $ot->overtime_date;
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}

						// my code start for multiple ot

						foreach ($overtime_date as $ov) {
							$get_day = strtotime($ov);
							$day = date('l', $get_day);

							$h_date_chck = $this->Timesheet_model->holiday_date_check($ov);
							$holiday_arr = array();
							if ($h_date_chck->num_rows() == 1) {
								$h_date = $this->Timesheet_model->holiday_date($ov);
								$begin = new DateTime($h_date[0]->start_date);
								$end = new DateTime($h_date[0]->end_date);
								$end = $end->modify('+1 day');

								$interval = new DateInterval('P1D');
								$daterange = new DatePeriod($begin, $interval, $end);

								foreach ($daterange as $date) {
									$holiday_arr[] =  $date->format("d-m-Y");
								}
							} else {
								$holiday_arr[] = '99-99-99';
							}
							$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($r->user_id, 2);
							// echo (in_array($ov,$holiday_arr));
							if (in_array($ov, $holiday_arr)) { // holiday
								$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($r->user_id, 3);
								$rate += $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
								$rate += $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
								$rate += $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
								$rate = $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
								$rate += $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
								$rate += $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
								$rate += $overtime_rate->overtime_pay_rate;
							} else if ($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
								$rate += $overtime_rate->overtime_pay_rate;
							} else {
								$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($r->user_id, 1);
								$rate += $overtime_rate->overtime_pay_rate;
							}
						}
						// echo $rate;
						// exit;
						$overtime_amount = $rate;
						// my code end for multiple ot


						//overtime rate
						// $overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						// if ($overtime_rate) {
						// 	$rate = $overtime_rate->overtime_pay_rate;
						// } else {
						// 	$week_hours = 44;
						// 	// if($r->office_shift_id) {
						// 	// 	$shift = $this->Employees_model->read_shift_information($r->office_shift_id);
						// 	// 	if($shift) {
						// 	// 		if($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						// 	// 			$time1 = $shift[0]->monday_in_time;
						// 	// 			$time2 = $shift[0]->monday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 		if($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						// 	// 			$time1 = $shift[0]->tuesday_in_time;
						// 	// 			$time2 = $shift[0]->tuesday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 		if($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						// 	// 			$time1 = $shift[0]->wednesday_in_time;
						// 	// 			$time2 = $shift[0]->wednesday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 		if($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						// 	// 			$time1 = $shift[0]->thursday_in_time;
						// 	// 			$time2 = $shift[0]->thursday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 		if($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						// 	// 			$time1 = $shift[0]->friday_in_time;
						// 	// 			$time2 = $shift[0]->friday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 		if($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						// 	// 			$time1 = $shift[0]->saturday_in_time;
						// 	// 			$time2 = $shift[0]->saturday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 		if($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						// 	// 			$time1 = $shift[0]->sunday_in_time;
						// 	// 			$time2 = $shift[0]->sunday_out_time;
						// 	// 			$time1 = explode(':',$time1);
						// 	// 			$time2 = explode(':',$time2);
						// 	// 			$hours1 = $time1[0];
						// 	// 			$hours2 = $time2[0];
						// 	// 			$mins1 = $time1[1];
						// 	// 			$mins2 = $time2[1];
						// 	// 			$hours = $hours2 - $hours1;
						// 	// 			$mins = 0;
						// 	// 			if($hours < 0)
						// 	// 			{
						// 	// 				$hours = 24 + $hours;
						// 	// 			}
						// 	// 			if($mins2 >= $mins1) {
						// 	// 				$mins = $mins2 - $mins1;
						// 	// 			}
						// 	// 			else {
						// 	// 				$mins = ($mins2 + 60) - $mins1;
						// 	// 				$hours--;
						// 	// 			}
						// 	// 			if($mins > 0) {
						// 	// 				$hours += round($mins / 60, 2);
						// 	// 			}
						// 	// 			$week_hours += $hours;
						// 	// 		}
						// 	// 	}	
						// 	// }else {
						// 	// 	$week_hours = 40;
						// 	// }
						// 	$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
						// 	$rate = $rate * 1.5;
						// }

						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}



					// make payment
					if ($system[0]->is_half_monthly == 1) {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $p_date);
						$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $p_date);
						if ($payment_check->num_rows() > 1) {
							//foreach($payment_last as $payment_half_last){
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $p_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';

							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}

							$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
							//}
							//detail link
							$detail = '';
						} else if ($payment_check->num_rows() > 0) {
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $p_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';

							$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
							$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
							$detail = '';
						} else {
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							//detail link
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						}
						//detail link
						//$detail = '';
					} else {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $p_date);


						if ($payment_check->num_rows() > 0) {
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $p_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
						} else {
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';

							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
						}
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}

					// add amount				
					$total_earning = $basic_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction = $loan_de_amount + $statutory_deductions_amount;
					$total_net_salary = $total_earning - $total_deduction - $unpaid_leave_amount;
					//if($r->salary_advance_paid == ''){
					//$data1 = $add_salary. ' - ' .$loan_de_amount. ' - ' .$net_salary . ' - ' .$salary_ssempee . ' - ' .$statutory_deductions;
					//$fnet_salary = $net_salary_default + $statutory_deductions;
					//	$net_salary = $fnet_salary - $loan_de_amount;

					/**
					 * Author : Syed Anees
					 * Sub Functionality : CPF on Gross Salary
					 */
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
					// $today = new DateTime($p_date . '-01');
					$today = new DateTime('01-' . $p_date);
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
					} elseif ($age_year > $age_upto && $age_year <= $age_above) {
						$age_from = $age_year;
						$age_to = $age_year;
					} elseif ($age_year > $age_above) {
						$age_from = $age_year;
						$age_to = null;
					} else {
						$age_from = null;
						$age_to = null;
					}

					$cpf_employee = 0;
					$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);

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
							// echo $pr_age_month;
							// if($pr_age_year == 0 && $pr_age_month > 0) {
							// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
							// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
							// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
							// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
							// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
							// }elseif($pr_age_year >= 2) {
							// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
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
								$total_cpf_contribution = $cpf_contribution->total_cpf;
								$employee_contribution = $cpf_contribution->contribution_employee;
								// $ordinary_wage = $basic_salary - $total_deduction;
								$ordinary_wage = $g_ordinary_wage;
								if ($ordinary_wage > $ordinary_wage_cap) {
									$ow = $ordinary_wage_cap;
								} else {
									$ow = $ordinary_wage;
								}

								//additional wage
								$additional_wage = $g_additional_wage;

								//total contribution
								$cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
								$cpf_total_aw = round(($total_cpf_contribution * $additional_wage) / 100);

								//employee contribution
								$cpf_employee_ow = floor(($employee_contribution * $ow) / 100);
								$cpf_employee_aw = floor(($employee_contribution * $additional_wage) / 100);

								$total_cpf = $cpf_total_ow + $cpf_total_aw;
								$cpf_employee = $cpf_employee_ow + $cpf_employee_aw;
								$total_net_salary = $total_net_salary - $cpf_employee;
								$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							}
						}
					}

					//SHG Contributions
					$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
					if ($employee_contributions) {
						$fund_deduction_amount = 0;
						$gross_s = $g_shg;

						$contribution_id = $employee_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $fund_deduction_amount;
					}

					$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					if ($employee_ashg_contributions) {
						$fund_deduction_amount = 0;
						$gross_s = $g_shg;

						$contribution_id = $employee_ashg_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $fund_deduction_amount;
					}

					$sdl_total_amount = 0;
					if ($g_sdl > 1 && $g_sdl <= 800) {
						$sdl_total_amount = 2;
					} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
						$sdl_amount = (0.25 * $g_sdl) / 100;
						$sdl_total_amount = $sdl_amount;
					} elseif ($g_sdl > 4500) {
						$sdl_total_amount = 11.25;
					}

					// $total_net_salary = $total_net_salary - $sdl_total_amount;

					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					//$basic_salary_cal = $basic_salary * $current_rate; 

					$basic_salary = number_format((float)$basic_salary, 2, '.', '');

					if ($basic_salary == 0 || $basic_salary == '') {
						$fmpay = '';
					} else {
						$fmpay = $mpay;
					}
					$company_info = $this->Company_model->read_company_information($r->company_id);

					if (!is_null($company_info)) {


						$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
						$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
						//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
					} else {
						//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
						$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary);

						$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary);

						//$net_salary = $this->Xin_model->currency_sign($net_salary);	
						//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
						$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount);
					}

					$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

					//action link
					//$act = $detail.$fmpay.$delete;
					$act = $fmpay . $delete;

					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}

					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $p_date);

					$p = $payslips->result();
					if (count($p) > 0) {
						$p_basic_salary = $this->Xin_model->currency_sign($p[0]->basic_salary);
						$p_cpf_employee = $this->Xin_model->currency_sign($p[0]->cpf_employee_amount);
						$p_net_salary = $this->Xin_model->currency_sign($p[0]->net_salary);
					} else {
						$p_basic_salary = $basic_salary;
						$p_cpf_employee = $cpf_employee;
						$p_net_salary = $this->Xin_model->currency_sign($total_net_salary);
					}

					$data[] = array(
						$act,
						$iemp_name,
						$emp_payroll_wage,
						$p_basic_salary,
						$p_cpf_employee,
						//$cpf_employee,
						$p_net_salary,
						$status
					);
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				$full_name = '<a target="_blank" class="text-primary" href="' . site_url() . 'admin/employees/detail/' . $r->user_id . '">' . $emp_name . '</a>';

				// get total hours > worked > employee
				$pay_date = $this->input->get('month_year');

				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
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
				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);

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

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					$p_class = 'emo_hourly_pay';
					$view_p_class = 'hourlywages_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}

				$g_ordinary_wage += $basic_salary;
				$g_shg += $basic_salary;
				$g_sdl += $basic_salary;

				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
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
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
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
				// echo 'Leave Days : <pre>'; print_r($leave_period);
				// echo 'Days Worked : '. $no_of_days_worked . '<br>';
				// echo 'Basic Pay : '. $basic_salary . '<br>';
				// echo 'Gross Pay : '. $gross_pay . '<br>';
				$g_ordinary_wage -= $unpaid_leave_amount;
				// echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
				// echo '<hr>';


				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
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

				// commissions
				$commissions_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
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
				$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
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

				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if ($count_other_payments > 0) {
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
				} else {
					$other_payments_amount = 0;
				}

				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				if ($count_statutory_deductions > 0) {
					foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
						if ($system[0]->statutory_fixed != 'yes') :
							$sta_salary = $basic_salary;
							$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
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
									$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
							} else {
								$single_sd = $sl_salary_statutory_deductions->deduction_amount;
							}
							$statutory_deductions_amount += $single_sd;
						endif;
					}
				} else {
					$statutory_deductions_amount = 0;
				}

				// 5: overtime
				// $salary_overtime = $this->Employees_model->read_salary_overtime($r->user_id);
				// $count_overtime = $this->Employees_model->count_employee_overtime($r->user_id);
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
				// 		$overtime_total = $eovertime_hours * $eovertime_rate;
				// 		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
				// 		$overtime_amount += $overtime_total;
				// 	}
				// } else {
				// 	$overtime_amount = 0;
				// }
				$overtime_amount = 0;
				$rate = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
				if ($overtime) {
					$ot_hrs = 0;
					$ot_mins = 0;
					$overtime_date = array();
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs = $total_hours[0];
						$ot_mins = $total_hours[1];
						$overtime_date[] = $ot->overtime_date;

						// // my code start for multiple ot
						if ($ot_mins > 0) {
							$ot_hrs = round($ot_mins / 60, 2);
						}
						// echo $ot_hrs."<br>";
						$get_day = strtotime($ot->overtime_date);
						$day = date('l', $get_day);

						$h_date_chck = $this->Timesheet_model->holiday_date_check($ot->overtime_date);
						$holiday_arr = array();
						if ($h_date_chck->num_rows() == 1) {
							$h_date = $this->Timesheet_model->holiday_date($ot->overtime_date);
							$begin = new DateTime($h_date[0]->start_date);
							$end = new DateTime($h_date[0]->end_date);
							$end = $end->modify('+1 day');

							$interval = new DateInterval('P1D');
							$daterange = new DatePeriod($begin, $interval, $end);

							foreach ($daterange as $date) {
								$holiday_arr[] =  $date->format("d-m-Y");
							}
						} else {
							$holiday_arr[] = '99-99-99';
						}
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($r->user_id, 2);
						// echo (in_array($ot->overtime_date,$holiday_arr));
						if (in_array($ot->overtime_date, $holiday_arr)) { // holiday
							$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($r->user_id, 3);
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else if ($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$overtime_rate = $this->Employees_model->getEmployeeOvertimeRateChange($r->user_id, 1);
							$rate = $overtime_rate->overtime_pay_rate;
						}
						if ($ot_hrs > 0) {
							$overtime_amount += round($ot_hrs * $rate, 2);
						}
						// end code
					}


					if ($system[0]->is_half_monthly == 1) {
						if ($system[0]->half_deduct_month == 2) {
							$overtime_amount = $overtime_amount / 2;
						}
					}
					$g_ordinary_wage += $overtime_amount;
					$g_sdl += $overtime_amount;
				}



				// make payment
				if ($system[0]->is_half_monthly == 1) {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $p_date);
					$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $p_date);
					if ($payment_check->num_rows() > 1) {
						//foreach($payment_last as $payment_half_last){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $p_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}

						$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
						//}
						//detail link
						$detail = '';
					} else if ($payment_check->num_rows() > 0) {
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $p_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';

						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$detail = '';
					} else {
						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
					//detail link
					//$detail = '';
				} else {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $p_date);


					if ($payment_check->num_rows() > 0) {
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $p_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
					} else {
						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
					}
					//detail link
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
				}

				// add amount				
				$total_earning = $basic_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction = $loan_de_amount + $statutory_deductions_amount;
				$total_net_salary = $total_earning - $total_deduction - $unpaid_leave_amount;
				//if($r->salary_advance_paid == ''){
				//$data1 = $add_salary. ' - ' .$loan_de_amount. ' - ' .$net_salary . ' - ' .$salary_ssempee . ' - ' .$statutory_deductions;
				//$fnet_salary = $net_salary_default + $statutory_deductions;
				//	$net_salary = $fnet_salary - $loan_de_amount;

				/**
				 * Author : Syed Anees
				 * Sub Functionality : CPF on Gross Salary
				 */
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);
				// $today = new DateTime($p_date . '-01');
				$today = new DateTime('01-' . $p_date);
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
				} elseif ($age_year > $age_upto && $age_year <= $age_above) {
					$age_from = $age_year;
					$age_to = $age_year;
				} elseif ($age_year > $age_above) {
					$age_from = $age_year;
					$age_to = null;
				} else {
					$age_from = null;
					$age_to = null;
				}

				$cpf_employee = 0;
				$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);

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
						// echo $pr_age_month;
						// if($pr_age_year == 0 && $pr_age_month > 0) {
						// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
						// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
						// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
						// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
						// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
						// }elseif($pr_age_year >= 2) {
						// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
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
							$total_cpf_contribution = $cpf_contribution->total_cpf;
							$employee_contribution = $cpf_contribution->contribution_employee;
							// $ordinary_wage = $basic_salary - $total_deduction;
							$ordinary_wage = $g_ordinary_wage;
							if ($ordinary_wage > $ordinary_wage_cap) {
								$ow = $ordinary_wage_cap;
							} else {
								$ow = $ordinary_wage;
							}

							//additional wage
							$additional_wage = $g_additional_wage;

							//total contribution
							$cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
							$cpf_total_aw = round(($total_cpf_contribution * $additional_wage) / 100);

							//employee contribution
							$cpf_employee_ow = floor(($employee_contribution * $ow) / 100);
							$cpf_employee_aw = floor(($employee_contribution * $additional_wage) / 100);

							$total_cpf = $cpf_total_ow + $cpf_total_aw;
							$cpf_employee = $cpf_employee_ow + $cpf_employee_aw;
							$total_net_salary = $total_net_salary - $cpf_employee;
							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
						}
					}
				}

				//SHG Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
				if ($employee_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;

					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $fund_deduction_amount;
				}

				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				if ($employee_ashg_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;

					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $fund_deduction_amount;
				}

				$sdl_total_amount = 0;
				if ($g_sdl > 1 && $g_sdl <= 800) {
					$sdl_total_amount = 2;
				} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					$sdl_amount = (0.25 * $g_sdl) / 100;
					$sdl_total_amount = $sdl_amount;
				} elseif ($g_sdl > 4500) {
					$sdl_total_amount = 11.25;
				}

				// $total_net_salary = $total_net_salary - $sdl_total_amount;

				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				//$basic_salary_cal = $basic_salary * $current_rate; 

				$basic_salary = number_format((float)$basic_salary, 2, '.', '');

				if ($basic_salary == 0 || $basic_salary == '') {
					$fmpay = '';
				} else {
					$fmpay = $mpay;
				}
				$company_info = $this->Company_model->read_company_information($r->company_id);

				if (!is_null($company_info)) {


					$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
					$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
					//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
				} else {
					//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
					$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary);

					$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary);

					//$net_salary = $this->Xin_model->currency_sign($net_salary);	
					//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
					$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount);
				}

				$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				//$act = $detail.$fmpay.$delete;
				$act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $p_date);

				$p = $payslips->result();
				if (count($p) > 0) {
					$p_basic_salary = $this->Xin_model->currency_sign($p[0]->basic_salary);
					$p_cpf_employee = $this->Xin_model->currency_sign($p[0]->cpf_employee_amount);
					$p_net_salary = $this->Xin_model->currency_sign($p[0]->net_salary);
				} else {
					$p_basic_salary = $basic_salary;
					$p_cpf_employee = $cpf_employee;
					$p_net_salary = $this->Xin_model->currency_sign($total_net_salary);
				}

				$data[] = array(
					$act,
					$iemp_name,
					$emp_payroll_wage,
					$p_basic_salary,
					$p_cpf_employee,
					//$cpf_employee,
					$p_net_salary,
					$status
				);
			}
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $payslip->num_rows(),
			"recordsFiltered" => $payslip->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	// get payroll template info by id

	public function payroll_template_read()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		// user full name
		$full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';
		}
		$data = array(
			'first_name' => $user[0]->first_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'user_id' => $user[0]->user_id,
			'department_name' => $department_name,
			'designation_name' => $designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender,
			'wages_type' => $user[0]->wages_type,
			'basic_salary' => $user[0]->basic_salary,
			'daily_wages' => $user[0]->daily_wages,
		);
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_templates', $data);
		} else {
			redirect('admin/');
		}
	}
	// pay hourly read > payslip
	public function hourlywage_template_read()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		// user full name
		$full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';
		}
		$data = array(
			'first_name' => $user[0]->first_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'user_id' => $user[0]->user_id,
			'euser_id' => $user[0]->user_id,
			'department_name' => $department_name,
			'designation_name' => $designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender,
			'wages_type' => $user[0]->wages_type,
			'basic_salary' => $user[0]->basic_salary,
			'daily_wages' => $user[0]->daily_wages
		);
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_templates', $data);
		} else {
			redirect('admin/');
		}
	}

	// pay monthly > create payslip
	public function pay_salary()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		$result = $this->Payroll_model->read_template_information($user[0]->monthly_grade_id);
		//$department = $this->Department_model->read_department_information($user[0]->department_id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_id = $designation[0]->designation_id;
		} else {
			$designation_id = 1;
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_id = $department[0]->department_id;
		} else {
			$department_id = 1;
		}
		$claim_info = $this->Employees_model->read_employee_claim_information($user[0]->user_id);

		$claim_amount = 0;
		if ($claim_info) {
			foreach ($claim_info as $claim) {
				$claim_amount += $claim->amount;
			}
		}


		//$location = $this->Location_model->read_location_information($department[0]->location_id);
		$data = array(
			'department_id' => $department_id,
			'designation_id' => $designation_id,
			'company_id' => $user[0]->company_id,
			'location_id' => $user[0]->location_id,
			'user_id' => $user[0]->user_id,
			'dob' => $user[0]->date_of_birth,
			'wages_type' => $user[0]->wages_type,
			'basic_salary' => $user[0]->basic_salary,
			'daily_wages' => $user[0]->daily_wages,
			'office_shift_id' => $user[0]->office_shift_id,
			'claim_amount' => $claim_amount
		);
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_make_payment', $data);
		} else {
			redirect('admin/');
		}
	}
	// pay hourly > create payslip
	public function pay_hourly()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		$result = $this->Payroll_model->read_template_information($user[0]->monthly_grade_id);
		//$department = $this->Department_model->read_department_information($user[0]->department_id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_id = $designation[0]->designation_id;
		} else {
			$designation_id = 1;
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_id = $department[0]->department_id;
		} else {
			$department_id = 1;
		}
		//$location = $this->Location_model->read_location_information($department[0]->location_id);
		$data = array(
			'department_id' => $department_id,
			'designation_id' => $designation_id,
			'company_id' => $user[0]->company_id,
			'location_id' => $user[0]->location_id,
			'user_id' => $user[0]->user_id,
			'euser_id' => $user[0]->user_id,
			'wages_type' => $user[0]->wages_type,
			'basic_salary' => $user[0]->basic_salary,
			'daily_wages' => $user[0]->daily_wages,
			'office_shift_id' => $user[0]->office_shift_id,
			'date_of_birth' => $user[0]->date_of_birth,
		);
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_make_payment', $data);
		} else {
			redirect('admin/');
		}
	}

	// Validate and add info in database > add monthly payment
	public function add_pay_monthly()
	{

		if ($this->input->post('add_type') == 'add_monthly_payment') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */

			/*if($Return['error']!=''){
				$this->output($Return
				);
    		}*/
			$basic_salary = $this->input->post('gross_salary');
			$system = $this->Xin_model->read_setting_info(1);
			$euser_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
			// office shift
			$office_shift = $this->Timesheet_model->read_office_shift_information($this->input->post('office_shift_id'));

			if ($system[0]->is_half_monthly == 1) {
				$is_half_monthly_payroll = 1;
			} else {
				$is_half_monthly_payroll = 0;
			}

			$jurl = random_string('alnum', 40);
			$data = array(
				'employee_id' => $this->input->post('emp_id'),
				'department_id' => $this->input->post('department_id'),
				'company_id' => $this->input->post('company_id'),
				'location_id' => $this->input->post('location_id'),
				'designation_id' => $this->input->post('designation_id'),
				'salary_month' => $this->input->post('pay_date'),
				'basic_salary' => $this->input->post('gross_salary'),
				'gross_salary' => $this->input->post('gross_salary'),
				'net_salary' => $this->input->post('net_salary'),
				'wages_type' => $this->input->post('wages_type'),
				'is_half_monthly_payroll' => $is_half_monthly_payroll,
				'total_commissions' => $this->input->post('total_commissions'),
				'total_statutory_deductions' => $this->input->post('total_statutory_deductions'),
				'total_other_payments' => $this->input->post('total_other_payments'),
				'total_allowances' => $this->input->post('total_allowances'),
				'total_loan' => $this->input->post('total_loan'),
				'total_overtime' => $this->input->post('total_overtime'),
				'claim_amount' => $this->input->post('employee_claim'),
				'cpf_employee_amount' => $this->input->post('total_cpf_employee'),
				'cpf_employer_amount' => $this->input->post('total_cpf_employer'),
				'leave_deduction' => $this->input->post('leave_deductions'),
				'contribution_fund' => $this->input->post('total_fund_contribution'),
				'share_option_amount' => $this->input->post('total_share'),
				'additonal_allowance' => $this->input->post('additional_allowances'),
				'deduction_amount' => $this->input->post('total_employee_deduction'),
				'is_payment' => '1',
				'status' => '0',
				'payslip_type' => 'full_monthly',
				'payslip_key' => $jurl,
				'year_to_date' => date('d-m-Y'),
				'created_at' => date('d-m-Y h:i:s')
			);
			$result = $this->Payroll_model->add_salary_payslip($data);

			$system_settings = system_settings_info(1);
			if ($system_settings->online_payment_account == '') {
				$online_payment_account = 0;
			} else {
				$online_payment_account = $system_settings->online_payment_account;
			}

			if ($result) {
				if (isset($_POST['allowance_amount']) && count($_POST['allowance_amount']) > 0) {
					for ($i = 0; $i < count($_POST['allowance_amount']); $i++) {
						$allowance_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'allowance_amount' => $this->input->post('allowance_amount')[$i],
							'allowance_name' => $this->input->post('allowance_type')[$i],
							'deduction_name' => '',
							'deduction_amount' => ''

						);
						$this->Payroll_model->add_mapping($allowance_data);
					}
				}
				if (isset($_POST['loan_amount']) && count($_POST['loan_amount']) > 0) {
					for ($j = 0; $j < count($_POST['loan_amount']); $j++) {
						$allowance_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'allowance_amount' => '',
							'allowance_name' => '',
							'deduction_name' => $this->input->post('loan_title')[$j],
							'deduction_amount' => $this->input->post('loan_amount')[$j],

						);
						$this->Payroll_model->add_mapping($allowance_data);
					}
				}
				if (isset($_POST['statutory_amount']) && count($_POST['statutory_amount']) > 0) {
					for ($k = 0; $k < count($_POST['statutory_amount']); $k++) {
						$allowance_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'allowance_amount' => '',
							'allowance_name' => '',
							'deduction_name' => $this->input->post('statutory_title')[$k],
							'deduction_amount' => $this->input->post('statutory_amount')[$k],

						);
						$this->Payroll_model->add_mapping($allowance_data);
					}
				}
				if (isset($_POST['deduction_amount']) && count($_POST['deduction_amount']) > 0) {
					for ($l = 0; $l < count($_POST['deduction_amount']); $l++) {
						$allowance_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'allowance_amount' => '',
							'allowance_name' => '',
							'deduction_name' => $this->input->post('deduction_type')[$l],
							'deduction_amount' => $this->input->post('deduction_amount')[$l],

						);
						$this->Payroll_model->add_mapping($allowance_data);
					}
				}
				// $ivdata = array(
				// 	'amount' => $this->input->post('net_salary'),
				// 	'account_id' => $online_payment_account,
				// 	'transaction_type' => 'expense',
				// 	'dr_cr' => 'cr',
				// 	'transaction_date' => date('Y-m-d'),
				// 	'payer_payee_id' => $this->input->post('emp_id'),
				// 	'payment_method_id' => 3,
				// 	'description' => 'Payroll Payments',
				// 	'reference' => 'Payroll Payments',
				// 	'invoice_id' => $result,
				// 	'client_id' => $this->input->post('emp_id'),
				// 	'created_at' => date('Y-m-d H:i:s')
				// );
				// $this->Finance_model->add_transactions($ivdata);

				// update data in bank account
				// $account_id = $this->Finance_model->read_bankcash_information($online_payment_account);
				// $acc_balance = $account_id[0]->account_balance - $this->input->post('net_salary');

				// $data3 = array(
				// 'account_balance' => $acc_balance
				// );
				// $this->Finance_model->update_bankcash_record($data3,$online_payment_account);

				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				$g_ordinary_wage += $basic_salary;
				$g_shg += $basic_salary;
				$g_sdl += $basic_salary;

				// set allowance
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($this->input->post('emp_id'), $this->input->post('pay_date'));

				if ($salary_allowances) {
					foreach ($salary_allowances as $sl_allowances) {
						$esl_allowances = $sl_allowances->allowance_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eallowance_amount = $esl_allowances / 2;
							} else {
								$eallowance_amount = $esl_allowances;
							}
						} else {
							$eallowance_amount = $esl_allowances;
						}
						$allowance_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'allowance_title' => $sl_allowances->allowance_title,
							'allowance_amount' => $eallowance_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$_allowance_data = $this->Payroll_model->add_salary_payslip_allowances($allowance_data);


						if (!empty($sl_allowances->salary_month)) {
							$g_additional_wage += $eallowance_amount;
						} else {
							$g_ordinary_wage += $eallowance_amount;
							if ($sl_allowances->id == 2) {
								$gross_allowance_amount = $eallowance_amount;
							}
						}

						if ($sl_allowances->sdl == 1) {
							$g_sdl += $eallowance_amount;
						}
						if ($sl_allowances->shg == 1) {
							$g_shg += $eallowance_amount;
						}

						$allowance_amount += $eallowance_amount;
					}
				}

				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				// $month_start_date = new DateTime($this->input->post('pay_date') . '-01');
				$month_start_date = new DateTime('01-' . $this->input->post('pay_date'));
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$p_day = $p->format('l');
					$p_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($this->input->post('company_id'), $p_date);
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
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						// $pay_date_month = new DateTime($this->input->post('pay_date') . '-01');
						$pay_date_month = new DateTime('01-' . $this->input->post('pay_date'));
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
				if ($unpaid_leave_amount > 0 && $leaves_taken_count > 0) {
					foreach ($leave_period as $l) {
						$is_half = $l['is_half'];
						$leave_dates = $l['leave_date'];
						$leave_day_pay = round(($basic_salary + $gross_allowance_amount) / $no_of_working_days, 2);
						if ($is_half) {
							$leave_day_pay = $leave_day_pay / 2;
						}
						foreach ($leave_dates as $ld) {
							$unpaid_leave_data = array(
								'payslip_id' => $result,
								'employee_id' => $this->input->post('emp_id'),
								'salary_month' => $this->input->post('pay_date'),
								'leave_date' => $ld,
								'leave_amount' => $leave_day_pay,
								'is_half' => $is_half,
								'total_leave_amount' => $unpaid_leave_amount
							);
							$this->Payroll_model->add_salary_payslip_leave_deduction($unpaid_leave_data);
						}
					}
				}

				// commissions
				$commission_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($commissions) {
					foreach ($commissions as $c) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ecommission_amount = $c->commission_amount / 2;
							} else {
								$ecommission_amount = $c->commission_amount;
							}
						} else {
							$ecommission_amount = $c->commission_amount;
						}

						if ($c->commission_type == 9) {
							$g_ordinary_wage += $ecommission_amount;
						} elseif ($c->commission_type == 10) {
							$g_additional_wage += $ecommission_amount;
						}

						if ($c->sdl == 1) {
							$g_sdl += $ecommission_amount;
						}
						if ($c->shg == 1) {
							$g_shg += $ecommission_amount;
						}

						$commissions_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'commission_id' => $c->commission_type,
							'commission_amount' => $ecommission_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$this->Payroll_model->add_salary_payslip_commissions($commissions_data);

						$commission_amount += $ecommission_amount;
					}
				}

				//share options
				$share_options_amount = 0;
				$share_options = $this->Employees_model->getEmployeeShareOptions($this->input->post('emp_id'), $this->input->post('pay_date'));
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

					$share_options_data = array(
						'payslip_id' => $result,
						'employee_id' => $this->input->post('emp_id'),
						'salary_month' => $this->input->post('pay_date'),
						'amount' => round($share_options_amount, 2)
					);
					$this->Payroll_model->add_salary_payslip_share_options($share_options_data);
				}

				// set other payments
				$salary_other_payments = $this->Employees_model->read_salary_other_payments($this->input->post('emp_id'));
				$count_other_payment = $this->Employees_model->count_employee_other_payments($this->input->post('emp_id'));
				$other_payment_amount = 0;
				if ($count_other_payment > 0) {
					foreach ($salary_other_payments as $sl_other_payments) {
						$esl_other_payments = $sl_other_payments->payments_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$epayments_amount = $esl_other_payments / 2;
							} else {
								$epayments_amount = $esl_other_payments;
							}
						} else {
							$epayments_amount = $esl_other_payments;
						}
						$other_payments_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'payments_title' => $sl_other_payments->payments_title,
							'payments_amount' => $epayments_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
					}
				}

				// set statutory_deductions
				$salary_statutory_deductions = $this->Employees_model->read_salary_statutory_deductions($this->input->post('emp_id'));
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($this->input->post('emp_id'));
				$statutory_deductions_amount = 0;
				if ($count_statutory_deductions > 0) {
					foreach ($salary_statutory_deductions as $sl_statutory_deduction) {
						$esl_statutory_deduction = $sl_statutory_deduction->deduction_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ededuction_amount = $esl_statutory_deduction / 2;
							} else {
								$ededuction_amount = $esl_statutory_deduction;
							}
						} else {
							$ededuction_amount = $esl_statutory_deduction;
						}
						$statutory_deduction_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'deduction_title' => $sl_statutory_deduction->deduction_title,
							'deduction_amount' => $ededuction_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$this->Payroll_model->add_salary_payslip_statutory_deductions($statutory_deduction_data);
					}
				}

				// set loan
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($this->input->post('emp_id'));
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($this->input->post('emp_id'));
				$loan_de_amount = 0;
				if ($count_loan_deduction > 0) {
					foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
						$esl_salary_loan_deduction = $sl_salary_loan_deduction->loan_deduction_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eloan_deduction_amount = $esl_salary_loan_deduction / 2;
							} else {
								$eloan_deduction_amount = $esl_salary_loan_deduction;
							}
						} else {
							$eloan_deduction_amount = $esl_salary_loan_deduction;
						}
						$loan_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'loan_title' => $sl_salary_loan_deduction->loan_deduction_title,
							'loan_amount' => $eloan_deduction_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$_loan_data = $this->Payroll_model->add_salary_payslip_loan($loan_data);
					}
				}

				// set overtime
				// $salary_overtime = $this->Employees_model->read_salary_overtime($this->input->post('emp_id'));
				// $count_overtime = $this->Employees_model->count_employee_overtime($this->input->post('emp_id'));
				// $overtime_amount = 0;
				// if($count_overtime > 0) {
				// 	foreach($salary_overtime as $sl_overtime){
				// 		$eovertime_hours = $sl_overtime->overtime_hours;
				// 		$eovertime_rate = $sl_overtime->overtime_rate;
				// 		if($system[0]->is_half_monthly==1){
				// 			if($system[0]->half_deduct_month==2){
				// 				$esl_overtime_hr = $eovertime_hours/2;
				// 				$esl_overtime_rate = $eovertime_rate/2;
				// 			} else {
				// 				$esl_overtime_hr = $eovertime_hours;
				// 				$esl_overtime_rate = $eovertime_rate;
				// 			}
				// 		} else {
				// 			$esl_overtime_hr = $eovertime_hours;
				// 			$esl_overtime_rate = $eovertime_rate;
				// 		}
				// 		$overtime_data = array(
				// 		'payslip_id' => $result,
				// 		'employee_id' => $this->input->post('emp_id'),
				// 		'overtime_salary_month' => $this->input->post('pay_date'),
				// 		'overtime_title' => $sl_overtime->overtime_type,
				// 		'overtime_no_of_days' => $sl_overtime->no_of_days,
				// 		'overtime_hours' => $esl_overtime_hr,
				// 		'overtime_rate' => $esl_overtime_rate,
				// 		'created_at' => date('d-m-Y h:i:s')
				// 		);
				// 		$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
				// 	}
				// }

				$overtime_amount = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($overtime) {
					$ot_days = 0;
					$ot_hrs = 0;
					$ot_mins = 0;
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs += $total_hours[0];
						$ot_mins += $total_hours[1];
						$ot_days += 1;
					}
					if ($ot_mins > 0) {
						$ot_hrs += round($ot_mins / 60, 2);
					}

					//overtime rate
					$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($this->input->post('emp_id'));

					if ($overtime_rate) {
						$rate = $overtime_rate->overtime_pay_rate;
					} else {
						$week_hours = 44;
						// if($this->input->post('office_shift_id')) {
						// 	$shift = $this->Employees_model->read_shift_information($this->input->post('office_shift_id'));
						// 	if($shift) {
						// 		if($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						// 			$time1 = $shift[0]->monday_in_time;
						// 			$time2 = $shift[0]->monday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						// 			$time1 = $shift[0]->tuesday_in_time;
						// 			$time2 = $shift[0]->tuesday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						// 			$time1 = $shift[0]->wednesday_in_time;
						// 			$time2 = $shift[0]->wednesday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						// 			$time1 = $shift[0]->thursday_in_time;
						// 			$time2 = $shift[0]->thursday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						// 			$time1 = $shift[0]->friday_in_time;
						// 			$time2 = $shift[0]->friday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						// 			$time1 = $shift[0]->saturday_in_time;
						// 			$time2 = $shift[0]->saturday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						// 			$time1 = $shift[0]->sunday_in_time;
						// 			$time2 = $shift[0]->sunday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 	}	
						// }else {
						// 	$week_hours = 40;
						// }
						$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
						$rate = $rate * 1.5;
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
					$g_sdl += $overtime_amount;

					$overtime_data = array(
						'payslip_id' => $result,
						'employee_id' => $this->input->post('emp_id'),
						'overtime_salary_month' => $this->input->post('pay_date'),
						'overtime_no_of_days' => $ot_days,
						'overtime_hours' => $ot_hrs,
						'overtime_rate' => $rate,
						'total_overtime' => $overtime_amount,
						'created_at' => date('d-m-Y h:i:s')
					);
					$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
				}

				//cpf
				$total_cpf =  $this->input->post('total_cpf');

				if ($total_cpf && $total_cpf > 0) {
					$ow_paid = $this->input->post('ow_paid');

					$cpf_data = [
						'payslip_id' => $result,
						// 'month_year' => $this->input->post('pay_date'). '-01',
						'month_year' => '01-' . $this->input->post('pay_date'),
						'ow_paid'	=> $ow_paid,
						'ow_cpf'	=> $this->input->post('ow_cpf'),
						'ow_cpf_employer'	=> $this->input->post('ow_cpf_employer'),
						'ow_cpf_employee'	=> $this->input->post('ow_cpf_employee'),
						'aw_paid'	=> $this->input->post('aw_paid'),
						'aw_cpf'	=> $this->input->post('aw_cpf'),
						'aw_cpf_employer'	=> $this->input->post('aw_cpf_employer'),
						'aw_cpf_employee'	=> $this->input->post('aw_cpf_employee')
					];

					$cpf_payslip = $this->Cpf_payslip_model->add_cpf_payslip($cpf_data);
				}

				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($this->input->post('emp_id'));
				if ($employee_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

					$fund_deduction_amount += $contribution_amount;
					$cdata = array(
						'payslip_id' => $result,
						'contribution_id' => $contribution_id,
						'contribution_amount' => $contribution_amount
					);
					$this->Contribution_fund_model->setContributionPayslip($cdata);
				}


				//ASHG Fund Contributions
				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($this->input->post('emp_id'));
				if ($employee_ashg_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

					$fund_deduction_amount += $contribution_amount;
					$cdata = array(
						'payslip_id' => $result,
						'contribution_id' => $contribution_id,
						'contribution_amount' => $contribution_amount
					);
					$this->Contribution_fund_model->setContributionPayslip($cdata);
				}

				//sdl
				$sdl = 0;
				if ($g_sdl > 1 && $g_sdl <= 800) {
					$sdl = 2;
				} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					$sdl_amount = (0.25 * $g_sdl) / 100;
					$sdl = $sdl_amount;
				} elseif ($g_sdl > 4500) {
					$sdl = 11.25;
				}

				$cdata = array(
					'payslip_id' => $result,
					'contribution_id' => 5,
					'contribution_amount' => $sdl
				);
				$this->Contribution_fund_model->setContributionPayslip($cdata);

				$Return['result'] = $this->lang->line('xin_success_payment_paid');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and add info in database > add monthly payment
	public function add_pay_hourly()
	{

		if ($this->input->post('add_type') == 'add_pay_hourly') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */

			/*if($Return['error']!=''){
       		$this->output($Return);
    	}*/
			$system = $this->Xin_model->read_setting_info(1);
			// office shift
			$office_shift = $this->Timesheet_model->read_office_shift_information($this->input->post('office_shift_id'));
			$basic_salary = $this->input->post('basic_salary');

			if ($system[0]->is_half_monthly == 1) {
				$is_half_monthly_payroll = 1;
			} else {
				$is_half_monthly_payroll = 0;
			}


			$jurl = random_string('alnum', 40);
			$data = array(
				'employee_id' => $this->input->post('emp_id'),
				'department_id' => $this->input->post('department_id'),
				'company_id' => $this->input->post('company_id'),
				'location_id' => $this->input->post('location_id'),
				'designation_id' => $this->input->post('designation_id'),
				'salary_month' => $this->input->post('pay_date'),
				'basic_salary' => $basic_salary,
				'gross_salary' => $this->input->post('gross_salary'),
				'net_salary' => $this->input->post('net_salary'),
				'wages_type' => $this->input->post('wages_type'),
				'is_half_monthly_payroll' => $is_half_monthly_payroll,
				'total_commissions' => $this->input->post('total_commissions'),
				'total_statutory_deductions' => $this->input->post('total_statutory_deductions'),
				'total_other_payments' => $this->input->post('total_other_payments'),
				'total_allowances' => $this->input->post('total_allowances'),
				'total_loan' => $this->input->post('total_loan'),
				'total_overtime' => $this->input->post('total_overtime'),
				'hours_worked' => $this->input->post('hours_worked'),
				'is_payment' => '1',
				'status' => '0',
				'payslip_type' => 'hourly',
				'payslip_key' => $jurl,
				'year_to_date' => date('d-m-Y'),
				'created_at' => date('d-m-Y h:i:s')
			);
			$result = $this->Payroll_model->add_salary_payslip($data);
			$system_settings = system_settings_info(1);
			if ($system_settings->online_payment_account == '') {
				$online_payment_account = 0;
			} else {
				$online_payment_account = $system_settings->online_payment_account;
			}
			if ($result) {
				// $ivdata = array(
				// 'amount' => $this->input->post('net_salary'),
				// 'account_id' => $online_payment_account,
				// 'transaction_type' => 'expense',
				// 'dr_cr' => 'cr',
				// 'transaction_date' => date('Y-m-d'),
				// 'payer_payee_id' => $this->input->post('emp_id'),
				// 'payment_method_id' => 3,
				// 'description' => 'Payroll Payments',
				// 'reference' => 'Payroll Payments',
				// 'invoice_id' => $result,
				// 'client_id' => $this->input->post('emp_id'),
				// 'created_at' => date('Y-m-d H:i:s')
				// );
				// $this->Finance_model->add_transactions($ivdata);
				// // update data in bank account
				// $account_id = $this->Finance_model->read_bankcash_information($online_payment_account);
				// $acc_balance = $account_id[0]->account_balance - $this->input->post('net_salary');

				// $data3 = array(
				// 'account_balance' => $acc_balance
				// );
				// $this->Finance_model->update_bankcash_record($data3,$online_payment_account);
				$result_total_work = $this->Payroll_model->total_hours_worked($this->input->post('emp_id'), $this->input->post('pay_date'));
				$hrs_old_int1 = 0;
				$pcount = 0;
				$Trest = 0;
				$total_time_rs = 0;
				$hrs_old_int_res1 = 0;
				foreach ($result_total_work->result() as $hour_work) {
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

				$basic_salary = $basic_salary * $pcount;



				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				$g_ordinary_wage += $basic_salary;
				$g_shg += $basic_salary;
				$g_sdl += $basic_salary;

				// set allowance
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($salary_allowances) {
					foreach ($salary_allowances as $sl_allowances) {
						$esl_allowances = $sl_allowances->allowance_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eallowance_amount = $esl_allowances / 2;
							} else {
								$eallowance_amount = $esl_allowances;
							}
						} else {
							$eallowance_amount = $esl_allowances;
						}
						$allowance_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'allowance_title' => $sl_allowances->allowance_title,
							'allowance_amount' => $eallowance_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$_allowance_data = $this->Payroll_model->add_salary_payslip_allowances($allowance_data);


						if (!empty($sl_allowances->salary_month)) {
							$g_additional_wage += $eallowance_amount;
						} else {
							$g_ordinary_wage += $eallowance_amount;
							if ($sl_allowances->id == 2) {
								$gross_allowance_amount = $eallowance_amount;
							}
						}

						if ($sl_allowances->sdl == 1) {
							$g_sdl += $eallowance_amount;
						}
						if ($sl_allowances->shg == 1) {
							$g_shg += $eallowance_amount;
						}

						$allowance_amount += $eallowance_amount;
					}
				}

				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				// $month_start_date = new DateTime($this->input->post('pay_date') . '-01');
				$month_start_date = new DateTime('01-' . $this->input->post('pay_date'));
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$p_day = $p->format('l');
					$p_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($this->input->post('company_id'), $p_date);
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
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						// $pay_date_month = new DateTime($this->input->post('pay_date') . '-01');
						$pay_date_month = new DateTime('01-' . $this->input->post('pay_date'));
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
				if ($unpaid_leave_amount > 0 && $leaves_taken_count > 0) {
					foreach ($leave_period as $l) {
						$is_half = $l['is_half'];
						$leave_dates = $l['leave_date'];
						$leave_day_pay = round(($basic_salary + $gross_allowance_amount) / $no_of_working_days, 2);
						if ($is_half) {
							$leave_day_pay = $leave_day_pay / 2;
						}
						foreach ($leave_dates as $ld) {
							$unpaid_leave_data = array(
								'payslip_id' => $result,
								'employee_id' => $this->input->post('emp_id'),
								'salary_month' => $this->input->post('pay_date'),
								'leave_date' => $ld,
								'leave_amount' => $leave_day_pay,
								'is_half' => $is_half,
								'total_leave_amount' => $unpaid_leave_amount
							);
							$this->Payroll_model->add_salary_payslip_leave_deduction($unpaid_leave_data);
						}
					}
				}

				// commissions
				$commission_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($commissions) {
					foreach ($commissions as $c) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ecommission_amount = $c->commission_amount / 2;
							} else {
								$ecommission_amount = $c->commission_amount;
							}
						} else {
							$ecommission_amount = $c->commission_amount;
						}

						if ($c->commission_type == 9) {
							$g_ordinary_wage += $ecommission_amount;
						} elseif ($c->commission_type == 10) {
							$g_additional_wage += $ecommission_amount;
						}

						if ($c->sdl == 1) {
							$g_sdl += $ecommission_amount;
						}
						if ($c->shg == 1) {
							$g_shg += $ecommission_amount;
						}

						$commissions_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'commission_id' => $c->commission_type,
							'commission_amount' => $ecommission_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$this->Payroll_model->add_salary_payslip_commissions($commissions_data);

						$commission_amount += $ecommission_amount;
					}
				}

				//share options
				$share_options_amount = 0;
				$share_options = $this->Employees_model->getEmployeeShareOptions($this->input->post('emp_id'), $this->input->post('pay_date'));
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

					$share_options_data = array(
						'payslip_id' => $result,
						'employee_id' => $this->input->post('emp_id'),
						'salary_month' => $this->input->post('pay_date'),
						'amount' => round($share_options_amount, 2)
					);
					$this->Payroll_model->add_salary_payslip_share_options($share_options_data);
				}

				// set other payments
				$salary_other_payments = $this->Employees_model->read_salary_other_payments($this->input->post('emp_id'));
				$count_other_payment = $this->Employees_model->count_employee_other_payments($this->input->post('emp_id'));
				$other_payment_amount = 0;
				if ($count_other_payment > 0) {
					foreach ($salary_other_payments as $sl_other_payments) {
						$esl_other_payments = $sl_other_payments->payments_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$epayments_amount = $esl_other_payments / 2;
							} else {
								$epayments_amount = $esl_other_payments;
							}
						} else {
							$epayments_amount = $esl_other_payments;
						}
						$other_payments_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'payments_title' => $sl_other_payments->payments_title,
							'payments_amount' => $epayments_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
					}
				}

				// set statutory_deductions
				$salary_statutory_deductions = $this->Employees_model->read_salary_statutory_deductions($this->input->post('emp_id'));
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($this->input->post('emp_id'));
				$statutory_deductions_amount = 0;
				if ($count_statutory_deductions > 0) {
					foreach ($salary_statutory_deductions as $sl_statutory_deduction) {
						$esl_statutory_deduction = $sl_statutory_deduction->deduction_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ededuction_amount = $esl_statutory_deduction / 2;
							} else {
								$ededuction_amount = $esl_statutory_deduction;
							}
						} else {
							$ededuction_amount = $esl_statutory_deduction;
						}
						$statutory_deduction_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'deduction_title' => $sl_statutory_deduction->deduction_title,
							'deduction_amount' => $ededuction_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$this->Payroll_model->add_salary_payslip_statutory_deductions($statutory_deduction_data);
					}
				}

				// set loan
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($this->input->post('emp_id'));
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($this->input->post('emp_id'));
				$loan_de_amount = 0;
				if ($count_loan_deduction > 0) {
					foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
						$esl_salary_loan_deduction = $sl_salary_loan_deduction->loan_deduction_amount;
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eloan_deduction_amount = $esl_salary_loan_deduction / 2;
							} else {
								$eloan_deduction_amount = $esl_salary_loan_deduction;
							}
						} else {
							$eloan_deduction_amount = $esl_salary_loan_deduction;
						}
						$loan_data = array(
							'payslip_id' => $result,
							'employee_id' => $this->input->post('emp_id'),
							'salary_month' => $this->input->post('pay_date'),
							'loan_title' => $sl_salary_loan_deduction->loan_deduction_title,
							'loan_amount' => $eloan_deduction_amount,
							'created_at' => date('d-m-Y h:i:s')
						);
						$_loan_data = $this->Payroll_model->add_salary_payslip_loan($loan_data);
					}
				}

				// set overtime
				// $salary_overtime = $this->Employees_model->read_salary_overtime($this->input->post('emp_id'));
				// $count_overtime = $this->Employees_model->count_employee_overtime($this->input->post('emp_id'));
				// $overtime_amount = 0;
				// if($count_overtime > 0) {
				// 	foreach($salary_overtime as $sl_overtime){
				// 		$eovertime_hours = $sl_overtime->overtime_hours;
				// 		$eovertime_rate = $sl_overtime->overtime_rate;
				// 		if($system[0]->is_half_monthly==1){
				// 			if($system[0]->half_deduct_month==2){
				// 				$esl_overtime_hr = $eovertime_hours/2;
				// 				$esl_overtime_rate = $eovertime_rate/2;
				// 			} else {
				// 				$esl_overtime_hr = $eovertime_hours;
				// 				$esl_overtime_rate = $eovertime_rate;
				// 			}
				// 		} else {
				// 			$esl_overtime_hr = $eovertime_hours;
				// 			$esl_overtime_rate = $eovertime_rate;
				// 		}
				// 		$overtime_data = array(
				// 		'payslip_id' => $result,
				// 		'employee_id' => $this->input->post('emp_id'),
				// 		'overtime_salary_month' => $this->input->post('pay_date'),
				// 		'overtime_title' => $sl_overtime->overtime_type,
				// 		'overtime_no_of_days' => $sl_overtime->no_of_days,
				// 		'overtime_hours' => $esl_overtime_hr,
				// 		'overtime_rate' => $esl_overtime_rate,
				// 		'created_at' => date('d-m-Y h:i:s')
				// 		);
				// 		$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
				// 	}
				// }

				$overtime_amount = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($overtime) {
					$ot_days = 0;
					$ot_hrs = 0;
					$ot_mins = 0;
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs += $total_hours[0];
						$ot_mins += $total_hours[1];
						$ot_days += 1;
					}
					if ($ot_mins > 0) {
						$ot_hrs += round($ot_mins / 60, 2);
					}

					//overtime rate
					$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($this->input->post('emp_id'));
					if ($overtime_rate) {
						$rate = $overtime_rate->overtime_pay_rate;
					} else {
						$week_hours = 44;
						// if($this->input->post('office_shift_id')) {
						// 	$shift = $this->Employees_model->read_shift_information($this->input->post('office_shift_id'));
						// 	if($shift) {
						// 		if($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
						// 			$time1 = $shift[0]->monday_in_time;
						// 			$time2 = $shift[0]->monday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
						// 			$time1 = $shift[0]->tuesday_in_time;
						// 			$time2 = $shift[0]->tuesday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
						// 			$time1 = $shift[0]->wednesday_in_time;
						// 			$time2 = $shift[0]->wednesday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
						// 			$time1 = $shift[0]->thursday_in_time;
						// 			$time2 = $shift[0]->thursday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
						// 			$time1 = $shift[0]->friday_in_time;
						// 			$time2 = $shift[0]->friday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
						// 			$time1 = $shift[0]->saturday_in_time;
						// 			$time2 = $shift[0]->saturday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 		if($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
						// 			$time1 = $shift[0]->sunday_in_time;
						// 			$time2 = $shift[0]->sunday_out_time;
						// 			$time1 = explode(':',$time1);
						// 			$time2 = explode(':',$time2);
						// 			$hours1 = $time1[0];
						// 			$hours2 = $time2[0];
						// 			$mins1 = $time1[1];
						// 			$mins2 = $time2[1];
						// 			$hours = $hours2 - $hours1;
						// 			$mins = 0;
						// 			if($hours < 0)
						// 			{
						// 				$hours = 24 + $hours;
						// 			}
						// 			if($mins2 >= $mins1) {
						// 				$mins = $mins2 - $mins1;
						// 			}
						// 			else {
						// 				$mins = ($mins2 + 60) - $mins1;
						// 				$hours--;
						// 			}
						// 			if($mins > 0) {
						// 				$hours += round($mins / 60, 2);
						// 			}
						// 			$week_hours += $hours;
						// 		}
						// 	}	
						// }else {
						// 	$week_hours = 40;
						// }
						$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
						$rate = $rate * 1.5;
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
					$g_sdl += $overtime_amount;

					$overtime_data = array(
						'payslip_id' => $result,
						'employee_id' => $this->input->post('emp_id'),
						'overtime_salary_month' => $this->input->post('pay_date'),
						'overtime_no_of_days' => $ot_days,
						'overtime_hours' => $ot_hrs,
						'overtime_rate' => $rate,
						'total_overtime' => $overtime_amount,
						'created_at' => date('d-m-Y h:i:s')
					);
					$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
				}

				//cpf
				$total_cpf =  $this->input->post('total_cpf');
				if ($total_cpf && $total_cpf > 0) {
					$ow_paid = $this->input->post('ow_paid');

					$cpf_data = [
						'payslip_id' => $result,
						// 'month_year' => $this->input->post('pay_date'). '-01',
						'month_year' => '01-' . $this->input->post('pay_date'),
						'ow_paid'	=> $ow_paid,
						'ow_cpf'	=> $this->input->post('ow_cpf'),
						'ow_cpf_employer'	=> $this->input->post('ow_cpf_employer'),
						'ow_cpf_employee'	=> $this->input->post('ow_cpf_employee'),
						'aw_paid'	=> $this->input->post('aw_paid'),
						'aw_cpf'	=> $this->input->post('aw_cpf'),
						'aw_cpf_employer'	=> $this->input->post('aw_cpf_employer'),
						'aw_cpf_employee'	=> $this->input->post('aw_cpf_employee')
					];

					$cpf_payslip = $this->Cpf_payslip_model->add_cpf_payslip($cpf_data);
				}

				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($this->input->post('emp_id'));
				if ($employee_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

					$fund_deduction_amount += $contribution_amount;
					$cdata = array(
						'payslip_id' => $result,
						'contribution_id' => $contribution_id,
						'contribution_amount' => $contribution_amount
					);
					$this->Contribution_fund_model->setContributionPayslip($cdata);
				}

				//ASHG Fund Contributions
				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($this->input->post('emp_id'));
				if ($employee_ashg_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

					$fund_deduction_amount += $contribution_amount;
					$cdata = array(
						'payslip_id' => $result,
						'contribution_id' => $contribution_id,
						'contribution_amount' => $contribution_amount
					);
					$this->Contribution_fund_model->setContributionPayslip($cdata);
				}

				//sdl
				$sdl = 0;
				if ($g_sdl > 1 && $g_sdl <= 800) {
					$sdl = 2;
				} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					$sdl_amount = (0.25 * $g_sdl) / 100;
					$sdl = $sdl_amount;
				} elseif ($g_sdl > 4500) {
					$sdl = 11.25;
				}

				$cdata = array(
					'payslip_id' => $result,
					'contribution_id' => 5,
					'contribution_amount' => $sdl
				);
				$this->Contribution_fund_model->setContributionPayslip($cdata);

				$Return['result'] = $this->lang->line('xin_success_payment_paid');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and add info in database > add monthly payment
	public function add_half_pay_to_all()
	{

		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();

		if ($this->input->post('add_type') == 'payroll') {
			if ($this->input->post('company_id') == 0 && $this->input->post('location_id') == 0 && $this->input->post('department_id') == 0) {
				$result = $this->Xin_model->all_employees();
			} else if ($this->input->post('company_id') != 0 && $this->input->post('location_id') == 0 && $this->input->post('department_id') == 0) {
				$eresult = $this->Payroll_model->get_company_payroll_employees($this->input->post('company_id'));
				$result = $eresult->result();
			} else if ($this->input->post('company_id') != 0 && $this->input->post('location_id') != 0 && $this->input->post('department_id') == 0) {
				$eresult = $this->Payroll_model->get_company_location_payroll_employees($this->input->post('company_id'), $this->input->post('location_id'));
				$result = $eresult->result();
			} else if ($this->input->post('company_id') != 0 && $this->input->post('location_id') != 0 && $this->input->post('department_id') != 0) {
				$eresult = $this->Payroll_model->get_company_location_dep_payroll_employees($this->input->post('company_id'), $this->input->post('location_id'), $this->input->post('department_id'));
				$result = $eresult->result();
			} else {
				$Return['error'] = $this->lang->line('xin_record_not_found');
			}
			$system = $this->Xin_model->read_setting_info(1);
			$system_settings = system_settings_info(1);
			if ($system_settings->online_payment_account == '') {
				$online_payment_account = 0;
			} else {
				$online_payment_account = $system_settings->online_payment_account;
			}
			foreach ($result as $empid) {
				$user_id = $empid->user_id;
				$user = $this->Xin_model->read_user_info($user_id);

				if ($system[0]->is_half_monthly == 1) {
					$is_half_monthly_payroll = 1;
				} else {
					$is_half_monthly_payroll = 0;
				}
				/* Server side PHP input validation */
				if ($empid->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $empid->basic_salary / 2;
					} else {
						$basic_salary = $empid->basic_salary;
					}
				} else {
					$basic_salary = $empid->daily_wages;
				}
				if ($basic_salary > 0) {
					// get designation
					$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
					if (!is_null($designation)) {
						$designation_id = $designation[0]->designation_id;
					} else {
						$designation_id = 1;
					}
					// department
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if (!is_null($department)) {
						$department_id = $department[0]->department_id;
					} else {
						$department_id = 1;
					}

					$salary_allowances = $this->Employees_model->read_salary_allowances($user_id);
					$count_allowances = $this->Employees_model->count_employee_allowances($user_id);
					$allowance_amount = 0;
					if ($count_allowances > 0) {
						foreach ($salary_allowances as $sl_allowances) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sl_allowances->allowance_amount / 2;
								} else {
									$eallowance_amount = $sl_allowances->allowance_amount;
								}
							} else {
								$eallowance_amount = $sl_allowances->allowance_amount;
							}
							$allowance_amount += $eallowance_amount;
							//  $allowance_amount += $sl_allowances->allowance_amount;
						}
					} else {
						$allowance_amount = 0;
					}
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
							// $loan_de_amount += $sl_salary_loan_deduction->loan_deduction_amount;
						}
					} else {
						$loan_de_amount = 0;
					}


					// 5: overtime
					$salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
					$count_overtime = $this->Employees_model->count_employee_overtime($user_id);
					$overtime_amount = 0;
					if ($count_overtime > 0) {
						foreach ($salary_overtime as $sl_overtime) {
							//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
							//$overtime_amount += $overtime_total;
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eovertime_hours = $sl_overtime->overtime_hours / 2;
									$eovertime_rate = $sl_overtime->overtime_rate / 2;
								} else {
									$eovertime_hours = $sl_overtime->overtime_hours;
									$eovertime_rate = $sl_overtime->overtime_rate;
								}
							} else {
								$eovertime_hours = $sl_overtime->overtime_hours;
								$eovertime_rate = $sl_overtime->overtime_rate;
							}
							$overtime_amount += $eovertime_hours * $eovertime_rate;
						}
					} else {
						$overtime_amount = 0;
					}



					// 6: statutory deductions
					// 4: other payment
					$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
					$other_payments_amount = 0;
					if (!is_null($other_payments)) :
						foreach ($other_payments->result() as $sl_other_payments) {
							//$other_payments_amount += $sl_other_payments->payments_amount;
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
					// all other payment
					$all_other_payment = $other_payments_amount;
					// 5: commissions
					$commissions = $this->Employees_model->set_employee_commissions($user_id);
					if (!is_null($commissions)) :
						$commissions_amount = 0;
						foreach ($commissions->result() as $sl_commissions) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $sl_commissions->commission_amount / 2;
								} else {
									$ecommissions_amount = $sl_commissions->commission_amount;
								}
							} else {
								$ecommissions_amount = $sl_commissions->commission_amount;
							}
							$commissions_amount += $ecommissions_amount;
							// $commissions_amount += $sl_commissions->commission_amount;
						}
					endif;
					// 6: statutory deductions
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
					if (!is_null($statutory_deductions)) :
						$statutory_deductions_amount = 0;
						foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') :
								$sta_salary = $basic_salary;
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
							//$statutory_deductions_amount += $sl_statutory_deductions->deduction_amount;
							endif;
						}
					endif;

					// add amount
					$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $other_payments_amount + $commissions_amount;
					// add amount
					$net_salary_default = $add_salary - $loan_de_amount - $statutory_deductions_amount;
					$net_salary = $net_salary_default;
					$net_salary = number_format((float)$net_salary, 2, '.', '');
					$jurl = random_string('alnum', 40);
					$data = array(
						'employee_id' => $user_id,
						'department_id' => $department_id,
						'company_id' => $user[0]->company_id,
						'designation_id' => $designation_id,
						'salary_month' => $this->input->post('month_year'),
						'basic_salary' => $basic_salary,
						'net_salary' => $net_salary,
						'wages_type' => $empid->wages_type,
						'total_allowances' => $allowance_amount,
						'total_loan' => $loan_de_amount,
						'total_overtime' => $overtime_amount,
						'total_commissions' => $commissions_amount,
						'total_statutory_deductions' => $statutory_deductions_amount,
						'total_other_payments' => $other_payments_amount,
						'is_half_monthly_payroll' => $is_half_monthly_payroll,
						'is_payment' => '1',
						'payslip_type' => 'full_monthly',
						'payslip_key' => $jurl,
						'year_to_date' => date('d-m-Y'),
						'created_at' => date('d-m-Y h:i:s')
					);
					$result = $this->Payroll_model->add_salary_payslip($data);

					if ($result) {
						$ivdata = array(
							'amount' => $net_salary,
							'account_id' => $online_payment_account,
							'transaction_type' => 'expense',
							'dr_cr' => 'cr',
							'transaction_date' => date('Y-m-d'),
							'payer_payee_id' => $user_id,
							'payment_method_id' => 3,
							'description' => 'Payroll Payments',
							'reference' => 'Payroll Payments',
							'invoice_id' => $result,
							'client_id' => $user_id,
							'created_at' => date('Y-m-d H:i:s')
						);
						$this->Finance_model->add_transactions($ivdata);
						// update data in bank account
						$account_id = $this->Finance_model->read_bankcash_information($online_payment_account);
						$acc_balance = $account_id[0]->account_balance - $net_salary;

						$data3 = array(
							'account_balance' => $acc_balance
						);
						$this->Finance_model->update_bankcash_record($data3, $online_payment_account);

						$salary_allowances = $this->Employees_model->read_salary_allowances($user_id);
						$count_allowances = $this->Employees_model->count_employee_allowances($user_id);
						$allowance_amount = 0;
						if ($count_allowances > 0) {
							foreach ($salary_allowances as $sl_allowances) {
								$allowance_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'allowance_title' => $sl_allowances->allowance_title,
									'allowance_amount' => $sl_allowances->allowance_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$_allowance_data = $this->Payroll_model->add_salary_payslip_allowances($allowance_data);
							}
						}
						// set commissions
						$salary_commissions = $this->Employees_model->read_salary_commissions($user_id);
						$count_commission = $this->Employees_model->count_employee_commissions($user_id);
						$commission_amount = 0;
						if ($count_commission > 0) {
							foreach ($salary_commissions as $sl_commission) {
								$commissions_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'commission_title' => $sl_commission->commission_title,
									'commission_amount' => $sl_commission->commission_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$this->Payroll_model->add_salary_payslip_commissions($commissions_data);
							}
						}
						// set other payments
						$salary_other_payments = $this->Employees_model->read_salary_other_payments($user_id);
						$count_other_payment = $this->Employees_model->count_employee_other_payments($user_id);
						$other_payment_amount = 0;
						if ($count_other_payment > 0) {
							foreach ($salary_other_payments as $sl_other_payments) {
								$other_payments_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'payments_title' => $sl_other_payments->payments_title,
									'payments_amount' => $sl_other_payments->payments_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
							}
						}
						// set statutory_deductions
						$salary_statutory_deductions = $this->Employees_model->read_salary_statutory_deductions($user_id);
						$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($user_id);
						$statutory_deductions_amount = 0;
						if ($count_statutory_deductions > 0) {
							foreach ($salary_statutory_deductions as $sl_statutory_deduction) {
								$statutory_deduction_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'deduction_title' => $sl_statutory_deduction->deduction_title,
									'deduction_amount' => $sl_statutory_deduction->deduction_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$this->Payroll_model->add_salary_payslip_statutory_deductions($statutory_deduction_data);
							}
						}
						$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($user_id);
						$count_loan_deduction = $this->Employees_model->count_employee_deductions($user_id);
						$loan_de_amount = 0;
						if ($count_loan_deduction > 0) {
							foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
								$loan_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'loan_title' => $sl_salary_loan_deduction->loan_deduction_title,
									'loan_amount' => $sl_salary_loan_deduction->loan_deduction_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$_loan_data = $this->Payroll_model->add_salary_payslip_loan($loan_data);
							}
						}
						$salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
						$count_overtime = $this->Employees_model->count_employee_overtime($user_id);
						$overtime_amount = 0;
						if ($count_overtime > 0) {
							foreach ($salary_overtime as $sl_overtime) {
								//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
								$overtime_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'overtime_salary_month' => $this->input->post('month_year'),
									'overtime_title' => $sl_overtime->overtime_type,
									'overtime_no_of_days' => $sl_overtime->no_of_days,
									'overtime_hours' => $sl_overtime->overtime_hours,
									'overtime_rate' => $sl_overtime->overtime_rate,
									'created_at' => date('d-m-Y h:i:s')
								);
								$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
							}
						}

						$Return['result'] = $this->lang->line('xin_success_payment_paid');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
				} // if basic salary
			}
			$Return['result'] = $this->lang->line('xin_success_payment_paid');
			$this->output($Return);
			exit;
		} // f
	}

	// Validate and add info in database > add monthly payment
	public function add_pay_to_all()
	{

		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();

		if ($this->input->post('add_type') == 'payroll') {
			$p_date = $this->input->post('month_year');
			if ($this->input->post('company_id') == 0 && $this->input->post('location_id') == 0 && $this->input->post('department_id') == 0) {
				$eresult = $this->Payroll_model->get_all_employees($p_date);
				$result = $eresult->result();
			} else if ($this->input->post('company_id') != 0 && $this->input->post('location_id') == 0 && $this->input->post('department_id') == 0) {
				$eresult = $this->Payroll_model->get_company_payroll_employees($this->input->post('company_id'), $p_date);
				$result = $eresult->result();
			} else if ($this->input->post('company_id') != 0 && $this->input->post('location_id') != 0 && $this->input->post('department_id') == 0) {
				$eresult = $this->Payroll_model->get_company_location_payroll_employees($this->input->post('company_id'), $this->input->post('location_id'), $p_date);
				$result = $eresult->result();
			} else if ($this->input->post('company_id') != 0 && $this->input->post('location_id') != 0 && $this->input->post('department_id') != 0) {
				$eresult = $this->Payroll_model->get_company_location_dep_payroll_employees($this->input->post('company_id'), $this->input->post('location_id'), $this->input->post('department_id'), $p_date);
				$result = $eresult->result();
			} else {
				$Return['error'] = $this->lang->line('xin_record_not_found');
			}
			$system = $this->Xin_model->read_setting_info(1);
			$system_settings = system_settings_info(1);

			if ($system_settings->online_payment_account == '') {
				$online_payment_account = 0;
			} else {
				$online_payment_account = $system_settings->online_payment_account;
			}
			// print_r($result);exit;
			foreach ($result as $empid) {
				$user_id = $empid->user_id;
				$user = $this->Xin_model->read_user_info($user_id);

				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($user[0]->office_shift_id);

				/* Server side PHP input validation */
				if ($empid->wages_type == 1) {
					$basic_salary = $empid->basic_salary;
				} else {
					$basic_salary = $empid->daily_wages;
				}
				$pay_count = $this->Payroll_model->read_make_payment_payslip_check($user_id, $this->input->post('month_year'));
				if ($pay_count->num_rows() > 0) {
					$pay_val = $this->Payroll_model->read_make_payment_payslip($user_id, $this->input->post('month_year'));
					$this->payslip_delete_all($pay_val[0]->payslip_id);
				}

				if ($basic_salary > 0) {
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;

					// get designation
					$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
					if (!is_null($designation)) {
						$designation_id = $designation[0]->designation_id;
					} else {
						$designation_id = 1;
					}
					// department
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if (!is_null($department)) {
						$department_id = $department[0]->department_id;
					} else {
						$department_id = 1;
					}

					$g_ordinary_wage += $basic_salary;
					$g_shg += $basic_salary;
					$g_sdl += $basic_salary;

					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($user_id, $p_date);
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

					// $salary_allowances = $this->Employees_model->read_salary_allowances($user_id);
					// $count_allowances = $this->Employees_model->count_employee_allowances($user_id);
					// $allowance_amount = 0;
					// if($count_allowances > 0) {
					// 	foreach($salary_allowances as $sl_allowances){
					// 		$allowance_amount += $sl_allowances->allowance_amount;
					// 	}
					// } else {
					// 	$allowance_amount = 0;
					// }

					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $p_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($user[0]->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}

					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($user_id, $p_date);
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $p_date);
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
							$loan_de_amount += $sl_salary_loan_deduction->loan_deduction_amount;
						}
					} else {
						$loan_de_amount = 0;
					}


					// 5: overtime
					// $salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
					// $count_overtime = $this->Employees_model->count_employee_overtime($user_id);
					// $overtime_amount = 0;
					// if($count_overtime > 0) {
					// 	foreach($salary_overtime as $sl_overtime){
					// 		$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
					// 		$overtime_amount += $overtime_total;
					// 	}
					// } else {
					// 	$overtime_amount = 0;
					// }



					// 4: other payment
					$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
					$other_payments_amount = 0;
					if (!is_null($other_payments)) :
						foreach ($other_payments->result() as $sl_other_payments) {
							$other_payments_amount += $sl_other_payments->payments_amount;
						}
					endif;

					// all other payment
					$all_other_payment = $other_payments_amount;

					// 5: commissions
					// $commissions = $this->Employees_model->set_employee_commissions($user_id);
					// if(!is_null($commissions)):
					// 	$commissions_amount = 0;
					// 	foreach($commissions->result() as $sl_commissions) {
					// 		$commissions_amount += $sl_commissions->commission_amount;
					// 	}
					// endif;
					// commissions
					$commissions_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($user_id, $p_date);
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
					$share_options = $this->Employees_model->getEmployeeShareOptions($user_id, $p_date);
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
								$statutory_deductions_amount += $st_amount;
							else :
								$statutory_deductions_amount += $sl_statutory_deductions->deduction_amount;
							endif;
						}
					endif;

					$overtime_amount = 0;
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($user_id, $p_date);
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
							// if($empid->office_shift_id) {
							// 	$shift = $this->Employees_model->read_shift_information($empid->office_shift_id);
							// 	if($shift) {
							// 		if($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
							// 			$time1 = $shift[0]->monday_in_time;
							// 			$time2 = $shift[0]->monday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 		if($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
							// 			$time1 = $shift[0]->tuesday_in_time;
							// 			$time2 = $shift[0]->tuesday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 		if($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
							// 			$time1 = $shift[0]->wednesday_in_time;
							// 			$time2 = $shift[0]->wednesday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 		if($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
							// 			$time1 = $shift[0]->thursday_in_time;
							// 			$time2 = $shift[0]->thursday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 		if($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
							// 			$time1 = $shift[0]->friday_in_time;
							// 			$time2 = $shift[0]->friday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 		if($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
							// 			$time1 = $shift[0]->saturday_in_time;
							// 			$time2 = $shift[0]->saturday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 		if($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
							// 			$time1 = $shift[0]->sunday_in_time;
							// 			$time2 = $shift[0]->sunday_out_time;
							// 			$time1 = explode(':',$time1);
							// 			$time2 = explode(':',$time2);
							// 			$hours1 = $time1[0];
							// 			$hours2 = $time2[0];
							// 			$mins1 = $time1[1];
							// 			$mins2 = $time2[1];
							// 			$hours = $hours2 - $hours1;
							// 			$mins = 0;
							// 			if($hours < 0)
							// 			{
							// 				$hours = 24 + $hours;
							// 			}
							// 			if($mins2 >= $mins1) {
							// 				$mins = $mins2 - $mins1;
							// 			}
							// 			else {
							// 				$mins = ($mins2 + 60) - $mins1;
							// 				$hours--;
							// 			}
							// 			if($mins > 0) {
							// 				$hours += round($mins / 60, 2);
							// 			}
							// 			$week_hours += $hours;
							// 		}
							// 	}	
							// }else {
							// 	$week_hours = 40;
							// }
							$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}

						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}

						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}


					// add amount
					$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $other_payments_amount + $commissions_amount + $share_options_amount;
					// add amount
					$net_salary_default = $add_salary - $loan_de_amount - $statutory_deductions_amount;
					$net_salary = $net_salary_default;
					$net_salary = number_format((float)$net_salary, 2, '.', '');
					$jurl = random_string('alnum', 40);

					//unpaid leave deduction
					$net_salary = $net_salary - $unpaid_leave_amount;

					/**
					 * Author : Syed Anees
					 * Sub Functionality : CPF on Gross Salary
					 */
					$emp_dob = $empid->date_of_birth;
					$dob = new DateTime($emp_dob);
					$today = new DateTime('01-' . $p_date);
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
					} elseif ($age_year > $age_upto && $age_year <= $age_above) {
						$age_from = $age_year;
						$age_to = $age_year;
					} elseif ($age_year > $age_above) {
						$age_from = $age_year;
						$age_to = null;
					} else {
						$age_from = null;
						$age_to = null;
					}
					$cpf_employee = 0;
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

							// if($pr_age_year == 0 && $pr_age_month > 0) {
							// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
							// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
							// }elseif($pr_age_year == 1 && $pr_age_month > 0) {
							// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
							// 	$cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
							// }elseif($pr_age_year >= 2) {
							// 	// $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 1);
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
								$total_cpf_contribution = $cpf_contribution->total_cpf;
								$employee_contribution = $cpf_contribution->contribution_employee;
								// $ordinary_wage = $basic_salary - $total_deduction;
								$ordinary_wage = $g_ordinary_wage;
								if ($ordinary_wage > $ordinary_wage_cap) {
									$ow = $ordinary_wage_cap;
								} else {
									$ow = $ordinary_wage;
								}

								//additional wage
								$additional_wage = $g_additional_wage;

								//total contribution
								$cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
								$cpf_total_aw = round(($total_cpf_contribution * $additional_wage) / 100);

								//employee contribution
								$cpf_employee_ow = floor(($employee_contribution * $ow) / 100);
								$cpf_employee_aw = floor(($employee_contribution * $additional_wage) / 100);

								$total_cpf = $cpf_total_ow + $cpf_total_aw;
								$cpf_employee = $cpf_employee_ow + $cpf_employee_aw;
								$net_salary = $net_salary - $cpf_employee;
								$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							}
						} else {
							$cpf_contribution = false;
						}
					}

					//SHG Contributions
					// $employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);

					// if($employee_contributions) {
					// 	$fund_deduction_amount = 0;
					// 	$gross_s = $g_shg;

					// 	$contribution_id = $employee_contributions->contribution_id;
					// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					// 	$fund_deduction_amount += $contribution_amount;
					// 	$net_salary = $net_salary - $fund_deduction_amount;
					// }

					//ASHG Contributions
					// $employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
					// if($employee_ashg_contributions) {
					// 	$fund_deduction_amount = 0;
					// 	$gross_s = $g_shg;

					// 	$contribution_id = $employee_ashg_contributions->contribution_id;
					// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					// 	$fund_deduction_amount += $contribution_amount;
					// 	$net_salary = $net_salary - $fund_deduction_amount;
					// }
					$fund_deduction_amount = 0;
					$contribution_data = $this->Employees_model->set_employee_contribution($user_id);

					if (count($contribution_data) > 0) {
						$contribution_fund = array();
						foreach ($contribution_data as $c_data) {
							$contribution_amount = $this->Contribution_fund_model->getContributionRate($basic_salary, $c_data->contribution_id);
							$fund_deduction_amount += $contribution_amount;
						}
						$net_salary = $net_salary - $fund_deduction_amount;
					}
					//SDL
					$sdl_total_amount = 0;
					if ($g_sdl > 1 && $g_sdl <= 800) {
						$sdl_total_amount = 2;
					} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
						$sdl_amount = (0.25 * $g_sdl) / 100;
						$sdl_total_amount = $sdl_amount;
					} elseif ($g_sdl > 4500) {
						$sdl_total_amount = 11.25;
					}
					// $net_salary = $net_salary - $sdl_total_amount;

					$data = array(
						'employee_id' => $user_id,
						'department_id' => $department_id,
						'company_id' => $user[0]->company_id,
						'designation_id' => $designation_id,
						'salary_month' => $this->input->post('month_year'),
						'basic_salary' => $basic_salary,
						'gross_salary' => $add_salary,
						'net_salary' => $net_salary,
						'wages_type' => $empid->wages_type,
						'total_allowances' => $allowance_amount,
						'total_loan' => $loan_de_amount,
						'total_overtime' => $overtime_amount,
						'total_commissions' => $commissions_amount,
						'total_statutory_deductions' => $statutory_deductions_amount,
						'total_other_payments' => $other_payments_amount,
						'cpf_employee_amount' => $cpf_employee,
						'is_payment' => '1',
						'payslip_type' => 'full_monthly',
						'payslip_key' => $jurl,
						'year_to_date' => date('d-m-Y'),
						'created_at' => date('d-m-Y h:i:s')
					);
					$result = $this->Payroll_model->add_salary_payslip($data);

					if ($result) {
						// $ivdata = array(
						// 	'amount' => $net_salary,
						// 	'account_id' => $online_payment_account,
						// 	'transaction_type' => 'expense',
						// 	'dr_cr' => 'cr',
						// 	'transaction_date' => date('Y-m-d'),
						// 	'payer_payee_id' => $user_id,
						// 	'payment_method_id' => 3,
						// 	'description' => 'Payroll Payments',
						// 	'reference' => 'Payroll Payments',
						// 	'invoice_id' => $result,
						// 	'client_id' => $user_id,
						// 	'created_at' => date('Y-m-d H:i:s')
						// );
						// $this->Finance_model->add_transactions($ivdata);
						// // update data in bank account
						// $account_id = $this->Finance_model->read_bankcash_information($online_payment_account);
						// $acc_balance = $account_id[0]->account_balance - $net_salary;

						// $data3 = array(
						// 	'account_balance' => $acc_balance
						// );
						// $this->Finance_model->update_bankcash_record($data3,$online_payment_account);

						$g_ordinary_wage = 0;
						$g_additional_wage = 0;
						$g_shg = 0;
						$g_sdl = 0;

						$g_ordinary_wage += $basic_salary;
						$g_shg += $basic_salary;
						$g_sdl += $basic_salary;

						// set allowance
						$allowance_amount = 0;
						$gross_allowance_amount = 0;
						$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($user_id, $this->input->post('month_year'));
						if ($salary_allowances) {
							foreach ($salary_allowances as $sl_allowances) {
								$esl_allowances = $sl_allowances->allowance_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$eallowance_amount = $esl_allowances / 2;
									} else {
										$eallowance_amount = $esl_allowances;
									}
								} else {
									$eallowance_amount = $esl_allowances;
								}
								$allowance_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'allowance_title' => $sl_allowances->allowance_title,
									'allowance_amount' => $eallowance_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$_allowance_data = $this->Payroll_model->add_salary_payslip_allowances($allowance_data);


								if (!empty($sl_allowances->salary_month)) {
									$g_additional_wage += $eallowance_amount;
								} else {
									$g_ordinary_wage += $eallowance_amount;
									if ($sl_allowances->id == 2) {
										$gross_allowance_amount = $eallowance_amount;
									}
								}

								if ($sl_allowances->sdl == 1) {
									$g_sdl += $eallowance_amount;
								}
								if ($sl_allowances->shg == 1) {
									$g_shg += $eallowance_amount;
								}

								$allowance_amount += $eallowance_amount;

								$allowance_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'allowance_amount' => $eallowance_amount,
									'allowance_name' => $sl_allowances->allowance_title,
									'deduction_name' => '',
									'deduction_amount' => ''

								);
								$this->Payroll_model->add_mapping($allowance_data);
							}
						}

						//3: Gross rate of pay (unpaid leave deduction)
						$holidays_count = 0;
						$no_of_working_days = 0;
						$month_start_date = new DateTime('01-' . $this->input->post('month_year'));
						$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
						$month_end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($month_start_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$period_day = $p->format('l');
							$period_date = $p->format('Y-m-d');

							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($user[0]->company_id, $period_date);
							if ($is_holiday) {
								$holidays_count += 1;
							}

							//working days excluding holidays based on office shift
							if ($period_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($period_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($period_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($period_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($period_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($period_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($period_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_working_days += 1;
								}
							}
						}

						//unpaid leave
						$unpaid_leave_amount = 0;
						$leaves_taken_count = 0;
						$leave_period = array();
						$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($user_id, $this->input->post('month_year'));
						if ($unpaid_leaves) {
							foreach ($unpaid_leaves as $k => $l) {
								$pay_date_month = new DateTime('01-' . $this->input->post('month_year'));
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
						if ($unpaid_leave_amount > 0 && $leaves_taken_count > 0) {
							foreach ($leave_period as $l) {
								$is_half = $l['is_half'];
								$leave_dates = $l['leave_date'];
								$leave_day_pay = round(($basic_salary + $gross_allowance_amount) / $no_of_working_days, 2);
								if ($is_half) {
									$leave_day_pay = $leave_day_pay / 2;
								}
								foreach ($leave_dates as $ld) {
									$unpaid_leave_data = array(
										'payslip_id' => $result,
										'employee_id' => $user_id,
										'salary_month' => $this->input->post('month_year'),
										'leave_date' => $ld,
										'leave_amount' => $leave_day_pay,
										'is_half' => $is_half,
										'total_leave_amount' => $unpaid_leave_amount
									);
									$this->Payroll_model->add_salary_payslip_leave_deduction($unpaid_leave_data);
								}
							}
						}
						// commissions
						$commission_amount = 0;
						$commissions = $this->Employees_model->getEmployeeMonthlyCommission($user_id, $this->input->post('month_year'));
						if ($commissions) {
							foreach ($commissions as $c) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$ecommission_amount = $c->commission_amount / 2;
									} else {
										$ecommission_amount = $c->commission_amount;
									}
								} else {
									$ecommission_amount = $c->commission_amount;
								}

								if ($c->commission_type == 9) {
									$g_ordinary_wage += $ecommission_amount;
								} elseif ($c->commission_type == 10) {
									$g_additional_wage += $ecommission_amount;
								}

								if ($c->sdl == 1) {
									$g_sdl += $ecommission_amount;
								}
								if ($c->shg == 1) {
									$g_shg += $ecommission_amount;
								}

								$commissions_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'commission_id' => $c->commission_type,
									'commission_amount' => $ecommission_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$this->Payroll_model->add_salary_payslip_commissions($commissions_data);

								$commission_amount += $ecommission_amount;
							}
						}

						//share options
						$share_options_amount = 0;
						$share_options = $this->Employees_model->getEmployeeShareOptions($user_id, $this->input->post('month_year'));
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

							$share_options_data = array(
								'payslip_id' => $result,
								'employee_id' => $user_id,
								'salary_month' => $this->input->post('month_year'),
								'amount' => round($share_options_amount, 2)
							);
							$this->Payroll_model->add_salary_payslip_share_options($share_options_data);
						}


						// set other payments
						$salary_other_payments = $this->Employees_model->read_salary_other_payments($user_id);
						$count_other_payment = $this->Employees_model->count_employee_other_payments($user_id);
						$other_payment_amount = 0;
						if ($count_other_payment > 0) {
							foreach ($salary_other_payments as $sl_other_payments) {
								$other_payments_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'payments_title' => $sl_other_payments->payments_title,
									'payments_amount' => $sl_other_payments->payments_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
							}
						}

						// set statutory_deductions
						$salary_statutory_deductions = $this->Employees_model->read_salary_statutory_deductions($user_id);
						$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($user_id);
						$statutory_deductions_amount = 0;
						if ($count_statutory_deductions > 0) {
							foreach ($salary_statutory_deductions as $sl_statutory_deduction) {
								$statutory_deduction_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'deduction_title' => $sl_statutory_deduction->deduction_title,
									'deduction_amount' => $sl_statutory_deduction->deduction_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$this->Payroll_model->add_salary_payslip_statutory_deductions($statutory_deduction_data);

								$allowance_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'allowance_amount' => '',
									'allowance_name' => '',
									'deduction_name' => $sl_statutory_deduction->deduction_title,
									'deduction_amount' => $sl_statutory_deduction->deduction_amount,

								);
								$this->Payroll_model->add_mapping($allowance_data);
							}
						}

						$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($user_id);
						$count_loan_deduction = $this->Employees_model->count_employee_deductions($user_id);
						$loan_de_amount = 0;
						if ($count_loan_deduction > 0) {
							foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
								$loan_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'salary_month' => $this->input->post('month_year'),
									'loan_title' => $sl_salary_loan_deduction->loan_deduction_title,
									'loan_amount' => $sl_salary_loan_deduction->loan_deduction_amount,
									'created_at' => date('d-m-Y h:i:s')
								);
								$_loan_data = $this->Payroll_model->add_salary_payslip_loan($loan_data);
								$allowance_data = array(
									'payslip_id' => $result,
									'employee_id' => $user_id,
									'allowance_amount' => '',
									'allowance_name' => '',
									'deduction_name' => $sl_salary_loan_deduction->loan_deduction_title,
									'deduction_amount' => $sl_salary_loan_deduction->loan_deduction_amount,

								);
								$this->Payroll_model->add_mapping($allowance_data);
							}
						}

						// $salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
						// $count_overtime = $this->Employees_model->count_employee_overtime($user_id);
						// $overtime_amount = 0;
						// if($count_overtime > 0) {
						// 	foreach($salary_overtime as $sl_overtime){
						// 		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
						// 		$overtime_data = array(
						// 		'payslip_id' => $result,
						// 		'employee_id' => $user_id,
						// 		'overtime_salary_month' => $this->input->post('month_year'),
						// 		'overtime_title' => $sl_overtime->overtime_type,
						// 		'overtime_no_of_days' => $sl_overtime->no_of_days,
						// 		'overtime_hours' => $sl_overtime->overtime_hours,
						// 		'overtime_rate' => $sl_overtime->overtime_rate,
						// 		'created_at' => date('d-m-Y h:i:s')
						// 		);
						// 		$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
						// 	}
						// }

						$overtime_amount = 0;
						$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($user_id, $this->input->post('month_year'));
						if ($overtime) {
							$ot_days = 0;
							$ot_hrs = 0;
							$ot_mins = 0;
							foreach ($overtime as $ot) {
								$total_hours = explode(':', $ot->total_hours);
								$ot_hrs += $total_hours[0];
								$ot_mins += $total_hours[1];
								$ot_days += 1;
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
								// if($empid->office_shift_id) {
								// 	$shift = $this->Employees_model->read_shift_information($empid->office_shift_id);
								// 	if($shift) {
								// 		if($shift[0]->monday_in_time != '' && $shift[0]->monday_out_time != '') {
								// 			$time1 = $shift[0]->monday_in_time;
								// 			$time2 = $shift[0]->monday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 		if($shift[0]->tuesday_in_time != '' && $shift[0]->tuesday_out_time != '') {
								// 			$time1 = $shift[0]->tuesday_in_time;
								// 			$time2 = $shift[0]->tuesday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 		if($shift[0]->wednesday_in_time != '' && $shift[0]->wednesday_out_time != '') {
								// 			$time1 = $shift[0]->wednesday_in_time;
								// 			$time2 = $shift[0]->wednesday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 		if($shift[0]->thursday_in_time != '' && $shift[0]->thursday_out_time != '') {
								// 			$time1 = $shift[0]->thursday_in_time;
								// 			$time2 = $shift[0]->thursday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 		if($shift[0]->friday_in_time != '' && $shift[0]->friday_out_time != '') {
								// 			$time1 = $shift[0]->friday_in_time;
								// 			$time2 = $shift[0]->friday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 		if($shift[0]->saturday_in_time != '' && $shift[0]->saturday_out_time != '') {
								// 			$time1 = $shift[0]->saturday_in_time;
								// 			$time2 = $shift[0]->saturday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 		if($shift[0]->sunday_in_time != '' && $shift[0]->sunday_out_time != '') {
								// 			$time1 = $shift[0]->sunday_in_time;
								// 			$time2 = $shift[0]->sunday_out_time;
								// 			$time1 = explode(':',$time1);
								// 			$time2 = explode(':',$time2);
								// 			$hours1 = $time1[0];
								// 			$hours2 = $time2[0];
								// 			$mins1 = $time1[1];
								// 			$mins2 = $time2[1];
								// 			$hours = $hours2 - $hours1;
								// 			$mins = 0;
								// 			if($hours < 0)
								// 			{
								// 				$hours = 24 + $hours;
								// 			}
								// 			if($mins2 >= $mins1) {
								// 				$mins = $mins2 - $mins1;
								// 			}
								// 			else {
								// 				$mins = ($mins2 + 60) - $mins1;
								// 				$hours--;
								// 			}
								// 			if($mins > 0) {
								// 				$hours += round($mins / 60, 2);
								// 			}
								// 			$week_hours += $hours;
								// 		}
								// 	}	
								// }else {
								// 	$week_hours = 40;
								// }
								$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
								$rate = $rate * 1.5;
							}

							if ($ot_hrs > 0) {
								$overtime_amount = round($ot_hrs * $rate, 2);
							}
							$g_ordinary_wage += $overtime_amount;
							$g_sdl += $overtime_amount;

							$overtime_data = array(
								'payslip_id' => $result,
								'employee_id' => $user_id,
								'overtime_salary_month' => $this->input->post('month_year'),
								'overtime_no_of_days' => $ot_days,
								'overtime_hours' => $ot_hrs,
								'overtime_rate' => $rate,
								'total_overtime' => $overtime_amount,
								'created_at' => date('d-m-Y h:i:s')
							);
							$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
						}

						//Other Fund Contributions
						$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);
						if ($employee_contributions) {
							$fund_deduction_amount = 0;
							$gross_s = $g_shg;
							$contribution_id = $employee_contributions->contribution_id;
							$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

							$fund_deduction_amount += $contribution_amount;
							$cdata = array(
								'payslip_id' => $result,
								'contribution_id' => $contribution_id,
								'contribution_amount' => $contribution_amount
							);
							$this->Contribution_fund_model->setContributionPayslip($cdata);
						}

						//ASHG Fund Contributions
						$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
						if ($employee_ashg_contributions) {
							$fund_deduction_amount = 0;
							$gross_s = $g_shg;
							$contribution_id = $employee_ashg_contributions->contribution_id;
							$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

							$fund_deduction_amount += $contribution_amount;
							$cdata = array(
								'payslip_id' => $result,
								'contribution_id' => $contribution_id,
								'contribution_amount' => $contribution_amount
							);
							$this->Contribution_fund_model->setContributionPayslip($cdata);
						}

						//sdl
						$sdl = 0;
						if ($g_sdl > 1 && $g_sdl <= 800) {
							$sdl = 2;
						} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
							$sdl_amount = (0.25 * $g_sdl) / 100;
							$sdl = $sdl_amount;
						} elseif ($g_sdl > 4500) {
							$sdl = 11.25;
						}

						$cdata = array(
							'payslip_id' => $result,
							'contribution_id' => 5,
							'contribution_amount' => $sdl
						);
						$this->Contribution_fund_model->setContributionPayslip($cdata);

						$cpf_total = 0;
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

							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
							$net_salary = $net_salary - $cpf_employee;
							$cpf_total = $cpf_employee + $cpf_employer;
						}

						//cpf

						if ($cpf_total > 0) {

							$ow_paid = $g_ordinary_wage;
							$aw_paid = $g_additional_wage;

							$cpf_data = [
								'payslip_id' => $result,
								'month_year' => '01-' . $this->input->post('month_year'),
								'ow_paid'	=> $ow_paid,
								'ow_cpf'	=> $ow,
								'ow_cpf_employer'	=> $ow_cpf_employer,
								'ow_cpf_employee'	=> $ow_cpf_employee,
								'aw_paid'	=> $aw_paid,
								'aw_cpf'	=> $aw_paid,
								'aw_cpf_employer'	=> $aw_cpf_employer,
								'aw_cpf_employee'	=> $aw_cpf_employee
							];

							$cpf_payslip = $this->Cpf_payslip_model->add_cpf_payslip($cpf_data);
						}

						$Return['result'] = $this->lang->line('xin_success_payment_paid');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
				} // if basic salary

			}
			$Return['result'] = $this->lang->line('xin_success_payment_paid');
			$this->output($Return);
			exit;
		} // f
	}

	// hourly_list > templates
	public function payment_history_list()
	{

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/payment_history", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if ($this->input->get("ihr") == 'true') {
			if ($this->input->get("company_id") == 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->all_employees_payment_history();
				} else {
					$history = $this->Payroll_model->all_employees_payment_history_month($this->input->get("salary_month"));
				}
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->get_company_payslip_history($this->input->get("company_id"));
				} else {
					$history = $this->Payroll_model->get_company_payslip_history_month($this->input->get("company_id"), $this->input->get("salary_month"));
				}
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") == 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->get_company_location_payslips($this->input->get("company_id"), $this->input->get("location_id"));
				} else {
					$history = $this->Payroll_model->get_company_location_payslips_month($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("salary_month"));
				}
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") != 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->get_company_location_department_payslips($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"));
				} else {
					$history = $this->Payroll_model->get_company_location_department_payslips_month($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"), $this->input->get("salary_month"));
				}
			}/**/ /*else if($this->input->get("company_id")!=0 && $this->input->get("location_id")!=0 && $this->input->get("department_id")!=0 && $this->input->get("designation_id")!=0){
				$history = $this->Payroll_model->get_company_location_department_designation_payslips($this->input->get("company_id"),$this->input->get("location_id"),$this->input->get("department_id"),$this->input->get("designation_id"));
			}*/
		} else {
			if ($user_info[0]->user_role_id == 1) {
				$history = $this->Payroll_model->employees_payment_history();
			} else {
				if (in_array('391', $role_resources_ids)) {
					$history = $this->Payroll_model->get_company_payslips($user_info[0]->company_id);
				} else {
					$history = $this->Payroll_model->get_payroll_slip($session['user_id']);
				}
			}
		}
		$data = array();

		foreach ($history->result() as $r) {

			// get addd by > template
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			if (!is_null($user)) {
				$full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
				$emp_link = $user[0]->employee_id;
				$month_payment = date("F, Y", strtotime('01-' . $r->salary_month));

				$p_amount = $this->Xin_model->currency_sign($r->net_salary);

				// get date > created at > and format
				$created_at = $this->Xin_model->set_date_format($r->created_at);
				// get designation
				$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
				if (!is_null($designation)) {
					$designation_name = $designation[0]->designation_name;
				} else {
					$designation_name = '--';
				}
				// department
				$department = $this->Department_model->read_department_information($user[0]->department_id);
				if (!is_null($department)) {
					$department_name = $department[0]->department_name;
				} else {
					$department_name = '--';
				}
				$department_designation = $designation_name . ' (' . $department_name . ')';
				// get company
				$company = $this->Xin_model->read_company_info($user[0]->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}
				// bank account
				$bank_account = $this->Employees_model->get_employee_bank_account_last($user[0]->user_id);
				if (!is_null($bank_account)) {
					$account_number = $bank_account[0]->account_number;
				} else {
					$account_number = '--';
				}
				$payslip = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $r->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $r->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				$ifull_name = nl2br($full_name . "\r\n <small class='text-muted'><i>" . $this->lang->line('xin_employees_id') . ': ' . $emp_link . "<i></i></i></small>\r\n <small class='text-muted'><i>" . $department_designation . '<i></i></i></small>');
				$data[] = array(
					$payslip,
					$full_name,
					$comp_name,
					$account_number,
					$p_amount,
					$month_payment,
					$created_at,
				);
			}
		} // if employee available

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $history->num_rows(),
			"recordsFiltered" => $history->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	// payment history
	public function payslip()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		//$data['title'] = $this->Xin_model->site_title();
		$key = $this->uri->segment(5);

		$result = $this->Payroll_model->read_salary_payslip_info_key($key);
		// echo '<pre>'; print_r($result); exit;
		if (is_null($result)) {
			redirect('admin/payroll/generate_payslip');
		}
		$p_method = '';
		/*$payment_method = $this->Xin_model->read_payment_method($result[0]->payment_method);
		if(!is_null($payment_method)){
		  $p_method = $payment_method[0]->method_name;
		} else {
		  $p_method = '--';
		}*/
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// user full name
		if (!is_null($user)) {
			$first_name = $user[0]->first_name;
			$last_name = $user[0]->last_name;
		} else {
			$first_name = '--';
			$last_name = '--';
		}
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';
		}

		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';
		}

		//$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data = array(
			'title' => $this->lang->line('xin_payroll_employee_payslip') . ' | ' . $this->Xin_model->site_title(),
			'first_name' => $first_name,
			'last_name' => $last_name,
			'employee_id' => $user[0]->employee_id,
			'euser_id' => $user[0]->user_id,
			'id_no' => $user[0]->id_no,
			'date_of_birth' => $user[0]->date_of_birth,
			'contact_no' => $user[0]->contact_no,
			'date_of_joining' => $user[0]->date_of_joining,
			'department_name' => $department_name,
			'designation_name' => $designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender,
			'make_payment_id' => $result[0]->payslip_id,
			'wages_type' => $result[0]->wages_type,
			'payment_status' => ($result[0]->status == 0 ? 'Pending' : 'Paid'),
			'payment_date' => $result[0]->salary_month,
			'year_to_date' => $result[0]->year_to_date,
			'basic_salary' => $result[0]->basic_salary,
			'daily_wages' => $result[0]->daily_wages,
			'payment_method' => $p_method,
			'total_allowances' => $result[0]->total_allowances,
			'total_loan' => $result[0]->total_loan,
			'total_overtime' => $result[0]->total_overtime,
			'total_commissions' => $result[0]->total_commissions,
			'total_statutory_deductions' => $result[0]->total_statutory_deductions,
			'total_other_payments' => $result[0]->total_other_payments,
			'share_option_amount' => $result[0]->share_option_amount,
			'net_salary' => $result[0]->net_salary,
			'claim_amount' => $result[0]->claim_amount,
			'other_payment' => $result[0]->other_payment,
			'payslip_key' => $result[0]->payslip_key,
			'payslip_type' => $result[0]->payslip_type,
			'hours_worked' => $result[0]->hours_worked,
			'pay_comments' => $result[0]->pay_comments,
			'deduction_amount' => $result[0]->deduction_amount,
			'is_payment' => $result[0]->is_payment,
			'approval_status' => $result[0]->status,
			'gross_salary'	=> $result[0]->gross_salary,
			'cpf_employee_amount' => $result[0]->cpf_employee_amount,
			'cpf_employer_amount' => $result[0]->cpf_employer_amount,
			'additional_fund' => $result[0]->cpf_employer_amount,
			'additonal_allowance' => $result[0]->additonal_allowance,
			'mapping_data' => $this->Payroll_model->get_mapping_data($result[0]->payslip_id)

		);
		$data['breadcrumbs'] = $this->lang->line('xin_payroll_employee_payslip');
		$data['path_url'] = 'payslip';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		//cpf
		$cpf_result = $this->Cpf_payslip_model->getCpfByPayslipId($result[0]->payslip_id);
		if ($cpf_result) {
			$data['cpf_employer'] = round($cpf_result->ow_cpf_employer + $cpf_result->aw_cpf_employer, 2);
			$data['cpf_employee'] = round($cpf_result->ow_cpf_employee + $cpf_result->aw_cpf_employee, 2);
		}

		//Contribution funds
		$contribution = $this->Contribution_fund_model->getContributionPayslip($result[0]->payslip_id);
		if ($contribution) {
			$contribution_fund = array();
			foreach ($contribution as $i => $c) {
				if ($c->contribution_id != 5) {
					$contribution_fund[$i]['contribution_id'] = $c->contribution_id;
					$contribution_fund[$i]['contribution'] = $c->contribution;
					$contribution_fund[$i]['contribution_amount'] = $c->contribution_amount;
				}
			}
			$data['contribution_fund'] = $contribution_fund;
		}

		//leave deduction
		$leave_deduction = $this->Payroll_model->getLeaveDeductionPayslip($result[0]->payslip_id);
		if ($leave_deduction) {
			$data['leave_deduction'] = round($leave_deduction[0]->total_leave_amount, 2);
		}
		//echo '<pre>'; print_r($data); exit;
		if (!empty($session)) {
			if ($result[0]->payslip_type == 'hourly') {
				$data['subview'] = $this->load->view("admin/payroll/hourly_payslip", $data, TRUE);
			} else {
				$data['subview'] = $this->load->view("admin/payroll/payslip", $data, TRUE);
			}
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/');
		}
	}

	public function pdf_create()
	{

		//$this->load->library('Pdf');
		$system = $this->Xin_model->read_setting_info(1);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$key = $this->uri->segment(5);
		$payment = $this->Payroll_model->read_salary_payslip_info_key($key);

		if (is_null($payment)) {
			redirect('admin/payroll/generate_payslip');
		}
		$user = $this->Xin_model->read_user_info($payment[0]->employee_id);

		// if password generate option enable
		if ($system[0]->is_payslip_password_generate == 1) {
			/**
			 * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
			 * to provide password as selected format in settings module.
			 */
			if ($system[0]->payslip_password_format == 'dateofbirth') {
				$password_val = date("dmY", strtotime($user[0]->date_of_birth));
			} else if ($system[0]->payslip_password_format == 'contact_no') {
				$password_val = $user[0]->contact_no;
			} else if ($system[0]->payslip_password_format == 'full_name') {
				$password_val = $user[0]->first_name . $user[0]->last_name;
			} else if ($system[0]->payslip_password_format == 'email') {
				$password_val = $user[0]->email;
			} else if ($system[0]->payslip_password_format == 'password') {
				$password_val = $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'user_password') {
				$password_val = $user[0]->username . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'employee_id') {
				$password_val = $user[0]->employee_id;
			} else if ($system[0]->payslip_password_format == 'employee_id_password') {
				$password_val = $user[0]->employee_id . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
				$dob = date("dmY", strtotime($user[0]->date_of_birth));
				$fname = $user[0]->first_name;
				$lname = $user[0]->last_name;
				$password_val = $dob . $fname[0] . $lname[0];
			}
			$pdf->SetProtection(array('print', 'copy', 'modify'), $password_val, $password_val, 0, null);
		}


		$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($_des_name)) {
			$_designation_name = $_des_name[0]->designation_name;
		} else {
			$_designation_name = '';
		}
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$_department_name = $department[0]->department_name;
		} else {
			$_department_name = '';
		}
		//$location = $this->Xin_model->read_location_info($department[0]->location_id);
		// company info
		$company = $this->Xin_model->read_company_info($user[0]->company_id);

		$p_method = '';
		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '--';
			}
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} else {
			$company_name = '--';
			$address_1 = '--';
			$address_2 = '--';
			$city = '--';
			$state = '--';
			$zipcode = '--';
			$country_name = '--';
			$c_info_email = '--';
			$c_info_phone = '--';
		}
		$company_logo = base_url() . 'uploads/logo/payroll/' . $system[0]->payroll_logo;
		$company_logos = base_url() . 'uploads/company/' . $company[0]->logo;

		//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		// set default header data
		//$c_info_address = $address_1.' '.$address_2.', '.$city.' - '.$zipcode.', '.$country_name;
		$c_info_address = $address_1 . ' ' . $address_2 . ', ' . $city . ' - ' . $zipcode;
		//$email_phone_address = "$c_info_address \n".$this->lang->line('xin_phone')." : $c_info_phone | ".$this->lang->line('dashboard_email')." : $c_info_email ";

		$email_phone_address = "$c_info_address \n" . $this->lang->line('xin_phone') . " : $c_info_phone | " . $this->lang->line('dashboard_email') . " : $c_info_email \n";

		$header_string = $email_phone_address;
		// set document information
		$pdf->SetCreator('HRSALE');
		$pdf->SetAuthor('HRSALE');
		//$pdf->SetTitle('Workable-Zone - Payslip');
		//$pdf->SetSubject('TCPDF Tutorial');
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		$pdf->SetHeaderData(PDF_HEADER_LOGO,  15, $company_name, $header_string);
		//$pdf->SetHeaderData("", 15, $company_name, $header_string);

		$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

		// set header and footer fonts
		$pdf->setHeaderFont(array('helvetica', '', 11.5));
		$pdf->setFooterFont(array('helvetica', '', 9));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont('courier');

		// set margins
		$pdf->SetMargins(15, 27, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 25);

		// set image scale factor
		$pdf->setImageScale(1.25);
		$pdf->SetAuthor('HRSALE');
		$pdf->SetTitle($company_name . ' - ' . $this->lang->line('xin_print_payslip'));
		$pdf->SetSubject($this->lang->line('xin_payslip'));
		$pdf->SetKeywords($this->lang->line('xin_payslip'));
		// set font
		$pdf->SetFont('helvetica', 'B', 10);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 10, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		// -----------------------------------------------------------------------------
		$fname = $user[0]->first_name . ' ' . $user[0]->last_name;
		$created_at = $this->Xin_model->set_date_format($payment[0]->created_at);
		$date_of_joining = $this->Xin_model->set_date_format($user[0]->date_of_joining);
		$salary_month = $this->Xin_model->set_date_format($payment[0]->salary_month);
		// check
		$half_title = '';
		if ($system[0]->is_half_monthly == 1) {
			$payment_check1 = $this->Payroll_model->read_make_payment_payslip_half_month_check_first($payment[0]->employee_id, $payment[0]->salary_month);
			$payment_check2 = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($payment[0]->employee_id, $payment[0]->salary_month);
			$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($payment[0]->employee_id, $payment[0]->salary_month);
			if ($payment_check->num_rows() > 1) {
				if ($payment_check2[0]->payslip_key == $this->uri->segment(5)) {
					$half_title = '(' . $this->lang->line('xin_title_second_half') . ')';
				} else if ($payment_check1[0]->payslip_key == $this->uri->segment(5)) {
					$half_title = '(' . $this->lang->line('xin_title_first_half') . ')';
				} else {
					$half_title = '';
				}
			} else {
				$half_title = '(' . $this->lang->line('xin_title_first_half') . ')';
			}
			$half_title = $half_title;
		} else {
			$half_title = '';
		}

		// basic salary
		$bs = 0;
		$bs = $payment[0]->basic_salary;
		//$company_detail = $this->Employees_model->get_company_detail($user[0]->payslip_id);

		// allowances
		$count_allowances = $this->Employees_model->count_employee_allowances_payslip($payment[0]->payslip_id);
		$allowances = $this->Employees_model->set_employee_allowances_payslip($payment[0]->payslip_id);
		//echo $this->db->last_query();exit;
		// commissions
		$count_commissions = $this->Employees_model->count_employee_commissions_payslip($payment[0]->payslip_id);
		$commissions = $this->Employees_model->set_employee_commissions_payslip($payment[0]->payslip_id);
		// otherpayments
		$count_other_payments = $this->Employees_model->count_employee_other_payments_payslip($payment[0]->payslip_id);
		$other_payments = $this->Employees_model->set_employee_other_payments_payslip($payment[0]->payslip_id);
		// statutory_deductions
		$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions_payslip($payment[0]->payslip_id);
		$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions_payslip($payment[0]->payslip_id);
		// overtime
		$count_overtime = $this->Employees_model->count_employee_overtime_payslip($payment[0]->payslip_id);
		$overtime = $this->Employees_model->set_employee_overtime_payslip($payment[0]->payslip_id);
		// loan
		$count_loan = $this->Employees_model->count_employee_deductions_payslip($payment[0]->payslip_id);
		$loan = $this->Employees_model->set_employee_deductions_payslip($payment[0]->payslip_id);
		//
		$statutory_deduction_amount = 0;
		$loan_de_amount = 0;
		$allowances_amount = 0;
		$commissions_amount = 0;
		$other_payments_amount = 0;
		$overtime_amount = 0;
		// laon
		if ($count_loan > 0) :
			foreach ($loan->result() as $r_loan) {
				$loan_de_amount += $r_loan->loan_amount;
			}
			$loan_de_amount = $loan_de_amount;
		else :
			$loan_de_amount = 0;
		endif;
		// allowances
		$allowances_amount = 0;
		foreach ($allowances->result() as $sl_allowances) {
			$allowances_amount += $sl_allowances->allowance_amount;
		}
		// commission
		$commissions_amount = 0;
		foreach ($commissions->result() as $sl_commissions) {
			$commissions_amount += $sl_commissions->commission_amount;
		}
		// statutory deduction
		$statutory_deduction_amount = 0;
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			//$statutory_deduction_amount += $sl_statutory_deductions->deduction_amount;
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $bs;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				$statutory_deduction_amount += $st_amount;
			else :
				$statutory_deduction_amount += $sl_statutory_deductions->deduction_amount;
			endif;
		}
		// other amount
		$other_payments_amount = 0;
		foreach ($other_payments->result() as $sl_other_payments) {
			$other_payments_amount += $sl_other_payments->payments_amount;
		}
		// overtime
		$overtime_amount = 0;
		foreach ($overtime->result() as $r_overtime) {
			$overtime_total = (float)$r_overtime->overtime_hours * (float)$r_overtime->overtime_rate;
			$overtime_amount += $overtime_total;
		}

		//cpf
		$cpf_result = $this->Cpf_payslip_model->getCpfByPayslipId($payment[0]->payslip_id);
		if ($cpf_result) {
			$cpf_employer = round($cpf_result->ow_cpf_employer + $cpf_result->aw_cpf_employer, 2);
			$cpf_employee = round($cpf_result->ow_cpf_employee + $cpf_result->aw_cpf_employee, 2);
		} else {
			$cpf_employer = 0;
			$cpf_employee = 0;
		}

		//share options
		$share_option_amount = 0;
		$share_option = $this->Payroll_model->getShareOptionPayslip($payment[0]->payslip_id);
		if ($share_option) {
			$share_option_amount = round($share_option->amount, 2);
		}

		//Leave Deductions
		$total_leave_deduction = 0;
		$leave_deductions = $this->Payroll_model->getLeaveDeductionPayslip($payment[0]->payslip_id);
		if ($leave_deductions) {
			$total_leave_deduction = round($leave_deductions[0]->total_leave_amount, 2);
		}
		$system = $this->Xin_model->read_setting_info(1);

		$company_info = $this->Xin_model->read_company_setting_info(1);
		$company_logo = base_url() . 'uploads/company/' . $company_info[0]->logo;

		// <td align="left" width="30%">'.$pdf->Image($company_logos,20, 30, 20, 20).'</td>

		$tbl = '<br><br>
		<table cellpadding="1" cellspacing="1" border="0">
		<tr>
				<td align="left" width="30%"></td>
				<td align="right" width="70%"><h1>' . $company_name . '</h1><br><br><br></td>

			</tr>
			
		</table>';
		$pdf->writeHTML($tbl, true, false, false, false, '');


		$tbl0 = '<table><tr>
		<td align="center"><h3>' . $this->lang->line('xin_payslip') . '</h3></td>
		</tr></table>';
		$pdf->writeHTML($tbl0, true, false, false, false, '');
		// -----------------------------------------------------------------------------
		// set cell padding
		$pdf->setCellPaddings(1, 1, 1, 1);

		// set cell margins
		$pdf->setCellMargins(0, 0, 0, 0);

		// set color for background
		$pdf->SetFillColor(255, 255, 127);
		// set some text for example
		//$txt = 'Employee Details';
		// Multicell
		//$pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
		//$pdf->Ln(7);
		$tbl1 = '
		<table cellpadding="3" cellspacing="0">
			
			<tr>
				<td>' . $this->lang->line('xin_employee_name') . ':</td>
				<td>' . $fname . '</td>
				
				<td>Payslip ID:</td>
				<td>' . $payment[0]->payslip_id . '</td>
			</tr>
			<tr>
			<td>Employee NRIC/FIN:</td>
				<td>' . $user[0]->id_no . '</td>
			<td>Payslip Month</td>
			<td>' . date('M Y', strtotime('01-' . $payment[0]->salary_month)) . '</td>
			</tr>
			
			<tr>
			<td>Employee DOB:</td>
				<td>' . $user[0]->date_of_birth . '</td>
				<td>Salary Period</td>
			<td>' . date("01-m-Y", strtotime('01-' . $payment[0]->salary_month)) . ' To ' . date("t-m-Y", strtotime('01-' . $payment[0]->salary_month)) . '</td>
			</tr>
			
			<tr>
				
				<td>' . $this->lang->line('left_designation') . '</td>
				<td>' . $_designation_name . '</td>
				<td>Payment Status</td>
			<td>' . ($payment[0]->is_payment == 1 ? 'Paid' : 'Pending') . '</td>
			</tr>
			<tr>
			<td>Reference:</td>
				<td></td>
				<td>Payment Type/Date</td>
			<td>' . ucfirst(str_replace('_', ' ', $payment[0]->payslip_type)) . '/' . $payment[0]->year_to_date . '</td>
			</tr>';
		if ($payment[0]->payslip_type == 'hourly') {
			$hcount = $payment[0]->hours_worked;
			$tbl1 .= '<tr>
				<td>' . $this->lang->line('xin_employee_doj') . '</td>
				<td>' . $date_of_joining . '</td>
				<td>' . $this->lang->line('xin_payroll_hours_worked_total') . '</td>
				<td>' . $hcount . '</td>
			</tr>';
		} else {
			// $date = strtotime($payment[0]->year_to_date);
			$date = strtotime('01-' . $payment[0]->salary_month);
			$day = date('d', $date);
			$month = date('m', $date);
			$year = date('Y', $date);
			// total days in month
			$daysInMonth = cal_days_in_month(0, $month, $year);
			$imonth = date('F', $date);
			$r = $this->Xin_model->read_user_info($user[0]->user_id);
			$pcount = 0;
			$acount = 0;
			$lcount = 0;
			$sun_sat = 0;
			for ($i = 1; $i <= $daysInMonth; $i++) :
				$i = str_pad($i, 2, 0, STR_PAD_LEFT);
				// get date <
				$attendance_date = $i . '-' . $month . '-' . $year;
				$get_day = strtotime($attendance_date);
				$day = date('l', $get_day);
				$user_id = $r[0]->user_id;
				$office_shift_id = $r[0]->office_shift_id;
				$attendance_status = '';
				// get holiday
				$h_date_chck = $this->Timesheet_model->holiday_date_check($attendance_date);
				$holiday_arr = array();
				if ($h_date_chck->num_rows() == 1) {
					$h_date = $this->Timesheet_model->holiday_date($attendance_date);
					$begin = new DateTime($h_date[0]->start_date);
					$end = new DateTime($h_date[0]->end_date);
					$end = $end->modify('+1 day');

					$interval = new DateInterval('P1D');
					$daterange = new DatePeriod($begin, $interval, $end);

					foreach ($daterange as $date) {
						$holiday_arr[] =  $date->format("Y-m-d");
					}
				} else {
					$holiday_arr[] = '99-99-99';
				}
				// get leave/employee
				$leave_date_chck = $this->Timesheet_model->leave_date_check($user_id, $attendance_date);
				$leave_arr = array();
				if ($leave_date_chck->num_rows() == 1) {
					$leave_date = $this->Timesheet_model->leave_date($user_id, $attendance_date);
					$begin1 = new DateTime($leave_date[0]->from_date);
					$end1 = new DateTime($leave_date[0]->to_date);
					$end1 = $end1->modify('+1 day');

					$interval1 = new DateInterval('P1D');
					$daterange1 = new DatePeriod($begin1, $interval1, $end1);

					foreach ($daterange1 as $date1) {
						$leave_arr[] =  $date1->format("d-m-Y");
					}
				} else {
					$leave_arr[] = '99-99-99';
				}
				$office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);
				$check = $this->Timesheet_model->attendance_first_in_check($user_id, $attendance_date);
				// get holiday>events

				if ($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
					$status = 'H';
					$pcount += 0;
					//$acount += 0;
				} else if ($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
					$status = 'H';
					$pcount += 0;
					//$acount += 0;
				} else if ($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
					$status = 'H';
					$pcount += 0;
					//$acount += 0;
				} else if ($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
					$status = 'H';
					$pcount += 0;
					//$acount += 0;
				} else if ($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
					$status = 'H';
					$pcount += 0;
					//$acount += 0;
				} else if ($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
					$status = 'H';
					$pcount += 0;
					$sun_sat += 1;
					//$acount -= 1;
				} else if ($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
					$status = 'H';
					$pcount += 0;
					$sun_sat += 1;
					//$acount -= 1;
				} else if (in_array($attendance_date, $holiday_arr)) { // holiday
					$status = 'H';
					$pcount += 0;
					//$acount += 0;
				} else if (in_array($attendance_date, $leave_arr)) { // on leave
					$status = 'L';
					$pcount += 0;
					$lcount += 1;
					//	$acount += 0;
				} else if ($check->num_rows() > 0) {
					$pcount += 1;
					//$acount -= 1;
				} else {
					$status = 'A';
					//$acount += 1;
					$pcount += 0;
					// set to present date
					$iattendance_date = strtotime($attendance_date);
					$icurrent_date = strtotime(date('Y-m-d'));
					if ($iattendance_date <= $icurrent_date) {
						$acount += 1;
					} else {
						$acount += 0;
					}
				}

				if ($user[0]->attendance_status == 1) {
					$total_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$pcount = $total_day - ($lcount + $sun_sat);
				}
			endfor;
			// echo $pcount;
			// exit;
			$tbl1 .= '<tr>
				<td>' . $this->lang->line('xin_employee_doj') . '</td>
				<td>' . $user[0]->date_of_joining . '</td>
				<td>' . $this->lang->line('xin_payroll_no_of_days_in_month') . '</td>
				<td>' . $daysInMonth . '</td>
			</tr>';
		}
		$tbl1 .= '</table>';

		$pdf->writeHTML($tbl1, true, false, true, false, '');
		if ($payment[0]->payslip_type == 'hourly') {
			$total_earning = $allowances_amount + $commissions_amount + $other_payments_amount + $overtime_amount;
			$total_deductions = $loan_de_amount + $statutory_deduction_amount;
			$total_count = $hcount * $bs;
		} else {
			$total_earning = $bs + $allowances_amount + $commissions_amount + $other_payments_amount + $share_option_amount + $overtime_amount;
			$total_deductions = $loan_de_amount + $statutory_deduction_amount;
		}
		/*<tr>
				<td colspan="2">'.$this->lang->line('xin_payroll_hourly_rate').'</td>
				<td align="center">'.$this->Xin_model->currency_sign($payment[0]->basic_salary).'</td>	
				<td>&nbsp;</td>				
			</tr>*/
		//// break..
		$pdf->Ln(7);

		//allowances
		$total_allowance = 0;
		if ($count_allowances > 0) {
			$tblbrk2 = '';
			foreach ($allowances->result() as $sl_allowances) {
				$tblbrk2 .= '<tr>
					<td colspan="2">' . $sl_allowances->allowance_title . '</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($sl_allowances->allowance_amount) . '</td>			
					</tr>';

				$total_allowance += $sl_allowances->allowance_amount;
			}
		} else {
			$tblbrk2 = '<tr>
					<td colspan="2">Allowance</td>
					<td colspan="2">' . $this->Xin_model->currency_sign(0) . '</td>				
					</tr>';
		}

		//statutory_deductions
		$tblbrk3 = '';
		$tblbrk4 = '';
		$tblbrk5 = '';
		$tblbrk6 = '';
		$total_statutory = 0;
		$total_loan = 0;
		$total_leave_deduction = 0;
		$total_contribution = 0;
		$tblbrk12 = '';

		if ($count_statutory_deductions > 0) {
			foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
				if ($system[0]->statutory_fixed != 'yes') :
					$sta_salary = $bs;
					$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
					$xstatutory_deduction_amount = $st_amount;
				else :
					$xstatutory_deduction_amount = $sl_statutory_deductions->deduction_amount;
				endif;
				$tblbrk3 .= '<tr>
					<td colspan="2">' . $sl_statutory_deductions->deduction_title . '</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($xstatutory_deduction_amount) . '</td>			
					</tr>';
				$total_statutory += $xstatutory_deduction_amount;
			}
		} else {
			$tblbrk3 = '';
		}
		//loan
		if ($count_loan > 0) {
			foreach ($loan->result() as $r_loan) {
				$tblbrk4 .= '<tr>
					<td colspan="2">' . $r_loan->loan_title . '</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($r_loan->loan_amount) . '</td>	
					</tr>';
			}
			$total_loan += $r_loan->loan_amount;
		} else {
			$tblbrk4 = '';
		}
		//Leave Deduction
		if ($leave_deductions) {
			// $total_leave_deduction = round($leave_deductions[0]->total_leave_amount, 2);
			foreach ($leave_deductions as $ld) {
				if ($ld->is_half == 0) {
					$lday = "Full Day";
				} else {
					$lday = "Half Day";
				}
				$tblbrk5 .= '<tr>
					<td colspan="2">No Pay (Unpaid Leave) (' . date('d M Y', strtotime($ld->leave_date)) . ') (' . $lday . ')</td>
					<td>&nbsp;</td>
					<td align="center">' . $this->Xin_model->currency_sign($ld->leave_amount) . '</td>	
					</tr>';
				$total_leave_deduction += $ld->leave_amount;
			}
		} else {
			$tblbrk5 = '';
		}
		//Contribution funds
		$contribution = $this->Contribution_fund_model->getContributionPayslip($payment[0]->payslip_id);

		if ($contribution) {
			$contribution_fund = array();
			foreach ($contribution as $i => $c) {
				//if($c->contribution_id != 5) {
				$tblbrk12 .= '<tr>
						<td colspan="2">' . $c->contribution . '</td>
						<td colspan="2">' . $this->Xin_model->currency_sign($c->contribution_amount) . '</td>	
						</tr>';
				//}
				$total_contribution += $c->contribution_amount;
			}
			// $data['contribution_fund'] = $contribution_fund;
		} else {
			$tblbrk12 = '';
		}
		if ($cpf_employee > 0) {

			$tblbrk6 .= '<tr>
					<td colspan="2">CPF Employee</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($cpf_employee) . '</td>	
					</tr>';
		} else {
			$cpf_employee = 0;
			$tblbrk6 = '';
		}

		$total_deduction = $total_statutory  + $total_loan + $total_leave_deduction + $cpf_employee + $total_contribution;
		// $tblbrk .= '<tr>
		// <td colspan="2">Total Deduction (C) </td>
		// <td colspan="2">'.$this->Xin_model->currency_sign($total_deduction).'</td>				
		// </tr>';

		//$tblbrk .= $tblbrk3 . $tblbrk4 . $tblbrk5 . $tblbrk6 .$tblbrk12;
		// $tblbrk .= '<tr>
		// 	<td colspan="4"></td>

		// 	</tr>';
		//overtime
		$tblbrk7 = '';
		$total_overtime = 0;

		if ($count_overtime > 0) {

			foreach ($overtime->result() as $r_overtime) {
				$overtime_total = (float)$r_overtime->overtime_hours * (float)$r_overtime->overtime_rate;
				$tblbrk7 .= '<tr>
					<td colspan="2">Overtime (' . $r_overtime->overtime_hours . 'hrs x ' . $this->Xin_model->currency_sign($r_overtime->overtime_rate) . ')</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($overtime_total) . '</td>			
					</tr>';
				$total_overtime += $overtime_total;
			}
			// $tblbrk .= '<tr>
			// 	<td colspan="2">Total Overtime Pay (D)</td>
			// 	<td colspan="2">'.$this->Xin_model->currency_sign($total_overtime).'</td>	

			// 	</tr>';

			//	$tblbrk .= $tblbrk7;
		} else {
			// $tblbrk .= '<tr>
			// <td colspan="2">Total Overtime Pay (D)</td>
			// <td colspan="2">'.$this->Xin_model->currency_sign($total_overtime).'</td>			
			// </tr>';
		}

		// $tblbrk .= '<tr>
		// <td colspan="4"></td>		
		// </tr>';

		//other_payments
		$tblbrk8 = '';
		$tblbrk9 = '';
		$tblbrk10 = '';
		$tblbrk11 = '';

		$total_other_payment = 0;
		$total_commission = 0;
		$total_other = 0;
		$cpf_employer = 0;
		$share_option_amount = 0;

		if ($count_other_payments > 0) {
			foreach ($other_payments->result() as $sl_other_payments) {
				$tblbrk8 .= '<tr>
					<td colspan="2">' . $sl_other_payments->payments_title . '</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($sl_other_payments->payments_amount) . '</td>				
					</tr>';
				$total_other_payment += $sl_other_payments->payments_amount;
			}
		} else {
			$total_other_payment = 0;
			$tblbrk8 = '';
		}
		//commissions
		if ($count_commissions > 0) {
			foreach ($commissions->result() as $sl_commissions) {
				$tblbrk9 .= '<tr>
					<td colspan="2">Commission</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($sl_commissions->commission_amount) . '</td>					
					</tr>';
				$total_commission += $sl_commissions->commission_amount;
			}
		} else {
			$total_commission = 0;
			$tblbrk9 = '';
		}
		//share options
		if ($share_option_amount > 0) {

			$tblbrk10 .= '<tr>
					<td colspan="2">Share Option Sale</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($share_option_amount) . '</td>				
					</tr>';
		} else {
			$share_option_amount = 0;
			$tblbrk10 = '';
		}

		//cpf
		if ($cpf_employer > 0) {
			$tblbrk11 .= '<tr>
					<td colspan="2">CPF Employer</td>
					<td colspan="2">' . $this->Xin_model->currency_sign($cpf_employer) . '</td>		
					</tr>';
		} else {
			$cpf_employer = 0;
			$tblbrk11 = '';
		}
		$total_other = $total_other_payment + $total_commission + $share_option_amount + $cpf_employer;
		// $tblbrk .= '<tr>
		// <td colspan="2">Other Additional Payments (E)</td>
		// <td colspan="2">'.$this->Xin_model->currency_sign($total_other).'</td>	
		// </tr>';
		// $tblbrk .= $tblbrk8 . $tblbrk9.$tblbrk10 .$tblbrk11;

		// $tblbrk .= '<tr>
		// <td colspan="4"></td>
		// </tr>';

		if ($payment[0]->payslip_type == 'hourly') {

			$total_net_salary = $total_count + $total_allowance - $total_deduction + $total_overtime + $total_other;
			$tblbrk = '
				
					<tr><td colspan="2" align="center"><strong>NET PAY(A+B-C+D+E)</strong></td>
					<td colspan="2" align="center"><strong>' . $this->Xin_model->currency_sign($fsalary) . '</strong></td>
					</tr></table>';
		} else {


			$total_net_salary = $bs + $total_allowance - $total_deduction + $total_overtime + $total_other;
			//cpf
			//$total_net_salary = $total_net_salary - $cpf_employee;
			//contribution
			if ($contribution) {
				$contribution_amount = 0;
				foreach ($contribution as $c) {
					if ($c->contribution_id != 5) {
						$contribution_amount += $c->contribution_amount;
					}
				}
			}



			// 	$tblbrk .= '<tr><td colspan="2">NET Salary Pay(A+B-C+D+E)</td>
			// 	<td colspan="2"><strong>'.$this->Xin_model->currency_sign($total_net_salary).'</strong></td>
			// 	</tr>';


			// 	$tblbrk .= '<tr>
			// <td colspan="4"></td>
			// </tr>';

			// $tblbrk .= '<tr>
			// <td colspan="4">Payment Information</td>
			// </tr>';
			// $tblbrk .= '<tr>
			// <td colspan="2">Date of Payment </td>
			// <td colspan="2">'.$payment[0]->year_to_date.'</td>	

			// </tr>';
			$bank_detail = $this->Employees_model->set_employee_bank_account($payment[0]->employee_id);
			$employee_bank_detail = $bank_detail->result();
			// if($bank_detail->num_rows() > 0){
			// $tblbrk .= '<tr>
			// <td colspan="2">Bank Transfer  </td>
			// <td colspan="2">'.$employee_bank_detail[0]->account_number.'('.$employee_bank_detail[0]->bank_name.')</td>	
			// </tr>';
			// $tblbrk .= '<tr>
			// <td colspan="4"></td>
			// </tr>';
			// }else{
			// 	$tblbrk .= '<tr>
			// 	<td colspan="2">Bank Transfer  </td>
			// 	<td colspan="2"></td>	
			// 	</tr>';
			// 	$tblbrk .= '<tr>
			// 	<td colspan="4"></td>
			// 	</tr>';
			// }
			$leave_detail = $this->Employees_model->getEmployeeMonthlyLeaves($payment[0]->employee_id, date('Y-m-d', strtotime($payment[0]->year_to_date)));

			$total_leave_detail = $this->Employees_model->getTotalEmployeeLeaves($payment[0]->employee_id, date('Y-m-d', strtotime($payment[0]->year_to_date)))->result();

			$leaves = $leave_detail->result();

			if ($leave_detail->num_rows() > 0) {
				$tblbrk = '<tr><td colspan="2">Leave Used</td>
				<td colspan="2">Days/Date</td>
			
				</tr>';
				$total_no_leave = 0;
				if ($total_leave_detail) {
					foreach ($total_leave_detail as $leave) {
						foreach ($leaves as $monthly_leave) {
							$total_no_leave += $monthly_leave->no_of_leaves;
							// if($leave->type_name == $monthly_leave->type_name){
							// $tblbrk .= '<tr><td colspan="2">'.$leave->type_name.'</td>
							// 	<td colspan="2" >'.$monthly_leave->no_of_leaves.'/'.$leave->no_of_leaves.'</td>

							// 	</tr>';

							// }
						}
					}
				}
			}
			// else{
			// 	$tblbrk .= '<tr><td colspan="2">Leave Used</td>
			// 	<td colspan="2">Days/Date</td>

			// 	</tr>';

			// 		$tblbrk .= '<tr><td colspan="2"></td>
			// 			<td colspan="2"></td>

			// 			</tr>';


			// }

			// 	$tblbrk .= '</table>';
			//  }
			$mapping_data = $this->Payroll_model->get_mapping_data($payment[0]->payslip_id);
			//echo "<pre>";print_r($mapping_data);exit;
			$tblbrk = '<table cellpadding="3" cellspacing="0" border="1" rules="none"><tr>
				<td colspan="3" align="center"><strong>Income</strong></td>
				<td colspan="3" align="center"><strong>Deductions</strong></td>	
							
			</tr>';
			$claim_info = $this->Employees_model->read_employee_claim_information($payment[0]->employee_id);

			$claim_amount = 0;
			if ($claim_info) {
				foreach ($claim_info as $claim) {
					$claim_amount += $claim->amount;
				}
			}
			$d_amount = 0;

			foreach ($mapping_data as $m) {
				if ($m->deduction_name != "") {
					$d_amount += $m->deduction_amount;
				}
			}
			$contribution_data = $this->Employees_model->set_employee_contribution($payment[0]->employee_id);
			// 	if(count($contribution_data) > 0){
			// 		$b=1;

			// 	foreach($contribution_data as $c_data){
			// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($bs, $c_data->contribution_id);
			// 	$d_amount += $contribution_amount;

			//  }

			//			 }

			if ($payment[0]->payslip_type != 'hourly') {
				$tblbrk .= '<tr>
					<td colspan="2"  style="border-left-style: hidden;">Basic Rate(Monthly)</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($bs) . '</td>	
					<td colspan="2"  style="border-left-style: hidden;">Total Deductions</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($d_amount) . '</td>

				</tr>';
				foreach ($mapping_data as $m) {
					if ($m->deduction_name != "") {
						$tblbrk .= '<tr>
								<td colspan="2" style="border-left-style: hidden;"></td>
								<td style="border-right-style: hidden;"></td>
								<td colspan="2"  style="border-left-style: hidden;">&nbsp;&nbsp;&nbsp;-' . $m->deduction_name . '</td>
								<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($m->deduction_amount) .
							'</td></tr>';
					}
				}
				// if(count($contribution_data) > 0) {
				// 	$contribution_fund = array();
				// 	foreach($contribution_data as $c_data) {
				// 		$contribution_amount = $this->Contribution_fund_model->getContributionRate($bs, $c_data->contribution_id);
				// 		//if($c->contribution_id != 5) {
				// 			$tblbrk .= '<tr>
				// 			<td colspan="2" style="border-left-style: hidden;"></td>
				// 			<td style="border-right-style: hidden;"></td>
				// 			<td colspan="2" style="border-left-style: hidden;">'.$c_data->contribution.'</td>
				// 			<td colspan="2" style="border-right-style: hidden;">'.$this->Xin_model->currency_sign($contribution_amount).'</td>	
				// 			</tr>';
				// 		//}


				// 	}
				// }
				$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;">' . $this->lang->line('xin_payroll_basic_salary') . '(A)</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($bs) . '</td>	
					<td colspan="2"  style="border-left-style: hidden;">Employee CPF</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->cpf_employee_amount) . '</td>		
				</tr>';
			} else {
				$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;">Basic Rate</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($total_count) . '</td>	
					<td colspan="2"  style="border-left-style: hidden;">Total Deductions</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->total_statutory_deductions) . '</td>		
				</tr>';

				foreach ($mapping_data as $m) {
					if ($m->deduction_name != "") {
						$tblbrk .= '<tr>
								<td colspan="2" style="border-left-style: hidden;"></td>
								<td style="border-right-style: hidden;"></td>
								<td colspan="2"  style="border-left-style: hidden;">&nbsp;&nbsp;&nbsp;-' . $m->deduction_name . '</td>
								<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($m->deduction_amount) .
							'</td></tr>';
					}
				}
				$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;">' . $this->lang->line('xin_payroll_hourly_rate') . ' x ' . $this->lang->line('xin_payroll_hours_worked_total') . '(A)<br> ' . $this->Xin_model->currency_sign($bs) . ' x ' . $hcount . '</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($total_count) . '</td>	
					<td colspan="2"  style="border-left-style: hidden;">Employee CPF</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->cpf_employee_amount) . '</td>			
				</tr>';
			}

			$tblbrk .= '<tr>
			<td colspan="2" style="border-left-style: hidden;">Total Allowance(B)</td>
			<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->total_allowances) . '</td>	
			<td colspan="2"  style="border-left-style: hidden;">Unpaid Leave</td>
			<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->leave_deduction) . '</td>		
		</tr>';
			foreach ($mapping_data as $m) {
				if ($m->allowance_name != "") {
					$tblbrk .= '<tr><td colspan="2"  style="border-left-style: hidden;">&nbsp;&nbsp;&nbsp;+' . $m->allowance_name . '</td>
						<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($m->allowance_amount) . '</td></tr>';
				}
			}
			// if($allowances->result() > 0){
			// foreach($allowances->result() as $sl_allowances) {
			// 	$tblbrk .= '<tr>
			// 		<td colspan="2" style="border-left-style: hidden;">'.$sl_allowances->allowance_title.'</td>
			// 		<td style="border-right-style: hidden;">'.$this->Xin_model->currency_sign($sl_allowances->allowance_amount).'</td>	
			// 		<td colspan="2" style="border-left-style: hidden;"></td>
			// 		<td style="border-right-style: hidden;"></td>	
			// 		</tr>';

			// 		$total_allowance +=$sl_allowances->allowance_amount;
			// 	}
			// }
			$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;"> Additional Allowances(E)</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->additonal_allowance) . '</td>	
					<td colspan="2"  style="border-left-style: hidden;">Loan</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->total_loan) . '</td>
					</tr>';
			$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;">Total Additional(C)</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign((float)$payment[0]->total_other_payments + (float)$payment[0]->total_commissions + (float)$payment[0]->share_option_amount) . '</td>	
					<td colspan="2"  style="border-left-style: hidden;"></td>
					<td style="border-right-style: hidden;"></td>	
					</tr>';
			$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;">Total Overtime Pay (D)</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->total_overtime) . '</td>	
							
					</tr>';
			$tblbrk .= '<tr>
					<td colspan="2" style="border-left-style: hidden;">Total Claim(I)</td>
					<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->claim_amount) . '</td>	
							
					</tr>';
			// $income=$bs+$total_allowance+$total_other_payment+ $total_commission+$share_option_amount+$total_overtime+$claim_amount;
			// $deduction= $total_deduction + $cpf_employee+$total_loan+$total_leave_deduction;
			$income = (float)$bs + (float)$payment[0]->total_allowances + (float)$payment[0]->total_other_payments + (float) $payment[0]->total_commissions + (float)$payment[0]->share_option_amount + (float)$payment[0]->total_overtime + (float)$payment[0]->claim_amount;
			//$deduction= (float)$payment[0]->deduction_amount + (float)$payment[0]->total_statutory_deductions	 +(float) $payment[0]->cpf_employee_amount+(float)$payment[0]->total_loan+(float)$payment[0]->leave_deduction;
			$deduction = (float)$d_amount + (float) $payment[0]->cpf_employee_amount + (float)$payment[0]->leave_deduction + (float)$payment[0]->total_loan;

			$tblbrk .= '<tr>
					<td colspan="2" style="border-top-style: hidden;"><strong>Gross Pay</strong></td>
					<td style="border-right-style: hidden;border-top-style: hidden;"><strong>' . $this->Xin_model->currency_sign($income) . '</strong></td>	
					<td colspan="2" style="border-top-style: hidden;"><strong>Total Deduction(F)</strong></td>
					<td style="border-right-style: hidden;border-top-style: hidden;"><strong>' . $this->Xin_model->currency_sign($deduction) . '</strong></td>	
				</tr>';
			$tblbrk .= '</table>';




			$pdf->writeHTML($tblbrk, true, false, true, false, '');
			$net_pay = $income - $deduction;
			$tblbrk1 = '<table cellpadding="3" cellspacing="0" border="1"><tr>
		<td colspan="2" style="border-left-style: dden;">Employer CPF</td>
		<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($payment[0]->cpf_employer_amount) . '</td>	
		<td colspan="2"  style="border-left-style: hidden;">Days Worked</td>
		<td style="border-right-style: hidden;">' . $pcount . '</td>	
		</tr>';
			// 	$tblbrk1 .= '<tr>
			// <td colspan="2" style="border-left-style: hidden;">Gross Pay(A+B+C+D+E+I)</td>
			// <td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($income) . '</td>	
			// <td colspan="2"  style="border-left-style: hidden;">Days Off</td>
			// <td style="border-right-style: hidden;">' . $lcount . '</td>	
			// </tr>';

			$get_std = $this->Payroll_model->read_sdl_payslip($payment[0]->payslip_id);

			$tblbrk1 .= '<tr>
		<td colspan="2" style="border-left-style: hidden;">SDL</td>
		<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($get_std[0]->contribution_amount) . '</td>	
		<td colspan="2"  style="border-left-style: hidden;">Days Off</td>
		<td style="border-right-style: hidden;">' . $lcount . '</td>	
		</tr>';

			$tblbrk1 .= '<tr>
		<td colspan="2" style="border-left-style: hidden;">NET Pay (A+B+C+D+E+I-F)</td>
		<td style="border-right-style: hidden;">' . $this->Xin_model->currency_sign($net_pay) . '</td>	
		<td colspan="2"  style="border-left-style: hidden;">Remark:</td>
		<td style="border-right-style: hidden;"></td>	
		</tr>';
			$tblbrk1 .= '</table>';
			$pdf->writeHTML($tblbrk1, true, false, true, false, '');

			// for balance leave
			$dateString = $payment[0]->salary_month;
			list($month, $year) = explode('-', $dateString);
			// echo $year;
			$emp_leaves = $this->Employees_model->getEmployeeLeavesForReport($payment[0]->employee_id, $year);
			$tblbrk2 = '<table border="1" cellspacing="0" cellpadding="3">';
			$tblbrk2 .= '<thead>
			<tr>
				<td align="center"><strong>Leave</strong></td> 
				<td align="center"><strong>Entitle</strong></td>
				<td align="center"><strong>Taken</strong></td>
				<td align="center"><strong>B/F</strong></td>
				<td align="center"><strong>Available</strong></td>
				<td align="center"><strong>Bal</strong></td>
			</tr>
			</thead>';
			foreach ($emp_leaves->result() as $e_leave) {
				$remaining_leave = 0;
				$previous_year = $this->Employees_model->getEmployeeLeaveCountForLeave($e_leave->leave_type_id, date('Y') - 1, $e_leave->employee_id);
				$current_year = $this->Employees_model->getEmployeeLeaveCountForLeave($e_leave->leave_type_id, date('Y'), $e_leave->employee_id);
				$setting = $this->Xin_model->read_setting_info(1);
				if ($setting[0]->module_prorated_leave == 'yes' && $e_leave->leave_type_id == 22) {

					$get_join_year = new DateTime($user[0]->date_of_joining);
					$join_year = $get_join_year->format('Y');
					if ($join_year < date('Y')) {
						// Total Leave/Days per year * (1st January until that day for example 19April is 109 days)

						$januaryFirst = new DateTime('first day of January this year');
						$januaryFirstFormatted = $januaryFirst->format('Y-m-d');

						$today = new DateTime();

						// Calculate the difference between today and the last day of December
						$interval = $januaryFirst->diff($today);

						// Get the number of days as an integer
						$daysRemaining = $interval->days;
						// $annual_leave_taken = ($remaining_leave/365) * $daysRemaining;
						$annual_leave_taken = ($current_year->no_of_leaves / 365) * $daysRemaining;
					} else {

						$join_date = new DateTime($user[0]->date_of_joining);
						$today = new DateTime();
						// Calculate the difference between join date and today
						$interval = $join_date->diff($today);

						// Get the number of days as an integer
						$daysRemaining = $interval->days;
						// $annual_leave_taken = ($remaining_leave/365) * $daysRemaining;
						$annual_leave_taken = ($current_year->no_of_leaves / 365) * $daysRemaining;
					}
					$annual_leave_taken = ceil($annual_leave_taken);
					$remaining_leave = $annual_leave_taken + (isset($previous_year->remain_leave) && $previous_year->remain_leave != null ? $previous_year->remain_leave : 0);
				} else {
					$type = $this->Timesheet_model->getEmployeeLeaveCount($e_leave->leave_type_id, $e_leave->employee_id);
					$leave_remaining_total =  $type->balance_leave;

					if ($leave_remaining_total > 0) {
						$remaining_leave = $leave_remaining_total;
					} else {
						$remaining_leave = 0;
					}
				}


				if ($remaining_leave > $current_year->balance_leave + (isset($previous_year->remain_leave) && $previous_year->remain_leave != null ? $previous_year->remain_leave : 0)) {
					$remaining_leave = $current_year->balance_leave;
				}


				$leaves_taken = $this->get_employee_leave_taken($payment[0]->salary_month, $payment[0]->employee_id, $e_leave->leave_type_id);

				$tblbrk2 .= '<tr>
						<td>' . $e_leave->type_name . '</td>
						<td>' . $e_leave->balance_leave_check . '</td>
						<td>' . $leaves_taken . '</td>
						<td>' . $e_leave->carried_leave . '</td>
						<td>' . $remaining_leave . '</td>
						<td>' . $e_leave->balance_leave . '</td>
				</tr>';
			}
			$tblbrk2 .= '</table>';
			$pdf->writeHTML($tblbrk2, true, false, true, false, '');
			// echo $tblbrk2;
			// exit;
			// end balance leave



			////////////////// end break salary..
			/*$pdf->Ln(7);
		$tblc = '<table cellpadding="3" cellspacing="0" border="1"><tr>
				<td colspan="2">'.$this->lang->line('xin_payroll_total_earning').'</td>
				<td colspan="2">'.$this->lang->line('xin_payroll_total_deductions').'</td>				
			</tr>
			<tr>
				<td colspan="2">'.$this->Xin_model->currency_sign($total_earning).'</td>
				<td colspan="2">'.$this->Xin_model->currency_sign($total_deductions).'</td>				
			</tr>
			</table>';
		$pdf->writeHTML($tblc, true, false, true, false, '');*/

			/*if(null!=$this->uri->segment(4) && $this->uri->segment(4)=='p') {
		// -----------------------------------------------------------------------------		
		$tbl2 = '';
		// -----------------------------------------------------------------------------
		$txt = 'Payslip Details';

		// Multicell test
		$pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
		$pdf->Ln(7);
		$tbl2 .= '
		<table cellpadding="3" cellspacing="0" border="1">';
			 if($payment[0]->wages_type == 1){
			$tbl2 .= ' <tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_basic_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->basic_salary).'</td>
			</tr>';
			} else {
				$tbl2 .= ' <tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_employee_daily_wages').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->daily_wages).'</td>
			</tr>';
			}
			if($payment[0]->total_allowances!=0 || $payment[0]->total_allowances!=''):
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_total_allowance').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_allowances).'</td>
			</tr>';
			endif;
			if($payment[0]->total_commissions!=0 || $payment[0]->total_commissions!=''):
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_hr_commissions').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_commissions).'</td>
			</tr>';
			endif;
			if($payment[0]->total_loan!=0 || $payment[0]->total_loan!=''):
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_total_loan').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_loan).'</td>
			</tr>';
			endif;
			if($payment[0]->total_overtime!=0 || $payment[0]->total_overtime!=''):
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_total_overtime').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_overtime).'</td>
			</tr>';
			endif;
			if($payment[0]->total_statutory_deductions!=0 || $payment[0]->total_statutory_deductions!=''):
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_employee_set_statutory_deductions').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_statutory_deductions).'</td>
			</tr>';
			endif;
			if($payment[0]->total_other_payments!=0 || $payment[0]->total_other_payments!=''):
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_employee_set_other_payment').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_other_payments).'</td>
			</tr>';
			endif;
			
			$total_earning = $bs + $allowances_amount + $overtime_amount + $commissions_amount + $other_payments_amount;
			$total_deduction = $loan_de_amount + $statutory_deduction_amount;
			$total_net_salary = $total_earning - $total_deduction;
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_net_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign(number_format($total_net_salary, 2, '.', ',')).'</td>
			</tr>
		</table>
		';
		
		$pdf->writeHTML($tbl2, true, false, false, false, '');
		}*/
			// $tbl = '
			// <table cellpadding="5" cellspacing="0" border="0">
			// 	<tr>
			// 		<td align="right" colspan="1">This is a computer generated slip and does not require signature.</td>
			// 	</tr>
			// </table>';
			// $pdf->writeHTML($tbl, true, false, false, false, '');

			// ---------------------------------------------------------

			// Close and output PDF document
			// This method has several options, check the source code documentation for more information.
			$fname = strtolower($fname);
			// $pay_month = strtolower(date("F Y", strtotime($payment[0]->year_to_date)));
			$pay_month = strtolower(date("F Y", strtotime("01-" . $payment[0]->salary_month)));
			//Close and output PDF document
			ob_start();
			$pdf->Output('payslip_' . $fname . '_' . $pay_month . '.pdf', 'I');
			ob_end_flush();
		}
	}

	public function get_employee_leave_taken($month, $employee_id, $leave_type_id)
	{
		$leaves = $this->Timesheet_model->getEmployeeLeave($employee_id);
		$user = $this->Xin_model->read_user_info($employee_id);
		$office_shift = $this->Timesheet_model->read_office_shift_information($user[0]->office_shift_id);

		$dateString = $month;
		$date = DateTime::createFromFormat('m-Y', $dateString);
		$monthNumber = $date->format('m');
		// echo $monthNumber."<br>";
		// foreach($leaves as $l) {
		$applied_leaves = $this->Timesheet_model->getEmployeeAppliedLeaveCountForReport($leave_type_id, $monthNumber, $employee_id);

		$leaves_taken = 0;
		if ($applied_leaves) {
			foreach ($applied_leaves as $l) {
				$start_date = new DateTime($l->from_date);
				$end_date = new DateTime($l->to_date);
				$end_date->modify('+1 day');
				// $diff = $end_date->diff($start_date)->format("%a");
				// $l->leave_count = $diff;
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($start_date, $interval, $end_date);
				$leave_period = array();
				foreach ($period as $d) {
					$p_day = $d->format('l');
					if ($p_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					} else if ($p_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					} else if ($p_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					} else if ($p_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					} else if ($p_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					} else if ($p_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					} else if ($p_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$leave_period[] = $d->format('Y-m-d');
						}
					}
				}

				if (count($leave_period) > 0) {
					if ($l->is_half_day == 0) {
						$leaves_taken += count($leave_period);
					} else {
						$leaves_taken += count($leave_period)  / 2;
					}
				}
			}
		}
		// }

		return $leaves_taken;
	}


	public function pdf_createv2()
	{

		//$this->load->library('Pdf');
		$system = $this->Xin_model->read_setting_info(1);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$id = $this->uri->segment(5);
		$payment = $this->Payroll_model->read_salary_payslip_info($id);
		$user = $this->Xin_model->read_user_info($payment[0]->employee_id);

		// if password generate option enable
		if ($system[0]->is_payslip_password_generate == 1) {
			/**
			 * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
			 * to provide password as selected format in settings module.
			 */
			if ($system[0]->payslip_password_format == 'dateofbirth') {
				$password_val = date("dmY", strtotime($user[0]->date_of_birth));
			} else if ($system[0]->payslip_password_format == 'contact_no') {
				$password_val = $user[0]->contact_no;
			} else if ($system[0]->payslip_password_format == 'full_name') {
				$password_val = $user[0]->first_name . $user[0]->last_name;
			} else if ($system[0]->payslip_password_format == 'email') {
				$password_val = $user[0]->email;
			} else if ($system[0]->payslip_password_format == 'password') {
				$password_val = $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'user_password') {
				$password_val = $user[0]->username . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'employee_id') {
				$password_val = $user[0]->employee_id;
			} else if ($system[0]->payslip_password_format == 'employee_id_password') {
				$password_val = $user[0]->employee_id . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
				$dob = date("dmY", strtotime($user[0]->date_of_birth));
				$fname = $user[0]->first_name;
				$lname = $user[0]->last_name;
				$password_val = $dob . $fname[0] . $lname[0];
			}
			$pdf->SetProtection(array('print', 'copy', 'modify'), $password_val, $password_val, 0, null);
		}


		$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($_des_name)) {
			$_designation_name = $_des_name[0]->designation_name;
		} else {
			$_designation_name = '';
		}
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$_department_name = $department[0]->department_name;
		} else {
			$_department_name = '';
		}
		//$location = $this->Xin_model->read_location_info($department[0]->location_id);
		// company info
		$company = $this->Xin_model->read_company_info($user[0]->company_id);


		$p_method = '';
		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '--';
			}
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} else {
			$company_name = '--';
			$address_1 = '--';
			$address_2 = '--';
			$city = '--';
			$state = '--';
			$zipcode = '--';
			$country_name = '--';
			$c_info_email = '--';
			$c_info_phone = '--';
		}
		//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		// set default header data
		$c_info_address = $address_1 . ' ' . $address_2 . ', ' . $city . ' - ' . $zipcode . ', ' . $country_name;
		$email_phone_address = "" . $this->lang->line('dashboard_email') . " : $c_info_email | " . $this->lang->line('xin_phone') . " : $c_info_phone \n" . $this->lang->line('xin_address') . ": $c_info_address";
		$header_string = $email_phone_address;
		// set document information
		$pdf->SetCreator('HRSALE');
		$pdf->SetAuthor('HRSALE');
		//$pdf->SetTitle('Workable-Zone - Payslip');
		//$pdf->SetSubject('TCPDF Tutorial');
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		$pdf->SetHeaderData('../../../uploads/logo/payroll/' . $system[0]->payroll_logo, 40, $company_name, $header_string);

		$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

		// set header and footer fonts
		$pdf->setHeaderFont(array('helvetica', '', 11.5));
		$pdf->setFooterFont(array('helvetica', '', 9));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont('courier');

		// set margins
		$pdf->SetMargins(15, 27, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 25);

		// set image scale factor
		$pdf->setImageScale(1.25);
		$pdf->SetAuthor('HRSALE');
		$pdf->SetTitle($company_name . ' - ' . $this->lang->line('xin_print_payslip'));
		$pdf->SetSubject($this->lang->line('xin_payslip'));
		$pdf->SetKeywords($this->lang->line('xin_payslip'));
		// set font
		$pdf->SetFont('helvetica', 'B', 10);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 10, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		// -----------------------------------------------------------------------------
		$fname = $user[0]->first_name . ' ' . $user[0]->last_name;
		$created_at = $this->Xin_model->set_date_format($payment[0]->created_at);
		$date_of_joining = $this->Xin_model->set_date_format($user[0]->date_of_joining);
		$salary_month = $this->Xin_model->set_date_format($payment[0]->salary_month);
		// basic salary
		$bs = 0;
		if ($payment[0]->basic_salary != '') {
			$bs = $payment[0]->basic_salary;
		} else {
			$bs = $payment[0]->daily_wages;
		}
		// allowances
		$count_allowances = $this->Employees_model->count_employee_allowances_payslip($payment[0]->payslip_id);
		$allowances = $this->Employees_model->set_employee_allowances_payslip($payment[0]->payslip_id);
		// commissions
		$count_commissions = $this->Employees_model->count_employee_commissions_payslip($payment[0]->payslip_id);
		$commissions = $this->Employees_model->set_employee_commissions_payslip($payment[0]->payslip_id);
		// otherpayments
		$count_other_payments = $this->Employees_model->count_employee_other_payments_payslip($payment[0]->payslip_id);
		$other_payments = $this->Employees_model->set_employee_other_payments_payslip($payment[0]->payslip_id);
		// statutory_deductions
		$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions_payslip($payment[0]->payslip_id);
		$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions_payslip($payment[0]->payslip_id);
		// overtime
		$count_overtime = $this->Employees_model->count_employee_overtime_payslip($payment[0]->payslip_id);
		$overtime = $this->Employees_model->set_employee_overtime_payslip($payment[0]->payslip_id);
		// loan
		$count_loan = $this->Employees_model->count_employee_deductions_payslip($payment[0]->payslip_id);
		$loan = $this->Employees_model->set_employee_deductions_payslip($payment[0]->payslip_id);
		//
		$statutory_deduction_amount = 0;
		$loan_de_amount = 0;
		$allowances_amount = 0;
		$commissions_amount = 0;
		$other_payments_amount = 0;
		$overtime_amount = 0;
		// laon
		if ($count_loan > 0) :
			foreach ($loan->result() as $r_loan) {
				$loan_de_amount += $r_loan->loan_amount;
			}
			$loan_de_amount = $loan_de_amount;
		else :
			$loan_de_amount = 0;
		endif;
		// allowances
		$allowances_amount = 0;
		foreach ($allowances->result() as $sl_allowances) {
			$allowances_amount += $sl_allowances->allowance_amount;
		}
		// commission
		$commissions_amount = 0;
		foreach ($commissions->result() as $sl_commissions) {
			$commissions_amount += $sl_commissions->commission_amount;
		}
		// statutory deduction
		$statutory_deduction_amount = 0;
		foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
			//$statutory_deduction_amount += $sl_statutory_deductions->deduction_amount;
			if ($system[0]->statutory_fixed != 'yes') :
				$sta_salary = $bs;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				$statutory_deduction_amount += $st_amount;
			else :
				$statutory_deduction_amount += $sl_statutory_deductions->deduction_amount;
			endif;
		}
		// other amount
		$other_payments_amount = 0;
		foreach ($other_payments->result() as $sl_other_payments) {
			$other_payments_amount += $sl_other_payments->payments_amount;
		}
		// overtime
		$overtime_amount = 0;
		foreach ($overtime->result() as $r_overtime) {
			$overtime_total = $r_overtime->overtime_hours * $r_overtime->overtime_rate;
			$overtime_amount += $overtime_total;
		}
		$tbl = '<br><br>
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="center"><h1>' . $this->lang->line('xin_payslip') . '</h1></td>
			</tr>
			<tr>
				<td align="center"><strong>' . $this->lang->line('xin_payslip_number') . ':</strong> #' . $payment[0]->payslip_id . '</td>
			</tr>
			<tr>
				<td align="center"><strong>' . $this->lang->line('xin_salary_month') . ':</strong> ' . date("F Y", strtotime($payment[0]->year_to_date)) . '</td>
			</tr>
		</table>
		';
		$pdf->writeHTML($tbl, true, false, false, false, '');
		// -----------------------------------------------------------------------------
		// set cell padding
		$pdf->setCellPaddings(1, 1, 1, 1);

		// set cell margins
		$pdf->setCellMargins(0, 0, 0, 0);

		// set color for background
		$pdf->SetFillColor(255, 255, 127);
		// set some text for example
		$txt = 'Employee Details';
		// Multicell
		$pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
		$pdf->Ln(7);
		$tbl1 = '
		<table cellpadding="3" cellspacing="0" border="1">
			<tr>
				<td>' . $this->lang->line('xin_name') . '</td>
				<td>' . $fname . '</td>
				<td>' . $this->lang->line('dashboard_employee_id') . '</td>
				<td>' . $user[0]->employee_id . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('left_department') . '</td>
				<td>' . $_department_name . '</td>
				<td>' . $this->lang->line('left_designation') . '</td>
				<td>' . $_designation_name . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_e_details_date') . '</td>
				<td>' . date("d F, Y") . '</td>
				<td>' . $this->lang->line('xin_payslip_number') . '</td>
				<td>' . $payment[0]->payslip_id . '</td>
			</tr>
		</table>
		';

		$pdf->writeHTML($tbl1, true, false, true, false, '');

		$total_earning = $bs + $allowances_amount + $commissions_amount + $other_payments_amount + $overtime_amount;
		$total_deductions = $loan_de_amount + $statutory_deduction_amount;
		$pdf->Ln(7);
		$tblc = '<table cellpadding="3" cellspacing="0" border="1"><tr>
				<td colspan="2">' . $this->lang->line('xin_payroll_total_earning') . '</td>
				<td colspan="2">' . $this->lang->line('xin_payroll_total_deductions') . '</td>				
			</tr>
			<tr>
				<td colspan="2">' . $this->Xin_model->currency_sign($total_earning) . '</td>
				<td colspan="2">' . $this->Xin_model->currency_sign($total_deductions) . '</td>				
			</tr>
			</table>';
		$pdf->writeHTML($tblc, true, false, true, false, '');

		if (null != $this->uri->segment(4) && $this->uri->segment(4) == 'p') {
			// -----------------------------------------------------------------------------		
			$tbl2 = '';
			// -----------------------------------------------------------------------------
			$txt = 'Payslip Details';

			// Multicell test
			$pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
			$pdf->Ln(7);
			$tbl2 .= '
		<table cellpadding="3" cellspacing="0" border="1">';
			if ($payment[0]->wages_type == 1) {
				$tbl2 .= ' <tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_payroll_basic_salary') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->basic_salary) . '</td>
			</tr>';
			} else {
				$tbl2 .= ' <tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_employee_daily_wages') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->daily_wages) . '</td>
			</tr>';
			}
			if ($payment[0]->total_allowances != 0 || $payment[0]->total_allowances != '') :
				$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_payroll_total_allowance') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->total_allowances) . '</td>
			</tr>';
			endif;
			if ($payment[0]->total_commissions != 0 || $payment[0]->total_commissions != '') :
				$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_hr_commissions') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->total_commissions) . '</td>
			</tr>';
			endif;
			if ($payment[0]->total_loan != 0 || $payment[0]->total_loan != '') :
				$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_payroll_total_loan') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->total_loan) . '</td>
			</tr>';
			endif;
			if ($payment[0]->total_overtime != 0 || $payment[0]->total_overtime != '') :
				$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_payroll_total_overtime') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->total_overtime) . '</td>
			</tr>';
			endif;
			if ($payment[0]->total_statutory_deductions != 0 || $payment[0]->total_statutory_deductions != '') :
				$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_employee_set_statutory_deductions') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->total_statutory_deductions) . '</td>
			</tr>';
			endif;
			if ($payment[0]->total_other_payments != 0 || $payment[0]->total_other_payments != '') :
				$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_employee_set_other_payment') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign($payment[0]->total_other_payments) . '</td>
			</tr>';
			endif;
			/*if($payment[0]->wages_type == 1){
				$bs = $payment[0]->basic_salary;
			} else {
				$bs = $payment[0]->daily_wages;
			}*/
			$total_earning = $bs + $allowances_amount + $overtime_amount + $commissions_amount + $other_payments_amount;
			$total_deduction = $loan_de_amount + $statutory_deduction_amount;
			$total_net_salary = $total_earning - $total_deduction;
			$tbl2 .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>' . $this->lang->line('xin_payroll_net_salary') . '</td>
				<td align="right">' . $this->Xin_model->currency_sign(number_format($total_net_salary, 2, '.', ',')) . '</td>
			</tr>
		</table>
		';

			$pdf->writeHTML($tbl2, true, false, false, false, '');
		}
		$tbl = '
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td align="right" colspan="1">This is a computer generated slip and does not require signature.</td>
			</tr>
		</table>';
		$pdf->writeHTML($tbl, true, false, false, false, '');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$fname = strtolower($fname);
		$pay_month = strtolower(date("F Y", strtotime($payment[0]->year_to_date)));
		//Close and output PDF document
		ob_start();
		$pdf->Output('payslip_' . $fname . '_' . $pay_month . '.pdf', 'D');
		ob_end_flush();
	}

	// get company > employees
	public function get_employees()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);

		$data = array(
			'company_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/get_employees", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	// make payment info by id
	public function make_payment_view()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('pay_id');
		// $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_make_payment_information($id);
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';
		}

		$data = array(
			'first_name' => $user[0]->first_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'department_name' => $department_name,
			'designation_name' => $designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender,
			'monthly_grade_id' => $user[0]->monthly_grade_id,
			'hourly_grade_id' => $user[0]->hourly_grade_id,
			'basic_salary' => $result[0]->basic_salary,
			//'is_half_monthly' => $user[0]->is_half_monthly,
			//'half_deduct_month' => $user[0]->half_deduct_month,
			'payment_date' => $result[0]->payment_date,
			'payment_method' => $result[0]->payment_method,
			'overtime_rate' => $result[0]->overtime_rate,
			'hourly_rate' => $result[0]->hourly_rate,
			'total_hours_work' => $result[0]->total_hours_work,
			'is_payment' => $result[0]->is_payment,
			'is_advance_salary_deduct' => $result[0]->is_advance_salary_deduct,
			'advance_salary_amount' => $result[0]->advance_salary_amount,
			'house_rent_allowance' => $result[0]->house_rent_allowance,
			'medical_allowance' => $result[0]->medical_allowance,
			'travelling_allowance' => $result[0]->travelling_allowance,
			'dearness_allowance' => $result[0]->dearness_allowance,
			'provident_fund' => $result[0]->provident_fund,
			'security_deposit' => $result[0]->security_deposit,
			'tax_deduction' => $result[0]->tax_deduction,
			'gross_salary' => $result[0]->gross_salary,
			'total_allowances' => $result[0]->total_allowances,
			'total_deductions' => $result[0]->total_deductions,
			'net_salary' => $result[0]->net_salary,
			'payment_amount' => $result[0]->payment_amount,
			'comments' => $result[0]->comments,
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_payslip', $data);
		} else {
			redirect('admin/');
		}
	}

	public function payslip_delete()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Payroll_model->delete_record($id);
		if (isset($id)) {
			$this->Payroll_model->delete_payslip_allowances_items($id);
			$this->Payroll_model->delete_payslip_commissions_items($id);
			$this->Payroll_model->delete_payslip_other_payment_items($id);
			$this->Payroll_model->delete_payslip_statutory_deductions_items($id);
			$this->Payroll_model->delete_payslip_overtime_items($id);
			$this->Payroll_model->delete_payslip_loan_items($id);
			$this->Contribution_fund_model->delete_contribution_payslip($id);
			$this->Cpf_payslip_model->delete_cpf_payslip($id);
			$this->Payroll_model->delete_payslip_share_options($id);
			$this->Payroll_model->delete_payslip_leave_deduction($id);
			$this->Payroll_model->delete_payslip_mapping($id);

			$Return['result'] = $this->lang->line('xin_hr_payslip_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

	public function payslip_delete_all($id)
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $id;
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$this->Payroll_model->delete_record($id);
		$this->Payroll_model->delete_payslip_allowances_items($id);
		$this->Payroll_model->delete_payslip_commissions_items($id);
		$this->Payroll_model->delete_payslip_other_payment_items($id);
		$this->Payroll_model->delete_payslip_statutory_deductions_items($id);
		$this->Payroll_model->delete_payslip_overtime_items($id);
		$this->Payroll_model->delete_payslip_loan_items($id);
	}

	// get company > locations
	public function get_company_plocations()
	{

		$data['title'] = $this->Xin_model->site_title();
		$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
		if (is_numeric($keywords[0])) {
			$id = $keywords[0];

			$data = array(
				'company_id' => $id
			);
			$session = $this->session->userdata('username');
			if (!empty($session)) {
				$data = $this->security->xss_clean($data);
				$this->load->view("admin/payroll/get_company_plocations", $data);
			} else {
				redirect('admin/');
			}
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	// get location > departments
	public function get_location_pdepartments()
	{

		$data['title'] = $this->Xin_model->site_title();
		$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
		if (is_numeric($keywords[0])) {
			$id = $keywords[0];

			$data = array(
				'location_id' => $id
			);
			$session = $this->session->userdata('username');
			if (!empty($session)) {
				$data = $this->security->xss_clean($data);
				$this->load->view("admin/payroll/get_location_pdepartments", $data);
			} else {
				redirect('admin/');
			}
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	public function get_department_pdesignations()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);

		$data = array(
			'department_id' => $id,
			'all_designations' => $this->Designation_model->all_designations(),
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/get_department_pdesignations", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	// Validate and update info in database // update_status
	public function update_payroll_status()
	{

		if ($this->input->post('type') == 'update_status') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if ($this->input->post('status') === '') {
				$Return['error'] = $this->lang->line('xin_error_template_status');
			}
			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$data = array(
				'status' => $this->input->post('status'),
			);
			$id = $this->input->post('payroll_id');
			$result = $this->Payroll_model->update_payroll_status($data, $id);
			if ($result == TRUE) {
				if ($this->input->post('status') == 1) {
					$Return['result'] = $this->lang->line('xin_role_first_level_approved');
				} else if ($this->input->post('status') == 2) {
					$Return['result'] = $this->lang->line('xin_approved_final_payroll_title');
				} else {
					$Return['result'] = $this->lang->line('xin_disabled_payroll_title');
				}
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function get_contribution_amount()
	{

		$user_id = $_GET['user_id'];
		$gross_salary = $_GET['gross_salary'];
		$emp_dob = $_GET['emp_dob'];
		$g_additional_wage = $_GET['g_additional_wage'];
		$g_ordinary_wage = $gross_salary;
		/*CPF start */
		$today = new DateTime('01-' . $this->input->get('pay_date'));
		$dob = new DateTime($emp_dob);


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
								$cpf_employer = round(9 / 100 * $tw);
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.45 * ($tw - 500));
								$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
								$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
								if ($count_total_cpf > 578) {
									$total_cpf = 578;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 340) {
									$cpf_employee = 340;
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
							} else if ($tw < 500 && $tw <= 750) {
								$cpf_employee = floor(0.6 * ($tw - 500));
								$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
								$cpf_employer = round($total_cpf - $cpf_employee);
							} else if ($tw > 750) {
								$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
								$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
								if ($count_total_cpf > 2516) {
									$total_cpf = 2516;
								} else {
									$total_cpf = $count_total_cpf;
								}
								if ($count_cpf_employee > 1360) {
									$cpf_employee = 1360;
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
								$cpf_employer = round($total_cpf - $cpf_employee);
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
						} else if ($tw < 500 && $tw <= 750) {
							$cpf_employee = floor(0.6 * ($tw - 500));
							$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
							$cpf_employer = round($total_cpf - $cpf_employee);
						} else if ($tw > 750) {
							$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
							$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
							if ($count_total_cpf > 2516) {
								$count_total_cpf = 2516;
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
							$cpf_employer = round($total_cpf - $cpf_employee);
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
							$cpf_employer = round($total_cpf - $cpf_employee);
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
							$cpf_employer = round($total_cpf - $cpf_employee);
						}
					}
				}
				/* new CPF calculation End*/


				// if ($ow > 500) {
				// 	$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				// } else {
				// 	$cpf_employee = 0;
				// }
				// $cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				// $cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				$cpf_employee = floor($cpf_employee);
				$cpf_employer = round($cpf_employer);
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
		/*contribution Funds*/
		$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($user_id);

		$shg_fund_deduction_amount = 0;
		if ($employee_contributions) {
			$gross_s = $gross_salary + $g_additional_wage;
			$contribution_id = $employee_contributions->contribution_id;
			$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
			$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
			$shg_fund_name = $contribution_type[0]->contribution;
			$shg_fund_deduction_amount += $contribution_amount;
		}
		$ashg_fund_deduction_amount = 0;
		$ashg_fund_name = "";
		$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($user_id);
		if ($employee_ashg_contributions) {
			$gross_s = $gross_salary + $g_additional_wage;

			$contribution_id = $employee_ashg_contributions->contribution_id;
			$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
			$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
			$ashg_fund_name = $contribution_type[0]->contribution;

			$ashg_fund_deduction_amount += $contribution_amount;
		}
		$output = array(
			'shg_fund_deduction_amount' => $shg_fund_deduction_amount,
			'ashg_fund_deduction_amount' => $ashg_fund_deduction_amount,
			'shg_fund_name' => $shg_fund_name,
			'ashg_fund_name' => $ashg_fund_name,
			'cpf_employee' => $cpf_employee,
			'cpf_employer' => $cpf_employer,
			'cpf_total' => $cpf_total

		);
		echo json_encode($output);
	}
}
