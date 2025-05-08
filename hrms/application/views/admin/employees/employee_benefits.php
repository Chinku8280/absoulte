<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>

<?php if (in_array('429', $role_resources_ids)) { ?>
    <div class="row match-heights">
        <div class="col-lg-3 col-md-3 <?php echo $get_animate; ?>">

            <div class="box">
                <div class="box-blocks">
                    <div class="list-group">
                        <a class="list-group-item list-group-item-action nav-tabs-link active" href="#accommodation"
                            data-constant="1" data-constant-block="accommodation" data-toggle="tab" aria-expanded="true"
                            id="constant_1">Accommodation </a>

                        <a class="list-group-item list-group-item-action nav-tabs-link" href="#accommodation_employee"
                            data-constant="2" data-constant-block="accommodation_employee" data-toggle="tab"
                            aria-expanded="true" id="constant_2">Accommodate Employee</a>

                        <a class="list-group-item list-group-item-action nav-tabs-link" href="#utilities" data-constant="3"
                            data-constant-block="utilities" data-toggle="tab" aria-expanded="true" id="constant_3">Utilities
                            & Accessories</a>

                        <a class="list-group-item list-group-item-action nav-tabs-link" href="#driver" data-constant="4"
                            data-constant-block="driver" data-toggle="tab" aria-expanded="true" id="constant_4">Driver</a>

                        <a class="list-group-item list-group-item-action nav-tabs-link" href="#housekeeping"
                            data-constant="5" data-constant-block="housekeeping" data-toggle="tab" aria-expanded="true"
                            id="constant_5">Housekeeping</a>

                        <a class="list-group-item list-group-item-action nav-tabs-link" href="#hotel_accommodation"
                            data-constant="6" data-constant-block="hotel_accommodation" data-toggle="tab"
                            aria-expanded="true" id="constant_6">Hotel Accommodation</a>

                        <a class="list-group-item list-group-item-action nav-tabs-link" href="#other_benefits"
                            data-constant="7" data-constant-block="other_benefits" data-toggle="tab" aria-expanded="true"
                            id="constant_7">Other Benefits</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="accommodation">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Accommodations </h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_accommodations"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th>Title</th>
                                    <th>Address</th>
                                    <th><i class="fa fa-calendar"></i> Period</th>
                                    <th>Annual Value</th>
                                    <th>Furnished</th>
                                    <th>Rent</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Accommodation</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'accommodation_form', 'id' => 'accommodation_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setaccommodation', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accommodation_title">Accommodation Title<i class="hrsale-asterisk">*</i></label>
                                <input class="form-control" placeholder="Accommodation Title" name="accommodation_title"
                                    type="text" value="" id="accommodation_title">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address_1">Address Line 1
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control" placeholder="Address" name="address_1" type="text" value=""
                                    id="address_1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address_2">Address Line 2</label>
                                <input class="form-control" placeholder="Address" name="address_2" type="text" value=""
                                    id="address_2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="period_from">Period From<i class="hrsale-asterisk">*</i></label>
                                <input class="form-control cont_date" placeholder="Accommodation from" name="period_from"
                                    type="text" value="" id="period_from" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="period_to">Period To
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control cont_date" placeholder="Accommodation from" name="period_to"
                                    type="text" value="" id="period_to" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accommodation_type">Accommodation Type<i class="hrsale-asterisk">*</i></label>
                                <select name="accommodation_type" id="accommodation_type" class="form-control"
                                    data-plugin="select_hrm" data-placeholder="Accommodation Type">
                                    <option value="">Select Accommodation Type</option>
                                    <option value="owned">Owned</option>
                                    <option value="rented">Rented</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" id="annual_value_field">
                            <div class="form-group">
                                <label for="annual_value">Annual Value(AV)<i class="hrsale-asterisk">*</i></label>
                                <input class="form-control" placeholder="Accommodation from" name="annual_value" type="text"
                                    value="" id="annual_value">
                            </div>
                        </div>
                        <div class="col-md-4" id="furnished_field">
                            <div class="form-group">
                                <label for="furnished">Furnished
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select name="furnished" id="furnished" class="form-control" data-plugin="select_hrm"
                                    data-placeholder="Furnished">
                                    <option value="">Select Furnished Type</option>
                                    <option value="1">Fully Furnished</option>
                                    <option value="2">Partially Furnished</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="rent_paid_field">
                            <div class="form-group">
                                <label for="rent_paid">Annual Rent Paid
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control" placeholder="Total Rent Paid in the period" name="rent_paid"
                                    type="text" value="" id="rent_paid">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="accommodation_employee" style="display:none;">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Accommodated Employees </h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered"
                            id="xin_table_employee_accommodation" style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th>Employee</th>
                                    <th>Accommodation</th>
                                    <th><i class="fa fa-calendar"></i> Period</th>
                                    <th>Rent Paid</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Accommodate Employee</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'accommodation_employee_form', 'id' => 'accommodation_employee_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setemployeeaccommodation', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accommodation">Accommodation
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control" name="accommodation" id="aj_accommodation"
                                    data-plugin="select_hrm" data-placeholder="Accommodation" required>
                                    <option value="">Select Accommodation</option>
                                    <?php foreach ($accommodations as $ac) { ?>
                                        <option value="<?php echo $ac->id; ?>"> <?php echo $ac->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input class="form-control" placeholder="Address" name="address" type="text" value=""
                                    id="address" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accommodation_period">Accommodation Period</label>
                                <input class="form-control" placeholder="Accommodation Period" name="accommodation_period"
                                    type="text" value="" id="accommodation_period" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="company">Company
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 employee_ajax">
                            <div class="form-group">
                                <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                                    <i class="hrsale-asterisk">*</i></label>
                                <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employee_acc_from">Accommodation From
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control cont_date" placeholder="Accommodation From"
                                    name="employee_acc_from" type="text" value="" id="employee_acc_from" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employee_acc_to">Accommodation To
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control cont_date" placeholder="Accommodation To" name="employee_acc_to"
                                    type="text" value="" id="employee_acc_to" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employee_rent">Employee Rent Fees</label>
                                <input class="form-control" placeholder="Rent Paid by Employee" name="employee_rent"
                                    type="text" value="" id="employee_rent">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="utilities" style="display:none;">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Utilities & Accessories</h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_utility"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                    <th><i class="fa fa-calendar"></i> Benefit Year</th>
                                    <th>Utility/Accessory</th>
                                    <th>Remark</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Utilities</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'benefit_utilities_form', 'id' => 'benefit_utilities_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setemployeeutilitybenefits', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="company">Company
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 employee_ajax">
                            <div class="form-group">
                                <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="benefits_year">Benefits Year
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control" name="benefits_year" id="benefits_year"
                                    data-plugin="select_hrm" data-placeholder="Benefit Year">
                                    <option value=""></option>
                                    <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                                        <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                                        <option value="<?php echo $ayear; ?>"><?php echo $ayear; ?></option>
                                    <?php } ?>
                                    <option value="<?php echo date('Y'); ?>" selected><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y', strtotime('1 year')); ?>">
                                        <?php echo date('Y', strtotime('1 year')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="utilityCont">
                        <div class="row mt-3" id="utilityDiv">
                            <div class="col-md-2 utAdd">
                                <div class="form-group">
                                    <button type="button"
                                        class="btn icon-btn btn-xs btn-success waves-effect waves-light utAddbtn">Add New
                                        <span class="fa fa-plus"></span></button>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="utility">Utilities & Accessories
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control selectut" name="utility[]" data-plugin="select_hrm"
                                        data-placeholder="Utility" required>
                                        <option value="">Select Utility</option>
                                        <option value="utility">Utilities</option>
                                        <option value="telephone">Telephone</option>
                                        <option value="pager">Pager</option>
                                        <option value="golf_bag_accessories">Golf Bag & Accessories</option>
                                        <option value="camera">Camera</option>
                                        <option value="tablet">Tablet</option>
                                        <option value="laptop">Laptop</option>
                                        <option value="electronic_gadget">Electronic Gadget</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="utility_remark">Remark</label>
                                    <input class="form-control" placeholder="Utility or Accessory Name"
                                        name="utility_remark[]" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="utility_amount">Actual Amount
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input class="form-control" placeholder="Acutal Amount of Utility or Accessory"
                                        name="utility_amount[]" type="text" required>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="driver" style="display:none;">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Driver Wages</h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_driver"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                    <th><i class="fa fa-calendar"></i> Benefit Year</th>
                                    <th>Driver Annual Wage</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Driver</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'benefit_driver_form', 'id' => 'benefit_driver_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setemployeedriverbenefits', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="company">Company
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 employee_ajax">
                            <div class="form-group">
                                <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="benefits_year">Benefits Year
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control" name="benefits_year" id="benefits_year"
                                    data-plugin="select_hrm" data-placeholder="Benefit Year">
                                    <option value=""></option>
                                    <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                                        <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                                        <option value="<?php echo $ayear; ?>"><?php echo $ayear; ?></option>
                                    <?php } ?>
                                    <option value="<?php echo date('Y'); ?>" selected><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y', strtotime('1 year')); ?>">
                                        <?php echo date('Y', strtotime('1 year')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="driver_wage">Driver Annual Wage
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control" placeholder="Annual wage of driver" name="driver_wage"
                                    id="driver_wage" type="text">
                                <small id="driver_wage_help" class="form-text text-muted">Annual wages x (Private / Total
                                    Mileage)</small>
                            </div>
                        </div>
                    </div>


                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="housekeeping" style="display:none;">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Housekeeping Wages</h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_housekeeping"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                    <th><i class="fa fa-calendar"></i> Benefit Year</th>
                                    <th>Service</th>
                                    <th>Remarks</th>
                                    <th>Annul Wage</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Housekeeping</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'benefit_housekeeping_form', 'id' => 'benefit_housekeeping_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setemployeehousekeepingbenefits', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="company">Company
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 employee_ajax">
                            <div class="form-group">
                                <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="benefits_year">Benefits Year
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control" name="benefits_year" id="benefits_year"
                                    data-plugin="select_hrm" data-placeholder="Benefit Year">
                                    <option value=""></option>
                                    <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                                        <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                                        <option value="<?php echo $ayear; ?>"><?php echo $ayear; ?></option>
                                    <?php } ?>
                                    <option value="<?php echo date('Y'); ?>" selected><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y', strtotime('1 year')); ?>">
                                        <?php echo date('Y', strtotime('1 year')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="houseKeepingCont">
                        <div class="row mt-3" id="housekeepingDiv">
                            <div class="col-md-2 utAdd">
                                <div class="form-group">
                                    <button type="button"
                                        class="btn icon-btn btn-xs btn-success waves-effect waves-light utAddbtn">Add New
                                        <span class="fa fa-plus"></span></button>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="housekeeping_service">Housekeeping Service
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control selectut" name="housekeeping_service[]"
                                        data-plugin="select_hrm" data-placeholder="Housekeeping Service" required>
                                        <option value="">Select Service</option>
                                        <option value="servant">Servant</option>
                                        <option value="gardener">Gardener</option>
                                        <option value="upkeep_of_compound">Upkeep of Compound</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="housekeeping_remark">Remark</label>
                                    <input class="form-control" placeholder="Remarks" name="housekeeping_remark[]"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="housekeeping_amount">Annual Wage
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input class="form-control" placeholder="Acutal Amount or Annual wage"
                                        name="housekeeping_amount[]" type="text" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="hotel_accommodation" style="display:none;">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Hotel Accommodation </h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_hotel_accommodation"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th width="100px;"><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                    <th>Hotel</th>
                                    <th><i class="fa fa-calendar"></i> Check In</th>
                                    <th><i class="fa fa-calendar"></i> Check Out</th>
                                    <th>Actual Cost</th>
                                    <th>Employee Paid</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Hotel Accommodation</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'benefit_hotel_accommodation_form', 'id' => 'benefit_hotel_accommodation_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setEmployeeHotelAccommodationBenefits', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="company">Company
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 employee_ajax">
                            <div class="form-group">
                                <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel_name">Hotel Name
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control" placeholder="Hotel Name" name="hotel_name" id="hotel_name"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ht_check_in">Check In Date
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control cont_date" placeholder="Check in date" name="ht_check_in"
                                    id="ht_check_in" type="text" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ht_check_out">Check Out Date
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control cont_date" placeholder="Check Out date" name="ht_check_out"
                                    id="ht_check_out" type="text" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ht_actual_cost">Actual Cost
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input class="form-control" placeholder="Actual Cost" name="ht_actual_cost"
                                    id="ht_actual_cost" type="text">
                                <small id="ht_actual_cost_help" class="form-text text-muted">Actual Cost of Hotel
                                    Accommodation</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ht_employee_paid">Employee Paid Amount</label>
                                <input class="form-control" placeholder="Amount Paid by Employee" name="ht_employee_paid"
                                    id="ht_employee_paid" type="text">
                                <small id="ht_employee_paid_help" class="form-text text-muted">Amount paid by the
                                    employee</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="other_benefits" style="display:none;">
            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Other Benefits </h3>
                </div>
                <div class="box-body">
                    <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_other_benefits"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                    <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                    <th><i class="fa fa-calendar"></i> Benefit Year</th>
                                    <th>Benefit</th>
                                    <th>Remarks</th>
                                    <th>Acutal Cost</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box <?php echo $get_animate; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">Other Benefits</h3>
                </div>
                <div class="box-body pb-2">
                    <?php $attributes = array('name' => 'other_benefit_form', 'id' => 'other_benefit_form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
                    <?php echo form_open('admin/employeebenefits/setEmployeeOtherBenefits', $attributes, $hidden); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="company">Company
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($all_companies as $company) { ?>
                                        <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 employee_ajax">
                            <div class="form-group">
                                <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="benefits_year">Benefits Year
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control" name="benefits_year" id="benefits_year"
                                    data-plugin="select_hrm" data-placeholder="Benefit Year">
                                    <option value=""></option>
                                    <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                                        <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                                        <option value="<?php echo $ayear; ?>"><?php echo $ayear; ?></option>
                                    <?php } ?>
                                    <option value="<?php echo date('Y'); ?>" selected><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y', strtotime('1 year')); ?>">
                                        <?php echo date('Y', strtotime('1 year')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="otherBenefitCont">
                        <div class="row mt-3" id="otherBenefitDiv">
                            <div class="col-md-2 utAdd">
                                <div class="form-group">
                                    <button type="button"
                                        class="btn icon-btn btn-xs btn-success waves-effect waves-light utAddbtn">Add New
                                        <span class="fa fa-plus"></span></button>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="other_benefit">Benefit
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control selectut" name="other_benefit[]" data-plugin="select_hrm"
                                        data-placeholder="Benefit" required>
                                        <option value="">Select Service</option>
                                        <option value="Home Leave Passage & Incidental Benefit">Home Leave Passage &
                                            Incidental Benefit</option>
                                        <option value="Interest Payment">Interest Payment</option>
                                        <option value="Insurance Premiums">Insurance Premiums</option>
                                        <option value="Free or Subsidised Holidays">Free or Subsidised Holidays</option>
                                        <option value="Educational expenses">Educational expenses</option>
                                        <option value="Social or Recreational clubs Fee">Social or Recreational clubs Fee
                                        </option>
                                        <option value="Gains from Assets sold to Employee">Gains from Assets sold to
                                            Employee</option>
                                        <option value="Motor Vehicle cost given to Employee">Motor Vehicle cost given to
                                            Employee</option>
                                        <option value="Car Benefits">Car Benefits</option>
                                        <option value="Other Benefit">Other Benefit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="other_benefit_remark">Remark</label>
                                    <input class="form-control" placeholder="Remarks" name="other_benefit_remark[]"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="other_benefit_cost">Actual Cost
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input class="form-control" placeholder="Acutal cost of benefit"
                                        name="other_benefit_cost[]" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input class="form-checkbox"
                                        name="deductible_from_salary[]" type="checkbox" value="1">
                                        <input class="form-checkbox"
                                        name="deductible_from_salary[]" type="hidden" value="0">
                                    <label for="deductible_from_salary">Deductible from Salary
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions box-footer hrsale-salary-button">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <style>
        @media (min-width: 768px) {
            .utAdd {
                line-height: 80px;
            }
        }
    </style>
<?php } ?>