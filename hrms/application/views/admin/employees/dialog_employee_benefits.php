<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'read_accommodations' && $_GET['type'] == 'read_accommodations') {
?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_accommodations'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_accommodations_info', 'id' => 'update_accommodations_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employeebenefits/update_accommodations_info', $attributes, $hidden); ?>
    <?php
    $edata_usr1 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr1);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="accommodation_title">Accommodation Title<i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="Accommodation Title" name="accommodation_title" type="text"
                        value="<?php echo $accommodation_title ?? '' ?>" id="edit_accommodation_title">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="address_1">Address Line 1
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control" placeholder="Address" name="address_1" type="text"
                        value="<?php echo $address_1 ?? '' ?>" id="edit_address_1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="address_2">Address Line 2</label>
                    <input class="form-control" placeholder="Address" name="address_2" type="text"
                        value="<?php echo $address_2 ?? '' ?>" id="edit_address_2">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="period_from">Period From<i class="hrsale-asterisk">*</i></label>
                    <input class="form-control cont_date" placeholder="Accommodation from" name="period_from" type="text"
                        value="<?php echo $period_from ?? '' ?>" id="edit_period_from" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="period_to">Period To
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control cont_date" placeholder="Accommodation from" name="period_to" type="text"
                        value="<?php echo $period_to ?? '' ?>" id="edit_period_to" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="accommodation_type">Accommodation Type<i class="hrsale-asterisk">*</i></label>
                    <select name="accommodation_type" id="edit_accommodation_type" class="form-control"
                        data-plugin="select_hrm" data-placeholder="Accommodation Type">
                        <option value="">Select Accommodation Type</option>
                        <option value="owned" <?php echo $accommodation_type == 1 ? 'selected' : '' ?>>Owned</option>
                        <option value="rented" <?php echo $accommodation_type == 2 ? 'selected' : '' ?>>Rented
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" id="edit_annual_value_field"
                style="display:<?php echo $accommodation_type == 1 ? '' : 'none' ?> ;">
                <div class="form-group">
                    <label for="annual_value">Annual Value(AV)<i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="Accommodation from" name="annual_value" type="text"
                        value="<?php echo $annual_value ?>" id="edit_annual_value">
                </div>
            </div>
            <div class="col-md-4" id="edit_furnished_field"
                style="display:<?php echo $accommodation_type == 1 ? '' : 'none' ?> ;">
                <div class="form-group">
                    <label for="furnished">Furnished
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select name="furnished" id="edit_furnished" class="form-control" data-plugin="select_hrm"
                        data-placeholder="Furnished">
                        <option value="">Select Furnished Type</option>
                        <option value="1" <?php echo $furnished == 1 ? 'selected' : '' ?>>Fully Furnished</option>
                        <option value="2" <?php echo $furnished == 2 ? 'selected' : '' ?>>Partially Furnished</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4" id="edit_rent_paid_field"
                style="display:<?php echo $accommodation_type == 2 ? '' : 'none' ?> ;">
                <div class="form-group">
                    <label for="rent_paid">Annual Rent Paid
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control" placeholder="Total Rent Paid in the period" name="rent_paid" type="text"
                        value="<?php echo $rent_paid ?>" id="edit_rent_paid">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.cont_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1940:' + (new Date().getFullYear() + 1),
            });

            // $('#edit_annual_value_field').hide();
            // $('#edit_furnished_field').hide();
            // $('#edit_rent_paid_field').hide();
            $('#edit_accommodation_type').on('change', function() {
                var act = this.value;
                console.log(act)
                if (act == 'owned') {
                    $('#edit_annual_value_field').show();
                    $('#edit_furnished_field').show();
                    $('#edit_rent_paid_field').hide();
                } else if (act == 'rented') {
                    $('#edit_annual_value_field').hide();
                    $('#edit_furnished_field').hide();
                    $('#edit_rent_paid_field').show();
                } else {
                    $('#edit_annual_value_field').hide();
                    $('#edit_furnished_field').hide();
                    $('#edit_rent_paid_field').hide();
                }
            });


            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });

            /* Update contact info */
            $("#update_accommodations_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=5&data=update_accommodations_info&type=update_accommodations_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_accommodations = $('#xin_table_accommodations')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employeebenefits/getaccommodation/") ?>",
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            xin_table_accommodations.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'employee_accommodation' && $_GET['type'] == 'employee_accommodation') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('edit_accommodated_employees'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_accommodated_employees_info', 'id' => 'update_accommodated_employees_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employeebenefits/update_accommodated_employees_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr3);
    ?>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="accommodation">Accommodation
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select class="form-control" name="accommodation" id="edit_aj_accommodation" data-plugin="select_hrm"
                        data-placeholder="Accommodation" required>
                        <option value="">Select Accommodation</option>
                        <?php foreach ($accommodations as $ac) { ?>
                            <option value="<?php echo $ac->id; ?>" <?php echo $accommodation_id == $ac->id ? 'selected' : '' ?>>
                                <?php echo $ac->title; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input class="form-control" placeholder="Address" name="address" type="text"
                        value="<?php echo $read_accommodations[0]->address_line_1 ?>" id="edit_address" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="accommodation_period">Accommodation Period</label>
                    <input class="form-control" placeholder="Accommodation Period" name="accommodation_period" type="text"
                        value="<?php echo date('d M Y', strtotime($read_accommodations[0]->period_from)) ?> - <?php echo date('d M Y', strtotime($read_accommodations[0]->period_to)) ?>"
                        id="edit_accommodation_period" readonly>
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
                            <option value="<?php echo $company->company_id; ?>"
                                <?php echo $read_employee[0]->company_id == $company->company_id ? 'selected' : '' ?>>
                                <?php echo $company->name; ?>
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
                        <option value="<?php echo $read_employee[0]->user_id; ?>" selected>
                            <?php echo $read_employee[0]->first_name; ?> <?php echo $read_employee[0]->last_name; ?>
                        </option>
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
                    <input class="form-control cont_date" placeholder="Accommodation From" name="employee_acc_from"
                        type="text" value="<?php echo $period_from; ?>" id="edit_employee_acc_from" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="employee_acc_to">Accommodation To
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control cont_date" placeholder="Accommodation To" name="employee_acc_to" type="text"
                        value="<?php echo $period_to; ?>" id="edit_employee_acc_to" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="employee_rent">Employee Rent Paid</label>
                    <input class="form-control" placeholder="Rent Paid by Employee" name="employee_rent" type="text"
                        value="<?php echo $rent_paid; ?>" id="edit_employee_rent">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.cont_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1940:' + (new Date().getFullYear() + 1),
            });

            $("#edit_aj_accommodation").change(function() {
                jQuery.get(base_url + "/get_accommodation/" + jQuery(this).val(), function(data, status) {
                    if (data.result != '') {
                        var address = data.result.address_line_1 + ' ' + data.result.address_line_2;

                        const date_from = new Date(data.result.period_from);
                        const df_ye = new Intl.DateTimeFormat('en', {
                            year: 'numeric'
                        }).format(date_from);
                        const df_mo = new Intl.DateTimeFormat('en', {
                            month: 'short'
                        }).format(date_from);
                        const df_da = new Intl.DateTimeFormat('en', {
                            day: '2-digit'
                        }).format(date_from);


                        const date_to = new Date(data.result.period_to);
                        const dt_ye = new Intl.DateTimeFormat('en', {
                            year: 'numeric'
                        }).format(date_to);
                        const dt_mo = new Intl.DateTimeFormat('en', {
                            month: 'short'
                        }).format(date_to);
                        const dt_da = new Intl.DateTimeFormat('en', {
                            day: '2-digit'
                        }).format(date_to);

                        jQuery('#edit_address').val(address);
                        jQuery('#edit_accommodation_period').val(`${df_da} ${df_mo} ${df_ye}` + ' - ' +
                            `${dt_da} ${dt_mo} ${dt_ye}`);
                        console.log(`${df_da} ${df_mo} ${df_ye}` + ' - ' + `${dt_da} ${dt_mo} ${dt_ye}`)
                    }

                });
            });


            // $('.aj_company').trigger('change');
            jQuery(".aj_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('.employee_ajax').html(data);
                });
            });



            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });
            // Date
            $('.e_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1900:' + (new Date().getFullYear() + 10),
            });

            /* Update document info */
            $("#update_accommodated_employees_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'update_accommodated_employees_info');
                fd.append("data", 'update_accommodated_employees_info');
                fd.append("form", action);
                e.preventDefault();
                $('.save').prop('disabled', true);
                $.ajax({
                    url: e.target.action,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_employee_accommodation = $(
                                '#xin_table_employee_accommodation').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employeebenefits/getemployeeaccommodation") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_employee_accommodation.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'utility' && $_GET['type'] == 'utility') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('edit_utility'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_utility_info', 'id' => 'update_utility_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employeebenefits/update_utility_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr3);
    ?>

    <div class="modal-body">

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
                            <option value="<?php echo $company->company_id; ?>"
                                <?php echo $read_employee[0]->company_id == $company->company_id ? 'selected' : '' ?>>
                                <?php echo $company->name; ?>
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
                        <!-- <option value="">Select Employee</option> -->
                        <option value="<?php echo $read_employee[0]->user_id; ?>" selected>
                            <?php echo $read_employee[0]->first_name . ' ' . $read_employee[0]->last_name; ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="benefits_year">Benefits Year
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select class="form-control" name="benefits_year" id="edit_benefits_year" data-plugin="select_hrm"
                        data-placeholder="Benefit Year">
                        <option value=""></option>
                        <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                            <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                            <option value="<?php echo $ayear; ?>" <?php echo $benefit_year == $ayear ? 'selected' : '' ?>>
                                <?php echo $ayear; ?>
                            </option>
                        <?php } ?>
                        <option value="<?php echo date('Y'); ?>" <?php echo $benefit_year == date('Y') ? 'selected' : '' ?>>
                            <?php echo date('Y'); ?></option>
                        <option value="<?php echo date('Y', strtotime('1 year')); ?>"
                            <?php echo $benefit_year == date('Y', strtotime('1 year')) ? 'selected' : '' ?>>
                            <?php echo date('Y', strtotime('1 year')); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div id="utilityCont">
            <div class="row mt-3" id="utilityDiv">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="utility">Utilities & Accessories
                            <i class="hrsale-asterisk">*</i>
                        </label>
                        <select class="form-control selectut" name="utility" data-plugin="select_hrm"
                            data-placeholder="Utility" required>
                            <option value="">Select Utility</option>
                            <option value="utility" <?= $utility == 'utility' ? 'selected' : '' ?>>Utilities</option>
                            <option value="telephone" <?= $utility == 'telephone' ? 'selected' : '' ?>>Telephone</option>
                            <option value="pager" <?= $utility == 'pager' ? 'selected' : '' ?>>Pager</option>
                            <option value="golf_bag_accessories"
                                <?= $utility == 'golf_bag_accessories' ? 'selected' : '' ?>>Golf Bag &
                                Accessories</option>
                            <option value="camera" <?= $utility == 'camera' ? 'selected' : '' ?>>Camera</option>
                            <option value="tablet" <?= $utility == 'tablet' ? 'selected' : '' ?>>Tablet</option>
                            <option value="laptop" <?= $utility == 'laptop' ? 'selected' : '' ?>>Laptop</option>
                            <option value="electronic_gadget" <?= $utility == 'electronic_gadget' ? 'selected' : '' ?>>
                                Electronic
                                Gadget</option>
                            <option value="other" <?= $utility == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="utility_remark">Remark</label>
                        <input class="form-control" placeholder="Utility or Accessory Name" name="utility_remark"
                            value="<?= $utility_remark ?>" type="text">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="utility_amount">Actual Amount
                            <i class="hrsale-asterisk">*</i>
                        </label>
                        <input class="form-control" placeholder="Actual Amount of Utility or Accessory"
                            name="utility_amount" type="text" required value="<?= $utility_amount ?>">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".aj_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('.employee_ajax').html(data);
                });
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });
            // Date
            $('.e_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1900:' + (new Date().getFullYear() + 10),
            });

            /* Update document info */
            $("#update_utility_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'utility');
                fd.append("data", 'utility');
                fd.append("form", action);
                e.preventDefault();
                $('.save').prop('disabled', true);
                $.ajax({
                    url: e.target.action,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_utility = $(
                                '#xin_table_utility').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employeebenefits/getemployeeutility") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_utility.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                    }
                });
            });
        });
    </script>


<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'driver' && $_GET['type'] == 'driver') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('edit_driver'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_driver_info', 'id' => 'update_driver_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employeebenefits/update_driver_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr3);
    ?>

    <div class="modal-body">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="company">Company
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select class="form-control aj_company" name="company" data-plugin="select_hrm"
                        data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                        <option value="">Select Company</option>
                        <?php foreach ($all_companies as $company) { ?>
                            <option value="<?php echo $company->company_id; ?>"
                                <?= $read_employee[0]->company_id == $company->company_id ? 'selected' : '' ?>>
                                <?php echo $company->name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 employee_ajax">
                <div class="form-group">
                    <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee'); ?>
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select name="employee_id" class="form-control employee_id" data-plugin="select_hrm"
                        data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
                        <!-- <option value="">Select Employee</option> -->
                        <option value="<?php echo $read_employee[0]->user_id; ?>" selected>
                            <?php echo $read_employee[0]->first_name . ' ' . $read_employee[0]->last_name; ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="benefits_year">Benefits Year
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select class="form-control" name="benefits_year" id="edit_benefits_year" data-plugin="select_hrm"
                        data-placeholder="Benefit Year">
                        <option value=""></option>
                        <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                            <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                            <option value="<?php echo $ayear; ?>" <?= $benefit_year == $ayear ? 'selected' : '' ?>>
                                <?php echo $ayear; ?></option>
                        <?php } ?>
                        <option value="<?php echo date('Y'); ?>" <?= $benefit_year == date('Y') ? 'selected' : '' ?>>
                            <?php echo date('Y'); ?></option>
                        <option value="<?php echo date('Y', strtotime('1 year')); ?>"
                            <?= $benefit_year == date('Y', strtotime('1 year')) ? 'selected' : '' ?>>
                            <?php echo date('Y', strtotime('1 year')); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="driver_wage">Driver Annual Wage
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control" placeholder="Annual wage of driver" name="driver_wage" id="edit_driver_wage"
                        type="text" value="<?= $driver_wage ?>">
                    <small id="driver_wage_help" class="form-text text-muted">Annual wages x (Private / Total
                        Mileage)</small>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".aj_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('.employee_ajax').html(data);
                });
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });
            // Date
            $('.e_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1900:' + (new Date().getFullYear() + 10),
            });

            /* Update document info */
            $("#update_driver_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'driver');
                fd.append("data", 'driver');
                fd.append("form", action);
                e.preventDefault();
                $('.save').prop('disabled', true);
                $.ajax({
                    url: e.target.action,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_driver = $(
                                '#xin_table_driver').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employeebenefits/getemployeedriver") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_driver.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'housekeeping' && $_GET['type'] == 'housekeeping') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('edit_housekeeping'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_housekeeping_info', 'id' => 'update_housekeeping_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employeebenefits/update_housekeeping_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr3);
    ?>

    <div class="modal-body">

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
                            <option value="<?php echo $company->company_id; ?>"
                                <?= $read_employee[0]->company_id == $company->company_id ? 'selected' : '' ?>>
                                <?php echo $company->name; ?>
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
                        <!-- <option value="">Select Employee</option> -->
                        <option value="<?php echo $read_employee[0]->user_id; ?>" selected>
                            <?php echo $read_employee[0]->first_name . ' ' . $read_employee[0]->last_name; ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="benefits_year">Benefits Year
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select class="form-control" name="benefits_year" id="edit_benefits_year" data-plugin="select_hrm"
                        data-placeholder="Benefit Year">
                        <option value=""></option>
                        <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                            <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                            <option value="<?php echo $ayear; ?>" <?= $benefit_year == $ayear ? 'selected' : '' ?>>
                                <?php echo $ayear; ?></option>
                        <?php } ?>
                        <option value="<?php echo date('Y'); ?>" <?= $benefit_year == date('Y') ? 'selected' : '' ?>>
                            <?php echo date('Y'); ?></option>
                        <option value="<?php echo date('Y', strtotime('1 year')); ?>"
                            <?= $benefit_year == date('Y', strtotime('1 year')) ? 'selected' : '' ?>>
                            <?php echo date('Y', strtotime('1 year')); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div id="houseKeepingCont">
            <div class="row mt-3" id="housekeepingDiv">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="housekeeping_service">Housekeeping Service
                            <i class="hrsale-asterisk">*</i>
                        </label>
                        <select class="form-control selectut" name="housekeeping_service" data-plugin="select_hrm"
                            data-placeholder="Housekeeping Service" required>
                            <option value="">Select Service</option>
                            <option value="servant" <?= $housekeeping_service == 'servant' ? 'selected' : '' ?>>Servant
                            </option>
                            <option value="gardener" <?= $housekeeping_service == 'gardener' ? 'selected' : '' ?>>Gardener
                            </option>
                            <option value="upkeep_of_compound"
                                <?= $housekeeping_service == 'upkeep_of_compound' ? 'selected' : '' ?>>Upkeep of Compound
                            </option>
                            <option value="other" <?= $housekeeping_service == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="housekeeping_remark">Remark</label>
                        <input class="form-control" placeholder="Remarks" name="housekeeping_remark" type="text"
                            value="<?= $housekeeping_remark ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="housekeeping_amount">Annual Wage
                            <i class="hrsale-asterisk">*</i>
                        </label>
                        <input class="form-control" placeholder="Actual Amount or Annual wage" name="housekeeping_amount"
                            type="text" required value="<?= $housekeeping_amount ?>">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".aj_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('.employee_ajax').html(data);
                });
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });


            /* Update document info */
            $("#update_housekeeping_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'housekeeping');
                fd.append("data", 'housekeeping');
                fd.append("form", action);
                e.preventDefault();
                $('.save').prop('disabled', true);
                $.ajax({
                    url: e.target.action,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_housekeeping = $(
                                '#xin_table_housekeeping').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employeebenefits/getemployeehousekeeping") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_housekeeping.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'hotel_accommodation' && $_GET['type'] == 'hotel_accommodation') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('edit_hotel_accommodation'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_hotel_accommodation_info', 'id' => 'update_hotel_accommodation_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employeebenefits/update_hotel_accommodation_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr3);
    ?>

    <div class="modal-body">

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
                            <option value="<?php echo $company->company_id; ?>"
                                <?= $read_employee[0]->company_id == $company->company_id ? 'selected' : '' ?>>
                                <?php echo $company->name; ?>
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
                        <!-- <option value="">Select Employee</option> -->
                        <option value="<?php echo $read_employee[0]->user_id; ?>" selected>
                            <?php echo $read_employee[0]->first_name . ' ' . $read_employee[0]->last_name; ?>
                        </option>
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
                    <input class="form-control" placeholder="Hotel Name" name="hotel_name" id="hotel_name" type="text"
                        value="<?= $hotel_name ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ht_check_in">Check In Date
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control cont_date" placeholder="Check in date" name="ht_check_in"
                        id="edit_ht_check_in" type="text" readonly value="<?= $check_in ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="ht_check_out">Check Out Date
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control cont_date" placeholder="Check Out date" name="ht_check_out"
                        id="edit_ht_check_out" type="text" readonly value="<?= $check_out ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ht_actual_cost">Actual Cost
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <input class="form-control" placeholder="Actual Cost" name="ht_actual_cost" id="edit_ht_actual_cost"
                        type="text" value="<?= $actual_cost ?>">
                    <small id="ht_actual_cost_help" class="form-text text-muted">Actual Cost of Hotel
                        Accommodation</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="ht_employee_paid">Employee Paid Amount</label>
                    <input class="form-control" placeholder="Amount Paid by Employee" name="ht_employee_paid"
                        id="edit_ht_employee_paid" type="text" value="<?= $employee_paid ?>">
                    <small id="ht_employee_paid_help" class="form-text text-muted">Amount paid by the
                        employee</small>
                </div>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {


            // Date
            $('.cont_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1940:' + (new Date().getFullYear() + 1),
            });


            $(".aj_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('.employee_ajax').html(data);
                });
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });


            /* Update document info */
            $("#update_hotel_accommodation_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'hotel_accommodation');
                fd.append("data", 'hotel_accommodation');
                fd.append("form", action);
                e.preventDefault();
                $('.save').prop('disabled', true);
                $.ajax({
                    url: e.target.action,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_hotel_accommodation = $(
                                '#xin_table_hotel_accommodation').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employeebenefits/getEmployeeHotelAccommodation") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_hotel_accommodation.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'other_benefits' && $_GET['type'] == 'other_benefits') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('edit_other_benefits'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'update_other_benefits_info', 'id' => 'update_other_benefits_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employeebenefits/update_other_benefits_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'id',
        'name'  => 'id',
        'value' => $id,
    );
    echo form_input($edata_usr3);
    ?>

    <div class="modal-body">

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
                            <option value="<?php echo $company->company_id; ?>"
                                <?= $read_employee[0]->company_id == $company->company_id ? 'selected' : '' ?>>
                                <?php echo $company->name; ?>
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
                        <!-- <option value="">Select Employee</option> -->
                        <option value="<?php echo $read_employee[0]->user_id; ?>" selected>
                            <?php echo $read_employee[0]->first_name . ' ' . $read_employee[0]->last_name; ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="benefits_year">Benefits Year
                        <i class="hrsale-asterisk">*</i>
                    </label>
                    <select class="form-control" name="benefits_year" id="benefits_year" data-plugin="select_hrm"
                        data-placeholder="Benefit Year">
                        <option value=""></option>
                        <?php for ($ay = 6; $ay > 0; $ay -= 1) { ?>
                            <?php $ayear = date('Y', strtotime("-$ay year")); ?>
                            <option value="<?php echo $ayear; ?>" <?= $benefit_year == $ayear ? 'selected' : '' ?>>
                                <?php echo $ayear; ?></option>
                        <?php } ?>
                        <option value="<?php echo date('Y'); ?>" <?= $benefit_year == date('Y') ? 'selected' : '' ?>>
                            <?php echo date('Y'); ?></option>
                        <option value="<?php echo date('Y', strtotime('1 year')); ?>"
                            <?= $benefit_year == date('Y', strtotime('1 year')) ? 'selected' : '' ?>>
                            <?php echo date('Y', strtotime('1 year')); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div id="otherBenefitCont">
            <div class="row mt-3" id="otherBenefitDiv">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="other_benefit">Benefit
                            <i class="hrsale-asterisk">*</i>
                        </label>
                        <select class="form-control selectut" name="other_benefit" data-plugin="select_hrm"
                            data-placeholder="Benefit" required>
                            <option value="">Select Service</option>
                            <option value="Home Leave Passage & Incidental Benefit"
                                <?= $other_benefit == 'Home Leave Passage & Incidental Benefit' ? 'selected' : '' ?>>Home
                                Leave Passage &
                                Incidental Benefit</option>
                            <option value="Interest Payment" <?= $other_benefit == 'Interest Payment' ? 'selected' : '' ?>>
                                Interest Payment</option>
                            <option value="Insurance Premiums"
                                <?= $other_benefit == 'Insurance Premiums' ? 'selected' : '' ?>>Insurance Premiums</option>
                            <option value="Free or Subsidized Holidays"
                                <?= $other_benefit == 'Free or Subsidized Holidays' ? 'selected' : '' ?>>Free or Subsidized
                                Holidays</option>
                            <option value="Educational expenses"
                                <?= $other_benefit == 'Educational expenses' ? 'selected' : '' ?>>Educational expenses
                            </option>
                            <option value="Social or Recreational clubs Fee"
                                <?= $other_benefit == 'Social or Recreational clubs Fee' ? 'selected' : '' ?>>Social or
                                Recreational clubs Fee
                            </option>
                            <option value="Gains from Assets sold to Employee"
                                <?= $other_benefit == 'Gains from Assets sold to Employee' ? 'selected' : '' ?>>Gains from
                                Assets sold to
                                Employee</option>
                            <option value="Motor Vehicle cost given to Employee"
                                <?= $other_benefit == 'Motor Vehicle cost given to Employee' ? 'selected' : '' ?>>Motor
                                Vehicle cost given to
                                Employee</option>
                            <option value="Car Benefits" <?= $other_benefit == 'Car Benefits' ? 'selected' : '' ?>>Car
                                Benefits</option>
                            <option value="Other Benefit" <?= $other_benefit == 'Other Benefit' ? 'selected' : '' ?>>Other
                                Benefit</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="other_benefit_remark">Remark</label>
                        <input class="form-control" placeholder="Remarks" name="other_benefit_remark" type="text"
                            value="<?= $other_benefit_remark ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="other_benefit_cost">Actual Cost
                            <i class="hrsale-asterisk">*</i>
                        </label>
                        <input class="form-control" placeholder="Actual cost of benefit" name="other_benefit_cost"
                            type="text" required value="<?= $other_benefit_cost ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-checkbox"
                            name="deductible_from_salary" type="checkbox" <?php echo $deductible_from_salary == 1 ? 'checked' : ''?>>
                        <label for="deductible_from_salary">Deductible from Salary
                        </label>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {


            // Date
            $('.cont_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1940:' + (new Date().getFullYear() + 1),
            });


            $(".aj_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('.employee_ajax').html(data);
                });
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%',
                allowClear: true,
            });


            /* Update document info */
            $("#update_other_benefits_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'other_benefits');
                fd.append("data", 'other_benefits');
                fd.append("form", action);
                e.preventDefault();
                $('.save').prop('disabled', true);
                $.ajax({
                    url: e.target.action,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load: table_contacts
                            var xin_table_other_benefits = $(
                                '#xin_table_other_benefits').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employeebenefits/getEmployeeOtherBenefits") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_other_benefits.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                    }
                });
            });
        });
    </script>


<?php };
?>