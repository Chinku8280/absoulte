<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-modal-data">Edit Employee Share Option</h4>
</div>
<?php $attributes = array('name' => 'update_employee_share', 'id' => 'update_employee_share', 'autocomplete' => 'off');?>
<?php $hidden = array('u_basic_info' => 'UPDATE');?>
<?php echo form_open('admin/employees/update_employee_share', $attributes, $hidden);?>
<?php
$edata_usr7 = array(
	'type'  => 'hidden',
	'id'  => 'user_id',
	'name'  => 'user_id',
	'value' => $employee_id,
);
echo form_input($edata_usr7);

?>

<div class="modal-body">
    <input type="hidden" name="share_id" value="<?php echo $share_id; ?>">
<div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_company">Company <i
                                                                        class="hrsale-asterisk">*</i></label>
                                                                <select name="so_company" id="so_company"
                                                                    class="form-control" data-plugin="select_hrm"
                                                                    data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                                                                    <option value="">Select Company</option>
                                                                    <?php foreach($get_all_companies as $company) {?>
                                                                    <option value="<?php echo $company->company_id;?>" <?php echo (($company->company_id == $company_id) ?'selected':'');?>>
                                                                        <?php echo $company->name;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_scheme">Share Option Scheme <i
                                                                        class="hrsale-asterisk">*</i></label>
                                                                <select name="so_scheme" id="so_scheme"
                                                                    class="form-control" data-plugin="select_hrm"
                                                                    data-placeholder="Share Option Scheme">
                                                                    <option value="">Select Scheme</option>
                                                                    <?php foreach($share_option_schemes as $s) {?>
                                                                    <option value="<?php echo $s->id;?>" <?php echo ($s->id == $so_scheme ? 'selected':''); ?>>
                                                                        <?php echo $s->scheme_shortname;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_scheme_plan">Type of Plan <i
                                                                        class="hrsale-asterisk">*</i></label>
                                                                <select name="so_scheme_plan" id="so_scheme_plan"
                                                                    class="form-control" data-plugin="select_hrm"
                                                                    data-placeholder="Share option plan">
                                                                    <option value="">Select Plan</option>
                                                                    <option value="1" <?php echo ($so_plan == 1 ? 'selected':'');?>>ESOP</option>
                                                                    <option value="2" <?php echo ($so_plan == 2 ? 'selected':'');?>>ESOW</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_date_of_grant">Date of Grant <i
                                                                        class="hrsale-asterisk">*</i></label>
                                                                <input class="form-control cont_date"
                                                                    placeholder="Date of Share Grant"
                                                                    name="so_date_of_grant" type="text" value="<?php echo $date_of_grant; ?>"
                                                                    id="so_date_of_grant" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_date_of_excerise">Date of Excercise <i
                                                                        class="hrsale-asterisk">*</i><i
                                                                        class="fa fa-info-circle"
                                                                        title="Date of exercise of ESOP or date of vesting of ESOW Plan (if applicable). If moratorium (i.e. selling restriction) is imposed, state the date the moratorium is lifted for the ESOP/ESOW Plans"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        aria-hidden="true"
                                                                        style="z-index: 999;"></i></label>
                                                                <input class="form-control cont_date"
                                                                    placeholder="Date of excerise or vesting"
                                                                    name="so_date_of_excerise" type="text" value="<?php echo $date_of_excercise; ?>"
                                                                    id="so_date_of_excerise" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_ex_price">Excerise Price <i
                                                                        class="hrsale-asterisk">*</i><i
                                                                        class="fa fa-info-circle"
                                                                        title="Exercise Price of ESOP / or Price Paid/ Payable per Share under ESOW Plan"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        aria-hidden="true"
                                                                        style="z-index: 999;"></i></label>
                                                                <input class="form-control" placeholder="Price"
                                                                    name="so_ex_price" type="text" value="<?php echo $excercise_price; ?>"
                                                                    id="so_ex_price">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_price_date_of_grant">Share Value at Date
                                                                    of Grant <i class="fa fa-info-circle"
                                                                        title="Open Market Value Per share as at the Date of Grant of ESOP/ ESOW Plan"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        aria-hidden="true"
                                                                        style="z-index: 999;"></i></label>
                                                                <input class="form-control"
                                                                    placeholder="Open Market Value at Date of Share Grant"
                                                                    name="so_price_date_of_grant" type="text" value="<?php echo $price_date_of_grant; ?>"
                                                                    id="so_price_date_of_grant">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_price_date_of_excerise">Share Value at
                                                                    Date of Excercise <i class="hrsale-asterisk">*</i><i
                                                                        class="fa fa-info-circle"
                                                                        title="Open Market Value Per Share as at the Date of Excercise"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        aria-hidden="true"
                                                                        style="z-index: 999;"></i></label>
                                                                <input class="form-control"
                                                                    placeholder="Market Value at Date of excerise or vesting"
                                                                    name="so_price_date_of_excerise" type="text"
                                                                    value="<?php echo $price_date_of_excercise; ?>" id="so_price_date_of_excerise">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="so_no_shares">No of Shares <i
                                                                        class="hrsale-asterisk">*</i></label>
                                                                <input class="form-control"
                                                                    placeholder="Number of Shares" name="so_no_shares"
                                                                    type="text" value="<?php echo $no_of_shares; ?>" id="so_no_shares">
                                                            </div>
                                                        </div>
                                                    </div>
</div>
<div class="modal-footer">
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?>
    <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function() {

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({
        width: '100%'
    });



    /* Update Employee Leave */
    $("#update_employee_share").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() +
                "&is_ajax=11&data=update_employee_share&type=update_employee_share&form=" +
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
                    var xin_table_emp_leaves = $('#xin_table_emp_leaves').dataTable({
                        "bDestroy": true,
                        "ajax": {
                            url: "<?php echo site_url("admin/employees/getEmployeeShareOptions") ?>/" +
                                $('#user_id').val(),
                            type: 'GET'
                        },
                        "fnDrawCallback": function(settings) {
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    });
                    xin_table_emp_leaves.api().ajax.reload(function() {
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