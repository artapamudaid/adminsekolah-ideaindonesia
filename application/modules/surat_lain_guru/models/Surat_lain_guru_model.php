<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_lain_guru_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('surat_lain_guru_id', $params['id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('user_id', $params['user_id']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['employee_id'])) {
			$this->db->where('surat_lain_guru.employee_id', $params['employee_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('surat_lain_guru_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('surat_lain_guru.surat_lain_guru_id, surat_lain_guru_no, surat_lain_guru.employee_id, surat_lain_guru.catatan, surat_lain_guru.status, surat_lain_guru.tanggal_input');
        $this->db->select('employee_name, employee_nip, employee_position_id,employee_category, employee_phone, position_name');

		$this->db->join('employee', 'surat_lain_guru.employee_id = employee.employee_id', 'left');
		$this->db->join('position', 'position.position_id = employee.employee_position_id', 'left');

		$this->db->join('users', 'surat_lain_guru.user_id = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('surat_lain_guru');

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

		if(isset($data['surat_lain_guru_id'])) {
			$this->db->set('surat_lain_guru_id', $data['surat_lain_guru_id']);
		}

		if(isset($data['surat_lain_guru_no'])) {
			$this->db->set('surat_lain_guru_no', $data['surat_lain_guru_no']);
		}

		if(isset($data['employee_id'])) {
			$this->db->set('employee_id', $data['employee_id']);
		}

		if(isset($data['catatan'])) {
			$this->db->set('catatan', $data['catatan']);
		}

		if(isset($data['status'])) {
			$this->db->set('status', $data['status']);
		}

		if(isset($data['user_id'])) {
			$this->db->set('user_id', $data['user_id']);
		}

		if (isset($data['surat_lain_guru_id'])) {
			$this->db->where('surat_lain_guru_id', $data['surat_lain_guru_id']);
			$this->db->update('surat_lain_guru');
			$id = $data['surat_lain_guru_id'];
		} else {
			$this->db->insert('surat_lain_guru');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('surat_lain_guru_id', $id);
		$this->db->delete('surat_lain_guru');
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
