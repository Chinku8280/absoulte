<?php $result = $this->Company_model->ajax_company_departments_info($company_id);?>

<div class="form-group">
    <label for="designation"><?php echo $this->lang->line('xin_transfer_to_department');?><i
            class="hrsale-asterisk">*</i></label>
    <select class="select2" data-plugin="select_hrm"
        data-placeholder="<?php echo $this->lang->line('xin_select_department');?>" name="transfer_department" id="transfer_department">
        <option value=""></option>
        <?php foreach($result as $deparment) {?>
        <option value="<?php echo $deparment->department_id?>"><?php echo $deparment->department_name?></option>
        <?php } ?>
    </select>
</div>
<?php
//}
?>
<script type="text/javascript">
$(document).ready(function() {
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({
        width: '100%'
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	// get sub departments
	jQuery("#transfer_department").change(function(){
		jQuery.get(base_url+"/get_sub_departments/"+jQuery(this).val(), function(data, status){
            console.log(data);
			jQuery('#subdepartment_ajax').html(data);
		});
	});
});
</script>