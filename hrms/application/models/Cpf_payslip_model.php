<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpf_payslip_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function add_cpf_payslip($cpf_data)
    {
        $this->db->insert('xin_cpf_payslip', $cpf_data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
    }

    public function getCpfByPayslipId(int $payslip_id)
    {
        $this->db->select('id, ow_paid, ow_cpf, ow_cpf_employer, ow_cpf_employee, aw_paid, aw_cpf, aw_cpf_employer, aw_cpf_employee');
        $this->db->from('xin_cpf_payslip');
        $this->db->where('payslip_id', $payslip_id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }

    public function getSalaryPayslipsByMonth($month)
    {
        $this->db->select('*');
        $this->db->from('xin_salary_payslips');
        $this->db->where('salary_month', $month);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }
        return false;
    }

    public function getSalaryPayslipsByMonthAndCompanyWise($month,$company_id)
    {
        $this->db->select('*');
        $this->db->from('xin_salary_payslips');
        $this->db->where('salary_month', $month);
        $this->db->where('company_id', $company_id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }
        return false;
    }

    public function delete_cpf_payslip($id){
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_cpf_payslip');	
	}
}