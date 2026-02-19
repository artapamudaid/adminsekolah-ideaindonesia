<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nadhoman_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function get($params = array())
	{
		if (isset($params['id'])) {
			$this->db->where('nadhoman_id', $params['id']);
		}

		if (isset($params['period_id'])) {
			$this->db->where('nadhoman_period_id', $params['period_id']);
		}

		if (isset($params['kitab_id'])) {
			$this->db->where('nadhoman_kitab_id', $params['kitab_id']);
		}

		if (isset($params['date'])) {
			$this->db->where('nadhoman_date', $params['date']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('nadhoman_student_id', $params['student_id']);
		}

		if ($this->session->userdata('umajorsid') != '0') {
			$this->db->where('majors_id', $this->session->userdata('umajorsid'));
		}

		if (isset($params['order_by'])) {
			$this->db->order_by($params['order_by'], 'desc');
		} else {
			$this->db->order_by('nadhoman_id', 'desc');
		}
		$this->db->where('majors_status', '1');

		$this->db->select('nadhoman_id, nadhoman_period_id, nadhoman_kitab_id,
							kitab_id, kitab_name, nadhoman_date,
							nadhoman_new, nadhoman_note');

		$this->db->join('student', 'student.student_id = nadhoman.nadhoman_student_id', 'left');
		$this->db->join('period', 'period.period_id = nadhoman.nadhoman_period_id', 'left');
		$this->db->join('kitab', 'kitab.kitab_id = nadhoman.nadhoman_kitab_id', 'left');
		$this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
		$this->db->join('users', 'users.user_id = nadhoman.nadhoman_user_id', 'left');

		$res = $this->db->get('nadhoman');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}

	// Add and update to database
	function add($data = array())
	{

		if (isset($data['nadhoman_id'])) {
			$this->db->set('nadhoman_id', $data['nadhoman_id']);
		}

		if (isset($data['nadhoman_date'])) {
			$this->db->set('nadhoman_date', $data['nadhoman_date']);
		}

		if (isset($data['nadhoman_kitab_id'])) {
			$this->db->set('nadhoman_kitab_id', $data['nadhoman_kitab_id']);
		}

		if (isset($data['nadhoman_period_id'])) {
			$this->db->set('nadhoman_period_id', $data['nadhoman_period_id']);
		}

		if (isset($data['nadhoman_new'])) {
			$this->db->set('nadhoman_new', $data['nadhoman_new']);
		}

		if (isset($data['nadhoman_note'])) {
			$this->db->set('nadhoman_note', $data['nadhoman_note']);
		}

		if (isset($data['nadhoman_student_id'])) {
			$this->db->set('nadhoman_student_id', $data['nadhoman_student_id']);
		}

		if (isset($data['nadhoman_user_id'])) {
			$this->db->set('nadhoman_user_id', $data['nadhoman_user_id']);
		}

		if (isset($data['nadhoman_id'])) {
			$this->db->where('nadhoman_id', $data['nadhoman_id']);
			$this->db->update('nadhoman');
			$id = $data['nadhoman_id'];
		} else {
			$this->db->insert('nadhoman');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

	//update nadhoman
	function update($tabel, $data, $id)
	{
		$this->db->set($data);
		$this->db->where($id);
		$this->db->update($tabel);
	}

	// Delete nadhoman to database
	function delete($id)
	{
		$this->db->where('nadhoman_id', $id);
		$this->db->delete('nadhoman');
	}

	function get_sum($params = array())
	{
		if (isset($params['id'])) {
			$this->db->where('nadhoman_id', $params['id']);
		}

		if (isset($params['period_id'])) {
			$this->db->where('nadhoman_period_id', $params['period_id']);
		}

		if (isset($params['kitab_id'])) {
			$this->db->where('nadhoman_kitab_id', $params['kitab_id']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('nadhoman_student_id', $params['student_id']);
		}
		//menambah modul date start dan end
		if (isset($params['date_start']) and isset($params['date_end'])) {
			$this->db->where('nadhoman_date >=', $params['date_start']);
			$this->db->where('nadhoman_date <=', $params['date_end']);
		}

		$this->db->group_by('nadhoman_kitab_id');

		$this->db->where('majors_status', '1');

		$this->db->select('student_id, student_full_name, student_nis');
		$this->db->select('class_id, class_name');
		$this->db->select('period_id');
		$this->db->select('majors_id, majors_short_name, majors_name');
		$this->db->select('kitab_id, kitab_name');
		$this->db->select('SUM(nadhoman_new) AS nadhomanSum, nadhoman_note, nadhoman_date');

		$this->db->join('student', 'student.student_id = nadhoman.nadhoman_student_id', 'left');
		$this->db->join('period', 'period.period_id = nadhoman.nadhoman_period_id', 'left');
		$this->db->join('kitab', 'kitab.kitab_id = nadhoman.nadhoman_kitab_id', 'left');
		$this->db->join('class', 'class.class_id = student.class_class_id', 'left');
		$this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');

		$res = $this->db->get('nadhoman');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}
}
