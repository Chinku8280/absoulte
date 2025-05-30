<?php
/* Warning view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('225',$role_resources_ids)) {?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header  with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new');?>
                <?php echo $this->lang->line('xin_warning');?></h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_warning', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open('admin/warning/add_warning', $attributes, $hidden);?>
                <div class="bg-white">
                    <div class="box-block">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if($user_info[0]->user_role_id==1){ ?>
                                <div class="form-group">
                                    <label for="first_name"><?php echo $this->lang->line('left_company');?><i
                                            class="hrsale-asterisk">*</i></label>
                                    <select class="form-control" name="company_id" id="aj_company"
                                        data-plugin="select_hrm"
                                        data-placeholder="<?php echo $this->lang->line('left_company');?>">
                                        <option value=""></option>
                                        <?php foreach($get_all_companies as $company) {?>
                                        <option value="<?php echo $company->company_id?>"><?php echo $company->name?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php } else {?>
                                <?php $ecompany_id = $user_info[0]->company_id;?>
                                <div class="form-group">
                                    <label for="first_name"><?php echo $this->lang->line('left_company');?><i
                                            class="hrsale-asterisk">*</i></label>
                                    <select class="form-control" name="company_id" id="aj_company"
                                        data-plugin="select_hrm"
                                        data-placeholder="<?php echo $this->lang->line('left_company');?>">
                                        <option value=""></option>
                                        <?php foreach($get_all_companies as $company) {?>
                                        <?php if($ecompany_id == $company->company_id):?>
                                        <option value="<?php echo $company->company_id?>"><?php echo $company->name?>
                                        </option>
                                        <?php endif;?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php }?>
                                <div class="form-group" id="employee_ajax">
                                    <label for="warning_to"><?php echo $this->lang->line('xin_warning_to');?><i
                                            class="hrsale-asterisk">*</i></label>
                                    <select name="warning_to" id="select2-demo-6" class="form-control"
                                        data-plugin="select_hrm"
                                        data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type"><?php echo $this->lang->line('xin_warning_type');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <select class="select2" data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('xin_warning_type');?>"
                                                name="type">
                                                <option value=""></option>
                                                <?php foreach($all_warning_types as $warning_type) {?>
                                                <option value="<?php echo $warning_type->warning_type_id?>">
                                                    <?php echo $warning_type->type;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject"><?php echo $this->lang->line('xin_subject');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <input class="form-control"
                                                placeholder="<?php echo $this->lang->line('xin_subject');?>"
                                                name="subject" type="text">
                                        </div>
                                    </div>
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
                                                name="description" cols="30" rows="5" id="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="warning_employee_ajax">
                                            <label for="warning_by"><?php echo $this->lang->line('xin_warning_by');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <select name="warning_by" id="select2-demo-6" class="form-control"
                                                data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="warning_date"><?php echo $this->lang->line('xin_warning_date');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <input class="form-control date"
                                                placeholder="<?php echo $this->lang->line('xin_warning_date');?>"
                                                readonly name="warning_date" type="text">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <fieldset class="form-group">
                                        <label
                                            for="attachment"><?php echo $this->lang->line('xin_attachment');?></label>
                                        <input type="file" class="form-control-file" id="attachment" name="attachment">
                                        <small><?php echo $this->lang->line('xin_company_file_type');?></small>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions box-footer">
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                                <?php echo $this->lang->line('xin_save');?> </button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?>
            <?php echo $this->lang->line('left_warnings');?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                        <th><i class="fa fa-user"></i> <?php echo $this->lang->line('dashboard_single_employee');?></th>
                        <th><?php echo $this->lang->line('left_company');?></th>
                        <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_warning_date');?></th>
                        <th><?php echo $this->lang->line('xin_subject');?></th>
                        <th><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_warning_by');?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>