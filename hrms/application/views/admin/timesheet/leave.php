<?php
/* Leave Application view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $user = $this->Xin_model->read_employee_info($session['user_id']); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $xuser_info = $this->Xin_model->read_user_info($session['user_id']); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php
// reports to 
$reports_to = get_reports_team_data($session['user_id']); ?>
<?php if ($xuser_info[0]->user_role_id == 1) { ?>
    <div id="filter_hrsale" class="collapse add-formd <?php echo $get_animate; ?>" data-parent="#accordion" style="">
        <div class="row">
            <div class="col-md-12">
                <div class="box mb-4">
                    <div class="box-header  with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('xin_filter'); ?></h3>
                        <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hrsale" aria-expanded="false">
                                <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-minus"></span>
                                    <?php echo $this->lang->line('xin_hide'); ?></button>
                            </a> </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php $attributes = array('name' => 'ihr_report', 'id' => 'ihr_report', 'class' => 'm-b-1 add form-hrm'); ?>
                                <?php $hidden = array('user_id' => $session['user_id']); ?>
                                <?php echo form_open('admin/timesheet/leave_list', $attributes, $hidden); ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="department"><?php echo $this->lang->line('module_company_title'); ?></label>
                                            <select class="form-control" name="company" id="aj_companyf" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                                <option value="0"><?php echo $this->lang->line('xin_all_companies'); ?>
                                                </option>
                                                <?php foreach ($get_all_companies as $company) { ?>
                                                    <option value="<?php echo $company->company_id; ?>">
                                                        <?php echo $company->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" id="employee_ajaxf">
                                            <label for="department"><?php echo $this->lang->line('dashboard_single_employee'); ?></label>
                                            <select id="employee_id" name="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                                <option value="0"><?php echo $this->lang->line('xin_all_employees'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status"><?php echo $this->lang->line('dashboard_xin_status'); ?></label>
                                            <select class="form-control" name="status" id="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status'); ?>">
                                                <option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
                                                <option value="1"><?php echo $this->lang->line('xin_pending'); ?></option>
                                                <option value="2"><?php echo $this->lang->line('xin_approved'); ?></option>
                                                <option value="3"><?php echo $this->lang->line('xin_rejected'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">From Date</label>
                                           <input type="date" name="from_date" class="form-control" value="<?php echo date('Y-m-d')?>" id="from_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">To Date</label>
                                           <input type="date" name="to_date" class="form-control" value="<?php echo date('Y-m-d')?>" id="to_date">
                                        </div>
                                    </div>
                                    <div class="col-md-1"><label for="xin_get">&nbsp;</label><button name="hrsale_form" type="submit" class="btn btn-primary"><i class="fa fa fa-check-square-o"></i>
                                            <?php echo $this->lang->line('xin_get'); ?></button>
                                    </div>
                                </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (in_array('287', $role_resources_ids)) { ?>
    <?php $leave_categories = $user[0]->leave_categories; ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <?php $leaave_cat = get_employee_leave_category($leave_categories, $session['user_id']); ?>
    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_leave'); ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_leave', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('_user' => $session['user_id']); ?>
                    <?php echo form_open('admin/timesheet/add_leave', $attributes, $hidden); ?>
                    <?php $leaave_cat = get_employee_leave_category($leave_categories, $session['user_id']);

                    ?>
                    <div class="bg-white">
                        <div class="box-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php $role_resources_ids = $this->Xin_model->user_role_resource();
                                    if ($user_info[0]->user_role_id == 1) { ?>
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="first_name"><?php echo $this->lang->line('left_company'); ?><i class="hrsale-asterisk">*</i></label>
                                                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                                                        <option value=""></option>
                                                        <?php foreach ($get_all_companies as $company) { ?>
                                                            <option value="<?php echo $company->company_id ?>">
                                                                <?php echo $company->name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="employee_ajax">
                                                    <label for="employees" class="control-label"><?php echo $this->lang->line('xin_employee'); ?><i class="hrsale-asterisk">*</i></label>
                                                    <select disabled="disabled" class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $session['user_id']; ?>" />
                                        <input type="hidden" name="company_id" id="company_id" value="<?php echo $user[0]->company_id; ?>" />
                                    <?php } ?>
                                    <div class="form-group" id="get_leave_types">
                                        <label for="leave_type" class="control-label"><?php echo $this->lang->line('xin_leave_type'); ?><i class="hrsale-asterisk">*</i></label>
                                        <select class="form-control" id="leave_type" name="leave_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>">
                                            <option value=""></option>
                                            <?php if ($user_info[0]->user_role_id != 1) { ?>
                                                <?php
                                                $leaves = $this->Timesheet_model->getEmployeeLeave($session['user_id']);
                                                $office_shift = $this->Timesheet_model->read_office_shift_information($user[0]->office_shift_id);
                                                if ($leaave_cat) {
                                                    foreach ($leaves as $l) {
                                                        $applied_leaves = $this->Timesheet_model->getEmployeeAppliedLeaveCount($l->leave_type_id, $l->leave_year, $session['user_id']);
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
                                                        $type = $this->Timesheet_model->getEmployeeLeaveCount($l->leave_type_id, $session['user_id']);
                                                        $previous_year = $this->Employees_model->getEmployeeLeaveCountForLeave($l->leave_type_id, date('Y') - 1, $session['user_id']);
                                                        $current_year = $this->Employees_model->getEmployeeLeaveCountForLeave($l->leave_type_id, date('Y'), $session['user_id']);
                                                        $setting = $this->Xin_model->read_setting_info(1);
                                                        if ($setting[0]->module_prorated_leave == 'yes' && $l->leave_type_id == 22) {
                                                            $type_name = $type->type_name;


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
                                                            // $remaining_leave = $l->no_of_leaves;
                                                            // $type = $this->Timesheet_model->getEmployeeLeaveCount($l->leave_type_id, $session['user_id']);

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
                                                        if ($remaining_leave > 0) {
                                                ?>
                                                            <option value="<?php echo $l->leave_type_id ?>">
                                                                <?php echo $type_name . ' (' . $remaining_leave . ' ' . $this->lang->line('xin_remaining') . ')'; ?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }

                                            ?>



                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date"><?php echo $this->lang->line('xin_start_date'); ?><i class="hrsale-asterisk">*</i></label>
                                                <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date'); ?>" readonly name="start_date" type="text" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date"><?php echo $this->lang->line('xin_end_date'); ?><i class="hrsale-asterisk">*</i></label>
                                                <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date'); ?>" readonly name="end_date" type="text" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description"><?php echo $this->lang->line('xin_remarks'); ?></label>
                                        <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_remarks'); ?>" name="remarks" rows="5"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" class="minimal" value="1" id="leave_half_day" name="leave_half_day">
                                            <?php echo $this->lang->line('xin_hr_leave_half_day'); ?></span> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <fieldset class="form-group">
                                            <label for="attachment"><?php echo $this->lang->line('xin_attachment'); ?></label>
                                            <input type="file" class="form-control-file" id="attachment" name="attachment">
                                            <small><?php echo $this->lang->line('xin_leave_file_type'); ?></small>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="summary"><?php echo $this->lang->line('xin_leave_reason'); ?>
                                    <!-- <i class="hrsale-asterisk">*</i> -->
                                </label>
                                <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_leave_reason'); ?>" name="reason" cols="30" rows="3" id="reason"></textarea>
                            </div>
                            <div class="form-actions box-footer">
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                                    <?php echo $this->lang->line('xin_save'); ?> </button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($xuser_info[0]->user_role_id == 1) { ?>
    <div class="box <?php echo $get_animate; ?>">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_list_all'); ?>
                <?php echo $this->lang->line('left_leave'); ?></h3>
            <?php if ($xuser_info[0]->user_role_id == 1) { ?><div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hrsale" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-filter"></span>
                            <?php echo $this->lang->line('xin_filter'); ?></button>
                    </a> </div><?php } ?>
        </div>
        <div class="box-body">
            <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('xin_action'); ?></th>
                            <th width="300"><?php echo $this->lang->line('xin_leave_type'); ?></th>
                            <th><?php echo $this->lang->line('left_department'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee'); ?></th>
                            <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_leave_duration'); ?></th>
                            <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_applied_on'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs (Pulled to the right) -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1-1" data-toggle="tab"><?php echo $this->lang->line('xin_list_all'); ?>
                            <?php echo $this->lang->line('xin_my_leave'); ?></a></li>
                    <?php if ($reports_to > 0) { ?>
                        <li><a href="#tab_2-2" data-toggle="tab"><?php echo $this->lang->line('xin_my_team'); ?>
                                <?php echo $this->lang->line('left_leave'); ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1-1">
                        <div class="box <?php echo $get_animate; ?>">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
                                    <?php echo $this->lang->line('left_leave'); ?> </h3>
                            </div>
                            <div class="box-body">
                                <div class="box-datatable table-responsive">
                                    <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('xin_action'); ?></th>
                                                <th width="300"><?php echo $this->lang->line('xin_leave_type'); ?></th>
                                                <th><?php echo $this->lang->line('left_department'); ?></th>
                                                <th><?php echo $this->lang->line('xin_employee'); ?></th>
                                                <th><i class="fa fa-calendar"></i>
                                                    <?php echo $this->lang->line('xin_leave_duration'); ?></th>
                                                <th><i class="fa fa-calendar"></i>
                                                    <?php echo $this->lang->line('xin_applied_on'); ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2-2">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('xin_my_team'); ?>
                                <?php echo $this->lang->line('left_leave'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="box-datatable table-responsive">
                                <table class="datatables-demo table table-striped table-bordered" id="xin_my_team_table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('xin_action'); ?></th>
                                            <th width="300"><?php echo $this->lang->line('xin_leave_type'); ?></th>
                                            <th><?php echo $this->lang->line('left_department'); ?></th>
                                            <th><?php echo $this->lang->line('xin_employee'); ?></th>
                                            <th><i class="fa fa-calendar"></i>
                                                <?php echo $this->lang->line('xin_leave_duration'); ?></th>
                                            <th><i class="fa fa-calendar"></i>
                                                <?php echo $this->lang->line('xin_applied_on'); ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
<?php } ?>

