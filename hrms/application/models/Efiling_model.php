<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Efiling_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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

    public function getEFilingDetails()
    {
        $this->db->select('id, csn, organisation_id_type, organisation_id_no, authorised_name, authorised_designation, authorised_email, authorised_phone, authorised_id_type, authorised_id_no');
        $this->db->from('xin_efiling_details');
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEFilingCompanyDetails($id)
    {
        $this->db->select('id, csn, organisation_id_type, organisation_id_no, authorised_name, authorised_designation, authorised_email, authorised_phone, authorised_id_type, authorised_id_no');
        $this->db->from('xin_efiling_details');
        $this->db->where('company_id', $id);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }


    public function updateEFilingDetails(array $data)
    {
        $this->db->trans_begin();
        // $eDetails = $this->getEFilingDetails();
        $eDetails = $this->getEFilingCompanyDetails($data['company_id']);
        if ($eDetails) {
            if ($eDetails->csn != $data['csn']) {
                $this->db->set('csn', $data['csn']);
            }
            if ($eDetails->organisation_id_type != $data['idtype']) {
                $this->db->set('organisation_id_type', $data['idtype']);
            }
            if ($eDetails->organisation_id_no != $data['idno']) {
                $this->db->set('organisation_id_no', $data['idno']);
            }
            if ($eDetails->authorised_name != $data['authorisedname']) {
                $this->db->set('authorised_name', $data['authorisedname']);
            }
            if ($eDetails->authorised_designation != $data['authoriseddesignation']) {
                $this->db->set('authorised_designation', $data['authoriseddesignation']);
            }
            if ($eDetails->authorised_email != $data['authorisedemail']) {
                $this->db->set('authorised_email', $data['authorisedemail']);
            }
            if ($eDetails->authorised_phone != $data['authorisedphone']) {
                $this->db->set('authorised_phone', $data['authorisedphone']);
            }
            if ($eDetails->authorised_id_type != $data['aurthorisedidtype']) {
                $this->db->set('authorised_id_type', $data['aurthorisedidtype']);
            }
            if ($eDetails->authorised_id_no != $data['authorisedidno']) {
                $this->db->set('authorised_id_no', $data['authorisedidno']);
            }
            $this->db->set('company_id', $data['company_id']);
            $this->db->where('id', $eDetails->id);
            $this->db->update('xin_efiling_details');
        } else {
            $eData = array(
                'company_id' => $data['company_id'],
                'csn' => $data['csn'],
                'organisation_id_type' => $data['idtype'],
                'organisation_id_no' => $data['idno'],
                'authorised_name' => $data['authorisedname'],
                'authorised_designation' => $data['authoriseddesignation']
            );
            if (isset($data['authorisedemail']) && !empty($data['authorisedemail'])) {
                $eData['authorised_email'] = $data['authorisedemail'];
            }
            if (isset($data['authorisedphone']) && !empty($data['authorisedphone'])) {
                $eData['authorised_phone'] = $data['authorisedphone'];
            }
            if (isset($data['aurthorisedidtype']) && !empty($data['aurthorisedidtype'])) {
                $eData['authorised_id_type'] = $data['aurthorisedidtype'];
            }
            if (isset($data['authorisedidno']) && !empty($data['authorisedidno'])) {
                $eData['authorised_id_no'] = $data['authorisedidno'];
            }
            $this->db->insert('xin_efiling_details', $eData);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getCpfSubmissionByMonth(string $month)
    {
        $this->db->select('id, csn, month_year, total_contribution_amount, cpf_file');
        $this->db->from('xin_cpf_submission');
        $this->db->where('month_year', $month);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getCpfSubmissionByMonthAndCompanyId(string $month, $company_id)
    {
        $this->db->select('id, csn, month_year, total_contribution_amount, cpf_file');
        $this->db->from('xin_cpf_submission');
        $this->db->where('month_year', $month);
        $this->db->where('company_id', $company_id);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function saveCpfSubmission(array $data)
    {
        $this->db->trans_begin();
        // $cpf_exists = $this->getCpfSubmissionByMonth($data['month_year']);
        $cpf_exists = $this->getCpfSubmissionByMonthAndCompanyId($data['month_year'], $data['company_id']);
        if ($cpf_exists) {
            $this->db->where('id', $cpf_exists->id);
            $this->db->update('xin_cpf_submission', $data);
        } else {
            $this->db->insert('xin_cpf_submission', $data);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getCpfSubmissionData()
    {
        $this->db->select('*');
        $this->db->from('xin_cpf_submission');
        $result = $this->db->get();
        return $result;
    }

    public function get_all_employee_payslip_summary($year)
    {
        $year = $year - 1;
        $this->db->select('sp.employee_id, e.first_name, e.last_name, e.nationality_id, c.country_name, sum(sp.gross_salary) as tgross_salary, sum(sp.net_salary) as tnet_salary, sum(cp.ow_cpf_employee) as tow_cpf_employee, sum(cp.ow_cpf_employer) as tow_cpf_employer, sum(cp.aw_cpf_employee) as taw_cpf_employee, sum(cp.aw_cpf_employer) as taw_cpf_employer');
        $this->db->from('xin_salary_payslips as sp');
        $this->db->join('xin_employees as e', 'sp.employee_id = e.user_id');
        $this->db->join('xin_countries as c', 'c.country_id = e.nationality_id');
        $this->db->join('xin_cpf_payslip as cp', 'sp.payslip_id = cp.payslip_id');
        $this->db->where("sp.salary_month LIKE '$year%'", NULL, FALSE);
        $this->db->group_by('sp.employee_id');
        return $this->db->get();
    }

    public function isEfilingGeneratedForYear($year)
    {
        $year = $year - 1;
        $this->db->select('id');
        $this->db->from('xin_efiling_ir8a');
        $this->db->where('ir8a_year', $year);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getEmployeesAnnualPay($year,$company_id = '')
    {
        $this->db->select('sp.employee_id, e.first_name, e.last_name, sum(sp.basic_salary) as basic_pay, sum(sp.total_overtime) as overtime_pay, sum(cp.ow_cpf_employee) as tow_cpf_employee, 
        sum(cp.aw_cpf_employee) as taw_cpf_employee, sum(ta.allowance_amount) as transport_allowance,sum(ea.allowance_amount) as entertainment_allowance, sum(oa.allowance_amount) as other_allowance, sum(sc.commission_amount) as commission, SUM(con.contribution_amount) AS contribution, SUM(cmb.contribution_amount) AS mbmf_contribution');
        $this->db->from('xin_salary_payslips as sp');
        $this->db->join('xin_employees as e', 'sp.employee_id = e.user_id');
        $this->db->join('xin_countries as c', 'c.country_id = e.nationality_id');
        $this->db->join('xin_cpf_payslip as cp', 'sp.payslip_id = cp.payslip_id');
        $this->db->join('xin_salary_payslip_allowances as ta', "ta.payslip_id = sp.payslip_id AND ta.allowance_title = 'Transport Allowance'", 'left');
        $this->db->join('xin_salary_payslip_allowances as ea', "ea.payslip_id = sp.payslip_id AND ea.allowance_title = 'Entertainment Allowance'", 'left');
        $this->db->join('xin_salary_payslip_allowances as oa', "oa.payslip_id = sp.payslip_id AND oa.allowance_title != 'Transport Allowance'  AND oa.allowance_title != 'Entertainment Allowance'", 'left');
        $this->db->join('xin_salary_payslip_commissions as sc', 'sc.payslip_id = sp.payslip_id', 'left');
        $this->db->join('(SELECT payslip_id, SUM(contribution_amount) as contribution_amount FROM xin_contribution_payslip WHERE contribution_id != 5 AND contribution_id != 1 group by payslip_id)  as con', 'con.payslip_id = sp.payslip_id', 'left', NULL);
        $this->db->join('(SELECT payslip_id, SUM(contribution_amount) as contribution_amount FROM xin_contribution_payslip WHERE contribution_id = 1 group by payslip_id) as cmb', 'cmb.payslip_id = sp.payslip_id', 'left', NULL);
        $this->db->where("RIGHT(sp.salary_month,4) LIKE '$year%'", NULL, FALSE);
        $this->db->where("sp.company_id",$company_id);
        $this->db->group_by('sp.employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getEmployeesAnnualPayForAp8a()
    {
        $this->db->select('user_id as employee_id');
        $this->db->from('xin_employees');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getEmployeeAccommodation(int $emp_id, $year)
    {
        $this->db->select('ea.accommodation_id, ea.employee_id, ea.period_from, ea.period_to, ea.rent_paid, a.address_line_1, a.address_line_2, a.accommodation_type, a.annual_value, a.furnished_type, a.rent_value');
        $this->db->from('xin_employee_accommodation as ea');
        $this->db->join('xin_accommodations as a', 'ea.accommodation_id = a.id');
        $this->db->where('ea.employee_id', $emp_id);
        // $this->db->where("YEAR(ea.period_from) = $year", NULL, FALSE);
        $this->db->where("YEAR(STR_TO_DATE(ea.period_from,'%d-%m-%Y')) = $year", NULL, FALSE);
        $this->db->order_by('ea.id', 'desc');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeOtherPayment(int $emp_id, $year,$name)
    {
        $this->db->select('*');
        $this->db->from('xin_salary_payslip_other_payments');
        $this->db->where('employee_id', $emp_id);
        // $this->db->where("YEAR(ea.period_from) = $year", NULL, FALSE);
        // $this->db->where("YEAR(STR_TO_DATE(ea.salary_month,'%d-%m-%Y')) = $year", NULL, FALSE);
        $this->db->where("RIGHT(salary_month,4) LIKE '$year%'", NULL, FALSE);
        $this->db->where('payments_title', $name);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeOtherPaymentDate(int $emp_id, $year,$name)
    {
        $this->db->select('*');
        $this->db->from('xin_salary_other_payments');
        $this->db->where('employee_id', $emp_id);
        // $this->db->where("YEAR(ea.period_from) = $year", NULL, FALSE);
        $this->db->where("YEAR(STR_TO_DATE(date,'%d-%m-%Y')) = $year", NULL, FALSE);
        // $this->db->where("RIGHT(salary_month,4) LIKE '$year%'", NULL, FALSE);
        $this->db->where('payments_title', $name);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }



    public function getSharedAccommodationCount($date_from, $date_to, $ac_id)
    {
        $this->db->select('id');
        $this->db->from('xin_employee_accommodation');
        $this->db->where('accommodation_id', $ac_id);
        $this->db->where("period_from >= $date_from or period_to <= $date_to", NULL, FALSE);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->num_rows();
        } else {
            return false;
        }
    }

    public function getEmployeeUtilityBenefit(int $emp_id, $year)
    {
        $this->db->select('employee_id, sum(utility_amount) as utility_amount');
        $this->db->from('xin_employee_utility_benefits');
        $this->db->where('employee_id', $emp_id);
        $this->db->where('benefit_year', $year);
        $this->db->group_by('employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeDriverBenefit(int $emp_id, $year)
    {
        $this->db->select('employee_id, sum(driver_wage) as driver_wage');
        $this->db->from('xin_employee_driver_benefits');
        $this->db->where('employee_id', $emp_id);
        $this->db->where('benefit_year', $year);
        $this->db->group_by('employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeHousekeepingBenefit(int $emp_id, $year)
    {
        $this->db->select('employee_id, sum(housekeeping_amount) as housekeeping_amount');
        $this->db->from('xin_employee_housekeeping_benefits');
        $this->db->where('employee_id', $emp_id);
        $this->db->where('benefit_year', $year);
        $this->db->group_by('employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeHotelAccommodationBenefit(int $emp_id, $year)
    {
        $this->db->select('employee_id, check_in, check_out, actual_cost, employee_paid');
        $this->db->from('xin_employee_hotel_accommodation_benefits');
        $this->db->where('employee_id', $emp_id);
        // $this->db->where("YEAR(check_in) = $year", NULL, FALSE);
        $this->db->where("YEAR(STR_TO_DATE(check_in,'%d-%m-%Y')) = $year", NULL, FALSE);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getEmployeeOtherBenefit(int $emp_id, $year)
    {
        $this->db->select('employee_id, sum(other_benefit_cost) as other_benefit_cost');
        $this->db->from('xin_employee_other_benefits');
        $this->db->where('employee_id', $emp_id);
        $this->db->where('benefit_year', $year);
        $this->db->group_by('employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeOtherBenefitDownload(int $emp_id, $year)
    {
        $this->db->select('employee_id, other_benefit_cost, other_benefit');
        $this->db->from('xin_employee_other_benefits');
        $this->db->where('employee_id', $emp_id);
        $this->db->where('benefit_year', $year);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }



    public function setIr8aEmployee(array $data)
    {
        $result = $this->db->insert('xin_efiling_ir8a', $data);

        if ($result) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function getIR8AEmployees($year)
    {
        $this->db->select('fa.employee_id, e.user_id, e.first_name, e.last_name, fa.id, fa.ir8a_key, fa.ir8a_year, fa.gross_salary, 
        fa.bonus, fa.director_fee, fa.director_fee, fa.total_d1_to_d9, fa.cpf_employee_deduction');
        $this->db->from('xin_efiling_ir8a as fa');
        $this->db->join('xin_employees as e', 'e.user_id = fa.employee_id');
        $this->db->where('fa.ir8a_year', $year);
        return $this->db->get();
    }

    public function getIR8AEmployeesID($year,$employee_id)
    {
        $this->db->select('*');
        $this->db->from('xin_efiling_ir8a');
        $this->db->where('ir8a_year', $year);
        $this->db->where('employee_id', $employee_id);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }


    public function getIR8AByKey($key)
    {
        $this->db->select('*');
        $this->db->from('xin_efiling_ir8a');
        $this->db->where('ir8a_key', $key);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function saveIr8aRecords(array $data)
    {
        $this->db->insert('xin_ir8a_submission', $data);
        return $this->db->insert_id();
    }

    public function getIr8aRecordByYear($year)
    {
        $this->db->select('id, basis_year, no_of_records, ir8a_file, status');
        $this->db->from('xin_ir8a_submission');
        $this->db->where('basis_year', $year);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function resetAllIr8aRecordsByYear($year)
    {
        $this->db->trans_begin();
        //ir8a submission delete xml
        $ir8a_record = $this->getIr8aRecordByYear($year);
        if ($ir8a_record) {
            $xml_file = $ir8a_record->ir8a_file;
            $file_path = FCPATH . str_replace('./', '', $xml_file);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        //delete records
        $this->db->where('basis_year', $year);
        $this->db->delete('xin_ir8a_submission');

        $this->db->where('ir8a_year', $year);
        $this->db->delete('xin_efiling_ir8a');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_all_employee_payslip_summary_8a($year)
    {
        $e_summary = $this->get_all_employee_payslip_summary($year);
        if ($e_summary->num_rows() > 0) {
            foreach ($e_summary->result() as $s) {
                $s->ir8a_d9 = 'D9';
                $s->ap8a_eligible = 'Yes';
                $emp_id = $s->employee_id;
                $this->db->select('id, ir8a_year, benefits_in_kind_ap8a');
                $this->db->from('xin_efiling_ir8a');
                $this->db->where('ir8a_year', $year - 1);
                $this->db->where('employee_id', $emp_id);
                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                    $ap8a_result = $result->row();
                    if ($ap8a_result->benefits_in_kind_ap8a) {
                        $s->ir8a_d9 = $ap8a_result->benefits_in_kind_ap8a;
                        $s->ap8a_eligible = 'Yes';
                    } else {
                        $s->ir8a_d9 = '';
                        $s->ap8a_eligible = 'No';
                    }
                } else {
                    $s->ir8a_d9 = '';
                    $s->ap8a_eligible = 'No';
                }
            }
        }
        return $e_summary;
    }

    public function setAp8aEmployee(array $data)
    {
        $result = $this->db->insert('xin_efiling_appendix8a', $data);
        if ($result) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function getAp8ARecordByYear($year)
    {
        $this->db->select('id, basis_year, no_of_records, ap8a_file, status');
        $this->db->from('xin_appendix8a_submission');
        $this->db->where('basis_year', $year);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeeBenefitByName(int $emp_id, $year, $benefit_name)
    {
        $this->db->select('employee_id, sum(other_benefit_cost) as benefit_cost');
        $this->db->from('xin_employee_other_benefits');
        $this->db->where('employee_id', $emp_id);
        $this->db->where('benefit_year', $year);
        $this->db->where('other_benefit', $benefit_name);
        $this->db->group_by('employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function saveAp8aRecords(array $data)
    {
        $this->db->insert('xin_appendix8a_submission', $data);
        return $this->db->insert_id();
    }

    public function getAp8AEmployees($year)
    {
        $this->db->select('fa.employee_id, e.user_id, e.first_name, e.last_name, fa.id, fa.ap8a_key, fa.ap8a_year, fa.accommodation, 
        fa.utilities_housekeeping, fa.hotel_accommodation, fa.other_benefits');
        $this->db->from('xin_efiling_appendix8a as fa');
        $this->db->join('xin_employees as e', 'e.user_id = fa.employee_id');
        $this->db->where('fa.ap8a_year', $year);
        return $this->db->get();
    }

    public function getAppendix8AEmployees($id)
    {
        $this->db->select('*');
        $this->db->from('xin_efiling_appendix8a');
        $this->db->where('id', $id);
        $result =  $this->db->get();
        return $result->result();
    }

    public function getAppendix8BEmployees($id)
    {
        $this->db->select('*');
        $this->db->from('xin_efiling_appendix8b');
        $this->db->where('ap8b_key', $id);
        $result =  $this->db->get();
        return $result->result();
    }


    public function resetAllAp8aRecordsByYear($year)
    {
        //reset ir8a
        $this->resetAllIr8aRecordsByYear($year);

        $this->db->trans_begin();
        //ap8a submission delete xml
        $ap8a_record = $this->getAp8ARecordByYear($year);
        if ($ap8a_record) {
            $xml_file = $ap8a_record->ap8a_file;
            $file_path = FCPATH . str_replace('./', '', $xml_file);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        //delete records
        $this->db->where('basis_year', $year);
        $this->db->delete('xin_appendix8a_submission');

        $this->db->where('ap8a_year', $year);
        $this->db->delete('xin_efiling_appendix8a');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateIr8aSubmission(int $id, array $data)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('xin_ir8a_submission', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAppendix8aSubmission(int $id, array $data)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('xin_appendix8a_submission', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getEmployeeGainsGrantedBefore2003(int $id, string $year)
    {
        $grant_year = '2003-01-01';
        $this->db->select('s.id, s.so_scheme, s.so_plan, s.excercise_price, s.price_date_of_grant, s.price_date_of_excercise, s.no_of_shares');
        $this->db->from('xin_employee_share_options as s');
        $this->db->where('s.employee_id', $id);
        // STR_TO_DATE(ea.period_from,'%d-%m-%Y')
        // $this->db->where('(YEAR(s.date_of_excercise) = \'' . $year . '\' AND s.date_of_grant  < \'' . $grant_year . '\')', NULL, FALSE);
        $this->db->where('(YEAR(STR_TO_DATE(s.date_of_excercise,"%d-%m-%Y")) = \'' . $year . '\' AND s.date_of_grant  < \'' . $grant_year . '\')', NULL, FALSE);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getEmployeeGainsGrantedAfter2003(int $id, string $year)
    {
        $grant_year = '2003-01-01';
        $this->db->select('s.id, s.so_scheme, s.so_plan, s.excercise_price, s.price_date_of_grant, s.price_date_of_excercise, s.no_of_shares');
        $this->db->from('xin_employee_share_options as s');
        $this->db->where('s.employee_id', $id);
        // $this->db->where('(YEAR(s.date_of_excercise) = \'' . $year . '\' AND s.date_of_grant  >= \'' . $grant_year . '\')', NULL, FALSE);
        $this->db->where('(YEAR(STR_TO_DATE(s.date_of_excercise,"%d-%m-%Y")) = \'' . $year . '\' AND s.date_of_grant  >= \'' . $grant_year . '\')', NULL, FALSE);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_all_employee_payslip_summary_8b($year)
    {
        $e_summary = $this->get_all_employee_payslip_summary($year);
        if ($e_summary->num_rows() > 0) {
            foreach ($e_summary->result() as $s) {
                $s->ir8a_d8 = 'D8';
                $s->ap8b_eligible = 'Yes';
                $emp_id = $s->employee_id;
                $this->db->select('id, ir8a_year, stock_gains_ap8b');
                $this->db->from('xin_efiling_ir8a');
                $this->db->where('ir8a_year', $year - 1);
                $this->db->where('employee_id', $emp_id);
                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                    $ap8b_result = $result->row();
                    if ($ap8b_result->stock_gains_ap8b) {
                        $s->ir8a_d8 = $ap8b_result->stock_gains_ap8b;
                        $s->ap8b_eligible = 'Yes';
                    } else {
                        $s->ir8a_d8 = '';
                        $s->ap8b_eligible = 'No';
                    }
                } else {
                    $s->ir8a_d8 = '';
                    $s->ap8b_eligible = 'No';
                }
            }
        }
        return $e_summary;
    }

    public function getAp8BEmployees($year)
    {
        $this->db->select('fa.employee_id, e.user_id, e.first_name, e.last_name, fa.id, fa.ap8b_key, fa.ap8b_year, fa.gross_amount_eebr, 
        fa.gross_amount_eris_sme, fa.gross_amount_eris_corp, fa.gross_amount_eris_startup');
        $this->db->from('xin_efiling_appendix8b as fa');
        $this->db->join('xin_employees as e', 'e.user_id = fa.employee_id');
        $this->db->where('fa.ap8b_year', $year);
        return $this->db->get();
    }

    public function getAp8BRecordByYear($year)
    {
        $this->db->select('id, basis_year, no_of_records, ap8b_file, status');
        $this->db->from('xin_appendix8b_submission');
        $this->db->where('basis_year', $year);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function getEmployeesAnnualShareOptions($year)
    {
        $this->db->select('sp.employee_id, e.first_name, e.last_name');
        $this->db->from('xin_salary_payslips as sp');
        $this->db->join('xin_employees as e', 'sp.employee_id = e.user_id');
        $this->db->join('xin_salary_payslip_share_options as so', 'so.payslip_id = sp.payslip_id');
        // $this->db->where("sp.salary_month LIKE '$year%'", NULL, FALSE);
        $this->db->where("RIGHT(sp.salary_month,4) LIKE '$year%'", NULL, FALSE);
        $this->db->group_by('sp.employee_id');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getEmployeeGains(int $emp_id, string $year, int $scheme)
    {
        $this->db->select('s.id, s.so_scheme, s.so_plan, s.date_of_grant, s.date_of_excercise, s.excercise_price, s.price_date_of_grant, s.price_date_of_excercise, s.no_of_shares, c.name as company_name, e.organisation_id_type, e.organisation_id_no');
        $this->db->from('xin_employee_share_options as s');
        $this->db->join('xin_companies as c', 'c.company_id = s.company_id');
        $this->db->join('xin_efiling_details as e', 'e.company_id = c.company_id');
        $this->db->where('s.employee_id', $emp_id);
        // $this->db->where('(YEAR(s.date_of_excercise) = \'' . $year . '\' AND s.so_scheme  = \'' . $scheme . '\')', NULL, FALSE);
        $this->db->where('(YEAR(STR_TO_DATE(s.date_of_excercise,"%d-%m-%Y")) = \'' . $year . '\' AND s.so_scheme  >= \'' . $scheme . '\')', NULL, FALSE);
        $this->db->order_by('s.date_of_excercise', 'ASC');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function setAp8bEmployee(array $data)
    {
        $result = $this->db->insert('xin_efiling_appendix8b', $data);
        if ($result) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function saveAp8bRecords(array $data)
    {
        $this->db->insert('xin_appendix8b_submission', $data);
        return $this->db->insert_id();
    }

    public function resetAllAp8bRecordsByYear($year)
    {
        //reset ir8a
        $this->resetAllIr8aRecordsByYear($year);

        $this->db->trans_begin();
        //ap8b submission delete xml
        $ap8b_record = $this->getAp8BRecordByYear($year);
        if ($ap8b_record) {
            $xml_file = $ap8b_record->ap8b_file;
            $file_path = FCPATH . str_replace('./', '', $xml_file);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        //delete records
        $this->db->where('basis_year', $year);
        $this->db->delete('xin_appendix8b_submission');

        $this->db->where('ap8b_year', $year);
        $this->db->delete('xin_efiling_appendix8b');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}
