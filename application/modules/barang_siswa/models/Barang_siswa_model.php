<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_siswa_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('barang_siswa_id', $params['id']);
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
			$this->db->where('barang_siswa.student_id', $params['student_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('barang_siswa_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('barang_siswa.barang_siswa_id, barang_siswa_no, barang_siswa.student_id, barang_siswa.catatan, barang_siswa.tanggal_input');
        $this->db->select('student_full_name, student_nis, student_nisn, class_id, class_name');

		$this->db->join('student', 'barang_siswa.student_id = student.student_id', 'left');
		$this->db->join('class', 'class.class_id = student.class_class_id', 'left');

		$this->db->join('users', 'barang_siswa.user_id = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('barang_siswa');

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

		if(isset($data['barang_siswa_id'])) {
			$this->db->set('barang_siswa_id', $data['barang_siswa_id']);
		}

		if(isset($data['barang_siswa_no'])) {
			$this->db->set('barang_siswa_no', $data['barang_siswa_no']);
		}

		if(isset($data['student_id'])) {
			$this->db->set('student_id', $data['student_id']);
		}

		if(isset($data['catatan'])) {
			$this->db->set('catatan', $data['catatan']);
		}

		if(isset($data['user_id'])) {
			$this->db->set('user_id', $data['user_id']);
		}

		if (isset($data['barang_siswa_id'])) {
			$this->db->where('barang_siswa_id', $data['barang_siswa_id']);
			$this->db->update('barang_siswa');
			$id = $data['barang_siswa_id'];
		} else {
			$this->db->insert('barang_siswa');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('barang_siswa_id', $id);
		$this->db->delete('barang_siswa');
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
        
    //     $this->db->select('student_id, employee_name, employee_nip');
    //     $this->db->select('position_id, position_name');
    //     $this->db->select('period_id');
    //     $this->db->select('majors_id, majors_short_name, majors_name');
	// 	$this->db->select('SUM(tahfidz_new) AS tahfidzSum');
		
	// 	$this->db->join('employee', 'employee.student_id = tahfidz.tahfidz_student_id', 'left');
		
	// 	$this->db->join('period', 'period.period_id = tahfidz.tahfidz_period_id', 'left');
		
    //     $this->db->join('position', 'position.position_id = employee.position_id', 'left');
		
    //     $this->db->join('majors', 'majors.majors_id = employee.majors_majors_id', 'left');

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
