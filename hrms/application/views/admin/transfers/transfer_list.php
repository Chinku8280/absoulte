<?php
/* Transfer view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('210',$role_resources_ids)) {?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $system = $this->Xin_model->read_setting_info(1);?>
<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new');?>
                <?php echo $this->lang->line('xin_transfer');?></h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_transfer', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open('admin/transfers/add_transfer', $attributes, $hidden);?>
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
                                <?php } ?>
                                <div class="form-group" id="employee_ajax">
                                    <label for="employee"><?php echo $this->lang->line('xin_employee_transfer');?><i
                                            class="hrsale-asterisk">*</i></label>
                                    <select name="employee_id" id="select2-demo-6" class="form-control"
                                        data-plugin="select_hrm"
                                        data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                                        <option value=""></option>
                                        <?php foreach($all_employees as $employee) {?>
                                        <option value="<?php echo $employee->user_id;?>">
                                            <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="transfer_date"><?php echo $this->lang->line('xin_transfer_date');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <input class="form-control date"
                                                placeholder="<?php echo $this->lang->line('xin_transfer_date');?>"
                                                readonly name="transfer_date" type="text">
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
                                                name="description" cols="30" rows="5" id=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="department_ajax">
                                            <label
                                                for="transfer_department"><?php echo $this->lang->line('xin_transfer_to_department');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <select class="select2" data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('xin_select_department');?>"
                                                name="transfer_department">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if($system[0]->is_active_sub_departments=='yes'){?>
                                    <div class="col-md-6">
                                        <div class="form-group" id="subdepartment_ajax">
                                            <label
                                                for="transfer_department"><?php echo $this->lang->line('xin_transfer_to_subdepartment');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <select class="select2" data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('xin_select_subdepartment');?>"
                                                name="transfer_subdepartment">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group" id="designation_ajax">
                                            <label
                                                for="transfer_location"><?php echo $this->lang->line('xin_transfer_to_designation');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <select class="select2" data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('xin_transfer_select_designation');?>"
                                                name="transfer_designation">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="location_ajax">
                                            <label
                                                for="transfer_location"><?php echo $this->lang->line('xin_transfer_to_location');?><i
                                                    class="hrsale-asterisk">*</i></label>
                                            <select class="select2" data-plugin="select_hrm"
                                                data-placeholder="<?php echo $this->lang->line('xin_transfer_select_location');?>"
                                                name="transfer_location">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
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
            <?php echo $this->lang->line('xin_transfers');?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                        <th><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_employee_name');?></th>
                        <th><?php echo $this->lang->line('left_company');?></th>
                        <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_transfer_date');?></th>
                        <th><?php echo $this->lang->line('dashboard_xin_status');?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>