<?php
defined('BASEPATH') or exit('No direct script access allowed');
class payroll_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// get payroll templates
	public function get_templates()
	{
		return $this->db->get("xin_salary_templates");
	}

	// get payroll templates > for companies
	public function get_comp_template($cid, $id, $month_year = NULL)
	{
		// if ($month_year != null) {
		// 	// $month_year = $month_year . '-01';
		// 	$month_year = '01-' . $month_year;
		// 	$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_role_id != ? AND date_of_joining < DATE_ADD(STR_TO_DATE(?,"%d-%m-%Y"), INTERVAL 1 MONTH)';
		// 	$binds = array($cid, 1, $month_year);
		// } else {
		// 	$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_role_id != ?';
		// 	$binds = array($cid, 1);
		// }

		// new code by debasis
		$date = date('Y-m-d',strtotime('31-'.$month_year));
		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_role_id != ? AND STR_TO_DATE(date_of_joining,"%d-%m-%Y") <= ?';
		$binds = array($cid, 1,$date);
		// new code by debasis
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get payroll templates > employee/company
	public function get_employee_comp_template($cid, $id, $month_year = NULL)
	{
		// if ($month_year != null) {
		// 	// $month_year = $month_year . '-01';
		// 	$month_year = '01-' . $month_year;
		// 	$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_id = ? AND date_of_joining < DATE_ADD(STR_TO_DATE(?,"%d-%m-%Y"), INTERVAL 1 MONTH)';
		// 	$binds = array($cid, $id, $month_year);
		// } else {
		// 	$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_id = ?';
		// 	$binds = array($cid, $id);
		// }

		// new code by debasis
		$date = date('Y-m-d',strtotime('31-'.$month_year));
		$sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_id = ? AND STR_TO_DATE(date_of_joining,"%d-%m-%Y") <= ?';
		$binds = array($cid, $id,$date);
		// end new code by debasis
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get total hours work > hourly template > payroll generate
	public function total_hours_worked($id, $attendance_date)
	{

		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date like ?';
		$binds = array($id, '%' . $attendance_date . '%');
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get total hours work > hourly template > payroll generate
	public function total_hours_worked_payslip($id, $attendance_date)
	{
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date like ?';
		$binds = array($id, '%' . $attendance_date . '%');
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get advance salaries > all employee
	public function get_advance_salaries()
	{
		return $this->db->get("xin_advance_salaries");
	}

	// get advance salaries > single employee
	public function get_advance_salaries_single($id)
	{

		$sql = 'SELECT * FROM xin_advance_salaries WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}


	public function get_salary_payslip_overtime_by($id)
	{
		$sql = 'SELECT * FROM xin_salary_payslip_overtime WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}


	// get advance salaries report
	public function get_advance_salaries_report()
	{
		return $this->db->query("SELECT advance_salary_id,employee_id,company_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 group by employee_id");
	}

	// get advance salaries report >> single employee > current user
	public function advance_salaries_report_single($id)
	{
		$sql = 'SELECT advance_salary_id,employee_id,company_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id = ? group by employee_id';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}


	// get payment history > all payslips
	public function all_payment_history()
	{
		return $this->db->get("xin_make_payment");
	}
	// new payroll > payslip
	public function employees_payment_history()
	{
		return $this->db->order_by('payslip_id', 'desc')->get("xin_salary_payslips");
	}
	// currency_converter
	public function get_currency_converter()
	{
		return $this->db->get("xin_currency_converter");
	}

	// get payslips of single employee
	public function get_payroll_slip($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE employee_id = ? and status = ?';
		$binds = array($id, 2);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function get_company_payslips($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and status = ?';
		$binds = array($id, 2);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// new payroll > payslip
	public function all_employees_payment_history()
	{
		$sql = 'SELECT * FROM xin_salary_payslips';
		$query = $this->db->query($sql);
		return $query;
	}
	// new payroll > payslip
	public function all_employees_payment_history_month($salary_month)
	{
		$sql = 'SELECT * FROM xin_salary_payslips WHERE salary_month = ?';
		$binds = array($salary_month);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get payslip history > company
	public function get_company_payslip_history($company_id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ?';
		$binds = array($company_id, $salary_month);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get payslip history > company
	public function get_company_payslip_history_month($company_id, $salary_month)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and salary_month = ?';
		$binds = array($company_id, $salary_month);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company/location payslips
	public function get_company_location_payslips($company_id, $location_id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and location_id = ?';
		$binds = array($company_id, $location_id, $salary_month);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company/location payslips
	public function get_company_location_payslips_month($company_id, $location_id, $salary_month)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and location_id = ? and salary_month = ?';
		$binds = array($company_id, $location_id, $salary_month);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company/location/departments payslips
	public function get_company_location_department_payslips($company_id, $location_id, $department_id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and location_id = ? and department_id = ?';
		$binds = array($company_id, $location_id, $department_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company/location/departments payslips
	public function get_company_location_department_payslips_month($company_id, $location_id, $department_id, $salary_month)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and location_id = ? and department_id = ? and salary_month = ?';
		$binds = array($company_id, $location_id, $department_id, $salary_month);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company/location/departments payslips
	public function get_company_location_department_designation_payslips($company_id, $location_id, $department_id, $designation_id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ?';
		$binds = array($company_id, $location_id, $department_id, $designation_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	/// pay to all
	// get all employees
	public function get_all_employees($month_year)
	{
		// $month_year = $month_year . '-01';
		$month_year = "01-" . $month_year;
		// $sql = 'SELECT * FROM xin_employees WHERE user_role_id != ? AND date_of_joining < DATE_ADD(?, INTERVAL 1 MONTH)';
		$sql = 'SELECT * FROM xin_employees WHERE user_role_id != ? AND date_of_joining < DATE_ADD(STR_TO_DATE(?,"%d-%m-%Y"), INTERVAL 1 MONTH)';
		$binds = array(1, $month_year);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get payslip bulk > company
	public function get_company_payroll_employees($company_id, $month_year)
	{
		$month_year = $month_year . '-01';
		$sql = 'SELECT * FROM xin_employees WHERE user_role_id != 1 and company_id = ? AND date_of_joining < DATE_ADD(?, INTERVAL 1 MONTH)';
		$binds = array($company_id, $month_year);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get payslip bulk > company|location
	public function get_company_location_payroll_employees($company_id, $location_id, $month_year)
	{
		$month_year = $month_year . '-01';
		$sql = 'SELECT * FROM xin_employees WHERE user_role_id != 1 and company_id = ? and location_id = ? AND date_of_joining < DATE_ADD(?, INTERVAL 1 MONTH)';
		$binds = array($company_id, $location_id, $month_year);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get payslip bulk > company|location|department
	public function get_company_location_dep_payroll_employees($company_id, $location_id, $department_id, $month_year)
	{
		$month_year = $month_year . '-01';
		$sql = 'SELECT * FROM xin_employees WHERE user_role_id != 1 and company_id = ? and location_id = ? and department_id = ? AND date_of_joining < DATE_ADD(?, INTERVAL 1 MONTH)';
		$binds = array($company_id, $location_id, $department_id, $month_year);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get hourly wages
	public function get_hourly_wages()
	{
		return $this->db->get("xin_hourly_templates");
	}

	public function read_template_information($id)
	{

		$sql = 'SELECT * FROM xin_salary_templates WHERE salary_template_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get request date details > advance salary
	public function requested_date_details($id)
	{

		$sql = 'SELECT * FROM `xin_advance_salaries` WHERE employee_id = ? and status = ?';
		$binds = array($id, 1);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	public function read_hourly_wage_information($id)
	{

		$sql = 'SELECT * FROM xin_hourly_templates WHERE hourly_rate_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_currency_converter_information($id)
	{

		$sql = 'SELECT * FROM xin_currency_converter WHERE currency_converter_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get advance salaries report > view all
	public function advance_salaries_report_view($id)
	{
		$sql = 'SELECT advance_salary_id,company_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id= ? group by employee_id';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	public function read_make_payment_information($id)
	{

		$sql = 'SELECT * FROM xin_make_payment WHERE make_payment_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_payslip_information($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// Function to Delete selected record from table
	public function delete_record($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslips');
	}
	// Function to Delete selected record from table
	public function delete_payslip_allowances_items($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_allowances');
	}
	// Function to Delete selected record from table
	public function delete_payslip_commissions_items($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_commissions');
	}
	// Function to Delete selected record from table
	public function delete_payslip_loan_items($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_loan');
	}
	// Function to Delete selected record from table
	public function delete_payslip_other_payment_items($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_other_payments');
	}
	// Function to Delete selected record from table
	public function delete_payslip_overtime_items($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_overtime');
	}
	// Function to Delete selected record from table
	public function delete_payslip_statutory_deductions_items($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_statutory_deductions');
	}

	public function read_advance_salary_info($id)
	{

		$sql = 'SELECT * FROM xin_advance_salaries WHERE advance_salary_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get advance salary by employee id >paid.total
	public function get_paid_salary_by_employee_id($id)
	{

		$sql = 'SELECT advance_salary_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id=? group by employee_id';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	// get advance salary by employee id
	public function advance_salary_by_employee_id($id)
	{

		$sql = 'SELECT * FROM xin_advance_salaries WHERE employee_id = ? and status = ? order by advance_salary_id desc';
		$binds = array($id, 1);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// Function to add record in table
	public function add_template($data)
	{
		$this->db->insert('xin_salary_templates', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > advance salary
	public function add_advance_salary_payroll($data)
	{
		$this->db->insert('xin_advance_salaries', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_hourly_wages($data)
	{
		$this->db->insert('xin_hourly_templates', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_currency_converter($data)
	{
		$this->db->insert('xin_currency_converter', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_monthly_payment_payslip($data)
	{
		$this->db->insert('xin_make_payment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_hourly_payment_payslip($data)
	{
		$this->db->insert('xin_make_payment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_template_record($id)
	{
		$this->db->where('salary_template_id', $id);
		$this->db->delete('xin_salary_templates');
	}

	// Function to Delete selected record from table
	public function delete_hourly_wage_record($id)
	{
		$this->db->where('hourly_rate_id', $id);
		$this->db->delete('xin_hourly_templates');
	}

	// Function to Delete selected record from table
	public function delete_currency_converter_record($id)
	{
		$this->db->where('currency_converter_id', $id);
		$this->db->delete('xin_currency_converter');
	}

	// Function to Delete selected record from table
	public function delete_advance_salary_record($id)
	{
		$this->db->where('advance_salary_id', $id);
		$this->db->delete('xin_advance_salaries');
	}

	// Function to update record in table
	public function update_template_record($data, $id)
	{
		$this->db->where('salary_template_id', $id);
		if ($this->db->update('xin_salary_templates', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// get all hourly templates
	public function all_hourly_templates()
	{
		$query = $this->db->query("SELECT * from xin_hourly_templates");
		return $query->result();
	}

	// get all salary tempaltes > payroll templates
	public function all_salary_templates()
	{
		$query = $this->db->query("SELECT * from xin_salary_templates");
		return $query->result();
	}

	// Function to update record in table
	public function update_hourly_wages_record($data, $id)
	{
		$this->db->where('hourly_rate_id', $id);
		if ($this->db->update('xin_hourly_templates', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_currency_converter_record($data, $id)
	{
		$this->db->where('currency_converter_id', $id);
		if ($this->db->update('xin_currency_converter', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > manage salary
	public function update_salary_template($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > deduction of advance salary
	public function updated_advance_salary_paid_amount($data, $id)
	{
		$this->db->where('employee_id', $id);
		if ($this->db->update('xin_advance_salaries', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > advance salary
	public function updated_advance_salary_payroll($data, $id)
	{
		$this->db->where('advance_salary_id', $id);
		if ($this->db->update('xin_advance_salaries', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > empty grade status
	public function update_empty_salary_template($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > set hourly grade
	public function update_hourlygrade_salary_template($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > set monthly grade
	public function update_monthlygrade_salary_template($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > zero hourly grade
	public function update_hourlygrade_zero($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > zero monthly grade
	public function update_monthlygrade_zero($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_employees', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function read_make_payment_payslip_check($employee_id, $p_date)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE employee_id = ? and salary_month = ?';
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function read_make_payment_payslip_half_month_check($employee_id, $p_date)
	{

		$sql = "SELECT * FROM xin_salary_payslips WHERE is_half_monthly_payroll = '1' and employee_id = ? and salary_month = ?";
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	public function read_make_payment_payslip_half_month_check_last($employee_id, $p_date)
	{

		$sql = "SELECT * FROM xin_salary_payslips WHERE is_half_monthly_payroll = '1' and employee_id = ? and salary_month = ? order by payslip_id desc";
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}
	

	public function check_make_payment_payslip_for_first_payment($employee_id, $p_date)
	{

		$sql = "SELECT * FROM xin_salary_payslips WHERE  employee_id = ? and salary_month = ?";
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	public function check_make_payment_payslip_for_final_payment($employee_id, $p_date)
	{

		$sql = "SELECT * FROM xin_salary_payslips WHERE  employee_id = ? and salary_month = ? and status = 1 and balance_amount = 0 order by payslip_id desc";
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	public function read_make_payment_payslip_half_month_check_first($employee_id, $p_date)
	{

		$sql = "SELECT * FROM xin_salary_payslips WHERE is_half_monthly_payroll = '1' and employee_id = ? and salary_month = ? order by payslip_id asc";
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	public function read_make_payment_payslip($employee_id, $p_date)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE employee_id = ? and salary_month = ?';
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}

	public function check_make_payment_payslip_as_desc($employee_id, $p_date)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE employee_id = ? and salary_month = ? order by  payslip_id desc';
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}


	public function read_count_make_payment_payslip($employee_id, $p_date)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE employee_id = ? and salary_month = ?';
		$binds = array($employee_id, $p_date);
		$query = $this->db->query($sql, $binds);

		return $query->num_rows();
	}

	public function read_sdl_payslip($id)
	{

		$sql = 'SELECT * FROM xin_contribution_payslip WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}
	public function delete_payslip_mapping_salary_backup($id)
	{
		$user = $this->db->where('payslip_id', $id)->get('xin_salary_payslips')->row()->employee_id;
		$this->db->where('user_id', $user);
		$this->db->delete('salary_backup_table');
		$this->db->where('user_id', $user)->update('xin_employees', ['payment_status' => 0]);
	}
	public function add_salary_payslip_update($data, $id)
	{
		$this->db->where('employee_id', $id)->update('xin_salary_payslips', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->where('employee_id', $id)->get('xin_salary_payslips')->row()->payslip_id;
		} else {
			return false;
		}
	}
	public function add_salary_payslip_deduction($data)
	{
		$this->db->insert('xin_salary_deduction', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function add_salary_payslip_get($id,$salary_month = '')
	{

		$data = $this->db->where('employee_id', $id)
		->where('MONTH(STR_TO_DATE(salary_month,"%d-%m-%Y"))',$salary_month)
		->get('xin_salary_payslips')->row();
		if ($this->db->affected_rows() > 0) {
			return $data;
		} else {
			return false;
		}
	}
	// Function to add record in table> salary payslip record
	public function add_salary_payslip($data)
	{
		$this->db->insert('xin_salary_payslips', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	// Function to add record in table> salary payslip record
	public function add_salary_payslip_allowances($data)
	{
		$this->db->insert('xin_salary_payslip_allowances', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table>
	public function add_salary_payslip_commissions($data)
	{
		$this->db->insert('xin_salary_payslip_commissions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table>
	public function add_salary_payslip_other_payments($data)
	{
		$this->db->insert('xin_salary_payslip_other_payments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table>
	public function add_salary_payslip_statutory_deductions($data)
	{
		$this->db->insert('xin_salary_payslip_statutory_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_salary_payslip_claim($data)
	{
		$this->db->insert('xin_salary_payslip_claims', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	// Function to add record in table> salary payslip record
	public function add_salary_payslip_loan($data)
	{
		$this->db->insert('xin_salary_payslip_loan', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table> salary payslip record
	public function add_salary_payslip_overtime($data)
	{
		$this->db->insert('xin_salary_payslip_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function read_salary_payslip_info($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function read_salary_payslip_info_key($id)
	{

		$sql = 'SELECT * FROM xin_salary_payslips WHERE payslip_key = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// Function to update record in table > set hourly grade
	public function update_payroll_status($data, $id)
	{
		$this->db->where('payslip_key', $id);
		if ($this->db->update('xin_salary_payslips', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function add_salary_payslip_share_options(array $data)
	{
		$result = $this->db->insert('xin_salary_payslip_share_options', $data);
		if ($result) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function getShareOptionPayslip(int $id)
	{
		$this->db->select('id, payslip_id, amount');
		$this->db->from('xin_salary_payslip_share_options');
		$this->db->where('payslip_id', $id);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function delete_payslip_share_options($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_share_options');
	}

	public function add_salary_payslip_leave_deduction(array $data)
	{
		$this->db->insert('xin_salary_payslip_leave_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_payslip_leave_deduction($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_salary_payslip_leave_deductions');
	}

	public function getLeaveDeductionPayslip(int $id)
	{
		$this->db->select('id, payslip_id, leave_date, leave_amount, is_half, total_leave_amount');
		$this->db->from('xin_salary_payslip_leave_deductions');
		$this->db->where('payslip_id', $id);
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}
	public function get_employee_payroll($id)
	{

		$sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
		$binds = array($id);


		$query = $this->db->query($sql, $binds);
		return $query->result();
	}
	public function get_payment_details($id)
	{

		$sql = 'SELECT sp.payslip_id,MONTHNAME(CONCAT(sp.salary_month,"-01")) as month,YEAR(CONCAT(sp.salary_month,"-01")) as year,sp.net_salary,e.first_name,e.last_name,e.username FROM xin_salary_payslips sp JOIN xin_employees e ON sp.employee_id=e.user_id WHERE sp.employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	public function get_payroll_details($id)
	{
		$query = $this->db->select('*,MONTHNAME(CONCAT(salary_month,"-01")) as month,(`basic_salary`+`daily_wages`+`total_allowances`+`total_commissions`+`total_other_payments`+`total_overtime`+`gross_salary`+`other_payment`) as earning,(`total_loan`+`statutory_deductions`+`total_statutory_deductions`) as deduction')
			->where('payslip_id', $id)
			->get('xin_salary_payslips');
		//	echo $this->db->last_query();exit;
		return $query->result();
	}
	public function get_deduction_detail($id)
	{
		$query = $this->db->select('sd.*,dt.deduction_type')
			->join('xin_deduction_type dt', 'sd.deduction_type_id=dt.deduction_type_id')
			->where('sd.employee_id', $id)
			->get('xin_salary_deductions sd');
		return $query->result();
	}
	public function add_mapping($data)
	{
		$this->db->insert('xin_make_payment_mapping', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function get_mapping_data($id)
	{
		$query = $this->db->where('payslip_id', $id)
			->get('xin_make_payment_mapping');
		return $query->result();
	}
	public function delete_payslip_mapping($id)
	{
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_make_payment_mapping');
	}
}
