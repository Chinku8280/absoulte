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

class Policy extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Policy_model");
		$this->load->model("Xin_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	 public function index()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_policies').' | '.$this->Xin_model->site_title();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = $this->lang->line('xin_policies');
		$data['path_url'] = 'policy';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('9',$role_resources_ids)) {
		$data['subview'] = $this->load->view("admin/policy/policy_list", $data, TRUE);
		$this->load->view('admin/layout/layout_main', $data); //page load		
		} else {
			redirect('admin/dashboard');
		}
     }
 
    public function policy_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/policy/policy_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id==1){
			$policy = $this->Policy_model->get_policies();
		} else {
			$policy = $this->Policy_model->get_company_policies($user_info[0]->company_id);
		}
		$data = array();

        foreach($policy->result() as $r) {
			 			  
		// get user > added by
		$user = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		if(!is_null($user)){
			$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		} else {
			$full_name = '--';	
		}
		// get date
		$pdate = $this->Xin_model->set_date_format($r->created_at);
		// get company
		if($r->company_id=='0'){
			$company = $this->lang->line('xin_all_companies');
		} else {
			$p_company = $this->Xin_model->read_company_info($r->company_id);
			if(!is_null($p_company)){
				$company = $p_company[0]->name;
			} else {
				$company = '--';	
			}
		}
		if(in_array('259',$role_resources_ids)) { //edit
			$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-policy_id="'. $r->policy_id . '"><span class="fa fa-pencil"></span></button></span>';
		} else {
			$edit = '';
		}
		if(in_array('260',$role_resources_ids)) { // delete
			$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->policy_id . '"><span class="fa fa-trash"></span></button></span>';
		} else {
			$delete = '';
		}
		if(in_array('261',$role_resources_ids)) { //view
			$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-policy_id="'. $r->policy_id . '"><span class="fa fa-eye"></span></button></span>';
		} else {
			$view = '';
		}
		$combhr = $edit.$view.$delete;
		$ititle = $r->title.'<br><small class="text-muted"><i>'.$this->lang->line('module_company_title').': '.$company.'<i></i></i></small>';
		$data[] = array(
			$combhr,
			$ititle,
			$pdate,
			$full_name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $policy->num_rows(),
			 "recordsFiltered" => $policy->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }

	 public function read()
	{
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('policy_id');
		$result = $this->Policy_model->read_policy_information($id);
		$data = array(
				'policy_id' => $result[0]->policy_id,
				'company_id' => $result[0]->company_id,
				'title' => $result[0]->title,
				'attachment' => $result[0]->attachment,
				'description' => $result[0]->description,
				'all_companies' => $this->Xin_model->get_companies()
				);
		if(!empty($session)){ 
			$this->load->view('admin/policy/dialog_policy', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and add info in database
	public function add_policy() {
	
		if($this->input->post('add_type')=='policy') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('company')==='') {
       		$Return['error'] = $this->lang->line('xin_error_company');
		} else if($this->input->post('title')==='') {
			$Return['error'] = $this->lang->line('xin_error_title');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		if(is_uploaded_file($_FILES['attachment']['tmp_name'])) {
			//checking image type
			$allowed =  array('png','jpg','jpeg','pdf','gif');
			$filename = $_FILES['attachment']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(in_array($ext,$allowed)){
				$tmp_name = $_FILES["attachment"]["tmp_name"];
				$profile = "uploads/company_policy/";
				$set_img = base_url()."uploads/company_policy/";
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($_FILES["attachment"]["name"]);
				$newfilename = 'company_policy_'.round(microtime(true)).'.'.$ext;
				move_uploaded_file($tmp_name, $profile.$newfilename);
				$fname = $newfilename;			
			} else {
				$Return['error'] = $this->lang->line('xin_error_attatchment_type');
			}
		} else {
			$fname = '';
		}
		
		$data = array(
		'company_id' => $this->input->post('company'),
		'title' => $this->input->post('title'),
		'description' => $qt_description,
		'attachment' => $fname,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Policy_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_add_policy');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='policy') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('title')==='') {
			$Return['error'] = $this->lang->line('xin_error_title');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'title' => $this->input->post('title'),
		'description' => $qt_description,		
		);
		
		$result = $this->Policy_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_policy');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		
		if($this->input->post('is_ajax')==2) {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Policy_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_success_delete_policy');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
}