<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Timesheet_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	// get office shifts
	public function get_office_shifts() {
	  return $this->db->get("xin_office_shift");
	}
	
	// get all tasks
	public function get_tasks() {
	  return $this->db->get("xin_tasks");
	}
	
	// get all project tasks
	public function get_project_tasks($id) {
		$sql = 'SELECT * FROM xin_tasks WHERE project_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get all project variations
	public function get_project_variations($id) {
		$sql = 'SELECT * FROM xin_project_variations WHERE project_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
			
	// check if check-in available
	public function attendance_first_in_check($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	
	// get user attendance
	public function attendance_time_check($employee_id) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ?';
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// check if check-in available
	public function attendance_first_in($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ?';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
	
	// check if check-out available
	public function attendance_first_out_check($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? order by time_attendance_id desc limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get leave types
	public function all_leave_types() {
	  $query = $this->db->get("xin_leave_type");
	  return $query->result();
	}
	// get company offshifts
	public function get_company_shifts($company_id) {
	
		$sql = 'SELECT * FROM xin_office_shift WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company holidays
	public function get_company_holidays($company_id) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// filter company holidays
	public function filter_company_holidays($company_id) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// filter company|status holidays
	public function filter_company_publish_holidays($company_id,$is_publish) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE company_id = ? and is_publish = ?';
		$binds = array($company_id,$is_publish);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// filter company|status holidays
	public function filter_notcompany_publish_holidays($is_publish) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE is_publish = ?';
		$binds = array($is_publish);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company leaves
	public function get_company_leaves($company_id) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get multi company leaves
	public function get_multi_company_leaves($company_ids) {
	
		$sql = 'SELECT * FROM xin_leave_applications where company_id IN ?';
		$binds = array($company_ids);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company tasks
	public function get_company_tasks($company_id) {
	
		$sql = 'SELECT * FROM xin_tasks WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get employee tasks
	public function get_employee_tasks($id) {
	
		$sql = "SELECT * FROM `xin_tasks` where assigned_to like '%$id,%' or assigned_to like '%,$id%' or assigned_to = '$id'";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	
		
	// check if check-out available
	public function attendance_first_out($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? order by time_attendance_id desc limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
	
	// get total hours work > attendance
	public function total_hours_worked_attendance($id,$attendance_date) {
		
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? and total_work != ""';
		$binds = array($id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get total rest > attendance
	public function total_rest_attendance($id,$attendance_date) {
		
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? and total_rest != ""';
		$binds = array($id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// check if holiday available
	public function holiday_date_check($attendance_date) {
	
		// $sql = 'SELECT * FROM xin_holidays WHERE (start_date between start_date and end_date) or (start_date = ? or end_date = ?) limit 1';
		$sql = 'SELECT * FROM xin_holidays WHERE (? between start_date and end_date) or (start_date = ? or end_date = ?) limit 1';
		$binds = array($attendance_date,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get all leaves STR_TO_DATE(from_date,"%d-%m-%Y")
	public function get_leaves($from_date = '',$to_date = '') {
	   $this->db->order_by('leave_id','desc');
		if(!empty($from_date)){
			$this->db->where('STR_TO_DATE(from_date,"%d-%m-%Y") >=', $from_date);
		}
		if(!empty($to_date)){
			$this->db->where('STR_TO_DATE(to_date,"%d-%m-%Y") <=', $to_date);
			$this->db->order_by('STR_TO_DATE(from_date,"%d-%m-%Y")','asc');
		}
	  return ($this->db->get("xin_leave_applications"));
	}
	// get company leaves
	public function filter_company_leaves($company_id,$from_date = '',$to_date = '') {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? AND STR_TO_DATE(from_date,"%d-%m-%Y") >= ? AND STR_TO_DATE(to_date,"%d-%m-%Y") <= ? ORDER BY STR_TO_DATE(from_date,"%d-%m-%Y") asc';
		$binds = array($company_id,$from_date,$to_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company|employees leaves
	public function filter_company_employees_leaves($company_id,$employee_id,$from_date = '',$to_date = '') {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and employee_id = ? AND STR_TO_DATE(from_date,"%d-%m-%Y") >= ? AND STR_TO_DATE(to_date,"%d-%m-%Y") <= ? ORDER BY STR_TO_DATE(from_date,"%d-%m-%Y") asc';
		$binds = array($company_id,$employee_id,$from_date,$to_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company|employees leaves
	public function filter_company_employees_status_leaves($company_id,$employee_id,$status,$from_date,$to_date) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and employee_id = ? and status = ? AND STR_TO_DATE(from_date,"%d-%m-%Y") >= ? AND STR_TO_DATE(to_date,"%d-%m-%Y") <= ? ORDER BY STR_TO_DATE(from_date,"%d-%m-%Y") asc';
		$binds = array($company_id,$employee_id,$status,$from_date,$to_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company|employees leaves
	public function filter_company_only_status_leaves($company_id,$status,$from_date,$to_date) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and status = ? AND STR_TO_DATE(from_date,"%d-%m-%Y") >= ? AND STR_TO_DATE(to_date,"%d-%m-%Y") <= ? ORDER BY STR_TO_DATE(from_date,"%d-%m-%Y") asc';
		$binds = array($company_id,$status,$from_date,$to_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get all employee leaves
	public function get_employee_leaves($id) {
		
		$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// check if holiday available
	public function holiday_date($attendance_date) {
	
		// $sql = 'SELECT * FROM xin_holidays WHERE (start_date between start_date and end_date) or (start_date = ? or end_date = ?) limit 1';
		$sql = 'SELECT * FROM xin_holidays WHERE (? between start_date and end_date) or (start_date = ? or end_date = ?) limit 1';
		$binds = array($attendance_date,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
	
	// get all holidays
	public function get_holidays() {
	  return $this->db->get("xin_holidays");
	}
	
	// get all holidays>calendar
	public function get_holidays_calendar() {
	  	
		$sql = 'SELECT * FROM xin_holidays WHERE is_publish = ?';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get all leaves>calendar
	public function get_leaves_request_calendar() {
	  return $query = $this->db->query("SELECT * from xin_leave_applications");
	}
	
	// check if leave available
	public function leave_date_check($emp_id,$attendance_date) {
	
		// $sql = 'SELECT * from xin_leave_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$sql = 'SELECT * from xin_leave_applications where (? between from_date and to_date) and employee_id = ? and status = 2 limit 1';
		$binds = array($attendance_date,$emp_id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// check if leave available
	public function leave_date($emp_id,$attendance_date) {
	
		// $sql = 'SELECT * from xin_leave_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$sql = 'SELECT * from xin_leave_applications where (? between from_date and to_date) and employee_id = ?  limit 1';
		$binds = array($attendance_date,$emp_id);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
	
	// get total number of leave > employee
	public function count_total_leaves($leave_type_id,$employee_id) {
		
		//$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ? and created_at >= DATE_SUB(NOW(),INTERVAL 1 YEAR)';
		$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ?';
		$binds = array($employee_id,$leave_type_id,2);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}
	
	
	// get payroll templates > NOT USED
	public function attendance_employee_with_date($emp_id,$attendance_date) {
		
		$sql = 'SELECT * FROM xin_attendance_time where attendance_date = ? and employee_id = ?';
		$binds = array($attendance_date,$emp_id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
		 
	 // get record of office shift > by id
	 public function read_office_shift_information($id) {
	
		$sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function getEmployeeLeaveUsingLeaveTypeId($id,$user_id,$year) {
	
		$sql = 'SELECT * FROM xin_employee_year_leave WHERE leave_type_id = ? AND employee_id = ? AND leave_year = ?  limit 1';
		$binds = array($id,$user_id,$year);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get record of leave > by id
	 public function read_leave_information($id) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get leave type by id
	public function read_leave_type_information($id) {
	
		$sql = 'SELECT * FROM xin_leave_type WHERE leave_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to add record in table
	public function add_employee_attendance($data){
		$this->db->insert('xin_attendance_time', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_leave_record($data){
		$this->db->insert('xin_leave_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_task_record($data){
		$this->db->insert('xin_tasks', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_project_variations($data){
		$this->db->insert('xin_project_variations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_office_shift_record($data){
		$this->db->insert('xin_office_shift', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_holiday_record($data){
		$this->db->insert('xin_holidays', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// get record of task by id
	 public function read_task_information($id) {
	
		$sql = 'SELECT * FROM xin_tasks WHERE task_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get record of variation by id
	 public function read_variation_information($id) {
	
		$sql = 'SELECT * FROM xin_project_variations WHERE variation_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get record of holiday by id
	 public function read_holiday_information($id) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE holiday_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get record of attendance by id
	 public function read_attendance_information($id) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE time_attendance_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_attendance_record($id){ 
		$this->db->where('time_attendance_id', $id);
		$this->db->delete('xin_attendance_time');
		
	}
	
	// Function to Delete selected record from table
	public function delete_task_record($id){ 
		$this->db->where('task_id', $id);
		$this->db->delete('xin_tasks');
		
	}
	// Function to Delete selected record from table
	public function delete_variation_record($id){ 
		$this->db->where('variation_id', $id);
		$this->db->delete('xin_project_variations');
		
	}
	
	// Function to Delete selected record from table
	public function delete_holiday_record($id){ 
		$this->db->where('holiday_id', $id);
		$this->db->delete('xin_holidays');
		
	}
	
	// Function to Delete selected record from table
	public function delete_shift_record($id){ 
		$this->db->where('office_shift_id', $id);
		$this->db->delete('xin_office_shift');
		
	}
	
	// Function to Delete selected record from table
	public function delete_leave_record($id){ 
		$this->db->where('leave_id', $id);
		$this->db->delete('xin_leave_applications');
		
	}
	
	// Function to update record in table
	public function update_task_record($data, $id){
		$this->db->where('task_id', $id);
		if( $this->db->update('xin_tasks',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table
	public function update_project_variations($data, $id){
		$this->db->where('variation_id', $id);
		if( $this->db->update('xin_project_variations',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_leave_record($data, $id){
		$this->db->where('leave_id', $id);
		if( $this->db->update('xin_leave_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_holiday_record($data, $id){
		$this->db->where('holiday_id', $id);
		if( $this->db->update('xin_holidays',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_attendance_record($data, $id){
		$this->db->where('time_attendance_id', $id);
		if( $this->db->update('xin_attendance_time',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_shift_record($data, $id){
		$this->db->where('office_shift_id', $id);
		if( $this->db->update('xin_office_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}	
	
	// Function to update record in table
	public function update_default_shift_record($data, $id){
		$this->db->where('office_shift_id', $id);
		if( $this->db->update('xin_office_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_default_shift_zero($data){
		$this->db->where("office_shift_id!=''");
		if( $this->db->update('xin_office_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function assign_task_user($data, $id){
		$this->db->where('task_id', $id);
		if( $this->db->update('xin_tasks',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get comments
	public function get_comments($id) {
		
		$sql = 'SELECT * FROM xin_tasks_comments WHERE task_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get comments
	public function get_attachments($id) {
		
		$sql = 'SELECT * FROM xin_tasks_attachment WHERE task_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// Function to add record in table > add comment
	public function add_comment($data){
		$this->db->insert('xin_tasks_comments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_comment_record($id){
		$this->db->where('comment_id', $id);
		$this->db->delete('xin_tasks_comments');
		
	}
	
	// Function to Delete selected record from table
	public function delete_attachment_record($id){
		$this->db->where('task_attachment_id', $id);
		$this->db->delete('xin_tasks_attachment');
		
	}
	
	// Function to add record in table > add attachment
	public function add_new_attachment($data){
		$this->db->insert('xin_tasks_attachment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// check user attendance 
	public function check_user_attendance() {
		$today_date = date('d-m-Y');
		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? order by time_attendance_id desc limit 1';
		$binds = array($session['user_id'],$today_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	public function check_all_user_attendance() {
		$today_date = date('d-m-Y');
		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where  `attendance_date` = ? order by time_attendance_id desc ';
		$binds = array($today_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	// check user attendance 
	public function check_admin_user_attendance($employee_id,$attendance_date) {
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? order by time_attendance_id desc limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// check user attendance 
	public function check_user_attendance_clockout() {
		$today_date = date('d-m-Y');
		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? and clock_out = ? order by time_attendance_id desc limit 1';
		$binds = array($session['user_id'],$today_date,'');
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	public function check_all_attendance_clockout() {
		$today_date = date('d-m-Y');
		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where  `attendance_date` = ? and clock_out = ? order by time_attendance_id desc';
		$binds = array($today_date,'');
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	//  set clock in- attendance > user
	public function add_new_attendance($data){
		$this->db->insert('xin_attendance_time', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// get last user attendance 
	public function get_last_user_attendance() {

		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? order by time_attendance_id desc limit 1';
		$binds = array($session['user_id']);
		$query = $this->db->query($sql, $binds);
	
		return $query->result();
	}
	
	// get last user attendance > check if loged in-
	public function attendance_time_checks($id) {

		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and clock_out = ? order by time_attendance_id desc limit 1';
		$binds = array($id,'');
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	public function attendance_all_time_checks() {

		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where  clock_out = ? order by time_attendance_id desc';
		$binds = array('');
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	// Function to update record in table > update attendace.
	public function update_attendance_clockedout($data,$id){
		//$this->db->where("time_attendance_id!=''");
		$this->db->where('time_attendance_id', $id);
		if( $this->db->update('xin_attendance_time',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// get employees > active
	public function get_xin_employees() {
		
		$sql = 'SELECT * FROM xin_employees WHERE is_active = ? and user_role_id!=1';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}
	public function get_new_xin_employees($employee_id,$project_id) {
		
		// $sql = 'SELECT * FROM xin_employees WHERE is_active = ? and user_role_id!=1';
		// $binds = array(1);
		// $query = $this->db->query($sql, $binds);
		// return $query->result();
		$this->db->select('*');
		$this->db->join('projects_assigned','projects_assigned.projectsassigned_userid = xin_employees.user_id');
		if(!empty($employee_id)){
			$this->db->where('xin_employees.user_id',$employee_id);
		}
		$this->db->where('projects_assigned.projectsassigned_projectid',$project_id);
		$query = $this->db->get('xin_employees');
		return $query->result();
	}
	
	// get all employee leaves>department wise
	public function get_employee_leaves_department_wise($department_id) {
		
		$sql = 'SELECT * FROM xin_leave_applications WHERE department_id = ?';
		$binds = array($department_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get total number of leave > employee
	public function employee_count_total_leaves($leave_type_id,$employee_id) {
		
		//$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ? and created_at >= DATE_SUB(NOW(),INTERVAL 1 YEAR)';
		$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ?';
		$binds = array($employee_id,$leave_type_id,2);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}
	
	// get total number of leave > employee
	public function employee_show_last_leave($employee_id,$leave_id) {
		$sql = "SELECT * FROM xin_leave_applications WHERE leave_id != '".$leave_id."' and employee_id = ? order by leave_id desc limit 1";
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	public function get_leave_types()
	{
		$this->db->select('leave_type_id, type_name');
		$this->db->from('xin_leave_type');
		$this->db->where('leave_type_id !=', 1);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->result();
		}else {
			return false;
		}
	}
	public function update_carried_leave($data,$year,$user_id,$id){
		$this->db->where('employee_id',$user_id);
		$this->db->where('leave_year',$year);
		$this->db->where('leave_type_id',$id);
		$result = $this->db->update('xin_employee_year_leave',$data);


		if($result) {
			return true;
		} else {
			return false;
		}	
	}
	public function getEmployeeLeave(int $id)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, l.no_of_leaves,l.balance_leave_check,l.balance_leave, t.type_name');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $id);
		$this->db->where('l.leave_year', date('Y'));

		// $this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, sum(l.no_of_leaves) as no_of_leaves, t.type_name,l.balance_leave');
		// $this->db->from('xin_employee_year_leave as l');
		// $this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		// $this->db->where('l.employee_id', $id);
		// $this->db->group_start();
		// $this->db->where('l.leave_year', date('Y'));
		// $this->db->or_where('l.leave_year', date('Y',strtotime("-1 Year")));
		// $this->db->group_end();
		// $this->db->group_by('t.type_name');
		
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->result();
		}else {
			return false;
		}
	}

	public function getEmployeeLeaveForUnpaid($user_id){
		$this->db->where('employee_id',$user_id);
		$this->db->where('leave_type_id',26);
		$this->db->where('leave_year', date('Y'));
		$this->db->from('xin_employee_year_leave');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->result();
		}else {
			return false;
		}
	}

	public function employeeYearLeaveCount(int $leave_type, $year, int $employee_id)
	{
		// $this->db->select('leave_id, employee_id, leave_type_id, from_date, to_date, status, is_half_day');
		// $this->db->from('xin_leave_applications');
		// $this->db->where('leave_type_id', $leave_type);
		// $this->db->where('YEAR(from_date)', $year);
		// $this->db->where('YEAR(to_date)', $year);
		// $this->db->where('employee_id', $employee_id);
		// $this->db->where('status', 2);
		// $result = $this->db->get();
		// if($result->num_rows() > 0) {
		// 	return $result->result();
		// }else {
		// 	return false;
		// }

		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? AND year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ? AND employee_id = ? AND status = ?';
		$binds = array($leave_type,$year,$year,$employee_id,2);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}


	}


	public function employeeYearLeaveCountBeforeStatusChange(int $leave_type, $year, int $employee_id,int $id)
	{
		// $this->db->select('leave_id, employee_id, leave_type_id, from_date, to_date, status, is_half_day');
		// $this->db->from('xin_leave_applications');
		// $this->db->where('leave_type_id', $leave_type);
		// $this->db->where('YEAR(from_date)', $year);
		// $this->db->where('YEAR(to_date)', $year);
		// $this->db->where('employee_id', $employee_id);
		// $this->db->where('status', 2);
		// $result = $this->db->get();
		// if($result->num_rows() > 0) {
		// 	return $result->result();
		// }else {
		// 	return false;
		// }

		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? AND year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ? AND employee_id = ? AND leave_id = ?';
		$binds = array($leave_type,$year,$year,$employee_id,$id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}


	}



	public function getEmployeeLeaveCount(int $leave_type, int $employee_id)
	{
		$this->db->select('l.id, l.leave_type_id, l.employee_id, l.leave_year, sum(l.no_of_leaves) as no_of_leaves,l.carried_leave,l.balance_leave,,l.balance_leave_check, t.type_name');
		$this->db->from('xin_employee_year_leave as l');
		$this->db->join('xin_leave_type as t', 'l.leave_type_id = t.leave_type_id');
		$this->db->where('l.employee_id', $employee_id);
		$this->db->where('l.leave_type_id', $leave_type);
		$this->db->group_start();
		$this->db->where('l.leave_year', date('Y'));
		//$this->db->or_where('l.leave_year', date('Y',strtotime("-1 Years")));
		$this->db->group_end();
		$this->db->group_by('t.type_name');
		$result = $this->db->get();
		//echo $this->db->last_query();exit;
		if($result->num_rows() > 0) {
			return $result->row();
		}else {
			return false;
		}
	}

	public function getEmployeeAppliedLeaveCount(int $leave_type, $year, int $employee_id)
	{
		// $this->db->select('leave_id, employee_id, leave_type_id, from_date, to_date, status, is_half_day');
		// $this->db->from('xin_leave_applications');
		// $this->db->where('leave_type_id', $leave_type);
		// $this->db->where('YEAR(from_date)', $year);
		// $this->db->where('YEAR(to_date)', $year);
		
		// $this->db->where('employee_id', $employee_id);
		// $this->db->where('status !=', 3);

		// dhruva code
		// $sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND employee_id = ? AND year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? OR year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? AND year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ? OR year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ? AND  status != ?';
		// $binds = array($leave_type,$employee_id,$year,$year+1,$year,$year+1,3);
		// $query = $this->db->query($sql, $binds);
		// end dhruve code

		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? AND year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ? AND employee_id = ? AND status != ?';
		$binds = array($leave_type,$year,$year,$employee_id,3);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}

		// $result = $this->db->get();
		// if($result->num_rows() > 0) {
		// 	return $result->result();
		// }else {
		// 	return false;
		// }
	}

	public function getEmployeeAppliedLeaveCountForLeaveReport(int $leave_type, $year, int $employee_id)
	{
		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? AND year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ? AND employee_id = ? AND status = ?';
		$binds = array($leave_type,$year,$year,$employee_id,2);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function getEmployeeAppliedLeaveCountForLeaveApplicationReport(int $leave_type, $year)
	{
		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND year(STR_TO_DATE(from_date,"%d-%m-%Y")) = ? AND year(STR_TO_DATE(to_date,"%d-%m-%Y")) = ?  AND status = ?';
		$binds = array($leave_type,$year,$year,2);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}



	public function getEmployeeAppliedLeaveCountForReport(int $leave_type, $month, int $employee_id)
	{
		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_type_id = ? AND MONTH(STR_TO_DATE(from_date,"%d-%m-%Y")) = ?  AND employee_id = ? AND status = ?';
		$binds = array($leave_type,$month,$employee_id,2);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function is_holiday_on_date(int $company_id,$m_date)
	{
		$this->db->select('holiday_id, event_name, start_date, end_date');
		$this->db->from('xin_holidays');
		$this->db->where('company_id', $company_id);
		$this->db->where('STR_TO_DATE(start_date,"%d-%m-%Y") <=', $m_date);
		$this->db->where('STR_TO_DATE(end_date,"%d-%m-%Y") >=', $m_date);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->row();
		}else {
			return false;
		}
	}

	public function get_total_work_for_month($user_id,$attendance_date){
		// $this->db->select('*');
		// $this->db->where('attendance_date',$attendance_date);
		// $this->db->where('total_work !=','');
		// $this->db->from('xin_attendance_time');
		$sql = 'SELECT * FROM xin_attendance_time WHERE attendance_date = ? AND employee_id = ? AND total_work !=" "';
		$binds = array($attendance_date,$user_id);
		$query = $this->db->query($sql, $binds);
		if ($query->num_rows() > 0) {
			// return $query->row();
			return $query->result();
		}
		//  else {
		// 	return '00:00:00';
		// }
	// 	$result = $this->db->get();
	// 	if($result->num_rows() > 0) {
	// 		return $result->row();
	// 	}else {
	// 		return '00:00:00';
	// 	}
	}


}
?>