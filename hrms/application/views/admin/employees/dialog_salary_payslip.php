<div class="box-header with-border">
    <h3 class="box-title">
        <?php echo $this->lang->line('xin_employee_update_salary'); ?> </h3>
</div>
<div class="box-body pb-2">
    <?php $attributes = array('name' => 'employee_update_salary', 'id' => 'employee_update_salary1', 'autocomplete' => 'off'); ?>
    <?php $hidden = array('user_id' => $user_id, 'u_basic_info' => 'UPDATE'); ?>
    <?php echo form_open('admin/employees/update_salary_option', $attributes, $hidden); ?>
    <div class="bg-white">
        <input type="hidden" name="type" value="employee_update_salary">
        <input type="hidden" name="salary_type" value="update_salary">
        <input type="hidden" name="salary_id" value="<?php echo $this->input->get('salary_id');?>">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="wages_type"><?php echo $this->lang->line('xin_employee_type_wages'); ?><i class="hrsale-asterisk">*</i></label>
                    <select name="wages_type" id="wages_type1" class="form-control" data-plugin="select_hrm">
                        <option value="1" <?php if ($wages_type == 1) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_payroll_basic_salary'); ?>
                        </option>
                        <option value="2" <?php if ($wages_type == 2) : ?> selected="selected" <?php endif; ?>>
                            <?php echo $this->lang->line('xin_employee_daily_wages'); ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="basic_salary"><?php echo $this->lang->line('xin_salary_title'); ?><i class="hrsale-asterisk">*</i></label>
                    <input class="form-control basic_salary" placeholder="<?php echo $this->lang->line('xin_salary_title'); ?>" name="basic_salary" type="text" value="<?php echo $basic_salary; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="form-actions box-footer">
                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script>
    jQuery("#employee_update_salary1").submit(function(e) {
        /*Form Submit*/
        console.log(11);
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        toastr.info(processing_request);
        $.ajax({
                type: "POST",
                url: e.target.action,
                data: obj.serialize() + "&is_ajax=3&data=employee_update_salary&type=employee_update_salary&form=" + action,
                cache: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.icon-spinner3').hide();
                        $('.save').prop('disabled', false);
                    } else {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        
                        $('#edit-modal-data').modal('hide');

                        var xin_table_salary = $('#xin_table_all_payslips').dataTable({
                            "bDestroy": true,
                            "ajax": {
                                url: site_url + "employees/set_salary/" + $('#user_id').val(),
                                type: 'GET'
                            },
                            "fnDrawCallback": function(settings) {
                                $('[data-toggle="tooltip"]').tooltip();
                            }
                        });



                    xin_table_salary.api().ajax.reload(function() {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
</script>