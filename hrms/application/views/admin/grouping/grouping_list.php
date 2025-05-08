<?php
/* Sub Departments view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>

<div class="row m-b-1 <?php echo $get_animate;?>">
  <?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
  <?php if(in_array('240',$role_resources_ids)) {?>
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> Grouping </h3>
      </div>
      <div class="box-body">
        <?php $attributes = array('name' => 'add_grouping', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/grouping/add_grouping', $attributes, $hidden);?>
        <div class="form-group">
        <label for="name">Grouping <?php echo $this->lang->line('xin_name');?><i class="hrsale-asterisk">*</i></label>
          <?php
			$data = array(
			  'name'        => 'grouping_name',
			  'id'          => 'grouping_name',
			  'value'       => '',
			  'placeholder'   => $this->lang->line('xin_name'),
			  'class'       => 'form-control',
			);
		echo form_input($data);
		?>
        </div>
        <div class="form-group">
          <label for="designation"><?php echo $this->lang->line('xin_hr_main_department');?><i class="hrsale-asterisk">*</i></label>
          <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_department');?>" name="department_id" id="aj_department">
            <option value=""></option>
            <?php foreach($all_departments as $deparment) {?>
            <option value="<?php echo $deparment->department_id;?>"><?php echo $deparment->department_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group" id="sub_department_ajax">
          <label for="name"><?php echo $this->lang->line('xin_hr_sub_department');?><i class="hrsale-asterisk">*</i></label>
          <select disabled="disabled" name="sub_department_id" id="sub_department_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_hr_sub_department');?>">
            <option value=""></option>
          </select>
        </div>
        <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
  <?php $colmdval = 'col-md-8';?>
  <?php } else {?>
  <?php $colmdval = 'col-md-12';?>
  <?php } ?>
  <div class="<?php echo $colmdval;?>">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Grouping </h3>
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th><?php echo $this->lang->line('xin_action');?></th>
                <th><?php echo $this->lang->line('xin_name');?></th>
                <th><?php echo $this->lang->line('xin_hr_main_department');?></th>
                <th><?php echo $this->lang->line('xin_hr_sub_departments');?></th>
                <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_created_at');?></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
