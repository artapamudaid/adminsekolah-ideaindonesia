<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_keterangan_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('keterangan_id', $params['id']);
		}

		if (isset($params['keterangan_nomor'])) {
			$this->db->where('keterangan_nomor_surat', $params['keterangan_nomor']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('keterangan_student_id', $params['student_id']);
		}

		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('keterangan_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('keterangan_id, keterangan_nomor_surat, keterangan_student_id, keterangan_jenis, keterangan_employee_id, keterangan_input_date, nomor_surat, employee_name, student_nis');		
		$this->db->select('lampiran_id, lampiran_isi');
        $this->db->join('surat_nomor', 'surat_nomor.nomor_surat = surat_keterangan.keterangan_nomor_surat', 'left');
		$this->db->join('student', 'student.student_id = surat_keterangan.keterangan_student_id', 'left');		
        $this->db->join('employee', 'employee.employee_id = surat_keterangan.keterangan_employee_id', 'left');
        $this->db->join('keterangan_lampiran', 'keterangan_lampiran.lampiran_surat_keterangan_id = surat_keterangan.keterangan_id', 'left');

		$res = $this->db->get('surat_keterangan');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

	function getPrint($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('surat_keterangan.keterangan_id', $params['id']);
		}

		if (isset($params['keterangan_nomor'])) {
			$this->db->where('keterangan_nomor_surat', $params['keterangan_nomor']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('keterangan_student_id', $params['student_id']);
		}

		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('keterangan_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('surat_keterangan.keterangan_id, surat_keterangan.keterangan_nomor_surat, surat_keterangan.keterangan_student_id, surat_keterangan.keterangan_jenis, surat_keterangan.keterangan_employee_id, surat_keterangan.keterangan_input_date, nomor_surat, employee_name');	
		$this->db->select('jenis_keterangan.keterangan_nama, jenis_keterangan.keterangan_isi, jenis_keterangan.keterangan_kop');
		$this->db->select('lampiran_isi');
		
        $this->db->join('surat_nomor', 'surat_nomor.nomor_surat = surat_keterangan.keterangan_nomor_surat', 'left');
		$this->db->join('student', 'student.student_id = surat_keterangan.keterangan_student_id', 'left');		
        $this->db->join('employee', 'employee.employee_id = surat_keterangan.keterangan_employee_id', 'left');	
        $this->db->join('jenis_keterangan', 'jenis_keterangan.keterangan_id = surat_keterangan.keterangan_jenis', 'left');
        $this->db->join('keterangan_lampiran', 'keterangan_lampiran.lampiran_surat_keterangan_id = surat_keterangan.keterangan_id', 'left');

		$res = $this->db->get('surat_keterangan');

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

		if(isset($data['nomor_surat'])) {
			$this->db->set('keterangan_nomor_surat', $data['nomor_surat']);
		}

		if(isset($data['keterangan_student_id'])) {
			$this->db->set('keterangan_student_id', $data['keterangan_student_id']);
		}

		if(isset($data['keterangan_jenis'])) {
			$this->db->set('keterangan_jenis', $data['keterangan_jenis']);
		}

		if(isset($data['keterangan_input_date'])) {
			$this->db->set('keterangan_input_date', $data['keterangan_input_date']);
		}

		if(isset($data['keterangan_employee_id'])) {
			$this->db->set('keterangan_employee_id', $data['keterangan_employee_id']);
		}

		$this->db->insert('surat_keterangan');
		$id = $this->db->insert_id();

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

	function add_lampiran($data = array()) {

		if(isset($data['lampiran_isi'])) {
			$this->db->set('lampiran_isi', $data['lampiran_isi']);
		}

		if(isset($data['lampiran_surat_keterangan_id'])) {
			$this->db->set('lampiran_surat_keterangan_id', $data['lampiran_surat_keterangan_id']);
		}

        if (isset($data['lampiran_id'])) {
            $this->db->where('lampiran_id', $data['lampiran_id']);
            $this->db->update('keterangan_lampiran');
            $id = $data['lampiran_id'];
        } else {
            $this->db->insert('keterangan_lampiran');
            $id = $this->db->insert_id(); 
        }

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete izin to database
	function delete($id, $nosur) {
		$this->db->where('keterangan_id', $id);
		$this->db->delete('surat_keterangan');

		$this->db->where('nomor_surat', $nosur);
		$this->db->delete('surat_nomor');
	}
	
	function get_sum($params = array()){
	    if(isset($params['id']))
		{
			$this->db->where('keterangan_id', $params['id']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('keterangan_student_id', $params['student_id']);
		}
        
        $this->db->group_by('keterangan_student_id');
		
        $this->db->where('majors_status', '1');
        
        $this->db->select('student_id, student_full_name, student_nis');
        $this->db->select('class_id, class_name');
        $this->db->select('majors_id, majors_short_name, majors_name');
		$this->db->select('COUNT(keterangan_id) AS izinSum');
		
		$this->db->join('student', 'student.student_id = surat_keterangan.keterangan_student_id', 'left');
		
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
		
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
		
		$res = $this->db->get('surat_keterangan');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

}
