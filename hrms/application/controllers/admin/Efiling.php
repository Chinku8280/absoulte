<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Efiling extends MY_Controller
{

	public function __construct()
	{
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
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function employerdetails()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'Employer\'s Filing Details';
		$data['breadcrumbs'] = 'Employer\'s Filing Details';
		$data['path_url'] = 'efiling_employer_details';
		$data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$data['id_type'] = $this->Efiling_model->getPersonIDType();
		$data['get_all_companies'] = $this->Xin_model->get_companies();
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('428', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/efiling/details", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// get get_efiling_data
	public function get_efiling_data()
	{
		$efiling = $this->Efiling_model->getEFilingCompanyDetails($this->input->get('company_id'));
		$id_type = $this->Efiling_model->getPersonIDType();

		$data = [
			'csn' 				=> $efiling->csn ?? '',
			'idno'				=> $efiling->organisation_id_no ?? '',
			'idtype'				=> $efiling->organisation_id_type ?? '',
			'authorisedname'	=> $efiling->authorised_name ?? '',
			'authoriseddesignation' => $efiling->authorised_designation ?? '',
			'aurthorisedidtype'	=>  $efiling->authorised_id_type ?? '',
			'authorisedidno'	=> $efiling->authorised_id_no ?? '',
			'authorisedemail'	=> $efiling->authorised_email ?? '',
			'authorisedphone'	=> $efiling->authorised_phone ?? '',
		];

		$this->output($data);
	}




	// Validate and update info in database // basic info
	public function efiling_details()
	{

		if ($this->input->post('type') == 'efiling_details') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('csn') === '') {
				$Return['error'] = 'CPF submission number is required';
			} elseif ($this->input->post('idtype') === '') {
				$Return['error'] = 'Organisation ID Type is required';
			} elseif ($this->input->post('idno') === '') {
				$Return['error'] = 'Organisation ID Number is required';
			} elseif ($this->input->post('authorisedname') === '') {
				$Return['error'] = 'Authorised Person Name is required';
			} elseif ($this->input->post('authoriseddesignation') === '') {
				$Return['error'] = 'Authorised Person Designation is required';
			} elseif ($this->input->post('authorisedemail') === '') {
				$Return['error'] = 'Authorised Person Email is required';
			} elseif ($this->input->post('aurthorisedidtype') === '') {
				$Return['error'] = 'Authorised Person ID Type is required';
			} elseif ($this->input->post('authorisedidno') === '') {
				$Return['error'] = 'Authorised Person ID Number is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$data = $this->input->post();
			$result = $this->Efiling_model->updateEFilingDetails($data);
			if ($result == TRUE) {
				$Return['result'] = 'Employer Filing Details updated successfully!';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function cpf()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'CPF Submission';
		$data['breadcrumbs'] = 'CPF Submission';
		$data['path_url'] = 'cpf_submission';
		$data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$data['get_all_companies'] = $this->Xin_model->get_companies();
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('428', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/efiling/cpf_submission", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function cpf_submission()
	{
		if ($this->input->post('type') == 'cpf_submission') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('csn') === '') {
				$Return['error'] = 'CPF submission number is required';
			} elseif ($this->input->post('month_year') === '') {
				$Return['error'] = 'Month is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			// $data = $this->input->post();
			// $result = $this->Efiling_model->updateEFilingDetails($data);
			// $payslips = $this->Cpf_payslip_model->getSalaryPayslipsByMonth($this->input->post('month_year'));
			$payslips = $this->Cpf_payslip_model->getSalaryPayslipsByMonthAndCompanyWise($this->input->post('month_year'), $this->input->post('company_id'));
			if ($payslips) {
				date_default_timezone_set('Asia/Singapore');
				$datetime = Date('YmdHis', time());

				/* Employer_header */
				$eh = 'F'; //submission mode
				$eh .= ' '; //Record Type
				$eh .= $this->input->post('csn'); //csn
				$eh .= ' '; //space
				$eh .= '01'; //AdiveCode
				$eh .= $datetime; //Date and Time
				$eh .= 'FTP.DTL'; //File ID

				$employer_header = $eh . "\n";
				/* End Employer header */

				// for date change
				$dateString = $this->input->post('month_year');
				$originalDate = DateTime::createFromFormat('m-Y', $dateString);
				$formattedDate = $originalDate->format('Y-m');

				// end date change



				/* Employer Contribution Summary Record */
				$summary_record = '';
				$total_cpf_contribution = 0;
				$total_mbmf_contribution = 0;
				$total_mbmf_donor = 0;
				$total_sinda_contribution = 0;
				$total_sinda_donor = 0;
				$total_cdac_contribution = 0;
				$total_cdac_donor = 0;
				$total_ecf_contribution = 0;
				$total_ecf_donor = 0;
				$total_rsfh_donor = 0;
				$total_cpfpn_donor = 0;
				$total_fwl_donor = 0;
				$total_fwlpi_donor = 0;
				$total_ccfh_donor = 0;
				$total_sdl_contribution = 0;

				foreach ($payslips as $p) {
					$cpf = $this->Cpf_payslip_model->getCpfByPayslipId($p->payslip_id);
					if ($cpf) {
						$total_cpf_contribution += round($cpf->ow_cpf_employer + $cpf->ow_cpf_employee + $cpf->aw_cpf_employer + $cpf->aw_cpf_employee, 2);
					}

					//mbmf
					$mbmf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 1);
					if ($mbmf) {
						$total_mbmf_contribution += $mbmf->contribution_amount;
						$total_mbmf_donor += 1;
					}

					//sinda
					$sinda = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 2);
					if ($sinda) {
						$total_sinda_contribution += $sinda->contribution_amount;
						$total_sinda_donor += 1;
					}

					//cdac
					$cdac = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 3);
					if ($cdac) {
						$total_cdac_contribution += $cdac->contribution_amount;
						$total_cdac_donor += 1;
					}

					//ecf
					$ecf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 4);
					if ($ecf) {
						$total_ecf_contribution += $ecf->contribution_amount;
						$total_ecf_donor += 1;
					}

					//sdl
					$sdl = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 5);
					if ($sdl) {
						$total_sdl_contribution += $sdl->contribution_amount;
					}
				}

				//cpf
				if ($total_cpf_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_cpf_contribution)) {
						$str_l = strlen($total_cpf_contribution);
						$decimal_point_pos = strpos($total_cpf_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_cpf_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_cpf_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_cpf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_cpf_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$cpfh = 'F'; //submission mode
					$cpfh .= '0'; //Record Type
					$cpfh .= $this->input->post('csn'); //csn
					$cpfh .= ' '; //space
					$cpfh .= '01'; //AdiveCode
					$cpfh .= $contribution_month; //Month
					$cpfh .= '01'; //Payment Code
					$cpfh .= $contribution_amount; //contribution amount
					$cpfh .= str_pad('', 7, '0'); //Donor Count
					$summary_record .= $cpfh . "\n";
				}

				//mbmf
				if ($total_mbmf_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_mbmf_contribution)) {
						$str_l = strlen($total_mbmf_contribution);
						$decimal_point_pos = strpos($total_mbmf_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_mbmf_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_mbmf_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_mbmf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_mbmf_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$mbfh = 'F'; //submission mode
					$mbfh .= '0'; //Record Type
					$mbfh .= $this->input->post('csn'); //csn
					$mbfh .= ' '; //space
					$mbfh .= '01'; //AdiveCode
					$mbfh .= $contribution_month; //Month
					$mbfh .= '02'; //Payment Code
					$mbfh .= $contribution_amount; //contribution amount
					$mbfh .= str_pad($total_mbmf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $mbfh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$mbfh = 'F'; //submission mode
					$mbfh .= '0'; //Record Type
					$mbfh .= $this->input->post('csn'); //csn
					$mbfh .= ' '; //space
					$mbfh .= '01'; //AdiveCode
					$mbfh .= $contribution_month; //Month
					$mbfh .= '02'; //Payment Code
					$mbfh .= $contribution_amount; //contribution amount
					$mbfh .= str_pad($total_mbmf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $mbfh . "\n";
				}

				//sinda
				if ($total_sinda_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_sinda_contribution)) {
						$str_l = strlen($total_sinda_contribution);
						$decimal_point_pos = strpos($total_sinda_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_sinda_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_sinda_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_sinda_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_sinda_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$sindh = 'F'; //submission mode
					$sindh .= '0'; //Record Type
					$sindh .= $this->input->post('csn'); //csn
					$sindh .= ' '; //space
					$sindh .= '01'; //AdiveCode
					$sindh .= $contribution_month; //Month
					$sindh .= '03'; //Payment Code
					$sindh .= $contribution_amount; //contribution amount
					$sindh .= str_pad($total_sinda_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sindh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$sindh = 'F'; //submission mode
					$sindh .= '0'; //Record Type
					$sindh .= $this->input->post('csn'); //csn
					$sindh .= ' '; //space
					$sindh .= '01'; //AdiveCode
					$sindh .= $contribution_month; //Month
					$sindh .= '03'; //Payment Code
					$sindh .= $contribution_amount; //contribution amount
					$sindh .= str_pad($total_sinda_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sindh . "\n";
				}

				//cdac
				if ($total_cdac_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_cdac_contribution)) {
						$str_l = strlen($total_cdac_contribution);
						$decimal_point_pos = strpos($total_cdac_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_cdac_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_cdac_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_cdac_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_cdac_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$cdach = 'F'; //submission mode
					$cdach .= '0'; //Record Type
					$cdach .= $this->input->post('csn'); //csn
					$cdach .= ' '; //space
					$cdach .= '01'; //AdiveCode
					$cdach .= $contribution_month; //Month
					$cdach .= '04'; //Payment Code
					$cdach .= $contribution_amount; //contribution amount
					$cdach .= str_pad($total_cdac_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $cdach . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$cdach = 'F'; //submission mode
					$cdach .= '0'; //Record Type
					$cdach .= $this->input->post('csn'); //csn
					$cdach .= ' '; //space
					$cdach .= '01'; //AdiveCode
					$cdach .= $contribution_month; //Month
					$cdach .= '04'; //Payment Code
					$cdach .= $contribution_amount; //contribution amount
					$cdach .= str_pad($total_cdac_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $cdach . "\n";
				}

				//ecf
				if ($total_ecf_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_ecf_contribution)) {
						$str_l = strlen($total_ecf_contribution);
						$decimal_point_pos = strpos($total_ecf_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_ecf_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_ecf_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_ecf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_ecf_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$ecfh = 'F'; //submission mode
					$ecfh .= '0'; //Record Type
					$ecfh .= $this->input->post('csn'); //csn
					$ecfh .= ' '; //space
					$ecfh .= '01'; //AdiveCode
					$ecfh .= $contribution_month; //Month
					$ecfh .= '05'; //Payment Code
					$ecfh .= $contribution_amount; //contribution amount
					$ecfh .= str_pad($total_ecf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $ecfh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$ecfh = 'F'; //submission mode
					$ecfh .= '0'; //Record Type
					$ecfh .= $this->input->post('csn'); //csn
					$ecfh .= ' '; //space
					$ecfh .= '01'; //AdiveCode
					$ecfh .= $contribution_month; //Month
					$ecfh .= '05'; //Payment Code
					$ecfh .= $contribution_amount; //contribution amount
					$ecfh .= str_pad($total_ecf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $ecfh . "\n";
				}

				//Reserved for future use
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$rsfh = 'F'; //submission mode
				$rsfh .= '0'; //Record Type
				$rsfh .= $this->input->post('csn'); //csn
				$rsfh .= ' '; //space
				$rsfh .= '01'; //AdiveCode
				$rsfh .= $contribution_month; //Month
				$rsfh .= '06'; //Payment Code
				$rsfh .= $contribution_amount; //contribution amount
				$rsfh .= str_pad($total_rsfh_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $rsfh . "\n";

				//cpf penalty interest
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$cpfpn = 'F'; //submission mode
				$cpfpn .= '0'; //Record Type
				$cpfpn .= $this->input->post('csn'); //csn
				$cpfpn .= ' '; //space
				$cpfpn .= '01'; //AdiveCode
				$cpfpn .= $contribution_month; //Month
				$cpfpn .= '07'; //Payment Code
				$cpfpn .= $contribution_amount; //contribution amount
				$cpfpn .= str_pad($total_cpfpn_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $cpfpn . "\n";

				//fwl
				// $contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$fwl = 'F'; //submission mode
				$fwl .= '0'; //Record Type
				$fwl .= $this->input->post('csn'); //csn
				$fwl .= ' '; //space
				$fwl .= '01'; //AdiveCode
				$fwl .= $contribution_month; //Month
				$fwl .= '08'; //Payment Code
				$fwl .= $contribution_amount; //contribution amount
				$fwl .= str_pad($total_fwl_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $fwl . "\n";

				//fwl penalty interest
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$fwlpi = 'F'; //submission mode
				$fwlpi .= '0'; //Record Type
				$fwlpi .= $this->input->post('csn'); //csn
				$fwlpi .= ' '; //space
				$fwlpi .= '01'; //AdiveCode
				$fwlpi .= $contribution_month; //Month
				$fwlpi .= '09'; //Payment Code
				$fwlpi .= $contribution_amount; //contribution amount
				$fwlpi .= str_pad($total_fwlpi_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $fwlpi . "\n";

				//community chest
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$ccfh = 'F'; //submission mode
				$ccfh .= '0'; //Record Type
				$ccfh .= $this->input->post('csn'); //csn
				$ccfh .= ' '; //space
				$ccfh .= '01'; //AdiveCode
				$ccfh .= $contribution_month; //Month
				$ccfh .= '10'; //Payment Code
				$ccfh .= $contribution_amount; //contribution amount
				$ccfh .= str_pad($total_ccfh_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $ccfh . "\n";

				//sdl
				if ($total_sdl_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_sdl_contribution)) {
						$str_l = strlen($total_sdl_contribution);
						$decimal_point_pos = strpos($total_sdl_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_sdl_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_sdl_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_sdl_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_sdl_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$sdlh = 'F'; //submission mode
					$sdlh .= '0'; //Record Type
					$sdlh .= $this->input->post('csn'); //csn
					$sdlh .= ' '; //space
					$sdlh .= '01'; //AdiveCode
					$sdlh .= $contribution_month; //Month
					$sdlh .= '11'; //Payment Code
					$sdlh .= $contribution_amount; //contribution amount
					$sdlh .= str_pad('', 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sdlh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$sdlh = 'F'; //submission mode
					$sdlh .= '0'; //Record Type
					$sdlh .= $this->input->post('csn'); //csn
					$sdlh .= ' '; //space
					$sdlh .= '01'; //AdiveCode
					$sdlh .= $contribution_month; //Month
					$sdlh .= '11'; //Payment Code
					$sdlh .= $contribution_amount; //contribution amount
					$sdlh .= str_pad('', 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sdlh . "\n";
				}
				/*End Employer Contribution Summary Record */

				/* Employer Contribution Detail Record */
				$detail_records = '';
				foreach ($payslips as $p) {
					$cpf = $this->Cpf_payslip_model->getCpfByPayslipId($p->payslip_id);
					$cpf_contribution = 0;
					if ($cpf) {
						$cpf_contribution = round($cpf->ow_cpf_employer + $cpf->ow_cpf_employee + $cpf->aw_cpf_employer + $cpf->aw_cpf_employee, 2);

						//ordinary wages
						$ow = (float)$cpf->ow_paid;

						//additional wages
						$aw = (float)$cpf->aw_paid;
						if ($aw === '') {
							$aw = 0;
						}
					}



					//mbmf
					$mbmf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 1);
					$mbmf_contribution = 0;
					if ($mbmf) {
						$mbmf_contribution = (float)$mbmf->contribution_amount;
					}



					//sinda
					$sinda = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 2);
					$sinda_contribution = 0;
					if ($sinda) {
						$sinda_contribution = (float)$sinda->contribution_amount;
					}

					//cdac
					$cdac = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 3);
					$cdac_contribution = 0;
					if ($cdac) {
						$cdac_contribution = (float)$cdac->contribution_amount;
					}

					//ecf
					$ecf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 4);
					$ecf_contribution = 0;
					if ($ecf) {
						$ecf_contribution = (float)$ecf->contribution_amount;
					}



					//employee detail
					$employee = $this->Employees_model->read_employee_information($p->employee_id);
					if ($employee) {
						$emp_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
						$emp_id_no = $employee[0]->id_no;
						$emp_join_date = ($employee[0]->date_of_joining != null) ? date('m-Y', strtotime($employee[0]->date_of_joining)) : null;
						$emp_leave_date = ($employee[0]->date_of_leaving != null) ? date('m-Y', strtotime($employee[0]->date_of_leaving)) : null;
						if (strlen($emp_name) > 22) {
							$emp_name = substr($emp_name, 0, 22);
						}
					}

					//cpf
					if ($cpf_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($cpf_contribution)) {
							$str_l = strlen($cpf_contribution);
							$decimal_point_pos = strpos($cpf_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $cpf_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $cpf_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($cpf_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {

							$contribution_amount = str_pad($cpf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}

						if (is_float($ow)) {
							$str_l = strlen($ow);
							$decimal_point_pos = strpos($ow, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$ow_amount = str_replace('.', '', $ow);
									$ow_amount = str_pad($ow_amount, 10, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$ow_amount = str_replace('.', '', $ow);
									$ow_amount .= '0';
									$ow_amount = str_pad($ow_amount, 10, '0', STR_PAD_LEFT);
								}
							} else {
								$ow_amount = str_pad($ow, 8, '0', STR_PAD_LEFT);
								$ow_amount .= '00';
							}
						} else {
							$ow_amount = str_pad($ow, 8, '0', STR_PAD_LEFT);
							$ow_amount .= '00';
						}

						if (is_float($aw)) {
							$str_l = strlen($aw);
							$decimal_point_pos = strpos($aw, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$aw_amount = str_replace('.', '', $aw);
									$aw_amount = str_pad($aw_amount, 10, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$aw_amount = str_replace('.', '', $aw);
									$aw_amount .= '0';
									$aw_amount = str_pad($aw_amount, 10, '0', STR_PAD_LEFT);
								}
							} else {
								$aw_amount = str_pad($aw, 8, '0', STR_PAD_LEFT);
								$aw_amount .= '00';
							}
						} else {
							$aw_amount = str_pad($aw, 8, '0', STR_PAD_LEFT);
							$aw_amount .= '00';
						}

						$cpfh = 'F'; //submission mode
						$cpfh .= '1'; //Record Type
						$cpfh .= $this->input->post('csn'); //csn
						$cpfh .= ' '; //space
						$cpfh .= '01'; //AdiveCode
						$cpfh .= $contribution_month; //Month
						$cpfh .= '01'; //Payment Code
						$cpfh .= $emp_id_no; //Employee ID No
						$cpfh .= $contribution_amount; //contribution amount
						$cpfh .= $ow_amount; //ow amount
						$cpfh .= $aw_amount; //aw amount
						/*E - Existing employee or
							employee who leaves and
							joins in the same month
						  L - Leaver
						  N - New Joiner
					      O - (alphabet O)
							Employee who joins and
							leaves in the same month 
						*/
						if ($emp_join_date != null && $emp_leave_date == null) {
							if ($emp_join_date == $dateString) {
								$status = 'N';
							} else {
								$status = 'E';
							}
						} elseif ($emp_join_date != null && $emp_leave_date != null) {
							if ($emp_join_date == $emp_leave_date) { // Employee leaves and joins in the same month
								$status = 'O';
							} elseif ($emp_leave_date == $dateString) { // Leaver
								$status = 'L';
							} else {
								$status = 'E';
							}
						} else {
							$status = 'F'; // Default to existing employee if no dates are provided
						}
						$cpfh .= $status; //existing employee
						$cpfh .= $emp_name; //employee name
						$detail_records .= $cpfh . "\n";
					}

					//mbmf
					if ($mbmf_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($mbmf_contribution)) {
							$str_l = strlen($mbmf_contribution);
							$decimal_point_pos = strpos($mbmf_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $mbmf_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {


									$contribution_amount = str_replace('.', '', $mbmf_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($mbmf_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($mbmf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$mbmfh = 'F'; //submission mode
						$mbmfh .= '1'; //Record Type
						$mbmfh .= $this->input->post('csn'); //csn
						$mbmfh .= ' '; //space
						$mbmfh .= '01'; //AdiveCode
						$mbmfh .= $contribution_month; //Month
						$mbmfh .= '02'; //Payment Code
						$mbmfh .= $emp_id_no; //Employee ID No
						$mbmfh .= $contribution_amount; //contribution amount
						$mbmfh .= str_pad('', 10, '0'); //ow amount
						$mbmfh .= str_pad('', 10, '0'); //aw
						$mbmfh .= ' '; //existing employee
						$mbmfh .= $emp_name; //employee name
						$detail_records .= $mbmfh . "\n";
					}

					//sinda
					if ($sinda_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($sinda_contribution)) {
							$str_l = strlen($sinda_contribution);
							$decimal_point_pos = strpos($sinda_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $sinda_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $sinda_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($sinda_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($sinda_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$sindah = 'F'; //submission mode
						$sindah .= '1'; //Record Type
						$sindah .= $this->input->post('csn'); //csn
						$sindah .= ' '; //space
						$sindah .= '01'; //AdiveCode
						$sindah .= $contribution_month; //Month
						$sindah .= '03'; //Payment Code
						$sindah .= $emp_id_no; //Employee ID No
						$sindah .= $contribution_amount; //contribution amount
						$sindah .= str_pad('', 10, '0'); //ow amount
						$sindah .= str_pad('', 10, '0'); //aw
						$sindah .= ' '; //existing employee
						$sindah .= $emp_name; //employee name
						$detail_records .= $sindah . "\n";
					}

					//cdac
					if ($cdac_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($cdac_contribution)) {
							$str_l = strlen($cdac_contribution);
							$decimal_point_pos = strpos($cdac_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $cdac_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $cdac_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($cdac_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($cdac_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$cdach = 'F'; //submission mode
						$cdach .= '1'; //Record Type
						$cdach .= $this->input->post('csn'); //csn
						$cdach .= ' '; //space
						$cdach .= '01'; //AdiveCode
						$cdach .= $contribution_month; //Month
						$cdach .= '04'; //Payment Code
						$cdach .= $emp_id_no; //Employee ID No
						$cdach .= $contribution_amount; //contribution amount
						$cdach .= str_pad('', 10, '0'); //ow amount
						$cdach .= str_pad('', 10, '0'); //aw
						$cdach .= ' '; //existing employee
						$cdach .= $emp_name; //employee name
						$detail_records .= $cdach . "\n";
					}

					//ecf
					if ($ecf_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($ecf_contribution)) {
							$str_l = strlen($ecf_contribution);
							$decimal_point_pos = strpos($ecf_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $ecf_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $ecf_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($ecf_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($ecf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$ecfh = 'F'; //submission mode
						$ecfh .= '1'; //Record Type
						$ecfh .= $this->input->post('csn'); //csn
						$ecfh .= ' '; //space
						$ecfh .= '01'; //AdiveCode
						$ecfh .= $contribution_month; //Month
						$ecfh .= '05'; //Payment Code
						$ecfh .= $emp_id_no; //Employee ID No
						$ecfh .= $contribution_amount; //contribution amount
						$ecfh .= str_pad('', 10, '0'); //ow amount
						$ecfh .= str_pad('', 10, '0'); //aw
						$ecfh .= ' '; //existing employee
						$ecfh .= $emp_name; //employee name
						$detail_records .= $ecfh . "\n";
					}
				}
				/* End Employer Contribution Detail Record*/

				/* Employer Trailer Record */
				$records = $employer_header . $summary_record . $detail_records;
				$no_records = substr_count($records, "\n");
				$total_cpf_amount = ($total_cpf_contribution + $total_mbmf_contribution + $total_sinda_contribution + $total_cdac_contribution + $total_ecf_contribution + $total_sdl_contribution);

				if (is_float($total_cpf_amount)) {
					$str_l = strlen($total_cpf_amount);
					$decimal_point_pos = strpos($total_cpf_amount, '.');
					if ($decimal_point_pos) {
						if (($str_l - $decimal_point_pos) == 3) {
							$contribution_amount = str_replace('.', '', $total_cpf_amount);
							$contribution_amount = str_pad($contribution_amount, 15, '0', STR_PAD_LEFT);
						} elseif (($str_l - $decimal_point_pos) == 2) {
							$contribution_amount = str_replace('.', '', $total_cpf_amount);
							$contribution_amount .= '0';
							$contribution_amount = str_pad($contribution_amount, 15, '0', STR_PAD_LEFT);
						}
					} else {
						$contribution_amount = str_pad($total_cpf_amount, 13, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}
				} else {
					$contribution_amount = str_pad($total_cpf_amount, 13, '0', STR_PAD_LEFT);
					$contribution_amount .= '00';
				}

				$emtr = 'F'; //submission mode
				$emtr .= '9'; //Record Type
				$emtr .= $this->input->post('csn'); //csn
				$emtr .= ' '; //space
				$emtr .= '01'; //AdiveCode
				$emtr .= str_pad($no_records + 1, 7, '0', STR_PAD_LEFT); //No of records fornat will in 0000000 + no record
				$emtr .= $contribution_amount; //contribution amount

				$employer_trailer = $emtr;

				/* End Employer Trailer Record */

				$content = $employer_header . $summary_record . $detail_records . $employer_trailer;
				$dateString = $this->input->post('month_year');
				$originalDate = DateTime::createFromFormat('m-Y', $dateString);
				$formattedDate = $originalDate->format('Y-m');

				$month = Date('MY', strtotime($formattedDate));
				// $filename = './uploads/efiling/cpf/'. strtoupper($this->input->post('csn') . $month . '01.txt');
				$company = $this->Company_model->get_company_single($this->input->post('company_id'));
				$company = $company->result();
				if (!is_dir('./uploads/efiling/cpf/' . $company[0]->name)) {
					mkdir('./uploads/efiling/cpf/' . $company[0]->name, 0777);
				}
				$filename = './uploads/efiling/cpf/' . $company[0]->name . "/" . strtoupper($this->input->post('csn') . $month . '01.txt');

				$save_file = file_put_contents($filename, $content);
				if ($save_file) {
					$cpf_data = array(
						'company_id'	=> $this->input->post('company_id'),
						'csn' => $this->input->post('csn'),
						'month_year' => '01-' . $this->input->post('month_year'),
						'no_employees' => count($payslips),
						'no_records' => $no_records + 1,
						'cpf_contribution_amount' => $total_cpf_contribution,
						'other_contribution' => $total_cpf_amount - $total_cpf_contribution,
						'total_contribution_amount' => $total_cpf_amount,
						'cpf_file' => $filename,
						'created_by' => $this->input->post('user_id')
					);
					$cpf_submit = $this->Efiling_model->saveCpfSubmission($cpf_data);
					if ($cpf_submit) {
						$Return['result'] = 'CPF Submission File created successfully';
					} else {
						$Return['error'] = 'Error! Could not create CPF Submission file';
					}
				} else {
					$Return['error'] = 'Error! Could not create CPF Submission file';
				}
			} else {
				$Return['error'] = 'Payslips for the selected is not generated';
			}
			// $result = true;
			// if ($result == TRUE) {
			// 	$Return['result'] = 'CPF Submission File is generated successfully!';
			// } else {
			// 	$Return['error'] = $this->lang->line('xin_error_msg');
			// }
			$this->output($Return);
			exit;
		}
	}
	public function old_cpf_submission()
	{
		if ($this->input->post('type') == 'cpf_submission') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('csn') === '') {
				$Return['error'] = 'CPF submission number is required';
			} elseif ($this->input->post('month_year') === '') {
				$Return['error'] = 'Month is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			// $data = $this->input->post();
			// $result = $this->Efiling_model->updateEFilingDetails($data);
			// $payslips = $this->Cpf_payslip_model->getSalaryPayslipsByMonth($this->input->post('month_year'));
			$payslips = $this->Cpf_payslip_model->getSalaryPayslipsByMonthAndCompanyWise($this->input->post('month_year'), $this->input->post('company_id'));
			if ($payslips) {
				date_default_timezone_set('Asia/Singapore');
				$datetime = Date('YmdHis', time());

				/* Employer_header */
				$eh = 'F'; //submission mode
				$eh .= ' '; //Record Type
				$eh .= $this->input->post('csn'); //csn
				$eh .= ' '; //space
				$eh .= '01'; //AdiveCode
				$eh .= $datetime; //Date and Time
				$eh .= 'FTP.DTL'; //File ID

				$employer_header = $eh . "\n";
				/* End Employer header */

				// for date change
				$dateString = $this->input->post('month_year');
				$originalDate = DateTime::createFromFormat('m-Y', $dateString);
				$formattedDate = $originalDate->format('Y-m');

				// end date change



				/* Employer Contribution Summary Record */
				$summary_record = '';
				$total_cpf_contribution = 0;
				$total_mbmf_contribution = 0;
				$total_mbmf_donor = 0;
				$total_sinda_contribution = 0;
				$total_sinda_donor = 0;
				$total_cdac_contribution = 0;
				$total_cdac_donor = 0;
				$total_ecf_contribution = 0;
				$total_ecf_donor = 0;
				$total_rsfh_donor = 0;
				$total_cpfpn_donor = 0;
				$total_fwl_donor = 0;
				$total_fwlpi_donor = 0;
				$total_ccfh_donor = 0;
				$total_sdl_contribution = 0;

				foreach ($payslips as $p) {
					$cpf = $this->Cpf_payslip_model->getCpfByPayslipId($p->payslip_id);
					if ($cpf) {
						$total_cpf_contribution += round($cpf->ow_cpf_employer + $cpf->ow_cpf_employee + $cpf->aw_cpf_employer + $cpf->aw_cpf_employee, 2);
					}

					//mbmf
					$mbmf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 1);
					if ($mbmf) {
						$total_mbmf_contribution += $mbmf->contribution_amount;
						$total_mbmf_donor += 1;
					}

					//sinda
					$sinda = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 2);
					if ($sinda) {
						$total_sinda_contribution += $sinda->contribution_amount;
						$total_sinda_donor += 1;
					}

					//cdac
					$cdac = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 3);
					if ($cdac) {
						$total_cdac_contribution += $cdac->contribution_amount;
						$total_cdac_donor += 1;
					}

					//ecf
					$ecf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 4);
					if ($ecf) {
						$total_ecf_contribution += $ecf->contribution_amount;
						$total_ecf_donor += 1;
					}

					//sdl
					$sdl = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 5);
					if ($sdl) {
						$total_sdl_contribution += $sdl->contribution_amount;
					}
				}

				//cpf
				if ($total_cpf_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_cpf_contribution)) {
						$str_l = strlen($total_cpf_contribution);
						$decimal_point_pos = strpos($total_cpf_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_cpf_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_cpf_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_cpf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_cpf_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$cpfh = 'F'; //submission mode
					$cpfh .= '0'; //Record Type
					$cpfh .= $this->input->post('csn'); //csn
					$cpfh .= ' '; //space
					$cpfh .= '01'; //AdiveCode
					$cpfh .= $contribution_month; //Month
					$cpfh .= '01'; //Payment Code
					$cpfh .= $contribution_amount; //contribution amount
					$cpfh .= str_pad('', 7, '0'); //Donor Count
					$summary_record .= $cpfh . "\n";
				}

				//mbmf
				if ($total_mbmf_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_mbmf_contribution)) {
						$str_l = strlen($total_mbmf_contribution);
						$decimal_point_pos = strpos($total_mbmf_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_mbmf_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_mbmf_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_mbmf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_mbmf_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$mbfh = 'F'; //submission mode
					$mbfh .= '0'; //Record Type
					$mbfh .= $this->input->post('csn'); //csn
					$mbfh .= ' '; //space
					$mbfh .= '01'; //AdiveCode
					$mbfh .= $contribution_month; //Month
					$mbfh .= '02'; //Payment Code
					$mbfh .= $contribution_amount; //contribution amount
					$mbfh .= str_pad($total_mbmf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $mbfh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$mbfh = 'F'; //submission mode
					$mbfh .= '0'; //Record Type
					$mbfh .= $this->input->post('csn'); //csn
					$mbfh .= ' '; //space
					$mbfh .= '01'; //AdiveCode
					$mbfh .= $contribution_month; //Month
					$mbfh .= '02'; //Payment Code
					$mbfh .= $contribution_amount; //contribution amount
					$mbfh .= str_pad($total_mbmf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $mbfh . "\n";
				}

				//sinda
				if ($total_sinda_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_sinda_contribution)) {
						$str_l = strlen($total_sinda_contribution);
						$decimal_point_pos = strpos($total_sinda_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_sinda_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_sinda_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_sinda_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_sinda_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$sindh = 'F'; //submission mode
					$sindh .= '0'; //Record Type
					$sindh .= $this->input->post('csn'); //csn
					$sindh .= ' '; //space
					$sindh .= '01'; //AdiveCode
					$sindh .= $contribution_month; //Month
					$sindh .= '03'; //Payment Code
					$sindh .= $contribution_amount; //contribution amount
					$sindh .= str_pad($total_sinda_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sindh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$sindh = 'F'; //submission mode
					$sindh .= '0'; //Record Type
					$sindh .= $this->input->post('csn'); //csn
					$sindh .= ' '; //space
					$sindh .= '01'; //AdiveCode
					$sindh .= $contribution_month; //Month
					$sindh .= '03'; //Payment Code
					$sindh .= $contribution_amount; //contribution amount
					$sindh .= str_pad($total_sinda_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sindh . "\n";
				}

				//cdac
				if ($total_cdac_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_cdac_contribution)) {
						$str_l = strlen($total_cdac_contribution);
						$decimal_point_pos = strpos($total_cdac_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_cdac_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_cdac_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_cdac_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_cdac_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$cdach = 'F'; //submission mode
					$cdach .= '0'; //Record Type
					$cdach .= $this->input->post('csn'); //csn
					$cdach .= ' '; //space
					$cdach .= '01'; //AdiveCode
					$cdach .= $contribution_month; //Month
					$cdach .= '04'; //Payment Code
					$cdach .= $contribution_amount; //contribution amount
					$cdach .= str_pad($total_cdac_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $cdach . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$cdach = 'F'; //submission mode
					$cdach .= '0'; //Record Type
					$cdach .= $this->input->post('csn'); //csn
					$cdach .= ' '; //space
					$cdach .= '01'; //AdiveCode
					$cdach .= $contribution_month; //Month
					$cdach .= '04'; //Payment Code
					$cdach .= $contribution_amount; //contribution amount
					$cdach .= str_pad($total_cdac_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $cdach . "\n";
				}

				//ecf
				if ($total_ecf_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_ecf_contribution)) {
						$str_l = strlen($total_ecf_contribution);
						$decimal_point_pos = strpos($total_ecf_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_ecf_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_ecf_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_ecf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_ecf_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$ecfh = 'F'; //submission mode
					$ecfh .= '0'; //Record Type
					$ecfh .= $this->input->post('csn'); //csn
					$ecfh .= ' '; //space
					$ecfh .= '01'; //AdiveCode
					$ecfh .= $contribution_month; //Month
					$ecfh .= '05'; //Payment Code
					$ecfh .= $contribution_amount; //contribution amount
					$ecfh .= str_pad($total_ecf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $ecfh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$ecfh = 'F'; //submission mode
					$ecfh .= '0'; //Record Type
					$ecfh .= $this->input->post('csn'); //csn
					$ecfh .= ' '; //space
					$ecfh .= '01'; //AdiveCode
					$ecfh .= $contribution_month; //Month
					$ecfh .= '05'; //Payment Code
					$ecfh .= $contribution_amount; //contribution amount
					$ecfh .= str_pad($total_ecf_donor, 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $ecfh . "\n";
				}

				//Reserved for future use
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$rsfh = 'F'; //submission mode
				$rsfh .= '0'; //Record Type
				$rsfh .= $this->input->post('csn'); //csn
				$rsfh .= ' '; //space
				$rsfh .= '01'; //AdiveCode
				$rsfh .= $contribution_month; //Month
				$rsfh .= '06'; //Payment Code
				$rsfh .= $contribution_amount; //contribution amount
				$rsfh .= str_pad($total_rsfh_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $rsfh . "\n";

				//cpf penalty interest
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$cpfpn = 'F'; //submission mode
				$cpfpn .= '0'; //Record Type
				$cpfpn .= $this->input->post('csn'); //csn
				$cpfpn .= ' '; //space
				$cpfpn .= '01'; //AdiveCode
				$cpfpn .= $contribution_month; //Month
				$cpfpn .= '07'; //Payment Code
				$cpfpn .= $contribution_amount; //contribution amount
				$cpfpn .= str_pad($total_cpfpn_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $cpfpn . "\n";

				//fwl
				// $contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$fwl = 'F'; //submission mode
				$fwl .= '0'; //Record Type
				$fwl .= $this->input->post('csn'); //csn
				$fwl .= ' '; //space
				$fwl .= '01'; //AdiveCode
				$fwl .= $contribution_month; //Month
				$fwl .= '08'; //Payment Code
				$fwl .= $contribution_amount; //contribution amount
				$fwl .= str_pad($total_fwl_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $fwl . "\n";

				//fwl penalty interest
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$fwlpi = 'F'; //submission mode
				$fwlpi .= '0'; //Record Type
				$fwlpi .= $this->input->post('csn'); //csn
				$fwlpi .= ' '; //space
				$fwlpi .= '01'; //AdiveCode
				$fwlpi .= $contribution_month; //Month
				$fwlpi .= '09'; //Payment Code
				$fwlpi .= $contribution_amount; //contribution amount
				$fwlpi .= str_pad($total_fwlpi_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $fwlpi . "\n";

				//community chest
				$contribution_month = str_replace('-', '', $formattedDate);
				$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
				$ccfh = 'F'; //submission mode
				$ccfh .= '0'; //Record Type
				$ccfh .= $this->input->post('csn'); //csn
				$ccfh .= ' '; //space
				$ccfh .= '01'; //AdiveCode
				$ccfh .= $contribution_month; //Month
				$ccfh .= '10'; //Payment Code
				$ccfh .= $contribution_amount; //contribution amount
				$ccfh .= str_pad($total_ccfh_donor, 7, '0', STR_PAD_LEFT); //Donor Count
				$summary_record .= $ccfh . "\n";

				//sdl
				if ($total_sdl_contribution > 0) {
					$contribution_month = str_replace('-', '', $formattedDate);
					if (is_float($total_sdl_contribution)) {
						$str_l = strlen($total_sdl_contribution);
						$decimal_point_pos = strpos($total_sdl_contribution, '.');
						if ($decimal_point_pos) {
							if (($str_l - $decimal_point_pos) == 3) {
								$contribution_amount = str_replace('.', '', $total_sdl_contribution);
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							} elseif (($str_l - $decimal_point_pos) == 2) {
								$contribution_amount = str_replace('.', '', $total_sdl_contribution);
								$contribution_amount .= '0';
								$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
							}
						} else {
							$contribution_amount = str_pad($total_sdl_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}
					} else {
						$contribution_amount = str_pad($total_sdl_contribution, 10, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}

					$sdlh = 'F'; //submission mode
					$sdlh .= '0'; //Record Type
					$sdlh .= $this->input->post('csn'); //csn
					$sdlh .= ' '; //space
					$sdlh .= '01'; //AdiveCode
					$sdlh .= $contribution_month; //Month
					$sdlh .= '11'; //Payment Code
					$sdlh .= $contribution_amount; //contribution amount
					$sdlh .= str_pad('', 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sdlh . "\n";
				} else {
					$contribution_month = str_replace('-', '', $formattedDate);
					$contribution_amount = str_pad('', 12, '0', STR_PAD_LEFT);
					$sdlh = 'F'; //submission mode
					$sdlh .= '0'; //Record Type
					$sdlh .= $this->input->post('csn'); //csn
					$sdlh .= ' '; //space
					$sdlh .= '01'; //AdiveCode
					$sdlh .= $contribution_month; //Month
					$sdlh .= '11'; //Payment Code
					$sdlh .= $contribution_amount; //contribution amount
					$sdlh .= str_pad('', 7, '0', STR_PAD_LEFT); //Donor Count
					$summary_record .= $sdlh . "\n";
				}
				/*End Employer Contribution Summary Record */

				/* Employer Contribution Detail Record */
				$detail_records = '';
				foreach ($payslips as $p) {
					$cpf = $this->Cpf_payslip_model->getCpfByPayslipId($p->payslip_id);
					$cpf_contribution = 0;
					if ($cpf) {
						$cpf_contribution = round($cpf->ow_cpf_employer + $cpf->ow_cpf_employee + $cpf->aw_cpf_employer + $cpf->aw_cpf_employee, 2);

						//ordinary wages
						$ow = (float)$cpf->ow_paid;

						//additional wages
						$aw = (float)$cpf->aw_paid;
						if ($aw === '') {
							$aw = 0;
						}
					}



					//mbmf
					$mbmf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 1);
					$mbmf_contribution = 0;
					if ($mbmf) {
						$mbmf_contribution = (float)$mbmf->contribution_amount;
					}



					//sinda
					$sinda = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 2);
					$sinda_contribution = 0;
					if ($sinda) {
						$sinda_contribution = (float)$sinda->contribution_amount;
					}

					//cdac
					$cdac = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 3);
					$cdac_contribution = 0;
					if ($cdac) {
						$cdac_contribution = (float)$cdac->contribution_amount;
					}

					//ecf
					$ecf = $this->Contribution_fund_model->getContributionByPayslipId($p->payslip_id, 4);
					$ecf_contribution = 0;
					if ($ecf) {
						$ecf_contribution = (float)$ecf->contribution_amount;
					}



					//employee detail
					$employee = $this->Employees_model->read_employee_information($p->employee_id);
					if ($employee) {
						$emp_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
						$emp_id_no = $employee[0]->id_no;

						if (strlen($emp_name) > 22) {
							$emp_name = substr($emp_name, 0, 22);
						}
					}

					//cpf
					if ($cpf_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($cpf_contribution)) {
							$str_l = strlen($cpf_contribution);
							$decimal_point_pos = strpos($cpf_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $cpf_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $cpf_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($cpf_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {

							$contribution_amount = str_pad($cpf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}

						if (is_float($ow)) {
							$str_l = strlen($ow);
							$decimal_point_pos = strpos($ow, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$ow_amount = str_replace('.', '', $ow);
									$ow_amount = str_pad($ow_amount, 10, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$ow_amount = str_replace('.', '', $ow);
									$ow_amount .= '0';
									$ow_amount = str_pad($ow_amount, 10, '0', STR_PAD_LEFT);
								}
							} else {
								$ow_amount = str_pad($ow, 8, '0', STR_PAD_LEFT);
								$ow_amount .= '00';
							}
						} else {
							$ow_amount = str_pad($ow, 8, '0', STR_PAD_LEFT);
							$ow_amount .= '00';
						}

						if (is_float($aw)) {
							$str_l = strlen($aw);
							$decimal_point_pos = strpos($aw, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$aw_amount = str_replace('.', '', $aw);
									$aw_amount = str_pad($aw_amount, 10, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$aw_amount = str_replace('.', '', $aw);
									$aw_amount .= '0';
									$aw_amount = str_pad($aw_amount, 10, '0', STR_PAD_LEFT);
								}
							} else {
								$aw_amount = str_pad($aw, 8, '0', STR_PAD_LEFT);
								$aw_amount .= '00';
							}
						} else {
							$aw_amount = str_pad($aw, 8, '0', STR_PAD_LEFT);
							$aw_amount .= '00';
						}

						$cpfh = 'F'; //submission mode
						$cpfh .= '1'; //Record Type
						$cpfh .= $this->input->post('csn'); //csn
						$cpfh .= ' '; //space
						$cpfh .= '01'; //AdiveCode
						$cpfh .= $contribution_month; //Month
						$cpfh .= '01'; //Payment Code
						$cpfh .= $emp_id_no; //Employee ID No
						$cpfh .= $contribution_amount; //contribution amount
						$cpfh .= $ow_amount; //ow amount
						$cpfh .= $aw_amount; //aw amount
						$cpfh .= 'E'; //existing employee
						$cpfh .= $emp_name; //employee name
						$detail_records .= $cpfh . "\n";
					}

					//mbmf
					if ($mbmf_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($mbmf_contribution)) {
							$str_l = strlen($mbmf_contribution);
							$decimal_point_pos = strpos($mbmf_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $mbmf_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {


									$contribution_amount = str_replace('.', '', $mbmf_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($mbmf_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($mbmf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$mbmfh = 'F'; //submission mode
						$mbmfh .= '1'; //Record Type
						$mbmfh .= $this->input->post('csn'); //csn
						$mbmfh .= ' '; //space
						$mbmfh .= '01'; //AdiveCode
						$mbmfh .= $contribution_month; //Month
						$mbmfh .= '02'; //Payment Code
						$mbmfh .= $emp_id_no; //Employee ID No
						$mbmfh .= $contribution_amount; //contribution amount
						$mbmfh .= str_pad('', 10, '0'); //ow amount
						$mbmfh .= str_pad('', 10, '0'); //aw
						$mbmfh .= ' '; //existing employee
						$mbmfh .= $emp_name; //employee name
						$detail_records .= $mbmfh . "\n";
					}

					//sinda
					if ($sinda_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($sinda_contribution)) {
							$str_l = strlen($sinda_contribution);
							$decimal_point_pos = strpos($sinda_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $sinda_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $sinda_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($sinda_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($sinda_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$sindah = 'F'; //submission mode
						$sindah .= '1'; //Record Type
						$sindah .= $this->input->post('csn'); //csn
						$sindah .= ' '; //space
						$sindah .= '01'; //AdiveCode
						$sindah .= $contribution_month; //Month
						$sindah .= '03'; //Payment Code
						$sindah .= $emp_id_no; //Employee ID No
						$sindah .= $contribution_amount; //contribution amount
						$sindah .= str_pad('', 10, '0'); //ow amount
						$sindah .= str_pad('', 10, '0'); //aw
						$sindah .= ' '; //existing employee
						$sindah .= $emp_name; //employee name
						$detail_records .= $sindah . "\n";
					}

					//cdac
					if ($cdac_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($cdac_contribution)) {
							$str_l = strlen($cdac_contribution);
							$decimal_point_pos = strpos($cdac_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $cdac_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $cdac_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($cdac_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($cdac_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$cdach = 'F'; //submission mode
						$cdach .= '1'; //Record Type
						$cdach .= $this->input->post('csn'); //csn
						$cdach .= ' '; //space
						$cdach .= '01'; //AdiveCode
						$cdach .= $contribution_month; //Month
						$cdach .= '04'; //Payment Code
						$cdach .= $emp_id_no; //Employee ID No
						$cdach .= $contribution_amount; //contribution amount
						$cdach .= str_pad('', 10, '0'); //ow amount
						$cdach .= str_pad('', 10, '0'); //aw
						$cdach .= ' '; //existing employee
						$cdach .= $emp_name; //employee name
						$detail_records .= $cdach . "\n";
					}

					//ecf
					if ($ecf_contribution > 0) {
						$contribution_month = str_replace('-', '', $formattedDate);
						if (is_float($ecf_contribution)) {
							$str_l = strlen($ecf_contribution);
							$decimal_point_pos = strpos($ecf_contribution, '.');
							if ($decimal_point_pos) {
								if (($str_l - $decimal_point_pos) == 3) {
									$contribution_amount = str_replace('.', '', $ecf_contribution);
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								} elseif (($str_l - $decimal_point_pos) == 2) {
									$contribution_amount = str_replace('.', '', $ecf_contribution);
									$contribution_amount .= '0';
									$contribution_amount = str_pad($contribution_amount, 12, '0', STR_PAD_LEFT);
								}
							} else {
								$contribution_amount = str_pad($ecf_contribution, 10, '0', STR_PAD_LEFT);
								$contribution_amount .= '00';
							}
						} else {
							$contribution_amount = str_pad($ecf_contribution, 10, '0', STR_PAD_LEFT);
							$contribution_amount .= '00';
						}


						$ecfh = 'F'; //submission mode
						$ecfh .= '1'; //Record Type
						$ecfh .= $this->input->post('csn'); //csn
						$ecfh .= ' '; //space
						$ecfh .= '01'; //AdiveCode
						$ecfh .= $contribution_month; //Month
						$ecfh .= '05'; //Payment Code
						$ecfh .= $emp_id_no; //Employee ID No
						$ecfh .= $contribution_amount; //contribution amount
						$ecfh .= str_pad('', 10, '0'); //ow amount
						$ecfh .= str_pad('', 10, '0'); //aw
						$ecfh .= ' '; //existing employee
						$ecfh .= $emp_name; //employee name
						$detail_records .= $ecfh . "\n";
					}
				}
				/* End Employer Contribution Detail Record*/

				/* Employer Trailer Record */
				$records = $employer_header . $summary_record . $detail_records;
				$no_records = substr_count($records, "\n");
				$total_cpf_amount = ($total_cpf_contribution + $total_mbmf_contribution + $total_sinda_contribution + $total_cdac_contribution + $total_ecf_contribution + $total_sdl_contribution);

				if (is_float($total_cpf_amount)) {
					$str_l = strlen($total_cpf_amount);
					$decimal_point_pos = strpos($total_cpf_amount, '.');
					if ($decimal_point_pos) {
						if (($str_l - $decimal_point_pos) == 3) {
							$contribution_amount = str_replace('.', '', $total_cpf_amount);
							$contribution_amount = str_pad($contribution_amount, 15, '0', STR_PAD_LEFT);
						} elseif (($str_l - $decimal_point_pos) == 2) {
							$contribution_amount = str_replace('.', '', $total_cpf_amount);
							$contribution_amount .= '0';
							$contribution_amount = str_pad($contribution_amount, 15, '0', STR_PAD_LEFT);
						}
					} else {
						$contribution_amount = str_pad($total_cpf_amount, 13, '0', STR_PAD_LEFT);
						$contribution_amount .= '00';
					}
				} else {
					$contribution_amount = str_pad($total_cpf_amount, 13, '0', STR_PAD_LEFT);
					$contribution_amount .= '00';
				}

				$emtr = 'F'; //submission mode
				$emtr .= '9'; //Record Type
				$emtr .= $this->input->post('csn'); //csn
				$emtr .= ' '; //space
				$emtr .= '01'; //AdiveCode
				$emtr .= $no_records + 1; //No of records
				$emtr .= $contribution_amount; //contribution amount

				$employer_trailer = $emtr;

				/* End Employer Trailer Record */

				$content = $employer_header . $summary_record . $detail_records . $employer_trailer;
				$dateString = $this->input->post('month_year');
				$originalDate = DateTime::createFromFormat('m-Y', $dateString);
				$formattedDate = $originalDate->format('Y-m');

				$month = Date('MY', strtotime($formattedDate));
				// $filename = './uploads/efiling/cpf/'. strtoupper($this->input->post('csn') . $month . '01.txt');
				$company = $this->Company_model->get_company_single($this->input->post('company_id'));
				$company = $company->result();
				if (!is_dir('./uploads/efiling/cpf/' . $company[0]->name)) {
					mkdir('./uploads/efiling/cpf/' . $company[0]->name, 0777);
				}
				$filename = './uploads/efiling/cpf/' . $company[0]->name . "/" . strtoupper($this->input->post('csn') . $month . '01.txt');

				$save_file = file_put_contents($filename, $content);
				if ($save_file) {
					$cpf_data = array(
						'company_id'	=> $this->input->post('company_id'),
						'csn' => $this->input->post('csn'),
						'month_year' => '01-' . $this->input->post('month_year'),
						'no_employees' => count($payslips),
						'no_records' => $no_records + 1,
						'cpf_contribution_amount' => $total_cpf_contribution,
						'other_contribution' => $total_cpf_amount - $total_cpf_contribution,
						'total_contribution_amount' => $total_cpf_amount,
						'cpf_file' => $filename,
						'created_by' => $this->input->post('user_id')
					);
					$cpf_submit = $this->Efiling_model->saveCpfSubmission($cpf_data);
					if ($cpf_submit) {
						$Return['result'] = 'CPF Submission File created successfully';
					} else {
						$Return['error'] = 'Error! Could not create CPF Submission file';
					}
				} else {
					$Return['error'] = 'Error! Could not create CPF Submission file';
				}
			} else {
				$Return['error'] = 'Payslips for the selected is not generated';
			}
			// $result = true;
			// if ($result == TRUE) {
			// 	$Return['result'] = 'CPF Submission File is generated successfully!';
			// } else {
			// 	$Return['error'] = $this->lang->line('xin_error_msg');
			// }
			$this->output($Return);
			exit;
		}
	}

	public function cpf_submission_list()
	{

		$session = $this->session->userdata('username');
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$system = $this->Xin_model->read_setting_info(1);
		$user_info = $this->Xin_model->read_user_info($session['user_id']);

		$cpf_submission = $this->Efiling_model->getCpfSubmissionData();

		$data = array();

		foreach ($cpf_submission->result() as $cs) {
			$file_url = base_url() . str_replace('./', '', $cs->cpf_file);

			$company = $this->Company_model->get_company_single($cs->company_id);
			$company = $company->result();

			$action = '<span data-toggle="tooltip" data-placement="right" title="" data-original-title="Download"><a href="' . $file_url . '" download><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			$data[] = array(
				$action,
				Date('M Y', strtotime($cs->month_year)),
				$company[0]->name,
				$cs->csn,
				$cs->no_employees,
				$cs->no_records,
				$cs->total_contribution_amount,
				Date('F j Y H:i:a', strtotime($cs->created_at))
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $cpf_submission->num_rows(),
			"recordsFiltered" => $cpf_submission->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function ir8a()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$year_a = '';
		$year = $this->uri->segment(5);
		$company = $this->uri->segment(6);
		if ($year) {
			$valid_year = (int)$year;
			$ayear = date('Y', strtotime("-6 year"));
			$fyear = date('Y', strtotime("+1 year"));

			if ($valid_year >= $ayear && $valid_year <= $fyear) {
				$year_a = $valid_year;
			} else {
				redirect('admin/efiling/ir8a');
			}
		}
		if ($year_a == '') {
			$year_a = date('Y');
		}
		$year_b = $year_a - 1;
		$data['title'] = 'IR8A';
		$data['breadcrumbs'] = 'IR8A';
		$data['path_url'] = 'ir8a_filing';
		$data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$data['year_a'] = $year_a;
		$data['company_id']	= $company;
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$isGenerated = $this->Efiling_model->getIr8aRecordByYear($year_b);
		$data['is_generated'] = $isGenerated;
		if (in_array('428', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/efiling/ir8a_filing", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function generateIr8a()
	{
		if ($this->input->post('type') == 'ir8a_generate_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}else if($this->input->post('company') === ''){
				$Return['error'] = 'Company is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;
			$company_id = $this->input->post('company');
			$employee_annual_pay = $this->Efiling_model->getEmployeesAnnualPay($year,$company_id);
			$emp_count = 0;
			//xml detail record
			$detail_record = "<Details>";
			$no_of_records = 0;
			$total_payment = 0;
			$total_salary = 0;
			$total_bonus = 0;
			$total_director_fees = 0;
			$total_others = 0;
			$total_exempt_income = 0;
			$total_income_tax_borne_employer = 0;
			$total_income_tax_borne_employee = 0;
			$total_donation = 0;
			$total_cpf = 0;
			$total_insurance = 0;
			$total_mbf = 0;
			$total_pension = 0;
			$total_gratuity = 0;
			$total_notice_pay = 0;
			$total_ex_gratia_payment = 0;
			$total_other_lump_sum = 0;
			$total_comp_loss_office = 0;

			if ($employee_annual_pay) {
				$submission_key =  random_string('alnum', 40);
				foreach ($employee_annual_pay as $e) {
					$jurl = random_string('alnum', 40);
					$edata = array(
						'ir8a_key' => $jurl,
						'submission_key' => $submission_key,
						'employee_id' => $e->employee_id,
						'ir8a_year' => $year
					);
					//gross salary
					$gross_salary = $e->basic_pay + $e->overtime_pay;
					$edata['gross_salary'] = $gross_salary;

					//allowance
					if ($e->transport_allowance != '') {
						$edata['allowance_transport'] = $e->transport_allowance;
					}
					if ($e->other_allowance != '') {
						$edata['allowance_other'] = $e->other_allowance;
					}
					if ($e->entertainment_allowance != '') {
						$edata['allowance_entertainment'] = $e->entertainment_allowance;
					}


					//commission
					if ($e->commission != '') {
						$edata['gross_commission'] = $e->commission;
					}

					// bonus 
					$bonus = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, 'Bonus');
					if ($bonus) {
						$total_bonus += $bonus->payments_amount;

						$getEmployeeOtherPaymentDate = $this->Efiling_model->getEmployeeOtherPaymentDate($e->employee_id, $year, 'Bonus');
						$edata['bonus_date'] = $getEmployeeOtherPaymentDate->date;
					}
					$edata['bonus'] = $total_bonus;

					// total_director_fees
					$director_fees = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Director's fee");
					if ($director_fees) {
						$total_director_fees += $director_fees->payments_amount;
						$getEmployeeOtherPaymentDate = $this->Efiling_model->getEmployeeOtherPaymentDate($e->employee_id, $year, "Director's fee");
						$edata['director_fee_date'] = $getEmployeeOtherPaymentDate->date;
					}
					$edata['director_fee'] = $total_director_fees;

					// pension
					$pension = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Pension");
					if ($pension) {
						$total_pension += $pension->payments_amount;
					}
					$edata['pension'] = $total_pension;


					// gratuity
					$gratuity = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Gratuity");
					if ($gratuity) {
						$total_gratuity += $gratuity->payments_amount;
					}
					$edata['gratuity'] = $total_gratuity;

					// notice_pay
					$notice_pay = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Notice Pay");
					if ($notice_pay) {
						$total_notice_pay += $notice_pay->payments_amount;
					}
					$edata['notice_pay'] = $total_notice_pay;

					// ex_gratia_payment
					$ex_gratia_payment = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Ex-gratia Payment");
					if ($ex_gratia_payment) {
						$total_ex_gratia_payment += $ex_gratia_payment->payments_amount;
					}
					$edata['ex_gratia_payment'] = $total_ex_gratia_payment;


					// other_lump_sum
					$other_lump_sum = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Other Lump Sum");
					if ($other_lump_sum) {
						$total_other_lump_sum += $other_lump_sum->payments_amount;
					}
					$edata['other_lump_sum'] = $total_other_lump_sum;


					// comp_loss_office
					$comp_loss_office = $this->Efiling_model->getEmployeeOtherPayment($e->employee_id, $year, "Compensation for loss of office");
					if ($comp_loss_office) {
						$total_comp_loss_office += $comp_loss_office->payments_amount;
					}
					$edata['comp_loss_office'] = $total_comp_loss_office;



					//accommodation
					$accommodation_amount = 0;
					$accommodation = $this->Efiling_model->getEmployeeAccommodation($e->employee_id, $year);
					if ($accommodation) {
						$ac_id = $accommodation->accommodation_id;
						$ac_type = $accommodation->accommodation_type;
						$rent_paid = $accommodation->rent_paid;
						$ac_from = new DateTime($accommodation->period_from);
						$ac_to = new DateTime($accommodation->period_to);
						$ac_days = $ac_to->diff($ac_from)->days;

						$shared_accommodation = $this->Efiling_model->getSharedAccommodationCount($accommodation->period_from, $accommodation->period_to, $ac_id);

						if ($ac_type == 1) {
							$ac_annual_value = $accommodation->annual_value;
							$ac_furnished = $accommodation->furnished_type;
							$annual_value = round(($ac_annual_value / $shared_accommodation) * ($ac_days / 365), 2);
							if ($ac_furnished == 1) {
								$furniture_value = round($annual_value * 50 / 100, 2);
							} else {
								$furniture_value = round($annual_value * 40 / 100, 2);
							}
							$accommodation_amount = round($annual_value + $furniture_value, 2);
							if ($rent_paid != '') {
								$accommodation_amount = round($accommodation_amount - $rent_paid, 2);
							}
						} else {
							$annual_rent_value = $accommodation->rent_value;
							$annual_value = round(($annual_rent_value / $shared_accommodation) * ($ac_days / 365), 2);
							$accommodation_amount = $annual_value;
							if ($rent_paid != '') {
								$accommodation_amount = $accommodation_amount - $rent_paid;
							}
						}
					}

					//utility
					$utilities_amount = 0;
					$utilities = $this->Efiling_model->getEmployeeUtilityBenefit($e->employee_id, $year);
					if ($utilities) {
						$utilities_amount = $utilities->utility_amount;
					}

					//driver
					$driver_benefit = 0;
					$driver = $this->Efiling_model->getEmployeeDriverBenefit($e->employee_id, $year);
					if ($driver) {
						$driver_benefit = $driver->driver_wage;
					}

					//housekeeping
					$housekeeping_benefit = 0;
					$housekeeping = $this->Efiling_model->getEmployeeHousekeepingBenefit($e->employee_id, $year);
					if ($housekeeping) {
						$housekeeping_benefit = $housekeeping->housekeeping_amount;
					}

					//hotel accommodation
					$hotel_accommodation_amount = 0;
					$hotel_accommodation = $this->Efiling_model->getEmployeeHotelAccommodationBenefit($e->employee_id, $year);
					if ($hotel_accommodation) {
						foreach ($hotel_accommodation as $ha) {
							$actual_cost = $ha->actual_cost;
							$employee_paid = $ha->employee_paid;
							if ($employee_paid != '') {
								$ha_amount = $actual_cost - $employee_paid;
								$hotel_accommodation_amount += $ha_amount;
							} else {
								$hotel_accommodation_amount += $actual_cost;
							}
						}
					}

					//other benefit
					$other_benefit_cost = 0;
					$other_benefit = $this->Efiling_model->getEmployeeOtherBenefit($e->employee_id, $year);
					if ($other_benefit) {
						$other_benefit_cost = $other_benefit->other_benefit_cost;
					}

					//total benefits
					$benefits = $accommodation_amount + 
					$utilities_amount + $driver_benefit +
					 $housekeeping_benefit + $hotel_accommodation_amount +
					  $other_benefit_cost + $total_pension
					  + $total_gratuity + $total_notice_pay + $total_ex_gratia_payment + $total_other_lump_sum + $total_comp_loss_office;

					if ($benefits > 0) {
						$edata['benefits_in_kind_ap8a'] = $benefits;
					}


					//share options
					$stock_gains_before_2003 = 0;
					$gains_before_2003 = $this->Efiling_model->getEmployeeGainsGrantedBefore2003($e->employee_id, $year);
					if ($gains_before_2003) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($gains_before_2003 as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;

								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$stock_gains_before_2003 = round($eebr_amount + $eris_amount, 2);
					}

					$stock_gains_after_2003 = 0;
					$gains_after_2003 = $this->Efiling_model->getEmployeeGainsGrantedAfter2003($e->employee_id, $year);
					if ($gains_after_2003) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($gains_after_2003 as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;

								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$stock_gains_after_2003 = round($eebr_amount + $eris_amount, 2);
					}

					$total_stock_gains = round($stock_gains_before_2003 + $stock_gains_after_2003, 2);
					if ($total_stock_gains) {
						$edata['stock_gains_ap8b'] = $total_stock_gains;
					}

					//total others d1 to d9
					$total_d1_to_d9 = $e->transport_allowance + $e->other_allowance + $e->commission + $benefits + $total_stock_gains + $e->entertainment_allowance;

					$edata['total_d1_to_d9'] = $total_d1_to_d9;

					$cpf_employee = round($e->tow_cpf_employee + $e->taw_cpf_employee, 2);

					$edata['cpf_employee_deduction'] = $cpf_employee;

					if ($e->contribution != '') {
						$edata['donation_funds'] = $e->contribution;
					}

					if ($e->mbmf_contribution != '') {
						$edata['mbmf_funds'] = $e->mbmf_contribution;
					}

					$employee = $this->Xin_model->read_user_info($e->employee_id);
					$emp_id_type = $employee[0]->id_type;
					$emp_id = $employee[0]->id_no;
					$emp_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
					$nationality = $this->Employees_model->getEmployeeNationality($employee[0]->user_id);
					if ($nationality) {
						$employee_nationality = '<Nationality xmlns="http://www.iras.gov.sg/IR8A">' . $nationality->iras_nationality_code . '</Nationality>';
					} else {
						$employee_nationality = '<Nationality xmlns="http://www.iras.gov.sg/IR8A"/>';
					}
					$emp_gender = ($employee[0]->gender == 'Male') ? 'M' : 'F';
					$emp_dob = date('Ymd', strtotime($employee[0]->date_of_birth));
					$total_d1_to_d9 = round($total_d1_to_d9);
					$amount = round($gross_salary + $total_d1_to_d9);
					$doj = $employee[0]->date_of_joining;
					$dol = $employee[0]->date_of_leaving;
					$year_end = intval($this->input->post('year')) - 1;
					$fa_year = $this->input->post('year');
					if ($doj) {
						$doy = date('Y', strtotime($doj));
						if ($doy == $year_end) {
							$period_from = date('Ymd', strtotime($doj));
							$employee_date_commence = '<CommencementDate xmlns="http://www.iras.gov.sg/IR8A">' . date('Ymd', strtotime($doj)) . '</CommencementDate>';
						} else {
							$period_from = $year_end . '0101';
							$employee_date_commence = '<CommencementDate xmlns="http://www.iras.gov.sg/IR8A"/>';
						}
					} else {
						$period_from = $year_end . '0101';
						$employee_date_commence = '<CommencementDate xmlns="http://www.iras.gov.sg/IR8A"/>';
					}

					if ($dol) {
						$doy = date('Y', strtotime($dol));
						if ($doy == $year_end) {
							$period_to = date('Ymd', strtotime($dol));
							$employee_date_cessation = '<CessationDate xmlns="http://www.iras.gov.sg/IR8A">' . date('Ymd', strtotime($dol)) . '</CessationDate>';
						} else {
							$period_to = $year_end . '1231';
							$employee_date_cessation = '<CessationDate xmlns="http://www.iras.gov.sg/IR8A"/>';
						}
					} else {
						$period_to = $year_end . '1231';
						$employee_date_cessation = '<CessationDate xmlns="http://www.iras.gov.sg/IR8A"/>';
					}
					$mbf = ($e->mbmf_contribution) ? ($e->mbmf_contribution) : '0.0';
					$donations = ($e->contribution) ? ($e->contribution) : '0.0';
					$benefits_indicator = ($benefits > 0) ? '<BenefitsInKind xmlns="http://www.iras.gov.sg/IR8A">Y</BenefitsInKind>' : '<BenefitsInKind xmlns="http://www.iras.gov.sg/IR8A"/>';
					if ($e->commission) {
						$gross_commission = '<GrossCommissionAmount xmlns="http://www.iras.gov.sg/IR8A">' . $e->commission . '</GrossCommissionAmount>';
						$gc_from = '<GrossCommissionPeriodFrom xmlns="http://www.iras.gov.sg/IR8A">' . $period_from . '</GrossCommissionPeriodFrom>';
						$gc_to = '<GrossCommissionPeriodTo xmlns="http://www.iras.gov.sg/IR8A">' . $period_to . '</GrossCommissionPeriodTo>';
						$gc_indicator = '<GrossCommissionIndicator xmlns="http://www.iras.gov.sg/IR8A">B</GrossCommissionIndicator>';
					} else {
						$gross_commission = '<GrossCommissionAmount xmlns="http://www.iras.gov.sg/IR8A">0.0</GrossCommissionAmount>';
						$gc_from = '<GrossCommissionPeriodFrom xmlns="http://www.iras.gov.sg/IR8A"/>';
						$gc_to = '<GrossCommissionPeriodTo xmlns="http://www.iras.gov.sg/IR8A"/>';
						$gc_indicator = '<GrossCommissionIndicator xmlns="http://www.iras.gov.sg/IR8A"/>';
					}
					$t_allowance = ($e->transport_allowance) ? $e->transport_allowance : '0.0';
					$ot_allowance = ($e->other_allowance) ? $e->other_allowance : '0.0';
					$designation = $this->Designation_model->read_designation_information($employee[0]->designation_id);
					if (!is_null($designation)) {
						$employee_designation = '<Designation xmlns="http://www.iras.gov.sg/IR8A">' . $designation[0]->designation_name . '</Designation>';
					} else {
						$employee_designation = '<Designation xmlns="http://www.iras.gov.sg/IR8A">';
					}

					$cpf = round($cpf_employee);

					$ir8a_result = $this->Efiling_model->setIr8aEmployee($edata);
					if ($ir8a_result) {
						$emp_count += 1;
					}

					//detail
					$dt = <<<EOT
					<IR8ARecord>
					<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">
					<IR8AST>
					<RecordType xmlns="http://www.iras.gov.sg/IR8A">1</RecordType>
					<IDType xmlns="http://www.iras.gov.sg/IR8A">$emp_id_type</IDType>
					<IDNo xmlns="http://www.iras.gov.sg/IR8A">$emp_id</IDNo>
					<NameLine1 xmlns="http://www.iras.gov.sg/IR8A">$emp_name</NameLine1>
					<NameLine2 xmlns="http://www.iras.gov.sg/IR8A"/>
					<AddressType xmlns="http://www.iras.gov.sg/IR8A"/>
					<BlockNo xmlns="http://www.iras.gov.sg/IR8A"/>
					<StName xmlns="http://www.iras.gov.sg/IR8A"/>
					<LevelNo xmlns="http://www.iras.gov.sg/IR8A"/>
					<UnitNo xmlns="http://www.iras.gov.sg/IR8A"/>
					<PostalCode xmlns="http://www.iras.gov.sg/IR8A"/>
					<AddressLine1 xmlns="http://www.iras.gov.sg/IR8A"/>
					<AddressLine2 xmlns="http://www.iras.gov.sg/IR8A"/>
					<AddressLine3 xmlns="http://www.iras.gov.sg/IR8A"/>
					<TX_UF_POSTAL_CODE xmlns="http://www.iras.gov.sg/IR8A"/>
					<CountryCode xmlns="http://www.iras.gov.sg/IR8A"/>
					$employee_nationality
					<Sex xmlns="http://www.iras.gov.sg/IR8A">$emp_gender</Sex>
					<DateOfBirth xmlns="http://www.iras.gov.sg/IR8A">$emp_dob</DateOfBirth>
					<Amount xmlns="http://www.iras.gov.sg/IR8A">$amount</Amount>
					<PaymentPeriodFromDate xmlns="http://www.iras.gov.sg/IR8A">$period_from</PaymentPeriodFromDate>
					<PaymentPeriodToDate xmlns="http://www.iras.gov.sg/IR8A">$period_to</PaymentPeriodToDate>
					<MBF xmlns="http://www.iras.gov.sg/IR8A">$mbf</MBF>
					<Donation xmlns="http://www.iras.gov.sg/IR8A">$donations</Donation>
					<CPF xmlns="http://www.iras.gov.sg/IR8A">$cpf</CPF>
					<Insurance xmlns="http://www.iras.gov.sg/IR8A">0.0</Insurance>
					<Salary xmlns="http://www.iras.gov.sg/IR8A">$gross_salary</Salary>
					<Bonus xmlns="http://www.iras.gov.sg/IR8A">0.0</Bonus>
					<DirectorsFees xmlns="http://www.iras.gov.sg/IR8A">0.0</DirectorsFees>
					<Others xmlns="http://www.iras.gov.sg/IR8A">$total_d1_to_d9</Others>
					<ShareOptionGainsS101g xmlns="http://www.iras.gov.sg/IR8A">$stock_gains_before_2003</ShareOptionGainsS101g>
					<ExemptIncome xmlns="http://www.iras.gov.sg/IR8A">0.0</ExemptIncome>
					<IncomeForTaxBorneByEmployer xmlns="http://www.iras.gov.sg/IR8A">0.0</IncomeForTaxBorneByEmployer>
					<IncomeForTaxBorneByEmployee xmlns="http://www.iras.gov.sg/IR8A">0</IncomeForTaxBorneByEmployee>
					$benefits_indicator
					<S45Applicable xmlns="http://www.iras.gov.sg/IR8A"/>
					<IncomeTaxBorneByEmployer xmlns="http://www.iras.gov.sg/IR8A"/>
					<GratuityNoticePymExGratiaPaid xmlns="http://www.iras.gov.sg/IR8A"/>
					<CompensationRetrenchmentBenefitsPaid xmlns="http://www.iras.gov.sg/IR8A"/>
					<ApprovalObtainedFromIRAS xmlns="http://www.iras.gov.sg/IR8A"/>
					<ApprovalDate xmlns="http://www.iras.gov.sg/IR8A"/>
					<CessationProvisions xmlns="http://www.iras.gov.sg/IR8A"/>
					<IR8SApplicable xmlns="http://www.iras.gov.sg/IR8A"/>
					<ExemptOrRemissionIncomeIndicator xmlns="http://www.iras.gov.sg/IR8A"/>
					<CompensationAndGratuity xmlns="http://www.iras.gov.sg/IR8A"/>
					$gross_commission
					$gc_from
					$gc_to
					$gc_indicator
					<Pension xmlns="http://www.iras.gov.sg/IR8A">0.0</Pension>
					<TransportAllowance xmlns="http://www.iras.gov.sg/IR8A">$t_allowance</TransportAllowance>
					<EntertainmentAllowance xmlns="http://www.iras.gov.sg/IR8A">0.0</EntertainmentAllowance>
					<OtherAllowance xmlns="http://www.iras.gov.sg/IR8A">$ot_allowance</OtherAllowance>
					<GratuityNoticePymExGratia xmlns="http://www.iras.gov.sg/IR8A">0.0</GratuityNoticePymExGratia>
					<RetrenchmentBenefits xmlns="http://www.iras.gov.sg/IR8A">0.0</RetrenchmentBenefits>
					<RetrenchmentBenefitsUpto311292 xmlns="http://www.iras.gov.sg/IR8A">0.0</RetrenchmentBenefitsUpto311292>
					<RetrenchmentBenefitsFrom1993 xmlns="http://www.iras.gov.sg/IR8A">0.0</RetrenchmentBenefitsFrom1993>
					<EmployerContributionToPensionOrPFOutsideSg xmlns="http://www.iras.gov.sg/IR8A">0.0</EmployerContributionToPensionOrPFOutsideSg>
					<ExcessEmployerContributionToCPF xmlns="http://www.iras.gov.sg/IR8A">0.0</ExcessEmployerContributionToCPF>
					<ShareOptionGainsS101b xmlns="http://www.iras.gov.sg/IR8A">$stock_gains_after_2003</ShareOptionGainsS101b>
					<BenefitsInKindValue xmlns="http://www.iras.gov.sg/IR8A">$benefits</BenefitsInKindValue>
					<EmployeesVoluntaryContributionToCPF xmlns="http://www.iras.gov.sg/IR8A">0.0</EmployeesVoluntaryContributionToCPF>
					$employee_designation
					$employee_date_commence
					$employee_date_cessation
					<BonusDecalrationDate xmlns="http://www.iras.gov.sg/IR8A"/>
					<DirectorsFeesApprovalDate xmlns="http://www.iras.gov.sg/IR8A"/>
					<RetirementBenefitsFundName xmlns="http://www.iras.gov.sg/IR8A"/>
					<DesignatedPensionOrProvidentFundName xmlns="http://www.iras.gov.sg/IR8A">CPF</DesignatedPensionOrProvidentFundName>
					<BankName xmlns="http://www.iras.gov.sg/IR8A"/>
					<PayrollDate xmlns="http://www.iras.gov.sg/IR8A"/>
					<Filler xmlns="http://www.iras.gov.sg/IR8A"/>
					<GratuityOrCompensationDetailedInfo xmlns="http://www.iras.gov.sg/IR8A"/>
					<ShareOptionGainsDetailedInfo xmlns="http://www.iras.gov.sg/IR8A"/>
					<Remarks xmlns="http://www.iras.gov.sg/IR8A"/>
					</IR8AST>
					</ESubmissionSDSC>
					</IR8ARecord>
					EOT;
					$dt = preg_replace("/\r|\n/", "", $dt);
					$detail_record .= $dt;

					//trailer
					$no_of_records += 1;
					$total_payment += $amount;
					$total_salary += $gross_salary;
					$total_others += $total_d1_to_d9;
					$total_donation += $donations;
					$total_cpf += $cpf_employee;
					$total_mbf += $mbf;
				}
				$detail_record .= "</Details>";
				$efiling_d = $this->Efiling_model->getEFilingDetails();
				$creation_date = date('Ymd');
				//XML File creation
				$xml_file_header = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><IR8A xmlns="http://www.iras.gov.sg/IR8ADef">';

				$eh = "<IR8AHeader>";
				$eh .= '<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">';
				$eh .= "<FileHeaderST>";
				$eh .= "<RecordType>0</RecordType>";
				$eh .= "<Source>6</Source>";
				$eh .= "<BasisYear>$year</BasisYear>";
				$eh .= "<PaymentType>08</PaymentType>";
				$eh .= "<OrganizationID>$efiling_d->organisation_id_type</OrganizationID>";
				$eh .= "<OrganizationIDNo>$efiling_d->organisation_id_no</OrganizationIDNo>";
				$eh .= "<AuthorisedPersonName>$efiling_d->authorised_name</AuthorisedPersonName>";
				$eh .= "<AuthorisedPersonDesignation>$efiling_d->authorised_designation</AuthorisedPersonDesignation>";
				$eh .= "<EmployerName>$efiling_d->authorised_name</EmployerName>";
				$eh .= "<Telephone>$efiling_d->authorised_name</Telephone>";
				$eh .= "<AuthorisedPersonEmail>$efiling_d->authorised_email</AuthorisedPersonEmail>";
				$eh .= "<BatchIndicator>O</BatchIndicator>";
				$eh .= "<BatchDate>$creation_date</BatchDate>";
				$eh .= "<DivisionOrBranchName/>";
				$eh .= "</FileHeaderST>";
				$eh .= "</ESubmissionSDSC>";
				$eh .= "</IR8AHeader>";

				$total_cpf = round($total_cpf);
				$trailer_record = <<<EOT
				<IR8ATrailer>
				<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">
				<IR8ATrailerST>
				<RecordType>2</RecordType>
				<NoOfRecords>$no_of_records</NoOfRecords>
				<TotalPayment>$total_payment</TotalPayment>
				<TotalSalary>$total_salary</TotalSalary>
				<TotalBonus>$total_bonus</TotalBonus>
				<TotalDirectorsFees>$total_director_fees</TotalDirectorsFees>
				<TotalOthers>$total_others</TotalOthers>
				<TotalExemptIncome>$total_exempt_income</TotalExemptIncome>
				<TotalIncomeForTaxBorneByEmployer>$total_income_tax_borne_employer</TotalIncomeForTaxBorneByEmployer>
				<TotalIncomeForTaxBorneByEmployee>$total_income_tax_borne_employee</TotalIncomeForTaxBorneByEmployee>
				<TotalDonation>$total_donation</TotalDonation>
				<TotalCPF>$total_cpf</TotalCPF>
				<TotalInsurance>$total_insurance</TotalInsurance>
				<TotalMBF>$total_mbf</TotalMBF>
				<Filler/>
				</IR8ATrailerST>
				</ESubmissionSDSC>
				</IR8ATrailer>
				EOT;
				$trailer_record = preg_replace("/\r|\n/", "", $trailer_record);
				$xml_file_trailer = "</IR8A>";

				$content = $xml_file_header . $eh . $detail_record . $trailer_record . $xml_file_trailer;

				// $filename = './uploads/efiling/ir8a/ir8a-' . date('YmdHis') . '.xml';

				// $save_file = file_put_contents($filename, $content);
				// if ($save_file) {
					$ir8a_data = array(
						'efiling_id' => $efiling_d->id,
						'submission_key' => $submission_key,
						'basis_year' => $year_end,
						'no_of_records' => $no_of_records,
						'total_payment' => $total_payment,
						'total_salary' => $total_salary,
						'ir8a_file' => 'filename'
					);
					if ($total_bonus > 0) {
						$ir8a_data['total_bonus'] = $total_bonus;
					}
					if ($total_director_fees > 0) {
						$ir8a_data['total_director_fee'] = $total_director_fees;
					}
					if ($total_others > 0) {
						$ir8a_data['total_other'] = $total_others;
					}
					if ($total_exempt_income > 0) {
						$ir8a_data['total_exempt_income'] = $total_exempt_income;
					}
					if ($total_income_tax_borne_employer > 0) {
						$ir8a_data['total_tax_borne_employer'] = $total_income_tax_borne_employer;
					}
					if ($total_income_tax_borne_employee > 0) {
						$ir8a_data['total_tax_borne_employee'] = $total_income_tax_borne_employee;
					}
					if ($total_donation > 0) {
						$ir8a_data['total_donation'] = $total_donation;
					}
					if ($total_cpf > 0) {
						$ir8a_data['total_cpf'] = $total_cpf;
					}
					if ($total_insurance > 0) {
						$ir8a_data['total_insurance'] = $total_insurance;
					}
					if ($total_mbf > 0) {
						$ir8a_data['total_mbf'] = $total_mbf;
					}
					$ir8a_submit = $this->Efiling_model->saveIr8aRecords($ir8a_data);
					if($ir8a_submit){
						$this->ir8a_excel($year,$company_id);
					}	
				// }
				
				$Return['result'] = 'IR8A form generated for ' . $emp_count . ' employees';
			} else {
				$Return['error'] = 'No employees eligible for IR8A';
			}
			$this->output($Return);
			exit;
		}
	}

	public function employeeSummary($year)
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/efiling/ir8a_filing", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));




		$data = array();

		$employee_summaries = $this->Efiling_model->get_all_employee_payslip_summary($year);
		if ($employee_summaries) {
			foreach ($employee_summaries->result() as $s) {

				// $action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $eu->id . '" data-field_type="salary_allowance"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $eu->id . '" data-token_type="all_allowances"><span class="fa fa-trash"></span></button></span>';
				$total_cpf = $s->tow_cpf_employee + $s->tow_cpf_employer + $s->taw_cpf_employee + $s->taw_cpf_employer;

				$data[] = array(
					$s->first_name . ' ' . $s->last_name,
					$s->country_name,
					$this->Xin_model->currency_sign($s->tgross_salary),
					$this->Xin_model->currency_sign($s->tgross_salary),
					$this->Xin_model->currency_sign('0'),
					$this->Xin_model->currency_sign($total_cpf)
				);
			}
		}


		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_summaries->num_rows(),
			"recordsFiltered" => $employee_summaries->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function employeeIr8aForm($year)
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/efiling/ir8a_filing", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$ir8a_year = $year - 1;

		$data = array();

		$employee_ir8a = $this->Efiling_model->getIR8AEmployees($ir8a_year);
		if ($employee_ir8a) {
			foreach ($employee_ir8a->result() as $e) {

				$action = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
				<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $e->id . '" data-field_type="salary_allowance">
				<span class="fa fa-pencil"></span></button></span>';

				$action .= '<span data-toggle="tooltip" data-placement="top" title="Download IR8A PDF">
				<a href="' . site_url() . 'admin/efiling/ir8a_pdf/p/' . $e->ir8a_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				$action .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $e->id . '" data-token_type="all_allowances"><span class="fa fa-trash"></span></button></span>';


				$data[] = array(
					$action,
					$e->first_name . ' ' . $e->last_name,
					$this->Xin_model->currency_sign($e->gross_salary),
					$this->Xin_model->currency_sign($e->bonus),
					$this->Xin_model->currency_sign($e->director_fee),
					$this->Xin_model->currency_sign($e->total_d1_to_d9),
					$this->Xin_model->currency_sign($e->cpf_employee_deduction)
				);
			}
		}


		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_ir8a->num_rows(),
			"recordsFiltered" => $employee_ir8a->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function ir8a_pdf()
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$key = $this->uri->segment(5);
		$ir8a = $this->Efiling_model->getIR8AByKey($key);
		if (!$ir8a) {
			redirect('admin/efiling/ir8a');
		}
		$user = $this->Xin_model->read_user_info($ir8a->employee_id);

		$efiling_d = $this->Efiling_model->getEFilingDetails();

		$nationality = $this->Employees_model->getEmployeeNationality($user[0]->user_id);
		if ($nationality) {
			$employee_nationality = $nationality->country_name;
		} else {
			$employee_nationality = '';
		}

		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$employee_designation = $designation[0]->designation_name;
		} else {
			$employee_designation = '';
		}

		//variable
		$year_end = $ir8a->ir8a_year;
		$fa_year = intval($ir8a->ir8a_year) + 1;
		$employer_tax_ref = $efiling_d->organisation_id_no;
		$employee_tax_ref = $user[0]->id_no;
		$employee_name = $user[0]->first_name . ' ' . $user[0]->last_name;
		$employee_dob = date('d/m/y', strtotime($user[0]->date_of_birth));
		$employee_gender = $user[0]->gender;
		if ($user[0]->address) {
			$employee_address = $user[0]->address;
		} else {
			$employee_address = '';
		}
		if ($user[0]->state) {
			$employee_address .= ', ' . $user[0]->state;
		}
		if ($user[0]->city) {
			$employee_address .= ', ' . $user[0]->city;
		}

		$doj = $user[0]->date_of_joining;
		$dol = $user[0]->date_of_leaving;

		if ($doj) {
			$doy = date('Y', strtotime($doj));
			if ($doy == $year_end) {
				$employee_date_commence = date('d/m/y', strtotime($doj));
			} else {
				$employee_date_commence = '';
			}
		} else {
			$employee_date_commence = '';
		}

		if ($dol) {
			$doy = date('Y', strtotime($dol));
			if ($doy == $year_end) {
				$employee_date_cessation = date('d/m/y', strtotime($dol));
			} else {
				$employee_date_cessation = '';
			}
		} else {
			$employee_date_cessation = '';
		}

		$gross_salary = ($ir8a->gross_salary) ? $ir8a->gross_salary : 'NA';
		$bonus = ($ir8a->bonus) ? $ir8a->bonus : 'NA';
		$director_fee = ($ir8a->director_fee) ? $ir8a->director_fee : 'NA';
		$allowance_transport = ($ir8a->allowance_transport) ? $ir8a->allowance_transport : '0.00';
		$allowance_entertainment = ($ir8a->allowance_entertainment) ? $ir8a->allowance_entertainment : '0.00';
		$allowance_other = ($ir8a->allowance_other) ? $ir8a->allowance_other : '0.00';
		$total_allowance = $allowance_transport + $allowance_entertainment + $allowance_other;
		$gross_commission = ($ir8a->gross_commission) ? $ir8a->gross_commission : 'NA';

		if ($gross_commission != 'NA') {
			$commission_from = ($employee_date_commence) ? $employee_date_commence : '01/01/' . $year_end;
			$commission_to = ($employee_date_cessation) ? $employee_date_cessation : '31/12/' . $year_end;
		} else {
			$commission_from = '';
			$commission_to = '';
		}

		$pension = ($ir8a->pension) ? $ir8a->pension : 'NA';
		$gratuity = ($ir8a->gratuity) ? $ir8a->gratuity : '0.00';
		$notice_pay = ($ir8a->notice_pay) ? $ir8a->notice_pay : '0.00';
		$ex_gratia_payment = ($ir8a->ex_gratia_payment) ? $ir8a->ex_gratia_payment : '0.00';
		$other_lump_sum = ($ir8a->other_lump_sum) ? $ir8a->other_lump_sum : '0.00';
		$office_loss = ($ir8a->comp_loss_office) ? $ir8a->comp_loss_office : '0.00';
		$toal_lump_sum = $gratuity + $notice_pay + $ex_gratia_payment + $other_lump_sum + $office_loss;
		$lump_sum_total = ($toal_lump_sum > 0) ?  $toal_lump_sum : 'NA';
		$approval_iras = ($ir8a->approval_iras) ? '*<strike>Yes</strike>/No' : '*Yes/<strike>No</strike>';
		$office_loss_date_approval = ($ir8a->date_of_approval) ? date('d/m/y', strtotime($ir8a->date_of_approval)) : '';
		$reason_for_payment = ($ir8a->reason_for_payment) ? $ir8a->reason_for_payment : '';
		$length_of_service = ($ir8a->length_of_service) ? $ir8a->length_of_service : '';
		$basis_of_payment = ($ir8a->basis_of_payment) ? $ir8a->basis_of_payment : '';

		$retirement_benefits_fund_name = ($ir8a->retirement_benefits_fund_name) ? $ir8a->retirement_benefits_fund_name : '';
		$amount_upto_1992 = ($ir8a->amount_upto_1992) ? $ir8a->amount_upto_1992 : '0.00';
		$amount_from_1993 = ($ir8a->amount_from_1993) ? $ir8a->amount_from_1993 : '0.00';
		$retirement_benefits = $amount_upto_1992 + $amount_from_1993;
		$retirement_benefits = ($retirement_benefits > 0) ? $retirement_benefits : 'NA';
		$overseas_provident_fund = ($ir8a->overseas_provident_fund) ? $ir8a->overseas_provident_fund : '';
		$full_contribution_amount = ($ir8a->full_contribution_amount) ? $ir8a->full_contribution_amount : '';
		// $contribution_mandatory = ($ir8a->contribution_mandatory) ? $ir8a->contribution_mandatory : '';
		// $contribution_claimed = ($ir8a->contribution_claimed) ? $ir8a->contribution_claimed : '';
		$excess_cpf_contribution_ir8s = ($ir8a->excess_cpf_contribution_ir8s) ? $ir8a->excess_cpf_contribution_ir8s : 'NA';
		$stock_gains_ap8b = ($ir8a->stock_gains_ap8b) ? $ir8a->stock_gains_ap8b : 'NA';
		$benefits_in_kind_ap8a = ($ir8a->benefits_in_kind_ap8a) ? $ir8a->benefits_in_kind_ap8a : 'NA';
		$total_d1_to_d9 = ($ir8a->total_d1_to_d9) ? $ir8a->total_d1_to_d9 : 'NA';
		$remission_amount = ($ir8a->remission_amount) ? $ir8a->remission_amount : '';
		// $overseas_posting = ($ir8a->overseas_posting) ? $ir8a->overseas_posting : 'NA';
		$exempt_income = ($ir8a->exempt_income) ? $ir8a->exempt_income : '';
		$tax_borne_by_employer = ($ir8a->tax_borne_by_employer) ? '*<strike>Yes</strike>/No' : '*Yes/<strike>No</strike>';
		$partial_tax_amount = ($ir8a->partial_tax_amount) ? $ir8a->partial_tax_amount : 'NA';
		$fixed_tax_amount = ($ir8a->fixed_tax_amount) ? $ir8a->fixed_tax_amount : 'NA';
		$cpf_employee_deduction = ($ir8a->cpf_employee_deduction) ? $ir8a->cpf_employee_deduction : 'NA';
		$donation_funds = ($ir8a->donation_funds) ? $ir8a->donation_funds : 'NA';
		$mbmf_funds = ($ir8a->mbmf_funds) ? $ir8a->mbmf_funds : 'NA';
		$life_insurance = ($ir8a->life_insurance) ? $ir8a->life_insurance : 'NA';

		$company = $this->Xin_model->read_company_info($user[0]->company_id);

		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '';
			}
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} else {
			$company_name = '';
			$address_1 = '';
			$address_2 = '';
			$city = '';
			$state = '';
			$zipcode = '';
			$country_name = '';
			$c_info_email = '';
			$c_info_phone = '';
		}
		$company_address = '';

		if ($address_1) {
			$company_address .= $address_1;
		}
		if ($address_2) {
			$company_address .= ', ' . $address_2;
		}
		if ($city) {
			$company_address .= ', ' . $city;
		}
		if ($state) {
			$company_address .= ', ' . $state;
		}
		if ($country_name) {
			$company_address .= ', ' . $country_name;
		}
		if ($zipcode) {
			$company_address .= ' - ' . $zipcode;
		}

		$employer_authorised_person = $efiling_d->authorised_name;
		$authorised_person_designation = $efiling_d->authorised_designation;
		$authorised_person_phone = $efiling_d->authorised_phone;

		$date_created = date('d/m/y', strtotime($ir8a->created_at));

		// set document information
		$pdf->SetCreator('HRSALE');
		$pdf->SetAuthor('HRSALE');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont('courier');

		// set margins
		$pdf->SetMargins(10.41, 6, 4.83);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		// convert TTF font to TCPDF format and store it on the fonts folder
		$arial_bold = TCPDF_FONTS::addTTFfont(FCPATH . '/application/libraries/tcpdf/fonts/ARIALBD.TTF', 'TrueTypeUnicode', '', 96);
		$arial = TCPDF_FONTS::addTTFfont(FCPATH . '/application/libraries/tcpdf/fonts/ARIAL.TTF', 'TrueTypeUnicode', '', 96);

		$pdf->AddPage();

		$tbl = '
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="40%" style="font-size:20px; font-weight: 300">
					<b>' . $fa_year . '</b>
				</td>
				<td width="60%" style="font-size:16px; vertical-align:bottom">
					<b>FORM IR8A</b>			
				</td>
			</tr>
		</table>';

		$pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table cellpadding="0" cellspacing="0" border="0" bgcolor="#000000" style="font-size:8px; color:#fff">
			<tr>
				<td align="center">
					Return of Employee\'s Remuneration for the Year Ended 31 Dec ' . $year_end . '
				</td>
			</tr>
			<tr>
				<td align="center">
					Fill in this form and give it to your employee by 1 Mar ' . $fa_year . '
				</td>
			</tr>
			<tr>
				<td align="center">
					(DO NOT SUBMIT THIS FORM TO IRAS UNLESS REQUESTED TO DO SO)
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table style="font-size:8px;">
			<tr>
				<td>
					<table cellpadding="4">
						<tr>
							<td>This Form will take about 10 minutes to complete. Please get ready the employee’s personal particulars and details of his/her employment income. Please read the explanatory notes when completing this form.</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table style="font-size:8px;" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table style="border-collapse: collapse">
						<tr>
							<td width="45%" border="0.5">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											Employer’s Tax Ref. No. / UEN
											<br>
											<span style="font-size: 9px">' . $employer_tax_ref . '</span>
										</td>
									</tr>
								</table>	
							</td>
							<td width="55%" border="0.5">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											Employee’s Tax Ref. No. : *NRIC / FIN (Foreign Identification No.)
											<br>
											<span style="font-size: 9px">' . $employee_tax_ref . '</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="45%" border="0.5">
								<table cellpadding="0">
									<tr>
										<td width="2%"></td>
										<td width="98%">
											Full Name of Employee as per NRIC / FIN
											<br />
											<span style="font-size: 8px">' . $employee_name . '</span>
										</td>
									</tr>
								</table>
								
							</td>
							<td width="55%" border="0.5">
								<table cellpadding="0">
									<tr>
										<td width="40%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="98%">
														Date of Birth
														<br>
														<span style="font-size: 8px">' . $employee_dob . '</span>
													</td>
												</tr>
											</table>
										</td>
										<td style="border-left: 0.5px solid black" width="60%">
											<table>
												<tr>
													<td width="35%">
														<table>
															<tr>
																<td width="8px"></td>
																<td width="90%">
																	Sex
																	<br>
																	<span style="font-size: 8px">' . $employee_gender . '</span>
																</td>
															</tr>
														</table>
													</td>
													<td style="border-left: 0.5px solid black" width="65%">
														<table>
															<tr>
																<td width="8px"></td>
																<td width="90%">
																	Nationality
																	<br>
																	<span style="font-size: 8px">' . $employee_nationality . '</span>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="45%" border="0.5">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											Residential Address
											<br>
											<span style="font-size: 8px">' . $employee_address . '</span>
										</td>
									</tr>
								</table>
								
							</td>
							<td width="55%" border="0.5">
								<table cellpadding="0">
									<tr>
										<td width="40%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="98%">
														Designation
														<br>
														<span style="font-size: 8px">' . $employee_designation . '</span>
													</td>
												</tr>
											</table>
										</td>
										
										<td style="border-left: 0.5px solid black;" width="60%">
											<table>
												<tr>
													<td width="8px"></td>
													<td width="90%">
														Bank to which salary is credited
														<br>
														<span style="font-size: 9px">&nbsp;</span>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="45%" border="0.5">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											If employment commenced and/or ceased during the year, state:
											<br>
											<span style="font-weight: 700">(See Explanatory Note 5)</span>
										</td>
									</tr>
								</table>
								
							</td>
							<td width="55%" border="0.5">
								<table cellpadding="0">
									<tr>
										<td width="40%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="98%">
														Date of Commencement
														<br>
														<span style="font-size: 9px">' . $employee_date_commence . '</span>
													</td>
												</tr>
											</table>
										</td>
										
										<td style="border-left: 0.5px solid black;" width="60%">
											<table>
												<tr>
													<td width="8px"></td>
													<td width="90%">
														Date of Cessation
														<br>
														<span style="font-size: 9px">' . $employee_date_cessation . '</span>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="background-color: black; color:white;">
							<td width="80%" border="0">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											<b>
											<span style="font-size: 8px">INCOME </span>
											<span style="font-size: 7px">(See Explanatory Note 9 unless otherwise specified)</span>
											</b>
										</td>
									</tr>
								</table>
								
							</td>
							<td width="20%" border="0">
								$&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table cellpadding="2" cellspacing="0" border="0" style="font-size:8px">
			<tr>
				<td>
					<table border="0">
						<tr>
							<td width="6%" align="center"> a) </td>
							<td width="79%"><b>Gross Salary, Fees, Leave Pay, Wages and Overtime Pay</b></td>
							<td width="15%" style="border: 1px solid black; border-radius: 5px">
								<table cellpadding="1">
									<tr>
										<td align="center">
										<span style="border-bottom: 1px dotted black">' . $gross_salary . '</span>
										</td>
									</tr>
								</table>
							
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0">
						<tr>
							<td width="6%" align="center"> b) </td>
							<td width="79%"><b>Bonus </b>(non-contractual bonus paid in 2019 and/or contractual bonus)</td>
							<td width="15%" style="border: 1px solid black; border-radius: 5px">
								<table cellpadding="1">
									<tr>
										<td align="center">
										<span style="border-bottom: 1px dotted black">' . $bonus . '</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0">
						<tr>
							<td width="6%" align="center"> c) </td>
							<td width="79%">Director’s fees (approved at the company’s AGM/EGM on 01 / 01 /19)</td>
							<td width="15%" style="border: 1px solid black; border-radius: 5px">
								<table cellpadding="1">
									<tr>
										<td align="center">
										<span style="border-bottom: 1px dotted black">' . $director_fee . '</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table style="border-collapse: separate; border-spacing: 0 2px;">
						<tr>
							<td width="6%" align="center"> d) </td>
							<td width="79%">Others:</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="40%">1.	Allowances: (i) Transport $ ' . $allowance_transport . '</td>
										<td width="22%">(ii) Entertainment $ ' . $allowance_entertainment . ' </td>
										<td width="23%">(iii) Others $ ' . $allowance_other . '</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $total_allowance . '</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="30%">2.	Gross Commission for the period</td>
										<td width="22%">' . $commission_from . ' to ' . $commission_to . '</td>
										<td width="33%">* Monthly and/or other adhoc payment</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $gross_commission . '</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="85%">3.	Pension</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $pension . '</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="85%">4.	Lump sum payment</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $lump_sum_total . '</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0.5" cellpadding="0" border-collapse="collapse">
									<tr>
										<td>
											<table cellpadding="2">
												<tr>
													<td>(i)	Gratuity $ ' . $gratuity . '</td>
													<td style="border-left: 0.5px solid black">(ii)	Notice Pay $ ' . $notice_pay . '</td>
													<td style="border-left: 0.5px solid black">(iii) Ex-gratia payment $ ' . $ex_gratia_payment . '</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="2">
												<tr>
													<td>(iv) Others (please state nature) $ ' . $other_lump_sum . '</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="2">
												<tr>
													<td>(v)	Compensation for loss of office $ ' . $office_loss . '</td>
													<td>Approval obtained from IRAS: ' . $approval_iras . '</td>
													<td>Date of approval:' . $office_loss_date_approval . '</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="2">
												<tr>
													<td width="60%" style="font-family: \'Arial BoldMT \'; font-weight: bold"><b>Reason for payment: </b>' . $reason_for_payment . '</td>
													<td width="40%" style="border-left: 0.5px solid black">Length of service within the company/group: ' . $length_of_service . '</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="2">
												<tr>
													<td width="30%" style="font-family: \'Arial BoldMT \'; font-weight: bold"><b>Basis of arriving at the payment:</b></td>
													<td width="70%">(Give details separately if space is insufficient)' . $basis_of_payment . '</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="85%">5.	Retirement benefits including gratuities/pension/commutation of pension/lump sum payments, etc from </td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="31%">&nbsp;&nbsp;&nbsp;	Pension/Provident Fund:  Name of Fund   </td>
										<td width="20%" style="border-bottom: 0.5px dashed black">' . $retirement_benefits_fund_name . '</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="40%">&nbsp;&nbsp;&nbsp;	(Amount accrued up to 31 Dec 1992  $ ' . $amount_upto_1992 . '   )</td>
										<td width="45%">Amount accrued from 1993: $ ' . $amount_from_1993 . '</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $retirement_benefits . '</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="90%">6.	Contributions made by employer to any Pension/Provident Fund constituted outside Singapore <span style="text-decoration: underline">without</span> tax concession:</td>
										<td width="10%" style="border-bottom: 0.5px dashed black" align="center">' . $full_contribution_amount . '</td>
									</tr>
									<tr>
										<td width="85%">&nbsp;&nbsp;&nbsp; Contributions made by employer to any Pension/Provident Fund constituted outside Singapore <span style="text-decoration: underline">with</span> tax concession: </td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" border-collapse="collapse">
												<tr>
													<td width="2%">&nbsp;&nbsp;&nbsp;</td>
													<td width="98%" >
														<table border="0.5" cellpadding="2" border-collapse="collapse">
															<tr>
																<td colspan="2">Name of the overseas pension/provident fund: ' . $overseas_provident_fund . '</td>
															</tr>
															<tr>
																<td>Full Amount of the contributions : ' . $full_contribution_amount . '</td>
																<td>Are contributions mandatory: *Yes/No</td>
															</tr>
															<tr>
																<td colspan="2">Were contributions charged / deductions claimed by a Singapore permanent establishment: *Yes/No</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="85%">7.	Excess/Voluntary contribution to CPF by employer (less amount refunded/to be refunded): </td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $excess_cpf_contribution_ir8s . '</td>
									</tr>
									<tr>
										<td width="85%" colspan="2">&nbsp;&nbsp;&nbsp; [Complete the Form IR8S]</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="85%">8.	Gains or profits from Employee Stock Option (ESOP)/other forms of Employee Share Ownership (ESOW) Plans:</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $stock_gains_ap8b . '</td>
									</tr>
									<tr>
										<td width="85%" colspan="2">&nbsp;&nbsp;&nbsp; [Complete the Appendix 8B]</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="6%" align="center">&nbsp;</td>
							<td width="93%">
								<table border="0">
									<tr>
										<td width="85%">9.	Value of Benefits-in-kind [See Explanatory Note 12 and complete Appendix 8A]</td>
										<td width="15%" style="border-bottom: 0.5px dashed black" align="center">' . $benefits_in_kind_ap8a . '</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';
		// $pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table cellpadding="0" cellspacing="0" border="0" style="font-size:8px">
			<tr>
				<td>
					<table>
						<tr>
							<td width="50%" align="center"></td>
							<td width="35%" align="left"><b>TOTAL (items d1 to d9)</b></td>
							<td width="15%" style="border: 1px solid black; border-radius: 5px">
								<table cellpadding="1">
									<tr>
										<td align="center">
										<span style="border-bottom: 1px dotted black">' . $total_d1_to_d9 . '</span>
										</td>
									</tr>
								</table>
							
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table cellpadding="0" cellspacing="0" border="0" style="font-size:8px">
			<tr>
				<td>
					<table border="0">
						<tr>
							<td width="7%" align="center"> e) </td>
							<td width="79%">
								<table>
									<tr>
										<td width="30%">1. Remission: Amount of Income $</td>
										<td width="10%" style="border-bottom: 0.5px dotted black" colspan="2">' . $remission_amount . '</td>
									</tr>
									<tr>
										<td colspan="3" width="100%">2.	Overseas Posting: *Full Year/Part of the Year (See Explanatory Note 8a)</td>
									</tr>
									<tr>
										<td width="18%">3.	Exempt Income: $</td>
										<td width="10%" style="border-bottom: 0.5px dotted black">' . $exempt_income . '</td> 	
										<td>(See Explanatory Note 8b)</td>
									</tr>
								</table>
							</td>
							
						</tr>
					</table>
				</td>
			</tr>
			
		</table>';
		$pdf->SetFont($arial, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table cellpadding="1" cellspacing="0" border="0" style="font-size:8px">
			<tr>
				<td>
					<table border="0">
						<tr>
							<td width="6%" align="center"> f) </td>
							<td width="94%">
								<table border="0.5" cellpadding="2">
									<tr>
										<td width="15%" rowspan="3">Employee’s income tax borne by employer?
										' . $tax_borne_by_employer . '
										</td>
										<td width="85%" colspan="2">If tax is fully borne by employer, DO NOT enter any amount in (i) and (ii)</td>
									</tr>
									<tr>
										<td width="75%">(i)	If tax is partially borne by employer, state the amount of income for which tax is borne by employer</td>
										<td width="10%">
											<table cellpadding="2">
												<tr>
													<td width="10%"></td>
													<td width="80%" style="border-bottom: 0.5px dotted black">' . $partial_tax_amount . '</td>
													<td width="10%"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td width="75%">(ii) If a fixed amount of tax is borne by employee, state the amount of tax to be paid by employee</td>
										<td width="10%">
											<table cellpadding="2">
												<tr>
													<td width="10%"></td>
													<td width="80%" style="border-bottom: 0.5px dotted black">' . $fixed_tax_amount . '</td>
													<td width="10%"></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
							
						</tr>
					</table>
				</td>
			</tr>
			
		</table>';
		$pdf->SetFont($arial, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table style="font-size:8px;" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table style="border-collapse: collapse" cellpadding="0">
						<tr style="background-color: black; color:white;">
							<td width="4%" align="center" style="border-left: 0.5px solid black"></td>
							<td width="80%" border="0">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											<b>
											<span style="font-size: 8px">DEDUCTIONS (See Explanatory Note 10 - Deductions) </span>
											</b>
										</td>
									</tr>
								</table>
								
							</td>
							<td width="16%" border="0">
								&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
							<td width="80%" style="border-bottom: 0.5px solid black">
								<table cellpadding="1">
									<tr>
										<td width="2%"></td>
										<td width="98%">
										EMPLOYEE’S COMPULSORY contribution to *CPF/Designated Pension or Provident Fund (less amount <br>refunded/to be refunded) Name of Fund : CPF    <br>
										(Apply the appropriate CPF rates published by CPF Board on its website ‘www.cpf.gov.sg’. Do not <br>include excess/voluntary contributions to CPF, voluntary contributions to Medisave Account, <br>voluntary contributions to Retirement Sum Topping-up Scheme, SRS contributions and <br>contributions to Overseas Pension or Provident Fund in this item)
										
										</td>
									</tr>
								</table>	
							</td>
							<td width="16%" style="border-right: 0.5px solid black; border-left: 0.5px solid black;">
								<table>
									<tr>
										<td width="20%"></td>
										<td width="60%"></td>
										<td width="20%"></td>
									</tr>
									<tr>
										<td width="20%"></td>
										<td width="60%"></td>
										<td width="20%"></td>
									</tr>
									<tr>
										<td width="20%"></td>
										<td width="60%" style="border-bottom: 0.5px dotted black;" align="center">' . $cpf_employee_deduction . '</td>
										<td width="20%"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
							<td width="80%" style="border-bottom: 0.5px solid black">
								<table cellpadding="1">
									<tr>
										<td width="2%"></td>
										<td width="98%">
										Donations deducted from salaries for:
										<br>*Yayasan Mendaki Fund/Community Chest of Singapore/SINDA/CDAC/ECF/Other tax exempt donations
											
										</td>
									</tr>
								</table>	
							</td>
							<td width="16%" style="border-right: 0.5px solid black; border-left: 0.5px solid black;">
								<table>
									<tr>
										<td width="20%"></td>
										<td width="60%" style="border-bottom: 0.5px dotted black" align="center">' . $donation_funds . '</td>
										<td width="20%"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
							<td width="80%" style="border-bottom: 0.5px solid black">
								<table cellpadding="1">
									<tr>
										<td width="2%"></td>
										<td width="98%">Contributions deducted from salaries to Mosque Building Fund :</td>
									</tr>
								</table>	
							</td>
							<td width="16%" style="border-right: 0.5px solid black; border-left: 0.5px solid black;">
								<table>
									<tr>
										<td width="20%"></td>
										<td width="60%" style="border-bottom: 0.5px dotted black" align="center">' . $mbmf_funds . '</td>
										<td width="20%"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
							<td width="80%" style="border-bottom: 0.5px solid black">
								<table cellpadding="1">
									<tr>
										<td width="2%"></td>
										<td width="98%">Life Insurance premiums deducted from salaries:</td>
									</tr>
								</table>	
							</td>
							<td width="16%" style="border-right: 0.5px solid black; border-left: 0.5px solid black; border-bottom: 0.5px solid black;">
								<table>
									<tr>
										<td width="20%"></td>
										<td width="60%" style="border-bottom: 0.5px dotted black" align="center">' . $life_insurance . '</td>
										<td width="20%"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="background-color: black; color:white;">
							<td width="4%" align="center" style="border-left: 0.5px solid black"></td>
							<td width="96%" border="0">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="98%">
											<b>
											<span style="font-size: 8px">DECLARATION (See Explanatory Note 2)</span>
											</b>
										</td>
									</tr>
								</table>
								
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black"></td>
							<td width="96%" style="border-right: 0.5px solid black">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="20%">Name of Employer  : </td>
										<td width="68%" style="border-bottom: 0.5px dotted black">' . $company_name . '</td>
									</tr>
								</table>
								
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black"></td>
							<td width="96%" style="border-right: 0.5px solid black">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="20%">Address of Employer  : </td>
										<td width="68%" style="border-bottom: 0.5px dotted black">' . $company_address . '</td>
									</tr>
								</table>
								
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black;"></td>
							<td width="96%" style="border-right: 0.5px solid black;">
								<table>
									<tr>
										<td width="2%"></td>
										<td width="40%" >
											<table>
												<tr>
													<td width="2%"></td>
													<td width="96%" style="border-bottom: 0.5px dotted black" align="center">' . $employer_authorised_person . '</td>
													<td width="2%"></td>
												</tr>
											</table>
										</td>
										<td width="15%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="96%" style="border-bottom: 0.5px dotted black" align="center">' . $authorised_person_designation . '</td>
													<td width="2%"></td>
												</tr>
											</table>
										</td>
										<td width="15%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="96%" style="border-bottom: 0.5px dotted black" align="center">' . $authorised_person_phone . '</td>
													<td width="2%"></td>
												</tr>
											</table>
										</td>
										<td width="18%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="96%" style="border-bottom: 0.5px dotted black"></td>
													<td width="2%"></td>
												</tr>
											</table>
										</td>
										<td width="10%">
											<table>
												<tr>
													<td width="2%"></td>
													<td width="94%" style="border-bottom: 0.5px dotted black" align="center">' . $date_created . '</td>
													<td width="2%"></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								
							</td>
						</tr>
						<tr>
							<td width="4%" align="center" style="border-left: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
							<td width="96%" style="border-right: 0.5px solid black;">
								<table>
									<tr>
										<td width="2%" style="border-bottom: 0.5px solid black; border-bottom: 0.5px solid black"></td>
										<td width="40%" style="border-bottom: 0.5px solid black;">Name of authorised person making the declaration  : </td>
										<td width="15%" style="border-bottom: 0.5px solid black; text-align: center;">Designation</td>
										<td width="15%" style="border-bottom: 0.5px solid black; text-align: center;">Tel. No.</td>
										<td width="18%" style="border-bottom: 0.5px solid black; text-align: center;">Signature</td>
										<td width="10%" style="border-bottom: 0.5px solid black; text-align: center;">Date</td>
									</tr>
								</table>
								
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table style="font-size:8px;" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table style="border-collapse: collapse">
						<tr>
							<td width="4%" align="center"></td>
							<td width="96%">
								<table>
									<tr>
										<td width="20%"></td>
										<td width="65%">There are penalties for failing to give a return or furnishing an incorrect or late return</td>
										<td width="15%"></td>
									</tr>
								</table>
								
							</td>
						</tr>
						
					</table>
					
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table style="font-size:8px;" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table style="border-collapse: collapse">
						<tr>
							<td width="4%" align="center"></td>
							<td width="96%">
								<table>
									<tr>
										<td width="20%">IR8A (1/' . $fa_year . ')</td>
										<td width="60%"></td>
										<td width="20%">* Delete where applicable</td>
									</tr>
								</table>
								
							</td>
						</tr>
						
					</table>
					
				</td>
			</tr>
		</table>';
		$pdf->SetFont($arial_bold, '', '', '', false);
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$fname = 'Ir8a form';
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$fname = strtolower($fname);
		// $pay_month = strtolower(date("F Y", strtotime($payment[0]->year_to_date)));
		//Close and output PDF document
		ob_start();
		$pdf->Output('payslip_' . $fname . '_ir8a.pdf', 'I');
		ob_end_flush();
	}

	public function ir8a_excel($year,$company_id)
	{
		error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Disable warnings & notices
		error_reporting(0); // Disable all errors
		ini_set('display_errors', 0);
		try {
			$this->load->library('Excel');
			$inputFile = FCPATH . 'uploads/IR8A_Import_Template.xlsx'; // Adjust the path
			$objPHPExcel = PHPExcel_IOFactory::load($inputFile);

			// Select active sheet
			$sheet = $objPHPExcel->getActiveSheet();
			// $year = 2025;
			$employee_list = $this->Employees_model->get_company_employees_flt($company_id);
			$i = 10;
			foreach ($employee_list->result() as $key => $list) {
				if ($list->id_type == 1) {
					$id_name = 'NRIC';
				} else {
					$id_name =	'FIN';
				}
				$sheet->setCellValue('B' . $i, $id_name);
				$sheet->setCellValue('C' . $i, $list->id_no);
				$sheet->setCellValue('D' . $i, $list->first_name . " " . $list->last_name);

				$country_name = $this->Employees_model->getEmployeeNationality($list->user_id);
				$sheet->setCellValue('E' . $i, strtoupper($country_name->country_name));
				$sheet->setCellValue('F' . $i, $list->gender);
				$sheet->setCellValue('G' . $i, date('d/m/Y', strtotime($list->date_of_birth)));

				$read_designation_information = $this->Designation_model->read_designation_information($list->designation_id);
				$sheet->setCellValue('H' . $i, $read_designation_information[0]->designation_name);
				$sheet->setCellValue('I' . $i, $list->residential_address_type);
				$sheet->setCellValue('J' . $i, $list->address);
				$sheet->setCellValue('K' . $i, $list->zipcode);
				$country_name = $this->Employees_model->getEmployeeNationality($list->user_id);
				$sheet->setCellValue('L' . $i, strtoupper($country_name->country_name));
				$sheet->setCellValue('M' . $i, date('d/m/Y', strtotime($list->date_of_joining)));
				$sheet->setCellValue('N' . $i, $list->date_of_leaving ? date('d/m/Y', strtotime($list->date_of_leaving)) : '');

				$get_employee_bank_account_last = $this->Employees_model->get_employee_bank_account_last($list->user_id);
				$sheet->setCellValue('O' . $i, $get_employee_bank_account_last[0]->bank_name ?? '');
				$sheet->setCellValue('P' . $i, $list->basic_salary ?? '');



				$getIR8AEmployeesID = $this->Efiling_model->getIR8AEmployeesID($year, $list->user_id);
				// print_r($getIR8AEmployeesID);exit;
				$sheet->setCellValue('Q' . $i, $getIR8AEmployeesID->bonus_date ? date('d/m/Y', strtotime($getIR8AEmployeesID->bonus_date)) : '');
				$sheet->setCellValue('R' . $i, $getIR8AEmployeesID->bonus ?? '');
				$sheet->setCellValue('s' . $i, $getIR8AEmployeesID->director_fee_date ? date('d/m/Y', strtotime($getIR8AEmployeesID->director_fee_date)) : '');
				$sheet->setCellValue('T' . $i, $getIR8AEmployeesID->director_fee ?? '');

				$sheet->setCellValue('W' . $i, $getIR8AEmployeesID->allowance_transport ?? '');
				$sheet->setCellValue('X' . $i, $getIR8AEmployeesID->allowance_entertainment ?? '');
				$sheet->setCellValue('Y' . $i, $getIR8AEmployeesID->allowance_other ?? '');

				if (!empty($getIR8AEmployeesID->gross_commission)) {
					$sheet->setCellValue('Z' . $i, date('d/m/Y', strtotime('01-01-' . $year)));
					$sheet->setCellValue('AA' . $i, date('d/m/Y', strtotime('31-12-' . $year)));
					$sheet->setCellValue('AB' . $i, $getIR8AEmployeesID->gross_commission ?? '');
					$sheet->setCellValue('AC' . $i, "Both");
				}
				$sheet->setCellValue('AD' . $i, $getIR8AEmployeesID->pension ?? '');
				// $sheet->setCellValue('AE'.$i, $getIR8AEmployeesID->gratuity + $getIR8AEmployeesID->notice_pay + $getIR8AEmployeesID->ex_gratia_payment + $getIR8AEmployeesID->other_lump_sum  ?? '');
				$sheet->setCellValue('AF' . $i, $getIR8AEmployeesID->gratuity + $getIR8AEmployeesID->notice_pay + $getIR8AEmployeesID->ex_gratia_payment + $getIR8AEmployeesID->other_lump_sum  ?? '');
				$sheet->setCellValue('AH' . $i, $getIR8AEmployeesID->comp_loss_office ?? '');

				$get_other_payment = $this->Employees_model->get_other_payment($year, $list->user_id);
				$sheet->setCellValue('AK' . $i, $get_other_payment[0]->fund_title ?? '');
				$sheet->setCellValue('AI' . $i, "Y");

				$sheet->setCellValue('AL' . $i, 0);

				


				$i++;
			}



			// Add data (e.g., add to row 5, column 2)

			$company_info = $this->Xin_model->read_company_info($company_id);
			// Save the updated file
			$outputFile = FCPATH . 'uploads/'.$company_info[0]->name.' - '.' IR8A YA'.$year.'.xlsx'; // Adjust the output file path
			$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$writer->save($outputFile);
		} catch (Exception $e) {
			print_r($e->getMessage());
		}
		// echo "Excel file updated successfully!";
		return;
	}

	public function isIr8aGenerated($year)
	{
		$ir8a = $this->Efiling_model->getIr8aRecordByYear($year);
		if ($ir8a) {
			$Return['result'] = $ir8a;
		} else {
			$Return['error'] =  'No record found';
		}
		$Return['year'] = $year;
		$this->output($Return);
		exit;
	}

	public function resetIr8a()
	{
		if ($this->input->post('type') == 'ir8a_reset_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;

			$reset_ir8a = $this->Efiling_model->resetAllIr8aRecordsByYear($year);

			if ($reset_ir8a) {
				$Return['result'] = 'IR8A reset successfully';
			} else {
				$Return['error'] = 'Error, could not reset';
			}
			$this->output($Return);
			exit;
		}
	}

	/**Appendix 8A */
	public function appendix8a()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$year_a = '';
		$year = $this->uri->segment(5);
		if ($year) {
			$valid_year = (int)$year;
			$ayear = date('Y', strtotime("-6 year"));
			$fyear = date('Y', strtotime("+1 year"));

			if ($valid_year >= $ayear && $valid_year <= $fyear) {
				$year_a = $valid_year;
			} else {
				redirect('admin/efiling/appendix8a');
			}
		}
		if ($year_a == '') {
			$year_a = date('Y');
		}
		$year_b = $year_a - 1;
		$data['title'] = 'Appendix 8A';
		$data['breadcrumbs'] = 'Appendix 8A';
		$data['path_url'] = 'appendix8a_filing';
		$data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$data['year_a'] = $year_a;
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$isGenerated = $this->Efiling_model->getAp8ARecordByYear($year_b);
		$data['is_generated'] = $isGenerated;
		if (in_array('428', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/efiling/appendix8a_filing", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function employeeSummaryAp8a($year)
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/efiling/ir8a_filing", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = array();

		$employee_summaries = $this->Efiling_model->get_all_employee_payslip_summary_8a($year);
		if ($employee_summaries) {
			foreach ($employee_summaries->result() as $s) {

				// $action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $eu->id . '" data-field_type="salary_allowance"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $eu->id . '" data-token_type="all_allowances"><span class="fa fa-trash"></span></button></span>';
				$total_cpf = $s->tow_cpf_employee + $s->tow_cpf_employer + $s->taw_cpf_employee + $s->taw_cpf_employer;

				$data[] = array(
					$s->first_name . ' ' . $s->last_name,
					$s->country_name,
					$this->Xin_model->currency_sign($s->tgross_salary),
					$this->Xin_model->currency_sign($s->ir8a_d9),
					$s->ap8a_eligible
				);
			}
		}


		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_summaries->num_rows(),
			"recordsFiltered" => $employee_summaries->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function generateAp8a()
	{
		if ($this->input->post('type') == 'ap8a_generate_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;
			// $employee_annual_pay = $this->Efiling_model->getEmployeesAnnualPay($year);
			$employee_annual_pay = $this->Efiling_model->getEmployeesAnnualPayForAp8a();
			$emp_count = 0;
			$no_of_records = 0;
			$total_accommodation = 0;
			$total_utilities_housekeeping = 0;
			$total_hotel_accommodation = 0;
			$total_other_benefits = 0;
			//xml detail record
			$detail_record = "<Details>";

			if ($employee_annual_pay) {
				$submission_key =  random_string('alnum', 40);
				foreach ($employee_annual_pay as $e) {
					//accommodation
					$accommodation_amount = 0;
					$accommodation = $this->Efiling_model->getEmployeeAccommodation($e->employee_id, $year);
					if ($accommodation) {
						$ac_id = $accommodation->accommodation_id;
						$ac_type = $accommodation->accommodation_type;
						$rent_paid = $accommodation->rent_paid;
						$ac_from = new DateTime($accommodation->period_from);
						$ac_to = new DateTime($accommodation->period_to);
						// $ac_days = $ac_to->diff($ac_from)->days;

						$ac_days = $ac_to->diff($ac_from)->days + 1;

						$shared_accommodation = $this->Efiling_model->getSharedAccommodationCount($accommodation->period_from, $accommodation->period_to, $ac_id);
						if ($ac_type == 1) {
							$ac_annual_value = $accommodation->annual_value;
							$ac_furnished = $accommodation->furnished_type;
							$annual_value = round(($ac_annual_value / $shared_accommodation) * ($ac_days / 365), 2);
							if ($ac_furnished == 1) {
								$furniture_value = round($annual_value * 50 / 100, 2);
							} else {
								$furniture_value = round($annual_value * 40 / 100, 2);
							}
							$accommodation_amount = round($annual_value + $furniture_value, 2);
							if ($rent_paid != '') {
								$accommodation_amount = round($accommodation_amount - $rent_paid, 2);
							}
						} else {
							$annual_rent_value = $accommodation->rent_value;
							$annual_value = round(($annual_rent_value / $shared_accommodation) * ($ac_days / 365), 2);
							$accommodation_amount = $annual_value;
							if ($rent_paid != '') {
								$accommodation_amount = $accommodation_amount - $rent_paid;
							}
						}
					}

					//utility
					$utilities_amount = 0;
					$utilities = $this->Efiling_model->getEmployeeUtilityBenefit($e->employee_id, $year);
					if ($utilities) {
						$utilities_amount = $utilities->utility_amount;
					}

					//driver
					$driver_benefit = 0;
					$driver = $this->Efiling_model->getEmployeeDriverBenefit($e->employee_id, $year);
					if ($driver) {
						$driver_benefit = $driver->driver_wage;
					}

					//housekeeping
					$housekeeping_benefit = 0;
					$housekeeping = $this->Efiling_model->getEmployeeHousekeepingBenefit($e->employee_id, $year);
					if ($housekeeping) {
						$housekeeping_benefit = $housekeeping->housekeeping_amount;
					}

					//hotel accommodation
					$hotel_accommodation_amount = 0;
					$hotel_accommodation = $this->Efiling_model->getEmployeeHotelAccommodationBenefit($e->employee_id, $year);
					if ($hotel_accommodation) {
						foreach ($hotel_accommodation as $ha) {
							$actual_cost = $ha->actual_cost;
							$employee_paid = $ha->employee_paid;
							if ($employee_paid != '') {
								$ha_amount = $actual_cost - $employee_paid;
								$hotel_accommodation_amount += $ha_amount;
							} else {
								$hotel_accommodation_amount += $actual_cost;
							}
						}
					}

					//other benefit
					$other_benefit_cost = 0;
					$other_benefit = $this->Efiling_model->getEmployeeOtherBenefit($e->employee_id, $year);
					if ($other_benefit) {
						$other_benefit_cost = $other_benefit->other_benefit_cost;
					}

					//total benefits
					$benefits = $accommodation_amount + $utilities_amount + $driver_benefit + $housekeeping_benefit + $hotel_accommodation_amount + $other_benefit_cost;

					if ($benefits > 0) {
						$jurl = random_string('alnum', 40);
						$edata = array(
							'ap8a_key' 			=> $jurl,
							'submission_key' 	=> $submission_key,
							'employee_id' 		=> $e->employee_id,
							'ap8a_year' 		=> $year,

							// new add 
							'shared_accommodation'	=> $shared_accommodation ?? 0,
							'driver_benefit'		=> $driver_benefit,
							'housekeeping_benefit'	=> $housekeeping_benefit,
							'utilities_amount'		=> $utilities_amount
						);

						if ($accommodation_amount > 0) {
							$edata['accommodation'] = $accommodation_amount;
						}

						$utilities_housekeeping = $utilities_amount + $driver_benefit + $housekeeping_benefit;
						if ($utilities_housekeeping > 0) {
							$edata['utilities_housekeeping'] = $utilities_housekeeping;
						}

						if ($hotel_accommodation_amount > 0) {
							$edata['hotel_accommodation'] = $hotel_accommodation_amount;
						}

						if ($other_benefit_cost > 0) {
							$edata['other_benefits'] = $other_benefit_cost;
						}

						$ap8a_result = $this->Efiling_model->setAp8aEmployee($edata);
						if ($ap8a_result) {
							$emp_count += 1;
						}

						$employee = $this->Xin_model->read_user_info($e->employee_id);
						$emp_id_type = $employee[0]->id_type;
						$emp_id = $employee[0]->id_no;
						$emp_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;


						$doj = $employee[0]->date_of_joining;
						$dol = $employee[0]->date_of_leaving;
						$year_end = intval($this->input->post('year')) - 1;
						$fa_year = $this->input->post('year');

						if ($accommodation) {
							$ac_type = $accommodation->accommodation_type;
							$rent_paid = $accommodation->rent_paid;
							$add_1 = $accommodation->address_line_1;
							$add_2 = $accommodation->address_line_2;
							$occ_from = date('Ymd', strtotime($accommodation->period_from));
							$occ_to = date('Ymd', strtotime($accommodation->period_from));

							if ($ac_type == 1) {
								$ac_annual_value = $accommodation->annual_value;
								$ac_furnished = $accommodation->furnished_type;
								$annual_value = round(($ac_annual_value / $shared_accommodation) * ($ac_days / 365), 2);
								$ac_val = '<AVOfPremises xmlns="http://www.iras.gov.sg/A8A2015">' . $annual_value . '</AVOfPremises>';


								if ($ac_furnished == 1) {
									$furniture_value = round($annual_value * 50 / 100, 2);
									$ac_val .= '<ValueFurnitureFittingInd xmlns="http://www.iras.gov.sg/A8A2015">F</ValueFurnitureFittingInd>';
								} else {
									$furniture_value = round($annual_value * 40 / 100, 2);
									$ac_val .= '<ValueFurnitureFittingInd xmlns="http://www.iras.gov.sg/A8A2015">P</ValueFurnitureFittingInd>';
								}
								$ac_val .= '<ValueFurnitureFitting xmlns="http://www.iras.gov.sg/A8A2015">' . $furniture_value . '</ValueFurnitureFitting>';
								$accommodation_amount = round($annual_value + $furniture_value, 2);
								$ac_val .= '<RentPaidToLandlord xmlns="http://www.iras.gov.sg/A8A2015"/>';
								$ac_val .= '<TaxableValuePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015">' . $accommodation_amount . '</TaxableValuePlaceOfResidence>';
								if ($rent_paid != '') {
									$accommodation_amount = round($accommodation_amount - $rent_paid, 2);
									$ac_val .= '<TotalRentPaidByEmployeePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015">' . $rent_paid . '</TotalRentPaidByEmployeePlaceOfResidence>';
								} else {
									$ac_val .= '<TotalRentPaidByEmployeePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015">0.0</TotalRentPaidByEmployeePlaceOfResidence>';
								}
							} else {
								$ac_val = '<AVOfPremises xmlns="http://www.iras.gov.sg/A8A2015"/>';
								$ac_val .= '<ValueFurnitureFittingInd xmlns="http://www.iras.gov.sg/A8A2015"/>';
								$ac_val .= '<ValueFurnitureFitting xmlns="http://www.iras.gov.sg/A8A2015"/>';

								$annual_rent_value = $accommodation->rent_value;
								$annual_value = round(($annual_rent_value / $shared_accommodation) * ($ac_days / 365), 2);
								$accommodation_amount = $annual_value;
								// $ac_val .= '<RentPaidToLandlord xmlns="http://www.iras.gov.sg/A8A2015"/>'.$annual_rent_value.'</RentPaidToLandlord>';
								$ac_val .= '<RentPaidToLandlord xmlns="http://www.iras.gov.sg/A8A2015"/>' . $annual_rent_value;
								$ac_val .= '<TaxableValuePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015">' . $accommodation_amount . '</TaxableValuePlaceOfResidence>';
								if ($rent_paid != '') {
									$accommodation_amount = $accommodation_amount - $rent_paid;
									$ac_val .= '<TotalRentPaidByEmployeePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015">' . $rent_paid . '</TotalRentPaidByEmployeePlaceOfResidence>';
								} else {
									$ac_val .= '<TotalRentPaidByEmployeePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015"/>';
								}
							}

							$ac_dt = <<<EOT
							<ResidenceAddressLine1 xmlns="http://www.iras.gov.sg/A8A2015">$add_1</ResidenceAddressLine1>
							<ResidenceAddressLine2 xmlns="http://www.iras.gov.sg/A8A2015">$add_2</ResidenceAddressLine2>
							<ResidenceAddressLine3 xmlns="http://www.iras.gov.sg/A8A2015"/>
							<OccupationFromDate xmlns="http://www.iras.gov.sg/A8A2015">$occ_from</OccupationFromDate>
							<OccupationToDate xmlns="http://www.iras.gov.sg/A8A2015">$occ_to</OccupationToDate>
							<NoOfDays xmlns="http://www.iras.gov.sg/A8A2015">$ac_days</NoOfDays>
							<NoOfEmployeeSharePremises xmlns="http://www.iras.gov.sg/A8A2015">$shared_accommodation</NoOfEmployeeSharePremises>
							$ac_val
							<TotalTaxableValuePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015">$accommodation_amount</TotalTaxableValuePlaceOfResidence>
							EOT;
						} else {
							$ac_dt = <<<EOT
							<ResidenceAddressLine1 xmlns="http://www.iras.gov.sg/A8A2015"/>
							<ResidenceAddressLine2 xmlns="http://www.iras.gov.sg/A8A2015"/>
							<ResidenceAddressLine3 xmlns="http://www.iras.gov.sg/A8A2015"/>
							<OccupationFromDate xmlns="http://www.iras.gov.sg/A8A2015"/>
							<OccupationToDate xmlns="http://www.iras.gov.sg/A8A2015"/>
							<NoOfDays xmlns="http://www.iras.gov.sg/A8A2015"/>
							<NoOfEmployeeSharePremises xmlns="http://www.iras.gov.sg/A8A2015"/>
							<AVOfPremises xmlns="http://www.iras.gov.sg/A8A2015"/>
							<ValueFurnitureFittingInd xmlns="http://www.iras.gov.sg/A8A2015"/>
							<ValueFurnitureFitting xmlns="http://www.iras.gov.sg/A8A2015"/>
							<RentPaidToLandlord xmlns="http://www.iras.gov.sg/A8A2015"/>
							<TaxableValuePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015"/>
							<TotalRentPaidByEmployeePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015"/>
							<TotalTaxableValuePlaceOfResidence xmlns="http://www.iras.gov.sg/A8A2015"/>
							EOT;
						}

						if ($utilities) {
							$utilities_amount = $utilities->utility_amount;
							$ut_dt = '<UtilitiesTelPagerSuitCaseAccessories xmlns="http://www.iras.gov.sg/A8A2015">' . $utilities_amount . '</UtilitiesTelPagerSuitCaseAccessories>';
						} else {
							$ut_dt = '<UtilitiesTelPagerSuitCaseAccessories xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						if ($driver) {
							$driver_benefit = $driver->driver_wage;
							$dr_dt = '<Driver xmlns="http://www.iras.gov.sg/A8A2015">' . $driver_benefit . '</Driver>';
						} else {
							$dr_dt = '<Driver xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						if ($housekeeping) {
							$housekeeping_benefit = $housekeeping->housekeeping_amount;
							$sr_dt = '<ServantGardener xmlns="http://www.iras.gov.sg/A8A2015">' . $housekeeping_benefit . '</ServantGardener>';
						} else {
							$sr_dt = '<ServantGardener xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						if ($utilities_housekeeping > 0) {
							$hk_dt = '<TaxableValueUtilitiesHouseKeeping xmlns="http://www.iras.gov.sg/A8A2015">' . $utilities_housekeeping . '</TaxableValueUtilitiesHouseKeeping>';
						} else {
							$hk_dt = '<TaxableValueUtilitiesHouseKeeping xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						if ($hotel_accommodation) {
							$actual_cost = 0;
							$employee_paid = 0;
							foreach ($hotel_accommodation as $ha) {
								$actual_cost += $ha->actual_cost;
								$employee_paid += $ha->employee_paid;
							}
							$ha_amount = $actual_cost - $employee_paid;
							$ha_dt = '<ActualHotelAccommodation xmlns="http://www.iras.gov.sg/A8A2015">' . $actual_cost . '</ActualHotelAccommodation>';
							if ($employee_paid > 0) {
								$ha_dt .= '<AmountPaidByEmployee xmlns="http://www.iras.gov.sg/A8A2015">' . $employee_paid . '</AmountPaidByEmployee>';
							} else {
								$ha_dt .= '<AmountPaidByEmployee xmlns="http://www.iras.gov.sg/A8A2015"/>';
							}

							$ha_dt .= '<TaxableValueHotelAccommodation xmlns="http://www.iras.gov.sg/A8A2015">' . $ha_amount . '</TaxableValueHotelAccommodation>';
						} else {
							$ha_dt = '<ActualHotelAccommodation xmlns="http://www.iras.gov.sg/A8A2015"/>';
							$ha_dt .= '<AmountPaidByEmployee xmlns="http://www.iras.gov.sg/A8A2015"/>';
							$ha_dt .= '<TaxableValueHotelAccommodation xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						//other benefit
						$home_leave = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Home Leave Passage & Incidental Benefit');
						if ($home_leave) {
							$benefit_cost = $home_leave->benefit_cost;
							$hl_dt = '<CostOfLeavePassageAndIncidentalBenefits xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</CostOfLeavePassageAndIncidentalBenefits>';
						} else {
							$hl_dt = '<CostOfLeavePassageAndIncidentalBenefits xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$interest_paid = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Interest Payment');
						if ($interest_paid) {
							$benefit_cost = $interest_paid->benefit_cost;
							$ip_dt = '<InterestPaidByEmployer xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</InterestPaidByEmployer>';
						} else {
							$ip_dt = '<InterestPaidByEmployer xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$life_insurance = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Insurance Premiums');
						if ($life_insurance) {
							$benefit_cost = $life_insurance->benefit_cost;
							$li_dt = '<LifeInsurancePremiumsPaidByEmployer xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</LifeInsurancePremiumsPaidByEmployer>';
						} else {
							$li_dt = '<LifeInsurancePremiumsPaidByEmployer xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$free_holidays = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Free or Subsidised Holidays');
						if ($free_holidays) {
							$benefit_cost = $free_holidays->benefit_cost;
							$fh_dt = '<FreeOrSubsidisedHoliday xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</FreeOrSubsidisedHoliday>';
						} else {
							$fh_dt = '<FreeOrSubsidisedHoliday xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$educational_expense = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Educational expenses');
						if ($educational_expense) {
							$benefit_cost = $educational_expense->benefit_cost;
							$ee_dt = '<EducationalExpenses xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</EducationalExpenses>';
						} else {
							$ee_dt = '<EducationalExpenses xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$social_club = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Social or Recreational clubs Fee');
						if ($social_club) {
							$benefit_cost = $social_club->benefit_cost;
							$sc_dt = '<EntranceOrTransferFeesToSocialClubs xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</EntranceOrTransferFeesToSocialClubs>';
						} else {
							$sc_dt = '<EntranceOrTransferFeesToSocialClubs xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$assets_gains = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Gains from Assets sold to Employee');
						if ($assets_gains) {
							$benefit_cost = $assets_gains->benefit_cost;
							$ag_dt = '<GainsFromAssets xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</GainsFromAssets>';
						} else {
							$ag_dt = '<GainsFromAssets xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$motor_vehicle = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Motor Vehicle cost given to Employee');
						if ($motor_vehicle) {
							$benefit_cost = $motor_vehicle->benefit_cost;
							$mv_dt = '<FullCostOfMotorVehicle xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</FullCostOfMotorVehicle>';
						} else {
							$mv_dt = '<FullCostOfMotorVehicle xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$car_benefit = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Car Benefits');
						if ($car_benefit) {
							$benefit_cost = $car_benefit->benefit_cost;
							$cb_dt = '<CarBenefit xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</CarBenefit>';
						} else {
							$cb_dt = '<CarBenefit xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						$other_benefit = $this->Efiling_model->getEmployeeBenefitByName($e->employee_id, $year, 'Other Benefit');
						if ($other_benefit) {
							$benefit_cost = $other_benefit->benefit_cost;
							$ot_dt = '<OthersBenefits xmlns="http://www.iras.gov.sg/A8A2015">' . $benefit_cost . '</OthersBenefits>';
						} else {
							$ot_dt = '<OthersBenefits xmlns="http://www.iras.gov.sg/A8A2015"/>';
						}

						//detail
						$dt = <<<EOT
						<A8ARecord>
						<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">
						<A8A2015ST>
						<RecordType xmlns="http://www.iras.gov.sg/A8A2015">1</RecordType>
						<IDType xmlns="http://www.iras.gov.sg/A8A2015">$emp_id_type</IDType>
						<IDNo xmlns="http://www.iras.gov.sg/A8A2015">$emp_id</IDNo>
						<NameLine1 xmlns="http://www.iras.gov.sg/A8A2015">$emp_name</NameLine1>
						<NameLine2 xmlns="http://www.iras.gov.sg/A8A2015"/>
						$ac_dt
						$ut_dt
						$dr_dt
						$sr_dt
						$hk_dt
						$ha_dt
						$hl_dt
						<NoOfLeavePassageSelf xmlns="http://www.iras.gov.sg/A8A2015"/>
						<NoOfLeavePassageSpouse xmlns="http://www.iras.gov.sg/A8A2015"/>
						<NoOfLeavePassageChildren xmlns="http://www.iras.gov.sg/A8A2015"/>
						<OHQStatus xmlns="http://www.iras.gov.sg/A8A2015"/>
						$ip_dt
						$li_dt
						$fh_dt
						$ee_dt
						<NonMonetaryAwardsForLongService xmlns="http://www.iras.gov.sg/A8A2015"/>
						$sc_dt
						$ag_dt
						$mv_dt
						$cb_dt
						$ot_dt
						<TotalBenefitsInKind xmlns="http://www.iras.gov.sg/A8A2015">$benefits</TotalBenefitsInKind>
						<Filler xmlns="http://www.iras.gov.sg/A8A2015"/>
						<FieldReserved xmlns="http://www.iras.gov.sg/A8A2015"/>
						</A8A2015ST>
						</ESubmissionSDSC>
						</A8ARecord>
						EOT;
						$dt = preg_replace("/\r|\n/", "", $dt);
						$detail_record .= $dt;

						$no_of_records += 1;
						$total_accommodation += $accommodation_amount;
						$total_utilities_housekeeping += $utilities_housekeeping;
						$total_hotel_accommodation += $hotel_accommodation_amount;
						$total_other_benefits += $other_benefit_cost;
					}
				}

				if ($emp_count > 0) {
					$detail_record .= "</Details>";
					$efiling_d = $this->Efiling_model->getEFilingDetails();
					$creation_date = date('Ymd');
					// XML File creation
					$xml_file_header = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><A8A2015 xmlns="http://www.iras.gov.sg/A8A2015Def">';

					$eh = "<A8AHeader>";
					$eh .= '<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">';
					$eh .= "<FileHeaderST>";
					$eh .= "<RecordType>0</RecordType>";
					$eh .= "<Source>6</Source>";
					$eh .= "<BasisYear>$year</BasisYear>";
					$eh .= "<OrganizationID>$efiling_d->organisation_id_type</OrganizationID>";
					$eh .= "<OrganizationIDNo>$efiling_d->organisation_id_no</OrganizationIDNo>";
					$eh .= "<AuthorisedPersonName>$efiling_d->authorised_name</AuthorisedPersonName>";
					$eh .= "<AuthorisedPersonDesignation>$efiling_d->authorised_designation</AuthorisedPersonDesignation>";
					$eh .= "<EmployerName>$efiling_d->authorised_name</EmployerName>";
					$eh .= "<Telephone>$efiling_d->authorised_name</Telephone>";
					$eh .= "<AuthorisedPersonEmail>$efiling_d->authorised_email</AuthorisedPersonEmail>";
					$eh .= "<BatchIndicator>O</BatchIndicator>";
					$eh .= "<BatchDate>$creation_date</BatchDate>";
					$eh .= "<DivisionOrBranchName/>";
					$eh .= "</FileHeaderST>";
					$eh .= "</ESubmissionSDSC>";
					$eh .= "</A8AHeader>";


					$xml_file_trailer = "</A8A2015>";
					$content = $xml_file_header . $eh . $detail_record . $xml_file_trailer;

					$filename = './uploads/efiling/appendix8a/appendix8a-' . date('YmdHis') . '.xml';

					$save_file = file_put_contents($filename, $content);
					if ($save_file) {
						$ap8a_data = array(
							'efiling_id' => $efiling_d->id,
							'submission_key' => $submission_key,
							'basis_year' => $year_end,
							'no_of_records' => $no_of_records,
							'ap8a_file' => $filename
						);
						if ($total_accommodation > 0) {
							$ap8a_data['total_accommodation'] = $total_accommodation;
						}
						if ($total_utilities_housekeeping > 0) {
							$ap8a_data['total_utilities_housekeeping'] = $total_utilities_housekeeping;
						}
						if ($total_hotel_accommodation > 0) {
							$ap8a_data['total_hotel_accommodation'] = $total_hotel_accommodation;
						}
						if ($total_other_benefits > 0) {
							$ap8a_data['total_other_benefits'] = $total_other_benefits;
						}

						$ap8a_submit = $this->Efiling_model->saveAp8aRecords($ap8a_data);
					}
				}

				$Return['result'] = 'Appendix 8A form generated for ' . $emp_count . ' employees';
			} else {
				$Return['error'] = 'No employees eligible for Appendix 8A';
			}
			$this->output($Return);
			exit;
		}
	}

	public function employeeAp8aForm($year)
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/efiling/ir8a_filing", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$ap8a_year = $year - 1;

		$data = array();

		$employee_ap8a = $this->Efiling_model->getAp8AEmployees($ap8a_year);
		if ($employee_ap8a) {
			foreach ($employee_ap8a->result() as $e) {

				$action = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
				<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $e->id . '" data-field_type="salary_allowance">
				<span class="fa fa-pencil"></span></button></span>';

				$action .= '<span data-toggle="tooltip" data-placement="top" title="Download Appendix 8A Document">
				<a href="' . site_url() . 'admin/efiling/download_appendix_8a_doc/' . $e->id  . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				// $action .= '<span data-toggle="tooltip" data-placement="top" title="Download IR8A PDF">
				// <a href="'.site_url().'admin/efiling/ir8a_pdf/p/'. $e->ir8a_key ?? 1 .'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				$action .= '<span class="ml-2" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $e->id . '" data-token_type="all_allowances"><span class="fa fa-trash"></span></button></span>';


				$data[] = array(
					$action,
					$e->first_name . ' ' . $e->last_name,
					$this->Xin_model->currency_sign($e->accommodation),
					$this->Xin_model->currency_sign($e->utilities_housekeeping),
					$this->Xin_model->currency_sign($e->hotel_accommodation),
					$this->Xin_model->currency_sign($e->other_benefits)
				);
			}
		}


		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_ap8a->num_rows(),
			"recordsFiltered" => $employee_ap8a->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function resetAp8a()
	{
		if ($this->input->post('type') == 'ap8a_reset_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;

			$reset_ap8a = $this->Efiling_model->resetAllAp8aRecordsByYear($year);

			if ($reset_ap8a) {
				$Return['result'] = 'Appendix 8A reset successfully';
			} else {
				$Return['error'] = 'Error, could not reset';
			}
			$this->output($Return);
			exit;
		}
	}

	public function irassubmission()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$year_a = '';
		$year = $this->uri->segment(5);
		if ($year) {
			$valid_year = (int)$year;
			$ayear = date('Y', strtotime("-6 year"));
			$fyear = date('Y', strtotime("+1 year"));

			if ($valid_year >= $ayear && $valid_year <= $fyear) {
				$year_a = $valid_year;
			} else {
				redirect('admin/efiling/appendix8a');
			}
		}
		if ($year_a == '') {
			$year_a = date('Y');
		}
		$year_b = $year_a - 1;
		$data['title'] = 'IRAS Submission';
		$data['breadcrumbs'] = 'IRAS Submission';
		$data['path_url'] = 'iras_submission';
		$data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$data['year_a'] = $year_a;
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$isGenerated = $this->Efiling_model->getAp8ARecordByYear($year_b);
		$data['is_generated'] = $isGenerated;
		if (in_array('428', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/efiling/iras_submission", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function validateIrasYear()
	{
		if ($this->input->post('type') == 'iras_validation_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;

			$ir8a_submission = $this->Efiling_model->getIr8aRecordByYear($year);
			$ap8a_submission = $this->Efiling_model->getAp8aRecordByYear($year);
			$ap8b_submission = $this->Efiling_model->getAp8bRecordByYear($year);
			$efiling = $this->Efiling_model->getEFilingDetails();

			$file_count = 0;
			$ir8a_file_data = "";
			$ap8a_file_data = "";
			$ap8b_file_data = "";


			if ($ir8a_submission) {
				$ir8a_file = $ir8a_submission->ir8a_file;
				$file_path = FCPATH . str_replace('./', '', $ir8a_file);
				if (file_exists($file_path)) {
					// file_put_contents($file_path,str_replace('"','\"',file_get_contents($file_path)));
					$file_count += 1;
					$ir8a_file_data = str_replace('"', '\"', file_get_contents($file_path));
				}
			}

			if ($ap8a_submission) {
				$ap8a_file = $ap8a_submission->ap8a_file;
				$file_path = FCPATH . str_replace('./', '', $ap8a_file);
				if (file_exists($file_path)) {
					// file_put_contents($file_path,str_replace('"','\"',file_get_contents($file_path)));
					$file_count += 1;
					$ap8a_file_data = str_replace('"', '\"', file_get_contents($file_path));
				}
			}

			if ($ap8b_submission) {
				$ap8b_file = $ap8b_submission->ap8b_file;
				$file_path = FCPATH . str_replace('./', '', $ap8b_file);
				if (file_exists($file_path)) {
					// file_put_contents($file_path,str_replace('"','\"',file_get_contents($file_path)));
					$file_count += 1;
					$ap8b_file_data = str_replace('"', '\"', file_get_contents($file_path));
				}
			}

			if ($file_count > 0) {
				if ($ir8a_file_data != '') {
					$ir8aInput = $ir8a_file_data;
				} else {
					$ir8aInput = null;
				}
				if ($ap8a_file_data != '') {
					$ap8aInput = $ap8a_file_data;
				} else {
					$ap8aInput = null;
				}
				if ($ap8b_file_data != '') {
					$ap8bInput = $ap8b_file_data;
				} else {
					$ap8bInput = null;
				}

				$authorised_user_id_type = $efiling->authorised_id_type;
				$authorised_user_id_no = $efiling->authorised_id_no;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://apisandbox.iras.gov.sg/iras/sb/AISubmission/submit');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($ch, CURLOPT_HEADER, 1);

				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "{
					\"validateOnly\": true,
					\"bypass\": false,
					\"ir8aInput\": \"$ir8aInput\",
					\"ir8sInput\": null,
					\"a8aInput\": \"$ap8aInput\",
					\"a8bInput\": \"$ap8bInput\",
					\"inputType\": \"XML\",
					\"clientID\" : \"6e2026c4-8b7a-48d1-8143-99ad983f410c\",
					\"userID\" : \"$authorised_user_id_no\",
					\"userIDType\" : \"$authorised_user_id_type\"
				}");

				$headers = array();

				// $headers[] = 'Access_token: REPLACE_THIS_VALUE';
				$headers[] = 'Content-Type: application/json';
				$headers[] = 'Accept: application/json';
				$headers[] = 'X-Ibm-Client-Id: 6e2026c4-8b7a-48d1-8143-99ad983f410c';
				$headers[] = 'X-Ibm-Client-Secret: oD7lP3wE0gE6kX6kE6nO8tQ3uB0uJ2pW2tA6sQ6aK8fT0gB1gB';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					// echo 'Error:' . curl_error($ch);
					$Return['error'] = curl_error($ch);
				}

				curl_close($ch);

				$Return['result'] = $result;
			} else {
				$Return['error'] = 'No forms to submit';
			}

			$this->output($Return);
			exit;
		}
	}

	public function submissionIrasYear()
	{
		if ($this->input->post('type') == 'iras_submission_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;

			$ir8a_submission = $this->Efiling_model->getIr8aRecordByYear($year);
			$ap8a_submission = $this->Efiling_model->getAp8aRecordByYear($year);
			$ap8b_submission = $this->Efiling_model->getAp8bRecordByYear($year);
			$efiling = $this->Efiling_model->getEFilingDetails();

			$file_count = 0;
			$ir8a_file_data = "";
			$ap8a_file_data = "";
			$ap8b_file_data = "";


			if ($ir8a_submission) {
				$ir8a_file = $ir8a_submission->ir8a_file;
				$file_path = FCPATH . str_replace('./', '', $ir8a_file);
				if (file_exists($file_path)) {
					// file_put_contents($file_path,str_replace('"','\"',file_get_contents($file_path)));
					$file_count += 1;
					$ir8a_file_data = str_replace('"', '\"', file_get_contents($file_path));
				}
			}

			if ($ap8a_submission) {
				$ap8a_file = $ap8a_submission->ap8a_file;
				$file_path = FCPATH . str_replace('./', '', $ap8a_file);
				if (file_exists($file_path)) {
					// file_put_contents($file_path,str_replace('"','\"',file_get_contents($file_path)));
					$file_count += 1;
					$ap8a_file_data = str_replace('"', '\"', file_get_contents($file_path));
				}
			}

			if ($ap8b_submission) {
				$ap8b_file = $ap8b_submission->ap8b_file;
				$file_path = FCPATH . str_replace('./', '', $ap8b_file);
				if (file_exists($file_path)) {
					// file_put_contents($file_path,str_replace('"','\"',file_get_contents($file_path)));
					$file_count += 1;
					$ap8b_file_data = str_replace('"', '\"', file_get_contents($file_path));
				}
			}

			if ($file_count > 0) {
				if ($ir8a_file_data != '') {
					$ir8aInput = $ir8a_file_data;
				} else {
					$ir8aInput = null;
				}
				if ($ap8a_file_data != '') {
					$ap8aInput = $ap8a_file_data;
				} else {
					$ap8aInput = null;
				}
				if ($ap8b_file_data != '') {
					$ap8bInput = $ap8b_file_data;
				} else {
					$ap8bInput = null;
				}

				$authorised_user_id_type = $efiling->authorised_id_type;
				$authorised_user_id_no = $efiling->authorised_id_no;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://apisandbox.iras.gov.sg/iras/sb/AISubmission/submit');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($ch, CURLOPT_HEADER, 1);

				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "{
					\"validateOnly\": false,
					\"bypass\": false,
					\"ir8aInput\": \"$ir8aInput\",
					\"ir8sInput\": null,
					\"a8aInput\": \"$ap8aInput\",
					\"a8bInput\": \"$ap8bInput\",
					\"inputType\": \"XML\",
					\"clientID\" : \"6e2026c4-8b7a-48d1-8143-99ad983f410c\",
					\"userID\" : \"$authorised_user_id_no\",
					\"userIDType\" : \"$authorised_user_id_type\"
				}");

				$headers = array();

				// $headers[] = 'Access_token: REPLACE_THIS_VALUE';
				$headers[] = 'Content-Type: application/json';
				$headers[] = 'Accept: application/json';
				$headers[] = 'X-Ibm-Client-Id: 6e2026c4-8b7a-48d1-8143-99ad983f410c';
				$headers[] = 'X-Ibm-Client-Secret: oD7lP3wE0gE6kX6kE6nO8tQ3uB0uJ2pW2tA6sQ6aK8fT0gB1gB';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				$curl_error = false;
				if (curl_errno($ch)) {
					$curl_error = true;
					$curl_error_msg = curl_error($ch);
					echo 'Curl Error ' . $curl_error;
				}
				curl_close($ch);

				if (!$curl_error) {
					$j_result = json_decode($result);
					$status_code = $j_result->statusCode;

					if ($status_code == 200) {
						if ($ir8a_submission) {
							$ir8a_id = $ir8a_submission->id;
							if (isset($j_result->ir8a->output)) {
								$ir8a_output = $j_result->ir8a->output;
								$ir8a_warnings = $j_result->ir8a->warnings;
								$ir8a_sd = [
									'status' => 1,
									'status_code' => $status_code,
									'submission_reference' => $ir8a_output,
									'submission_date' => date('Y-m-d H:i:s')
								];
								if (count($ir8a_warnings) > 0) {
									$ir8a_sd['response'] = $ir8a_warnings;
								}
								$this->Efiling_model->updateIr8aSubmission($ir8a_id, $ir8a_sd);
							}
						}

						if ($ap8a_submission) {
							$ap8a_id = $ap8a_submission->id;
							if (isset($j_result->a8a->output)) {
								$ap8a_output = $j_result->a8a->output;
								$ap8a_warnings = $j_result->a8a->warnings;
								$ap8a_sd = [
									'status' => 1,
									'status_code' => $status_code,
									'submission_reference' => $ap8a_output,
									'submission_date' => date('Y-m-d H:i:s')
								];
								if (count($ap8a_warnings) > 0) {
									$ap8a_sd['response'] = $ap8a_warnings;
								}
								$this->Efiling_model->updateAppendix8aSubmission($ap8a_id, $ap8a_sd);
							}
						}
					}
				}

				$Return['result'] = $result;
			} else {
				$Return['error'] = 'No forms to submit';
			}

			$this->output($Return);
			exit;
		}
	}

	/**Appendix 8B */
	public function appendix8b()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$year_a = '';
		$year = $this->uri->segment(5);
		if ($year) {
			$valid_year = (int)$year;
			$ayear = date('Y', strtotime("-6 year"));
			$fyear = date('Y', strtotime("+1 year"));

			if ($valid_year >= $ayear && $valid_year <= $fyear) {
				$year_a = $valid_year;
			} else {
				redirect('admin/efiling/appendix8b');
			}
		}
		if ($year_a == '') {
			$year_a = date('Y');
		}
		$year_b = $year_a - 1;
		$data['title'] = 'Appendix 8B';
		$data['breadcrumbs'] = 'Appendix 8B';
		$data['path_url'] = 'appendix8b_filing';
		$data['efiling'] = $this->Efiling_model->getEFilingDetails();
		$data['year_a'] = $year_a;
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$isGenerated = $this->Efiling_model->getAp8BRecordByYear($year_b);
		$data['is_generated'] = $isGenerated;
		if (in_array('428', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/efiling/appendix8b_filing", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function employeeSummaryAp8b($year)
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/efiling/ir8a_filing", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = array();

		$employee_summaries = $this->Efiling_model->get_all_employee_payslip_summary_8b($year);
		if ($employee_summaries) {
			foreach ($employee_summaries->result() as $s) {

				// $action = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $eu->id . '" data-field_type="salary_allowance"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $eu->id . '" data-token_type="all_allowances"><span class="fa fa-trash"></span></button></span>';
				$total_cpf = $s->tow_cpf_employee + $s->tow_cpf_employer + $s->taw_cpf_employee + $s->taw_cpf_employer;

				$data[] = array(
					$s->first_name . ' ' . $s->last_name,
					$s->country_name,
					$this->Xin_model->currency_sign($s->tgross_salary),
					$this->Xin_model->currency_sign($s->ir8a_d8),
					$s->ap8b_eligible
				);
			}
		}


		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_summaries->num_rows(),
			"recordsFiltered" => $employee_summaries->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function employeeAp8bForm($year)
	{
		//set data
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/efiling/ir8a_filing", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$ap8b_year = $year - 1;

		$data = array();

		$employee_ap8b = $this->Efiling_model->getAp8BEmployees($ap8b_year);
		if ($employee_ap8b) {
			foreach ($employee_ap8b->result() as $e) {

				$action = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
				<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $e->id . '" data-field_type="salary_allowance">
				<span class="fa fa-pencil"></span></button></span>';

				$action .= '<span data-toggle="tooltip" data-placement="top" title="Download Appendix 8B Doc">
				<a href="' . site_url() . 'admin/efiling/ap8b_docx/' . $e->ap8b_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				$action .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $e->id . '" data-token_type="all_allowances"><span class="fa fa-trash"></span></button></span>';


				$data[] = array(
					$action,
					$e->first_name . ' ' . $e->last_name,
					$this->Xin_model->currency_sign($e->gross_amount_eebr),
					$this->Xin_model->currency_sign($e->gross_amount_eris_sme),
					$this->Xin_model->currency_sign($e->gross_amount_eris_corp),
					$this->Xin_model->currency_sign($e->gross_amount_eris_startup)
				);
			}
		}


		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee_ap8b->num_rows(),
			"recordsFiltered" => $employee_ap8b->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function generateAp8b()
	{
		if ($this->input->post('type') == 'ap8b_generate_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;
			$employee_annual_share_options = $this->Efiling_model->getEmployeesAnnualShareOptions($year);
			$emp_count = 0;
			$no_of_records = 0;
			$secA_non_exempt_gross_amount_b = 0;
			$secA_non_exempt_gross_amount_g = 0;
			$secA_gains_gross_amount_b = 0;
			$secA_gains_gross_amount_g = 0;

			$secB_exempt_gross_amount_b = 0;
			$secB_exempt_gross_amount_g = 0;
			$secB_non_exempt_gross_amount_b = 0;
			$secB_non_exempt_gross_amount_g = 0;
			$secB_gains_gross_amount_b = 0;
			$secB_gains_gross_amount_g = 0;

			$secC_exempt_gross_amount_b = 0;
			$secC_exempt_gross_amount_g = 0;
			$secC_non_exempt_gross_amount_b = 0;
			$secC_non_exempt_gross_amount_g = 0;
			$secC_gains_gross_amount_b = 0;
			$secC_gains_gross_amount_g = 0;

			$secD_exempt_gross_amount_b = 0;
			$secD_exempt_gross_amount_g = 0;
			$secD_non_exempt_gross_amount_b = 0;
			$secD_non_exempt_gross_amount_g = 0;
			$secD_gains_gross_amount_b = 0;
			$secD_gains_gross_amount_g = 0;

			$secE_non_exempt_gross_amount_b = 0;
			$secE_non_exempt_gross_amount_g = 0;
			$secE_gains_gross_amount_b = 0;
			$secE_gains_gross_amount_g = 0;

			//xml detail record
			$detail_record = "<Details>";

			if ($employee_annual_share_options) {
				$submission_key =  random_string('alnum', 40);
				foreach ($employee_annual_share_options as $e) {
					$employee = $this->Xin_model->read_user_info($e->employee_id);
					$emp_id_type = $employee[0]->id_type;
					$emp_id = $employee[0]->id_no;
					$emp_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
					$nationality = $this->Employees_model->getEmployeeNationality($employee[0]->user_id);
					if ($nationality) {
						$employee_nationality = '<Nationality xmlns="http://www.iras.gov.sg/A8B2009">' . $nationality->iras_nationality_code . '</Nationality>';
					} else {
						$employee_nationality = '<Nationality xmlns="http://www.iras.gov.sg/A8B2009"/>';
					}
					$emp_gender = ($employee[0]->gender == 'Male') ? 'M' : 'F';
					$emp_dob = date('Ymd', strtotime($employee[0]->date_of_birth));

					$emp_record = <<<EOT
					<A8BRecord>
					<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">
					<A8B2009ST>
					<RecordType xmlns="http://www.iras.gov.sg/A8B2009">1</RecordType>
					<IDType xmlns="http://www.iras.gov.sg/A8B2009">$emp_id_type</IDType>
					<IDNo xmlns="http://www.iras.gov.sg/A8B2009">$emp_id</IDNo>
					<NameLine1 xmlns="http://www.iras.gov.sg/A8B2009">$emp_name</NameLine1>
					<NameLine2 xmlns="http://www.iras.gov.sg/A8B2009"/>
					$employee_nationality
					<Sex xmlns="http://www.iras.gov.sg/A8B2009">$emp_gender</Sex>
					<DateOfBirth xmlns="http://www.iras.gov.sg/A8B2009">$emp_dob</DateOfBirth>
					EOT;


					//eebr
					$eebr_gross_amount_nonexempt_g = 0;
					$eebr_gross_amount_nonexempt_b = 0;
					$eebr_gross_amount_gains_g = 0;
					$eebr_gross_amount_gains_b = 0;
					$eebr = $this->Efiling_model->getEmployeeGains($e->employee_id, $year, 1);
					if ($eebr) {
						$eebr_amount = 0;
						$record_count = 0;
						$eebr_detail = '';
						foreach ($eebr as $s) {
							++$record_count;
							$price_doe = $s->price_date_of_excercise;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;
							$amount = ($price_doe - $price_ex) * $no_shares;
							$eebr_amount += $amount;
							if ($s->date_of_grant < '2003-01-01') {
								$eebr_gross_amount_nonexempt_g += $amount;
								$eebr_gross_amount_gains_g += $amount;
							} elseif ($s->date_of_grant >= '2003-01-01') {
								$eebr_gross_amount_nonexempt_b += $amount;
								$eebr_gross_amount_gains_b += $amount;
							}


							$company_id_type = $s->organisation_id_type;
							$company_id_no = $s->organisation_id_no;
							$company_name = $s->company_name;
							$plan = ($s->so_plan == 1) ? 'ESOP' : 'ESOW';
							$date_grant = date('Ymd', strtotime('$s->date_of_grant'));
							$date_excercise = date('Ymd', strtotime('$s->date_of_excercise'));

							$eebr_detail .= <<<EOT
							<Record$record_count xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_type</CompanyIDType>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_no</CompanyIDNo>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes">$company_name</CompanyName>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes">$plan</PlanType>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$date_grant</DateOfGrant>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$date_excercise</DateOfExercise>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">$price_ex</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$price_doe</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">$no_shares</NoOfShares>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">$amount</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">$amount</GrossAmountGains>
							</Record$record_count>
							EOT;
						}
						if ($record_count < 15) {
							for ($i = $record_count + 1; $i <= 15; ++$i) {
								$eebr_detail .= <<<EOT
								<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
								<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
								<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0</OpenMarketValueAtDateOfGrant>
								<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
								<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
								<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
								<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
								</Record$i>
								EOT;
							}
						}
					} else {
						$eebr_detail = '';
						for ($i = 1; $i <= 15; $i++) {
							$eebr_detail .= <<<EOT
							<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
							</Record$i>
							EOT;
						}
					}
					$eebr_detail .= <<<EOT
					<SectionATotals xmlns="http://www.iras.gov.sg/A8B2009">
					<TotalGrossAmountNonExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eebr_gross_amount_nonexempt_b</TotalGrossAmountNonExemptAfter2003>
					<TotalGrossAmountNonExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eebr_gross_amount_nonexempt_g</TotalGrossAmountNonExemptBefore2003>
					<TotalGrossAmountGainsAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eebr_gross_amount_gains_b</TotalGrossAmountGainsAfter2003>
					<TotalGrossAmountGainsBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eebr_gross_amount_gains_g</TotalGrossAmountGainsBefore2003>
					</SectionATotals>
					EOT;
					$emp_record .= $eebr_detail;

					//eris sme
					$eris_gross_amount_exempt_b = 0; //after 2003
					$eris_gross_amount_exempt_g = 0; //before 2003
					$eris_gross_amount_non_exempt_b = 0; //after 2003
					$eris_gross_amount_non_exempt_g = 0; //before 2003
					$eris_gross_amount_gains_b = 0; //after 2003
					$eris_gross_amount_gains_g = 0; //before 2003
					$eris_sme = $this->Efiling_model->getEmployeeGains($e->employee_id, $year, 2);
					if ($eris_sme) {
						$record_count = 16;
						$eris_sme_detail = '';
						$eris_sme_amount = 0;

						foreach ($eris_sme as $s) {
							$price_doe = $s->price_date_of_excercise;
							$price_dog = $s->price_date_of_grant;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;

							$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
							$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
							$eris_sme_amount += $tax_exempt_amount + $tax_no_exempt_amount;

							if ($s->date_of_grant < '2003-01-01') {
								$eris_gross_amount_exempt_g += $tax_exempt_amount;
								$eris_gross_amount_non_exempt_g += $tax_no_exempt_amount;
								$eris_gross_amount_gains_g += $eris_sme_amount;
							} elseif ($s->date_of_grant >= '2003-01-01') {
								$eris_gross_amount_exempt_b += $tax_exempt_amount;
								$eris_gross_amount_non_exempt_b += $tax_no_exempt_amount;
								$eris_gross_amount_gains_b += $eris_sme_amount;
							}


							$company_id_type = $s->organisation_id_type;
							$company_id_no = $s->organisation_id_no;
							$company_name = $s->company_name;
							$plan = ($s->so_plan == 1) ? 'ESOP' : 'ESOW';
							$date_grant = date('Ymd', strtotime('$s->date_of_grant'));
							$date_excercise = date('Ymd', strtotime('$s->date_of_excercise'));

							$eris_sme_detail .= <<<EOT
							<Record$record_count xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_type</CompanyIDType>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_no</CompanyIDNo>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes">$company_name</CompanyName>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes">$plan</PlanType>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$date_grant</DateOfGrant>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$date_excercise</DateOfGrant>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">$price_ex</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$price_dog</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$price_doe</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">$no_shares</NoOfShares>
							<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">$tax_exempt_amount</ExemptGrossAmountUnderERIS>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">$tax_no_exempt_amount</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_sme_amount</GrossAmountGains>
							</Record$record_count>
							EOT;
						}
						if ($record_count < 30) {
							for ($i = $record_count + 1; $i <= 30; ++$i) {
								$eris_sme_detail .= <<<EOT
								<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
								<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
								<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfGrant>
								<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
								<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
								<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</ExemptGrossAmountUnderERIS>
								<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
								<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
								</Record$i>
								EOT;
							}
						}
					} else {
						$eris_sme_detail = '';
						for ($i = 16; $i <= 30; $i++) {
							$eris_sme_detail .= <<<EOT
							<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
							<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</ExemptGrossAmountUnderERIS>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
							</Record$i>
							EOT;
						}
					}
					$eris_sme_detail .= <<<EOT
					<SectionBTotals xmlns="http://www.iras.gov.sg/A8B2009">
					<TotalGrossAmountExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_gross_amount_exempt_b</TotalGrossAmountExemptAfter2003>
					<TotalGrossAmountExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_gross_amount_exempt_g</TotalGrossAmountExemptBefore2003>
					<TotalGrossAmountNonExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_gross_amount_non_exempt_b</TotalGrossAmountNonExemptAfter2003>
					<TotalGrossAmountNonExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_gross_amount_non_exempt_g</TotalGrossAmountNonExemptBefore2003>
					<TotalGrossAmountGainsAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_gross_amount_gains_b</TotalGrossAmountGainsAfter2003>
					<TotalGrossAmountGainsBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_gross_amount_gains_g</TotalGrossAmountGainsBefore2003>
					</SectionBTotals>
					EOT;
					$emp_record .= $eris_sme_detail;

					//eris corp
					$eris_corp_gross_amount_exempt_b = 0; //after 2003
					$eris_corp_gross_amount_exempt_g = 0; //before 2003
					$eris_corp_gross_amount_non_exempt_b = 0; //after 2003
					$eris_corp_gross_amount_non_exempt_g = 0; //before 2003
					$eris_corp_gross_amount_gains_b = 0; //after 2003
					$eris_corp_gross_amount_gains_g = 0; //before 2003
					$eris_corp = $this->Efiling_model->getEmployeeGains($e->employee_id, $year, 3);
					if ($eris_corp) {
						$record_count = 31;
						$eris_corp_detail = '';
						$eris_corp_amount = 0;

						foreach ($eris_corp as $s) {
							$price_doe = $s->price_date_of_excercise;
							$price_dog = $s->price_date_of_grant;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;

							$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
							$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
							$eris_corp_amount += $tax_exempt_amount + $tax_no_exempt_amount;

							if ($s->date_of_grant < '2003-01-01') {
								$eris_corp_gross_amount_exempt_g += $tax_exempt_amount;
								$eris_corp_gross_amount_non_exempt_g += $tax_no_exempt_amount;
								$eris_corp_gross_amount_gains_g += $eris_corp_amount;
							} elseif ($s->date_of_grant >= '2003-01-01') {
								$eris_corp_gross_amount_exempt_b += $tax_exempt_amount;
								$eris_corp_gross_amount_non_exempt_b += $tax_no_exempt_amount;
								$eris_corp_gross_amount_gains_b += $eris_corp_amount;
							}


							$company_id_type = $s->organisation_id_type;
							$company_id_no = $s->organisation_id_no;
							$company_name = $s->company_name;
							$plan = ($s->so_plan == 1) ? 'ESOP' : 'ESOW';
							$date_grant = date('Ymd', strtotime('$s->date_of_grant'));
							$date_excercise = date('Ymd', strtotime('$s->date_of_excercise'));

							$eris_corp_detail .= <<<EOT
							<Record$record_count xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_type</CompanyIDType>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_no</CompanyIDNo>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes">$company_name</CompanyName>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes">$plan</PlanType>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$date_grant</DateOfGrant>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$date_excercise</DateOfGrant>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">$price_ex</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$price_dog</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$price_doe</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">$no_shares</NoOfShares>
							<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">$tax_exempt_amount</ExemptGrossAmountUnderERIS>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">$tax_no_exempt_amount</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_sme_amount</GrossAmountGains>
							</Record$record_count>
							EOT;
						}
						if ($record_count < 45) {
							for ($i = $record_count + 1; $i <= 45; ++$i) {
								$eris_corp_detail .= <<<EOT
								<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
								<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
								<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfGrant>
								<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
								<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
								<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</ExemptGrossAmountUnderERIS>
								<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
								<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
								</Record$i>
								EOT;
							}
						}
					} else {
						$eris_corp_detail = '';
						for ($i = 31; $i <= 45; $i++) {
							$eris_corp_detail .= <<<EOT
							<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
							<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</ExemptGrossAmountUnderERIS>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
							</Record$i>
							EOT;
						}
					}
					$eris_corp_detail .= <<<EOT
					<SectionCTotals xmlns="http://www.iras.gov.sg/A8B2009">
					<TotalGrossAmountExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_corp_gross_amount_exempt_b</TotalGrossAmountExemptAfter2003>
					<TotalGrossAmountExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_corp_gross_amount_exempt_g</TotalGrossAmountExemptBefore2003>
					<TotalGrossAmountNonExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_corp_gross_amount_non_exempt_b</TotalGrossAmountNonExemptAfter2003>
					<TotalGrossAmountNonExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_corp_gross_amount_non_exempt_g</TotalGrossAmountNonExemptBefore2003>
					<TotalGrossAmountGainsAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_corp_gross_amount_gains_b</TotalGrossAmountGainsAfter2003>
					<TotalGrossAmountGainsBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_corp_gross_amount_gains_g</TotalGrossAmountGainsBefore2003>
					</SectionCTotals>
					EOT;
					$emp_record .= $eris_corp_detail;

					//eris startup
					$eris_startup_gross_amount_exempt_b = 0; //after 2003
					$eris_startup_gross_amount_exempt_g = 0; //before 2003
					$eris_startup_gross_amount_non_exempt_b = 0; //after 2003
					$eris_startup_gross_amount_non_exempt_g = 0; //before 2003
					$eris_startup_gross_amount_gains_b = 0; //after 2003
					$eris_startup_gross_amount_gains_g = 0; //before 2003
					$eris_startup = $this->Efiling_model->getEmployeeGains($e->employee_id, $year, 4);
					if ($eris_startup) {
						$record_count = 46;
						$eris_startup_detail = '';
						$eris_startup_amount = 0;

						foreach ($eris_startup as $s) {
							$price_doe = $s->price_date_of_excercise;
							$price_dog = $s->price_date_of_grant;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;

							$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
							$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
							$eris_startup_amount += $tax_exempt_amount + $tax_no_exempt_amount;

							if ($s->date_of_grant < '2003-01-01') {
								$eris_startup_gross_amount_exempt_g += $tax_exempt_amount;
								$eris_startup_gross_amount_non_exempt_g += $tax_no_exempt_amount;
								$eris_startup_gross_amount_gains_g += $eris_startup_amount;
							} elseif ($s->date_of_grant >= '2003-01-01') {
								$eris_startup_gross_amount_exempt_b += $tax_exempt_amount;
								$eris_startup_gross_amount_non_exempt_b += $tax_no_exempt_amount;
								$eris_startup_gross_amount_gains_b += $eris_startup_amount;
							}


							$company_id_type = $s->organisation_id_type;
							$company_id_no = $s->organisation_id_no;
							$company_name = $s->company_name;
							$plan = ($s->so_plan == 1) ? 'ESOP' : 'ESOW';
							$date_grant = date('Ymd', strtotime('$s->date_of_grant'));
							$date_excercise = date('Ymd', strtotime('$s->date_of_excercise'));

							$eris_startup_detail .= <<<EOT
							<Record$record_count xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_type</CompanyIDType>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes">$company_id_no</CompanyIDNo>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes">$company_name</CompanyName>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes">$plan</PlanType>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$date_grant</DateOfGrant>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$date_excercise</DateOfGrant>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">$price_ex</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">$price_dog</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">$price_doe</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">$no_shares</NoOfShares>
							<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">$tax_exempt_amount</ExemptGrossAmountUnderERIS>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">$tax_no_exempt_amount</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_amount</GrossAmountGains>
							</Record$record_count>
							EOT;
						}
						if ($record_count < 60) {
							for ($i = $record_count + 1; $i <= 60; ++$i) {
								$eris_startup_detail .= <<<EOT
								<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
								<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
								<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
								<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfGrant>
								<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
								<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
								<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</ExemptGrossAmountUnderERIS>
								<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
								<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
								</Record$i>
								EOT;
							}
						}
					} else {
						$eris_startup_detail = '';
						for ($i = 46; $i <= 60; $i++) {
							$eris_startup_detail .= <<<EOT
							<Record$i xmlns="http://www.iras.gov.sg/A8B2009">
							<CompanyIDType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyIDNo xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<CompanyName xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<PlanType xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<DateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes"/>
							<Price xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</Price>
							<OpenMarketValueAtDateOfGrant xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfGrant>
							<OpenMarketValueAtDateOfExercise xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</OpenMarketValueAtDateOfExercise>
							<NoOfShares xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NoOfShares>
							<ExemptGrossAmountUnderERIS xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</ExemptGrossAmountUnderERIS>
							<NonExemptGrossAmount xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</NonExemptGrossAmount>
							<GrossAmountGains xmlns="http://www.iras.gov.sg/SchemaTypes">0.0</GrossAmountGains>
							</Record$i>
							EOT;
						}
					}
					$eris_startup_detail .= <<<EOT
					<SectionDTotals xmlns="http://www.iras.gov.sg/A8B2009">
					<TotalGrossAmountExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_gross_amount_exempt_b</TotalGrossAmountExemptAfter2003>
					<TotalGrossAmountExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_gross_amount_exempt_g</TotalGrossAmountExemptBefore2003>
					<TotalGrossAmountNonExemptAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_gross_amount_non_exempt_b</TotalGrossAmountNonExemptAfter2003>
					<TotalGrossAmountNonExemptBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_gross_amount_non_exempt_g</TotalGrossAmountNonExemptBefore2003>
					<TotalGrossAmountGainsAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_gross_amount_gains_b</TotalGrossAmountGainsAfter2003>
					<TotalGrossAmountGainsBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$eris_startup_gross_amount_gains_g</TotalGrossAmountGainsBefore2003>
					</SectionDTotals>
					EOT;
					$emp_record .= $eris_startup_detail;

					//section E - total
					$non_exempt_gross_amount_b = round($eebr_gross_amount_nonexempt_b + $eris_gross_amount_non_exempt_b + $eris_corp_gross_amount_non_exempt_b + $eris_startup_gross_amount_non_exempt_b, 2);

					$non_exempt_gross_amount_g = round($eebr_gross_amount_nonexempt_g + $eris_gross_amount_non_exempt_g + $eris_corp_gross_amount_non_exempt_g + $eris_startup_gross_amount_non_exempt_g, 2);

					$gains_gross_amount_b = round($eebr_gross_amount_gains_b + $eris_gross_amount_gains_b + $eris_corp_gross_amount_gains_b + $eris_startup_gross_amount_gains_b, 2);

					$gains_gross_amount_g = round($eebr_gross_amount_gains_g + $eris_gross_amount_gains_g + $eris_corp_gross_amount_gains_g + $eris_startup_gross_amount_gains_g, 2);

					$total_detail = <<<EOT
					<SectionE xmlns="http://www.iras.gov.sg/A8B2009">
					<NonExemptGrandTotalGrossAmountAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$non_exempt_gross_amount_b</NonExemptGrandTotalGrossAmountAfter2003>
					<NonExemptGrandTotalGrossAmountBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$non_exempt_gross_amount_g</NonExemptGrandTotalGrossAmountBefore2003>
					<GainsGrandTotalGrossAmountAfter2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$gains_gross_amount_b</GainsGrandTotalGrossAmountAfter2003>
					<GainsGrandTotalGrossAmountBefore2003 xmlns="http://www.iras.gov.sg/SchemaTypes">$gains_gross_amount_g</GainsGrandTotalGrossAmountBefore2003>
					<Remarks xmlns="http://www.iras.gov.sg/SchemaTypes"/>
					<Filler xmlns="http://www.iras.gov.sg/SchemaTypes"/>
					</SectionE>
					EOT;

					$emp_record .= $total_detail;

					$emp_record .= "</A8B2009ST></ESubmissionSDSC></A8BRecord>";
					$detail_record .= $emp_record;

					if ($gains_gross_amount_b > 0 || $gains_gross_amount_g > 0) {
						$jurl = random_string('alnum', 40);
						$edata = array(
							'ap8b_key' => $jurl,
							'submission_key' => $submission_key,
							'employee_id' => $e->employee_id,
							'ap8b_year' => $year
						);
						$gains_gross_amount_eebr = round($eebr_gross_amount_gains_b + $eebr_gross_amount_gains_g, 2);
						if ($gains_gross_amount_eebr > 0) {
							$edata['gross_amount_eebr'] = $gains_gross_amount_eebr;
						}

						$gains_gross_amount_eris_sme = round($eris_gross_amount_gains_b + $eris_gross_amount_gains_g, 2);
						if ($gains_gross_amount_eris_sme > 0) {
							$edata['gross_amount_eris_sme'] = $gains_gross_amount_eris_sme;
						}

						$gains_gross_amount_eris_corp = round($eris_corp_gross_amount_gains_b + $eris_corp_gross_amount_gains_g, 2);
						if ($gains_gross_amount_eris_corp > 0) {
							$edata['gross_amount_eris_corp'] = $gains_gross_amount_eris_corp;
						}

						$gains_gross_amount_eris_startup = round($eris_startup_gross_amount_gains_b + $eris_startup_gross_amount_gains_g, 2);
						if ($gains_gross_amount_eris_startup > 0) {
							$edata['gross_amount_eris_startup'] = $gains_gross_amount_eris_startup;
						}

						$ap8b_result = $this->Efiling_model->setAp8bEmployee($edata);
						if ($ap8b_result) {
							$emp_count += 1;
						}

						$no_of_records += 1;
						$secA_non_exempt_gross_amount_b += $eebr_gross_amount_nonexempt_b;
						$secA_non_exempt_gross_amount_g += $eebr_gross_amount_nonexempt_g;
						$secA_gains_gross_amount_b += $eebr_gross_amount_gains_b;
						$secA_gains_gross_amount_g += $eebr_gross_amount_gains_g;

						$secB_exempt_gross_amount_b += $eris_gross_amount_exempt_b;
						$secB_exempt_gross_amount_g += $eris_gross_amount_exempt_g;
						$secB_non_exempt_gross_amount_b += $eris_gross_amount_non_exempt_b;
						$secB_non_exempt_gross_amount_g += $eris_gross_amount_non_exempt_g;
						$secB_gains_gross_amount_b += $eris_gross_amount_gains_b;
						$secB_gains_gross_amount_g += $eris_gross_amount_gains_g;

						$secC_exempt_gross_amount_b += $eris_corp_gross_amount_exempt_b;
						$secC_exempt_gross_amount_g += $eris_corp_gross_amount_exempt_g;
						$secC_non_exempt_gross_amount_b += $eris_corp_gross_amount_non_exempt_b;
						$secC_non_exempt_gross_amount_g += $eris_corp_gross_amount_non_exempt_g;
						$secC_gains_gross_amount_b += $eris_corp_gross_amount_gains_b;
						$secC_gains_gross_amount_g += $eris_corp_gross_amount_gains_g;

						$secD_exempt_gross_amount_b += $eris_startup_gross_amount_exempt_b;
						$secD_exempt_gross_amount_g += $eris_startup_gross_amount_exempt_g;
						$secD_non_exempt_gross_amount_b += $eris_startup_gross_amount_non_exempt_b;
						$secD_non_exempt_gross_amount_g += $eris_startup_gross_amount_non_exempt_g;
						$secD_gains_gross_amount_b += $eris_startup_gross_amount_gains_b;
						$secD_gains_gross_amount_g += $eris_startup_gross_amount_gains_g;

						$secE_non_exempt_gross_amount_b += $non_exempt_gross_amount_b;
						$secE_non_exempt_gross_amount_g += $non_exempt_gross_amount_g;
						$secE_gains_gross_amount_b += $gains_gross_amount_b;
						$secE_gains_gross_amount_g += $gains_gross_amount_g;
					}
				}

				if ($emp_count > 0) {
					$detail_record .= "</Details>";
					$efiling_d = $this->Efiling_model->getEFilingDetails();
					$creation_date = date('Ymd');
					// XML File creation
					$xml_file_header = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><A8B2009 xmlns="http://www.iras.gov.sg/A8BDef2009">';

					$eh = "<A8BHeader>";
					$eh .= '<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">';
					$eh .= "<FileHeaderST>";
					$eh .= "<RecordType>0</RecordType>";
					$eh .= "<Source>6</Source>";
					$eh .= "<BasisYear>$year</BasisYear>";
					$eh .= "<PaymentType>13</PaymentType>";
					$eh .= "<OrganizationID>$efiling_d->organisation_id_type</OrganizationID>";
					$eh .= "<OrganizationIDNo>$efiling_d->organisation_id_no</OrganizationIDNo>";
					$eh .= "<AuthorisedPersonName>$efiling_d->authorised_name</AuthorisedPersonName>";
					$eh .= "<AuthorisedPersonDesignation>$efiling_d->authorised_designation</AuthorisedPersonDesignation>";
					$eh .= "<EmployerName>$efiling_d->authorised_name</EmployerName>";
					$eh .= "<Telephone>$efiling_d->authorised_name</Telephone>";
					$eh .= "<AuthorisedPersonEmail>$efiling_d->authorised_email</AuthorisedPersonEmail>";
					$eh .= "<BatchIndicator>O</BatchIndicator>";
					$eh .= "<BatchDate>$creation_date</BatchDate>";
					$eh .= "<IncorporationDate/>";
					$eh .= "<DivisionOrBranchName/>";
					$eh .= "</FileHeaderST>";
					$eh .= "</ESubmissionSDSC>";
					$eh .= "</A8BHeader>";

					$xml_file_trailer = "</A8B2009>";

					$trailer_detail = <<<EOT
					<A8BTrailer>
					<ESubmissionSDSC xmlns="http://tempuri.org/ESubmissionSDSC.xsd">
					<A8BTrailer2009ST>
					<RecordType>2</RecordType>
					<NoOfRecords>$no_of_records</NoOfRecords>
					<SectionATrailerNonExemptTotalGrossAmountAfter2003>$secA_non_exempt_gross_amount_b</SectionATrailerNonExemptTotalGrossAmountAfter2003>
					<SectionATrailerNonExemptTotalGrossAmountBefore2003>$secA_non_exempt_gross_amount_g</SectionATrailerNonExemptTotalGrossAmountBefore2003>
					<SectionATrailerGainsTotalGrossAmountAfter2003>$secA_gains_gross_amount_b</SectionATrailerGainsTotalGrossAmountAfter2003>
					<SectionATrailerGainsTotalGrossAmountBefore2003>$secA_gains_gross_amount_g</SectionATrailerGainsTotalGrossAmountBefore2003>
					<SectionBTrailerExemptTotalGrossAmountAfter2003>$secB_exempt_gross_amount_b</SectionBTrailerExemptTotalGrossAmountAfter2003>
					<SectionBTrailerExemptTotalGrossAmountBefore2003>$secB_exempt_gross_amount_g</SectionBTrailerExemptTotalGrossAmountBefore2003>
					<SectionBTrailerNonExemptTotalGrossAmountAfter2003>$secB_non_exempt_gross_amount_b</SectionBTrailerNonExemptTotalGrossAmountAfter2003>
					<SectionBTrailerNonExemptTotalGrossAmountBefore2003>$secB_non_exempt_gross_amount_g</SectionBTrailerNonExemptTotalGrossAmountBefore2003>
					<SectionBTrailerGainsTotalGrossAmountAfter2003>$secB_gains_gross_amount_b</SectionBTrailerGainsTotalGrossAmountAfter2003>
					<SectionBTrailerGainsTotalGrossAmountBefore2003>$secB_gains_gross_amount_g</SectionBTrailerGainsTotalGrossAmountBefore2003>
					<SectionCTrailerExemptTotalGrossAmountAfter2003>$secC_exempt_gross_amount_b</SectionCTrailerExemptTotalGrossAmountAfter2003>
					<SectionCTrailerExemptTotalGrossAmountBefore2003>$secC_exempt_gross_amount_g</SectionCTrailerExemptTotalGrossAmountBefore2003>
					<SectionCTrailerNonExemptTotalGrossAmountAfter2003>$secC_non_exempt_gross_amount_b</SectionCTrailerNonExemptTotalGrossAmountAfter2003>
					<SectionCTrailerNonExemptTotalGrossAmountBefore2003>$secC_non_exempt_gross_amount_g</SectionCTrailerNonExemptTotalGrossAmountBefore2003>
					<SectionCTrailerGainsTotalGrossAmountAfter2003>$secC_gains_gross_amount_b</SectionCTrailerGainsTotalGrossAmountAfter2003>
					<SectionCTrailerGainsTotalGrossAmountBefore2003>$secC_gains_gross_amount_g</SectionCTrailerGainsTotalGrossAmountBefore2003>
					<SectionDTrailerExemptTotalGrossAmountAfter2003>$secD_exempt_gross_amount_b</SectionDTrailerExemptTotalGrossAmountAfter2003>
					<SectionDTrailerExemptTotalGrossAmountBefore2003>$secD_exempt_gross_amount_g</SectionDTrailerExemptTotalGrossAmountBefore2003>
					<SectionDTrailerNonExemptTotalGrossAmountAfter2003>$secD_non_exempt_gross_amount_b</SectionDTrailerNonExemptTotalGrossAmountAfter2003>
					<SectionDTrailerNonExemptTotalGrossAmountBefore2003>$secD_non_exempt_gross_amount_g</SectionDTrailerNonExemptTotalGrossAmountBefore2003>
					<SectionDTrailerGainsTotalGrossAmountAfter2003>$secD_gains_gross_amount_b</SectionDTrailerGainsTotalGrossAmountAfter2003>
					<SectionDTrailerGainsTotalGrossAmountBefore2003>$secD_gains_gross_amount_g</SectionDTrailerGainsTotalGrossAmountBefore2003>
					<SectionETrailerNonExemptGrandTotalGrossAmountAfter2003>$secE_non_exempt_gross_amount_b</SectionETrailerNonExemptGrandTotalGrossAmountAfter2003>
					<SectionETrailerNonExemptGrandTotalGrossAmountBefore2003>$secE_non_exempt_gross_amount_g</SectionETrailerNonExemptGrandTotalGrossAmountBefore2003>
					<SectionETrailerGainsGrandTotalGrossAmountAfter2003>$secE_gains_gross_amount_b</SectionETrailerGainsGrandTotalGrossAmountAfter2003>
					<SectionETrailerGainsGrandTotalGrossAmountBefore2003>$secE_gains_gross_amount_g</SectionETrailerGainsGrandTotalGrossAmountBefore2003>
					<Filler/>
					</A8BTrailer2009ST>
					</ESubmissionSDSC>
					</A8BTrailer>
					EOT;

					$content = $xml_file_header . $eh . $detail_record . $trailer_detail . $xml_file_trailer;
					$content = preg_replace("/\r|\n/", "", $content);
					$Return['result'] = $content;

					$filename = './uploads/efiling/appendix8b/appendix8b-' . date('YmdHis') . '.xml';

					$save_file = file_put_contents($filename, $content);
					if ($save_file) {
						$ap8b_data = array(
							'efiling_id' => $efiling_d->id,
							'submission_key' => $submission_key,
							'basis_year' => $year,
							'no_of_records' => $no_of_records,
							'ap8b_file' => $filename
						);
						$total_gross_amount_eebr = round($secA_gains_gross_amount_b + $secA_gains_gross_amount_g, 2);
						if ($total_gross_amount_eebr > 0) {
							$ap8b_data['total_gross_amount_eebr'] = $total_gross_amount_eebr;
						}

						$total_gross_amount_eris_sme = round($secB_gains_gross_amount_b + $secB_gains_gross_amount_g, 2);
						if ($total_gross_amount_eris_sme > 0) {
							$ap8b_data['total_gross_amount_eris_sme'] = $total_gross_amount_eris_sme;
						}

						$total_gross_amount_eris_corp = round($secC_gains_gross_amount_b + $secC_gains_gross_amount_g, 2);
						if ($total_gross_amount_eris_corp > 0) {
							$ap8b_data['total_gross_amount_eris_corp'] = $total_gross_amount_eris_corp;
						}

						$total_gross_amount_eris_startup = round($secD_gains_gross_amount_b + $secD_gains_gross_amount_g, 2);
						if ($total_gross_amount_eris_startup > 0) {
							$ap8b_data['total_gross_amount_eris_startup'] = $total_gross_amount_eris_startup;
						}

						$ap8b_submit = $this->Efiling_model->saveAp8bRecords($ap8b_data);
					}
				}

				$Return['result'] = 'Appendix 8B form generated for ' . $emp_count . ' employees';
			} else {
				$Return['error'] = 'No employees eligible for Appendix 8B';
			}
			$this->output($Return);
			exit;
		}
	}

	public function resetAp8b()
	{
		if ($this->input->post('type') == 'ap8b_reset_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('year') === '') {
				$Return['error'] = 'Year of Assessment is required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$year = $this->input->post('year') - 1;

			$reset_ap8a = $this->Efiling_model->resetAllAp8bRecordsByYear($year);

			if ($reset_ap8a) {
				$Return['result'] = 'Appendix 8B reset successfully';
			} else {
				$Return['error'] = 'Error, could not reset';
			}
			$this->output($Return);
			exit;
		}
	}

	public function ap8b_docx($id)
	{
		include_once(APPPATH . "third_party/PhpWord/TemplateProcessor.php");
		include_once(APPPATH . "third_party/PhpWord/Settings.php");
		include_once(APPPATH . "third_party/PhpWord/Shared/ZipArchive.php");
		include_once(APPPATH . "third_party/PhpWord/Shared/Text.php");

		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(FCPATH . '/uploads/appendix-8b.docx');

		$get_appendix8a = $this->Efiling_model->getAppendix8BEmployees($id);

		$user_info = $this->Xin_model->read_user_info($get_appendix8a[0]->employee_id);



		if ($user_info[0]->address) {
			$employee_address = $user_info[0]->address;
		} else {
			$employee_address = '';
		}
		if ($user_info[0]->state) {
			$employee_address .= ', ' . $user_info[0]->state;
		}
		if ($user_info[0]->city) {
			$employee_address .= ', ' . $user_info[0]->city;
		}

		$company = $this->Xin_model->read_company_info($user_info[0]->company_id);
		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '';
			}
		} else {
			$company_name = '';
			$address_1 = '';
			$address_2 = '';
			$city = '';
			$state = '';
			$zipcode = '';
			$country_name = '';
		}

		$company_address = '';

		if ($address_1) {
			$company_address .= $address_1;
		}
		if ($address_2) {
			$company_address .= ', ' . $address_2;
		}
		if ($city) {
			$company_address .= ', ' . $city;
		}
		if ($state) {
			$company_address .= ', ' . $state;
		}
		if ($country_name) {
			$company_address .= ', ' . $country_name;
		}
		if ($zipcode) {
			$company_address .= ' - ' . $zipcode;
		}

		$efiling_d = $this->Efiling_model->getEFilingDetails();
		$employer_authorised_person = $efiling_d->authorised_name;
		$authorised_person_designation = $efiling_d->authorised_designation;
		$authorised_person_phone = $efiling_d->authorised_phone;

		$date_created = date('d/m/y', strtotime($get_appendix8a[0]->created_at));
		$fa_year = intval($get_appendix8a[0]->ap8b_year) + 1;
		$templateProcessor->setValues([
			'year'				=>	$fa_year,
			'employee_name'     => 	$user_info[0]->first_name . ' ' . $user_info[0]->last_name,
			'tax_no'			=> 	$user_info[0]->id_no,
			'address'			=> 	$employee_address,
			'rg_no'				=> 	$company[0]->registration_no ?? '',
			'employer_name' 	=> 	$company_name,
			'employer_address' 	=> 	$company_address,
			'employer_person'	=> $employer_authorised_person,
			'designation'	=> $authorised_person_designation,
			'phone'			=> $authorised_person_phone,
			'date_created'	=>	$date_created
		]);

		// print_r([
		// 	'employee_name'     => 	$user_info[0]->first_name . ' ' . $user_info[0]->last_name,
		// 	'tax_no'			=> 	$user_info[0]->id_no,
		// 	// 'address'			=> 	$get_employee_accommodations[0]->address_line_1 ?? '----',
		// 	'address'			=> 	$employee_address,
		// 	'period_occupation'	=>	isset($get_employee_accommodations) && count($get_employee_accommodations) > 0 ? $get_employee_accommodations[0]->period_from . ' to ' . $get_employee_accommodations[0]->period_to : '',
		// 	'sharing_premises'	=> 	$get_appendix8a[0]->shared_accommodation ?? '',
		// 	'no_of_days'		=>	$days,
		// 	'annual_value'		=>	$annual_value,
		// 	'furnished'			=> 	$furniture_value,
		// 	'rent_paid'			=>	isset($rent_paid) && !empty($rent_paid)   ? $rent_paid : '0.00',
		// 	'2a+2b'				=> $accommodation_amount,
		// 	'paid_rent'			=>	isset($rent_paid) && !empty($rent_paid)  ? $rent_paid : '0.00',
		// 	'2d-2e'				=>	$accommodation_amount - $rent_paid,
		// 	'utilities'			=>	$get_appendix8a[0]->utilities_amount ?? "0.00",
		// 	'driver'			=>	$get_appendix8a[0]->driver_benefit ?? "0.00",
		// 	'housekeeping'		=> 	$get_appendix8a[0]->housekeeping_benefit ?? "0.00",
		// 	'2g+2h+2i'			=> 	$get_appendix8a[0]->utilities_housekeeping ?? '0.00',
		// 	'actual_cost'		=>	$actual_cost,
		// 	'em_paid'			=> 	!empty($employee_paid) && isset($employee_paid) ? $employee_paid : '0.00',
		// 	'3a-3b'				=> 	$get_appendix8a[0]->hotel_accommodation ?? '0.00',
		// 	'pass_incident'		=>	number_format($pass_incident, 2),
		// 	'interest_pay'		=> 	number_format($interest_pay, 2),
		// 	'insurance'			=> 	number_format($insurance, 2),
		// 	'free_subsi'		=> 	number_format($free_subsi, 2),
		// 	'education'			=> 	number_format($education, 2),
		// 	'social'			=> 	number_format($social, 2),
		// 	'gains'				=> 	number_format($gains, 2),
		// 	'motor'				=> 	number_format($motor, 2),
		// 	'car_ben'			=> 	number_format($car_ben, 2),
		// 	'other'				=> 	number_format($other, 2),
		// 	'employer_name' 	=> 	$company_name,
		// 	'employer_address' 	=> 	$company_address,
		// 	'employer_authorised_person'	=> $employer_authorised_person,
		// 	'authorised_person_designation'	=> $authorised_person_designation,
		// 	'authorised_person_phone'	=> $authorised_person_phone,
		// 	'date_created'	=>	$date_created
		// ]);
		// exit;

		header("Content-Disposition: attachment; filename=" . $user_info[0]->first_name . " " . $user_info[0]->last_name . " appendix8b.docx");

		$templateProcessor->saveAs('php://output');
	}



	public function download_appendix_8a_doc($id)
	{
		include_once(APPPATH . "third_party/PhpWord/TemplateProcessor.php");
		include_once(APPPATH . "third_party/PhpWord/Settings.php");
		include_once(APPPATH . "third_party/PhpWord/Shared/ZipArchive.php");
		include_once(APPPATH . "third_party/PhpWord/Shared/Text.php");

		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(FCPATH . '/uploads/Appendix-8A.docx');

		$get_appendix8a = $this->Efiling_model->getAppendix8AEmployees($id);

		$user_info = $this->Xin_model->read_user_info($get_appendix8a[0]->employee_id);

		$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($get_appendix8a[0]->employee_id);
		$days = 0;
		if ($get_employee_accommodations) {
			$period_from 	= 	new DateTime($get_employee_accommodations[0]->period_from);
			$period_to		=	new DateTime($get_employee_accommodations[0]->period_to);
			$interval = $period_from->diff($period_to);
			$days = $interval->days + 1;
		}

		$accommodation_amount = 0;
		$annual_value = 0;
		$furniture_value = 0;
		$rent_paid = 0;
		$accommodation = $this->Efiling_model->getEmployeeAccommodation($get_appendix8a[0]->employee_id, $get_appendix8a[0]->ap8a_year);
		if ($accommodation) {
			$ac_id = $accommodation->accommodation_id;
			$ac_type = $accommodation->accommodation_type;
			$rent_paid = $accommodation->rent_paid;
			$ac_from = new DateTime($accommodation->period_from);
			$ac_to = new DateTime($accommodation->period_to);
			// $ac_days = $ac_to->diff($ac_from)->days;

			$ac_days = $ac_to->diff($ac_from)->days + 1;

			$shared_accommodation = $this->Efiling_model->getSharedAccommodationCount($accommodation->period_from, $accommodation->period_to, $ac_id);
			if ($ac_type == 1) {
				$ac_annual_value = $accommodation->annual_value;
				$ac_furnished = $accommodation->furnished_type;
				$annual_value = round(($ac_annual_value / $shared_accommodation) * ($ac_days / 365), 2);
				if ($ac_furnished == 1) {
					$furniture_value = round($annual_value * 50 / 100, 2);
				} else {
					$furniture_value = round($annual_value * 40 / 100, 2);
				}
				$accommodation_amount = round($annual_value + $furniture_value, 2);
				if ($rent_paid != '') {
					$accommodation_amount = round($accommodation_amount - $rent_paid, 2);
				}
			} else {
				$annual_rent_value = $accommodation->rent_value;
				$annual_value = round(($annual_rent_value / $shared_accommodation) * ($ac_days / 365), 2);
				$accommodation_amount = $annual_value;
				if ($rent_paid != '') {
					$accommodation_amount = $accommodation_amount - $rent_paid;
				}
				$annual_value = 0;
			}
		}


		$hotel_accommodation = $this->Efiling_model->getEmployeeHotelAccommodationBenefit($get_appendix8a[0]->employee_id, $get_appendix8a[0]->ap8a_year);
		$actual_cost = 0;
		$employee_paid = 0;
		$hotel_accommodation_amount = 0;
		if ($hotel_accommodation) {
			foreach ($hotel_accommodation as $ha) {
				$actual_cost = $ha->actual_cost;
				$employee_paid = $ha->employee_paid;
				if ($employee_paid != '') {
					$ha_amount = $actual_cost - $employee_paid;
					$hotel_accommodation_amount += $ha_amount;
				} else {
					$hotel_accommodation_amount += $actual_cost;
				}
			}
		}


		$get_other_benifit = $this->Efiling_model->getEmployeeOtherBenefitDownload($get_appendix8a[0]->employee_id, $get_appendix8a[0]->ap8a_year);
		$pass_incident = 0;
		$interest_pay = 0;
		$insurance = 0;
		$free_subsi = 0;
		$education = 0;
		$social = 0;
		$gains = 0;
		$motor = 0;
		$car_ben = 0;
		$other = 0;
		foreach ($get_other_benifit as $list) {
			if ($list->other_benefit == 'Home Leave Passage & Incidental Benefit')
				$pass_incident += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Interest Payment')
				$interest_pay += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Insurance Premiums')
				$insurance += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Free or Subsidised Holidays')
				$free_subsi += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Educational expenses')
				$education += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Social or Recreational clubs Fee')
				$social += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Gains from Assets sold to Employee')
				$gains += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Motor Vehicle cost given to Employee')
				$motor += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Car Benefits')
				$car_ben += $list->other_benefit_cost;
			else if ($list->other_benefit == 'Other Benefit')
				$other += $list->other_benefit_cost;
		}


		if ($user_info[0]->address) {
			$employee_address = $user_info[0]->address;
		} else {
			$employee_address = '';
		}
		if ($user_info[0]->state) {
			$employee_address .= ', ' . $user_info[0]->state;
		}
		if ($user_info[0]->city) {
			$employee_address .= ', ' . $user_info[0]->city;
		}

		$company = $this->Xin_model->read_company_info($user_info[0]->company_id);
		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '';
			}
		} else {
			$company_name = '';
			$address_1 = '';
			$address_2 = '';
			$city = '';
			$state = '';
			$zipcode = '';
			$country_name = '';
		}

		$company_address = '';

		if ($address_1) {
			$company_address .= $address_1;
		}
		if ($address_2) {
			$company_address .= ', ' . $address_2;
		}
		if ($city) {
			$company_address .= ', ' . $city;
		}
		if ($state) {
			$company_address .= ', ' . $state;
		}
		if ($country_name) {
			$company_address .= ', ' . $country_name;
		}
		if ($zipcode) {
			$company_address .= ' - ' . $zipcode;
		}

		$efiling_d = $this->Efiling_model->getEFilingDetails();
		$employer_authorised_person = $efiling_d->authorised_name;
		$authorised_person_designation = $efiling_d->authorised_designation;
		$authorised_person_phone = $efiling_d->authorised_phone;

		$date_created = date('d/m/y', strtotime($get_appendix8a[0]->created_at));
		$fa_year = intval($get_appendix8a[0]->ap8a_year) + 1;
		$templateProcessor->setValues([
			'year'				=>	$fa_year,
			'employee_name'     => 	$user_info[0]->first_name . ' ' . $user_info[0]->last_name,
			'tax_no'			=> 	$user_info[0]->id_no,
			// 'address'			=> 	$get_employee_accommodations[0]->address_line_1 ?? '----',
			'address'			=> 	$employee_address,
			'period_occupation'	=>	isset($get_employee_accommodations) && count($get_employee_accommodations) > 0 ? $get_employee_accommodations[0]->period_from . ' to ' . $get_employee_accommodations[0]->period_to : '',
			'sharing_premises'	=> 	$get_appendix8a[0]->shared_accommodation ?? '',
			'no_of_days'		=>	$days,
			'annual_value'		=>	$annual_value,
			'furnished'			=> 	$furniture_value,
			'rent_paid'			=>	isset($rent_paid) && !empty($rent_paid)   ? $rent_paid : '0.00',
			'2a+2b'				=> $accommodation_amount,
			'paid_rent'			=>	isset($rent_paid) && !empty($rent_paid)  ? $rent_paid : '0.00',
			'2d-2e'				=>	$accommodation_amount - $rent_paid,
			'utilities'			=>	$get_appendix8a[0]->utilities_amount ?? "0.00",
			'driver'			=>	$get_appendix8a[0]->driver_benefit ?? "0.00",
			'housekeeping'		=> 	$get_appendix8a[0]->housekeeping_benefit ?? "0.00",
			'2g+2h+2i'			=> 	$get_appendix8a[0]->utilities_housekeeping ?? '0.00',
			'actual_cost'		=>	$actual_cost,
			'em_paid'			=> 	!empty($employee_paid) && isset($employee_paid) ? $employee_paid : '0.00',
			'3a-3b'				=> 	$get_appendix8a[0]->hotel_accommodation ?? '0.00',
			'pass_incident'		=>	number_format($pass_incident, 2),
			'interest_pay'		=> 	number_format($interest_pay, 2),
			'insurance'			=> 	number_format($insurance, 2),
			'free_subsi'		=> 	number_format($free_subsi, 2),
			'education'			=> 	number_format($education, 2),
			'social'			=> 	number_format($social, 2),
			'gains'				=> 	number_format($gains, 2),
			'motor'				=> 	number_format($motor, 2),
			'car_ben'			=> 	number_format($car_ben, 2),
			'other'				=> 	number_format($other, 2),
			'employer_name' 	=> 	$company_name,
			'employer_address' 	=> 	$company_address,
			'employer_authorised_person'	=> $employer_authorised_person,
			'person_designation'	=> $authorised_person_designation,
			'person_phone'	=> $authorised_person_phone,
			'date_created'	=>	$date_created
		]);

		// print_r([
		// 	'employee_name'     => 	$user_info[0]->first_name . ' ' . $user_info[0]->last_name,
		// 	'tax_no'			=> 	$user_info[0]->id_no,
		// 	// 'address'			=> 	$get_employee_accommodations[0]->address_line_1 ?? '----',
		// 	'address'			=> 	$employee_address,
		// 	'period_occupation'	=>	isset($get_employee_accommodations) && count($get_employee_accommodations) > 0 ? $get_employee_accommodations[0]->period_from . ' to ' . $get_employee_accommodations[0]->period_to : '',
		// 	'sharing_premises'	=> 	$get_appendix8a[0]->shared_accommodation ?? '',
		// 	'no_of_days'		=>	$days,
		// 	'annual_value'		=>	$annual_value,
		// 	'furnished'			=> 	$furniture_value,
		// 	'rent_paid'			=>	isset($rent_paid) && !empty($rent_paid)   ? $rent_paid : '0.00',
		// 	'2a+2b'				=> $accommodation_amount,
		// 	'paid_rent'			=>	isset($rent_paid) && !empty($rent_paid)  ? $rent_paid : '0.00',
		// 	'2d-2e'				=>	$accommodation_amount - $rent_paid,
		// 	'utilities'			=>	$get_appendix8a[0]->utilities_amount ?? "0.00",
		// 	'driver'			=>	$get_appendix8a[0]->driver_benefit ?? "0.00",
		// 	'housekeeping'		=> 	$get_appendix8a[0]->housekeeping_benefit ?? "0.00",
		// 	'2g+2h+2i'			=> 	$get_appendix8a[0]->utilities_housekeeping ?? '0.00',
		// 	'actual_cost'		=>	$actual_cost,
		// 	'em_paid'			=> 	!empty($employee_paid) && isset($employee_paid) ? $employee_paid : '0.00',
		// 	'3a-3b'				=> 	$get_appendix8a[0]->hotel_accommodation ?? '0.00',
		// 	'pass_incident'		=>	number_format($pass_incident, 2),
		// 	'interest_pay'		=> 	number_format($interest_pay, 2),
		// 	'insurance'			=> 	number_format($insurance, 2),
		// 	'free_subsi'		=> 	number_format($free_subsi, 2),
		// 	'education'			=> 	number_format($education, 2),
		// 	'social'			=> 	number_format($social, 2),
		// 	'gains'				=> 	number_format($gains, 2),
		// 	'motor'				=> 	number_format($motor, 2),
		// 	'car_ben'			=> 	number_format($car_ben, 2),
		// 	'other'				=> 	number_format($other, 2),
		// 	'employer_name' 	=> 	$company_name,
		// 	'employer_address' 	=> 	$company_address,
		// 	'employer_authorised_person'	=> $employer_authorised_person,
		// 	'authorised_person_designation'	=> $authorised_person_designation,
		// 	'authorised_person_phone'	=> $authorised_person_phone,
		// 	'date_created'	=>	$date_created
		// ]);
		// exit;

		header("Content-Disposition: attachment; filename=" . $user_info[0]->first_name . " " . $user_info[0]->last_name . ".docx");

		$templateProcessor->saveAs('php://output');
	}
}
