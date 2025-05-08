<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentDeduction_Model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllRecurringAllowances()
    {
        $this->db->select('pdt.id as allowance_id, pdt.payment_deduction_name as allowance_name, pdt.cpf, pdt.tax, pdt.sdl, pdt.shg');
        $this->db->from('xin_payslip_items as pi');
        $this->db->join('xin_payment_deduction_types as pdt', 'pi.id = payslip_item_id');
        $this->db->where('pi.id', 2);
        $this->db->where('(pdt.pd_type = 1 OR pdt.pd_type = 3)', NULL, FALSE);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }else {
            return false;
        }
    }

    public function getAllAdHocAllowances()
    {
        $this->db->select('pdt.id as allowance_id, pdt.payment_deduction_name as allowance_name, pdt.cpf, pdt.tax, pdt.sdl, pdt.shg');
        $this->db->from('xin_payslip_items as pi');
        $this->db->join('xin_payment_deduction_types as pdt', 'pi.id = payslip_item_id');
        $this->db->where('pi.id', 2);
        $this->db->where('(pdt.pd_type = 2 OR pdt.pd_type = 3)', NULL, FALSE);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }else {
            return false;
        }
    }

    public function getPaymentDeductionById(int $id)
    {
        $this->db->select('pdt.id as allowance_id, pdt.payment_deduction_name as allowance_name, pdt.cpf, pdt.tax, pdt.sdl, pdt.shg');
        $this->db->from('xin_payment_deduction_types as pdt');
        $this->db->where('pdt.id', $id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }else {
            return false;
        }
    }

    public function updateEmployeeAllowance(array $data, int $emp_id)
    {
        $allowance_id = $data['allowance'];
        if($allowance_id != 'other') {
            $allowance_title = $this->getPaymentDeductionById($allowance_id)->allowance_name;
        }else {
            $allowance_id = 2;
            $allowance_title = $data['other_allowance'];
        }

        $a_data = [
            'employee_id' => $emp_id,
            'payment_type_id' => $allowance_id,
            'allowance_title' => $allowance_title,
            'allowance_amount' => $data['allowance_amount']
        ];
        if(isset($data['allowance_month'])) {
            $a_data['salary_month'] = $data['allowance_month'];
        }
        
        $insert = $this->db->insert('xin_salary_allowances', $a_data);
        if($insert) {
            return $this->db->insert_id();
        }else {
            return false;
        }  
    }

    public function getCommissionTypes()
    {
        $this->db->select('pdt.id as commission_id, pdt.payment_deduction_name as commission_name, pdt.cpf, pdt.tax, pdt.sdl, pdt.shg');
        $this->db->from('xin_payslip_items as pi');
        $this->db->join('xin_payment_deduction_types as pdt', 'pi.id = payslip_item_id');
        $this->db->where('pi.id', 5);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->result();
        }else {
            return false;
        }
    }
    public function getDeductionType(){
        $query=$this->db->get('xin_deduction_type');
        return $query->result();
    }
    public function saveAccommodation(array $data)
    {
        $result = $this->db->insert('xin_accommodations', $data);
        if($result) {
            return $this->db->insert_id();
        }else {
            return false;
        }
    }

    public function get_all_accommodations()
    {
        $this->db->select('*');
        $this->db->from('xin_accommodations');
        return $this->db->get();
    }

    public function getAccommodationById(int $id)
    {
        $this->db->select('*');
        $this->db->from('xin_accommodations');
        $this->db->where('id', $id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }else {
            return false;
        }
    }

    public function saveEmployeeAccommodation(array $data)
    {
        $result = $this->db->insert('xin_employee_accommodation', $data);
        if($result) {
            return $this->db->insert_id();
        }else {
            return false;
        }
    }

    public function get_all_employee_accommodations()
    {
        $this->db->select('ec.id, ec.employee_id, ec.accommodation_id, ec.period_from, ec.period_to, ec.rent_paid, ac.title, em.first_name, em.last_name');
        $this->db->from('xin_employee_accommodation as ec');
        $this->db->join('xin_accommodations as ac', 'ec.accommodation_id = ac.id');
        $this->db->join('xin_employees as em', 'ec.employee_id = em.user_id');
        return $this->db->get();
    }

    public function get_employee_accommodations_by_employee_id($id)
    {
        $this->db->select('ec.id, ac.address_line_1, ec.employee_id, ec.accommodation_id, ec.period_from, ec.period_to, ec.rent_paid, ac.title');
        $this->db->from('xin_employee_accommodation as ec');
        $this->db->join('xin_accommodations as ac', 'ec.accommodation_id = ac.id');
        $this->db->where('ec.employee_id',$id);
        $this->db->order_by('ec.id','desc');
        $result = $this->db->get();
        return $result->result();
    }

    public function saveEmployeeUtilities(array $data) 
    {
        $this->db->trans_begin();
        $emp_id = $data['employee_id'];
        $b_year = $data['benefit_year'];
        $utility = $data['utility'];

        foreach($utility as $i => $u) {
            $u_data = [
                'employee_id' => $emp_id,
                'benefit_year' => $b_year,
                'utility' => $u,
                'utility_amount' => $data['utility_amount'][$i]
            ];
            if($data['utility_remark'][$i] != '') {
                $u_data['utility_remark'] = $data['utility_remark'][$i];
            }

            $this->db->insert('xin_employee_utility_benefits', $u_data);
        }

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_all_employee_utilities()
    {
        $this->db->select('ub.id, ub.employee_id, ub.benefit_year, ub.utility, ub.utility_remark, ub.utility_amount, em.first_name, em.last_name');
        $this->db->from('xin_employee_utility_benefits as ub');
        $this->db->join('xin_employees as em', 'ub.employee_id = em.user_id');
        return $this->db->get();
    }

    public function saveEmployeeDriver(array $data)
    {
        $result = $this->db->insert('xin_employee_driver_benefits', $data);
        if($result) {
            return $this->db->insert_id();
        }else {
            return false;
        }
    }

    public function get_all_employee_drivers()
    {
        $this->db->select('db.id, db.employee_id, db.benefit_year, db.driver_wage, em.first_name, em.last_name');
        $this->db->from('xin_employee_driver_benefits as db');
        $this->db->join('xin_employees as em', 'db.employee_id = em.user_id');
        return $this->db->get();
    }

    public function saveEmployeeHousekeeping(array $data)
    {
        $this->db->trans_begin();
        $emp_id = $data['employee_id'];
        $b_year = $data['benefit_year'];
        $housekeeping_service = $data['housekeeping_service'];

        foreach($housekeeping_service as $i => $u) {
            $u_data = [
                'employee_id' => $emp_id,
                'benefit_year' => $b_year,
                'housekeeping_service' => $u,
                'housekeeping_amount' => $data['housekeeping_amount'][$i]
            ];
            if($data['housekeeping_remark'][$i] != '') {
                $u_data['housekeeping_remark'] = $data['housekeeping_remark'][$i];
            }

            $this->db->insert('xin_employee_housekeeping_benefits', $u_data);
        }

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_all_employee_housekeeping()
    {
        $this->db->select('hk.id, hk.employee_id, hk.benefit_year, hk.housekeeping_service, hk.housekeeping_remark, hk.housekeeping_amount, em.first_name, em.last_name');
        $this->db->from('xin_employee_housekeeping_benefits as hk');
        $this->db->join('xin_employees as em', 'hk.employee_id = em.user_id');
        return $this->db->get();
    }

    public function saveEmployeeHotelAccommodation(array $data)
    {
        $result = $this->db->insert('xin_employee_hotel_accommodation_benefits', $data);
        if($result) {
            return $this->db->insert_id();
        }else {
            return false;
        }
    }

    public function get_all_employee_hotelaccommodation()
    {
        $this->db->select('ha.id, ha.employee_id, ha.hotel_name, ha.check_in, ha.check_out, ha.actual_cost, ha.employee_paid, em.first_name, em.last_name');
        $this->db->from('xin_employee_hotel_accommodation_benefits as ha');
        $this->db->join('xin_employees as em', 'ha.employee_id = em.user_id');
        return $this->db->get();
    }

    public function saveEmployeeOtherBenefits(array $data)
    {
        $this->db->trans_begin();
        $emp_id = $data['employee_id'];
        $b_year = $data['benefit_year'];
        $other_benefit = $data['other_benefit'];

        foreach($other_benefit as $i => $u) {
            $u_data = [
                'employee_id'           =>  $emp_id,
                'benefit_year'          =>  $b_year,
                'other_benefit'         =>  $u,
                'other_benefit_cost'    =>  $data['other_benefit_cost'][$i],
                'deductible_from_salary' => $data['deductible_from_salary'][$i],
            ];
            if($data['other_benefit_remark'][$i] != '') {
                $u_data['other_benefit_remark'] = $data['other_benefit_remark'][$i];
            }
           

            $this->db->insert('xin_employee_other_benefits', $u_data);
        }

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_all_employee_other_benefits()
    {
        $this->db->select('ob.id, ob.employee_id, ob.benefit_year, ob.other_benefit, ob.other_benefit_remark, ob.other_benefit_cost, em.first_name, em.last_name');
        $this->db->from('xin_employee_other_benefits as ob');
        $this->db->join('xin_employees as em', 'ob.employee_id = em.user_id');
        return $this->db->get();
    }

    public function get_all_employee_other_benefits_for_payslip($employee_id,$benefit_year)
    {
        $benefit_year = date('Y',strtotime('01-'.$benefit_year));
        $this->db->select('*');
        $this->db->where('employee_id',$employee_id);
        $this->db->where('benefit_year',$benefit_year);
        $this->db->where('deductible_from_salary',1);
        $this->db->from('xin_employee_other_benefits');
    
        return $this->db->get();
    }


    // start here


    public function read_accommodations_information($id){
		$sql = 'SELECT * FROM xin_accommodations WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

    public function updateAccommodation($data,$id)
    {
        $this->db->where('id',$id);
        $result = $this->db->update('xin_accommodations', $data);
        if($result) {
            return true;
		} else {
			return false;
		}		
    }

    public function delete_accommodations_record($id){
        $this->db->where('id',$id);
        $return = $this->db->delete('xin_accommodations');
    }


    public function read_employee_accommodations_information($id){
        $sql = 'SELECT * FROM xin_employee_accommodation WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    }

    public function updateEmployeeAccommodation($data,$id){
        $this->db->where('id',$id);
        $result = $this->db->update('xin_employee_accommodation', $data);
        if($result) {
            return true;
		} else {
			return false;
		}		
    }

    public function delete_employee_accommodation_record($id){
        $this->db->where('id',$id);
        $this->db->delete('xin_employee_accommodation');
    }

    public function read_utility_information($id){
        $sql = 'SELECT * FROM xin_employee_utility_benefits WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    }

    public function updateEmployeeUtilities($data,$id){
        $this->db->where('id',$id);
        $result = $this->db->update('xin_employee_utility_benefits', $data);
        if($result) {
            return true;
		} else {
			return false;
		}	
    }
    public function delete_utility_record($id){
        $this->db->where('id',$id);
        $this->db->delete('xin_employee_utility_benefits');
    }

    public function read_driver_information($id){
        $sql = 'SELECT * FROM xin_employee_driver_benefits WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    }

    public function updateEmployeeDriver($data,$id){
        $this->db->where('id',$id);
        $result = $this->db->update('xin_employee_driver_benefits', $data);
        if($result) {
            return true;
		} else {
			return false;
		}	
    }

    public function delete_driver_record($id){
        $this->db->where('id',$id);
        $this->db->delete('xin_employee_driver_benefits');
    }

    public function read_housekeeping_information($id){
        $sql = 'SELECT * FROM xin_employee_housekeeping_benefits WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    }

    public function updateEmployeeHousekeeping($data,$id){
        $this->db->where('id',$id);
        $result = $this->db->update('xin_employee_housekeeping_benefits', $data);
        if($result) {
            return true;
		} else {
			return false;
		}	
    }

    public function delete_housekeeping_record($id){
        $this->db->where('id',$id);
        $this->db->delete('xin_employee_housekeeping_benefits');
    }

    public function read_hotel_accommodation_information($id){
        $sql = 'SELECT * FROM xin_employee_hotel_accommodation_benefits WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    }

    public function updateEmployeeHotelAccommodation($data, $id){
        $this->db->where('id',$id);
        $result = $this->db->update('xin_employee_hotel_accommodation_benefits', $data);
        if($result) {
            return true;
		} else {
			return false;
		}	
    }

    public function delete_hotel_accommodation_record($id){
        $this->db->where('id',$id);
        $this->db->delete('xin_employee_hotel_accommodation_benefits');
    }

    public function read_other_benefits_information($id){
        $sql = 'SELECT * FROM xin_employee_other_benefits WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    } 

    public function updateEmployeeOtherBenefits($data,$id){
        $this->db->where('id',$id);
        $result = $this->db->update('xin_employee_other_benefits', $data);
        if($result) {
            return true;
		} else {
			return false;
		}	
    }

    public function delete_other_benefits_record($id){
        $this->db->where('id',$id);
        $this->db->delete('xin_employee_other_benefits');
    }


    public function get_commissions_title($id){
        $sql = 'SELECT * FROM xin_payment_deduction_types WHERE id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
    }


}