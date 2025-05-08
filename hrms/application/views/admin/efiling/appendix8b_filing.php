<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource();?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $system = $this->Xin_model->read_setting_info(1);?>

<div class="row">
    <div class="col-md-4">
        <div class="box mb-4 <?php echo $get_animate; ?>">
            <div class="box-header  with-border">
                <h3 class="box-title">Appendix 8B YA (Year of Assessment)</h3>
            </div>

            <div class="box-body">
                
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="year">Select Year</label>
                                <select class="form-control" name="year" id="year" data-plugin="select_hrm"
                                    data-placeholder="Assessment Year"
                                    data-csrf-name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                    data-csrf-hash="<?php echo $this->security->get_csrf_hash(); ?>"
                                    data-user-id="<?php echo $session['user_id'] ?>">
                                    <option value=""></option>
                                    <?php for ($ay = 6; $ay > 0; $ay -= 1) {?>
                                    <?php $ayear = date('Y', strtotime("-$ay year"));?>
                                    <option value="<?php echo $ayear; ?>" <?php if ($year_a == $ayear) { echo 'selected'; }?>><?php echo $ayear; ?></option>
                                    <?php }?>
                                    <option value="<?php echo date('Y'); ?>" <?php if ($year_a == date('Y')) { echo 'selected'; }?>><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y', strtotime('1 year')); ?>" <?php if ($year_a == date('Y', strtotime('1 year'))) { echo 'selected'; } ?>><?php echo date('Y', strtotime('1 year')); ?></option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                

                <div class="form-actions box-footer">
                    <?php if ($is_generated) {?>
                    <div id="reset_cont">
                        <div class="row">
                            <div class="col-md-4">
                                <?php $file_url = base_url() . str_replace('./', '', $is_generated->ap8b_file);?>
                                <a href="<?php echo $file_url ?>" download>Download XML File</a>
                            </div>
                            <div class="col-md-8">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-danger waves-effect waves-light delete"
                                    data-toggle="modal" data-target="#ap8bResetModal" data-record-id="">Reset Appendix 8B for
                                    <span class="fy_label"><?php echo $year_a ?><span></button>

                            </div>
                        </div>

                    </div>
                    <?php } else {?>
                    <div id="generate_cont">
                        <?php $attributes = array('name' => 'ap8b_generate_form', 'id' => 'ap8b_generate_form', 'class' => 'm-b-1 add form-hrm');?>
                        <?php $hidden = array('user_id' => $session['user_id']);?>
                        <?php echo form_open('admin/efiling/generateAp8b', $attributes, $hidden); ?>
                        <input type="hidden" name="year" id="gen_fy" value="<?php echo $year_a ?>">

                        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => 'Generate Appendix 8B for <span class="fy_label">' . $year_a . '<span>')); ?>
                        <?php echo form_close(); ?>
                    </div>
                    <?php }?>

                </div>

            </div>


        </div>
    </div>

    <div class="col-md-8">
        <div class="box <?php echo $get_animate; ?>">
            <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Employee Summary for <span
                        class="fy_label"><?php echo $year_a ?></span> </h3>
            </div>
            <div class="box-body">
                <div class="box-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered" id="xin_table_employee_summary_ap8b"
                        style="width:100%;">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                <th>Citizenship</th>
                                <th>Total Taxable Amount</th>
                                <th>IR8A D8</th>
                                <th>Eligible</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box <?php echo $get_animate; ?>">
            <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Employee Appendix 8B form for <span
                        class="fy_label"><?php echo $year_a ?></span> </h3>
            </div>
            <div class="box-body">
                <div class="box-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered" id="xin_table_employee_ap8b_form"
                        style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width:80px;"><?php echo $this->lang->line('xin_action'); ?></th>
                                <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                                <th>Total Gross Amount of Gains (EEBR)</th>
                                <th>Total Gross Amount of Gains (ESIR) SMEs</th>
                                <th>Total Gross Amount of Gains (ESIR) CORPs</th>
                                <th>Total Gross Amount of Gains (ESIR) START-UPs</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($is_generated) {?>
<!-- Modal -->
<div class="modal fade" id="ap8bResetModal" tabindex="-1" role="dialog" aria-labelledby="ap8bResetModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="ap8bResetModalLabel">Reset Appendix 8B to Start Over</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <h4>Are you sure you want to reset Appendix 8B for <?php echo $year_a ?>?</h4>
                <p><b>Note: </b>Resetting form Appendix 8B might reset other forms as well.</p>
                <?php $attributes = array('name' => 'ap8b_reset_form', 'id' => 'ap8b_reset_form', 'class' => 'm-b-1 add form-hrm');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open('admin/efiling/resetAp8b', $attributes, $hidden); ?>
                <input type="hidden" name="year" value="<?php echo $year_a ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => 'btn btn-danger', 'content' => 'Reset Appendix 8A')); ?>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php }?>