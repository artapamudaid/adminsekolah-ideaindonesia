<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lesson_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        $cache_key = get_session_cache_key('lesson', $params);
        try {
            if (!isset($this->cache)) {
                $this->load->driver('cache', array('adapter' => 'file'));
            }
            if (isset($this->cache)) {
                $cached = $this->cache->get($cache_key);
                if ($cached !== FALSE) {
                    return $cached;
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cache read error in Lesson_model::get: ' . $e->getMessage());
        }

        if (isset($params['id'])) {
            $this->db->where('lesson_id', $params['id']);
        }

        if (isset($params['lesson_code'])) {
            $this->db->where('lesson_code', $params['lesson_code']);
        }

        if (isset($params['lesson_name'])) {
            $this->db->where('lesson_name', $params['lesson_name']);
        }

        if (isset($params['lesson_teacher'])) {
            $this->db->where('lesson_teacher', $params['lesson_teacher']);
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
            $this->db->order_by('lesson.lesson_name', 'asc');
        }

        $this->db->select('lesson_id, lesson_name, lesson_code, lesson_teacher, employee_id, employee_name');

        $this->db->join('employee', 'employee.employee_id = lesson.lesson_teacher', 'left');

        $res = $this->db->get('lesson');

        if (isset($params['id'])) {
            $result = $res->row_array();
        } else {
            $result = $res->result_array();
        }

        try {
            if (isset($this->cache)) {
                $this->cache->save($cache_key, $result, CACHE_TTL);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cache write error in Lesson_model::get: ' . $e->getMessage());
        }

        return $result;
    }

    function add($data = array())
    {

        if (isset($data['lesson_id'])) {
            $this->db->set('lesson_id', $data['lesson_id']);
        }

        if (isset($data['lesson_code'])) {
            $this->db->set('lesson_code', $data['lesson_code']);
        }

        if (isset($data['lesson_name'])) {
            $this->db->set('lesson_name', $data['lesson_name']);
        }

        if (isset($data['lesson_teacher'])) {
            $this->db->set('lesson_teacher', $data['lesson_teacher']);
        }

        if (isset($data['lesson_id'])) {
            $this->db->where('lesson_id', $data['lesson_id']);
            $this->db->update('lesson');
            $id = $data['lesson_id'];
        } else {
            $this->db->insert('lesson');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        bump_cache_version('lesson');
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('lesson_id', $id);
        $this->db->delete('lesson');
        bump_cache_version('lesson');
    }

    public function invalidate_cache()
    {
        bump_cache_version('lesson');
    }
}
