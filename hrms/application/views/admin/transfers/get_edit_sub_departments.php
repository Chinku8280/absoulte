<?php $result = get_sub_departments($department_id);?>

<div class="form-group" id="subdepartment_ajax">
    <label for="designation"><?php echo $this->lang->line('xin_transfer_to_subdepartment');?>
        <!-- <i class="hrsale-asterisk">*</i> -->
    </label>
    <select class="select2" data-plugin="select_hrm"
        data-placeholder="<?php echo $this->lang->line('xin_select_subdepartment');?>" name="edit_transfer_subdepartment"
        id="edit_subdepartment">
        <option value=""></option>
        <?php foreach($result as $deparment) {?>
        <option value="<?php echo $deparment->sub_department_id?>"><?php echo $deparment->department_name?></option>
        <?php } ?>
    </select>
</div>
<?php
//}
?>
<script type="text/javascript">
$(document).ready(function() {
    // get designations
    jQuery("#edit_subdepartment").change(function() {
        jQuery.get(base_url + "/designation/" + jQuery(this).val(), function(data, status) {
            jQuery('#designation_div').html(data);
        });
    });
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({
        width: '100%'
    });
});
</script>