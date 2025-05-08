<?php $session = $this->session->userdata('username'); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $system = $this->Xin_model->read_setting_info(1); ?>

<div class="box mb-4 <?php echo $get_animate; ?>">
  <div id="accordion">
    <div class="box-header  with-border">
      <h3 class="box-title">Generate CPF Submission File</h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
          <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span> Create New</button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'cpf_submission_form', 'id' => 'cpf_submission_form', 'class' => 'm-b-1 add form-hrm'); ?>
        <?php $hidden = array('user_id' => $session['user_id']); ?>
        <?php echo form_open('admin/efiling/cpf_submission', $attributes, $hidden); ?>
        <div class="form-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="csn">Select Company</label>
                    <select class="form-control" name="company_id" id="filter_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                      <option value="">Select</option>
                      <?php foreach ($get_all_companies as $company) { ?>
                        <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                </div>


                <div class="col-md-4">
                  <div class="form-group">
                    <label for="csn">CPF Submission Number</label>
                    <!-- <input class="form-control" placeholder="CPF Submission Number" readonly id="csn" name="csn" type="text" value="<?php if ($efiling) echo $efiling->csn ?>"> -->
                    <input class="form-control" placeholder="CPF Submission Number" readonly id="csn" name="csn" type="text" id="csn">
                  
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="month_year"><?php echo $this->lang->line('xin_select_month'); ?></label>
                    <input class="form-control month_year" placeholder="<?php echo $this->lang->line('xin_select_month'); ?>" readonly id="month_year" name="month_year" type="text" value="<?php echo date('m-Y'); ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> Generate')); ?> </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<div class="box <?php echo $get_animate; ?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> CPF Submissions </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="cpf_table">
        <thead>
          <tr>
            <th style="width:80px;"><?php echo $this->lang->line('xin_action'); ?></th>
            <th width="200">Month Year</th>
            <th>Company Name</th>
            <th width="200">CSN</th>
            <th>No. of Employees</th>
            <th>No. of Records</th>
            <th>Total Amount</th>
            <th>Date Created</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>