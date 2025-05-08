<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PmsSession_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_pms_session($session_id)
    {
        $this->db->select('id, user_id, ip_address, user_agent, payload, created_at, updated_at, last_activity');
        $this->db->from('sessions');
        $this->db->where('id', $session_id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }
}