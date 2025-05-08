<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpf_options_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_option_value($option_name)
    {
        $this->db->select('id, option_name, option_value');
        $this->db->from('xin_cpf_options');
        $this->db->where('option_name', $option_name);
        $result = $this->db->get();
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return false;
    }
}