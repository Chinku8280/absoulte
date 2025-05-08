<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PmUserRole_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_user_role_resources($role_id)
    {
        $this->db->select('*');
        $this->db->from('roles');
        $this->db->where('role_id', $role_id);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }
}