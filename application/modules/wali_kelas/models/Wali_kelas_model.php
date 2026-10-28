<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wali_kelas_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('wali_kelas_id', $params['id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('class.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['semester_id'])) {
            $this->db->where('wali_kelas.wali_kelas_semester_id', $params['semester_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('semester.semester_period_id', $params['period_id']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('period_start', 'desc');
            $this->db->order_by('semester.semester_name', 'desc');
            $this->db->order_by('class_name', 'asc');
            $this->db->order_by('employee.employee_name', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('wali_kelas_id, wali_kelas_employee_id, wali_kelas_class_id, class_name, class_id,
                            employee_name, employee_nip, employee_id, majors_majors_id, semester_id, semester_name,
                            semester_id, period_id, period_start, period_end');

        $this->db->join('employee', 'employee.employee_id=wali_kelas.wali_kelas_employee_id');
        $this->db->join('class', 'class.class_id=wali_kelas.wali_kelas_class_id');
        $this->db->join('majors', 'majors.majors_id=class.majors_majors_id');
        $this->db->join('semester', 'semester.semester_id = wali_kelas.wali_kelas_semester_id');
        $this->db->join('period', 'semester.semester_period_id = period.period_id');

        $res = $this->db->get('wali_kelas');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['wali_kelas_id'])) {
            $this->db->set('wali_kelas_id', $data['wali_kelas_id']);
        }

        if (isset($data['wali_kelas_employee_id'])) {
            $this->db->set('wali_kelas_employee_id', $data['wali_kelas_employee_id']);
        }

        if (isset($data['wali_kelas_class_id'])) {
            $this->db->set('wali_kelas_class_id', $data['wali_kelas_class_id']);
        }

        if (isset($data['wali_kelas_semester_id'])) {
            $this->db->set('wali_kelas_semester_id', $data['wali_kelas_semester_id']);
        }

        if (isset($data['wali_kelas_id'])) {
            $this->db->where('wali_kelas_id', $data['wali_kelas_id']);
            $this->db->update('wali_kelas');
            $id = $data['wali_kelas_id'];
        } else {
            $this->db->insert('wali_kelas');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('wali_kelas_id', $id);
        $this->db->delete('wali_kelas');
    }

    public function is_exist($field, $value)
    {
        $this->db->where($field, $value);

        return $this->db->count_all_results('student') > 0 ? TRUE : FALSE;
    }
}
