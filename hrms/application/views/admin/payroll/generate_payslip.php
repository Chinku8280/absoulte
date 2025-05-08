<style>
	/* Ensure table layout is fixed so columns respect the width */
	#xin_table_bulk {
		width: 100%;
		/* table-layout: fixed; */
	}

	/* Set the width for each column */
	#xin_table_bulk .col-1 {
		width: 1%;
	}

	#xin_table_bulk .col-2 {
		width: 4%;
	}

	#xin_table_bulk .col-3 {
		width: 3%;
	}

	/* Optional: Adjust header and body alignment if needed */
	#xin_table_bulk th,
	#xin_table_bulk td {
		text-align: left;
		padding: 1px;
		/* border: 0.1px solid black;  */
		box-shadow: 0 0 0 0.5px black;
		/* Creates a thin border effect */


		padding: 8px;

	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<?php

if ($this->input->get('ismobile') == 'true') { ?>
	<style>
		main {
			overflow-x: hidden;
			overflow-y: auto;
		}

		.vl {
			border-left: 2px solid rgba(0, 0, 0, 0.215);
			height: 70px;
			left: 50%;
			margin-left: -3px;
			top: 0;
		}
	</style>


	<!--Content-->
	<main class="py-3 mt-3 mb-5 px-4">
		<section class="mt-3">
			<h3 class="theme-color text-uppercase text-start my-0 py-0 " style="font-size: 24px;">Hey <?php echo $name ?>,</h3>
			<p class="my-0 py-0 " style="font-size: 15px;">Welcome To My Pay Summary Details</p>

			<div class="theme-shadow rounded-3  mt-3">
				<div class="bg-theme d-inline-flex w-100 rounded-top-3 p-3">
					<img src="<?php echo base_url('assets/massets/wallet-vector.png') ?>" alt="" style="width: 29px;height: 24px;" class="my-auto ">
					<p class="text-white my-auto ms-2 " style="font-size: 20px;">Payroll</p>
				</div>


				<!--Item1-->

				<?php foreach ($main_data as $payslip) { ?>
					<!-- <a href="<?php //echo base_url('admin/payroll/payslip/id/' . $payslip->payslip_key) 
									?>?ismobile=true"> -->
					<div class="d-inline-flex  justify-content-between align-items-center w-100 p-2 my-2 bg-white " style="color: #484A4B;">

						<div class="d-flex justify-content-center align-items-center ">
							<div class="mx-1 my-auto theme-eclipse rounded-circle d-flex justify-content-center align-items-center" style="height: 28px;width: 28px;">
								<img src="<?php echo base_url('assets/massets/calendar-vector.png') ?>" alt="" class="img-fluid" style="height: 14px; width: 14px;">
							</div>
							<div class="mx-1 my-auto p-2 border-danger">
								<p class="text-start  m-0 pb-1 theme-color" style="font-size: 15px; font-weight: 400 ;line-height: normal; "><?php echo date('F Y', strtotime('01-' . $payslip->salary_month)) ?></p>
								<!-- <p class="text-start  m-0 pb-1" style="font-size: 12px; color: #969393">01/10/2022 -
                                31/10/2022</p> -->

							</div>
						</div>


						<div class="mx-1 my-auto p-2 ">
							<p class="text-center m-0 pb-1 theme-color" style="font-size: 16px; font-weight: 400 ;line-height: normal;">
								<span>&dollar;</span><?php echo $payslip->basic_salary ?>
							</p>
						</div>
					</div>
					<!-- </a> -->

				<?php } ?>

				<div class="bg-white w-100 rounded-bottom-3 p-1 mb-0"></div>


			</div>
		</section>
	</main>

<?php } else { ?>
	<?php
	/* Generate Payslip view
*/
	?>
	<?php $session = $this->session->userdata('username'); ?>
	<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
	<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
	<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
	<?php $system = $this->Xin_model->read_setting_info(1); ?>
	<?php
	$is_half_col = '5';
	if ($system[0]->is_half_monthly == 1) {
		$bulk_form_url = 'admin/payroll/add_half_pay_to_all';
		$is_half_col = '12';
	} else {
		$bulk_form_url = 'admin/payroll/add_pay_to_all';
		$is_half_col = '5';
	}

	?>

	<div class="row <?php echo $get_animate; ?>">
		<div class="col-md-<?php echo $is_half_col; ?>">
			<div class="box mb-4">
				<div class="box-header with-border">
					<h3 class="box-title"> <?php echo $this->lang->line('left_generate_payslip'); ?> </h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<?php $attributes = array('name' => 'set_salary_details', 'id' => 'set_salary_details', 'class' => 'm-b-1 add form-hrm'); ?>
							<?php $hidden = array('user_id' => $session['user_id']); ?>
							<?php echo form_open('admin/payroll/set_salary_details', $attributes, $hidden); ?>
							<div class="row">
								<?php if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) { ?>
									<div class="col-md-4">
										<?php if ($user_info[0]->user_role_id == 1) { ?>
											<div class="form-group">
												<label for="department"><?php echo $this->lang->line('module_company_title'); ?></label>
												<select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
													<option value="0"><?php echo $this->lang->line('xin_all_companies'); ?></option>
													<?php foreach ($all_companies as $company) { ?>
														<option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
														</option>
													<?php } ?>
												</select>
											</div>
										<?php } else { ?>
											<?php $ecompany_id = $user_info[0]->company_id; ?>
											<div class="form-group">
												<label for="department"><?php echo $this->lang->line('module_company_title'); ?></label>
												<select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
													<option value=""><?php echo $this->lang->line('module_company_title'); ?>
													</option>
													<?php foreach ($all_companies as $company) { ?>
														<?php if ($ecompany_id == $company->company_id) : ?>
															<option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
															</option>
														<?php endif; ?>
													<?php } ?>
												</select>
											</div>
										<?php } ?>
									</div>
									<div class="col-md-4">
										<div class="form-group" id="employee_ajax">
											<label for="department"><?php echo $this->lang->line('dashboard_single_employee'); ?></label>
											<select id="employee_id" name="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee'); ?>">
												<option value="0"><?php echo $this->lang->line('xin_all_employees'); ?></option>
											</select>
										</div>
									</div>
								<?php } else { ?>
									<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $session['user_id']; ?>" />
								<?php } ?>
								<div class="col-md-4">
									<div class="form-group">
										<label for="month_year"><?php echo $this->lang->line('xin_select_month'); ?></label>
										<input class="form-control month_year" placeholder="<?php echo $this->lang->line('xin_select_month'); ?>" readonly id="month_year" name="month_year" type="text" value="<?php echo date('m-Y', strtotime('-1 month')); ?>">
									</div>
								</div>
							</div>
							<?php
							if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) {
								$colmd = '12';
							} else {
								$colmd = '4';
							}
							?>
							<div class="row">
								<div class="col-md-<?php echo $colmd; ?>">
									<div class="form-group">
										<div class="form-actions">
											<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
												<?php echo $this->lang->line('xin_search'); ?> </button>
										</div>
									</div>
								</div>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($system[0]->is_half_monthly != 1) { ?>
			<?php if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) { ?>
				<div class="col-md-7">
					<div class="box mb-4">
						<div class="box-header with-border">
							<h3 class="box-title"> <?php echo  $this->lang->line('xin_payroll_bulk_payment'); ?> </h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<?php $attributes = array('name' => 'bulk_payment', 'id' => 'bulk_payment', 'class' => 'm-b-1 add form-hrm'); ?>
									<?php $hidden = array('user_id' => $session['user_id']); ?>
									<?php echo form_open($bulk_form_url, $attributes, $hidden); ?>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
												<select class="form-control" name="company_id" id="aj_companyx" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
													<option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
													<?php foreach ($all_companies as $company) { ?>
														<option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-3" id="location_ajax">
											<div class="form-group">
												<label for="name"><?php echo $this->lang->line('left_location'); ?></label>
												<select name="location_id" id="aj_location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location'); ?>">
													<option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group" id="department_ajax">
												<label for="department"><?php echo $this->lang->line('left_department'); ?></label>
												<select class="form-control" id="aj_subdepartments" name="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_department'); ?>">
													<option value="0"><?php echo $this->lang->line('xin_acc_all'); ?></option>
												</select>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="month_year"><?php echo $this->lang->line('xin_select_month'); ?></label>
												<input class="form-control month_year" placeholder="<?php echo $this->lang->line('xin_select_month'); ?>" readonly name="month_year" type="text" value="<?php echo date('m-Y', strtotime('-1 month')); ?>" id="bmonth_year">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="name">payment mode </label>
												<select class="form-control" name="payment_mode" id="payment_mode">

													<option value="cash">Cash</option>
													<!-- <option value="Bank">Bank</option> -->
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<div class="form-actions">
													<button type="button" class="btn btn-primary" id="bulk_pyment_advance"> <i class="fa fa-check-square-o"></i>
														Advance Salary</button>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<div class="form-actions">
													<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
														<?php echo $this->lang->line('xin_payroll_bulk_payment'); ?>
													</button>
												</div>
											</div>
										</div>
									</div>
									<?php echo form_close(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?><?php } ?>
	</div>
	<div class="box <?php echo $get_animate; ?>">
		<div class="box-header with-border">
			<h3 class="box-title"> <?php echo $this->lang->line('xin_payment_info_for'); ?>
				<span class="text-danger" id="p_month"><?php echo date('Y-m', strtotime('-1 month')); ?></span>
			</h3>
			<?php if (in_array('37', $role_resources_ids)) { ?>
				<div class="box-tools pull-right"> <a href="<?php echo site_url('admin/payroll/payment_history'); ?>">
						<button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-money"></span>
							<?php echo $this->lang->line('xin_payslip_history'); ?></button>
					</a> </div>
			<?php } ?>
		</div>
		<div class="box-body" id="payslip_list_tBLW">
			<div class="box-datatable table-responsive">
				<table class="datatables-demo table table-striped table-bordered" id="xin_table">
					<thead>
						<tr>
							<th width="160"><?php echo $this->lang->line('xin_action'); ?></th>
							<th><?php echo $this->lang->line('xin_name'); ?></th>
							<th><?php echo $this->lang->line('xin_salary_type'); ?></th>
							<th><?php echo $this->lang->line('xin_salary_title'); ?></th>
							<th>CPF Employee</th>
							<th><?php echo $this->lang->line('xin_payroll_net_salary'); ?></th>
							<th>Balance Amount</th>
							<th>Mode Of Payment</th>
							<th><?php echo $this->lang->line('dashboard_xin_status'); ?></th>

						</tr>
					</thead>
				</table>
			</div>
		</div>




		<div class="box-body" id="payslip_list_bulk_advance" style="display: none;">
			<?php $attributes = array('name' => 'add_pay_bulk_advance', 'id' => 'add_pay_bulk_advance', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
			<?php $hidden = array('_method' => 'ADD'); ?>
			<?php echo form_open('admin/payroll/add_pay_bulk_advance/', $attributes, $hidden); ?>
			<div class="box-datatable table-responsive">
				<table class="datatables-demo table table-striped  table-bordered" id="xin_table_bulk_advance">
					<thead>
						<tr>
							<th>
								<?php echo $this->lang->line('xin_action'); ?>
							</th>
							<th><?php echo $this->lang->line('xin_name'); ?> </th>
							<th>Basic Salary</th>
							<th>Advance Salary</th>


						</tr>
					</thead>
				</table>
			</div>

			<div class="form-actions box-footer">
				<?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_pay'))); ?>
			</div>
			<?php echo form_close(); ?>
		</div>


		<div class="box-body" id="payslip_list_bulk" style="display: none;">

			<?php $attributes = array('name' => 'add_pay_bulk', 'id' => 'add_pay_bulk', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
			<?php $hidden = array('_method' => 'ADD'); ?>
			<?php echo form_open('admin/payroll/add_pay_bulk/', $attributes, $hidden); ?>
			<div class="box-datatable table-responsive">
				<div id="checkboxContainer">
					<input type="checkbox" id="selectUsersCheckbox" /> <label for="selectUsersCheckbox">Select Users</label>
				</div>

				<table class="datatables-demo table table-striped  table-bordered" id="xin_table_bulk">
					<thead>
						<tr>
							<th>
								<?php echo $this->lang->line('xin_action'); ?>
							</th>
							<th><?php echo $this->lang->line('xin_name'); ?> </th>
							<th><?php echo $this->lang->line('xin_salary_type'); ?></th>
							<th>Basic <?php echo $this->lang->line('xin_salary_title'); ?> <input type="checkbox" class="header_input_bulk" data-column="header_chk_gross_salary"></th>
							<th>Total Allowance <input type="checkbox" data-type="allowance_amount" class="header_input_bulk" data-column="header_chk_total_allowances"></th>
							<th>Leave Deductions <input type="checkbox" data-type="unpaid_leave_amount" class="header_input_bulk" data-column="header_chk_leave_deductions"></th>
							<th>Commissions <input type="checkbox" data-type="commission" class="header_input_bulk" data-column="header_chk_total_commissions"></th>
							<th>Total Loan <input type="checkbox" data-type="loan_de_amount" class="header_input_bulk" data-column="header_chk_loan_de_amount"></th>
							<th>Total Overtime <input type="checkbox" data-type="all_overtime_P" class="header_input_bulk" data-column="header_chk_total_overtime"></th>
							<th>Statutory deductions <input type="checkbox" data-type="statutory_deductions_amount" class="header_input_bulk" data-column="header_chk_total_statutory_deductions"></th>
							<th>Other Payment <input type="checkbox" data-type="other_payments_amount" class="header_input_bulk" data-column="header_chk_total_other_payments"> </th>
							<th>CPF Employee</th>
							<th>CPF Employer</th>
							<th>Total CPF</th>
							<th>Contribution Funds</th>
							<th>Deduction Amount <input type="checkbox" data-type="total_deduction" class="header_input_bulk" data-column="header_chk_total_employee_deduction"></th>
							<th>Employee Claims<input type="checkbox" data-type="employee_claim" class="header_input_bulk" data-column="header_chk_employee_claim"></th>
							<th>ShareOptions Amount <input type="checkbox" data-type="share_options_amount" class="header_input_bulk" data-column="header_chk_total_share"></th>

							<th>Balance Amount</th>
							<th><?php echo $this->lang->line('xin_payroll_net_salary'); ?></th>
							<th>Payment Amount</th>
							<th><?php echo $this->lang->line('dashboard_xin_status'); ?></th>
							<th>Payment Mode</th>

						</tr>
					</thead>
				</table>
			</div>
			<div class="form-actions box-footer">
				<?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_pay'))); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<style type="text/css">
		.hide-calendar .ui-datepicker-calendar {
			display: none !important;
		}

		.hide-calendar .ui-priority-secondary {
			display: none !important;
		}
	</style>

<?php } ?>
