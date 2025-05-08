<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> E Filing Details </h3>
    </div>
    <div class="box-body">
        <?php $attributes = array('name' => 'efiling_details', 'id' => 'efiling_details', 'autocomplete' => 'off'); ?>
        <?php echo form_open('admin/efiling/efiling_details', $attributes); ?>
        <div class="bg-white">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="csn">Select Company</label>
                        <select class="form-control" name="company_id" id="filter_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                            <option value="">Select</option>
                            <?php foreach ($get_all_companies as $company) { ?>
                                <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="csn">CPF Submission Number (CSN)</label>
                        <!-- <input class="form-control" placeholder="CPF Submission Number" name="csn" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->csn ?>"> -->
                        <input class="form-control" placeholder="CPF Submission Number" name="csn" type="text" id="csn">
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="idtype">Organization ID Type<i class="hrsale-asterisk">*</i></label>
                        <select class="form-control" name="idtype" id="idtype" data-plugin="select_hrm" data-placeholder="Organization ID Type">
                            <option value=""></option>
                            <!-- <option value="7" <?php if (isset($efiling) && $efiling->organisation_id_type == '7') echo 'selected' ?>>UEN (Business Registration No issued by ACRA)</option>
                            <option value="8" <?php if (isset($efiling) && $efiling->organisation_id_type == '8') echo 'selected' ?>>UEN (Local Company Registration No issued by ACRA)</option>
                            <option value="A" <?php if (isset($efiling) && $efiling->organisation_id_type == 'A') echo 'selected' ?>>ASGD (Tax Reference number assigned by IRAS)</option>
                            <option value="I" <?php if (isset($efiling) && $efiling->organisation_id_type == 'I') echo 'selected' ?>>ITR (Income Tax Reference number assigned by IRAS)</option>
                            <option value="U" <?php if (isset($efiling) && $efiling->organisation_id_type == 'U') echo 'selected' ?>>UENO (Unique Entity Number Others e.g. Foreign Company Registration Number)</option> -->
                            <option value="7">UEN (Business Registration No issued by ACRA)</option>
                            <option value="8">UEN (Local Company Registration No issued by ACRA)</option>
                            <option value="A">ASGD (Tax Reference number assigned by IRAS)</option>
                            <option value="I">ITR (Income Tax Reference number assigned by IRAS)</option>
                            <option value="U">UENO (Unique Entity Number Others e.g. Foreign Company Registration Number)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="idno" class="control-label">Organization ID No<i class="hrsale-asterisk">*</i></label>
                        <!-- <input class="form-control" placeholder="Organization ID No" name="idno" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->organisation_id_no ?>"> -->
                        <input class="form-control" placeholder="Organization ID No" name="idno" type="text" id="idno">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="authorisedname">Authorised Person Name<i class="hrsale-asterisk">*</i></label>
                        <!-- <input class="form-control" placeholder="Authorised Person Name" name="authorisedname" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->authorised_name ?>"> -->
                        <input class="form-control" placeholder="Authorised Person Name" name="authorisedname" type="text" id="authorisedname">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="authoriseddesignation" class="control-label">Authorised Person Designation<i class="hrsale-asterisk">*</i></label>
                        <!-- <input class="form-control" placeholder="Authorised Person Designation" name="authoriseddesignation" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->authorised_designation ?>"> -->
                        <input class="form-control" placeholder="Authorised Person Designation" name="authoriseddesignation" type="text" id="authoriseddesignation">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="aurthorisedidtype">Authorised Person ID Type<i class="hrsale-asterisk">*</i></label>
                        <select class="form-control" name="aurthorisedidtype" id="aurthorisedidtype" data-plugin="select_hrm" data-placeholder="Authorised ID Type">
                            <option value=""></option>
                            <?php if ($id_type) {
                                foreach ($id_type as $i) { ?>
                                    <!-- <option value="<?php echo $i->id ?>" <?php if ($efiling->authorised_id_type == $i->id) echo "selected" ?>><?php echo $i->id_name ?></option> -->
                                    <option value="<?php echo $i->id ?>"><?php echo $i->id_name ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="authorisedidno" class="control-label">Authorised Person ID No<i class="hrsale-asterisk">*</i></label>
                        <!-- <input class="form-control" placeholder="Organization ID No" name="authorisedidno" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->authorised_id_no ?>"> -->
                        <input class="form-control" placeholder="Organization ID No" name="authorisedidno" type="text" id="authorisedidno">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="authorisedemail">Authorised Person Email<i class="hrsale-asterisk">*</i></label>
                        <!-- <input class="form-control" placeholder="Authorised Person Name" name="authorisedemail" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->authorised_email ?>"> -->
                        <input class="form-control" placeholder="Authorised Person Name" name="authorisedemail" type="text" id="authorisedemail">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="authorisedphone" class="control-label">Authorised Person Telephone Number<i class="hrsale-asterisk">*</i></label>
                        <!-- <input class="form-control" placeholder="Authorised Person Designation" name="authorisedphone" type="text" value="<?php if (isset($efiling) && $efiling) echo $efiling->authorised_phone ?>"> -->
                        <input class="form-control" placeholder="Authorised Person Designation" name="authorisedphone" type="text" id="authorisedphone">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?> </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
</div>