<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_contact' && $_GET['type'] == 'emp_contact') {
?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_contact'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_contact_info', 'id' => 'e_contact_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_contact_info', $attributes, $hidden); ?>
    <?php
    $edata_usr1 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr1);
    ?>
    <?php
    $edata_usr2 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $contact_id,
    );
    echo form_input($edata_usr2);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="relation"><?php echo $this->lang->line('xin_e_details_relation'); ?><i class="hrsale-asterisk">*</i></label>
                    <select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one'); ?>">
                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                        <option value="Self" <?php if ($relation == 'Self') { ?> selected="selected" <?php } ?>>
                            <?php echo $this->lang->line('xin_self'); ?></option>
                        <option value="Parent" <?php if ($relation == 'Parent') { ?> selected="selected" <?php } ?>>
                            <?php echo $this->lang->line('xin_parent'); ?></option>
                        <option value="Spouse" <?php if ($relation == 'Spouse') { ?> selected="selected" <?php } ?>>
                            <?php echo $this->lang->line('xin_spouse'); ?></option>
                        <option value="Child" <?php if ($relation == 'Child') { ?> selected="selected" <?php } ?>>
                            <?php echo $this->lang->line('xin_child'); ?></option>
                        <option value="Sibling" <?php if ($relation == 'Sibling') { ?> selected="selected" <?php } ?>>
                            <?php echo $this->lang->line('xin_sibling'); ?></option>
                        <option value="In Laws" <?php if ($relation == 'In Laws') { ?> selected="selected" <?php } ?>>
                            <?php echo $this->lang->line('xin_in_laws'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label for="work_email" class="control-label"><?php echo $this->lang->line('dashboard_email'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_work'); ?>" name="work_email" type="text" value="<?php echo $work_email; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label class="display-inline-block custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_primary" value="1" name="is_primary" <?php if ($is_primary == '1') { ?> checked="checked" <?php } ?>>
                        <span class="custom-control-indicator"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_pcontact'); ?></span>
                    </label>
                    &nbsp;
                    <label class="display-inline-block custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_dependent" value="2" name="is_dependent" <?php if ($is_dependent == '2') { ?> checked="checked" <?php } ?>>
                        <span class="custom-control-indicator"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_dependent'); ?></span>
                    </label>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_personal'); ?>" name="personal_email" type="text" value="<?php echo $personal_email; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="name" class="control-label"><?php echo $this->lang->line('xin_name'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_name'); ?>" name="contact_name" type="text" value="<?php echo $contact_name; ?>">
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group" id="designation_ajax">
                    <label for="address_1" class="control-label"><?php echo $this->lang->line('xin_address'); ?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1'); ?>" name="address_1" type="text" value="<?php echo $address_1; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="work_phone"><?php echo $this->lang->line('xin_phone'); ?><i class="hrsale-asterisk">*</i></label>
                    <div class="row">
                        <div class="col-xs-8">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_work'); ?>" name="work_phone" type="text" value="<?php echo $work_phone; ?>">
                        </div>
                        <div class="col-xs-4">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_phone_ext'); ?>" name="work_phone_extension" type="text" value="<?php echo $work_phone_extension; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2'); ?>" name="address_2" type="text" value="<?php echo $address_2; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_mobile'); ?>" name="mobile_phone" type="text" value="<?php echo $mobile_phone; ?>">
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-5">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city'); ?>" name="city" type="text" value="<?php echo $city; ?>">
                        </div>
                        <div class="col-xs-4">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state'); ?>" name="state" type="text" value="<?php echo $state; ?>">
                        </div>
                        <div class="col-xs-3">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode'); ?>" name="zipcode" type="text" value="<?php echo $zipcode; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_home'); ?>" name="home_phone" type="text" value="<?php echo $home_phone; ?>">
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <select name="country" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country'); ?>">
                        <option value=""></option>
                        <?php foreach ($all_countries as $country) { ?>
                            <option value="<?php echo $country->country_id; ?>" <?php if ($country->country_id == $icountry) { ?> selected="selected" <?php } ?>> <?php echo $country->country_name; ?></option>
                        <?php } ?>
                    </select>
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            /* Update contact info */
            $("#e_contact_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=5&data=e_contact_info&type=e_contact_info&form=" +
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
                            var xin_table_contact = $('#xin_table_contact').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/contacts") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_contact.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_document' && $_GET['type'] == 'emp_document') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_document'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_document_info', 'id' => 'e_document_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employees/e_document_info', $attributes, $hidden); ?>
    <?php
    $edata_usr3 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $d_employee_id,
    );
    echo form_input($edata_usr3);
    ?>
    <?php
    $edata_usr4 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $document_id,
    );
    echo form_input($edata_usr4);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="relation"><?php echo $this->lang->line('xin_e_details_dtype'); ?><i class="hrsale-asterisk">*</i></label>
                    <select name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype'); ?>">
                        <option value=""></option>
                        <?php foreach ($all_document_types as $document_type) { ?>
                            <option value="<?php echo $document_type->document_type_id; ?>" <?php if ($document_type->document_type_id == $document_type_id) { ?> selected="selected" <?php } ?>> <?php echo $document_type->document_type; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_of_expiry" class="control-label"><?php echo $this->lang->line('xin_e_details_doe'); ?></label>
                    <input class="form-control e_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_doe'); ?>" name="date_of_expiry" type="text" value="<?php echo $date_of_expiry; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title" class="control-label"><?php echo $this->lang->line('xin_e_details_dtitle'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_dtitle'); ?>" name="title" type="text" value="<?php echo $title; ?>">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description" class="control-label"><?php echo $this->lang->line('xin_description'); ?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description'); ?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"><?php echo $description; ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group">
                        <label for="logo"><?php echo $this->lang->line('xin_e_details_document_file'); ?></label>
                        <input type="file" class="form-control-file" id="document_file" name="document_file">
                        <small><?php echo $this->lang->line('xin_e_details_d_type_file'); ?></small>
                        <?php if ($document_file != '' && $document_file != 'no file') { ?>
                            <br />
                            <a href="<?php echo site_url('admin/download/'); ?>?type=document&filename=<?php echo $document_file; ?>"><?php echo $document_file; ?></a>
                        <?php } ?>
                    </fieldset>
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
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
            $("#e_document_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'e_document_info');
                fd.append("data", 'e_document_info');
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
                            var xin_table_document = $('#xin_table_document').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/documents") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_document.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'e_imgdocument' && $_GET['type'] == 'e_imgdocument') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_immigration'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_imgdocument_info', 'id' => 'e_imgdocument_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_document_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employees/e_immigration_info', $attributes, $hidden); ?>
    <?php
    $edata_usr5 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $d_employee_id,
    );
    echo form_input($edata_usr5);
    ?>
    <?php
    $edata_usr6 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $immigration_id,
    );
    echo form_input($edata_usr6);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="relation"><?php echo $this->lang->line('xin_e_details_document'); ?></label>
                    <select name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype'); ?>">
                        <option value=""></option>
                        <?php foreach ($all_document_types as $document_type) { ?>
                            <option value="<?php echo $document_type->document_type_id; ?>" <?php if ($document_type->document_type_id == $document_type_id) { ?> selected="selected" <?php } ?>> <?php echo $document_type->document_type; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="document_number" class="control-label"><?php echo $this->lang->line('xin_employee_document_number'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_document_number'); ?>" name="document_number" type="text" value="<?php echo $document_number; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="issue_date" class="control-label"><?php echo $this->lang->line('xin_issue_date'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control e_date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_issue_date'); ?>" name="issue_date" type="text" value="<?php echo $issue_date; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="expiry_date" class="control-label"><?php echo $this->lang->line('xin_expiry_date'); ?>
                        <!-- <i class="hrsale-asterisk">*</i> -->
                    </label>
                    <input class="form-control e_date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_expiry_date'); ?>" name="expiry_date" type="text" value="<?php echo $expiry_date; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group">
                        <label for="logo"><?php echo $this->lang->line('xin_e_details_document_file'); ?><i class="hrsale-asterisk">*</i></label>
                        <input type="file" class="form-control-file" id="p_file2" name="document_file">
                        <small><?php echo $this->lang->line('xin_e_details_d_type_file'); ?></small>
                        <?php if ($document_file != '' && $document_file != 'no file') { ?>
                            <br />
                            <a href="<?php echo site_url('admin/download/'); ?>?type=document/immigration&filename=<?php echo $document_file; ?>"><?php echo $document_file; ?></a>
                        <?php } ?>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="eligible_review_date" class="control-label"><?php echo $this->lang->line('xin_eligible_review_date'); ?></label>
                    <input class="form-control e_date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_eligible_review_date'); ?>" name="eligible_review_date" type="text" value="<?php echo $eligible_review_date; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="send_mail"><?php echo $this->lang->line('xin_country'); ?></label>
                    <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country'); ?>">
                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                        <?php foreach ($all_countries as $scountry) { ?>
                            <option value="<?php echo $scountry->country_id; ?>" <?php if ($scountry->country_id == $country_id) { ?> selected="selected" <?php } ?>> <?php echo $scountry->country_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
            <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
    <!--<link rel="stylesheet" href="http://localhost/hrsale_final/skin/hrsale_assets/theme_assets/bower_components/select2/dist/css/select2.min.css">
<script src="http://localhost/hrsale_final/skin/hrsale_assets/vendor/select2/dist/js/select2.min.js"></script>-->
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
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
            $("#e_imgdocument_info").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 9);
                fd.append("type", 'e_immigration_info');
                fd.append("data", 'e_immigration_info');
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
                            var xin_table_immigration = $('#xin_table_imgdocument').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/immigration") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_immigration.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_qualification' && $_GET['type'] == 'emp_qualification') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_qualification'); ?>
        </h4>
    </div>
    <?php $attributes = array('name' => 'e_qualification_info', 'id' => 'e_qualification_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_qualification_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $qualification_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name"><?php echo $this->lang->line('xin_e_details_inst_name'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_inst_name'); ?>" name="name" type="text" value="<?php echo $name; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="education_level" class="control-label"><?php echo $this->lang->line('xin_e_details_edu_level'); ?></label>
                    <select class="form-control" name="education_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_edu_level'); ?>">
                        <?php foreach ($all_education_level as $education_level) { ?>
                            <option value="<?php echo $education_level->education_level_id; ?>" <?php if ($education_level->education_level_id == $education_level_id) { ?> selected="selected" <?php } ?>> <?php echo $education_level->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod'); ?><i class="hrsale-asterisk">*</i></label>
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control edate" readonly="readonly" value="<?php echo $from_year; ?>" placeholder="<?php echo $this->lang->line('xin_e_details_from'); ?>" name="from_year" type="text">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control edate" readonly="readonly" value="<?php echo $to_year; ?>" placeholder="<?php echo $this->lang->line('dashboard_to'); ?>" name="to_year" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="language" class="control-label"><?php echo $this->lang->line('xin_e_details_language'); ?></label>
                    <select class="form-control" name="language" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_language'); ?>">
                        <?php foreach ($all_qualification_language as $qualification_language) { ?>
                            <option value="<?php echo $qualification_language->language_id; ?>" <?php if ($qualification_language->language_id == $language_id) { ?> selected="selected" <?php } ?>>
                                <?php echo $qualification_language->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="skill" class="control-label"><?php echo $this->lang->line('xin_e_details_skill'); ?></label>
                    <select class="form-control" name="skill" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_skill'); ?>">
                        <?php foreach ($all_qualification_skill as $qualification_skill) { ?>
                            <option value="<?php echo $qualification_skill->skill_id ?>" <?php if ($qualification_skill->skill_id == $skill_id) { ?> selected="selected" <?php } ?>>
                                <?php echo $qualification_skill->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="to_year" class="control-label"><?php echo $this->lang->line('xin_description'); ?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description'); ?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"><?php echo $description; ?></textarea>
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            $('.edate').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1900:' + (new Date().getFullYear() + 15),
                beforeShow: function(input) {
                    $(input).datepicker("widget").show();
                }
            });

            /* Update qualification info */
            $("#e_qualification_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=11&data=e_qualification_info&type=e_qualification_info&form=" +
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
                            var xin_table_qualification = $('#xin_table_qualification').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/qualification") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_qualification.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_work_experience' && $_GET['type'] == 'emp_work_experience') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_wexp'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_work_experience_info', 'id' => 'e_work_experience_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_work_experience_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $work_experience_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('xin_company_name'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name'); ?>" name="company_name" type="text" value="<?php echo $company_name; ?>" id="company_name">
                </div>
                <div class="form-group">
                    <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date'); ?><i class="hrsale-asterisk">*</i></label>
                    <input type="text" class="form-control edate" id="e_from_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date'); ?>" readonly value="<?php echo $from_date; ?>">
                </div>
                <div class="form-group">
                    <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date'); ?><i class="hrsale-asterisk">*</i></label>
                    <input type="text" class="form-control edate" id="e_to_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date'); ?>" readonly value="<?php echo $to_date; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="post"><?php echo $this->lang->line('xin_e_details_post'); ?>
                        <i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_post'); ?>" name="post" type="text" value="<?php echo $post; ?>" id="post">
                </div>
                <div class="form-group">
                    <label for="description"><?php echo $this->lang->line('xin_description'); ?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description'); ?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="4" id="description"><?php echo $description; ?></textarea>
                    <span class="countdown"></span>
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            $('.edate').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1900:' + (new Date().getFullYear() + 15),
                beforeShow: function(input) {
                    $(input).datepicker("widget").show();
                }
            });

            /* Update work experience info */
            $("#e_work_experience_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=14&data=e_work_experience_info&type=e_work_experience_info&form=" +
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
                            var xin_table_work_experience = $('#xin_table_work_experience')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employees/experience") ?>/" +
                                            $('#user_id').val(),
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            xin_table_work_experience.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_bank_account' && $_GET['type'] == 'emp_bank_account') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_baccount'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_bank_account_info', 'id' => 'e_bank_account_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_bank_account_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $bankaccount_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="account_title"><?php echo $this->lang->line('xin_e_details_acc_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_title'); ?>" name="account_title" type="text" value="<?php echo $account_title; ?>" id="account_name">
                </div>
                <div class="form-group">
                    <label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number'); ?>" name="account_number" type="text" value="<?php echo $account_number; ?>" id="account_number">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name'); ?>" name="bank_name" type="text" value="<?php echo $bank_name; ?>" id="bank_name">
                </div>
                <div class="form-group">
                    <label for="bank_code"><?php echo $this->lang->line('xin_e_details_bank_code'); ?>
                        <!-- <i class="hrsale-asterisk">*</i> -->
                    </label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_code'); ?>" name="bank_code" type="text" value="<?php echo $bank_code; ?>" id="bank_code">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch'); ?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch'); ?>" name="bank_branch" type="text" value="<?php echo $bank_branch; ?>" id="bank_branch">
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

            /* Update bank acount info */
            $("#e_bank_account_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=17&data=e_bank_account_info&type=e_bank_account_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/bank_account") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_bank_account.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'esecurity_level_info' && $_GET['type'] == 'esecurity_level_info') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_emp_security_level'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_security_level_info', 'id' => 'e_security_level_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_security_level_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $security_level_id,
    );
    echo form_input($edata_usr8);
    ?>
    <?php $security_level_list = $this->Xin_model->get_security_level_type(); ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="account_title"><?php echo $this->lang->line('xin_esecurity_level_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <select class="form-control" name="security_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_esecurity_level_title'); ?>">
                        <option value=""><?php echo $this->lang->line('xin_esecurity_level_title'); ?></option>
                        <?php foreach ($security_level_list->result() as $sc_level) { ?>
                            <option value="<?php echo $sc_level->type_id ?>" <?php if ($security_type == $sc_level->type_id) : ?> selected="selected" <?php endif; ?>><?php echo $sc_level->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="account_number"><?php echo $this->lang->line('xin_e_details_doe'); ?></label>
                    <input class="form-control ee_date" placeholder="<?php echo $this->lang->line('xin_e_details_doe'); ?>" name="expiry_date" type="text" value="<?php echo $expiry_date; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="account_number"><?php echo $this->lang->line('xin_e_details_do_clearance'); ?></label>
                    <input class="form-control ee_date" placeholder="<?php echo $this->lang->line('xin_e_details_do_clearance'); ?>" name="date_of_clearance" type="text" value="<?php echo $date_of_clearance; ?>">
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            // Date
            $('.ee_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });
            $('.ee_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });
            /* Update bank acount info */
            $("#e_security_level_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=17&data=e_security_level_info&type=e_security_level_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var exin_table_security_level = $('#xin_table_security_level')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employees/security_level_list") ?>/" +
                                            $('#user_id').val(),
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            exin_table_security_level.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_contract' && $_GET['type'] == 'emp_contract') { ?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_contract'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_contract_info', 'id' => 'e_contract_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_contract_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $contract_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contract_type_id" class=""><?php echo $this->lang->line('xin_e_details_contract_type'); ?></label>
                    <select class="form-control" name="contract_type_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one'); ?>">
                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                        <?php foreach ($all_contract_types as $contract_type) { ?>
                            <option value="<?php echo $contract_type->contract_type_id; ?>" <?php if ($contract_type->contract_type_id == $contract_type_id) { ?> selected="selected" <?php } ?>> <?php echo $contract_type->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="" for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date'); ?></label>
                    <input type="text" class="form-control e_cont_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date'); ?>" readonly value="<?php echo $from_date; ?>">
                </div>
                <div class="form-group">
                    <label for="designation_id" class=""><?php echo $this->lang->line('dashboard_designation'); ?></label>
                    <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one'); ?>">
                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                        <?php foreach ($all_designations as $designation) { ?>
                            <?php if ($designation_id == $designation->designation_id) : ?>
                                <option value="<?php echo $designation->designation_id ?>" <?php if ($designation_id == $designation->designation_id) : ?> selected <?php endif; ?>>
                                    <?php echo $designation->designation_name ?></option>
                            <?php endif; ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class=""><?php echo $this->lang->line('xin_e_details_contract_title'); ?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_contract_title'); ?>" name="title" type="text" value="<?php echo $title; ?>" id="title">
                </div>
                <div class="form-group">
                    <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date'); ?></label>
                    <input type="text" class="form-control e_cont_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date'); ?>" readonly value="<?php echo $to_date; ?>">
                </div>
                <div class="form-group">
                    <label for="description"><?php echo $this->lang->line('xin_description'); ?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description'); ?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"><?php echo $description; ?></textarea>
                    <span class="countdown"></span>
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            // Date
            $('.e_cont_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });

            /* Update bank acount info */
            $("#e_contract_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=20&data=e_contract_info&type=e_contract_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_contract = $('#xin_table_contract').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/contract") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_contract.api().ajax.reload(function() {
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

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_exempt_indicator' && $_GET['type'] == 'emp_exempt_indicator') { ?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data">Edit indicator</h4>
    </div>
    <?php $attributes = array('name' => 'e_exempt_indicator_info', 'id' => 'e_exempt_indicator_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_exempt_indicator_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $exempt_indicator_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="relation"><?php echo "Type" ?><i class="hrsale-asterisk">*</i></label>
                    <select name="exempt_indicator" id="exempt_indicator" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype'); ?>">
                        <option value=""></option>
                        <option value="Tax Remission on OCLA" <?php echo $type == "Tax Remission on OCLA" ? 'selected' : ''; ?>>Tax Remission on OCLA</option>
                        <option value="Seaman" <?php echo $type == "Seaman" ? 'selected' : ''; ?>>Seaman</option>
                        <option value="Exemption" <?php echo $type == "Exemption" ? 'selected' : ''; ?>>Exemption</option>
                        <option value="Overseas Pension Fund w TxCon" <?php echo $type == "Overseas Pension Fund w TxCon" ? 'selected' : ''; ?>>Overseas Pension Fund w TxCon</option>
                        <option value="Overseas Employment" <?php echo $type == "Overseas Employment" ? 'selected' : ''; ?>>Overseas Employment</option>
                        <option value="Overseas Emp & Pension w TxCon" <?php echo $type == "Overseas Emp & Pension w TxCon" ? 'selected' : ''; ?>>Overseas Emp & Pension w TxCon</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="document_number" class="control-label"><?php echo "Amount" ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo "amount" ?>" name="amount" type="text" value="<?php echo $amount ?>">
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

            /* Update bank acount info */
            $("#e_exempt_indicator_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=20&data=e_exempt_indicator_info&type=e_exempt_indicator_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_exempt_indicator = $('#xin_table_exempt_indicator').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/exempt_indicator_list") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_exempt_indicator.api().ajax.reload(function() {
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



<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_income_tax_born' && $_GET['type'] == 'emp_income_tax_born') { ?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data">Edit</h4>
    </div>
    <?php $attributes = array('name' => 'e_income_tax_born_info', 'id' => 'e_income_tax_born_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_income_tax_born_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $income_tax_born_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="relation"><?php echo "Type" ?><i class="hrsale-asterisk">*</i></label>
                    <select name="income_tax_born" id="income_tax_born_edit" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype'); ?>">
                        <option value=""></option>
                        <option value="No, tax is NOT borne by employer" <?php echo $type == "No, tax is NOT borne by employer" ? 'selected' : '' ?>>No, tax is NOT borne by employer</option>
                        <option value="Yes, tax is FULLY borne by employer" <?php echo $type == "Yes, tax is FULLY borne by employer" ? 'selected' : '' ?>>Yes, tax is FULLY borne by employer</option>
                        <option value="Yes, tax is PARTIALLY borne by employer" <?php echo $type == "Yes, tax is PARTIALLY borne by employer" ? 'selected' : '' ?>>Yes, tax is PARTIALLY borne by employer</option>
                        <option value="Yes, a FIXED amount of tax is borne by employee" <?php echo $type == "Yes, a FIXED amount of tax is borne by employee" ? 'selected' : '' ?>>Yes, a FIXED amount of tax is borne by employee</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6" style="<?php echo $type == "Yes, tax is PARTIALLY borne by employer" || $type == "Yes, a FIXED amount of tax is borne by employee" ? '' : 'display:none' ?>" id="tax_amount_edit_section">
                <div class="form-group">
                    <label for="document_number" class="control-label"><?php echo "Amount" ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo "amount" ?>" name="amount" type="text" value="<?php echo $amount ?>">
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

            $('#income_tax_born_edit').change(function() {

                if ($("#income_tax_born_edit option:selected").val() == 'Yes, tax is PARTIALLY borne by employer' || $("#income_tax_born_edit option:selected").val() == 'Yes, a FIXED amount of tax is borne by employee') {
                    $('#tax_amount_edit_section').css('display', '');
                } else {
                    $('#tax_amount_edit_section').css('display', 'none');
                }
            });

            /* Update bank acount info */
            $("#e_income_tax_born_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=20&data=e_income_tax_born_info&type=e_income_tax_born_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_income_tax_born = $('#xin_table_income_tax_born').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/income_tax_born_list") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_income_tax_born.api().ajax.reload(function() {
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





<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_leave' && $_GET['type'] == 'emp_leave') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_leave'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_leave_info', 'id' => 'e_leave_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_leave_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $leave_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="casual_leave" class="control-label"><?php echo $this->lang->line('xin_e_details_casual_leave'); ?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_casual_leave'); ?>" name="casual_leave" type="text" value="<?php echo $casual_leave; ?>">
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label for="medical_leave" class="control-label"><?php echo $this->lang->line('xin_e_details_medical_leave'); ?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_medical_leave'); ?>" name="medical_leave" type="text" value="<?php echo $medical_leave; ?>">
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            /* Update leave info */
            $("#e_leave_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=23&data=e_leave_info&type=e_leave_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_leave = $('#xin_table_leave').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/leave") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_leave.api().ajax.reload(function() {
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

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'es_employee_claim_info' && $_GET['type'] == 'es_employee_claim_info') {
?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data">Edit Claim</h4>
    </div>
    <?php $attributes = array('name' => 'e_claim_info', 'id' => 'e_claim_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open_multipart('admin/employees/e_claim_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'claim_id',
        'name'  => 'claim_id',
        'value' => $claim_id,
    );
    echo form_input($edata_usr8);
    ?>

    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'employee_id',
        'name'  => 'employee_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="casual_leave" class="control-label">Claim Type</label>
                    <select name="claim_type" id="claim_type" class="form-control" data-plugin="select_hrm" data-placeholder="Claim Type">
                        <option value="">Select Claim Type</option>

                        <option value="<?php echo $claim_type_id; ?>" selected>
                            <?php echo $claim_type_name; ?>
                        </option>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="medical_leave" class="control-label">Claim Year</label>
                    <select name="claim_year" id="claim_year" class="form-control" data-plugin="select_hrm" data-placeholder="Claim Type">
                        <option value="">Select Claim Type</option>

                        <option value="<?php echo $claim_year; ?>" selected>
                            <?php echo $claim_year; ?>
                        </option>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="medical_leave" class="control-label">Amount</label>
                    <input class="form-control" placeholder="Amount" name="amount" type="text" value="<?php echo $amount; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="medical_leave" class="control-label">Date</label>
                    <input class="form-control es_date" placeholder="Date" name="date" type="text" value="<?php echo $date; ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="medical_leave" class="control-label">Attachment</label>
                    <input class="form-control" placeholder="Attachment" name="attachment" type="file">
                    <span data-toggle="tooltip" data-placement="top" title="Download"><a href="<?php echo site_url() . 'admin/download?type=claim&filename=' . $attachment ?>"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="'.$this->lang->line('xin_download').'"><i class="fa fa-download"></i></button></a></span>'
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            $('.es_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });


            /* Update leave info */
            $("#e_claim_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);

                var fd = new FormData(this);
                fd.append("is_ajax", 23);
                fd.append("type", 'e_claim_info');
                fd.append("data", 'e_claim_info');
                fd.append("form", action);


                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    // data: obj.serialize() + "&is_ajax=23&data=e_claim_info&type=e_claim_info&form=" +
                    //     action,
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
                            // On page load:
                            var xin_table_claim = $('#xin_table_claim').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/getEmployeeClaims") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_claim.api().ajax.reload(function() {
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


<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_shift' && $_GET['type'] == 'emp_shift') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_shift'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_shift_info', 'id' => 'e_shift_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_shift_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $emp_shift_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date'); ?></label>
                    <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_frm_date'); ?>" name="from_date" type="text" value="<?php echo $from_date; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="to_date" class="control-label"><?php echo $this->lang->line('xin_e_details_to_date'); ?></label>
                    <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_to_date'); ?>" name="to_date" type="text" value="<?php echo $to_date; ?>">
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

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            // Date
            $('.es_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });

            /* Update leave info */
            $("#e_shift_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=26&data=e_shift_info&type=e_shift_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_shift = $('#xin_table_shift').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/shift") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_shift.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_location' && $_GET['type'] == 'emp_location') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_location'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_location_info', 'id' => 'e_location_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/e_location_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $office_location_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date'); ?></label>
                    <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_frm_date'); ?>" name="from_date" type="text" value="<?php echo $from_date; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="to_date" class="control-label"><?php echo $this->lang->line('xin_e_details_to_date'); ?></label>
                    <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_to_date'); ?>" name="to_date" type="text" value="<?php echo $to_date; ?>">
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
            $('.es_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });

            /* Update location info */
            $("#e_location_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=e_location_info&type=e_location_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_location = $('#xin_table_location').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/location") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_location.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'e_salary_allowance' && $_GET['type'] == 'e_salary_allowance') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_employee_edit_allowance'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_allowance_info', 'id' => 'e_allowance_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_allowance_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $allowance_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="is_allowance_taxable"><?php echo $this->lang->line('xin_salary_allowance_options'); ?><i class="hrsale-asterisk">*</i></label>
                    <select name="is_allowance_taxable" id="is_allowance_taxable" class="form-control" data-plugin="select_hrm">
                        <option value="0" <?php if ($is_allowance_taxable == 0) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_salary_allowance_non_taxable'); ?></option>
                        <option value="1" <?php if ($is_allowance_taxable == 1) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_salary_allowance_taxable'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="allowance_title"><?php echo $this->lang->line('dashboard_xin_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>" name="allowance_title" type="text" value="<?php echo $allowance_title; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="allowance_amount" class="control-label"><?php echo $this->lang->line('xin_amount'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="allowance_amount" type="text" value="<?php echo $allowance_amount; ?>">
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

            /* Update location info */
            $("#e_allowance_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=e_allowance_info&type=e_allowance_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_all_allowances = $('#xin_table_all_allowances')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employees/salary_all_allowances") ?>/" +
                                            $('#user_id').val(),
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            xin_table_all_allowances.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'e_salary_loan' && $_GET['type'] == 'e_salary_loan') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_employee_edit_loan_title'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_loan_info', 'id' => 'e_salary_loan_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_loan_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $loan_deduction_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="loan_options"><?php echo $this->lang->line('xin_salary_loan_options'); ?><i class="hrsale-asterisk">*</i></label>
                            <select name="loan_options" id="loan_options" class="form-control" data-plugin="select_hrm">
                                <option value="1" <?php if ($loan_options == 1) : ?> selected="selected" <?php endif; ?>>
                                    <?php echo $this->lang->line('xin_loan_ssc_title'); ?></option>
                                <option value="2" <?php if ($loan_options == 2) : ?> selected="selected" <?php endif; ?>>
                                    <?php echo $this->lang->line('xin_loan_hdmf_title'); ?></option>
                                <option value="0" <?php if ($loan_options == 0) : ?> selected="selected" <?php endif; ?>>
                                    <?php echo $this->lang->line('xin_loan_other_sd_title'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="month_year"><?php echo $this->lang->line('dashboard_xin_title'); ?><i class="hrsale-asterisk">*</i></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>" name="loan_deduction_title" type="text" value="<?php echo $loan_deduction_title; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edu_role"><?php echo $this->lang->line('xin_employee_monthly_installment_title'); ?><i class="hrsale-asterisk">*</i></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_monthly_installment_title'); ?>" name="monthly_installment" type="text" id="m_monthly_installment" value="<?php echo $monthly_installment; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="month_year"><?php echo $this->lang->line('xin_start_date'); ?><i class="hrsale-asterisk">*</i></label>
                            <input class="form-control d_month_year" placeholder="<?php echo $this->lang->line('xin_start_date'); ?>" readonly="readonly" name="start_date" type="text" value="<?php echo $start_date; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date"><?php echo $this->lang->line('xin_end_date'); ?><i class="hrsale-asterisk">*</i></label>
                            <input class="form-control d_month_year" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_end_date'); ?>" name="end_date" type="text" value="<?php echo $end_date; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description"><?php echo $this->lang->line('xin_reason'); ?></label>
                            <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_reason'); ?>" name="reason" cols="30" rows="2" id="reason2"><?php echo $reason; ?></textarea>
                        </div>
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
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            // Month & Year
            $('.d_month_year').datepicker({
                changeMonth: true,
                changeYear: true,
                // dateFormat: 'yy-mm-dd',
                dateFormat: js_date_format,
                yearRange: '1990:' + (new Date().getFullYear() + 10),
            });

            /* Update location info */
            $("#e_salary_loan_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=29&data=loan_info&type=loan_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_all_deductions = $('#xin_table_all_deductions')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employees/salary_all_deductions") . '/' . $employee_id; ?>/",
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            xin_table_all_deductions.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_overtime_info' && $_GET['type'] == 'emp_overtime_info') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_employee_edit_allowance'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_overtime_info', 'id' => 'e_overtime_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_overtime_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $salary_overtime_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="overtime_type"><?php echo $this->lang->line('xin_employee_overtime_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_title'); ?>" name="overtime_type" type="text" value="<?php echo $overtime_type; ?>" id="overtime_type">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="no_of_days"><?php echo $this->lang->line('xin_employee_overtime_no_of_days'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_no_of_days'); ?>" name="no_of_days" type="text" value="<?php echo $no_of_days; ?>" id="no_of_days">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="overtime_hours"><?php echo $this->lang->line('xin_employee_overtime_hour'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_hour'); ?>" name="overtime_hours" type="text" value="<?php echo $overtime_hours; ?>" id="overtime_hours">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="overtime_rate"><?php echo $this->lang->line('xin_employee_overtime_rate'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_rate'); ?>" name="overtime_rate" type="text" value="<?php echo $overtime_rate; ?>" id="overtime_rate">
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

            /* Update location info */
            $("#e_overtime_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=e_overtime_info&type=e_overtime_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_emp_overtime = $('#xin_table_emp_overtime').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/salary_overtime") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_emp_overtime.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'salary_commissions_info' && $_GET['type'] == 'salary_commissions_info') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_employee_edit_allowance'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_commissions_info', 'id' => 'e_salary_commissions_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_commissions_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $salary_commissions_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <!-- <div class="col-md-6">
            <div class="form-group">
                <label for="title"><?php echo $this->lang->line('dashboard_xin_title'); ?><i
                        class="hrsale-asterisk">*</i></label>
                <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>"
                    name="title" type="text" value="<?php echo $commission_title; ?>">
            </div>
        </div> -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="amount" type="text" value="<?php echo $commission_amount; ?>">
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

            /* Update location info */
            $("#e_salary_commissions_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=e_salary_commissions_info&type=e_salary_commissions_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_all_commissions = $('#xin_table_all_commissions')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employees/salary_all_commissions") ?>/" +
                                            $('#user_id').val(),
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            xin_table_all_commissions.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'salary_statutory_deductions_info' && $_GET['type'] == 'salary_statutory_deductions_info') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_employee_edit_allowance'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_statutory_deductions_info', 'id' => 'e_salary_statutory_deductions_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_statutory_deductions_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $statutory_deductions_id,
    );
    echo form_input($edata_usr8);
    ?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="statutory_options"><?php echo $this->lang->line('xin_salary_sd_options'); ?><i class="hrsale-asterisk">*</i></label>
                    <select name="statutory_options" id="statutory_options" class="form-control" data-plugin="select_hrm">
                        <option value="1" <?php if ($statutory_options == 1) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_sd_ssc_title'); ?></option>
                        <option value="2" <?php if ($statutory_options == 2) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_sd_phic_title'); ?></option>
                        <option value="3" <?php if ($statutory_options == 3) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_sd_hdmf_title'); ?></option>
                        <option value="4" <?php if ($statutory_options == 4) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_sd_wht_title'); ?></option>
                        <option value="0" <?php if ($statutory_options == 0) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_sd_other_sd_title'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>" name="title" type="text" value="<?php echo $deduction_title; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount'); ?>
                        <?php if ($system[0]->statutory_fixed != 'yes') : ?> (%) <?php endif; ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="amount" type="text" value="<?php echo $deduction_amount; ?>">
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

            /* Update location info */
            $("#e_salary_statutory_deductions_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=e_salary_statutory_deductions_info&type=e_salary_statutory_deductions_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_all_statutory_deductions = $(
                                '#xin_table_all_statutory_deductions').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/employees/salary_all_statutory_deductions") ?>/" +
                                        $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_all_statutory_deductions.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'salary_other_payments_info' && $_GET['type'] == 'salary_other_payments_info') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data">
            <?php echo $this->lang->line('xin_edit') . ' ' . $this->lang->line('xin_employee_set_other_payment'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_other_payments_info', 'id' => 'e_salary_other_payments_info', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_other_payment_info', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $other_payments_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>" name="title" type="text" value="<?php echo $payments_title; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="amount" type="text" value="<?php echo $payments_amount; ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Date<i class="hrsale-asterisk">*</i></label>
                    <input class="form-control cont_date" placeholder="date" name="date" type="text" value="<?php echo $date; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="amount" class="control-label">Ad Hoc Allowance<i class="hrsale-asterisk">*</i></label>
                    <select class="form-control" name="ad_hoc_allowance">
                        <option value="Ad Hoc" <?php echo $ad_hoc_allowance == 'Ad Hoc' ? 'selected' : '' ?>>Ad Hoc</option>
                        <option value="Recurring" <?php echo $ad_hoc_allowance == 'Recurring' ? 'selected' : '' ?>>Recurring</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                    <div class="form-group">
                        <label for="title">End Date</label>
                        <input class="form-control cont_date" placeholder="Date" name="end_date" type="text" value="<?php echo !empty($end_date) ? $end_date  : '' ?>">
                    </div>
                </div>

            <div class="col-md-4">
                <div class="form-group">
                    <input class="form-checkbox" name="cpf_applicable" type="checkbox" <?php echo $cpf_applicable == 1 ? 'checked' : '';?>>
                    <label for="amount">CPF applicable</label>
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
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });

            /* Update location info */
            $("#e_salary_other_payments_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=e_salary_other_payments_info&type=e_salary_other_payments_info&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_all_other_payments = $('#xin_table_all_other_payments')
                                .dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: "<?php echo site_url("admin/employees/salary_all_other_payments") ?>/" +
                                            $('#user_id').val(),
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            xin_table_all_other_payments.api().ajax.reload(function() {
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
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'emp_overtime_rate' && $_GET['type'] == 'emp_overtime_rate') {
?>
    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data">
            <?php echo $this->lang->line('xin_edit') . ' ' . "Employee Overtime Rate" ?></h4>
    </div>
    <?php $attributes = array('name' => 'emp_overtime_rate', 'id' => 'emp_overtime_rate', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_employee_overtime_rate', $attributes, $hidden); ?>
    <?php
    $edata_usr7 = array(
        'type'  => 'hidden',
        'id'  => 'user_id',
        'name'  => 'user_id',
        'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
        'type'  => 'hidden',
        'id'  => 'e_field_id',
        'name'  => 'e_field_id',
        'value' => $id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="overtime_type">Overtime Type
                    </label>
                    <select name="overtime_type" class="form-control" data-plugin="select_hrm" data-placeholder="Overtime Rate Type">
                        <option value="">Select Rate Type</option>
                        <!-- <option value="1" <?php echo $overtime_type == 1 ? "selected" : '' ?>>
                            Weekday </option>
                        <option value="2" <?php echo $overtime_type == 2 ? "selected" : '' ?>>
                            Rest Day/PH</option> -->
                        <option value="1" <?php echo $overtime_type == 1 ? "selected" : '' ?>>
                            Working Day </option>
                        <option value="2" <?php echo $overtime_type == 2 ? "selected" : '' ?>>
                            Non Working Day </option>
                        <option value="3" <?php echo $overtime_type == 3 ? "selected" : '' ?>>
                            Holiday </option>
                    </select>

                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="overtime_rate_type"><?php echo $this->lang->line('xin_employee_overtime_rate'); ?>
                        Type </label>
                    <select name="overtime_rate_type" id="overtime_rate_type_update" class="form-control" data-plugin="select_hrm" data-placeholder="Overtime Rate Type">
                        <option value="">Select Rate Type</option>
                        <option value="1" <?php echo $overtime_rate_type == 1 ? "selected" : '' ?>>
                            Percentage (x times basic pay)</option>
                        <option value="2" <?php echo $overtime_rate_type == 2 ? "selected" : '' ?>>
                            Amount</option>
                    </select>

                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="overtime_rate"><?php echo $this->lang->line('xin_employee_overtime_rate'); ?>
                        <i class="fa fa-info-circle" title="Hourly basic rate of pay for monthly-rated employees is calculated as - (12 x monthly basic rate of pay) / (52 x 44). By Default overtime rate is set as 1.5x(times) basic rate of pay." data-toggle="tooltip" data-placement="right" aria-hidden="true" style="z-index: 999;"></i></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_rate'); ?>" name="overtime_rate" type="text" id="overtime_rate_update" value="<?php echo $overtime_rate ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="overtime_rate_amount">Amount</label>
                    <input class="form-control" placeholder="Rate of Pay" name="overtime_rate_amount" type="text" value="<?php if ($overtime_pay_rate) echo $overtime_pay_rate; ?>" id="overtime_rate_amount_update">
                </div>
            </div>

            <!-- <div class="col-md-4">
                <div class="form-group">
                    <label for="overtime_rate">Status
                    </label>
                    <select name="status" class="form-control" data-plugin="select_hrm" data-placeholder="status">
                        <option value="">Select status </option>
                        <option value="1" <?php echo ($status == 1) ? 'selected' : ''; ?>>
                            Active </option>
                        <option value="0" <?php echo ($status == 0) ? 'selected' : ''; ?>>
                            Inactive </option>
                    </select>

                </div>
            </div> -->


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
                yearRange: '1950:' + (new Date().getFullYear() + 1)
            });


            $("#overtime_rate_type_update").on('change', function() {
                $("#overtime_rate_update").val("");
                $("#overtime_rate_amount_update").val("");
                console.log(11);
            });
            $("#overtime_rate_update").on('change paste keyup', function() {
                var rate = $(this).val();
                var rate_type = $('#overtime_rate_type_update').val();
                var user_id = $('#user_id').val();

                jQuery.get(base_url + "/get_overtime_rate/" + user_id + "/" + rate_type + "/" + rate, function(data, status) {
                    console.log(data);
                    if (data.result != '') {
                        jQuery('#overtime_rate_amount_update').val(data.result);
                    }
                });
            });



            /* Update location info */
            $("#emp_overtime_rate").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() +
                        "&is_ajax=29&data=emp_overtime_rate&type=emp_overtime_rate&form=" +
                        action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            // On page load:
                            var xin_table_all_over_time = $('#xin_table_all_over_time').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: site_url + "employees/getEmployeeOvertime/" + $('#user_id').val(),
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_all_over_time.api().ajax.reload(function() {
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
<?php } ?>