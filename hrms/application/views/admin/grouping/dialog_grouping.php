<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['grouping_id']) && $_GET['data']=='grouping'){
?>

<div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
  <h4 class="modal-title" id="edit-modal-data">Edit Grouping</h4>
</div>
<?php $attributes = array('name' => 'edit_grouping', 'id' => 'edit_grouping', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $grouping_id, 'ext_name' => $grouping_name);?>
<?php echo form_open('admin/grouping/update_sub_record/'.$grouping_id, $attributes, $hidden);?>
<div class="modal-body">
  <div class="form-group">
    <label for="department-name" class="form-control-label">Grouping <?php echo $this->lang->line('xin_name');?>:</label>
    <input type="text" class="form-control" name="grouping_name" value="<?php echo $grouping_name?>">
  </div>
  <div class="form-group">
      <label for="xin_hr_main_department"><?php echo $this->lang->line('xin_hr_main_department');?></label>
      <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_department');?>" name="department_id" id="edit_aj_department">
        <option value=""></option>
        <?php foreach($all_departments as $deparment) {?>
        <option value="<?php echo $deparment->department_id;?>" <?php if($deparment->department_id==$department_id):?> selected="selected"<?php endif;?>><?php echo $deparment->department_name;?></option>
        <?php } ?>
      </select>
    </div>
    <?php $subresult = get_sub_departments($department_id);?>
    
    <div class="form-group" id="edit_sub_department_ajax">
        <label for="name"><?php echo $this->lang->line('xin_hr_sub_department');?><i class="hrsale-asterisk">*</i></label>
        <select class="form-control" name="subdepartment_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>" id="aj_subdepartment">
            <option value=""></option>
            <?php foreach($subresult as $sbdeparment) {?>
                <option value="<?php echo $sbdeparment->sub_department_id;?>" <?php if($sub_department_id==$sbdeparment->sub_department_id):?> selected <?php endif;?>><?php echo $sbdeparment->department_name;?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="modal-footer"> <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> </div>
<?php echo form_close(); ?> 
<script type="text/javascript">
$(document).ready(function(){
    jQuery("#edit_aj_department").change(function(){
		jQuery.get(escapeHtmlSecure(base_url+"/get_sub_department/"+jQuery(this).val()), function(data, status){
			jQuery('#edit_sub_department_ajax').html(data);
		});
	});

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	/* Edit data */
	$("#edit_grouping").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&edit_type=grouping&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					// On page load: datatable
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : "<?php echo htmlspecialchars(site_url("admin/grouping/grouping_list")); ?>",
							type : 'GET'
						},
						dom: 'lBfrtip',
						"buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
						"fnDrawCallback": function(settings){
						$('[data-toggle="tooltip"]').tooltip();          
						}
					});
					xin_table.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					}, true);
					$('.edit-modal-data').modal('toggle');
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php }
?>
