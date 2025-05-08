<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpf_percentage_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_cpf_contribution_by_age($age, $immigration_status, $age_from = null, $age_to = null, $pr_year = null)
    {
        $this->db->select('id, employee_age_from, employee_age_to, contribution_employee, contribution_employer, total_cpf, status');
        $this->db->from('xin_cpf_percentage');
        if($age_from == null) {
            $this->db->where('employee_age_from', null);
            $this->db->where('employee_age_to >=', $age);
        }elseif($age_to == null) {
            $this->db->where('employee_age_from <=', $age);
            $this->db->where('employee_age_to', null);
        }elseif($age_from != null && $age_to != null) {
            $this->db->where('employee_age_from <=', $age);
            $this->db->where('employee_age_to >=', $age);
        }
        $this->db->where('immigration_status', $immigration_status);
        if($pr_year != null) {
            $this->db->where('immigration_status_year', $pr_year);
        }
        $this->db->where('status', 1);
        $this->db->order_by('effective_from', 'DESC');
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }
}