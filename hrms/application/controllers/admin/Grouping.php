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

class Grouping extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Department_model");
		$this->load->model("Location_model");
		$this->load->model("Xin_model");
        $this->load->model('Grouping_model');
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
		if(!$session){ 
			redirect('admin/');
		}
		$data['title'] = "Grouping".' | '.$this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = "Grouping";
		$data['all_departments'] = $this->Department_model->all_departments();
        
		$data['path_url'] = 'grouping';
		$role_resources_ids = $this->Xin_model->user_role_resource();
				
		if(in_array('3',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("admin/grouping/grouping_list", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
     }

     public function grouping_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/grouping/grouping_list", $data);
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
			$grouping = $this->Grouping_model->get_grouping();
		} else {
			$grouping = $this->Grouping_model->get_department_subdepartments_grouping($user_info[0]->department_id,$user_info[0]->sub_department_id);
		}
		$data = array();

          foreach($grouping->result() as $r) {
			  			
			// department
			$dep = $this->Department_model->read_department_information($r->department_id);
			if(!is_null($dep)){
				$d_name = $dep[0]->department_name;
			} else {
				$d_name = '--';	
			}

            // sub department
			$dep = $this->Department_model->read_sub_department_info($r->sub_department_id);
			if(!is_null($dep)){
				$sub_name = $dep[0]->department_name;
			} else {
				$sub_name = '--';	
			}
			
			if(in_array('241',$role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-grouping_id="'. $r->grouping_id . '"><span class="fa fa-pencil"></span></button></span>';
			} else {
				$edit = '';
			}
			if(in_array('242',$role_resources_ids)) { // delete
				$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->grouping_id . '"><span class="fa fa-trash"></span></button></span>';
			} else {
				$delete = '';
			}
			$created_at = $this->Xin_model->set_date_format($r->created_at);
			$combhr = $edit.$delete;
			  
		   $data[] = array(
				$combhr,
				$r->grouping_name,
				$d_name,
                $sub_name,
				$created_at
		   );
          }

          $output = array(
                 "draw"             => $draw,
                 "recordsTotal"     => $grouping->num_rows(),
                 "recordsFiltered"  => $grouping->num_rows(),
                 "data"             => $data
            );
          echo json_encode($output);
          exit();
     }


      // get sub department
	 public function get_sub_department() {

		$data['title'] = $this->Xin_model->site_title();
		$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
		if(is_numeric($keywords[0])) {
			$id = $keywords[0];
		
			$data = array(
				'department_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$data = $this->security->xss_clean($data);
				$this->load->view("admin/employees/get_sub_departments", $data);
			} else {
				redirect('admin/');
			}
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	 }


     function add_grouping(){
        if($this->input->post('add_type')=='grouping') {
            // Check validation for user input
            $session = $this->session->userdata('username');
            
            $this->form_validation->set_rules('grouping_name', 'Grouping Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            $this->form_validation->set_rules('subdepartment_id', 'Sub Department', 'trim|required|xss_clean');
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            //if($this->form_validation->run() == FALSE) {
                    //$Return['error'] = 'validation error.';
            //}
            /* Server side PHP input validation */
            if($this->input->post('grouping_name')==='') {
                $Return['error'] = 'The Grouping name field is required.';
            } else if($this->input->post('department_id')==='') {
                $Return['error'] = 'Please Select Department';
            } else if($this->input->post('subdepartment_id')==='') {
                $Return['error'] = 'Please Select Sub Department';
            } 
            if($Return['error']!=''){
                
                   $this->output($Return);
            }
        
            $data = array(
            'grouping_name'     => $this->input->post('grouping_name'),
            'department_id'     => $this->input->post('department_id'),
            'sub_department_id' => $this->input->post('subdepartment_id'),
            'created_at'        => date('Y-m-d H:i:s'),
            );
    
            $data = $this->security->xss_clean($data);
            $result = $this->Grouping_model->add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_add_department');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            }
     }
	 public function sub_delete() {
		
		if($this->input->post('is_ajax')==2) {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('');
			}
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
				$id = $this->security->xss_clean($id);
				$result = $this->Grouping_model->delete_sub_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_hr_sub_department_deleted');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}
	}


    public function read_sub_record()
	{
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$keywords = preg_split("/[\s,]+/", $this->input->get('grouping_id'));
		if(is_numeric($keywords[0])) {
			$id = $keywords[0];
			$id = $this->security->xss_clean($id);
			$result = $this->Grouping_model->read_grouping_info($id);
			$data = array(
                'sub_department_id' => $result[0]->sub_department_id,
                'department_id'     => $result[0]->department_id,
                'grouping_name'     => $result[0]->grouping_name,
                'grouping_id'       => $result[0]->grouping_id
			);
			$data['all_departments'] = $this->Department_model->all_departments();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/grouping/dialog_grouping', $data);
			} else {
				redirect('admin/');
			}
		}
	}


    // Validate and update info in database
	public function update_sub_record() {
	
		if($this->input->post('edit_type')=='grouping') {
			
		
		$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
		if(is_numeric($keywords[0])) {
			$id = $keywords[0];
		
			// Check validation for user input
            $this->form_validation->set_rules('grouping_name', 'Grouping Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            $this->form_validation->set_rules('subdepartment_id', 'Sub Department', 'trim|required|xss_clean');
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();	
			/* Server side PHP input validation */
			if($this->input->post('grouping_name')==='') {
                $Return['error'] = 'The Grouping name field is required.';
            } else if($this->input->post('department_id')==='') {
                $Return['error'] = 'Please Select Department';
            } else if($this->input->post('subdepartment_id')==='') {
                $Return['error'] = 'Please Select Sub Department';
            } 
					
			if($Return['error']!=''){
				$this->output($Return);
			}
		
			$data = array(
                'grouping_name'     => $this->input->post('grouping_name'),
                'department_id'     => $this->input->post('department_id'),
                'sub_department_id' => $this->input->post('subdepartment_id'),
			);
			$data = $this->security->xss_clean($data);
			$result = $this->Grouping_model->update_sub_record($data,$id);		
			
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_hr_sub_department_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
		}
	}
	 
	 
}
