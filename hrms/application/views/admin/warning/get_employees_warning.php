<?php $result = $this->Department_model->ajax_company_employee_info($company_id);?>

<?php

$new_data = array();

foreach($result as $item){
$role_user = $this->Xin_model->read_user_role_info($item->user_role_id);
    if(!is_null($role_user)){
        $role_resources_ids = explode(',',$role_user[0]->role_resources);
    } else {
        $role_resources_ids = explode(',',0);	
    }

    if(in_array('20',$role_resources_ids)){
        $new_data[] = [
            'user_id'       => $item->user_id,
            'first_name'    => $item->first_name,
            'last_name'     => $item->last_name
        ];
    }
}
?>


<div class="form-group">
    <label for="xin_department_head"><?php echo $this->lang->line('xin_warning_by');?><i
            class="hrsale-asterisk">*</i></label>
    <select name="warning_by" id="select2-demo-6" class="form-control" data-plugin="select_hrm"
        data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
        <option value=""></option>
        <?php foreach($new_data as $employee) {?>
        <option value="<?php echo $employee['user_id'];?>"> <?php echo $employee['first_name'].' '.$employee['last_name'];?>
        </option>
        <?php } ?>
    </select>
</div>
<?php
//}
?>




<!-- <div class="form-group">
    <label for="xin_department_head"><?php echo $this->lang->line('xin_warning_by');?><i
            class="hrsale-asterisk">*</i></label>
    <select name="warning_by" id="select2-demo-6" class="form-control" data-plugin="select_hrm"
        data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
        <option value=""></option>
        <?php foreach($result as $employee) {?>
        <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?>
        </option>
        <?php } ?>
    </select>
</div>
<?php
//}
?> -->
<script type="text/javascript">
$(document).ready(function() {
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({
        width: '100%'
    });
});
</script>