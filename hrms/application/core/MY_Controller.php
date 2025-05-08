<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	public function __construct()
	{

		parent::__construct();
		$ci = &get_instance();
		$ci->load->helper('language');
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('url_helper');
		$this->load->helper('html');
		$this->load->database();
		$this->load->helper('security');
		$this->load->library('form_validation');
		$this->load->model("Xin_model");
		$this->load->model("Company_model");
		$this->load->model("PmsSession_model");
		$this->load->model("Users_model");
		$this->load->model("PmUserRole_model");

		// set default timezone  
		$system = $this->read_setting_info(1);

		$session = $this->session->userdata('username');

		if(empty($session)){
			$default_timezone = $system[0]->system_timezone;
			date_default_timezone_set($default_timezone);

			/**
			 * Sub Functionality - Custom login through session cookie
			 */
			if(isset($_COOKIE['hrms_session']) && !empty($_COOKIE['hrms_session'])) {
				$pms_session = $this->PmsSession_model->get_pms_session($_COOKIE['hrms_session']);
				if($pms_session) {
					$pms_user_id = $pms_session->user_id;
					$pms_session_payload = unserialize(base64_decode($pms_session->payload));
					$pms_logged_in_id = $pms_session_payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];
					if($pms_user_id == $pms_logged_in_id) {
						$user_info = $this->Xin_model->read_user_info($pms_user_id);
						$session_data = array(
							'user_id' => $user_info[0]->user_id,
							'username' => $user_info[0]->username,
							'email' => $user_info[0]->email,
						);
						// Add user data in session
						$this->session->set_userdata('username', $session_data);
						$this->session->set_userdata('user_id', $session_data);
						// echo var_dump($user_info);

						$last_data = array(
							'last_login_date' => date('d-m-Y H:i:s'),
							'last_login_ip' => $pms_session->ip_address,
							'is_logged_in' => '1'
						); 

						$id = $user_info[0]->user_id; // user id

						$this->Users_model->update_record($last_data, $id);
					}

					// echo var_dump($pms_session_payload);
				}
				// echo var_dump($pms_logged_in_id);
			}

			/** End custom session */
		} elseif(!empty($session) && !isset($_COOKIE['hrms_session'])) {
			if(!isset($_COOKIE['remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'])) {
				$sess_array = array('username' => '');
				$this->session->sess_destroy();
				$base_url = str_replace('/hrms', '', base_url());
				redirect($base_url.'login/', 'refresh');
			}

		// } else {
			$user_info = $this->Xin_model->read_user_info($session['user_id']);
			$company_info = $this->Company_model->read_company_information($user_info[0]->company_id);
			if(!is_null($company_info)){
				$default_timezone = $company_info[0]->default_timezone;
				if($default_timezone == ''){
					$default_timezone = $system[0]->system_timezone;
				} else {
					$default_timezone = $default_timezone;
				}
				date_default_timezone_set($default_timezone);
			} else {
				$default_timezone = $system[0]->system_timezone;
				date_default_timezone_set($default_timezone);	
			}
		}


		// set language
		$siteLang = $ci->session->userdata('site_lang');
		if ($system[0]->default_language == '') {
			$default_language = 'english';
		} else {
			$default_language = $system[0]->default_language;
		}
		if ($siteLang) {
			$ci->lang->load('hrsale', $siteLang);
		} else {
			$ci->lang->load('hrsale', $default_language);
		}
	}

	// get setting info
	public function read_setting_info($id)
	{

		$condition = "setting_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_system_setting');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
}
