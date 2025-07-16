<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get_izin($params = array())
	{

		if (isset($params['period_id'])) {
			$this->db->where('pengajuan_izin_period_id', $params['period_id']);
		}
		
		if (isset($params['majors_id'])) {
			$this->db->where('pengajuan_izin_majors_id', $params['majors_id']);
		}
		
		if (isset($params['status'])) {
			$this->db->where('pengajuan_izin_status', $params['status']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('pengajuan_izin_id', 'desc');
		}

		$this->db->select('period_start, period_end');
		
		$this->db->select('student_id, student_full_name, student_parent_phone');
		
		$this->db->select('class_name, majors_short_name');

		$this->db->select('pengajuan_izin_id, pengajuan_izin_date, pengajuan_izin_time, pengajuan_izin_note, pengajuan_izin_status');
		
		$this->db->join('majors', 'majors.majors_id = pengajuan_izin.pengajuan_izin_majors_id', 'left');
		
		$this->db->join('period', 'period.period_id = pengajuan_izin.pengajuan_izin_period_id', 'left');
		
		$this->db->join('student', 'student.student_id = pengajuan_izin.pengajuan_izin_student_id', 'left');
		
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
		
        $this->db->join('users', 'users.user_id = pengajuan_izin.pengajuan_izin_user_id', 'left');

		$res = $this->db->get('pengajuan_izin');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

	function get_pulang($params = array())
	{

		if (isset($params['period_id'])) {
			$this->db->where('pengajuan_pulang_period_id', $params['period_id']);
		}
		
		if (isset($params['majors_id'])) {
			$this->db->where('pengajuan_pulang_majors_id', $params['majors_id']);
		}
		
		if (isset($params['status'])) {
			$this->db->where('pengajuan_pulang_status', $params['status']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('pengajuan_pulang_id', 'desc');
		}

		$this->db->select('period_start, period_end');
		
		$this->db->select('student_id, student_full_name, student_parent_phone');
		
		$this->db->select('class_name, majors_short_name');

		$this->db->select('pengajuan_pulang_id, pengajuan_pulang_date, pengajuan_pulang_days, pengajuan_pulang_note, pengajuan_pulang_status');
		
        $this->db->join('majors', 'majors.majors_id = pengajuan_pulang.pengajuan_pulang_majors_id', 'left');
		
		$this->db->join('period', 'period.period_id = pengajuan_pulang.pengajuan_pulang_period_id', 'left');
		
		$this->db->join('student', 'student.student_id = pengajuan_pulang.pengajuan_pulang_student_id', 'left');
		
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
		
        $this->db->join('users', 'users.user_id = pengajuan_pulang.pengajuan_pulang_user_id', 'left');

		$res = $this->db->get('pengajuan_pulang');

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
