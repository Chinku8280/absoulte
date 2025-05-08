<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource();?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $system = $this->Xin_model->read_setting_info(1);?>

<div class="row">
  <div class="col-md-4">
    <div class="box mb-4 <?php echo $get_animate; ?>">
      <div class="box-header  with-border">
        <h3 class="box-title">IR8A YA (Year of Assessment)</h3>
      </div>

      <div class="box-body">
        
        <div class="form-body">
          <div class="row">

          <div class="col-md-12">
              <div class="form-group">
                  <label for="year">Select Company</label>
                  <select class="form-control" name="company" id="company" data-plugin="select_hrm" data-placeholder="company">
                    <option value=""></option>
                    <?php foreach($this->Company_model->get_companies()->result() as $list){ ?>
                      <option value="<?php echo $list->company_id?>" <?php echo $company_id == $list->company_id ? 'selected' : '' ?>><?php echo $list->name?></option>
                      <?php } ?>
                  </select>
              </div>

            </div>

            <div class="col-md-12">
              <div class="form-group">
                  <label for="year">Select Year</label>
                  <select class="form-control" name="year" id="year" data-plugin="select_hrm" data-placeholder="Assessment Year" data-csrf-name="<?php echo $this->security->get_csrf_token_name(); ?>" data-csrf-hash="<?php echo $this->security->get_csrf_hash(); ?>" data-user-id="<?php echo $session['user_id'] ?>">
                    <option value=""></option>
                    <?php for ($ay = 6; $ay > 0; $ay -= 1) {?>
                      <?php $ayear = date('Y', strtotime("-$ay year"));?>
                      <option value="<?php echo $ayear; ?>" <?php if ($year_a == $ayear) { echo 'selected'; }?>><?php echo $ayear; ?></option>
                    <?php }?>
                    <option value="<?php echo date('Y'); ?>" <?php if ($year_a == date('Y')) { echo 'selected';}?>><?php echo date('Y'); ?></option>
                    <option value="<?php echo date('Y', strtotime('1 year')); ?>" <?php if ($year_a == date('Y', strtotime('1 year'))) { echo 'selected';}?>><?php echo date('Y', strtotime('1 year')); ?></option>
                </select>
              </div>

            </div>
          </div>
        </div>

        <div class="form-actions box-footer">
          <?php if ($is_generated) {?>
          <div id="reset_cont">
            <div class="row">
              <!-- <div class="col-md-6">
                <?php $file_url = base_url() . str_replace('./', '', $is_generated->ir8a_file);?>
                <a href="<?php echo $file_url ?>" download>Download XML File</a>
              </div> -->
              <div class="col-md-6">
                <?php 
                $final_year = $year_a-1;

                $company_info = $this->Xin_model->read_company_info($company_id);

                $file_url = base_url() . "/uploads/".$company_info[0]->name." - "." IR8A YA".$final_year.".xlsx"?>
                <a href="<?php echo $file_url ?>" download>Download Excel File</a>
              </div>
              <div class="col-md-6">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger waves-effect waves-light delete" data-toggle="modal" data-target="#if8aResetModal" data-record-id="">Reset IR8A for <span class="fy_label"><?php echo $year_a ?><span></button>

              </div>
            </div>

          </div>
          <?php } else {?>
          <div id="generate_cont">
            <?php $attributes = array('name' => 'ir8a_generate_form', 'id' => 'ir8a_generate_form', 'class' => 'm-b-1 add form-hrm');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/efiling/generateIr8a', $attributes, $hidden); ?>
            <input type="hidden" name="year" id="gen_fy" value="<?php echo $year_a ?>">
            <input type="hidden" name="company" id="gen_company" value="<?php echo $company_id ?>">

            <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => 'Generate IR8A for <span class="fy_label">' . $year_a . '<span>')); ?>
            <?php echo form_close(); ?>
          </div>
          <?php }?>

        </div>

      </div>


    </div>
  </div>

  <div class="col-md-8">
    <div class="box <?php echo $get_animate; ?>">
      <div class="box-header with-border">
          <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Employee Summary for <span class="fy_label"><?php echo $year_a ?></span> </h3>
      </div>
      <div class="box-body">
          <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table_employee_summary" style="width:100%;">
              <thead>
                  <tr>
                      <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                      <th>Citizenship</th>
                      <th>Total Taxable Amount</th>
                      <th>YTD Gross Salary</th>
                      <th>YTD Bonus</th>
                      <th>YTD CPF</th>
                  </tr>
              </thead>
          </table>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box <?php echo $get_animate; ?>">
      <div class="box-header with-border">
          <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Employee IR8A form for <span class="fy_label"><?php echo $year_a ?></span> </h3>
      </div>
      <div class="box-body">
          <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table_employee_ir8a_form" style="width:100%;">
              <thead>
                  <tr>
                    <th style="width:80px;"><?php echo $this->lang->line('xin_action'); ?></th>
                    <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                    <th>Gross Salay Amount</th>
                    <th>Bonus Amount</th>
                    <th>Director Fees</th>
                    <th>Total (D1 to D9)</th>
                    <th>CPF Amount (Employee)</th>
                  </tr>
              </thead>
          </table>
          </div>
      </div>
    </div>
  </div>
</div>

<?php if ($is_generated) {?>
<!-- Modal -->
<div class="modal fade" id="if8aResetModal" tabindex="-1" role="dialog" aria-labelledby="if8aResetModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="if8aResetModalLabel">Reset IR8A to Start Over</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <h4>Are you sure you want to reset IR8A for <?php echo $year_a ?>?</h4>
        <p><b>Note: </b>Resetting form IR8A might reset other forms as well.</p>
        <?php $attributes = array('name' => 'ir8a_reset_form', 'id' => 'ir8a_reset_form', 'class' => 'm-b-1 add form-hrm');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/efiling/resetIr8a', $attributes, $hidden); ?>
        <input type="hidden" name="year" value="<?php echo $year_a ?>">
        <input type="hidden" name="company_id" value="<?php echo $company_id ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => 'btn btn-danger', 'content' => 'Reset IR8A')); ?>
      </div>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php }?>
