<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employeebenefits extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->library('Pdf');
		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
		$this->load->model("Timesheet_model");
		$this->load->model("Overtime_request_model");
		$this->load->model("Company_model");
		$this->load->model("Finance_model");
		$this->load->model("Cpf_options_model");
		$this->load->model("Cpf_percentage_model");
		$this->load->model("Cpf_payslip_model");
		$this->load->model("Contribution_fund_model");
		$this->load->model("Efiling_model");
		$this->load->model("PaymentDeduction_Model");
		$this->load->helper('string');
		$this->load->helper('file');
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
		$data['title'] = 'Employee Benefits';
        $data['breadcrumbs'] = 'Employee Benefits';
		$data['path_url'] = 'employee_benefits';
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['accommodations'] = $this->PaymentDeduction_Model->get_all_accommodations()->result();
		// $data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if(in_array('429',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/employees/employee_benefits", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}		  
	}
	
	public function setaccommodation()
	{
		if($this->input->post('type') == 'accommodation_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();	
			
			if($this->input->post('accommodation_title') === '') {
				$Return['error'] = 'Accommodation Title is required';
			}elseif($this->input->post('address_1') === '') {
				$Return['error'] = 'Enter address of accommodation';
			}elseif($this->input->post('period_from') === '') {
				$Return['error'] = 'Select accommodation period from';
			}elseif($this->input->post('period_to') === '') {
				$Return['error'] = 'Select accommodation period to';
			}elseif(date('Y-m-d',strtotime($this->input->post('period_to'))) <= date('Y-m-d',strtotime($this->input->post('period_from')))) {
				$Return['error'] = 'Accommodation period to should be greater than period from';
			}elseif($this->input->post('accommodation_type') === '') {
				$Return['error'] = 'Select accommodation type';
			}elseif($this->input->post('accommodation_type') === 'owned' && $this->input->post('annual_value') === '') {
				$Return['error'] = 'Enter Annual Value of Accommodation';
			}elseif($this->input->post('annual_value') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('annual_value')) ) {
				$Return['error'] = 'Entered Annual Value is not correct';
			}elseif($this->input->post('annual_value') != '' && $this->input->post('furnished') === '') {
				$Return['error'] = 'Select Furnished Type';
			}elseif($this->input->post('accommodation_type') === 'rented' && $this->input->post('rent_paid') === '') {
				$Return['error'] = 'Enter Annual rent of Accommodation';
			}elseif($this->input->post('rent_paid') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('rent_paid')) ) {
				$Return['error'] = 'Entered Annual Rent Value is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'title' => $this->input->post('accommodation_title'),
				'address_line_1' => $this->input->post('address_1'),
				'period_from' => $this->input->post('period_from'),
				'period_to' => $this->input->post('period_to')
			);

			if($this->input->post('address_2')) {
				$data['address_line_2'] = $this->input->post('address_2');
			}
			$accommodation_type = $this->input->post('accommodation_type');
			if($accommodation_type == 'owned') {
				$data['accommodation_type'] = 1;
				$data['annual_value'] = $this->input->post('annual_value');
				$data['furnished_type'] = $this->input->post('furnished');
			}elseif($accommodation_type == 'rented') {
				$data['accommodation_type'] = 2;
				$data['rent_value'] = $this->input->post('rent_paid');
			}
			
			$result = $this->PaymentDeduction_Model->saveAccommodation($data);
			if ($result == TRUE) {
				$Return['result'] = 'Accommodation saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			if($Return['error'] != ''){
				$this->output($Return);
			}

			$this->output($Return);
			exit;
		}
	}

	public function getaccommodation()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$accommodations = $this->PaymentDeduction_Model->get_all_accommodations();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($accommodations->result() as $ac) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $ac->id . '" data-field_type="read_accommodations"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $ac->id . '" data-token_type="accommodations"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$ac->title,
				$ac->address_line_1 . ' ' . $ac->address_line_2,
				date('d M Y', strtotime($ac->period_from)) . ' - ' . date('d M Y', strtotime($ac->period_to)),
				$this->Xin_model->currency_sign($ac->annual_value ?? ''),
				($ac->furnished_type == 2) ? 'Partially Furnished' : 'Fully Furnished',
				$this->Xin_model->currency_sign($ac->rent_value)
			);
		}

	  	$output = array(
		   "draw" => $draw,
			"recordsTotal" => $accommodations->num_rows(),
			"recordsFiltered" => $accommodations->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}

	public function get_employees() {

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);
		
		$data = array(
			'company_id' => $id
			);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/get_employees", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	
	public function get_accommodation($id)
	{
		$accommodation = $this->PaymentDeduction_Model->getAccommodationById($id);
		if($accommodation) {
			$Return['result'] = $accommodation;
		}else {
			$Return['error'] =  'No record found';
		}
		$this->output($Return);
		exit;
	}

	public function setemployeeaccommodation()
	{
		if($this->input->post('type') == 'accommodation_employee_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('accommodation') === '') {
				$Return['error'] = 'Select Accommodation';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('employee_acc_from') === '') {
				$Return['error'] = 'Select employee accommodation period from';
			}elseif($this->input->post('employee_acc_to') === '') {
				$Return['error'] = 'Select accommodation period to';
			}elseif($this->input->post('employee_acc_to') <= $this->input->post('employee_acc_from')) {
				$Return['error'] = 'Accommodation period to should be greater than period from';
			}elseif($this->input->post('employee_acc_from') != '' && $this->input->post('employee_acc_to') != '') {
				$accommodation = $this->PaymentDeduction_Model->getAccommodationById($this->input->post('accommodation'));
				$acc_from = new DateTime($accommodation->period_from);
				$acc_to = new DateTime($accommodation->period_to);
				$emp_acc_from = new DateTime($this->input->post('employee_acc_from'));
				$emp_acc_to = new DateTime($this->input->post('employee_acc_to'));

				if($emp_acc_from < $acc_from || $emp_acc_from >= $acc_to) {
					$Return['error'] = 'Accommodation Date should be in accommodation period range';
				}elseif($emp_acc_to <= $acc_from || $emp_acc_to > $acc_to) {
					$Return['error'] = 'Accommodation Date should be in accommodation period range';
				}
			}elseif($this->input->post('employee_rent') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('employee_rent')) ) {
				$Return['error'] = 'Entered Employee Rent value is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'accommodation_id' => $this->input->post('accommodation'),
				'employee_id' => $this->input->post('employee_id'),
				'period_from' => $this->input->post('employee_acc_from'),
				'period_to' => $this->input->post('employee_acc_to')
			);
			if($this->input->post('employee_rent')) {
				$data['rent_paid'] = $this->input->post('employee_rent');
			}

			$result = $this->PaymentDeduction_Model->saveEmployeeAccommodation($data);
			if ($result == TRUE) {
				$Return['result'] = 'Accommodation saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}

	public function getemployeeaccommodation()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$employee_accommodations = $this->PaymentDeduction_Model->get_all_employee_accommodations();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($employee_accommodations->result() as $ac) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $ac->id . '" data-field_type="employee_accommodation"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $ac->id . '" data-token_type="employee_accommodation"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$ac->first_name . ' ' . $ac->last_name,
				$ac->title,
				date('d M Y', strtotime($ac->period_from)) . ' - ' . date('d M Y', strtotime($ac->period_to)),
				$this->Xin_model->currency_sign($ac->rent_paid)
			);
		}

	  	$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_accommodations->num_rows(),
			"recordsFiltered" => $employee_accommodations->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}

	public function setemployeeutilitybenefits()
	{
		if($this->input->post('type') == 'benefit_utilities_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif(is_array($this->input->post('utility')) && count($this->input->post('utility')) == 0) {
				$Return['error'] = 'Select at least one utility';
			}elseif(count($this->input->post('utility')) > 0) {
				$utility = $this->input->post('utility');
				$utility_amount = $this->input->post('utility_amount');
				foreach($utility as $i => $u) {
					if($utility_amount[$i] === '') {
						$Return['error'] = 'Enter Actual amount for ' . $u;
					}elseif(!preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $utility_amount[$i])) {
						$Return['error'] = 'Amount entered for ' . $u . ' is not correct';
					}
				}
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'benefit_year' => $this->input->post('benefits_year'),
				'utility' => $this->input->post('utility'),
				'utility_remark' => $this->input->post('utility_remark'),
				'utility_amount' => $this->input->post('utility_amount'),
			);
			
			$result = $this->PaymentDeduction_Model->saveEmployeeUtilities($data);
			if ($result == TRUE) {
				$Return['result'] = 'Utilities saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;

		}
	}

	public function getemployeeutility()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$employee_utilities = $this->PaymentDeduction_Model->get_all_employee_utilities();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($employee_utilities->result() as $eu) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $eu->id . '" data-field_type="utility"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $eu->id . '" data-token_type="utility"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$eu->first_name . ' ' . $eu->last_name,
				$eu->benefit_year,
				ucwords($eu->utility),
				$eu->utility_remark,
				$this->Xin_model->currency_sign($eu->utility_amount)
			);
		}

	  	$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_utilities->num_rows(),
			"recordsFiltered" => $employee_utilities->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}

	public function setemployeedriverbenefits()
	{
		if($this->input->post('type') == 'benefit_driver_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif($this->input->post('driver_wage') === '') {
				$Return['error'] = 'Please enter driver annual wage';
			}elseif($this->input->post('driver_wage') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('driver_wage'))) {
				$Return['error'] = 'Annual wage entered is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'benefit_year' => $this->input->post('benefits_year'),
				'driver_wage' => $this->input->post('driver_wage')
			);
			
			$result = $this->PaymentDeduction_Model->saveEmployeeDriver($data);
			if ($result == TRUE) {
				$Return['result'] = 'Driver wage saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;
		}
	}

	public function getemployeedriver()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$employee_drivers = $this->PaymentDeduction_Model->get_all_employee_drivers();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($employee_drivers->result() as $db) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $db->id . '" data-field_type="driver"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $db->id . '" data-token_type="driver"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$db->first_name . ' ' . $db->last_name,
				$db->benefit_year,
				$this->Xin_model->currency_sign($db->driver_wage)
			);
		}

	  	$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_drivers->num_rows(),
			"recordsFiltered" => $employee_drivers->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}

	public function setemployeehousekeepingbenefits()
	{
		if($this->input->post('type') == 'benefit_housekeeping_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif(is_array($this->input->post('housekeeping_service')) && count($this->input->post('housekeeping_service')) == 0) {
				$Return['error'] = 'Select at least one Housekeeping Service';
			}elseif(count($this->input->post('housekeeping_service')) > 0) {
				$housekeeping_service = $this->input->post('housekeeping_service');
				$housekeeping_amount = $this->input->post('housekeeping_amount');
				foreach($housekeeping_service as $i => $u) {
					if($housekeeping_amount[$i] === '') {
						$Return['error'] = 'Enter Annual wage for ' . $u;
					}elseif(!preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $housekeeping_amount[$i])) {
						$Return['error'] = 'Amount entered for ' . $u . ' is not correct';
					}
				}
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'benefit_year' => $this->input->post('benefits_year'),
				'housekeeping_service' => $this->input->post('housekeeping_service'),
				'housekeeping_remark' => $this->input->post('housekeeping_remark'),
				'housekeeping_amount' => $this->input->post('housekeeping_amount'),
			);
			
			$result = $this->PaymentDeduction_Model->saveEmployeeHousekeeping($data);
			if ($result == TRUE) {
				$Return['result'] = 'Housekeeping wages saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;

		}
	}

	public function getemployeehousekeeping()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$employee_housekeeping = $this->PaymentDeduction_Model->get_all_employee_housekeeping();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($employee_housekeeping->result() as $hk) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $hk->id . '" data-field_type="housekeeping"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $hk->id . '" data-token_type="housekeeping"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$hk->first_name . ' ' . $hk->last_name,
				$hk->benefit_year,
				ucwords($hk->housekeeping_service),
				$hk->housekeeping_remark,
				$this->Xin_model->currency_sign($hk->housekeeping_amount)
			);
		}

	  	$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_housekeeping->num_rows(),
			"recordsFiltered" => $employee_housekeeping->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}

	public function setEmployeeHotelAccommodationBenefits()
	{
		if($this->input->post('type') == 'benefit_hotel_accommodation_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('hotel_name') === '') {
				$Return['error'] = 'Enter Hotel name';
			}elseif($this->input->post('ht_check_in') === '') {
				$Return['error'] = 'Select Check In Date';
			}elseif($this->input->post('ht_check_out') === '') {
				$Return['error'] = 'Select Check Out Date';
			}elseif($this->input->post('ht_check_out') <= $this->input->post('ht_check_in')) {
				$Return['error'] = 'Check Out Date should be greater than Check In Date';
			}elseif($this->input->post('ht_actual_cost') === '') {
				$Return['error'] = 'Enter actual cost of hotel accommodation';
			}elseif($this->input->post('ht_actual_cost') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('ht_actual_cost'))) {
				$Return['error'] = 'Actual Cost entered is not correct';
			}elseif($this->input->post('ht_employee_paid') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('ht_employee_paid'))) {
				$Return['error'] = 'Employee Paid Amount is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'hotel_name' => $this->input->post('hotel_name'),
				'check_in' => $this->input->post('ht_check_in'),
				'check_out' => $this->input->post('ht_check_out'),
				'actual_cost' => $this->input->post('ht_actual_cost')
			);
			if($this->input->post('ht_employee_paid') != '') {
				$data['employee_paid'] = $this->input->post('ht_employee_paid');
			}
			
			$result = $this->PaymentDeduction_Model->saveEmployeeHotelAccommodation($data);
			if ($result == TRUE) {
				$Return['result'] = 'Hotel Accommodation Benefits saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;
		}
	}

	public function getEmployeeHotelAccommodation()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$employee_hotelaccommodation = $this->PaymentDeduction_Model->get_all_employee_hotelaccommodation();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($employee_hotelaccommodation->result() as $ha) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $ha->id . '" data-field_type="hotel_accommodation"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $ha->id . '" data-token_type="hotel_accommodation"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$ha->first_name . ' ' . $ha->last_name,
				ucwords($ha->hotel_name),
				date('d M Y', strtotime($ha->check_in)),
				date('d M Y', strtotime($ha->check_out)),
				$this->Xin_model->currency_sign($ha->actual_cost),
				$this->Xin_model->currency_sign($ha->employee_paid)
			);
		}

	  	$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_hotelaccommodation->num_rows(),
			"recordsFiltered" => $employee_hotelaccommodation->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}

	public function setEmployeeOtherBenefits()
	{
		if($this->input->post('type') == 'other_benefit_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif(is_array($this->input->post('other_benefit')) && count($this->input->post('other_benefit')) == 0) {
				$Return['error'] = 'Select at least one Housekeeping Service';
			}elseif(count($this->input->post('other_benefit')) > 0) {
				$other_benefit = $this->input->post('other_benefit');
				$other_benefit_cost = $this->input->post('other_benefit_cost');
				foreach($other_benefit as $i => $u) {
					if($other_benefit_cost[$i] === '') {
						$Return['error'] = 'Enter Actual cost for ' . $u;
					}elseif(!preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $other_benefit_cost[$i])) {
						$Return['error'] = 'Amount entered for ' . $u . ' is not correct';
					}
				}
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' 				=> 	$this->input->post('employee_id'),
				'benefit_year' 				=> 	$this->input->post('benefits_year'),
				'other_benefit' 			=> 	$this->input->post('other_benefit'),
				'other_benefit_remark' 		=> 	$this->input->post('other_benefit_remark'),
				'other_benefit_cost' 		=> 	$this->input->post('other_benefit_cost'),
				'deductible_from_salary' 	=> 	$this->input->post('deductible_from_salary')
			);
		
			$result = $this->PaymentDeduction_Model->saveEmployeeOtherBenefits($data);
			if ($result == TRUE) {
				$Return['result'] = 'Other Benefits saved successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;

		}
	}

	public function getEmployeeOtherBenefits()
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/employees/employee_benefits", $data);
		} else {
			redirect('admin/');
		}
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$employee_other_benefits = $this->PaymentDeduction_Model->get_all_employee_other_benefits();
		
		$data = array();
		/*$system = $this->Xin_model->read_setting_info(1);
		$default_currency = $this->Xin_model->read_currency_con_info($system[0]->default_currency_id);
		if(!is_null($default_currency)) {
			$current_rate = $default_currency[0]->to_currency_rate;
			$current_title = $default_currency[0]->to_currency_title;
		} else {
			$current_rate = 1;
			$current_title = 'USD';
		}*/

        foreach($employee_other_benefits->result() as $ob) {			
			//$current_amount = $r->allowance_amount * $current_rate;
			// if($r->salary_month == ''){
			// 	$month = 'Recurring';
			// } else {
			// 	$month = date('M Y', strtotime($r->salary_month));
			// }
			
			$action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $ob->id . '" data-field_type="other_benefits"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $ob->id . '" data-token_type="other_benefits"><span class="fa fa-trash"></span></button></span>';
			
			$data[] = array(
				$action,
				$ob->first_name . ' ' . $ob->last_name,
				$ob->benefit_year,
				ucwords($ob->other_benefit),
				$ob->other_benefit_remark,
				$this->Xin_model->currency_sign($ob->other_benefit_cost)
			);
		}

	  	$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_other_benefits->num_rows(),
			"recordsFiltered" => $employee_other_benefits->num_rows(),
			"data" => $data
		);
	  	echo json_encode($output);
	  	exit();
	}


	// start here

	

	function dialog_read_accommodations(){
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $accommodations = $this->PaymentDeduction_Model->read_accommodations_information($id);
        $data = array(
            'id' => $accommodations[0]->id,
            'accommodation_title' => $accommodations[0]->title,
            'address_1' => $accommodations[0]->address_line_1,
            'address_2' => $accommodations[0]->address_line_2,
            'period_from' => $accommodations[0]->period_from,
            'period_to' => $accommodations[0]->period_to,
            'accommodation_type' => $accommodations[0]->accommodation_type,
            'annual_value' => $accommodations[0]->annual_value,
            'furnished' => $accommodations[0]->furnished_type,
            'rent_paid' => $accommodations[0]->rent_value,
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}

	public function update_accommodations_info()
    {

        if ($this->input->post('type') == 'update_accommodations_info') {
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();	
			
			if($this->input->post('accommodation_title') === '') {
				$Return['error'] = 'Accommodation Title is required';
			}elseif($this->input->post('address_1') === '') {
				$Return['error'] = 'Enter address of accommodation';
			}elseif($this->input->post('period_from') === '') {
				$Return['error'] = 'Select accommodation period from';
			}elseif($this->input->post('period_to') === '') {
				$Return['error'] = 'Select accommodation period to';
			}elseif($this->input->post('period_to') <= $this->input->post('period_from')) {
				$Return['error'] = 'Accommodation period to should be greater than period from';
			}elseif($this->input->post('accommodation_type') === '') {
				$Return['error'] = 'Select accommodation type';
			}elseif($this->input->post('accommodation_type') === 'owned' && $this->input->post('annual_value') === '') {
				$Return['error'] = 'Enter Annual Value of Accommodation';
			}elseif($this->input->post('annual_value') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('annual_value')) ) {
				$Return['error'] = 'Entered Annual Value is not correct';
			}elseif($this->input->post('annual_value') != '' && $this->input->post('furnished') === '') {
				$Return['error'] = 'Select Furnished Type';
			}elseif($this->input->post('accommodation_type') === 'rented' && $this->input->post('rent_paid') === '') {
				$Return['error'] = 'Enter Annual rent of Accommodation';
			}elseif($this->input->post('rent_paid') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('rent_paid')) ) {
				$Return['error'] = 'Entered Annual Rent Value is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'title' => $this->input->post('accommodation_title'),
				'address_line_1' => $this->input->post('address_1'),
				'period_from' => $this->input->post('period_from'),
				'period_to' => $this->input->post('period_to')
			);

			if($this->input->post('address_2')) {
				$data['address_line_2'] = $this->input->post('address_2');
			}
			$accommodation_type = $this->input->post('accommodation_type');
			if($accommodation_type == 'owned') {
				$data['accommodation_type'] = 1;
				$data['annual_value'] = $this->input->post('annual_value');
				$data['furnished_type'] = $this->input->post('furnished');

				// delete data
				$data['rent_value'] = '';


			}elseif($accommodation_type == 'rented') {
				$data['accommodation_type'] = 2;
				$data['rent_value'] = $this->input->post('rent_paid');


				// delete data
				$data['annual_value'] = '';
				$data['furnished_type'] = '';
			}
			
			$result = $this->PaymentDeduction_Model->updateAccommodation($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Accommodation Update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			if($Return['error'] != ''){
				$this->output($Return);
			}

			$this->output($Return);
			exit;
        }
    }

	public function delete_accommodations(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_accommodations_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	function dialog_employee_accommodation(){
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $accommodations = $this->PaymentDeduction_Model->read_employee_accommodations_information($id);
        $data = array(
            'id' => $accommodations[0]->id,
            'accommodation_id' => $accommodations[0]->accommodation_id,
            'employee_id' => $accommodations[0]->employee_id,
            'period_from' => $accommodations[0]->period_from,
            'period_to' => $accommodations[0]->period_to,
            'rent_paid' => $accommodations[0]->rent_paid,
			'accommodations' => $this->PaymentDeduction_Model->get_all_accommodations()->result(),
			'read_accommodations' => $this->PaymentDeduction_Model->read_accommodations_information($accommodations[0]->accommodation_id),
			'read_employee' => $this->Xin_model->read_user_info($accommodations[0]->employee_id),
			'all_companies' => $this->Xin_model->get_companies(),
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}

	public function update_accommodated_employees_info(){
		if($this->input->post('type') == 'update_accommodated_employees_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('accommodation') === '') {
				$Return['error'] = 'Select Accommodation';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('employee_acc_from') === '') {
				$Return['error'] = 'Select employee accommodation period from';
			}elseif($this->input->post('employee_acc_to') === '') {
				$Return['error'] = 'Select accommodation period to';
			}elseif($this->input->post('employee_acc_to') <= $this->input->post('employee_acc_from')) {
				$Return['error'] = 'Accommodation period to should be greater than period from';
			}elseif($this->input->post('employee_acc_from') != '' && $this->input->post('employee_acc_to') != '') {
				$accommodation = $this->PaymentDeduction_Model->getAccommodationById($this->input->post('accommodation'));
				$acc_from = $accommodation->period_from;
				$acc_to = $accommodation->period_to;
				$emp_acc_from = $this->input->post('employee_acc_from');
				$emp_acc_to = $this->input->post('employee_acc_to');

				if($emp_acc_from < $acc_from || $emp_acc_from >= $acc_to) {
					$Return['error'] = 'Accommodation Date should be in accommodation period range';
				}elseif($emp_acc_to <= $acc_from || $emp_acc_to > $acc_to) {
					$Return['error'] = 'Accommodation Date should be in accommodation period range';
				}
			}elseif($this->input->post('employee_rent') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('employee_rent')) ) {
				$Return['error'] = 'Entered Employee Rent value is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'accommodation_id' => $this->input->post('accommodation'),
				'employee_id' => $this->input->post('employee_id'),
				'period_from' => $this->input->post('employee_acc_from'),
				'period_to' => $this->input->post('employee_acc_to')
			);
			if($this->input->post('employee_rent')) {
				$data['rent_paid'] = $this->input->post('employee_rent');
			}

			$result = $this->PaymentDeduction_Model->updateEmployeeAccommodation($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Accommodation Update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}

	public function delete_employee_accommodation(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_employee_accommodation_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	function dialog_utility(){
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $utility = $this->PaymentDeduction_Model->read_utility_information($id);
        $data = array(
            'id' => $utility[0]->id,
            'employee_id' => $utility[0]->employee_id,
            'benefit_year' => $utility[0]->benefit_year,
            'utility' => $utility[0]->utility,
            'utility_remark' => $utility[0]->utility_remark,
			'utility_amount' => $utility[0]->utility_amount,
			'read_employee' => $this->Xin_model->read_user_info($utility[0]->employee_id),
			'all_companies' => $this->Xin_model->get_companies(),
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}

	public function update_utility_info(){
		if($this->input->post('type') == 'utility') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif($this->input->post('utility') === '') {
				$Return['error'] = 'Select at least one utility';
			}elseif($this->input->post('utility_amount') === '') {
				$Return['error'] = 'Enter  utility Amount';
			}elseif($this->input->post('utility_amount') !== '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('utility_amount')) ) {
				$Return['error'] = 'Entered utility Amount value is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'benefit_year' => $this->input->post('benefits_year'),
				'utility' => $this->input->post('utility'),
				'utility_remark' => $this->input->post('utility_remark'),
				'utility_amount' => $this->input->post('utility_amount'),
			);
			
			$result = $this->PaymentDeduction_Model->updateEmployeeUtilities($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Utilities Update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;

		}
	}

	public function delete_utility(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_utility_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function dialog_driver(){	
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $driver = $this->PaymentDeduction_Model->read_driver_information($id);
        $data = array(
            'id' 				=> $driver[0]->id,
            'employee_id' 		=> $driver[0]->employee_id,
            'benefit_year' 		=> $driver[0]->benefit_year,
			'driver_wage' 		=> $driver[0]->driver_wage,
			'read_employee' 	=> $this->Xin_model->read_user_info($driver[0]->employee_id),
			'all_companies' 	=> $this->Xin_model->get_companies(),
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}

	public function update_driver_info(){
		if($this->input->post('type') == 'driver') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif($this->input->post('driver_wage') === '') {
				$Return['error'] = 'Please enter driver annual wage';
			}elseif($this->input->post('driver_wage') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('driver_wage'))) {
				$Return['error'] = 'Annual wage entered is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'benefit_year' => $this->input->post('benefits_year'),
				'driver_wage' => $this->input->post('driver_wage')
			);
			
			$result = $this->PaymentDeduction_Model->updateEmployeeDriver($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Driver wage Update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;
		}
	}

	public function delete_driver(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_driver_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function dialog_housekeeping(){	
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $housekeeping = $this->PaymentDeduction_Model->read_housekeeping_information($id);
        $data = array(
            'id' 				=> $housekeeping[0]->id,
            'employee_id' 		=> $housekeeping[0]->employee_id,
            'benefit_year' 		=> $housekeeping[0]->benefit_year,
			'housekeeping_service' => $housekeeping[0]->housekeeping_service,
			'housekeeping_remark' => $housekeeping[0]->housekeeping_remark,
			'housekeeping_amount' => $housekeeping[0]->housekeeping_amount,
			'read_employee' 	=> $this->Xin_model->read_user_info($housekeeping[0]->employee_id),
			'all_companies' 	=> $this->Xin_model->get_companies(),
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}
	
	public function update_housekeeping_info(){
		if($this->input->post('type') == 'housekeeping') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif($this->input->post('housekeeping_service') === '') {
				$Return['error'] = 'Select at least one Housekeeping Service';
			}elseif($this->input->post('housekeeping_amount') === '') {
				$Return['error'] = 'Please Enter Housekeeping Annual wage';
			}elseif($this->input->post('driver_wage') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('driver_wage'))) {
				$Return['error'] = 'Annual wage entered is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'benefit_year' => $this->input->post('benefits_year'),
				'housekeeping_service' => $this->input->post('housekeeping_service'),
				'housekeeping_remark' => $this->input->post('housekeeping_remark'),
				'housekeeping_amount' => $this->input->post('housekeeping_amount'),
			);
			
			$result = $this->PaymentDeduction_Model->updateEmployeeHousekeeping($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Housekeeping wages Update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;

		}
	}

	public function delete_housekeeping(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_housekeeping_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function dialog_hotel_accommodation(){
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $hotel_accommodation = $this->PaymentDeduction_Model->read_hotel_accommodation_information($id);
        $data = array(
            'id' 				=> $hotel_accommodation[0]->id,
            'employee_id' 		=> $hotel_accommodation[0]->employee_id,
            'hotel_name' 		=> $hotel_accommodation[0]->hotel_name,
			'check_in' => $hotel_accommodation[0]->check_in,
			'check_out' => $hotel_accommodation[0]->check_out,
			'actual_cost' => $hotel_accommodation[0]->actual_cost,
			'employee_paid' => $hotel_accommodation[0]->employee_paid,
			'read_employee' 	=> $this->Xin_model->read_user_info($hotel_accommodation[0]->employee_id),
			'all_companies' 	=> $this->Xin_model->get_companies(),
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}

	public function update_hotel_accommodation_info(){
		if($this->input->post('type') == 'hotel_accommodation') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('hotel_name') === '') {
				$Return['error'] = 'Enter Hotel name';
			}elseif($this->input->post('ht_check_in') === '') {
				$Return['error'] = 'Select Check In Date';
			}elseif($this->input->post('ht_check_out') === '') {
				$Return['error'] = 'Select Check Out Date';
			}elseif($this->input->post('ht_check_out') <= $this->input->post('ht_check_in')) {
				$Return['error'] = 'Check Out Date should be greater than Check In Date';
			}elseif($this->input->post('ht_actual_cost') === '') {
				$Return['error'] = 'Enter actual cost of hotel accommodation';
			}elseif($this->input->post('ht_actual_cost') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('ht_actual_cost'))) {
				$Return['error'] = 'Actual Cost entered is not correct';
			}elseif($this->input->post('ht_employee_paid') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('ht_employee_paid'))) {
				$Return['error'] = 'Employee Paid Amount is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'hotel_name' => $this->input->post('hotel_name'),
				'check_in' => $this->input->post('ht_check_in'),
				'check_out' => $this->input->post('ht_check_out'),
				'actual_cost' => $this->input->post('ht_actual_cost')
			);
			if($this->input->post('ht_employee_paid') != '') {
				$data['employee_paid'] = $this->input->post('ht_employee_paid');
			}
			
			$result = $this->PaymentDeduction_Model->updateEmployeeHotelAccommodation($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Hotel Accommodation Benefits Update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;
		}
		
	}

	public function delete_hotel_accommodation(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_hotel_accommodation_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function dialog_other_benefits(){
		$session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $other_benefits = $this->PaymentDeduction_Model->read_other_benefits_information($id);
        $data = array(
            'id' 					=> 	$other_benefits[0]->id,
            'employee_id' 			=> 	$other_benefits[0]->employee_id,
            'benefit_year' 			=> 	$other_benefits[0]->benefit_year,
			'other_benefit' 		=> 	$other_benefits[0]->other_benefit,
			'other_benefit_remark' 	=> 	$other_benefits[0]->other_benefit_remark,
			'other_benefit_cost' 	=> 	$other_benefits[0]->other_benefit_cost,
			'deductible_from_salary' => $other_benefits[0]->deductible_from_salary,
			'read_employee' 	=> $this->Xin_model->read_user_info($other_benefits[0]->employee_id),
			'all_companies' 	=> $this->Xin_model->get_companies(),
        );
        if (!empty($session)) {
            $this->load->view('admin/employees/dialog_employee_benefits', $data);
        } else {
            redirect('admin/');
        }
	}

	public function update_other_benefits_info(){
		if($this->input->post('type') == 'other_benefits') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if($this->input->post('company') === '') {
				$Return['error'] = 'Select company';
			}elseif($this->input->post('employee_id') === '') {
				$Return['error'] = 'Select an Employee';
			}elseif($this->input->post('benefits_year') === '') {
				$Return['error'] = 'Select year of benefit';
			}elseif($this->input->post('other_benefit') === '') {
				$Return['error'] = 'Select at least one Housekeeping Service';
			}elseif($this->input->post('other_benefit_cost') != '' && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $this->input->post('other_benefit_cost'))) {
				$Return['error'] = 'Actual Cost  Amount is not correct';
			}

			if($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'employee_id' 			=> 	$this->input->post('employee_id'),
				'benefit_year' 			=> 	$this->input->post('benefits_year'),
				'other_benefit' 		=> 	$this->input->post('other_benefit'),
				'other_benefit_remark' 	=> 	$this->input->post('other_benefit_remark'),
				'other_benefit_cost' 	=> 	$this->input->post('other_benefit_cost'),
				'deductible_from_salary'=> 	$this->input->post('deductible_from_salary') ? 1 : 0
			);
			
			$result = $this->PaymentDeduction_Model->updateEmployeeOtherBenefits($data,$this->input->post('id'));
			if ($result == TRUE) {
				$Return['result'] = 'Other Benefits update successfully';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
			exit;

		}
	}

	public function delete_other_benefits(){
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->PaymentDeduction_Model->delete_other_benefits_record($id);
			if(isset($id)) {
				$Return['result'] = "Delete Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

}