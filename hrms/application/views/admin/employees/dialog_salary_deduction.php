<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-modal-data">Edit Employee Salary Deduction</h4>
</div>
<?php $attributes = array('name' => 'update_employee_deduction', 'id' => 'update_employee_deduction', 'autocomplete' => 'off');?>
<?php $hidden = array('u_basic_info' => 'UPDATE');?>
<?php echo form_open('admin/employees/update_employee_deduction', $attributes, $hidden);?>
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
    <input type="hidden" name="deduction_id" value="<?php echo $deduction_id; ?>">
    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="deduction">Deduction Option<i class="hrsale-asterisk">*</i></label>
                                                                <select name="e_deduction_option" id="e_deduction_option" class="form-control" data-plugin="select_hrm">
                                                                   <?php foreach($deduction_type->result() as $type){ ?>
                                                                    <option value="<?php echo $type->deduction_type_id; ?>" <?php echo ($deduction_type_id == $type->deduction_type_id ?'selected':'');?>><?php echo $type->deduction_type; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="title">Deduction Type<i class="hrsale-asterisk">*</i></label>
                                                                <select name="e_deduction_type" id="e_deduction_type" class="form-control" data-plugin="select_hrm">
                                                                    <option value="1" <?php echo $type_id == 1 ?'selected':''; ?>>Recurring</option>
                                                                    <option value="2" <?php echo $type_id == 2 ?'selected':''; ?>>Ad Hoc</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="amount"><?php echo $this->lang->line('xin_amount'); ?>
                                                                  <i class="hrsale-asterisk">*</i>
                                                                </label>
                                                                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="e_amount" type="text" value="<?php echo $amount; ?>" id="e_amount">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="from_date">From Date
                                                                </label>
                                                                <input class="form-control cont_date" placeholder="From Date" name="e_from_date" type="text"  id="e_from_date" value="<?php echo $from_date; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="from_date">To Date
                                                                </label>
                                                                <input class="form-control cont_date" placeholder="To Date" name="e_to_date" type="text"  id="e_to_date" value="<?php echo $to_date; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <div class="box-footer hrsale-salary-button">
                                                                    <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="form-actions box-footer">&nbsp;</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>

                                            </div>

<script type="text/javascript">
$(document).ready(function() {

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({
        width: '100%'
    });



    /* Update Employee Leave */
    $("#update_employee_deduction").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() +
                "&is_ajax=11&data=update_employee_deduction&type=update_employee_deduction&form=" +
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
                    var xin_table_deductions =$('#xin_table_deductions').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_deductions/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    xin_table_deductions.api().ajax.reload(function() {
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