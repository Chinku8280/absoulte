<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contribution_fund_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getContributionFunds()
    {
        $this->db->select('id, contribution');
        $this->db->from('xin_contribution_funds');
        $this->db->where('id !=', 5);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }
        return false;
    }
    public function getContributionFundsById($id)
    {
        $this->db->select('id, contribution');
        $this->db->from('xin_contribution_funds');
        $this->db->where('id', $id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }
        return false;
    }

    public function getEmployeeSelfHelpContributions($id)
    {
        $this->db->select('id, employee_id, contribution_id');
        $this->db->from('xin_employee_contribution_funds');
        $this->db->where('employee_id', $id);
        $this->db->where('contribution_type', 1);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }

    public function getEmployeeAdditionalSelfHelpContributions($id)
    {
        $this->db->select('id, employee_id, contribution_id');
        $this->db->from('xin_employee_contribution_funds');
        $this->db->where('employee_id', $id);
        $this->db->where('contribution_type', 2);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }

    public function updateEmployeeContribution($data, $id)
    {
        $this->db->trans_begin();
        $data_shg = $data['shgcontribution'];
        $data_ashg = $data['ashgcontribution'];

        $shg_contribution = $this->getEmployeeSelfHelpContributions($id);
        $ashg_contribution = $this->getEmployeeAdditionalSelfHelpContributions($id);

        if($shg_contribution) {
            $shg_id = $shg_contribution->id;
            if($data_shg) {
                $this->db->where('id', $shg_id);
                $this->db->where('employee_id', $id);
                $this->db->set('contribution_id', $data_shg);
                $this->db->update('xin_employee_contribution_funds');
            }else {
                $this->db->where('id', $shg_id);
                $this->db->where('employee_id', $id);
                $this->db->delete('xin_employee_contribution_funds');
            }
        }else {
            if($data_shg) {
                $dCont = [
                    'employee_id' => $id,
                    'contribution_id' => $data_shg,
                    'contribution_type' => 1
                ];
                $this->db->insert('xin_employee_contribution_funds', $dCont);
            }
        }

        if($ashg_contribution) {
            $ashg_id = $ashg_contribution->id;
            if($data_ashg) {
                $this->db->where('id', $ashg_id);
                $this->db->where('employee_id', $id);
                $this->db->set('contribution_id', $data_ashg);
                $this->db->update('xin_employee_contribution_funds');
            }else {
                $this->db->where('id', $ashg_id);
                $this->db->where('employee_id', $id);
                $this->db->delete('xin_employee_contribution_funds');
            }
        }else {
            if($data_ashg) {
                $dCont = [
                    'employee_id' => $id,
                    'contribution_id' => $data_ashg,
                    'contribution_type' => 2
                ];
                $this->db->insert('xin_employee_contribution_funds', $dCont);
            }
        }

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getContributionRate($gross_salary, $contribution_id)
    {
        $contribution_amount = 0;
        
        $this->db->select('id, contribution_amount');
        $this->db->from('xin_contribution_rates');
        $this->db->where('contribution_id', $contribution_id);
        $this->db->where('min_salary <', $gross_salary);
        $this->db->where('max_salary >=', $gross_salary);
        $this->db->order_by('date_effect', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            $cResult = $result->row();
            $contribution_amount = $cResult->contribution_amount;
        }else {
            $this->db->select('id, contribution_amount');
            $this->db->from('xin_contribution_rates');
            $this->db->where('contribution_id', $contribution_id);
            $this->db->where('min_salary', null);
            $this->db->where('max_salary >=', $gross_salary);
            $this->db->order_by('date_effect', 'DESC');
            $this->db->limit(1);
            $mResult = $this->db->get();
            if($mResult->num_rows() > 0) {
                $mCResult = $mResult->row();
                $contribution_amount = $mCResult->contribution_amount;
            }else {
                $this->db->select('id, contribution_amount');
                $this->db->from('xin_contribution_rates');
                $this->db->where('contribution_id', $contribution_id);
                $this->db->where('min_salary <', $gross_salary);
                $this->db->where('max_salary', null);
                $this->db->order_by('date_effect', 'DESC');
                $this->db->limit(1);
                $gResult = $this->db->get();
                if($gResult->num_rows() > 0) {
                    $gCResult = $gResult->row();
                    $contribution_amount = $gCResult->contribution_amount;
                }
            }
        }

        return $contribution_amount;
    }

    public function setContributionPayslip($data)
    {
        $this->db->insert('xin_contribution_payslip', $data);
        return $this->db->insert_id();
    }

    public function getContributionPayslip($payslip_id)
    {
        $this->db->select('cp.id, cp.payslip_id, cp.contribution_id, cf.contribution, cp.contribution_amount');
        $this->db->from('xin_contribution_payslip as cp');
        $this->db->join('xin_contribution_funds as cf', 'cp.contribution_id = cf.id');
        $this->db->where('cp.payslip_id', $payslip_id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }else {
            return false;
        }
    }

    public function getContributionByPayslipId($payslip_id, $contribution_id)
    {
        $this->db->select('cp.id, cp.payslip_id, cp.contribution_id, cp.contribution_amount');
        $this->db->from('xin_contribution_payslip as cp');
        $this->db->where('cp.payslip_id', $payslip_id);
        $this->db->where('cp.contribution_id', $contribution_id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }else {
            return false;
        }
    }

    public function delete_contribution_payslip($id){
		$this->db->where('payslip_id', $id);
		$this->db->delete('xin_contribution_payslip');	
	}
}