<?php
/* Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php
$role_resources_ids = $this->Xin_model->user_role_resource();
$user_info = $this->Xin_model->read_user_info($session['user_id']);
// check
$half_title = '';
if($system[0]->is_half_monthly==1){
	$payment_check1 = $this->Payroll_model->read_make_payment_payslip_half_month_check_first($euser_id,$payment_date);
	$payment_check2 = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($euser_id,$payment_date);
	$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($euser_id,$payment_date);
	if($payment_check->num_rows() > 1) {
		if($payment_check2[0]->payslip_key == $this->uri->segment(5)){
			$half_title = '('.$this->lang->line('xin_title_second_half').')';
		} else if($payment_check1[0]->payslip_key == $this->uri->segment(5)){
			$half_title = '('.$this->lang->line('xin_title_first_half').')';
		} else {
			$half_title = '';
		}
	} else {
		$half_title = '('.$this->lang->line('xin_title_first_half').')';
	}
	$half_title = $half_title;
} else {
	$half_title = '';
}
?>
<?php
if($user_info[0]->user_role_id==1 || in_array('404',$role_resources_ids) || in_array('405',$role_resources_ids)){
	$cmdp_1st = 'col-md-9';
	$cmdp_2nd = 'col-md-3';
} else {
	$cmdp_1st = 'col-md-12';
	$cmdp_2nd = '';
}

// echo date('Y-m',strtotime('01-'.$payment_date));exit;
$date = strtotime($year_to_date);
$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);
// total days in month
$daysInMonth = cal_days_in_month(0, $month, $year);
$imonth = date('F', $date);
$r = $this->Xin_model->read_user_info($euser_id);
$pcount = 0;
$acount = 0;
$lcount = 0;
for($i = 1; $i <= $daysInMonth; $i++):
    $i = str_pad($i, 2, 0, STR_PAD_LEFT);
    // get date <
    $attendance_date = $year.'-'.$month.'-'.$i;
    $get_day = strtotime($attendance_date);
    $day = date('l', $get_day);
    $user_id = $r[0]->user_id;
    $office_shift_id = $r[0]->office_shift_id;
    $attendance_status = '';
    // get holiday
    $h_date_chck = $this->Timesheet_model->holiday_date_check($attendance_date);
    $holiday_arr = array();
    if($h_date_chck->num_rows() == 1){
        $h_date = $this->Timesheet_model->holiday_date($attendance_date);
        $begin = new DateTime( $h_date[0]->start_date );
        $end = new DateTime( $h_date[0]->end_date);
        $end = $end->modify( '+1 day' ); 
        
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        
        foreach($daterange as $date){
            $holiday_arr[] =  $date->format("Y-m-d");
        }
    } else {
        $holiday_arr[] = '99-99-99';
    }
    // get leave/employee
    $leave_date_chck = $this->Timesheet_model->leave_date_check($user_id,$attendance_date);
    $leave_arr = array();
    if($leave_date_chck->num_rows() == 1){
        $leave_date = $this->Timesheet_model->leave_date($user_id,$attendance_date);
        $begin1 = new DateTime( $leave_date[0]->from_date );
        $end1 = new DateTime( $leave_date[0]->to_date);
        $end1 = $end1->modify( '+1 day' ); 
        
        $interval1 = new DateInterval('P1D');
        $daterange1 = new DatePeriod($begin1, $interval1 ,$end1);
        
        foreach($daterange1 as $date1){
            $leave_arr[] =  $date1->format("Y-m-d");
        }	
    } else {
        $leave_arr[] = '99-99-99';
    }
    $office_shift = $this->Timesheet_model->read_office_shift_information($office_shift_id);
    $check = $this->Timesheet_model->attendance_first_in_check($user_id,$attendance_date);
    // get holiday>events
    if($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
        $status = 'H';	
        $pcount += 0;
        //$acount += 0;
    } else if($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
        $status = 'H';
        $pcount += 0;
        //$acount += 0;
    } else if($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
        $status = 'H';
        $pcount += 0;
        //$acount += 0;
    } else if($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
        $status = 'H';
        $pcount += 0;
        //$acount += 0;
    } else if($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
        $status = 'H';
        $pcount += 0;
        //$acount += 0;
    } else if($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
        $status = 'H';
        $pcount += 0;
        //$acount -= 1;
    } else if($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
        $status = 'H';
        $pcount += 0;
        //$acount -= 1;
    } else if(in_array($attendance_date,$holiday_arr)) { // holiday
        $status = 'H';
        $pcount += 0;
        //$acount += 0;
    } else if(in_array($attendance_date,$leave_arr)) { // on leave
        $status = 'L';
        $pcount += 0;
        $lcount += 1;
    //	$acount += 0;
    } else if($check->num_rows() > 0){
        $pcount += 1;
        //$acount -= 1;
    }	else {
        $status = 'A';
        //$acount += 1;
        $pcount += 0;
        // set to present date
        $iattendance_date = strtotime($attendance_date);
        $icurrent_date = strtotime(date('Y-m-d'));
        if($iattendance_date <= $icurrent_date){
            $acount += 1;
        } else {
            $acount += 0;
        }
    }
endfor;
?>

<div class="row">
    <div class="<?php echo $cmdp_1st;?>">
        <div class="box mb-4">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $half_title.' - '.$this->lang->line('xin_payslip');?> -
                    </strong><?php echo date("F, Y", strtotime(date('Y-m',strtotime('01-'.$payment_date))));?></h3>
                <div class="box-tools pull-right"> <a
                        href="<?php echo site_url();?>admin/payroll/pdf_create/p/<?php echo $payslip_key;?>/"
                        class="btn btn-social-icon mb-1 btn-outline-github" data-toggle="tooltip" data-placement="top"
                        title=""
                        data-original-title="<?php echo $this->lang->line('xin_payroll_download_payslip');?>"><i
                            class="fa fa-file-pdf-o"></i></a> </div>
            </div>
            <div class="box-body">
                <div class="table-responsive" data-pattern="priority-columns">
                    <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                            <tr>
                                <!-- <td><strong class="help-split"><?php echo $this->lang->line('dashboard_employee_id');?>:
                                    </strong>#<?php echo $employee_id;?></td> -->
                                <td><strong class="help-split"><?php echo $this->lang->line('xin_employee_name');?>:
                                    </strong><?php echo $first_name.' '.$last_name;?></td>
                                <td><strong class="help-split"><?php echo $this->lang->line('xin_payslip_number');?>:
                                    </strong><?php echo $make_payment_id;?></td>
                            </tr>
                            <tr>
                                <td><strong class="help-split">Employee NRIC/FIN :
                                    </strong><?php echo $id_no;?></td>
                                <td><strong class="help-split">Payslip Month:
                                    </strong><?php echo date("F, Y", strtotime(date('Y-m',strtotime('01-'.$payment_date))));?></td>
                            </tr>
                            <tr>
                                <td><strong class="help-split">Employee DOB:
                                    </strong><?php echo $date_of_birth;?></td>
                                    <td><strong class="help-split">Salary Period:
                                    </strong><?php echo date("01-m-Y", strtotime('01-'.$payment_date)).' To '.date("t-m-Y", strtotime('01-'.$payment_date));?></td>
                                <!-- <td><strong class="help-split">&nbsp;</strong></td> -->
                            </tr>
                            <tr>
                            <td><strong class="help-split">Designation:
                                    </strong><?php echo $designation_name;?></td>
                                    <td><strong class="help-split">Payment Status:
                                    </strong><?php echo $payment_status;?></td>
                            </tr>
                            <tr>
                            <td><strong class="help-split">Reference:
                                    </strong></td>
                                    <td><strong class="help-split">Payment Type / Date:
                                    </strong><?php echo str_replace('_',' ',ucfirst($payslip_type)).'/'.$year_to_date;?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if($user_info[0]->user_role_id==1 || in_array('404',$role_resources_ids) || in_array('405',$role_resources_ids)){?>
    <div class="<?php echo $cmdp_2nd;?>">
        <div class="box">
            <div class="box-body">
                <div class="form-group">

                    <?php $attributes2 = array('name' => 'update_status', 'id' => 'update_status', 'autocomplete' => 'off');?>
                    <?php $hidden2 = array('user_id' => $session['user_id']);?>
                    <?php echo form_open('admin/payroll/update_payroll_status', $attributes2, $hidden2);?>
                    <?php
			$data2 = array(
			  'name'        => 'payroll_id',
			  'id'          => 'payroll_id',
			  'type'        => 'hidden',
			  'value'   	   => $this->uri->segment(5),
			  'class'       => 'form-control',
			);
		
			echo form_input($data2);
			?>

                    <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                    <?php if($user_info[0]->user_role_id==1){?>
                    <select class="form-control" name="status" data-plugin="select_hrm"
                        data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">

                        <option value=""><?php echo $this->lang->line('dashboard_xin_status');?></option>
                        <option value="0" <?php if($approval_status=='0'):?> selected <?php endif; ?>>
                            <?php echo $this->lang->line('xin_not_approve_payroll_title');?></option>
                        <option value="1" <?php if($approval_status=='1'):?> selected <?php endif; ?>>
                            <?php echo $this->lang->line('xin_role_first_level_approval');?></option>
                        <option value="2" <?php if($approval_status=='2'):?> selected <?php endif; ?>>
                            <?php echo $this->lang->line('xin_second_level_payroll_approver_title');?></option>

                    </select>
                    <?php } else if(in_array('404',$role_resources_ids)){?>

                    <select class="form-control" name="status" data-plugin="select_hrm"
                        data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                        <option value=""><?php echo $this->lang->line('dashboard_xin_status');?></option>
                        <option value="1" <?php if($approval_status=='1'):?> selected <?php endif; ?>>
                            <?php echo $this->lang->line('xin_role_first_level_approval');?></option>
                    </select>
                    <?php } else if(in_array('405',$role_resources_ids)){?>

                    <select class="form-control" name="status" data-plugin="select_hrm"
                        data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                        <option value=""><?php echo $this->lang->line('dashboard_xin_status');?></option>
                        <option value="2" <?php if($approval_status=='2'):?> selected <?php endif; ?>>
                            <?php echo $this->lang->line('xin_second_level_payroll_approver_title');?></option>
                    </select>
                    <?php } ?>
                </div>
                <div class="form-actions box-footer">
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                        <?php echo $this->lang->line('xin_save');?> </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <?php }
        ?>
</div>
<?php $user_id = $employee_id;?>
<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box">
        <table style="width: 100%; ">

<tbody>
    <tr>
        <td style="padding: 0;  width: 50%; vertical-align: top;">
            <table class="datatables-demo table table-striped table-bordered dataTable no-footer" style="width: 100%; margin: 0;">
                <caption style="margin-bottom: 0.5rem; margin-top: 0.5rem;"><b>INCOME</b></caption>
                <tbody>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Basic Rate:</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($basic_salary > 0 ? $this->Xin_model->currency_sign($basic_salary):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Basic Salary(A):</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($basic_salary > 0 ? $this->Xin_model->currency_sign($basic_salary) : $this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <?php 
                $a_amount =0;
                    foreach($mapping_data as $m){
                    if($m->allowance_name !=""){
                        $a_amount += $m->allowance_amount;
                    }
                }
                ?>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Total Allowance (B)</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($a_amount > 0 ?$this->Xin_model->currency_sign($a_amount):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <?php 
                    foreach($mapping_data as $m){
			if($m->allowance_name !=""){
                ?>
                <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo '&nbsp;&nbsp;&nbsp;+'.$m->allowance_name; ?></span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo $this->Xin_model->currency_sign($m->allowance_amount); ?></span>
                        </td>
                    </tr>
				
                        <?php
			}
		}
        ?>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Additional Allowances (E)</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($additonal_allowance > 0 ?$this->Xin_model->currency_sign($additonal_allowance):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Total Additional(C)</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo (((float)$total_other_payments + (float)$total_commissions + (float)$share_option_amount) > 0? $this->Xin_model->currency_sign((float)$total_other_payments + (float)$total_commissions + (float)$share_option_amount):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>+ Bonus</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span>: $2,000.00(Monthly)</span>
                        </td>
                    </tr> -->
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Total Overtime Pay(D)</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($total_overtime > 0 ? $this->Xin_model->currency_sign($total_overtime) :$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; padding-bottom: 0.5rem;">
                            <span>Total Claim(I):</span>
                        </td>
                        <td style="border: none;padding-bottom: 0.5rem;">
                            <span><?php echo ($claim_amount >0 ?$this->Xin_model->currency_sign($claim_amount):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="border: none; border-top: 1px solid #000;">
                            <b>TOTAL INCOME</b>
                        </td>
                        <td style="border: none; border-top: 1px solid #000;">
                            <b><?php echo $this->Xin_model->currency_sign((float)$basic_salary+(float)$a_amount+(float)$additonal_allowance+(float)$claim_amount+(float)$total_other_payments+(float)$total_commissions + (float)$share_option_amount+(float)$total_overtime); ?></b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </td>
        <?php 
                        $total_leave_deduction = 0;
                            $leave_deductions = $this->Payroll_model->getLeaveDeductionPayslip($make_payment_id);
                    if($leave_deductions) {
                                    // $total_leave_deduction = round($leave_deductions[0]->total_leave_amount, 2);
                                    foreach($leave_deductions as $ld) {
                                       
                                        $total_leave_deduction += $ld->leave_amount;
                                    }
                    }
        ?>
        <td style="padding: 0;  width: 50%; vertical-align: top;">
            <table style="width: 100%; margin: 0;" class="datatables-demo table table-striped table-bordered dataTable no-footer">
                <caption style="margin-bottom: 0.5rem; margin-top: 0.5rem;"><b>Deductions</b></caption>
                <tbody>
                <?php 
                $d_amount =0;
                    foreach($mapping_data as $m){
                    if($m->deduction_name !=""){
                        $d_amount += $m->deduction_amount;
                    }
                }
				$contribution_data=$this->Employees_model->set_employee_contribution($euser_id);
                //echo $this->db->last_query();exit;
                // if(count($contribution_data) > 0) {
                //     $contribution_fund = array();
                //     foreach($contribution_data as $c_data) {
                //         $contribution_amount = $this->Contribution_fund_model->getContributionRate($basic_salary, $c_data->contribution_id);
                //         $d_amount +=$contribution_amount;
                //     }
                //      echo $this->db->last_query();exit;
                // }
                ?>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Total Deductions</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($d_amount > 0 ? $this->Xin_model->currency_sign($d_amount):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <?php 
                    foreach($mapping_data as $m){
			if($m->deduction_name !=""){
                ?>
                <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo '&nbsp;&nbsp;&nbsp;-'.$m->deduction_name; ?></span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo $this->Xin_model->currency_sign($m->deduction_amount); ?></span>
                        </td>
                    </tr>
				
                        <?php
			}
		}
        // if(count($contribution_data) > 0) {
        //     $contribution_fund = array();
        //     foreach($contribution_data as $c_data) {
        //         $contribution_amount = $this->Contribution_fund_model->getContributionRate($basic_salary, $c_data->contribution_id);
                ?>
                  <!-- <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php //echo '&nbsp;&nbsp;&nbsp;-'.$c_data->contribution; ?></span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php //echo $this->Xin_model->currency_sign($contribution_amount); ?></span>
                        </td>
                    </tr> -->
                <?php
               
        //     }
             
        // }
        ?>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Employee CPF</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($cpf_employee_amount > 0 ? $this->Xin_model->currency_sign($cpf_employee_amount):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span>Unpaid Leave</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span><?php echo ($total_leave_deduction > 0 ?$this->Xin_model->currency_sign($total_leave_deduction):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;padding-bottom: 0.5rem;">
                            <span>Loan</span>
                        </td>
                        <td style="border: none;padding-bottom: 0.5rem;">
                            <span><?php echo ($total_loan > 0?$this->Xin_model->currency_sign($total_loan):$this->Xin_model->currency_sign(0)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span style="visibility: hidden;">Unpaid Leave</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span style="visibility: hidden;">: $0.00</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;padding-bottom: 0;">
                            <span style="visibility: hidden;">Unpaid Leave</span>
                        </td>
                        <td style="border: none;padding-bottom: 0;">
                            <span style="visibility: hidden;">: $0.00</span>
                        </td>
                    </tr>
                    
                    
                   
                </tbody>
                <tfoot >
                    <tr>
                        <td style="border: none; border-top: 1px solid #000;">
                            <b>TOTAL Deductions(F)</b>
                        </td>
                        <td style="border: none; border-top: 1px solid #000;">
                            <b><?php echo $this->Xin_model->currency_sign((float)$d_amount+(float)$cpf_employee_amount+(float)$total_loan); ?></b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </td>
       
    </tr>


</tbody>

</table>
  <table style="width: 100%; " class="datatables-demo table table-striped table-bordered dataTable no-footer">

            <tbody>
                <tr>
                    <td style="padding: 0;  width: 50%; vertical-align: top;">
                        <table style="width: 100%; margin: 0;">
                           
                            <tbody>
                                <!-- <tr>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span>Employer CPF</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span><?php echo ($cpf_employer_amount >0 ?$this->Xin_model->currency_sign($cpf_employer_amount):$this->Xin_model->currency_sign(0)); ?></span>
                                    </td>
                                </tr> -->
                                 
                                <tr>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span>Gross Pay(A+B+C+D+E+I)</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span><?php echo $this->Xin_model->currency_sign((float)$basic_salary+(float)$total_allowances+(float)$claim_amount+(float)$total_other_payments+(float)$total_commissions + (float)$share_option_amount+(float)$total_overtime); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;padding-bottom: 0.5rem;">
                                        <span>NET Pay (A+B+C+D+E+I-F)</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0.5rem;">
                                        <span><?php echo $this->Xin_model->currency_sign((float)$basic_salary+(float)$total_allowances+(float)$claim_amount+(float)$total_other_payments+ (float)$total_commissions + (float)$share_option_amount+(float)$total_overtime-((float)$d_amount+(float)$cpf_employee_amount+(float)$total_loan)); ?></span>
                                    </td>
                                </tr>
                               
                            </tbody>
                            
                        </table>
                    </td>
                    <td style="padding: 0;  width: 50%; vertical-align: top;">
                        <table style="width: 100%; margin: 0;">
                            <tbody>
                                <tr>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span>Days Worked</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span><?php ($pcount > 0 ?$pcount:0);?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span>Days Off</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span><?php ($acount > 0 ? $acount:0);?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span>Remark</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span>: </span>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span style="visibility: hidden;">Unpaid Leave</span>
                                    </td>
                                    <td style="border: none;padding-bottom: 0;">
                                        <span style="visibility: hidden;">: $0.00</span>
                                    </td>
                                </tr>
                                
                                
                               
                            </tbody>
                            
                        </table>
                    </td>
                   
                </tr>


            </tbody>

        </table>
        </div>
        <div class="box" style="display: none;">
            <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_payment_details');?> </h3>
            </div>
            <div class="box-body">
                <div id="accordion">
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse"
                                href="#basic_salary" aria-expanded="false">
                                <strong><?php echo $this->lang->line('xin_payroll_basic_salary');?></strong> </a> </div>
                        <div id="basic_salary" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table
                                        class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                        <tbody>
                                            <tr>
                                                <td><strong><?php echo $this->lang->line('xin_payroll_basic_salary');?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($basic_salary);?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $count_allowances = $this->Employees_model->count_employee_allowances_payslip($make_payment_id);?>
                    <?php $allowances = $this->Employees_model->set_employee_allowances_payslip($make_payment_id);?>
                    <?php if($count_allowances > 0):?>
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse"
                                href="#set_allowances" aria-expanded="false">
                                <strong><?php echo $this->lang->line('xin_employee_set_allowances');?></strong> </a>
                        </div>
                        <div id="set_allowances" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table
                                        class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                        <tbody>
                                            <?php $allowance_amount = 0; foreach($allowances->result() as $sl_allowances) { ?>
                                            <?php $allowance_amount += $sl_allowances->allowance_amount;?>
                                            <tr>
                                                <td><strong><?php echo $sl_allowances->allowance_title;?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($sl_allowances->allowance_amount);?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><strong><?php echo $this->lang->line('xin_acc_total');?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($allowance_amount);?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php $count_commissions = $this->Employees_model->count_employee_commissions_payslip($make_payment_id);?>
                    <?php $commissions = $this->Employees_model->set_employee_commissions_payslip($make_payment_id);?>
                    <?php if($count_commissions > 0):?>
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse"
                                href="#set_commissions" aria-expanded="false">
                                <strong><?php echo $this->lang->line('xin_hr_commissions');?></strong> </a> </div>
                        <div id="set_commissions" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table
                                        class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                        <tbody>
                                            <?php $commissions_amount = 0; foreach($commissions->result() as $sl_commissions) { ?>
                                            <?php $commissions_amount += $sl_commissions->commission_amount;?>
                                            <?php
                                                $commissions_title = $this->PaymentDeduction_Model->get_commissions_title($sl_commissions->commission_id);
                                                ?>
                                            <td>
                                                <!-- <strong><?php echo $sl_commissions->commission_title;?>:</strong> -->
                                                <?php echo $commissions_title[0]->payment_deduction_name;?>
                                                <span
                                                    class="pull-right"><?php echo $this->Xin_model->currency_sign($sl_commissions->commission_amount);?></span>
                                            </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><strong><?php echo $this->lang->line('xin_acc_total');?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($commissions_amount);?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else:?>
                    <?php $commissions_amount = 0;?>
                    <?php endif;?>
                    <?php $count_loan = $this->Employees_model->count_employee_deductions_payslip($make_payment_id);?>
                    <?php $loan = $this->Employees_model->set_employee_deductions_payslip($make_payment_id);?>
                    <?php if($count_loan > 0):?>
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse"
                                href="#set_loan_deductions" aria-expanded="false">
                                <strong><?php echo $this->lang->line('xin_employee_set_loan_deductions');?></strong>
                            </a> </div>
                        <div id="set_loan_deductions" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table
                                        class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                        <tbody>
                                            <?php $loan_de_amount = 0; foreach($loan->result() as $r_loan) { ?>
                                            <?php $loan_de_amount += $r_loan->loan_amount;?>
                                            <tr>
                                                <td><strong><?php echo $r_loan->loan_title;?>:</strong> <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($r_loan->loan_amount);?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><strong><?php echo $this->lang->line('xin_acc_total');?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($loan_de_amount);?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else:?>
                    <?php $loan_de_amount = 0;?>
                    <?php endif;?>
                    <?php $contribution_data=$this->Employees_model->set_employee_contribution($user_id); 
                    if(count($contribution_data) > 0) {
						$contribution_fund = array();
						foreach($contribution_data as $c_data) {
							$contribution_amount = $this->Contribution_fund_model->getContributionRate($basic_salary, $c_data->contribution_id);
							$fund_deduction_amount +=$contribution_amount;
						}
					 	

					}
                    ?>
                    <?php $count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions_payslip($make_payment_id);?>
                    <?php $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions_payslip($make_payment_id);?>
                    <?php if($count_statutory_deductions > 0):?>
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse"
                                href="#set_statutory_deductions" aria-expanded="false">
                                <strong><?php echo $this->lang->line('xin_employee_set_statutory_deductions');?></strong>
                            </a> </div>
                        <div id="set_statutory_deductions" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table
                                        class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                        <tbody>
                                            <?php $statutory_deductions_amount = 0; foreach($statutory_deductions->result() as $sl_statutory_deductions) { ?>
                                            <?php
                        if($system[0]->statutory_fixed!='yes'):
							$sta_salary = $basic_salary;
							$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
							$statutory_deductions_amount += $st_amount;
							$single_sd = $st_amount;
                        else:
                        	$statutory_deductions_amount += $sl_statutory_deductions->deduction_amount;
							$single_sd = $sl_statutory_deductions->deduction_amount;
                        endif;
                        ?>
                                            <tr>
                                                <td><strong><?php echo $sl_statutory_deductions->deduction_title;?>
                                                        :</strong> <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($single_sd);?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><strong><?php echo $this->lang->line('xin_acc_total');?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($statutory_deductions_amount);?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else:?>
                    <?php $statutory_deductions_amount = 0;?>
                    <?php endif;?>
                    <?php $count_other_payments = $this->Employees_model->count_employee_other_payments_payslip($make_payment_id);?>
                    <?php $other_payments = $this->Employees_model->set_employee_other_payments_payslip($make_payment_id);?>
                    <?php if($count_other_payments > 0):?>
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse"
                                href="#set_other_payments" aria-expanded="false">
                                <strong><?php echo $this->lang->line('xin_employee_set_other_payment');?></strong> </a>
                        </div>
                        <div id="set_other_payments" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table
                                        class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                        <tbody>
                                            <?php $other_payments_amount = 0; foreach($other_payments->result() as $sl_other_payments) { ?>
                                            <?php $other_payments_amount += $sl_other_payments->payments_amount;?>
                                            <tr>
                                                <td><strong><?php echo $sl_other_payments->payments_title;?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($sl_other_payments->payments_amount);?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><strong><?php echo $this->lang->line('xin_acc_total');?>:</strong>
                                                    <span
                                                        class="pull-right"><?php echo $this->Xin_model->currency_sign($other_payments_amount);?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else:?>
                    <?php $other_payments_amount = 0;?>
                    <?php endif;?>
                    <?php $count_overtime = $this->Employees_model->count_employee_overtime_payslip($make_payment_id);?>
                    <?php $overtime = $this->Employees_model->set_employee_overtime_payslip($make_payment_id);?>
                    <?php if($count_overtime > 0):?>
                    <div class="card hrsale-payslip mb-2">
                        <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#overtime"
                                aria-expanded="false">
                                <strong><?php echo $this->lang->line('dashboard_overtime');?></strong> </a> </div>
                        <div id="overtime" class="collapse" data-parent="#accordion" style="">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <!-- <th><?php echo $this->lang->line('xin_employee_overtime_title');?></th> -->
                                                <th><?php echo $this->lang->line('xin_employee_overtime_no_of_days');?>
                                                </th>
                                                <th><?php echo $this->lang->line('xin_employee_overtime_hour');?></th>
                                                <th><?php echo $this->lang->line('xin_employee_overtime_rate');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1; $overtime_amount = 0; foreach($overtime->result() as $r_overtime) { ?>
                                            <?php
						$overtime_total = (float)$r_overtime->overtime_hours * (float)$r_overtime->overtime_rate;
						$overtime_amount += $overtime_total;
						?>
                                            <tr>
                                                <th scope="row"><?php echo $i;?></th>
                                                <!-- <td><?php echo $r_overtime->overtime_title;?></td> -->
                                                <td><?php echo $r_overtime->overtime_no_of_days;?></td>
                                                <td><?php echo $r_overtime->overtime_hours;?></td>
                                                <td><?php echo $r_overtime->overtime_rate;?></td>
                                            </tr>
                                            <?php $i++; } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" align="right">
                                                    <strong><?php echo $this->lang->line('xin_acc_total');?>:</strong>
                                                </td>
                                                <td><?php echo $this->Xin_model->currency_sign($overtime_amount);?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else:?>
                    <?php $overtime_amount = 0;?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
            $final_share_price = 0;
            $share_price = $this->Employees_model->get_share_data($make_payment_id);
            foreach($share_price->result() as $share){
              $final_share_price += $share->amount;
            }
  ?>


    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="box" style="display: none;">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo $this->lang->line('xin_payslip_earning');?> </h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                <tbody>
                                    <?php if($wages_type == 1){?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_payroll_basic_salary');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($basic_salary);?></span>
                                        </td>
                                    </tr>
                                    <?php } else {?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_employee_daily_wages');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($daily_wages);?></span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php if($total_allowances!=0 || $total_allowances!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_payroll_total_allowance');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($total_allowances);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if($commissions_amount!=0 || $commissions_amount!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_hr_commissions');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($commissions_amount);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if($total_loan!=0 || $total_loan!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_payroll_total_loan');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign(number_format($total_loan, 2, '.', ','));?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if($total_overtime!=0 || $total_overtime!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_payroll_total_overtime');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($total_overtime);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if($statutory_deductions_amount!=0 || $statutory_deductions_amount!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_employee_set_statutory_deductions');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($statutory_deductions_amount);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if(!empty($final_share_price)):?>
                                    <tr>
                                        <td><strong><?php echo 'Share Option';?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($final_share_price);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if($other_payments_amount!=0 || $other_payments_amount!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_employee_set_other_payment');?>:</strong>
                                            <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($other_payments_amount);?></span>
                                        </td>
                                    </tr>
                                    <?php
                                        $other_payments_amount = $other_payments_amount + $final_share_price;
                                    ?>
                                    <?php endif;?>

                                    <!-- cpf -->
                                    <?php if(isset($cpf_employee) && isset($cpf_employer)):?>
                                    <tr>
                                        <td><strong>CPF Employer:</strong> <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($cpf_employer);?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>CPF Employee:</strong> <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($cpf_employee);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>

                                    <!-- Contribution Funds -->
                                    <?php if(isset($contribution_fund)): foreach($contribution_fund as $cf):?>
                                    <tr>
                                        <td><strong><?php echo $cf['contribution']?>:</strong> <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($cf['contribution_amount']);?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif;?>

                                    <!-- Leave Deduction -->
                                    <?php if(isset($leave_deduction)):?>
                                    <tr>
                                        <td><strong>Leave Deductions:</strong> <span
                                                class="pull-right"><?php echo $this->Xin_model->currency_sign($leave_deduction);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>

                                    <?php if($net_salary!=0 || $net_salary!=''):?>
                                    <?php
                    if($wages_type == 1){
                      $bs = $basic_salary;
                    } else {
                      $bs = $daily_wages;
                    }
                    // $total_earning = $bs + $total_allowances + $overtime_amount + $commissions_amount + $other_payments_amount;
                    $total_earning = $bs + $total_allowances + $total_overtime + $commissions_amount + $other_payments_amount;
                    $total_deduction = $loan_de_amount + $statutory_deductions_amount;
                    $total_net_salary = $total_earning - $total_deduction;
                    // echo $total_overtime;
                    //cpf
                    if(isset($cpf_employee)) {
                      $total_net_salary = $total_net_salary - $cpf_employee;
                    }
                    
                   
                    //contributions
                    if(isset($contribution_fund)) {
                      $total_contribution = 0;
                      foreach($contribution_fund as $cf) {
                        $total_contribution += $cf['contribution_amount'];
                      }
                      $total_net_salary = $total_net_salary - $total_contribution;
                    }

                    //Leave Deduction
                    if(isset($leave_deduction)) {
                      $total_net_salary = $total_net_salary - $leave_deduction;
                    }
                  ?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_paid_amount');?>:</strong> <span
                                                class="pull-right">
                                                <?php echo $this->Xin_model->currency_sign($total_net_salary);?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php /*?><?php if($payment_method):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_payment_method');?>:</strong>
                                            <span class="pull-right"><?php echo $payment_method;?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php if($net_salary!=0 || $net_salary!=''):?>
                                    <tr>
                                        <td><strong><?php echo $this->lang->line('xin_payment_comment');?>:</strong>
                                            <span class="pull-right"><?php echo $pay_comments;?></span>
                                        </td>
                                    </tr>
                                    <?php endif;?><?php */?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>