<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaduan_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('pengaduan_id', $params['id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('user_id', $params['user_id']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['pengaduan_status'])) {
			$this->db->where('pengaduan_status', $params['pengaduan_status']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('pengaduan_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('pengaduan_id, pengaduan_no, pengaduan_nama, pengaduan_tujuan, pengaduan_detail, pengaduan_status, tanggal_input');

		$this->db->join('users', 'pengaduan.user_id = users.user_id ', 'left');

		$res = $this->db->get('pengaduan');

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

		if(isset($data['pengaduan_id'])) {
			$this->db->set('pengaduan_id', $data['pengaduan_id']);
		}

		if(isset($data['pengaduan_no'])) {
			$this->db->set('pengaduan_no', $data['pengaduan_no']);
		}

		if(isset($data['pengaduan_nama'])) {
			$this->db->set('pengaduan_nama', $data['pengaduan_nama']);
		}

		if(isset($data['pengaduan_tujuan'])) {
			$this->db->set('pengaduan_tujuan', $data['pengaduan_tujuan']);
		}

		if(isset($data['pengaduan_detail'])) {
			$this->db->set('pengaduan_detail', $data['pengaduan_detail']);
		}

		if(isset($data['pengaduan_status'])) {
			$this->db->set('pengaduan_status', $data['pengaduan_status']);
		}

		if(isset($data['user_id'])) {
			$this->db->set('user_id', $data['user_id']);
		}

		if (isset($data['pengaduan_id'])) {
			$this->db->where('pengaduan_id', $data['pengaduan_id']);
			$this->db->update('pengaduan');
			$id = $data['pengaduan_id'];
		} else {
			$this->db->insert('pengaduan');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('pengaduan_id', $id);
		$this->db->delete('pengaduan');
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
