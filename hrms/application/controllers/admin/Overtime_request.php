<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the HRSALE License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.hrsale.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hrsalesoft@gmail.com so we can send you a copy immediately.
 *
 * @author   HRSALE
 * @author-email  hrsalesoft@gmail.com
 * @copyright  Copyright © hrsale.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Overtime_request extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Overtime_request_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->library('email');
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Project_model");
		$this->load->model("Location_model");
		$this->load->model("Project_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	 
	 // overtime request > timesheet
	 public function index()
     {
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_overtime_request').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_overtime_request');
		$data['path_url'] = 'overtime_request';		
		$data['get_all_companies'] = $this->Xin_model->get_companies();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('401',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/timesheet/overtime_request", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}	  
     }	 
	 // update_attendance_list > timesheet
	 public function overtime_request_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		// get date
		$attendance_date = $this->input->get("attendance_date");
		// get employee id
		$employee_id = $session['user_id'];
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id == 1){
			$attendance_employee = $this->Overtime_request_model->all_employee_overtime_requests();
		} else {
			$attendance_employee = $this->Overtime_request_model->employee_overtime_requests($employee_id);
		}
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$data = array();

          foreach($attendance_employee->result() as $r) {
			  
			// total work
			$in_time = new DateTime($r->in_time);
			$out_time = new DateTime($r->out_time);
			
			$employee_id = $this->Xin_model->read_user_info($r->employee_id);	
			if(!is_null($employee_id)) {
				// $full_name = $employee_id[0]->employee_id;
				$full_name = $employee_id[0]->first_name . " " .$employee_id[0]->last_name;
			} else {
				$full_name = '';
			}
			
			
			$clock_in = $in_time->format('h:i a');			
			// attendance date
			$att_date_in = explode(' ',$r->in_time);
			$att_date_out = explode(' ',$r->out_time);
			$request_date = $this->Xin_model->set_date_format($r->overtime_date);
			$cin_date = $clock_in;
			if($r->out_time=='') {
				$cout_date = '-';
				$total_time = '-';
			} else {
				$clock_out = $out_time->format('h:i a');
				$interval = $in_time->diff($out_time);
				$hours  = $interval->format('%h');
				$minutes = $interval->format('%i');			
				$total_time = $hours ."h ".$minutes."m";
				$cout_date = $clock_out;
			}
			if($user_info[0]->user_role_id==1){
				if(in_array('402',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-time_request_id="'.$r->id.'"><i class="fa fa-pencil"></i></button></span>';
				} else {
					$edit = '';
				}
				if(in_array('403',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'.$r->id.'"><i class="fa fa-trash"></i></button></span>';
				} else {
					$delete = '';
				}
			} else {
				if($r->status == '2'){
					if(in_array('402',$role_resources_ids)) { //edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" disabled data-toggle="modal" data-target=".edit-modal-data" ><i class="fa fa-pencil"></i></button></span>';
					} else {
						$edit = '';
					}
					if(in_array('403',$role_resources_ids)) { // delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" disabled data-toggle="modal" data-target=".delete-modal" ><i class="fa fa-trash"></i></button></span>';
					} else {
						$delete = '';
					}
				} else {
					if(in_array('402',$role_resources_ids)) { //edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-time_request_id="'.$r->id.'"><i class="fa fa-pencil"></i></button></span>';
					} else {
						$edit = '';
					}
					if(in_array('403',$role_resources_ids)) { // delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'.$r->id.'"><i class="fa fa-trash"></i></button></span>';
					} else {
						$delete = '';
					}
				}
			}
			if($r->status == '1'){
				$status = $this->lang->line('xin_pending');
			} else if($r->status == '2'){
				$status = $this->lang->line('xin_accepted');
			} else {
				$status = $this->lang->line('xin_rejected');
			}
			
			$combhr = $edit.$delete;

		   $data[] = array(
				$combhr,
				$full_name,
				$request_date,
				$cin_date,
				$cout_date,
				$total_time,
				$status
		   );
	  }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $attendance_employee->num_rows(),
			 "recordsFiltered" => $attendance_employee->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	// add attendance > modal form 
	public function update_attendance_add() {
		$data['title'] = $this->Xin_model->site_title();
		//$employee_id = $this->input->get('employee_id');
		//$user = $this->Xin_model->read_user_info($employee_id);
		$data = array(
				'get_all_companies' => $this->Xin_model->get_companies(),
				'all_employees' => $this->Xin_model->all_employees(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/timesheet/dialog_overtime_request', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and add info in database
	public function add_request_attendance() {
	
		if($this->input->post('add_type')=='attendance') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */		
			if($this->input->post('company_id')==='') {
				$Return['error'] = $this->lang->line('xin_error_company');
			} elseif($this->input->post('employee_id')==='') {
				$Return['error'] = $this->lang->line('xin_error_employee_id');
			} elseif($this->input->post('attendance_date_m')==='') {
				$Return['error'] = $this->lang->line('xin_error_request_attendance_date');
			} elseif($this->input->post('clock_in_m')==='') {
				$Return['error'] = $this->lang->line('xin_error_request_attendance_in_time');
			} elseif($this->input->post('clock_out_m')==='') {
				$Return['error'] = $this->lang->line('xin_error_request_attendance_out_time');
			} elseif((strtotime($this->input->post('clock_in_m').':00')) > (strtotime($this->input->post('clock_out_m').':00'))) {
				$Return['error'] = 'Out time should be greater than In time';
			}
					
			if($Return['error']!=''){
				$this->output($Return);
			}
			
			$attendance_date = date("d-m-Y", strtotime($this->input->post('attendance_date_m')));
			$clock_in = $this->input->post('clock_in_m');
			$clock_out = $this->input->post('clock_out_m');
			
			$clock_in2 = $attendance_date.' '.$clock_in.':00';
			$clock_out2 = $attendance_date.' '.$clock_out.':00';
			
			//total work
			$total_work_cin =  new DateTime($clock_in2);
			$total_work_cout =  new DateTime($clock_out2);
			
			$interval_cin = $total_work_cout->diff($total_work_cin);
			$hours_in   = $interval_cin->format('%h');
			$minutes_in = $interval_cin->format('%i');
			$total_work = $hours_in .":".$minutes_in;
		
			//paydate
			$att_date = strtotime($attendance_date);
			$rq_date = date('Y-m',$att_date);
			
			$data = array(
				'company_id' => $this->input->post('company_id'),
				'employee_id' => $this->input->post('employee_id'),
				'overtime_date' => $attendance_date,
				'in_time' => $clock_in2,
				'out_time' => $clock_out2,
				'total_hours' => $total_work,
				'status' => 1
			);
			if($this->input->post('xin_reason') != '') {
				$data['reason'] = $this->input->post('xin_reason');
			}
			$result = $this->Overtime_request_model->add_employee_overtime($data);
			
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_request_attendance_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	
	// get record of attendance
	public function read() {
		$data['title'] = $this->Xin_model->site_title();
		$time_request_id = $this->input->get('time_request_id');
		$result = $this->Overtime_request_model->read_overtime_request_info($time_request_id);
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		
		$in_time = new DateTime($result[0]->in_time);
		$out_time = new DateTime($result[0]->out_time);
		
		$clock_in = $in_time->format('H:i');
		if($result[0]->out_time == '') {
			$clock_out = '';
		} else {
			$clock_out = $out_time->format('H:i');
		}
		
		$data = array(
				'time_request_id' => $result[0]->id,
				'company_id' => $result[0]->company_id,
				'employee_id' => $result[0]->employee_id,
				'full_name' => $full_name,
				'request_date' => $result[0]->overtime_date,
				'request_clock_in' => $clock_in,
				'request_clock_out' => $clock_out,
				'request_reason' => $result[0]->reason,
				'is_approved' => $result[0]->status,
				'get_all_companies' => $this->Xin_model->get_companies(),
				'all_employees' => $this->Xin_model->all_employees(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/timesheet/dialog_overtime_request', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and update info in database
	public function edit_attendance() {
	
		if($this->input->post('edit_type')=='attendance') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$session = $this->session->userdata('username');
		$user = $this->Xin_model->read_user_info($session['user_id']);	
		/* Server side PHP input validation */		
		if($this->input->post('company_id')==='') {
			$Return['error'] = $this->lang->line('xin_error_company');
		} else if($this->input->post('employee_id')==='') {
			$Return['error'] = $this->lang->line('xin_error_employee_id');
		} else if($this->input->post('attendance_date_e')==='') {
        	$Return['error'] = $this->lang->line('xin_error_request_attendance_date');
		} else if($this->input->post('clock_in')==='') {
        	$Return['error'] = $this->lang->line('xin_error_request_attendance_in_time');
		} else if($this->input->post('clock_out')==='') {
        	$Return['error'] = $this->lang->line('xin_error_request_attendance_out_time');
		} elseif((strtotime($this->input->post('clock_in').':00')) > (strtotime($this->input->post('clock_out').':00'))) {
			$Return['error'] = 'Out time should be greater than In time';
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		$attendance_date = $this->input->post('attendance_date_e');
		$clock_in = $this->input->post('clock_in');
		
		$clock_in2 = $attendance_date.' '.$clock_in.':00';
		
		//total work
		$total_work_cin =  new DateTime($clock_in2);
		
		$clock_out = $this->input->post('clock_out');
		$clock_out2 = $attendance_date.' '.$clock_out.':00';
		$total_work_cout =  new DateTime($clock_out2);
		
		$interval_cin = $total_work_cout->diff($total_work_cin);
		$hours_in   = $interval_cin->format('%h');
		$minutes_in = $interval_cin->format('%i');
		$total_work = $hours_in .":".$minutes_in;
		if($user[0]->user_role_id == 1) {
			$data = array(
			'company_id' => $this->input->post('company_id'),
			'employee_id' => $this->input->post('employee_id'),
			'overtime_date' => $attendance_date,
			'in_time' => $clock_in2,
			'out_time' => $clock_out2,
			'total_hours' => $total_work,
			'reason' => $this->input->post('xin_reason'),
			'status' => $this->input->post('status'),
			);
		} else {
			$data = array(
			'overtime_date' => $attendance_date,
			'in_time' => $clock_in2,
			'out_time' => $clock_out2,
			'total_hours' => $total_work,
			'reason' => $this->input->post('xin_reason'),
			);
		}
		
		$result = $this->Overtime_request_model->updateEmployeeOvertime($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_request_attendance_update');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// delete attendance record
	public function delete_attendance() {
		if($this->input->post('type')=='delete') {
			// Define return | here result is used to return user data and error for error message 
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Overtime_request_model->delete_overtime_request_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_success_employe_attendance_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	// get company > employees
	 public function get_update_employees() {

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);
		
		$data = array(
			'company_id' => $id
			);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/timesheet/get_request_employees", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	
	 }
}