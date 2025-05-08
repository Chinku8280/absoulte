<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Overtime_request_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
 
 	// Function to add record in table
	public function add_employee_overtime_request($data){
		$this->db->insert('xin_attendance_time_request', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to update record in table
	public function update_request_record($data, $id){
		$this->db->where('time_request_id', $id);
		if( $this->db->update('xin_attendance_time_request',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get record of attendance by id
	 public function read_overtime_request_info($id) {
	
		$sql = 'SELECT * FROM xin_employee_overtime WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_overtime_request_record($id){ 
		$this->db->where('id', $id);
		$this->db->delete('xin_employee_overtime');
		
	}
	
	// get overtime request
	public function employee_overtime_requests($emp_id) {
		
		$sql = 'SELECT * FROM xin_employee_overtime where employee_id = ?';
		$binds = array($emp_id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get overtime request>admin>all
	public function all_employee_overtime_requests() {
		
		$sql = 'SELECT * FROM xin_employee_overtime';
		$query = $this->db->query($sql);
		
		return $query;
	}
	// get overtime request>admin>all
	public function get_overtime_request_count($employee_id,$pay_date) {
		
		$sql = 'SELECT * FROM `xin_attendance_time_request` where employee_id = ? and is_approved = ? and request_date_request = ?';
		$binds = array($employee_id,2,$pay_date);
		$query = $this->db->query($sql, $binds);
		$result = $query->result();
		return $result;
	}

	public function add_employee_overtime($data)
	{
		$this->db->insert('xin_employee_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function updateEmployeeOvertime($data, $id)
	{
		$this->db->where('id', $id);
		if( $this->db->update('xin_employee_overtime',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	public function getEmployeeMonthOvertime(int $employee_id, string $pay_date)
	{
		// $pay_date = $pay_date . '-01';STR_TO_DATE(commission_date,"%d-%m-%Y")
		$pay_date = '01-'.$pay_date;
		$this->db->select('id, employee_id, overtime_date, in_time, out_time, total_hours, status');
		$this->db->from('xin_employee_overtime');
		$this->db->where('employee_id', $employee_id);
		$this->db->where('status', 2);
		// $this->db->where('(MONTH(overtime_date) = MONTH(\''. $pay_date .'\') AND YEAR(overtime_date) = YEAR(\''. $pay_date .'\'))', NULL, FALSE);
		$this->db->where('(MONTH(STR_TO_DATE(overtime_date,"%d-%m-%Y")) = MONTH(STR_TO_DATE(\''. $pay_date .'\',"%d-%m-%Y")) AND YEAR(STR_TO_DATE(overtime_date,"%d-%m-%Y")) = YEAR(STR_TO_DATE(\''. $pay_date .'\',"%d-%m-%Y")))', NULL, FALSE);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->result();
		}else {
			return false;
		}
	}
}
?>