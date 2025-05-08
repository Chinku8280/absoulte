<?php
/* Employees report view
*/

$company_id = $this->input->post('company_id');
$department_id = $this->input->post('department_id');
$designation_id = $this->input->post('designation_id');
$employee = $this->Reports_model->get_employees_reports($company_id, $department_id, $designation_id);

$departments = $this->Company_model->ajax_company_departments_info($company_id);
$designations = $this->Designation_model->ajax_is_designation_information($department_id);
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $_tasks = $this->Timesheet_model->get_tasks(); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
<div class="row m-b-1 <?php echo $get_animate; ?>">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_hr_report_filters'); ?> </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <?php $attributes = array('name' => 'employee_reports', 'id' => 'employee_reports', 'autocomplete' => 'off', 'class' => 'add form-hrm'); ?>
            <?php $hidden = array('euser_id' => $session['user_id']); ?>
            <!-- <?php echo form_open('admin/reports/employee_reports', $attributes, $hidden); ?> -->
            <?php echo form_open('admin/reports/employees', $attributes, $hidden); ?>
            <?php
            $data = array(
              'name'        => 'user_id',
              'id'          => 'user_id',
              'type'        => 'hidden',
              'value'        => $session['user_id'],
              'class'       => 'form-control',
            );

            echo form_input($data);
            ?>
            <?php if ($user_info[0]->user_role_id == 1) { ?>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                      <option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
                      <?php foreach ($all_companies as $company) { ?>
                        <option value="<?php echo $company->company_id ?>" <?php if (isset($company_id)) : if ($company->company_id == $company_id) : ?> selected="selected" <?php endif;
                                                                                                                                                                          endif; ?>><?php echo $company->name ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } else { ?>
                <?php $ecompany_id = $user_info[0]->company_id; ?>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                      <option value=""><?php echo $this->lang->line('left_company'); ?></option>
                      <?php foreach ($all_companies as $company) { ?>
                        <?php if ($ecompany_id == $company->company_id) : ?>
                          <option value="<?php echo $company->company_id ?>" <?php if (isset($company_id)) : if ($company->company_id == $company_id) : ?> selected="selected" <?php endif;
                                                                                                                                                                            endif; ?>><?php echo $company->name ?></option>
                        <?php endif; ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } ?>
              <div class="col-md-3">
                <div class="form-group" id="department_ajax">
                  <label for="department"><?php echo $this->lang->line('xin_employee_department'); ?></label>
                  <select  class="form-control aj_department_id" name="department_id" id="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department'); ?>">
                    <option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
                    <?php foreach ($departments as $deparment) { ?>
                      <option value="<?php echo $deparment->department_id ?>" <?php if (isset($department_id)) : if ($deparment->department_id == $department_id) : ?> selected="selected" <?php endif;
                                                                                                                                                                                        endif; ?>><?php echo $deparment->department_name ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3" id="designation_ajax">
                <div class="form-group">
                  <label for="designation"><?php echo $this->lang->line('xin_designation'); ?></label>
                  <select  class="form-control" id="designation_id" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation'); ?>">
                    <option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
                    <?php foreach ($designations as $designation) { ?>
                      <option value="<?php echo $designation->designation_id ?>" <?php if (isset($designation_id)) : if ($designation->designation_id == $designation_id) : ?> selected="selected" <?php endif;
                                                                                                                                                                                        endif; ?>><?php echo $designation->designation_name ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="submit">&nbsp;</label><br />
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_get'); ?> </button>
                </div>
              </div>
              </div>
              <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 <?php echo $get_animate; ?>">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_view'); ?> <?php echo $this->lang->line('xin_hr_report_employees'); ?> </h3>
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th><?php echo $this->lang->line('xin_employees_id'); ?></th>
                <th><?php echo $this->lang->line('xin_employees_full_name'); ?></th>
                <th>NRIC/FIN</th>
                <th>Date of Birth</th>
                <th>WP No.</th>
                <th><?php echo $this->lang->line('xin_designation'); ?></th>
                <!-- <th>CSOC</th>
                <th>Date of Expiry</th> -->
                <!-- <th>Shipyard Safety</th>
                <th>Safety Supervisor</th>
                <th>Jurong Island Pass</th>
                <th>Forklift</th>
                <th>Scissor Lift</th>
                <th>Boom Lift</th>
                <th>WSQ</th>
                <th>SEC</th>
                <th>CoreTrade</th>
                <th>Others</th> -->
                <?php $all_document_types = $this->Employees_model->all_document_types();
                foreach ($all_document_types as $document_type) { ?>
                  <th><?php echo $document_type->document_type; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('dashboard_xin_status'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($employee->result() as $key => $r) {
                $key++;
                // get company
                $company = $this->Xin_model->read_company_info($r->company_id);
                if (!is_null($company)) {
                  $comp_name = $company[0]->name;
                } else {
                  $comp_name = '--';
                }

                // user full name 
                $full_name = $r->first_name . ' ' . $r->last_name;
                // get status
                if ($r->is_active == 0) : $status = $this->lang->line('xin_employees_inactive');
                elseif ($r->is_active == 1) : $status = $this->lang->line('xin_employees_active');
                endif;
                // get designation
                $designation = $this->Designation_model->read_designation_information($r->designation_id);
                if (!is_null($designation)) {
                  $designation_name = $designation[0]->designation_name;
                } else {
                  $designation_name = '--';
                }
                // department
                $department = $this->Department_model->read_department_information($r->department_id);
                if (!is_null($department)) {
                  $department_name = $department[0]->department_name;
                } else {
                  $department_name = '--';
                }
              ?>
                <tr>
                  <td><?php echo $key ?></td>
                  <td><?php echo $full_name ?></td>
                  <td><?php echo $r->id_no ?></td>
                  <td><?php echo $r->date_of_birth ?></td>
                  <td><?php echo "" ?></td>
                  <td><?php echo $designation_name ?></td>
                  <?php
                  $all_document_types = $this->Employees_model->all_document_types();
                  foreach ($all_document_types as $document_type) {
                    $document_details = $this->Employees_model->read_document_information_by_document_id($document_type->document_type_id, $r->user_id);
                  ?>
                    <td><?php echo isset($document_details[0]->title) ? $document_details[0]->title . "(" . $document_details[0]->date_of_expiry . ")" : ''  ?></td>
                  <?php
                  }
                  ?>
                  <td><?php echo $status ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>