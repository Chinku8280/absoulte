<?php
/* Announcement view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('254',$role_resources_ids)) {?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new');?>
                <?php echo $this->lang->line('xin_announcement');?></h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_announcement', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open('admin/announcement/add_announcement', $attributes, $hidden);?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title"><?php echo $this->lang->line('xin_title');?><i
                                        class="hrsale-asterisk">*</i></label>
                                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_title');?>"
                                    name="title" type="text" value="">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date"><?php echo $this->lang->line('xin_start_date');?><i
                                                class="hrsale-asterisk">*</i></label>
                                        <input class="form-control date"
                                            placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly
                                            name="start_date" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date"><?php echo $this->lang->line('xin_end_date');?><i
                                                class="hrsale-asterisk">*</i></label>
                                        <input class="form-control date"
                                            placeholder="<?php echo $this->lang->line('xin_end_date');?>" readonly
                                            name="end_date" type="text" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php if($user_info[0]->user_role_id==1){ ?>
                                    <div class="form-group">
                                        <label for="designation"
                                            class="control-label"><?php echo $this->lang->line('module_company_title');?><i
                                                class="hrsale-asterisk">*</i></label>
                                        <select class="form-control" name="company_id" id="aj_company"
                                            data-plugin="select_hrm"
                                            data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                                            <option value=""></option>
                                            <?php foreach($get_all_companies as $company) {?>
                                            <option value="<?php echo $company->company_id?>">
                                                <?php echo $company->name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php } else {?>
                                    <?php $ecompany_id = $user_info[0]->company_id;?>
                                    <div class="form-group">
                                        <label for="designation"
                                            class="control-label"><?php echo $this->lang->line('module_company_title');?><i
                                                class="hrsale-asterisk">*</i></label>
                                        <select class="form-control" name="company_id" id="aj_company"
                                            data-plugin="select_hrm"
                                            data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                                            <option value=""></option>
                                            <?php foreach($get_all_companies as $company) {?>
                                            <?php if($ecompany_id == $company->company_id):?>
                                            <option value="<?php echo $company->company_id?>">
                                                <?php echo $company->name?></option>
                                            <?php endif;?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="col-md-6" id="location_ajax">
                                    <div class="form-group">
                                        <label for="name"><?php echo $this->lang->line('left_location');?><i
                                                class="hrsale-asterisk">*</i></label>
                                        <select disabled="disabled" name="location_id" id="location_id"
                                            class="form-control" data-plugin="select_hrm"
                                            data-placeholder="<?php echo $this->lang->line('left_location');?>">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <!--<div class="col-md-6">
                  <div class="form-group" id="department_ajax">
                    <label for="department" class="control-label"><?php echo $this->lang->line('xin_department');?></label>
                    <select class="form-control" name="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_department');?>">
                      <option value=""></option>
                    </select>
                  </div>
                </div>-->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label
                                            for="description"><?php echo $this->lang->line('xin_description');?></label>
                                        <textarea class="form-control textarea"
                                            placeholder="<?php echo $this->lang->line('xin_description');?>"
                                            name="description" cols="8" rows="5" id="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" id="department_ajax">
                                <label for="department"
                                    class="control-label"><?php echo $this->lang->line('xin_department');?><i
                                        class="hrsale-asterisk">*</i></label>
                                <select class="form-control" name="department_id" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $this->lang->line('xin_department');?>">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="summary"><?php echo $this->lang->line('xin_summary');?><i
                                        class="hrsale-asterisk">*</i></label>
                                <input type="text" class="form-control"
                                    placeholder="<?php echo $this->lang->line('xin_summary');?>" name="summary"
                                    id="summary">
                            </div>
                        </div>
                    </div>
                    <?php $count_module_attributes = $this->Custom_fields_model->count_announcements_module_attributes();?>
                    <?php if($count_module_attributes > 0):?>
                    <div class="row">
                        <?php $module_attributes = $this->Custom_fields_model->announcements_hrsale_module_attributes();?>
                        <?php foreach($module_attributes as $mattribute):?>
                        <?php if($mattribute->attribute_type == 'date'){?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                <input class="form-control date"
                                    placeholder="<?php echo $mattribute->attribute_label;?>"
                                    name="<?php echo $mattribute->attribute;?>" type="text">
                            </div>
                        </div>
                        <?php } else if($mattribute->attribute_type == 'select'){?>
                        <div class="col-md-4">
                            <?php $iselc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                            <div class="form-group">
                                <label
                                    for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                <select class="form-control" name="<?php echo $mattribute->attribute;?>"
                                    data-plugin="select_hrm"
                                    data-placeholder="<?php echo $mattribute->attribute_label;?>">
                                    <?php foreach($iselc_val as $selc_val) {?>
                                    <option value="<?php echo $selc_val->attributes_select_value_id?>">
                                        <?php echo $selc_val->select_label?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else if($mattribute->attribute_type == 'multiselect'){?>
                        <div class="col-md-4">
                            <?php $imulti_selc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                            <div class="form-group">
                                <label
                                    for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                <select multiple="multiple" class="form-control"
                                    name="<?php echo $mattribute->attribute;?>[]" data-plugin="select_hrm"
                                    data-placeholder="<?php echo $mattribute->attribute_label;?>">
                                    <?php foreach($imulti_selc_val as $multi_selc_val) {?>
                                    <option value="<?php echo $multi_selc_val->attributes_select_value_id?>">
                                        <?php echo $multi_selc_val->select_label?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else if($mattribute->attribute_type == 'textarea'){?>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label
                                    for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                <input class="form-control" placeholder="<?php echo $mattribute->attribute_label;?>"
                                    name="<?php echo $mattribute->attribute;?>" type="text">
                            </div>
                        </div>
                        <?php } else if($mattribute->attribute_type == 'fileupload'){?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                <input class="form-control-file" name="<?php echo $mattribute->attribute;?>"
                                    type="file">
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                <input class="form-control" placeholder="<?php echo $mattribute->attribute_label;?>"
                                    name="<?php echo $mattribute->attribute;?>" type="text">
                            </div>
                        </div>
                        <?php }	?>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                            <?php echo $this->lang->line('xin_save');?> </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?>
            <?php echo $this->lang->line('xin_announcements');?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                        <th width="420"><?php echo $this->lang->line('xin_title');?></th>
                        <th><?php echo $this->lang->line('left_company');?></th>
                        <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_start_date');?></th>
                        <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_end_date');?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
var announcement_url = '<?php echo site_url("announcement") ?>';
</script>