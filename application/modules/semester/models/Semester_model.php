<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Semester_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('semester_id', $params['id']);
        }

        if (isset($params['semester_name'])) {
            $this->db->where('semester_name', $params['semester_name']);
        }

        if (isset($params['semester_status'])) {
            $this->db->where('semester_status', $params['semester_status']);
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
            $this->db->order_by('period.period_start', 'DESC');
            $this->db->order_by('semester.semester_name', 'ASC');
        }

        $this->db->select('semester_id, semester_name, semester_status, semester_period_id, period_start, period_end');
        $this->db->join('period', 'period.period_id=semester.semester_period_id', 'left');
        $res = $this->db->get('semester');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['semester_id'])) {
            $this->db->set('semester_id', $data['semester_id']);
        }

        if (isset($data['semester_name'])) {
            $this->db->set('semester_name', $data['semester_name']);
        }

        if (isset($data['semester_period_id'])) {
            $this->db->set('semester_period_id', $data['semester_period_id']);
        }

        if (isset($data['semester_status'])) {
            $this->db->set('semester_status', $data['semester_status']);
        }

        if (isset($data['semester_id'])) {
            $this->db->where('semester_id', $data['semester_id']);
            $this->db->update('semester');
            $id = $data['semester_id'];
        } else if (isset($data['status_active'])) {
            $this->db->where('semester_id !=', $data['semester_id']);
            $this->db->set('semester_status', 0);
            $this->db->update('semester');
            $id = NULL;
        } else {
            $this->db->insert('semester');
            $id = $this->db->insert_id();
        }
        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('semester_id', $id);
        $this->db->delete('semester');
    }
}
