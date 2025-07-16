<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publik_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('publik_id', $params['id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('user_id', $params['user_id']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['position'])) {
			$this->db->where('publik_position_id', $params['position']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('publik_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('publik_id, publik_no, publik_nama, publik_alamat, publik_telepon, publik_nik, publik_position_id, keperluan, publik_status, tanggal_input,position_name');

		$this->db->join('position', 'publik.publik_position_id = position.position_id ', 'left');
		$this->db->join('users', 'publik.user_id = users.user_id ', 'left');

		$res = $this->db->get('publik');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

    // Add and update to database
	function add($data = array()) {

		if(isset($data['publik_id'])) {
			$this->db->set('publik_id', $data['publik_id']);
		}

		if(isset($data['publik_no'])) {
			$this->db->set('publik_no', $data['publik_no']);
		}

		if(isset($data['publik_nama'])) {
			$this->db->set('publik_nama', $data['publik_nama']);
		}

		if(isset($data['publik_alamat'])) {
			$this->db->set('publik_alamat', $data['publik_alamat']);
		}

		if(isset($data['publik_telepon'])) {
			$this->db->set('publik_telepon', $data['publik_telepon']);
		}

		if(isset($data['publik_nik'])) {
			$this->db->set('publik_nik', $data['publik_nik']);
		}

		if(isset($data['publik_position_id'])) {
			$this->db->set('publik_position_id', $data['publik_position_id']);
		}

		if(isset($data['keperluan'])) {
			$this->db->set('keperluan', $data['keperluan']);
		}

		if(isset($data['publik_status'])) {
			$this->db->set('publik_status', $data['publik_status']);
		}

		if(isset($data['user_id'])) {
			$this->db->set('user_id', $data['user_id']);
		}

		if (isset($data['publik_id'])) {
			$this->db->where('publik_id', $data['publik_id']);
			$this->db->update('publik');
			$id = $data['publik_id'];
		} else {
			$this->db->insert('publik');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('publik_id', $id);
		$this->db->delete('publik');
	}
	
	// function get_sum($params = array()){
	//     if(isset($params['id']))
	// 	{
	// 		$this->db->where('id_arsip ', $params['id']);
	// 	}

	// 	if (isset($params['period_id'])) {
	// 		$this->db->where('tahfidz_period_id', $params['period_id']);
	// 	}

	// 	if (isset($params['student_id'])) {
	// 		$this->db->where('tahfidz_student_id', $params['student_id']);
	// 	}
        
    //     $this->db->group_by('tahfidz_student_id');
		
    //     $this->db->where('majors_status', '1');
        
    //     $this->db->select('student_id, student_full_name, student_nis');
    //     $this->db->select('class_id, class_name');
    //     $this->db->select('period_id');
    //     $this->db->select('majors_id, majors_short_name, majors_name');
	// 	$this->db->select('SUM(tahfidz_new) AS tahfidzSum');
		
	// 	$this->db->join('student', 'student.student_id = tahfidz.tahfidz_student_id', 'left');
		
	// 	$this->db->join('period', 'period.period_id = tahfidz.tahfidz_period_id', 'left');
		
    //     $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
		
    //     $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');

	// 	$res = $this->db->get('tahfidz');

	// 	if(isset($params['id']))
	// 	{
	// 		return $res->row_array();
	// 	}
	// 	else
	// 	{
	// 		return $res->result_array();
	// 	}
	// }
}
