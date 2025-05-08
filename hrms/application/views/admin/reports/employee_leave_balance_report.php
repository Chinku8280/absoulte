<?php
/* Monthly Timesheet view > hrsale
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php
$user_info = $this->Xin_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Xin_model->user_role_resource();
$year = $this->input->post('year');
$company_id = $this->input->post('company_id');
$employee_id = $user_info[0]->user_id;
?>

<div class="box mb-4 <?php echo $get_animate; ?>">
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <?php $attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                <?php $hidden = array('_user' => $session['user_id']); ?>
                <?php echo form_open('admin/reports/employee_leave_balance_report', $attributes, $hidden); ?>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="first_name"><?php echo $this->lang->line('xin_e_details_date'); ?></label>
                            <input class="form-control year" value="<?php if (!isset($year)) : echo date('Y');
                                                                    else : echo $year;
                                                                    endif; ?>" name="year" type="text">
                        </div>
                    </div>
                    <?php if ($user_info[0]->user_role_id == 1) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
                                <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($get_all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id ?>" <?php if (isset($employee_id)) : if ($company->company_id == $company_id) : ?> selected="selected" <?php endif;
                                                                                                                                                                                endif; ?>><?php echo $company->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                    <label for="employee"><?php echo $this->lang->line('xin_employee'); ?></label>
                        <div class="form-group" id="employee_ajax">
                            <select name="employee_id" id="employee_id" class="form-control employee-data"
                                data-plugin="select_hrm"
                                data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>" required>
                                <?php if (isset($employee_id)) : ?>
                                <?php $result = $this->Department_model->ajax_company_employee_info($company_id); ?>
                                <option value="0">All</option>
                                <?php foreach ($result as $employee) { ?>
                                <option value="<?php echo $employee->user_id; ?>"
                                    <?php if ($employee->user_id == $employee_id) : ?> selected="selected" <?php endif; ?>>
                                    <?php echo $employee->first_name . ' ' . $employee->last_name; ?></option>
                                <?php } ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div> -->
                    <?php } ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">&nbsp;</label>
                            <br />
                            <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_get'))); ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_employees_monthly_timesheet'); ?></h3>
        <h5>For the month of
            <?php if (isset($year)) : echo date('Y', strtotime($year));
            else : echo date('Y');
            endif; ?>
        </h5>
        <!-- <div class="box-tools pull-right"> A: Absent, P: Present, H: Holiday, L: Leave</div> -->
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="employee_leave_balance_report_table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Employee Name </th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Date of Commencement</th>
                        <th>Leave Entitlement for <?php echo $year ?> </th>
                        <th>Balance Brought Forward from Year <?php echo $year - 1 ?></th>
                        <th>Off- In- Lieu</th>
                        <th>Balance Leave for Year <?php echo $year ?></th>
                    </tr>
                </thead>

                <?php

                $employees = $this->Employees_model->get_company_employees_flt($company_id);

                foreach ($employees->result() as $key => $employee) {
                    $key++;
                ?>
                    <tr>
                        <td><?php echo $key;?></td>
                        <td><?php echo $employee->first_name . " " . $employee->last_name ?></td>
                        <?php
                            $read_designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);
                            $read_department = $this->Department_model->read_department_information($user_info[0]->department_id);
                            $user_id = $employee->user_id;
                            $leaves = $this->Timesheet_model->getEmployeeLeave($user_id);
                            $office_shift = $this->Timesheet_model->read_office_shift_information($user_info[0]->office_shift_id);
                            $current_year = $this->Employees_model->getEmployeeLeaveCountForLeave(22, $year, $user_id);
                            $remaining_leave = 0;
                            if ($current_year) {
                    
                                $applied_leaves = $this->Timesheet_model->getEmployeeAppliedLeaveCount(22, $year, $user_id);
                    
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
                    
                    
                    
                    
                                $type = $this->Timesheet_model->getEmployeeLeaveCount(22, $user_id);
                                $previous_year = $this->Employees_model->getEmployeeLeaveCountForLeave(22, $year - 1, $user_id);
                                $current_year = $this->Employees_model->getEmployeeLeaveCountForLeave(22, $year, $user_id);
                                $setting = $this->Xin_model->read_setting_info(1);
                                if ($setting[0]->module_prorated_leave == 'yes' && 22 == 22) {
                    
                    
                                    $get_join_year = new DateTime($user_info[0]->date_of_joining);
                                    $join_year = $get_join_year->format('Y');
                                    if ($join_year < $year) {
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
                    
                                        $join_date = new DateTime($user_info[0]->date_of_joining);
                                        $today = new DateTime();
                                        // Calculate the difference between join date and today
                                        $interval = $join_date->diff($today);
                    
                                        // Get the number of days as an integer
                                        $daysRemaining = $interval->days;
                                        // $annual_leave_taken = ($remaining_leave/365) * $daysRemaining;
                                        $annual_leave_taken = ($current_year->no_of_leaves / 365) * $daysRemaining;
                                    }
                                    $annual_leave_taken = intval(ceil($annual_leave_taken));
                                    $remaining_leave = $annual_leave_taken + (isset($previous_year->remain_leave) && $previous_year->remain_leave != null ? $previous_year->remain_leave : 0);
                                } else {
                                    // $remaining_leave = $l->no_of_leaves;
                                    // $type = $this->Timesheet_model->getEmployeeLeaveCount($l->leave_type_id, $employee_id);
                    
                                    if ($type) {
                                        $type_name = $type->type_name;
                                        $total = $type->no_of_leaves;
                                        // $leave_remaining_total =  $type->balance_leave - $leaves_taken;
                                        $leave_remaining_total =  $type->balance_leave_check - $leaves_taken;
                                    }
                                    // $remaining_leave = $l->no_of_leaves - $leaves_taken;
                                    if ($leave_remaining_total > 0) {
                                        $remaining_leave = $leave_remaining_total;
                                    } else {
                                        $remaining_leave = 0;
                                    }
                                }
                    
                                if ($remaining_leave > $current_year->balance_leave + (isset($previous_year->remain_leave) && $previous_year->remain_leave != null ? $previous_year->remain_leave : 0)) {
                                    $remaining_leave = $current_year->balance_leave;
                                }
                            }




                    
                        ?>
                        <td><?php echo $read_designation[0]->designation_name ?></td>
                        <td><?php echo $read_department[0]->department_name ?></td>
                        <td><?php echo $employee->date_of_joining ?></td>
                        <td><?php echo $remaining_leave ?></td>
                        <td><?php echo $previous_year->carried_leave ?? strval(0) ?></td>
                        <td><?php echo "" ?></td>
                        <td><?php echo $current_year->balance_leave ?? strval(0) ?></td>
                    </tr>
                <?php
                }

                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<style type="text/css">
    .box-tools {
        margin-right: -5px !important;
    }

    .col-md-8 {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    .dataTables_length {
        float: left;
    }

    .dt-buttons {
        position: relative;
        float: right;
        margin-left: 10px;
    }

    .hide-calendar .ui-datepicker-calendar {
        display: none !important;
    }

    .hide-calendar .ui-priority-secondary {
        display: none !important;
    }
</style>