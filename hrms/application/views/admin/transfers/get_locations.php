<?php $result = $this->Department_model->ajax_location_information($location_id);?>
<div class="form-group">
    <label for="designation"><?php echo $this->lang->line('xin_transfer_to_location');?><i
            class="hrsale-asterisk">*</i></label>
    <select class="form-control" name="transfer_location" data-plugin="select_hrm"
        data-placeholder="<?php echo $this->lang->line('xin_transfer_select_location');?>">
        <option value=""></option>
        <?php foreach($result as $location) {?>
        <option value="<?php echo $location->location_id?>"><?php echo $location->location_name?></option>
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