<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_rekomendasi_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('siswa_rekomendasi_id', $params['id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('user_id', $params['user_id']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['student_id'])) {
			$this->db->where('siswa_rekomendasi.student_id', $params['student_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('siswa_rekomendasi_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('siswa_rekomendasi.siswa_rekomendasi_id, siswa_rekomendasi_no, siswa_rekomendasi.student_id, siswa_rekomendasi.catatan, keperluan, siswa_rekomendasi.file, siswa_rekomendasi.status, siswa_rekomendasi.tanggal_input');
        $this->db->select('student_full_name, student_nis, student_nisn, class_class_id, class_name');

		$this->db->join('student', 'siswa_rekomendasi.student_id = student.student_id', 'left');
		$this->db->join('class', 'class.class_id = student.class_class_id', 'left');

		$this->db->join('users', 'siswa_rekomendasi.user_id = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('siswa_rekomendasi');

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

		if(isset($data['siswa_rekomendasi_id'])) {
			$this->db->set('siswa_rekomendasi_id', $data['siswa_rekomendasi_id']);
		}

		if(isset($data['siswa_rekomendasi_no'])) {
			$this->db->set('siswa_rekomendasi_no', $data['siswa_rekomendasi_no']);
		}

		if(isset($data['student_id'])) {
			$this->db->set('student_id', $data['student_id']);
		}

		if(isset($data['keperluan'])) {
			$this->db->set('keperluan', $data['keperluan']);
		}

		if(isset($data['catatan'])) {
			$this->db->set('catatan', $data['catatan']);
		}

		if(isset($data['file'])) {
			$this->db->set('file', $data['file']);
		}

		if(isset($data['status'])) {
			$this->db->set('status', $data['status']);
		}

		if(isset($data['user_id'])) {
			$this->db->set('user_id', $data['user_id']);
		}

		if (isset($data['siswa_rekomendasi_id'])) {
			$this->db->where('siswa_rekomendasi_id', $data['siswa_rekomendasi_id']);
			$this->db->update('siswa_rekomendasi');
			$id = $data['siswa_rekomendasi_id'];
		} else {
			$this->db->insert('siswa_rekomendasi');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('siswa_rekomendasi_id', $id);
		$this->db->delete('siswa_rekomendasi');
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
