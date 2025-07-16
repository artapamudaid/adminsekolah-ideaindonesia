<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_mutasi_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('siswa_mutasi_id', $params['id']);
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
			$this->db->where('siswa_mutasi.student_id', $params['student_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('siswa_mutasi_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('siswa_mutasi.siswa_mutasi_id, siswa_mutasi_no, siswa_mutasi.student_id, sekolah_tujuan, alamat_sekolah_tujuan, alasan_pindah, siswa_mutasi.catatan, siswa_mutasi.file, siswa_mutasi.status, siswa_mutasi.tanggal_input');
        $this->db->select('student_full_name, student_nis, student_nisn, student_name_of_mother, student_name_of_father, class_class_id, class_name');

		$this->db->join('student', 'siswa_mutasi.student_id = student.student_id', 'left');
		$this->db->join('class', 'class.class_id = student.class_class_id', 'left');

		$this->db->join('users', 'siswa_mutasi.user_id = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('siswa_mutasi');

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

		if(isset($data['siswa_mutasi_id'])) {
			$this->db->set('siswa_mutasi_id', $data['siswa_mutasi_id']);
		}

		if(isset($data['siswa_mutasi_no'])) {
			$this->db->set('siswa_mutasi_no', $data['siswa_mutasi_no']);
		}

		if(isset($data['student_id'])) {
			$this->db->set('student_id', $data['student_id']);
		}

		if(isset($data['catatan'])) {
			$this->db->set('catatan', $data['catatan']);
		}

		if(isset($data['sekolah_tujuan'])) {
			$this->db->set('sekolah_tujuan', $data['sekolah_tujuan']);
		}

		if(isset($data['alamat_sekolah_tujuan'])) {
			$this->db->set('alamat_sekolah_tujuan', $data['alamat_sekolah_tujuan']);
		}

		if(isset($data['alasan_pindah'])) {
			$this->db->set('alasan_pindah', $data['alasan_pindah']);
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

		if (isset($data['siswa_mutasi_id'])) {
			$this->db->where('siswa_mutasi_id', $data['siswa_mutasi_id']);
			$this->db->update('siswa_mutasi');
			$id = $data['siswa_mutasi_id'];
		} else {
			$this->db->insert('siswa_mutasi');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('siswa_mutasi_id', $id);
		$this->db->delete('siswa_mutasi');
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
