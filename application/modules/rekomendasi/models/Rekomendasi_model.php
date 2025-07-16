<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekomendasi_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('rekomendasi_id ', $params['id']);
		}

		if (isset($params['id_users'])) {
			$this->db->where('rekomendasi_users', $params['id_users']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('rekomendasi_input_date >=', $params['date_start']);
            $this->db->where('rekomendasi_input_date <=', $params['date_end']);
        }

		if (isset($params['student_id'])) {
			$this->db->where('rekomendasi_student_id', $params['student_id']);
		}
        
        if($this->session->userdata('umajorsid') != '0')
        {
            // $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('rekomendasi_id ', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('surat_rekomendasi.rekomendasi_id, rekomendasi_nomor_surat_id, rekomendasi_desc, rekomendasi_student_id, rekomendasi_date_start, rekomendasi_date_end , rekomendasi_time_start, rekomendasi_time_end, rekomendasi_lokasi, rekomendasi_users');
        $this->db->select('student_full_name, student_nis, student_nisn');
        $this->db->select('class_name');

        $this->db->join('surat_nomor', 'surat_nomor.nomor_surat = surat_rekomendasi.rekomendasi_nomor_surat_id', 'left');
		$this->db->join('student', 'student.student_id = surat_rekomendasi.rekomendasi_student_id', 'left');		
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');		
        $this->db->join('users', 'users.user_id = surat_rekomendasi.rekomendasi_users', 'left');

		$res = $this->db->get('surat_rekomendasi');

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

		if(isset($data['rekomendasi_id '])) {
			$this->db->set('rekomendasi_id ', $data['rekomendasi_id ']);
		}

		if(isset($data['nomor_surat'])) {
			$this->db->set('rekomendasi_nomor_surat_id', $data['nomor_surat']);
		}

		if(isset($data['rekomendasi_desc'])) {
			$this->db->set('rekomendasi_desc', $data['rekomendasi_desc']);
		}

		if(isset($data['rekomendasi_student_id'])) {
			$this->db->set('rekomendasi_student_id', $data['rekomendasi_student_id']);
		}

		if(isset($data['rekomendasi_date_start'])) {
			$this->db->set('rekomendasi_date_start', $data['rekomendasi_date_start']);
		}

		if(isset($data['rekomendasi_date_end'])) {
			$this->db->set('rekomendasi_date_end', $data['rekomendasi_date_end']);
		}

		if(isset($data['rekomendasi_time_start'])) {
			$this->db->set('rekomendasi_time_start', $data['rekomendasi_time_start']);
		}

		if(isset($data['rekomendasi_time_end'])) {
			$this->db->set('rekomendasi_time_end', $data['rekomendasi_time_end']);
		}

		if(isset($data['rekomendasi_lokasi'])) {
			$this->db->set('rekomendasi_lokasi', $data['rekomendasi_lokasi']);
		}

		if(isset($data['id_users'])) {
			$this->db->set('rekomendasi_users', $data['id_users']);
		}

		if (isset($data['rekomendasi_id'])) {
			$this->db->where('rekomendasi_id', $data['rekomendasi_id']);
			$this->db->update('surat_rekomendasi');
			$id = $data['rekomendasi_id'];
		} else {
			$this->db->insert('surat_rekomendasi');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

	public function add_nomor($data = array())
	{
		if(isset($data['nomor_surat'])) {
			$this->db->set('nomor_surat', $data['nomor_surat']);
		}

		if(isset($data['tahun'])) {
			$this->db->set('tahun', $data['tahun']);
		}

		$this->db->insert('surat_nomor');
		$id = $this->db->insert_id();

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id_nomor;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('rekomendasi_id ', $id);
		$this->db->delete('surat_rekomendasi');
	}

    function delete_nomor($id) {
		$this->db->where('nomor_id ', $id);
		$this->db->delete('surat_nomor');
	}
	
	

}
