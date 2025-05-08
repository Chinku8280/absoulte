<style>
/* HTML: <div class="loader"></div> */
.loader {
  width: 28px;
  aspect-ratio: 1;
  border-radius: 50%;
  background: #F10C49;
  animation: l2 1.5s infinite;
}
@keyframes l2 {
  0%,
  100%{transform:translate(-35px);box-shadow:  0     0 #F4DD51, 0     0 #E3AAD6}
  40% {transform:translate( 35px);box-shadow: -15px  0 #F4DD51,-30px  0 #E3AAD6}
  50% {transform:translate( 35px);box-shadow:  0     0 #F4DD51, 0     0 #E3AAD6}
  90% {transform:translate(-35px);box-shadow:  15px  0 #F4DD51, 30px  0 #E3AAD6}
}
</style>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['leave_id']) && $_GET['data'] == 'leave') {
?>
  <?php $session = $this->session->userdata('username'); ?>
  <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_leave'); ?></h4>
  </div>
  <?php $attributes = array('name' => 'edit_leave', 'id' => 'edit_leave', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
  <?php $hidden = array('_method' => 'EDIT', '_token' => $leave_id, 'ext_name' => $leave_id); ?>
  <?php echo form_open('admin/timesheet/edit_leave/' . $leave_id, $attributes, $hidden); ?>
  <?php $session = $this->session->userdata('username'); ?>
  <?php $user = $this->Xin_model->read_employee_info($session['user_id']); ?>
  <?php ?>
  <!-- <?php $leave_categories = $user[0]->leave_categories; ?> -->
  <?php $leaave_cat = get_employee_leave_category($leave_categories, $session['user_id']); ?>
  <?php if ($user[0]->user_role_id == 1) { ?>
    <?php $result = $this->Department_model->ajax_company_employee_info($company_id); ?>
  <?php } else { ?>
    <?php $dep_data = $this->Xin_model->get_company_department_employees($company_id); ?>
    <?php $result = $this->Xin_model->get_department_employees($user[0]->department_id); ?>
    <?php } ?><?php  ?>
    <div class="modal-body">
      <div class="row">
        <!-- <div class="col-md-12">
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_remarks'); ?></label>
          <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_remarks'); ?>" name="remarks" cols="30" rows="3"><?php echo $remarks; ?></textarea>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="reason"><?php echo $this->lang->line('xin_leave_reason'); ?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_leave_reason'); ?>" name="reason" cols="30" rows="3" id="reason"><?php echo $reason; ?></textarea>
        </div>
      </div> -->

        <div class="col-md-6">
          <?php $role_resources_ids = $this->Xin_model->user_role_resource();

          if ($user_info[0]->user_role_id == 1) { ?>
            <div class="row">
              <div class="col-md-6">

                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company'); ?><i class="hrsale-asterisk">*</i></label>
                  <select class="form-control" name="company_id" id="aj_company_edit" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                    <option value=""></option>
                    <?php foreach ($get_all_companies as $company) { ?>
                      <option value="<?php echo $company->company_id ?>" <?php echo $company_id == $company->company_id ? 'selected' : '' ?>>
                        <?php echo $company->name ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" id="employee_ajax_edit">
                  <label for="employees" class="control-label"><?php echo $this->lang->line('xin_employee'); ?><i class="hrsale-asterisk">*</i></label>
                  <select class="form-control" name="employee_id" id="employee_id_edit" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                    <option value=""></option>
                    <?php foreach ($result as $employee) { ?>
                      <option value="<?php echo $employee->user_id; ?>" <?php echo $employee_id == $employee->user_id ? 'selected' : '' ?>> <?php echo $employee->first_name . ' ' . $employee->last_name; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <input type="hidden" name="employee_id" id="employee_id_edit" value="<?php echo $session['user_id']; ?>" />
            <input type="hidden" name="company_id" id="company_id_edit" value="<?php echo $user[0]->company_id; ?>" />
          <?php } ?>


          <div class="form-group get_leave_types">
            <label for="leave_type" class="control-label"><?php echo $this->lang->line('xin_leave_type'); ?><i class="hrsale-asterisk">*</i></label>
            <select class="form-control " name="leave_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>">
              <?php
              $leaves = $this->Timesheet_model->getEmployeeLeave($employee_id);
              $office_shift = $this->Timesheet_model->read_office_shift_information($user[0]->office_shift_id);
              if ($leaves) {
                foreach ($leaves as $l) {
                  $applied_leaves = $this->Timesheet_model->getEmployeeAppliedLeaveCount($l->leave_type_id, $l->leave_year, $employee_id);

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
                  $type = $this->Timesheet_model->getEmployeeLeaveCount($l->leave_type_id, $employee_id);
                  $previous_year = $this->Employees_model->getEmployeeLeaveCountForLeave($l->leave_type_id, date('Y') - 1, $employee_id);
                  $current_year = $this->Employees_model->getEmployeeLeaveCountForLeave($l->leave_type_id, date('Y'), $employee_id);
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


                  if ($remaining_leave > 0) {
              ?>
                    <option value="<?php echo $l->leave_type_id ?>" <?php echo $leave_type_id == $l->leave_type_id ? 'selected' : '' ?>>
                      <?php echo $type_name . ' (' . $remaining_leave . ' ' . $this->lang->line('xin_remaining') . ')'; ?></option>
              <?php
                  }
                }
              }
              ?>
            </select>
          </div>



          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date"><?php echo $this->lang->line('xin_start_date'); ?><i class="hrsale-asterisk">*</i></label>
              <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date'); ?>" readonly name="start_date" type="text" value="<?php echo $from_date ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date"><?php echo $this->lang->line('xin_end_date'); ?><i class="hrsale-asterisk">*</i></label>
              <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date'); ?>" readonly name="end_date" type="text" value="<?php echo $to_date ?>">
            </div>
          </div>

        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="description"><?php echo $this->lang->line('xin_remarks'); ?></label>
            <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_remarks'); ?>" name="remarks" rows="5">
            <?php echo $remarks; ?>
            </textarea>
          </div>
          <div class="form-group">
            <label>
              <input type="checkbox" class="minimal" value="1" id="leave_half_day" name="leave_half_day" <?php echo $is_half_day == 1 ? 'checked' : '' ?>>
              <?php echo $this->lang->line('xin_hr_leave_half_day'); ?></span> </label>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <fieldset class="form-group">
              <label for="attachment"><?php echo $this->lang->line('xin_attachment'); ?></label>
              <input type="file" class="form-control-file" id="attachment" name="attachment">
              <small><?php echo $this->lang->line('xin_leave_file_type'); ?></small>
            </fieldset>
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group ">
            <label for="summary"><?php echo $this->lang->line('xin_leave_reason'); ?>
              <!-- <i class="hrsale-asterisk">*</i> -->
            </label>
            <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_leave_reason'); ?>" name="reason" cols="30" rows="3" id="reason">
              <?php echo $reason; ?>
            </textarea>
          </div>
        </div>




      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
      <button type="submit" class="btn btn-primary">
      <div class="loader" style="display: none;"></div>
        <?php echo $this->lang->line('xin_update'); ?>
      </button>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
      $(document).ready(function() {

        $('.date').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd-mm-yy',
          yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
        });


        jQuery("#aj_company_edit").change(function() {
          jQuery.get(base_url + "/get_leave_employees/" + jQuery(this).val(), function(data, status) {
            jQuery('#employee_ajax_edit').html(data);
          });
        });
        //employee_id
        //filter
        jQuery("#aj_companyf_edit").change(function() {
          jQuery.get(site_url + "payroll/get_employees/" + jQuery(this).val(), function(data, status) {
            jQuery('#employee_ajaxf_edit').html(data);
          });
        });




        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
          width: '100%'
        });
        // jQuery("#ajx_company").change(function() {
        //   jQuery.get(base_url + "/get_update_employees/" + jQuery(this).val(), function(data, status) {
        //     jQuery('#employee_ajx').html(data);
        //   });
        // });
        $('#remarks2').trumbowyg();
        // Date
        $('.e_date').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'yy-mm-dd',
          yearRange: '1900:' + (new Date().getFullYear() + 15),
        });
        /* Edit*/
        $("#edit_leave").submit(function(e) {
          /*Form Submit*/
          e.preventDefault();
          var fd = new FormData(this);
          var obj = $(this),
            action = obj.attr('name');
          fd.append("is_ajax", 2);
          fd.append("edit_type", 'leave');
          fd.append("form", action);
          $('.icon-spinner3').show();
          $('.save').prop('disabled', true);
          $.ajax({
            // type: "POST",
            // url: e.target.action,
            // // data: obj.serialize() + "&is_ajax=2&edit_type=leave&form=" + action,
            // data: fd,
            // cache: false,
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
              $('.loader').css('display','');
            },
            success: function(JSON) {
              if (JSON.error != '') {
                $('.loader').css('display','');
                toastr.error(JSON.error);
                $('.icon-spinner3').hide();
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                $('.save').prop('disabled', false);
              } else {
                $('.loader').css('display','none');
                $('.icon-spinner3').hide();
                $('.edit-modal-data').modal('toggle');
                var xin_table = $('#xin_table').dataTable({
                  "bDestroy": true,
                  "ajax": {
                    url: "<?php echo site_url("admin/timesheet/leave_list") ?>",
                    type: 'GET'
                  },
                  dom: 'lBfrtip',
                  "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
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
  <?php } else if (isset($_GET['jd']) && isset($_GET['leave_id']) && $_GET['data'] == 'view_leave') {
  ?>
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view'); ?> <?php echo $this->lang->line('left_leave'); ?></h4>
    </div>
    <form class="m-b-1">
      <div class="modal-body">
        <table class="footable-details table table-striped table-hover toggle-circle">
          <tbody>
            <tr>
              <th><?php echo $this->lang->line('module_company_title'); ?></th>
              <td style="display: table-cell;"><?php foreach ($get_all_companies as $company) { ?>
                  <?php if ($company_id == $company->company_id): ?>
                    <?php echo $company->name; ?>
                  <?php endif; ?>
                <?php } ?></td>
            </tr>
            <?php $employee = $this->Xin_model->read_user_info($employee_id); ?>
            <?php if (!is_null($employee)): ?><?php $eName = $employee[0]->first_name . ' ' . $employee[0]->last_name; ?>
            <?php else: ?><?php $eName = ''; ?><?php endif; ?>
            <tr>
              <th><?php echo $this->lang->line('xin_employee'); ?></th>
              <td style="display: table-cell;"><?php echo $eName; ?></td>
            </tr>
            <tr>
              <th><?php echo $this->lang->line('xin_leave_type'); ?></th>
              <td style="display: table-cell;"><?php foreach ($all_leave_types as $type) { ?>
                  <?php if ($type->leave_type_id == $leave_type_id): ?> <?php echo $type->type_name; ?> <?php endif; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <th><?php echo $this->lang->line('xin_start_date'); ?></th>
              <td style="display: table-cell;"><?php echo $this->Xin_model->set_date_format($from_date); ?></td>
            </tr>
            <tr>
              <th><?php echo $this->lang->line('xin_end_date'); ?></th>
              <td style="display: table-cell;"><?php echo $this->Xin_model->set_date_format($to_date); ?></td>
            </tr>
            <tr>
              <th><?php echo $this->lang->line('xin_remarks'); ?></th>
              <td style="display: table-cell;"><?php echo html_entity_decode($remarks); ?></td>
            </tr>
            <tr>
              <th><?php echo $this->lang->line('xin_leave_reason'); ?></th>
              <td style="display: table-cell;"><?php echo html_entity_decode($reason); ?></td>
            </tr>
            <?php if ($status == '1'): ?> <?php $status_lv = $this->lang->line('xin_pending'); ?> <?php endif; ?>
            <?php if ($status == '2'): ?> <?php $status_lv = $this->lang->line('xin_approved'); ?> <?php endif; ?>
            <?php if ($status == '3'): ?> <?php $status_lv = $this->lang->line('xin_rejected'); ?> <?php endif; ?>
            <tr>
              <th><?php echo $this->lang->line('dashboard_xin_status'); ?></th>
              <td style="display: table-cell;"><?php echo $status_lv; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
      </div>
      <?php echo form_close(); ?>
    <?php } ?>