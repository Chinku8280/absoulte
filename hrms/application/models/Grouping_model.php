<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class grouping_model extends CI_Model {
		public function __construct()
		{
			parent::__construct();
			$this->load->database();
		}
		function get_grouping(){
			return $this->db->get("grouping");
		}

		public function get_department_subdepartments_grouping($department_id,$sub_department_id) {
	
			$sql = 'SELECT * FROM grouping WHERE department_id = ? WHERE sub_department_id = ?';
			$binds = array($department_id,$sub_department_id);
			$query = $this->db->query($sql, $binds);
			return $query;
		}
		// Function to add record in table
		public function add($data){
			$this->db->insert('grouping', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}

		// Function to Delete selected record from table
		public function delete_sub_record($id){
			$this->db->where('grouping_id', $id);
			$this->db->delete('grouping');
			
		}
		public function read_grouping_info($id) {
	
			$sql = 'SELECT * FROM grouping WHERE grouping_id = ?';
			$binds = array($id);
			$query = $this->db->query($sql, $binds);
			
			if ($query->num_rows() > 0) {
				return $query->result();
			} else {
				return null;
			}
		}

		// Function to update record in table
		public function update_sub_record($data, $id){
			$this->db->where('grouping_id', $id);
			$data = $this->security->xss_clean($data);
			if( $this->db->update('grouping',$data)) {
				return true;
			} else {
				return false;
			}		
		}
    }