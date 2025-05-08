<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PmUser_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function savePmUser(array $data)
	{
		$this->db->insert('users', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function basic_info_user($data, $id){
		$this->db->where('id', $id);
		if( $this->db->update('users',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to update record in table > change_password
	public function change_password($data, $id){
		$this->db->where('id', $id);
		if( $this->db->update('users',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}