<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_tugas_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('id_surat_tugas', $params['id']);
		}

		if (isset($params['id_users'])) {
			$this->db->where('id_users', $params['id_users']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['employee_id'])) {
			$this->db->where('surat_tugas.employee_id', $params['employee_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('id_surat_tugas', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('surat_tugas.id_surat_tugas, no_surat_tugas, surat_tugas.employee_id, pengikut_id, deskripsi, tgl_kegiatan, tmp_kegiatan, alamat_kegiatan, keterangan, file, id_users, user_full_name, tanggal_input');
        $this->db->select('employee_name, employee_nip, employee_position_id,employee_category, employee_phone, employee_gender');

		$this->db->join('employee', 'surat_tugas.employee_id = employee.employee_id', 'left');
		$this->db->join('position', 'position.position_id = employee.employee_position_id', 'left');

		$this->db->join('users', 'surat_tugas.id_users = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('surat_tugas');

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

		if(isset($data['id_surat_tugas'])) {
			$this->db->set('id_surat_tugas', $data['id_surat_tugas']);
		}

		if(isset($data['no_surat_tugas'])) {
			$this->db->set('no_surat_tugas', $data['no_surat_tugas']);
		}

		if(isset($data['employee_id'])) {
			$this->db->set('employee_id', $data['employee_id']);
		}

		if(isset($data['pengikut_id'])) {
			$this->db->set('pengikut_id', $data['pengikut_id']);
		}

		if(isset($data['deskripsi'])) {
			$this->db->set('deskripsi', $data['deskripsi']);
		}

		if(isset($data['tgl_kegiatan'])) {
			$this->db->set('tgl_kegiatan', $data['tgl_kegiatan']);
		}

		if(isset($data['tmp_kegiatan'])) {
			$this->db->set('tmp_kegiatan', $data['tmp_kegiatan']);
		}

		if(isset($data['alamat_kegiatan'])) {
			$this->db->set('alamat_kegiatan', $data['alamat_kegiatan']);
		}

		if(isset($data['keterangan'])) {
			$this->db->set('keterangan', $data['keterangan']);
		}

		if(isset($data['upl_surat_tugas'])) {
			$this->db->set('file', $data['upl_surat_tugas']);
		}

		if(isset($data['status_surat'])) {
			$this->db->set('status_surat', $data['status_surat']);
		}

		if(isset($data['id_users'])) {
			$this->db->set('id_users', $data['id_users']);
		}

		if (isset($data['id_surat_tugas'])) {
			$this->db->where('id_surat_tugas', $data['id_surat_tugas']);
			$this->db->update('surat_tugas');
			$id = $data['id_surat_tugas'];
		} else {
			$this->db->insert('surat_tugas');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('id_surat_tugas', $id);
		$this->db->delete('surat_tugas');
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
