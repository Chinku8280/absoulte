<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employees_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function saveEmployeeImmigrationStatus(array $data)
	{
		$this->db->insert('xin_employee_immigration_status', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function get_bank_account_info_add($id)
	{
		$data = $this->db->where('employee_id', $id)->get('xin_employee_bankaccount')->row();
		if ($data) {
			return $data;
		} else {
			return false;
		}
	}
	public function get_pendding_salary($user_id, $pay_date)
	{
		return $this->db->where('user_id', $user_id)->order_by('id', 'DESC')->limit(1)->get('salary_backup_table')->row();
	}
	public function get_pendding_salary_advance($user_id, $pay_date)
	{
		return $this->db->where('employee_id', $user_id)->where('month', $pay_date)->get('advance_salary_table')->row();
	}

	public function getEmployeeImmigrationStatus(int $id)
	{
		$this->db->select('id, employee_id, immigration_id, issue_date');
		$this->db->from('xin_employee_immigration_status');
		$this->db->where('employee_id', $id);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function updateEmployeeImmigrationStatus(array $data, int $id)
	{
		$this->db->trans_begin();
		$ims_exists = $this->getEmployeeImmigrationStatus($id);
		if ($ims_exists) {
			$this->db->where('employee_id', $id);
			$this->db->set('immigration_id', $data['immigration_id']);
			if (isset($data['issue_date']) && $data['immigration_id'] == 2) {
				$this->db->set('issue_date', $data['issue_date']);
			} else {
				$this->db->set('issue_date', null);
			}
			$this->db->update('xin_employee_immigration_status');
		} else {
			$im_data = array(
				'employee_id' => $id,
				'immigration_id' => $data['immigration_id']
			);
			if (isset($data['issue_date'])) {
				$im_data['issue_date'] = $data['issue_date'];
			}
			$this->db->insert('xin_employee_immigration_status', $im_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}




	// get all employes
	public function get_employees()
	{
		return $this->db->order_by('is_active', 'DESC')
			->order_by('first_name', 'asc')
			->get("xin_employees");
	}

	public function get_employees_by_employee_id($user_id)
	{
		return $this->db->order_by('is_active', 'DESC')
			->order_by('first_name', 'asc')
			->where('user_id',$user_id)
			->get("xin_employees");
	}

	public function get_all_employees()
	{
		return $this->db->where('is_active', '1')->get("xin_employees");
	}
	// get all my team employes > not super admin
	public function get_employees_my_team($cid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id != ? and reports_to = ?';
		$binds = array(1, $cid);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes > not super admin
	public function get_employees_for_other($cid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id != ? and company_id = ?';
		$binds = array(1, $cid);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes > not super admin
	public function get_employees_for_location($cid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id != ? and location_id = ?';
		$binds = array(1, $cid);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes|company>
	public function get_company_employees_flt($cid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? ORDER BY is_active desc';
		$binds = array($cid);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all MY TEAM employes
	public function get_my_team_employees($reports_to)
	{

		$sql = 'SELECT * FROM xin_employees WHERE reports_to = ?';
		$binds = array($reports_to);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes>company|location >
	public function get_company_location_employees_flt($cid, $lid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id = ? ORDER BY is_active desc';
		$binds = array($cid, $lid);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes>company|location|department >
	public function get_company_location_department_employees_flt($cid, $lid, $dep_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id = ? and department_id = ? ORDER BY is_active desc';
		$binds = array($cid, $lid, $dep_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes>company|location|department|designation >
	public function get_company_location_department_designation_employees_flt($cid, $lid, $dep_id, $des_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ? ORDER BY is_active desc';
		$binds = array($cid, $lid, $dep_id, $des_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get all employes >
	public function get_employees_payslip($date = '')
	{
		$lastDay = date('t-m-Y', strtotime("01-".$date));
		$date = date('Y-m-d', strtotime($lastDay));
		$sql = 'SELECT * FROM xin_employees WHERE user_role_id != ? AND STR_TO_DATE(date_of_joining,"%d-%m-%Y") <= ?';
		$binds = array(1, $date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	public function get_single_employees_payslip($date,$user_id)
	{
		$lastDay = date('t-m-Y', strtotime("01-".$date));
		$date = date('Y-m-d', strtotime($lastDay));
		$sql = 'SELECT * FROM xin_employees WHERE user_id = ? AND user_role_id != ? AND STR_TO_DATE(date_of_joining,"%d-%m-%Y") <= ?';
		$binds = array($user_id,1, $date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get employes
	public function get_attendance_employees()
	{

		$sql = 'SELECT * FROM xin_employees WHERE is_active = ?';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	public function get_attendance_employees_company($company_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE is_active = ? AND company_id = ?';
		$binds = array(1, $company_id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	public function get_attendance_employees_company_location($company_id, $ref_location_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE is_active = ? AND company_id = ? AND location_id = ?';
		$binds = array(1, $company_id, $ref_location_id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}



	// get employes with location
	public function get_attendance_location_employees($location_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE location_id = ? and is_active = ?';
		$binds = array($location_id, 1);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get total number of employees
	public function get_total_employees()
	{	
		$this->db->where('is_active',1);
		$query = $this->db->get("xin_employees");
		return $query->num_rows();
	}

	public function read_employee_information($id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// check employeeID
	public function check_employee_id($id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// check old password
	public function check_old_password($old_password, $user_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
		$binds = array($user_id);
		$query = $this->db->query($sql, $binds);
		//$rw_password = $query->result();
		$options = array('cost' => 12);
		$password_hash = password_hash($old_password, PASSWORD_BCRYPT, $options);
		if ($query->num_rows() > 0) {
			$rw_password = $query->result();
			if (password_verify($old_password, $rw_password[0]->password)) {
				return 1;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// check username
	public function check_employee_username($id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE username = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// check email
	public function check_employee_email($id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE email = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}

	// Function to add record in table
	public function add($data)
	{
		$this->db->insert('xin_employees', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_record($id)
	{
		$this->db->where('user_id', $id);
		$this->db->delete('xin_employees');

		$this->db->where('id', $id);
		$this->db->delete('users');
	}

	/*  Update Employee Record */

	// Function to update record in table
	public function update_record($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > basic_info
	public function basic_info($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function add_basic_info($data)
	{
		$this->db->insert('xin_employee_salary', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function update_basic_info($data, $id)
	{
		$this->db->where('salary_id', $id);
		if ($this->db->update('xin_employee_salary', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > change_password
	public function change_password($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > social_info
	public function social_info($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > profile picture
	public function profile_picture($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > contact_info
	public function contact_info_add($data)
	{
		$this->db->insert('xin_employee_contacts', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > exempt_indicator_info_add
	public function exempt_indicator_info_add($data)
	{
		$this->db->insert('xin_employee_exempt_indicator', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > contact_info
	public function contact_info_update($data, $id)
	{
		$this->db->where('contact_id', $id);
		if ($this->db->update('xin_employee_contacts', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > document_info_update
	public function document_info_update($data, $id)
	{
		$this->db->where('document_id', $id);
		if ($this->db->update('xin_employee_documents', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > document_info_update
	public function img_document_info_update($data, $id)
	{
		$this->db->where('immigration_id', $id);
		if ($this->db->update('xin_employee_immigration', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > document info
	public function document_info_add($data)
	{
		$this->db->insert('xin_employee_documents', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > immigration info
	public function immigration_info_add($data)
	{
		$this->db->insert('xin_employee_immigration', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	// Function to add record in table > qualification_info_add
	public function qualification_info_add($data)
	{
		$this->db->insert('xin_employee_qualification', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > qualification_info_update
	public function qualification_info_update($data, $id)
	{
		$this->db->where('qualification_id', $id);
		if ($this->db->update('xin_employee_qualification', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > work_experience_info_add
	public function work_experience_info_add($data)
	{
		$this->db->insert('xin_employee_work_experience', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > work_experience_info_update
	public function work_experience_info_update($data, $id)
	{
		$this->db->where('work_experience_id', $id);
		if ($this->db->update('xin_employee_work_experience', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > bank_account_info_add
	public function bank_account_info_add($data)
	{
		$this->db->insert('xin_employee_bankaccount', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > security level info_add
	public function security_level_info_add($data)
	{
		$this->db->insert('xin_employee_security_level', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > bank_account_info_update
	public function bank_account_info_update($data, $id)
	{
		$this->db->where('bankaccount_id', $id);
		if ($this->db->update('xin_employee_bankaccount', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > security_level_info_update
	public function security_level_info_update($data, $id)
	{
		$this->db->where('security_level_id', $id);
		if ($this->db->update('xin_employee_security_level', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > contract_info_add
	public function contract_info_add($data)
	{
		$this->db->insert('xin_employee_contract', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > income_tax_born_info
	public function income_tax_born_info_add($data)
	{
		$this->db->insert('xin_employee_income_tax_born', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//for current contact > employee
	public function check_employee_contact_current($id)
	{

		$sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ? and contact_type = ? limit 1';
		$binds = array($id, 'current');
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	//for permanent contact > employee
	public function check_employee_contact_permanent($id)
	{

		$sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ? and contact_type = ? limit 1';
		$binds = array($id, 'permanent');
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get current contacts by id
	public function read_contact_info_current($id)
	{

		$sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? and contact_type = ? limit 1';
		$binds = array($id, 'current');
		$query = $this->db->query($sql, $binds);

		$row = $query->row();
		return $row;
	}

	// get permanent contacts by id
	public function read_contact_info_permanent($id)
	{

		$sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? and contact_type = ? limit 1';
		$binds = array($id, 'permanent');
		$query = $this->db->query($sql, $binds);

		$row = $query->row();
		return $row;
	}

	// Function to update record in table > contract_info_update
	public function contract_info_update($data, $id)
	{
		$this->db->where('contract_id', $id);
		if ($this->db->update('xin_employee_contract', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > contract_info_update
	public function income_tax_born_info_update($data, $id)
	{
		$this->db->where('income_tax_born_id', $id);
		if ($this->db->update('xin_employee_income_tax_born', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > exempt_indicator_update
	public function exempt_indicator_update($data, $id)
	{
		$this->db->where('exempt_indicator_id', $id);
		if ($this->db->update('xin_employee_exempt_indicator', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > leave_info_add
	public function leave_info_add($data)
	{
		$this->db->insert('xin_employee_leave', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > leave_info_update
	public function leave_info_update($data, $id)
	{
		$this->db->where('leave_id', $id);
		if ($this->db->update('xin_employee_leave', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > leave_info_update
	public function claim_info_update($data, $id, $employee_id)
	{
		$this->db->where('claim_id', $id);
		$this->db->where('employee_id', $employee_id);
		if ($this->db->update('xin_employee_claim', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > shift_info_add
	public function shift_info_add($data)
	{
		$this->db->insert('xin_employee_shift', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > shift_info_update
	public function shift_info_update($data, $id)
	{
		$this->db->where('emp_shift_id', $id);
		if ($this->db->update('xin_employee_shift', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > location_info_add
	public function location_info_add($data)
	{
		$this->db->insert('xin_employee_location', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > location_info_update
	public function location_info_update($data, $id)
	{
		$this->db->where('office_location_id', $id);
		if ($this->db->update('xin_employee_location', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// get all office shifts 
	public function all_office_shifts()
	{
		$query = $this->db->query("SELECT * from xin_office_shift");
		return $query->result();
	}

	// get contacts
	public function set_employee_contacts($id)
	{

		$sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get documents
	public function set_employee_documents($id)
	{

		$sql = 'SELECT * FROM xin_employee_documents WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get documents
	public function get_documents_expired_all()
	{


		$curr_date = date('Y-m-d');

		$query = $this->db->query("SELECT * from xin_employee_documents where STR_TO_DATE(date_of_expiry,'%d-%m-%Y') < '" . $curr_date . "' and date_of_expiry != '' ORDER BY `date_of_expiry` asc");
		return $query;
	}
	// user/
	public function get_user_documents_expired_all($employee_id)
	{

		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_documents where employee_id = '" . $employee_id . "' and date_of_expiry < '" . $curr_date . "' ORDER BY `date_of_expiry` asc");
		return $query;
	}
	// get immigration documents
	public function get_img_documents_expired_all()
	{

		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_immigration where STR_TO_DATE(expiry_date,'%d-%m-%Y')  < '" . $curr_date . "' ORDER BY `expiry_date` asc");
		return $query;
	}
	//user // get immigration documents
	public function get_user_img_documents_expired_all($employee_id)
	{

		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_immigration where employee_id = '" . $employee_id . "' and expiry_date < '" . $curr_date . "' ORDER BY `expiry_date` asc");
		return $query;
	}
	public function company_license_expired_all()
	{
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_company_documents where expiry_date < '" . $curr_date . "' ORDER BY `expiry_date` asc");
		return $query;
	}
	public function get_company_license_expired($company_id)
	{

		$curr_date = date('Y-m-d');
		$sql = "SELECT * FROM xin_company_documents WHERE expiry_date < '" . $curr_date . "' and company_id = ?";
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// assets warranty all
	public function warranty_assets_expired_all()
	{
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_assets where warranty_end_date < '" . $curr_date . "' ORDER BY `warranty_end_date` asc");
		return $query;
	}
	// user assets warranty all
	public function user_warranty_assets_expired_all($employee_id)
	{
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_assets where employee_id = '" . $employee_id . "' and warranty_end_date < '" . $curr_date . "' ORDER BY `warranty_end_date` asc");
		return $query;
	}
	// company assets warranty all
	public function company_warranty_assets_expired_all($company_id)
	{
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_assets where company_id = '" . $company_id . "' and warranty_end_date < '" . $curr_date . "' ORDER BY `warranty_end_date` asc");
		return $query;
	}
	// get immigration
	public function set_employee_immigration($id)
	{

		$sql = 'SELECT * FROM xin_employee_immigration WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee qualification
	public function set_employee_qualification($id)
	{

		$sql = 'SELECT * FROM xin_employee_qualification WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee work experience
	public function set_employee_experience($id)
	{

		$sql = 'SELECT * FROM xin_employee_work_experience WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee bank account
	public function set_employee_bank_account($id)
	{

		$sql = 'SELECT * FROM xin_employee_bankaccount WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee bank account
	public function set_employee_security_level($id)
	{

		$sql = 'SELECT * FROM xin_employee_security_level WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee bank account > Last
	public function get_employee_bank_account_last($id)
	{

		$sql = 'SELECT * FROM xin_employee_bankaccount WHERE employee_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee contract
	public function set_employee_contract($id)
	{

		$sql = 'SELECT * FROM xin_employee_contract WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee contract
	public function set_employee_income_tax_born($id)
	{

		$sql = 'SELECT * FROM xin_employee_income_tax_born WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee exempt_indicator
	public function set_employee_exempt_indicator($id)
	{

		$sql = 'SELECT * FROM xin_employee_exempt_indicator WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}


	// get employee office shift
	public function set_employee_shift($id)
	{

		$sql = 'SELECT * FROM xin_employee_shift WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee leave
	public function set_employee_leave($id)
	{

		$sql = 'SELECT * FROM xin_employee_leave WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee location
	public function set_employee_location($id)
	{

		$sql = 'SELECT * FROM xin_employee_location WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get document type by id
	public function read_document_type_information($id)
	{

		$sql = 'SELECT * FROM xin_document_type WHERE document_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// contract type
	public function read_contract_type_information($id)
	{

		$sql = 'SELECT * FROM xin_contract_type WHERE contract_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// contract employee
	public function read_contract_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_contract WHERE contract_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// income_tax_born employee
	public function read_income_tax_born_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_income_tax_born WHERE income_tax_born_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// contract employee
	public function read_exempt_indicator_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_exempt_indicator WHERE exempt_indicator_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// office shift
	public function read_shift_information($id)
	{

		$sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}



	// get all contract types
	public function all_contract_types()
	{
		$query = $this->db->query("SELECT * from xin_contract_type");
		return $query->result();
	}

	// get all contracts
	public function all_contracts()
	{
		$query = $this->db->query("SELECT * from xin_employee_contract");
		return $query->result();
	}

	// get all document types
	public function all_document_types()
	{
		$query = $this->db->query("SELECT * from xin_document_type");
		return $query->result();
	}

	// get all education level
	public function all_education_level()
	{
		$query = $this->db->query("SELECT * from xin_qualification_education_level");
		return $query->result();
	}

	// get education level by id
	public function read_education_information($id)
	{

		$sql = 'SELECT * FROM xin_qualification_education_level WHERE education_level_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get all qualification languages
	public function all_qualification_language()
	{
		$query = $this->db->query("SELECT * from xin_qualification_language");
		return $query->result();
	}

	// get languages by id
	public function read_qualification_language_information($id)
	{

		$sql = 'SELECT * FROM xin_qualification_language WHERE language_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get all qualification skills
	public function all_qualification_skill()
	{
		$query = $this->db->query("SELECT * from xin_qualification_skill");
		return $query->result();
	}

	// get qualification by id
	public function read_qualification_skill_information($id)
	{

		$sql = 'SELECT * FROM xin_qualification_skill WHERE skill_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get contacts by id
	public function read_contact_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get documents by id
	public function read_document_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_documents WHERE document_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_document_information_by_document_id($id, $employee_id)
	{

		$sql = 'SELECT * FROM xin_employee_documents WHERE document_type_id = ? AND employee_id = ? limit 1';
		$binds = array($id, $employee_id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get documents by id
	public function read_imgdocument_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_immigration WHERE immigration_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get qualifications by id
	public function read_qualification_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_qualification WHERE qualification_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get qualifications by id
	public function read_work_experience_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_work_experience WHERE work_experience_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get bank account by id
	public function read_bank_account_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_bankaccount WHERE bankaccount_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get sc level by id
	public function read_security_level_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_security_level WHERE security_level_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_employee_claim_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_claim as ec left join xin_claim_type as xc on ec.claim_type_id = xc.claim_type_id  WHERE ec.claim_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get leave by id
	public function read_leave_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_leave WHERE leave_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get shift by id
	public function read_emp_shift_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_shift WHERE emp_shift_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// Function to Delete selected record from table
	public function delete_contact_record($id)
	{
		$this->db->where('contact_id', $id);
		$this->db->delete('xin_employee_contacts');
	}

	// Function to Delete selected record from table
	public function delete_document_record($id)
	{
		$this->db->where('document_id', $id);
		$this->db->delete('xin_employee_documents');
	}

	// Function to Delete selected record from table
	public function delete_imgdocument_record($id)
	{
		$this->db->where('immigration_id', $id);
		$this->db->delete('xin_employee_immigration');
	}

	// Function to Delete selected record from table
	public function delete_qualification_record($id)
	{
		$this->db->where('qualification_id', $id);
		$this->db->delete('xin_employee_qualification');
	}

	// Function to Delete selected record from table
	public function delete_work_experience_record($id)
	{
		$this->db->where('work_experience_id', $id);
		$this->db->delete('xin_employee_work_experience');
	}

	// Function to Delete selected record from table
	public function delete_bank_account_record($id)
	{
		$this->db->where('bankaccount_id', $id);
		$this->db->delete('xin_employee_bankaccount');
	}
	// Function to Delete selected record from table
	public function delete_security_level_record($id)
	{
		$this->db->where('security_level_id', $id);
		$this->db->delete('xin_employee_security_level');
	}
	public function delete_claim_record($id)
	{
		$this->db->where('claim_id', $id);
		$this->db->delete('xin_employee_claim');
	}

	// Function to Delete selected record from table
	public function delete_contract_record($id)
	{
		$this->db->where('contract_id', $id);
		$this->db->delete('xin_employee_contract');
	}

	// Function to Delete selected record from table
	public function delete_income_tax_born($id)
	{
		$this->db->where('income_tax_born_id', $id);
		$this->db->delete('xin_employee_income_tax_born');
	}


	// Function to Delete selected record from table
	public function delete_exempt_indicator_record($id)
	{
		$this->db->where('exempt_indicator_id', $id);
		$this->db->delete('xin_employee_exempt_indicator');
	}

	// Function to Delete selected record from table
	public function delete_leave_record($id)
	{
		$this->db->where('leave_id', $id);
		$this->db->delete('xin_employee_leave');
	}

	// Function to Delete selected record from table
	public function delete_shift_record($id)
	{
		$this->db->where('emp_shift_id', $id);
		$this->db->delete('xin_employee_shift');
	}

	// Function to Delete selected record from table
	public function delete_location_record($id)
	{
		$this->db->where('office_location_id', $id);
		$this->db->delete('xin_employee_location');
	}

	// get location by id
	public function read_location_information($id)
	{

		$sql = 'SELECT * FROM xin_employee_location WHERE office_location_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function record_count()
	{
		$sql = 'SELECT * FROM xin_employees where user_role_id!=1';
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	public function record_count_myteam($reports_to)
	{
		$sql = 'SELECT * FROM xin_employees where user_role_id!=1 and reports_to = ' . $reports_to . '';
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	// read filter record
	public function get_employee_by_department($cid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE department_id = ?';
		$binds = array($cid);
		$query = $this->db->query($sql, $binds);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// read filter record
	public function record_count_company_employees($cid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ?';
		$binds = array($cid);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// read filter record
	public function record_count_company_location_employees($cid, $lid)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id= ?';
		$binds = array($cid, $lid);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// read filter record
	public function record_count_company_location_department_employees($cid, $lid, $dep_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id= ? and department_id= ?';
		$binds = array($cid, $lid, $dep_id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// read filter record
	public function record_count_company_location_department_designation_employees($cid, $lid, $dep_id, $des_id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id= ? and department_id= ? and designation_id= ?';
		$binds = array($cid, $lid, $dep_id, $des_id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	//reports_to -> my employees
	public function fetch_all_team_employees($limit, $start)
	{
		$session = $this->session->userdata('username');
		$this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		//$this->db->where("user_role_id!=",1);
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		$this->db->where("reports_to", $session['user_id']);
		$this->db->where("user_role_id!=1");
		$query = $this->db->get("xin_employees");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function fetch_all_employees($limit, $start)
	{
		$session = $this->session->userdata('username');
		$this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		//$this->db->where("user_role_id!=",1);
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id != 1) {
			$this->db->where("company_id", $user_info[0]->company_id);
		}
		$this->db->where("user_role_id!=1");
		$query = $this->db->get("xin_employees");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	// get company employees
	public function fetch_all_company_employees_flt($limit, $start, $cid)
	{
		$session = $this->session->userdata('username');
		$this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id", $cid);
		$query = $this->db->get("xin_employees");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	// get company|location employees
	public function fetch_all_company_location_employees_flt($limit, $start, $cid, $lid)
	{
		$session = $this->session->userdata('username');
		$this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id=", $cid);
		$this->db->where("location_id=", $lid);
		$query = $this->db->get("xin_employees");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	// get company|location|department employees
	public function fetch_all_company_location_department_employees_flt($limit, $start, $cid, $lid, $dep_id)
	{
		$session = $this->session->userdata('username');
		$this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id=", $cid);
		$this->db->where("location_id=", $lid);
		$this->db->where("department_id=", $dep_id);
		$query = $this->db->get("xin_employees");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	// get company|location|department|designation employees
	public function fetch_all_company_location_department_designation_employees_flt($limit, $start, $cid, $lid, $dep_id, $des_id)
	{
		$session = $this->session->userdata('username');
		$this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id=", $cid);
		$this->db->where("location_id=", $lid);
		$this->db->where("department_id=", $dep_id);
		$this->db->where("designation_id=", $des_id);
		$query = $this->db->get("xin_employees");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function des_fetch_all_employees($limit, $start)
	{
		// $this->db->limit($limit, $start);

		$sql = 'SELECT * FROM xin_employees order by designation_id asc limit ?, ?';
		$binds = array($limit, $start);
		$query = $this->db->query($sql, $binds);

		//  $query = $this->db->get("xin_employees");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function set_salary($id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->row();
	}
	public function get_salary($id)
	{

		$sql = 'SELECT * FROM xin_employee_salary WHERE user_id = ? ORDER BY salary_id DESC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee allowances
	public function set_employee_allowances($id)
	{

		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee commissions
	public function set_employee_commissions($id)
	{

		// $sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
		// $binds = array($id);
		// $query = $this->db->query($sql, $binds);

		$sql = 'SELECT sc.*,pd.payment_deduction_name FROM xin_salary_commissions sc LEFT JOIN xin_payment_deduction_types pd ON sc.commission_type=pd.id WHERE sc.employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee statutory deductions
	public function set_employee_statutory_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	public function set_employee_deduction($id)
	{

		$sql = 'SELECT * FROM  xin_salary_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee other payments
	public function set_employee_other_payments($id)
	{

		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee overtime
	public function set_employee_overtime($id)
	{

		$sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee allowances
	public function set_employee_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	//-- payslip data
	// get employee allowances
	public function set_employee_allowances_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_allowances WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee commissions
	public function set_employee_commissions_payslip($id)
	{

		// $sql = 'SELECT * FROM xin_salary_payslip_commissions WHERE payslip_id = ?';
		$this->db->select('*');
		$this->db->from('xin_salary_payslip_commissions as sc');
		$this->db->join('xin_payment_deduction_types as dt', 'sc.commission_id = dt.id');
		$this->db->where('sc.payslip_id', $id);
		$data = $this->db->get();
		// $binds = array($id);
		// $query = $this->db->query($sql, $binds);

		return $data;
	}
	// get employee other payments
	public function set_employee_other_payments_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_other_payments WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee statutory_deductions
	public function set_employee_statutory_deductions_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_statutory_deductions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee overtime
	public function set_employee_overtime_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_overtime WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// get employee allowances
	public function set_employee_deductions_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_loan WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	// get employee allowances
	public function get_employee_deductions_payslip($id, $month)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_loan WHERE employee_id = ? AND salary_month = ?';
		$binds = array($id, $month);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	//------
	// get employee allowances
	public function count_employee_allowances_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_allowances WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	public function get_employee_allowances_payslip($id, $month)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_allowances WHERE employee_id = ? AND salary_month=?';
		$binds = array($id, $month);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}
	// get employee commissions
	public function count_employee_commissions_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_commissions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee statutory_deductions
	public function count_employee_statutory_deductions_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_statutory_deductions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee other payments
	public function count_employee_other_payments_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_other_payments WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee overtime
	public function count_employee_overtime_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_overtime WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}

	// get employee allowances
	public function count_employee_deductions_payslip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslip_loan WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	//////////////////////
	// get employee allowances
	public function count_employee_allowances($id)
	{

		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee commissions
	public function count_employee_commissions($id)
	{

		$sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee other payments
	public function count_employee_other_payments($id)
	{

		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee statutory deduction
	public function count_employee_statutory_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	// get employee overtime
	public function count_employee_overtime($id)
	{

		$sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}

	// get employee allowances
	public function count_employee_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}

	// get employee salary allowances
	public function read_salary_allowances($id)
	{

		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary commissions
	public function read_salary_commissions($id)
	{

		$sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary other payments
	public function read_salary_other_payments($id)
	{

		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function get_other_payment($year,$employee_id)
	{

		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ? and YEAR(STR_TO_DATE(date,"%d-%m-%Y")) = ? and fund_title != ? ';
		$binds = array($employee_id,$year,"");
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// get employee statutory deductions
	public function read_salary_statutory_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee overtime
	public function read_salary_overtime($id)
	{

		$sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary loan_deduction
	public function read_salary_loan_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary loan_deduction
	public function read_single_loan_deductions($id)
	{

		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE loan_deduction_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	//Calculates how many months is past between two timestamps.
	public function get_month_diff($start, $end = FALSE)
	{
		$end or $end = time();
		$start = new DateTime($start);
		$end   = new DateTime($end);
		$diff  = $start->diff($end);
		return $diff->format('%y') * 12 + $diff->format('%m');
	}
	// get employee salary allowances
	public function read_single_salary_allowance($id)
	{

		$sql = 'SELECT * FROM xin_salary_allowances WHERE allowance_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee commissions
	public function read_single_salary_commissions($id)
	{

		$sql = 'SELECT * FROM xin_salary_commissions WHERE salary_commissions_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get
	public function read_single_salary_statutory_deduction($id)
	{

		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE statutory_deductions_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function read_single_salary_other_payment($id)
	{

		$sql = 'SELECT * FROM xin_salary_other_payments WHERE other_payments_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee overtime record
	public function read_salary_overtime_record($id)
	{

		$sql = 'SELECT * FROM xin_salary_overtime WHERE salary_overtime_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function read_salary_overtime_rate($id)
	{

		$sql = 'SELECT * FROM xin_employee_overtime_rate WHERE id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// Function to add record in table > allowance
	public function add_salary_allowances($data)
	{
		$this->db->insert('xin_salary_allowances', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function add_salary_deduction($data)
	{
		$this->db->insert('xin_salary_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function set_salary_deduction($id)
	{
		$query = $this->db->where('deduction_id', $id)->get('xin_salary_deductions');
		return $query->result();
	}
	// Function to add record in table > commissions
	public function add_salary_commissions($data)
	{
		$this->db->insert('xin_salary_commissions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > statutory_deductions
	public function add_salary_statutory_deductions($data)
	{
		$this->db->insert('xin_salary_statutory_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > other payments
	public function add_salary_other_payments($data)
	{
		$this->db->insert('xin_salary_other_payments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > loan
	public function add_salary_loan($data)
	{
		$this->db->insert('xin_salary_loan_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > overtime
	public function add_salary_overtime($data)
	{
		$this->db->insert('xin_salary_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_salary($id)
	{
		$this->db->where('user_id', $id);
		$this->db->delete('xin_employee_salary');
	}
	// Function to Delete selected record from table
	public function delete_allowance_record($id)
	{
		$this->db->where('allowance_id', $id);
		$this->db->delete('xin_salary_allowances');
	}

	// Function to Delete selected record from table
	public function delete_commission_record($id)
	{
		$this->db->where('salary_commissions_id', $id);
		$this->db->delete('xin_salary_commissions');
	}
	// Function to Delete selected record from table
	public function delete_statutory_deductions_record($id)
	{
		$this->db->where('statutory_deductions_id', $id);
		$this->db->delete('xin_salary_statutory_deductions');
	}
	// Function to Delete selected record from table
	public function delete_other_payments_record($id)
	{
		$this->db->where('other_payments_id', $id);
		$this->db->delete('xin_salary_other_payments');
	}
	// Function to Delete selected record from table
	public function delete_loan_record($id)
	{
		$this->db->where('loan_deduction_id', $id);
		$this->db->delete('xin_salary_loan_deductions');
	}
	// Function to Delete selected record from table
	public function delete_overtime_record($id)
	{
		$this->db->where('salary_overtime_id', $id);
		$this->db->delete('xin_salary_overtime');
	}

	public function delete_overtime_record_rate($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('xin_employee_overtime_rate');
	}
	// Function to update record in table > update allowance record
	public function salary_allowance_update_record($data, $id)
	{
		$this->db->where('allowance_id', $id);
		if ($this->db->update('xin_salary_allowances', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function updateEmployeeDeduction($data, $id)
	{
		$this->db->where('deduction_id', $id);
		if ($this->db->update('xin_salary_deductions', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table >
	public function salary_commissions_update_record($data, $id)
	{
		$this->db->where('salary_commissions_id', $id);
		if ($this->db->update('xin_salary_commissions', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table >
	public function salary_statutory_deduction_update_record($data, $id)
	{
		$this->db->where('statutory_deductions_id', $id);
		if ($this->db->update('xin_salary_statutory_deductions', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table >
	public function salary_other_payment_update_record($data, $id)
	{
		$this->db->where('other_payments_id', $id);
		if ($this->db->update('xin_salary_other_payments', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function overtime_rate_update_record($data, $id)
	{
		$this->db->where('id', $id);
		if ($this->db->update('xin_employee_overtime_rate', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > update allowance record
	public function salary_loan_update_record($data, $id)
	{
		$this->db->where('loan_deduction_id', $id);
		if ($this->db->update('xin_salary_loan_deductions', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > update allowance record
	public function salary_overtime_update_record($data, $id)
	{
		$this->db->where('salary_overtime_id', $id);
		if ($this->db->update('xin_salary_overtime', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// get single record > company | office shift
	public function ajax_company_officeshift_information($id)
	{

		$sql = 'SELECT * FROM xin_office_shift WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function getPersonIDType()
	{
		$this->db->select('id, id_name, iras_code');
		$this->db->from('xin_person_id_type');
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function getEmployeeLeaves(int $emp_id)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, l.no_of_leaves,l.carried_leave,l.balance_leave, t.type_name');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $emp_id);
		return $this->db->get();
	}

	public function getEmployeeOvertime(int $emp_id)
	{
		$this->db->select('*');
		$this->db->from('xin_employee_overtime_rate');
		$this->db->where('employee_id', $emp_id);
		return $this->db->get();
	}

	public function getEmployeeLeavesForReport(int $emp_id, $year)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, l.no_of_leaves,l.carried_leave,l.balance_leave, t.type_name,l.balance_leave_check');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $emp_id);
		$this->db->where('l.leave_year', $year);
		return $this->db->get();
	}

	public function getEmployeeLeavesForProrated(int $emp_id, $year)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, l.no_of_leaves,l.carried_leave,l.balance_leave, t.type_name');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $emp_id);
		$this->db->where('l.leave_year', $year);
		$this->db->where('l.leave_type_id', 22);
		$result =  $this->db->get();
		return $result->row();
	}


	public function getEmployeeLeavesId(int $emp_id)
	{
		$this->db->select('l.leave_type_id');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $emp_id);
		return $this->db->get();
	}
	public function getTotalEmployeeLeaves(int $emp_id, $date)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, sum(l.no_of_leaves) as no_of_leaves, t.type_name');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $emp_id);
		$this->db->group_start();
		$this->db->where('l.leave_year', date('Y', strtotime($date)));
		$this->db->or_where('l.leave_year', date('Y', strtotime('-1 Years', strtotime($date))));
		$this->db->group_end();
		$this->db->group_by('t.type_name');
		return $this->db->get();
	}

	public function getEmployeeMonthlyLeaves(int $emp_id, $month)
	{
		$this->db->select('count(a.leave_id) as no_of_leaves,t.type_name');
		$this->db->from('xin_leave_applications as a');
		//$this->db->join('xin_employee_year_leave as l','a.employee_id = l.employee_id');
		$this->db->join('xin_leave_type as t', 'a.leave_type_id = t.leave_type_id');
		$this->db->where(' MONTH(STR_TO_DATE(a.from_date,"%d-%m-%Y"))= MONTH("' . $month . '")');
		$this->db->where(' MONTH(STR_TO_DATE(a.to_date,"%d-%m-%Y"))= MONTH("' . $month . '")');
		$this->db->where(' YEAR(STR_TO_DATE(a.from_date,"%d-%m-%Y"))= YEAR("' . $month . '")');
		$this->db->where(' YEAR(STR_TO_DATE(a.to_date,"%d-%m-%Y"))= YEAR("' . $month . '")');
		$this->db->where('a.status !=', 3);
		$this->db->where('a.employee_id', $emp_id);

		$this->db->group_by('a.leave_type_id');

		return $this->db->get();
	}
	public function getEmployeeClaim(int $emp_id)
	{
		$this->db->select('l.claim_id, l.claim_type_id, l.employee_id, l.claim_year,l.date,l.attachment,l.status, t.name,amount');
		$this->db->from('xin_employee_claim as l');
		$this->db->join('xin_claim_type as t', 'l.claim_type_id = t.claim_type_id');
		$this->db->where('l.employee_id', $emp_id);
		return $this->db->get();
	}



	public function getEmployeeLeaveByTypeYear($user_id,  $type,  $year)
	{
		$this->db->select('id, leave_type_id, employee_id, leave_year, no_of_leaves');
		$this->db->from('xin_employee_year_leave');
		$this->db->where('employee_id', $user_id);
		$this->db->where('leave_type_id', $type);
		$this->db->where('leave_year', $year);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function getEmployeeClaimByTypeYear($user_id,  $type,  $year)
	{
		$this->db->select('claim_id, claim_type_id, employee_id, claim_year');
		$this->db->from('xin_employee_claim');
		$this->db->where('employee_id', $user_id);
		$this->db->where('claim_type_id', $type);
		$this->db->where('claim_year', $year);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function setEmployeeLeave(array $data)
	{
		$result = $this->db->insert('xin_employee_year_leave', $data);
		if ($result) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function setEmployeeClaim(array $data)
	{
		$result = $this->db->insert('xin_employee_claim', $data);
		if ($result) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function getEmployeeOvertimeRate(int $id)
	{
		$this->db->select('*');
		$this->db->from('xin_employee_overtime_rate');
		$this->db->where('employee_id', $id);
		// $this->db->where('status', 1);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function getEmployeeOvertimeRateChange(int $id, $ov_type)
	{
		$this->db->select('*');
		$this->db->from('xin_employee_overtime_rate');
		$this->db->where('employee_id', $id);
		$this->db->where('overtime_type', $ov_type);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}



	public function add_employee_overtime_rate($data, $check)
	{
		$employee_id = $data['employee_id'];
		// $this->db->select('id');
		// $this->db->from('xin_employee_overtime_rate');
		// $this->db->where('employee_id', $employee_id);
		// $result = $this->db->get();
		if ($check == 1) {
			$this->db->where('employee_id', $employee_id);
			$update = $this->db->update('xin_employee_overtime_rate', $data);
			if ($update) {
				return true;
			} else {
				return false;
			}
		} else {
			$this->db->insert('xin_employee_overtime_rate', $data);
			if ($this->db->affected_rows() > 0) {
				return $this->db->insert_id();;
			} else {
				return false;
			}
		}
	}

	public function update_status_employee_overtime_rate($id, $employee_id)
	{
		$data = array(
			'status' => 0
		);
		$this->db->where('id !=', $id);
		$this->db->where('employee_id', $employee_id);
		$update = $this->db->update('xin_employee_overtime_rate', $data);
	}

	public function getShareOptionSchemes()
	{
		$this->db->select('id, scheme_shortname, scheme_fullname');
		$this->db->from('xin_share_option_scheme');
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function saveEmployeeShareOptions(array $data)
	{
		$result = $this->db->insert('xin_employee_share_options', $data);
		if ($result) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function getEmployeeShareById($id)
	{
		$query = $this->db->select('so.*,ss.scheme_shortname, ss.scheme_fullname')
			->join('xin_share_option_scheme ss', 'so.id=ss.id')
			->where('so.id', $id)->get('xin_employee_share_options so');
		return $query->result();
	}
	public function getShareOptionsByEmployee(int $id)
	{
		$this->db->select('so.id, so.employee_id, so.so_plan, so.date_of_grant, so.date_of_excercise, so.price_date_of_excercise, so.no_of_shares, c.name as company, s.scheme_shortname');
		$this->db->from('xin_employee_share_options as so');
		$this->db->join('xin_companies as c', 'so.company_id = c.company_id');
		$this->db->join('xin_share_option_scheme as s', 'so.so_scheme = s.id');
		$this->db->where('so.employee_id', $id);
		return $this->db->get();
	}

	public function getEmployeeShareOptions(int $id, string $month_year)
	{
		// $month_year = $month_year . '-01';
		$month_year = '01-' . $month_year;
		$this->db->select('s.id, s.so_scheme, s.so_plan, s.excercise_price, s.price_date_of_grant, s.price_date_of_excercise, s.no_of_shares');
		$this->db->from('xin_employee_share_options as s');
		$this->db->where('s.employee_id', $id);
		$this->db->where('(MONTH(STR_TO_DATE(s.date_of_excercise,"%d-%m-%Y")) = MONTH(STR_TO_DATE(\'' . $month_year . '\',"%d-%m-%Y")) AND YEAR(STR_TO_DATE(s.date_of_excercise,"%d-%m-%Y")) = YEAR(STR_TO_DATE(\'' . $month_year . '\',"%d-%m-%Y")))', NULL, FALSE);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function getEmployeeLeaveById(int $id)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, l.no_of_leaves, t.type_name,l.balance_leave');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.id', $id);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function updateEmployeeLeave(array $data)
	{
		$this->db->where('employee_id', $data['employee_id']);
		$this->db->where('leave_type_id', $data['leave_type_id']);
		$this->db->where('leave_year', $data['leave_year']);
		$this->db->set('no_of_leaves', $data['no_of_leaves']);
		$this->db->set('balance_leave', $data['balance_leave']);
		$this->db->set('balance_leave_check', $data['balance_leave_check']);
		$result = $this->db->update('xin_employee_year_leave');
		return $result;
	}

	public function delete_employee_leave(int $id)
	{
		$this->db->where('id', $id);
		$this->db->delete('xin_employee_year_leave');
	}
	public function delete_deduction_record($id)
	{
		$this->db->where('deduction_id', $id);
		$this->db->delete('xin_salary_deductions');
	}


	/**
	 * Author : Syed Anees
	 */
	public function getEmployeeMonthlyAllowance(int $id, string $month_year)
	{
		// $month_year = $month_year . '-01';
		$month_year = '01-' . $month_year;
		$this->db->select('sa.allowance_id, sa.allowance_title, sa.allowance_amount, sa.salary_month, dt.id, dt.cpf, dt.tax, dt.sdl, dt.shg');
		$this->db->from('xin_salary_allowances as sa');
		$this->db->join('xin_payment_deduction_types as dt', 'sa.payment_type_id = dt.id');
		$this->db->where('sa.employee_id', $id);
		$this->db->where('(sa.salary_month = \'' . $month_year . '\' OR sa.salary_month IS NULL)', NULL, FALSE);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function getEmployeeMonthlyCommission(int $id, string $month_year)
	{
		// $month_year = $month_year . '-01';
		$month_year = '01-' . $month_year;
		$this->db->select('sc.salary_commissions_id, sc.commission_date, sc.commission_type, sc.commission_amount, dt.pd_type, dt.cpf, dt.tax, dt.sdl, dt.shg,dt.payment_deduction_name');
		$this->db->from('xin_salary_commissions as sc');
		$this->db->join('xin_payment_deduction_types as dt', 'sc.commission_type = dt.id');
		$this->db->where('sc.employee_id', $id);
		$this->db->where('(MONTH(STR_TO_DATE(sc.commission_date,"%d-%m-%Y")) = MONTH(STR_TO_DATE(\'' . $month_year . '\',"%d-%m-%Y")) AND YEAR(STR_TO_DATE(sc.commission_date,"%d-%m-%Y")) = YEAR(STR_TO_DATE(\'' . $month_year . '\',"%d-%m-%Y")))', NULL, FALSE);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function getEmployeeNationality(int $id)
	{
		$this->db->select('e.user_id, e.nationality_id, c.country_name, c.iras_nationality_code');
		$this->db->from('xin_employees as e');
		$this->db->join('xin_countries as c', 'e.nationality_id = c.country_id');
		$this->db->where('e.user_id', $id);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}





	public function getEmployeeMonthUnpaidLeaves(int $emp_id, string $pay_date)
	{
		// $pay_date = $pay_date . '-01';
		$pay_date = '01-' . $pay_date;
		$this->db->select('la.leave_id, la.employee_id, la.from_date, la.to_date, la.status, la.is_half_day, t.type_name, t.is_paid');
		$this->db->from('xin_leave_applications as la');
		$this->db->join('xin_leave_type as t', 'la.leave_type_id = t.leave_type_id');
		$this->db->where('la.employee_id', $emp_id);
		$this->db->where('la.status', 2);
		$this->db->where('t.is_paid', 0);
		// $this->db->where('((MONTH(la.from_date) = MONTH(\''. $pay_date .'\') AND YEAR(la.from_date) = YEAR(\''. $pay_date .'\')) OR (MONTH(la.to_date) = MONTH(\''. $pay_date .'\') AND YEAR(la.to_date) = YEAR(\''. $pay_date .'\')))', NULL, FALSE);
		$this->db->where('((MONTH(STR_TO_DATE(la.from_date,"%d-%m-%Y")) = MONTH(STR_TO_DATE(\'' . $pay_date . '\',"%d-%m-%Y")) AND YEAR(STR_TO_DATE(la.from_date,"%d-%m-%Y")) = YEAR(STR_TO_DATE(\'' . $pay_date . '\',"%d-%m-%Y"))) OR (MONTH(STR_TO_DATE(la.to_date,"%d-%m-%Y")) = MONTH(STR_TO_DATE(\'' . $pay_date . '\',"%d-%m-%Y")) AND YEAR(STR_TO_DATE(la.to_date,"%d-%m-%Y")) = YEAR(STR_TO_DATE(\'' . $pay_date . '\',"%d-%m-%Y"))))', NULL, FALSE);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function get_share_data($id)
	{
		$sql = 'SELECT * FROM xin_salary_payslip_share_options WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function get_company_detail($id)
	{
		$sql = 'SELECT * FROM xin_employees e JOIN xin_companies c ON e.company_id=c.company_id WHERE e.user_id=?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	public function getEmployeeLeaveCount(int $leave_type, $year, int $employee_id)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, sum(l.balance_leave) as no_of_leaves, t.type_name,l.carried_leave, l.balance_leave');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $employee_id);
		$this->db->where('l.leave_type_id', $leave_type);
		$this->db->where('l.leave_year', $year);
		$this->db->group_by('t.type_name');
		$result = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}


	public function getEmployeeLeaveCountForLeave(int $leave_type, $year, int $employee_id)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, l.no_of_leaves,l.balance_leave,l.remain_leave, t.type_name,l.carried_leave');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $employee_id);
		$this->db->where('l.leave_type_id', $leave_type);
		$this->db->where('l.leave_year', $year);
		$this->db->group_by('t.type_name');
		$result = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function getLeaveDetailsById($id)
	{
		$this->db->select('*');
		$this->db->where('leave_id', $id);
		$this->db->from('xin_leave_applications');
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}


	public function getEmployeeLeave(int $id, $year)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, sum(l.no_of_leaves) as no_of_leaves, t.type_name');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $id);

		$this->db->where('l.leave_year', $year);

		$this->db->group_by('t.type_name');

		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}
	public function updateEmployeeShare($data, $id)
	{
		$this->db->where('id', $id);
		if ($this->db->update('xin_employee_share_options', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_employee_share_option($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('xin_employee_share_options');
	}
	public function set_employee_contribution($employee_id)
	{
		$query = $this->db->select('ecf.*,cf.contribution')
			->join('xin_contribution_funds cf', 'cf.id=ecf.contribution_id')
			->where('ecf.employee_id', $employee_id)
			->get('xin_employee_contribution_funds ecf');
		return $query->result();
	}
	public function get_employee_allowances($id)
	{

		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}
	public function get_employee_exit($id)
	{
		$query = $this->db->where('employee_id', $id)->get('xin_employee_exit');
		return $query->result();
	}
}
