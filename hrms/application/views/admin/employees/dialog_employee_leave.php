<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-modal-data">Edit Employee Leave</h4>
</div>
<?php $attributes = array('name' => 'update_employee_leave', 'id' => 'update_employee_leave', 'autocomplete' => 'off');?>
<?php $hidden = array('u_basic_info' => 'UPDATE');?>
<?php echo form_open('admin/employees/update_employee_leave', $attributes, $hidden);?>
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
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="leavetype">Leave Type</label>
                <input type="hidden" class="form-control" name="id"
                    placeholder="No of Leaves in a year" value="<?php echo $id?>">
                <select name="leavetype" id="leavetype" class="form-control" data-plugin="select_hrm"
                    data-placeholder="Leave Type">
                    <option value="">Select Leave Type</option>
                    <option value="<?php echo $leave_type_id?>" selected><?php echo $type_name?></option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="leaveyear">Year</label>
                <select name="leaveyear" id="leaveyear" class="form-control" data-plugin="select_hrm"
                    data-placeholder="Year">
                    <option value="<?php echo $leave_year?>" selected><?php echo $leave_year?></option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="noofdays">No of Days</label>
                <input type="text" class="form-control" name="noofdays" id="noofdays"
                    placeholder="No of Leaves in a year" value="<?php echo $no_of_leaves?>">
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
    $("#update_employee_leave").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() +
                "&is_ajax=11&data=update_employee_leave&type=update_employee_leave&form=" +
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
                            url: "<?php echo site_url("admin/employees/getEmployeeLeaves") ?>/" +
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