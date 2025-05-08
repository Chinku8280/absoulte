<?php
/* Leave Detail view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $user = $this->Xin_model->read_user_info($session['user_id']); ?>
<?php
$datetime1 = new DateTime($from_date);
$datetime2 = new DateTime($to_date);
$datetime2->modify('+1 day');
// $interval = $datetime1->diff($datetime2);

// if(strtotime($from_date) == strtotime($to_date)){
// 	$no_of_days =1;
// } else {
// 	$no_of_days = $interval->format('%a') +1;
// }

// new logic by debasis
$user_info = $this->Xin_model->read_user_info($employee_id);
$office_shift = $this->Timesheet_model->read_office_shift_information($user_info[0]->office_shift_id);
$interval = new DateInterval('P1D');
$period = new DatePeriod($datetime1, $interval, $datetime2);
// add new logic by debasis

$no_of_days = array();
foreach ($period as $d) {
    $p_day = $d->format('l');
    if ($p_day == 'Monday') {
        if ($office_shift[0]->monday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    } else if ($p_day == 'Tuesday') {
        if ($office_shift[0]->tuesday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    } else if ($p_day == 'Wednesday') {
        if ($office_shift[0]->wednesday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    } else if ($p_day == 'Thursday') {
        if ($office_shift[0]->thursday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    } else if ($p_day == 'Friday') {
        if ($office_shift[0]->friday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    } else if ($p_day == 'Saturday') {
        if ($office_shift[0]->saturday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    } else if ($p_day == 'Sunday') {
        if ($office_shift[0]->sunday_in_time != '') {
            $no_of_days[] = $d->format('Y-m-d');
        }
    }
}

$no_of_days = count($no_of_days);

// end new logic by debasis

$leave_user = $this->Xin_model->read_user_info($employee_id);

//department head
$department = $this->Department_model->read_department_information($user[0]->department_id);
?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<div class="row m-b-1">
    <div class="col-md-4">
        <section id="decimal">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_leave_detail'); ?> </h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive" data-pattern="priority-columns">
                                <table class="table table-striped m-md-b-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="border-top:0px;">
                                                <?php echo $this->lang->line('xin_employee'); ?></th>
                                            <td class="text-right"><?php echo $full_name; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" style="border-top:0px;">
                                                <?php echo $this->lang->line('left_department'); ?></th>
                                            <td class="text-right"><?php echo $department_name; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo $this->lang->line('xin_leave_type'); ?></th>
                                            <td class="text-right"><?php echo $type; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo $this->lang->line('xin_applied_on'); ?></th>
                                            <td class="text-right">
                                                <?php echo $this->Xin_model->set_date_format($created_at); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo $this->lang->line('xin_start_date'); ?></th>
                                            <td class="text-right">
                                                <?php echo $this->Xin_model->set_date_format($from_date); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo $this->lang->line('xin_end_date'); ?></th>
                                            <td class="text-right">
                                                <?php echo $this->Xin_model->set_date_format($to_date); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo $this->lang->line('xin_attachment'); ?></th>
                                            <td class="text-right">
                                                <?php if ($leave_attachment != '' && $leave_attachment != 'NULL'): ?>
                                                    <a
                                                        href="<?php echo site_url() ?>admin/download?type=leave&filename=<?php echo $leave_attachment; ?>"><?php echo $this->lang->line('xin_download'); ?></a>
                                                <?php else: ?>

                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo $this->lang->line('xin_hrsale_total_days'); ?>
                                            </th>
                                            <td class="text-right">
                                                <?php
                                                if ($is_half_day == 1) {
                                                    $leave_day_info = $this->lang->line('xin_hr_leave_half_day');
                                                } else {
                                                    $leave_day_info = $no_of_days;
                                                }
                                                echo $leave_day_info; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div
                                    class="bs-callout-success callout-border-left callout-square callout-transparent mt-1 p-1">
                                    <?php echo $reason; ?> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
    // reports to 
    $reports_to = get_reports_team_data($session['user_id']);
    if (($user[0]->user_role_id == 1 || $reports_to > 0) && ($eemployee_id != $session['user_id'])) { ?>
        <div class="col-md-4">
            <section id="decimal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_update_status'); ?> </h3>
                            </div>
                            <div class="box-body">
                                <?php $attributes = array('name' => 'update_status', 'id' => 'update_status', 'autocomplete' => 'off'); ?>
                                <?php $hidden = array('user_id' => $session['user_id'], '_token_status' => $leave_id); ?>
                                <?php echo form_open('admin/timesheet/update_leave_status/' . $leave_id, $attributes, $hidden); ?>
                                <input type="hidden" name="employee_id" value="<?php echo $eemployee_id; ?>">
                                <input type="hidden" name="leave_type" value="<?php echo $leave_type_id; ?>">
                                <input type="hidden" name="no_of_leave" value="<?php echo $leave_day_info; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="status"><?php echo $this->lang->line('dashboard_xin_status'); ?></label>
                                            <select class="form-control" name="status" data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('dashboard_xin_status'); ?>">
                                                <option value="1" <?php if ($status == '1'): ?> selected <?php endif; ?>>
                                                    <?php echo $this->lang->line('xin_pending'); ?></option>
                                                <option value="2" <?php if ($status == '2'): ?> selected <?php endif; ?>>
                                                    <?php echo $this->lang->line('xin_approved'); ?></option>
                                                <option value="3" <?php if ($status == '3'): ?> selected <?php endif; ?>>
                                                    <?php echo $this->lang->line('xin_rejected'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks"><?php echo $this->lang->line('xin_remarks'); ?></label>
                                            <textarea class="form-control textarea"
                                                placeholder="<?php echo $this->lang->line('xin_remarks'); ?>" name="remarks"
                                                id="remarks" cols="30" rows="5"><?php echo $remarks; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions box-footer">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                                        <?php echo $this->lang->line('xin_save'); ?> </button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    <?php } ?>
    <div class="col-md-4">
        <section id="decimal">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_last_taken_leave_title'); ?> </h3>
                        </div>
                        <div class="box-body">
                            <div class="box-block card-dashboard">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table class="table table-striped m-md-b-0">
                                        <tbody>
                                            <?php $show_last_leave = $this->Timesheet_model->employee_show_last_leave($employee_id, $leave_id); ?>
                                            <?php foreach ($show_last_leave as $last_leave) { ?>
                                                <?php
                                                // get leave types
                                                $type = $this->Timesheet_model->read_leave_type_information($last_leave->leave_type_id);
                                                if (!is_null($type)) {
                                                    $type_name = $type[0]->type_name;
                                                } else {
                                                    $type_name = '--';
                                                }
                                                $datetime1 = new DateTime($last_leave->from_date);
                                                $datetime2 = new DateTime($last_leave->to_date);
                                                $interval = $datetime1->diff($datetime2);

                                                if (strtotime($last_leave->from_date) == strtotime($last_leave->to_date)) {
                                                    $last_leave_no_of_days = 1;
                                                } else {
                                                    $last_leave_no_of_days = $interval->format('%a') + 1;
                                                }
                                                if ($last_leave->is_half_day == 1) {
                                                    $last_leave_day_info = $this->lang->line('xin_hr_leave_half_day');
                                                } else {
                                                    $last_leave_day_info = $last_leave_no_of_days;
                                                }
                                                ?>
                                                <tr>
                                                    <th scope="row"><?php echo $this->lang->line('xin_leave_type'); ?></th>
                                                    <td class="text-right"><?php echo $type_name; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><?php echo $this->lang->line('xin_applied_on'); ?></th>
                                                    <td class="text-right">
                                                        <?php echo $this->Xin_model->set_date_format($last_leave->created_at); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><?php echo $this->lang->line('xin_hrsale_total_days'); ?>
                                                    </th>
                                                    <td class="text-right"><?php echo $last_leave_day_info; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="decimal">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_leave_statistics'); ?> </h3>
                        </div>
                        <div class="box-body">
                            <div class="box-block card-dashboard">
                                <?php if ($employee_leaves) {
                                    foreach ($employee_leaves as $eml) {

                                        // $edays_per_year = $type->days_per_year;
                                        //   $edays_per_year = $eml->no_of_leaves;
                                        $edays_per_year = $eml->balance_leave_check;
                                        $count_l = $eml->leaves_taken_count;
                                        $count_data = $count_l / $edays_per_year * 100;

                                        // progress
                                        if ($count_data <= 20) {
                                            $progress_class = 'progress-success';
                                        } else if ($count_data > 20 && $count_data <= 50) {
                                            $progress_class = 'progress-info';
                                        } else if ($count_data > 50 && $count_data <= 75) {
                                            $progress_class = 'progress-warning';
                                        } else {
                                            $progress_class = 'progress-danger';
                                        }

                                ?>
                                        <div id="leave-statistics">
                                            <p><strong><?php echo $eml->type_name; ?>
                                                    (<?php echo $count_l; ?>/<?php echo $edays_per_year; ?>)</strong></p>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar" style="width: <?php echo $count_data; ?>%;"></div>
                                            </div>
                                    <?php }
                                } ?>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<style type="text/css">
    .trumbowyg-editor {
        min-height: 110px !important;
    }
</style>