<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);


$employees = $this->db->get('xin_employees')->result();
$all_office_shifts = $this->Location_model->all_office_locations();
$employee_Type = "";

$p_month = date('F Y', strtotime('01-' . $date));
$pa_month = date('Y-m', strtotime('01-' . $date));

?>
<div class="row m-b-1 <?php echo $get_animate; ?>">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">

            </div>
            <div class="box-body">
                <form action="<?= base_url() ?>admin/reports/Salary_Report" method="post" id="generate_report_table">

                    <div class="row ">
                        <?php if ($user_info[0]->user_role_id == 1) { ?>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="company_name"><?php echo $this->lang->line('module_company_title'); ?></label>
                                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>">
                                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                        <?php foreach ($all_companies as $company) { ?>
                                            <option value="<?php echo $company->name; ?>"> <?php echo $company->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?php $ecompany_id = $user_info[0]->company_id; ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="company_name"><?php echo $this->lang->line('module_company_title'); ?></label>
                                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>">
                                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                        <?php foreach ($all_companies as $company) { ?>
                                            <?php if ($ecompany_id == $company->company_id) : ?>
                                                <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?></option>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                    </div>
                <?php } ?>
                <div class="col-md-3" id="employee_div_id">
                    <div class="form-group">
                        <label for="company_name">Employees</label>
                        <select class="form-control" name="company_id" id="Employees_name_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>">
                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                            <?php foreach ($employees as $em) : ?>

                                <option value="<?php echo $em->first_name; ?>"> <?php echo $em->first_name; ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="name"><?php echo $this->lang->line('left_location'); ?></label>
                        <select name="location_id" id="customFilterSelect" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location'); ?>">
                            <option value="">ALL</option>
                            <?php foreach ($all_office_shifts as $elocation) { ?>
                                <option value="<?php echo $elocation->location_name ?>">
                                    <?php echo $elocation->location_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class=" col-md-3">
                    <div class="form-group">
                        <label for="month_year"><?php echo $this->lang->line('xin_award_month_year'); ?></label>
                        <input class="form-control r_month_year" placeholder="<?php echo $this->lang->line('xin_award_month_year'); ?>" readonly name="month_year" id="month_year" type="text">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">&nbsp;</label><br />
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-12 <?php echo $get_animate; ?>" id="salary_1">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_view'); ?> <?= $date ?></h3>
        </div>
        <div class="box-body">
            <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="Salary_Reportg1">
                    <thead>
                        <tr>
                            <th>SR No</th>
                            <th>Co</th>
                            <th>Employee No</th>
                            <th>Bank Details</th>
                            <th>Name Of Staff</th>
                            <th>Client/Location</th>
                            <th>Designation</th>
                            <th>ID No.</th>
                            <th>Start work</th>
                            <th>Last Day</th>
                            <th>Shift</th>

                            <th> Basic </th>
                            <th> OverTime pay </th>

                            <?php foreach ($recurring_allowances as $sl): ?>

                                <th><?= $sl->allowance_name ?></th>

                            <?php endforeach; ?>

                            <th> Claim </th>


                            <?php foreach ($deduction_type as $sl): ?>

                                <th><?= $sl->deduction_type ?></th>

                            <?php endforeach; ?>
                            <?php foreach ($commission as $com): ?>

                                <th><?= $com->commission_name ?></th>

                            <?php endforeach; ?>

                            <th> Un Paid leave </th>
                            <th> CPF </th>
                            <th> Donation </th>
                            <th> Total Salary Payouts </th>
                            <th>Pay Date</th>
                            <th>Annual Leave</th>
                            <th>Month</th>


                        </tr>
                    </thead>
                    <tbody>
                        <!-- this is code  -->
                        <?php
                        $d = $this->db->where('user_role_id !=', 1)->get('xin_employees')->result();
                        // print_r($all_companies);die;
                        foreach ($d as $rs => $e) { ?>
                            <?php

                            $system = $this->Xin_model->read_setting_info(1);
                            $payment_month = strtotime('01-' . $this->input->get('pay_date'));
                            $p_month = date('F Y', $payment_month);
                            $basic_salary = $e->basic_salary;
                            $hourly_rate = $e->basic_salary;
                            $comapny_n = $this->db->where('company_id', $e->company_id)->get('xin_companies')->row()->name;
                            $location_g = $this->db->where('location_id', $e->location_id)->get('xin_office_location')->row()->location_name;


                            $pay_date = $date;

                            ?>
                            <?php




                            // office shift
                            $office_shift = $this->Timesheet_model->read_office_shift_information($e->office_shift_id);

                            //overtime request
                            $overtime_count = $this->Overtime_request_model->get_overtime_request_count($e->user_id, $this->input->get('month_year'));
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
                            $result = $this->Payroll_model->total_hours_worked($e->user_id, $pay_date);
                            // $hrms_time = $this->db->where('user_id', $e->user_id)->where('date', $pay_date)->get('hrms_timecards')->row();
                            // $pay = $this->Employees_model->get_basic_pay($e->user_id, $pay_date);
                            // $amm = $pay['pay'];
                            $hrs_old_int1 = 0;
                            $pcount = 0;
                            $Trest = 0;
                            $total_time_rs = 0;
                            $hrs_old_int_res1 = 0;

                            // this is the allowance
                            $aloow = $this->db->where('employee_id', $e->user_id)->get('xin_salary_allowances')->result();
                            // $alo_time = $this->db->where('user_id', $e->user_id)->get('hrms_timecards')->result();
                            $alo_rate = $this->db->where('employee_id', $e->user_id)->get('xin_employee_overtime_rate')->result();
                            $add_amount = 0;
                            $all_overtime = 0;
                            $all_overtime_P = 0;
                            $all_work = 0;
                            $a_work_day = 0;
                            foreach ($aloow as $al) {

                                $add_amount += $al->allowance_amount;
                            }
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
                            if ($e->wages_type == 1) {
                                // $wages_type = $this->lang->line('xin_payroll_basic_salary');
                                if ($system[0]->is_half_monthly == 1) {
                                    $basic_salary = $basic_salary / 2;
                                } else {
                                    $basic_salary = $basic_salary;
                                }
                                $p_class = 'emo_monthly_pay';
                                $view_p_class = 'payroll_template_modal';
                            } else if ($e->wages_type == 2) {
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
                            // echo "<pre>";
                            // print_r($basic_salary);
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
                            $basic_salary = $basic_salary;
                            $g_ordinary_wage += $basic_salary;

                            $g_shg += $basic_salary;
                            $g_sdl += $basic_salary;

                            $pay_date = $date;

                            //Allowance
                            $allowance_amount = 0;
                            $gross_allowance_amount = 0;
                            $salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($e->user_id, $pay_date);
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
                            $employee_deduction = $this->Payroll_model->get_deduction_detail($e->user_id, $pay_date);
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
                            // print_r($pay_date);die;
                            // print_r($salary_allowances);die;
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
                                $is_holiday = $this->Timesheet_model->is_holiday_on_date($e->company_id, $p_date);
                                if ($is_holiday) {
                                    $holidays_count += 1;
                                }

                                //working days excluding holidays based on office shift
                                if ($p_day == 'Monday') {
                                    if ($office_shift[0]->monday_in_time ?? 0 != '') {
                                        $no_of_working_days += 1;
                                    }
                                } else if ($p_day == 'Tuesday') {
                                    if ($office_shift[0]->tuesday_in_time != '') {
                                        $no_of_working_days += 1;
                                    }
                                } else if ($p_day == 'Wednesday') {
                                    if ($office_shift[0]->wednesday_in_time ?? 0 != '') {
                                        $no_of_working_days += 1;
                                    }
                                } else if ($p_day == 'Thursday') {
                                    if ($office_shift[0]->thursday_in_time ?? 0 != '') {
                                        $no_of_working_days += 1;
                                    }
                                } else if ($p_day == 'Friday') {
                                    if ($office_shift[0]->friday_in_time ?? 0 != '') {
                                        $no_of_working_days += 1;
                                    }
                                } else if ($p_day == 'Saturday') {
                                    if ($office_shift[0]->saturday_in_time ?? 0 != '') {
                                        $no_of_working_days += 1;
                                    }
                                } else if ($p_day == 'Sunday') {
                                    if ($office_shift[0]->sunday_in_time ?? 0 != '') {
                                        $no_of_working_days += 1;
                                    }
                                }
                            }
                            $des = $this->db->where('designation_id', $e->designation_id)->get('xin_designations')->row()->designation_name ?? '';

                            //unpaid leave
                            $unpaid_leave_amount = 0;
                            $leaves_taken_count = 0;
                            $Annual_taken_count = 0;
                            $leave_period = array();
                            $unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($e->user_id, $pay_date);
                            if ($unpaid_leaves) {
                                foreach ($unpaid_leaves as $k => $l) {
                                    // $pay_date_month = new DateTime($pay_date . '-01');
                                    $pay_date_month = new DateTime('01-' . $pay_date);
                                    $l_from_date = new DateTime($l->from_date);
                                    $l_to_date = new DateTime($l->to_date);
                                    if ($l->type_name == "Annual Leave") {
                                        $Annual_taken_count += 1;
                                    }

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
                            // $g_ordinary_wage -= $unpaid_leave_amount;
                            // echo 'Unpaid Leave : '. $unpaid_leave_amount . '<br>';
                            // echo '<hr>';

                            // 3: all loan/deductions
                            $salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($e->user_id);
                            $count_loan_deduction = $this->Employees_model->count_employee_deductions($e->user_id);
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
                            $other_payments = $this->Employees_model->set_employee_other_payments($e->user_id);
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
                            $commissions = $this->Employees_model->getEmployeeMonthlyCommission($e->user_id, $pay_date);
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
                            $share_options = $this->Employees_model->getEmployeeShareOptions($e->user_id, $pay_date);
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
                            $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($e->user_id);
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


                            $overtime_amount = 0;
                            $overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($e->user_id, $pay_date);
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
                                $overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($e->user_id);
                                if ($overtime_rate) {
                                    $rate = $overtime_rate->overtime_pay_rate;
                                } else {
                                    $week_hours = 0;
                                    if ($office_shift_id) {
                                        $shift = $this->Employees_model->read_shift_information($e->office_shift_id);
                                        // print_r($shift);
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
                                    // $overtime_amount = round($ot_hrs * $rate, 2);
                                    $overtime_amount = round($all_overtime_P, 2);
                                }
                                if ($system[0]->is_half_monthly == 1) {
                                    if ($system[0]->half_deduct_month == 2) {
                                        $overtime_amount = $overtime_amount / 2;
                                    }
                                }
                                // $g_ordinary_wage += $overtime_amount;
                            }
                            // $overt = $this->Employees_model->get_overtime($e->user_id, $pay_date);
                            $all_overtime_P = $overtime_amount;
                            $new_leave_deduction_value = 0;

                            // all other payment
                            // $restday_pay1 = $this->Employees_model->restday_pay($e->user_id, $pay_date);
                            // $restday_pay = $restday_pay1['rest_daya_amount'];
                            // $get_productivity =;
                            $productivity_amount = 0;
                            $restday_pay = 0;
                            // all other payment
                            // $deduction = ;

                            $all_other_payment = $other_payments_amount + $share_options_amount;
                            $gross_salary = $basic_salary + $allowance_amount + $commissions_amount + $all_overtime_P  + $all_other_payment;
                            // add amount
                            $add_salary = $allowance_amount + $basic_salary + $productivity_amount + $all_overtime_P + $restday_pay + $all_other_payment + $commissions_amount;
                            // add amount
                            $net_salary_default = $add_salary - floatval($loan_de_amount) - $statutory_deductions_amount - (float) $new_leave_deduction_value - $deduction_amount;
                            $sta_salary = $allowance_amount + $basic_salary;

                            $estatutory_deductions = $statutory_deductions_amount;
                            // net salary + statutory deductions
                            $net_salary = $net_salary_default;
                            // print_r($basic_salary);
                            $new_pay = number_format((float)$basic_salary, 2, '.', '');
                            // print_r();

                            $g_ordinary_wage += $restday_pay;
                            $g_ordinary_wage += $other_payments_amount;
                            $g_ordinary_wage += $all_overtime_P;
                            $g_ordinary_wage -= $new_leave_deduction_value;
                            $g_ordinary_wage -= $deduction_amount;
                            // print_r($basic_salary)/;

                            // if ($get_productivity['incentive_type'] == 1) {
                            //     $g_additional_wage += $productivity_amount;
                            // } else {
                            //     $g_ordinary_wage += $productivity_amount;
                            // }
                            // $g_shg += $productivity_amount;
                            // $g_sdl += $productivity_amount;

                            /**
                             * Author : Syed Anees
                             * Sub Functionality : CPF on Gross Salary
                             */
                            $emp_dob = $e->date_of_birth;
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

                            $im_status = $this->Employees_model->getEmployeeImmigrationStatus($e->user_id);
                            $aw_cpf_employee = 0;
                            $aw_cpf_employer = 0;
                            $cpf_employee = 0;
                            $cpf_employer = 0;
                            $cpf_total = 0;
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
                                    if ($pr_age_year == 0 && $pr_age_month > 0) {
                                        // $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 1);
                                        $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to,  1);
                                    } elseif ($pr_age_year == 1 && $pr_age_month > 0) {
                                        // $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, $age_from, $age_to, 2, 2);
                                        $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 2);
                                        // print_r($pr_age_month);exit;
                                    } elseif ($pr_age_year >= 2) {
                                        // $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year,2, $age_from, $age_to, 1);
                                        $cpf_contribution = $this->Cpf_percentage_model->get_cpf_contribution_by_age($age_year, 2, $age_from, $age_to, 1);
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


                                        // $cpf_total_ow = round(($total_cpf_contribution * $ow) / 100);
                                        // $ow_cpf_employee = floor(($employee_contribution * $ow) / 100);
                                        // $ow_cpf_employer = $cpf_total_ow - $ow_cpf_employee;


                                        $aw = $g_additional_wage;
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
                                        // $cpf_total = $cpf_employee + $cpf_employer;
                                        $cpf_total = $cpf_employee;


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
                                }
                            }
                            // print_r($ow);
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
                            $get_cliam_data = 0;
                            $shg_fund_deduction_amount = 0;
                            //Other Fund Contributions
                            $employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($e->user_id);
                            if ($employee_contributions) {
                                $gross_s = $g_shg;
                                $contribution_id = $employee_contributions->contribution_id;
                                $contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
                                $shg_fund_deduction_amount += $contribution_amount;
                                $net_salary = $net_salary - $shg_fund_deduction_amount;
                            }

                            $ashg_fund_deduction_amount = 0;

                            $employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($e->user_id);
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
                                $payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($e->user_id, $this->input->get('pay_date'));
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

                            // print_r($ow);
                            $ac = $this->db->where('employee_id', $e->user_id)->get('xin_employee_bankaccount')->row();


                            ?>
                            <tr>
                                <th scope="row"><?= $rs + 1 ?> </th>
                                <th scope="row"> <?= $comapny_n ?></th>
                                <th scope="row"><?= $e->employee_id ?? '' ?> </th>
                                <th scope="row"><?= $ac->account_number ?? '' ?> </th>
                                <th scope="row"><?= $e->first_name ?> <?= $e->last_name ?></th>
                                <th scope="row"><?= $location_g ?></th>
                                <td><?php echo $des ?? ''; ?></td>
                                <td><?php echo $e->id_no ?? ''; ?></td>
                                <td><?php echo $e->date_of_joining ?? ''; ?></td>
                                <td><?php echo $e->date_of_leaving ?? ''; ?></td>

                                <td><?= $office_shift[0]->shift_name ?? '' ?></td>

                                <td><?php echo $this->Xin_model->currency_sign($e->basic_salary ?? 0,$e->user_id); ?></td>
                                <th scope="row"><?= $this->Xin_model->currency_sign($all_overtime_P,$e->user_id) ?></th>

                                <?php foreach ($recurring_allowances as $sl): ?>



                                    <td><?= $this->Xin_model->currency_sign(get_allowances_type_heler($e->user_id, $pay_date, '' . $sl->allowance_name . ''),$e->user_id) ?></td>
                                <?php endforeach; ?>



                                <td><?= $get_cliam_data ?></td>

                                <?php foreach ($deduction_type as $sl): ?>

                                    <td><?= $this->Xin_model->currency_sign(get_deduction_type_heler($e->user_id, $pay_date, '' . $sl->deduction_type . ''),$e->user_id) ?></td>


                                <?php endforeach; ?>

                                <?php foreach ($commission as $com): ?>

                                    <td><?= $this->Xin_model->currency_sign(get_commission_type_heler($e->user_id, $pay_date, '' . $com->commission_name . ''),$e->user_id) ?></td>

                                <?php endforeach; ?>
                                <td><?php echo $this->Xin_model->currency_sign($new_leave_deduction_value,$e->user_id); ?></td>
                                <td><?php echo $this->Xin_model->currency_sign($cpf_total,$e->user_id); ?></td>
                                <td></td>
                                <td><?php echo $this->Xin_model->currency_sign($net_salary + $get_cliam_data,$e->user_id); ?></td>
                                <td><?= $pay_details->year_to_date ?? '' ?></td>

                                <td><?= $Annual_taken_count ?></td>
                                <td><?= $date ?></td>


                            </tr>

                        <?php    }
                        ?>
                        <!-- this is code  -->

                    <tfoot>
                        <tr>
                            <th colspan="11" style="text-align:right">Total:</th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th id="total-salary"></th>
                            <th colspan="2"></th>
                            <th id="total-salary"></th>
                        </tr>
                    </tfoot>

                    </tbody>
                </table>
            </div>
            <!-- responsive -->
        </div>
    </div>
</div>
</div>

<?php
$date_e = DateTime::createFromFormat('m-Y', $date);
$formattedDate = $date_e->format('F`Y');

?>
</div>
<style type="text/css">
    .hide-calendar .ui-datepicker-calendar {
        display: none !important;
    }

    .hide-calendar .ui-priority-secondary {
        display: none !important;
    }
</style>
<?php
$jsonArray = json_encode($employees);

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

<script>
    function salary(a, b) {

        if (a == 1) {
            $("#customer_id" + a).css("background-color", "red")
            $("#customer_id3").css("background-color", "")
            $("#customer_id2").css("background-color", "")
            $("#salary_2").hide()
            $("#salary_3").hide()
            $("#salary_1").show()
        } else if (a == 2) {
            $("#customer_id2").css("background-color", "red")
            $("#customer_id3").css("background-color", "")
            $("#customer_id1").css("background-color", "")
            $("#salary_2").show()
            $("#salary_1").hide()
            $("#salary_3").hide()
        } else {
            $("#customer_id" + a).css("background-color", "red")
            $("#customer_id2").css("background-color", "")
            $("#customer_id1").css("background-color", "")
            $("#salary_1").hide()
            $("#salary_2").hide()
            $("#salary_3").show()


        }
        var jsArray = <?php echo $jsonArray; ?>;
        var filteredEmployees_data = jsArray.filter(function(em) {
            return em.client == b;
        });



        filteredEmployees_data.forEach(function(em) {
            $('#Employees_name_id').append(
                $('<option></option>').val(em.first_name).text(em.first_name)
            );
        });


    }

    $(document).ready(function() {

        $("#customer_id1").css("background-color", "red")

        $("#month_year").val('');

        // var table2 = $('#Salary_Reportg2').DataTable({

        //     paging: true,


        //     columnDefs: [{
        //         "width": "100px",
        //         "targets": [0]
        //     }, ],


        // });
        // var table1 = $('#Salary_Reportg1').DataTable({

        //     paging: true,

        //     dom: 'lBfrtip',
        //     "buttons": ['csv', 'excel']
        // });
        var filterValues = {
            location: "",
            company: "",
            employee: "",
            project: "",
            department: "",
            client: "",
            month: ""
        };

        function updateFilterValue(key, value) {
            filterValues[key] = value;
            applyFilters();
        }

        // Function to apply all filters to the DataTable
        function applyFilters() {
            var searchValue = Object.values(filterValues).join(" ");
            var table = $('#Salary_Reportg1').DataTable();
            // var table2 = $('#Salary_Reportg2').DataTable();
            // var table3 = $('#Salary_Reportg3').DataTable();
            table.search(searchValue).draw();
            // table2.search(searchValue).draw();
            // table3.search(searchValue).draw();
        }
        $("#aj_company").on("change", function(e) {
            e.preventDefault();
            updateFilterValue('company', $(this).val());
        })
        $("#Employees_name_id").on("change", function(e) {
            e.preventDefault();


            updateFilterValue('employee', $(this).val());
        })
        $('#customFilterSelect').on('change', function(e) {

            e.preventDefault();

            updateFilterValue('location', $(this).val());
        });



    })
</script>


<!-- <script>
    $(document).ready(function() {
        var table = $('#Salary_Reportg3').DataTable({
            paging: true,
            dom: 'lBfrtip',
            buttons: [{
                text: 'Export to Excel',
                action: function(e, dt, button, config) {
                    exportTableToExcel();
                }
            }]


        });

        const boldBorderStyle = {
            top: {
                style: 'thick',
                color: {
                    argb: 'FF000000'
                }
            },
            bottom: {
                style: 'thick',
                color: {
                    argb: 'FF000000'
                }
            },
            left: {
                style: 'thick',
                color: {
                    argb: 'FF000000'
                }
            },
            right: {
                style: 'thick',
                color: {
                    argb: 'FF000000'
                }
            }
        };


        function exportTableToExcel() {

            $('#b_firt_date').remove();
            $('#f_firt_date').remove();
            let formattedDate = $('#formattedDate').val();
            var table = $('#Salary_Reportg3').DataTable();
            var data = [];




            var extraHeader = [];
            for (var i = 0; i < 52; i++) {
                if (i == 43) {
                    extraHeader.push({
                        v: "    " + "       " + "       " + "       " + "       " + "       " + "       " + "Deduction",
                        s: {
                            alignment: {
                                horizontal: 'center'
                            },
                            border: boldBorderStyle


                        }
                    });
                } else {
                    extraHeader.push({
                        v: "",
                        s: {
                            alignment: {
                                horizontal: 'center'
                            },

                        }
                    });
                }

            }
            var extraHeader1 = [];
            for (var i = 0; i < 52; i++) {
                if (i == 4) {

                    extraHeader1.push({
                        v: " " + " " + formattedDate,
                        s: {
                            alignment: {
                                horizontal: 'center'
                            },

                        }
                    });
                } else if (i == 10) {

                    extraHeader1.push({
                        v: "  " + "  " + "  " + "  " + "  " + "   " + "    " + "     " + "     " + "     " + "      " + "1-15" + " " + formattedDate,
                        s: {
                            alignment: {
                                horizontal: 'center'
                            },

                        }
                    });
                } else if (i == 27) {

                    extraHeader1.push({
                        v: "16-30" + " " + formattedDate,
                        s: {
                            alignment: {
                                horizontal: 'middle'
                            },

                        }
                    });
                } else {
                    extraHeader1.push({
                        v: "",
                        s: {
                            alignment: {
                                horizontal: 'center'
                            },

                        }
                    });

                }
            }


            data.push(extraHeader1);
            data.push(extraHeader);

            // Extract actual table headers
            var headers = [];
            $('#Salary_Reportg3 thead th').each(function() {
                headers.push($(this).text());
            });

            data.push(headers);

            // Extract data rows
            // table.rows().every(function() {
            // 	var rowData = this.data();
            // 	// var selectedRowData = [rowData[0], rowData[1], ];
            // 	var selectedRowData = table.row({
            // 		selected: true
            // 	}).data(); // Assumes row selection is enabled
            // 	console.log(selectedRowData);
            // 	// data.push(selectedRowData);
            // });
            // Extract data rows
            var selectedRows = table.rows({
                selected: true
            }).data();

            selectedRows.each(function(value, index) {
                // Process each selected row
                data.push(value);
            });




            var ws = XLSX.utils.aoa_to_sheet(data);
            ws['!cols'] = [];
            for (let i = 0; i < extraHeader1.length; i++) {
                ws['!cols'][i] = {
                    width: 20
                };
            }



            // Set column widths (in Excel character units)


            // Merge cells for the extra header
            ws['!merges'] = [{
                    s: {
                        r: 0,
                        c: 0
                    }, // Start at row 0, column 0 (A1)
                    e: {
                        r: 0,
                        c: 3
                    } // End at row 0, column 2 (C1)
                },
                {
                    s: {
                        r: 0,
                        c: 4
                    }, // Start at row 0, column 0 (A1)
                    e: {
                        r: 0,
                        c: 9
                    } // End at row 0, column 2 (C1)
                },

                {
                    s: {
                        r: 0,
                        c: 10
                    }, // Start at row 2, column 0 (A3)
                    e: {
                        r: 0,
                        c: 24
                    } // End at row 2, column 3 (D3)
                }, // Merge A3:D3
                {
                    s: {
                        r: 0,
                        c: 27
                    }, // Start at row 2, column 0 (A3)
                    e: {
                        r: 0,
                        c: 52
                    } // End at row 2, column 3 (D3)
                } // Merge A3:D3

                // row1 =start
                , // Merge A1:C1
                {
                    s: {
                        r: 1,
                        c: 0
                    }, // Start at row 2, column 0 (A3)
                    e: {
                        r: 1,
                        c: 42
                    } // End at row 2, column 3 (D3)
                }, // Merge A3:D3
                {
                    s: {
                        r: 1,
                        c: 43
                    }, // Start at row 2, column 0 (A3)
                    e: {
                        r: 1,
                        c: 48
                    } // End at row 2, column 3 (D3)
                }, // Merge A3:D3
                // Merge A3:D3
                {
                    s: {
                        r: 1,
                        c: 49
                    }, // Start at row 2, column 0 (A3)
                    e: {
                        r: 1,
                        c: 52
                    } // End at row 2, column 3 (D3)
                }, // Merge A3:D3
            ];

            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            var wbout = XLSX.write(wb, {
                bookType: 'xlsx',
                type: 'binary'
            });


            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            var blob = new Blob([s2ab(wbout)], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8;'
            });
            var link = document.createElement('a');
            if (link.download !== undefined) {
                var url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', 'Monthly_attendance.xlsx');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
            // return data;
            window.location.reload(1)
        }
        // var data = exportTableToExcel();


    });


    // thi=s 
</script> -->

<script>
    $(document).ready(function() {
        // Initialize the DataTable
        var table = $('#Salary_Reportg1').DataTable({
            dom: 'lBfrtip',
            paging: false, // Disable pagination
            "buttons": ['csv', 'excel'],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();

                // Function to remove $ and commas from salary and convert to a number
                var parseSalary = function(salary) {
                    return parseFloat(String(salary).replace(/[^\d.-]/g, '')) || 0;
                };

                // Calculate total salary for the current page
                var total = api
                    .column(11, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var over_time = api
                    .column(12, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var allowance = api
                    .column(13, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var extra_duty_allowance = api
                    .column(14, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var housing = api
                    .column(15, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var incentive = api
                    .column(16, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var meal = api
                    .column(17, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var per_diem = api
                    .column(18, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var transport = api
                    .column(19, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var claim = api
                    .column(20, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var d1 = api
                    .column(21, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var d2 = api
                    .column(22, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var commission_d_w_m = api
                    .column(23, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var commission_i = api
                    .column(24, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var unpaid_leave = api
                    .column(25, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var cpf = api
                    .column(26, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var donation = api
                    .column(27, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var payout = api
                    .column(28, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);
                var annual_leave = api
                    .column(30, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return parseSalary(a) + parseSalary(b);
                    }, 0);


                // Update the footer with the total salary
                $(api.column(11).footer()).html('$' + total.toLocaleString());
                $(api.column(12).footer()).html('$' + over_time.toLocaleString());
                $(api.column(13).footer()).html('$' + allowance.toLocaleString());
                $(api.column(14).footer()).html('$' + extra_duty_allowance.toLocaleString());
                $(api.column(15).footer()).html('$' + housing.toLocaleString());
                $(api.column(16).footer()).html('$' + incentive.toLocaleString());
                $(api.column(17).footer()).html('$' + meal.toLocaleString());
                $(api.column(18).footer()).html('$' + per_diem.toLocaleString());
                $(api.column(19).footer()).html('$' + transport.toLocaleString());
                $(api.column(20).footer()).html('$' + claim.toLocaleString());
                $(api.column(21).footer()).html('$' + d1.toLocaleString());
                $(api.column(22).footer()).html('$' + d2.toLocaleString());
                $(api.column(23).footer()).html('$' + commission_d_w_m.toLocaleString());
                $(api.column(24).footer()).html('$' + commission_i.toLocaleString());
                $(api.column(25).footer()).html('$' + unpaid_leave.toLocaleString());
                $(api.column(26).footer()).html('$' + cpf.toLocaleString());
                $(api.column(27).footer()).html('$' + donation.toLocaleString());
                $(api.column(28).footer()).html('$' + payout.toLocaleString());
                $(api.column(30).footer()).html('$' + annual_leave.toLocaleString());



            }
        });
    });
</script>