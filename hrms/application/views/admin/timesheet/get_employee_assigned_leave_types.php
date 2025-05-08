<?php $session = $this->session->userdata('username'); ?>
<?php $user = $this->Xin_model->read_user_info($employee_id); ?>
<?php //$leave_categories = explode(',',$user[0]->leave_categories);
?>

<?php $leaves = $this->Timesheet_model->getEmployeeLeave($employee_id);

?>
<?php $office_shift = $this->Timesheet_model->read_office_shift_information($user[0]->office_shift_id); ?>

<div class="form-group">
	<label for="employee"><?php echo $this->lang->line('xin_leave_type'); ?><i class="hrsale-asterisk">*</i></label>
	<select class="form-control" id="leave_type" name="leave_type" data-plugin="select_hrm"
		data-placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>">
		<option value=""></option>

		<?php
		if ($leaves) {
			foreach ($leaves as $l) {
				$applied_leaves = $this->Timesheet_model->getEmployeeAppliedLeaveCount($l->leave_type_id, $l->leave_year, $employee_id);

				$leaves_taken = 0;
				if ($applied_leaves) {
					foreach ($applied_leaves as $l) {
						$start_date = new DateTime($l->from_date);
						$end_date = new DateTime($l->to_date);
						$end_date->modify('+1 day');
						// $diff = $end_date->diff($start_date)->format("%a");
						// $l->leave_count = $diff;
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($start_date, $interval, $end_date);
						$leave_period = array();
						foreach ($period as $d) {
							$p_day = $d->format('l');
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$leave_period[] = $d->format('Y-m-d');
								}
							}
						}

						if (count($leave_period) > 0) {
							if ($l->is_half_day == 0) {
								$leaves_taken += count($leave_period);
							} else {
								$leaves_taken += count($leave_period)  / 2;
							}
						}
					}
				}
				$type = $this->Timesheet_model->getEmployeeLeaveCount($l->leave_type_id, $employee_id);
				$previous_year = $this->Employees_model->getEmployeeLeaveCountForLeave($l->leave_type_id, date('Y') - 1, $employee_id);
				$current_year = $this->Employees_model->getEmployeeLeaveCountForLeave($l->leave_type_id, date('Y'), $employee_id);
				$setting = $this->Xin_model->read_setting_info(1);
				if ($setting[0]->module_prorated_leave == 'yes' && $l->leave_type_id == 22) {
					$type_name = $type->type_name;


					$get_join_year = new DateTime($user[0]->date_of_joining);
					$join_year = $get_join_year->format('Y');
					if ($join_year < date('Y')) {
						// Total Leave/Days per year * (1st January until that day for example 19April is 109 days)

						$januaryFirst = new DateTime('first day of January this year');
						$januaryFirstFormatted = $januaryFirst->format('Y-m-d');

						$today = new DateTime();

						// Calculate the difference between today and the last day of December
						$interval = $januaryFirst->diff($today);

						// Get the number of days as an integer
						$daysRemaining = $interval->days;
						// $annual_leave_taken = ($remaining_leave/365) * $daysRemaining;
						$annual_leave_taken = ($current_year->no_of_leaves / 365) * $daysRemaining;
					} else {

						$join_date = new DateTime($user[0]->date_of_joining);
						$today = new DateTime();
						// Calculate the difference between join date and today
						$interval = $join_date->diff($today);

						// Get the number of days as an integer
						$daysRemaining = $interval->days;
						// $annual_leave_taken = ($remaining_leave/365) * $daysRemaining;
						$annual_leave_taken = ($current_year->no_of_leaves / 365) * $daysRemaining;
					}
					$annual_leave_taken = ceil($annual_leave_taken);

					$remaining_leave = $annual_leave_taken + (isset($previous_year->remain_leave) && $previous_year->remain_leave != null ? $previous_year->remain_leave : 0);
				} else {
					// $remaining_leave = $l->no_of_leaves;
					// $type = $this->Timesheet_model->getEmployeeLeaveCount($l->leave_type_id, $employee_id);

					if ($type) {
						$type_name = $type->type_name;
						$total = $type->no_of_leaves;
						// $leave_remaining_total =  $type->balance_leave - $leaves_taken;
						$leave_remaining_total =  $type->balance_leave_check - $leaves_taken;
					}
					// $remaining_leave = $l->no_of_leaves - $leaves_taken;
					if ($leave_remaining_total > 0) {
						$remaining_leave = $leave_remaining_total;
					} else {
						$remaining_leave = 0;
					}
				}

				if ($remaining_leave > $current_year->balance_leave + (isset($previous_year->remain_leave) && $previous_year->remain_leave != null ? $previous_year->remain_leave : 0)) {
					$remaining_leave = $current_year->balance_leave;
				}


				if ($remaining_leave > 0) {
		?>
					<option value="<?php echo $l->leave_type_id ?>">
						<?php echo $type_name . ' (' . $remaining_leave . ' ' . $this->lang->line('xin_remaining') . ')'; ?></option>
		<?php
				}
			}
		}
		?>
		<!-- <option value="1">No Pay Leave</option> -->
		<?php //foreach($leave_categories as $leave_cat) {
		?>
		<?php //if($leave_cat!=0):
		?>
		<?php
		// $remaining_leave = $this->Timesheet_model->employee_count_total_leaves($leave_cat,$employee_id);
		// $type = $this->Timesheet_model->read_leave_type_information($leave_cat);
		// if(!is_null($type)){
		// 	$type_name = $type[0]->type_name;
		// 	$total = $type[0]->days_per_year;
		// 	$leave_remaining_total = $total - $remaining_leave;	
		?>
		<!-- <option value="<? #php echo $leave_cat;
							?>"> <#?php echo $type_name.' ('.$leave_remaining_total.' '.$this->lang->line('xin_remaining').')';?></option> -->
		<?php //}  endif;
		?>
		<?php //} 
		?>
	</select>
	<span id="remaining_leave" style="display:none; font-weight:600; color:#F00;">&nbsp;</span>
</div>
<?php
//}
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		jQuery('[data-plugin="select_hrm"]').select2({
			width: '100%'
		});

		/*jQuery("#leave_type").change(function(){
			var employee_id = jQuery('#employee_id').val();
			var leave_type_id = jQuery(this).val();
			if(leave_type_id == '' || leave_type_id == 0) {
				jQuery('#remaining_leave').show();
				jQuery('#remaining_leave').html('<?php echo $this->lang->line('xin_error_leave_type_field'); ?>');
			} else {
				jQuery.get(base_url+"/get_employees_leave/"+leave_type_id+"/"+employee_id, function(data, status){
					jQuery('#remaining_leave').show();
					jQuery('#remaining_leave').html(data);
				});
			}
			alert(employee_id + ' - - '+leave_type_id);
			
		});*/
	});
</script>