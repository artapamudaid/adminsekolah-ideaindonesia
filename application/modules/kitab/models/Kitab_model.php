<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kitab_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('kitab_id', $params['id']);
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
            $this->db->order_by('kitab_id', 'asc');
        }

        $this->db->select('kitab_id, kitab_name');

        $res = $this->db->get('kitab');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['kitab_id'])) {
            $this->db->set('kitab_id', $data['kitab_id']);
        }

        if (isset($data['kitab_name'])) {
            $this->db->set('kitab_name', $data['kitab_name']);
        }

        if (isset($data['kitab_id'])) {
            $this->db->where('kitab_id', $data['kitab_id']);
            $this->db->update('kitab');
            $id = $data['kitab_id'];
        } else {
            $this->db->insert('kitab');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('kitab_id', $id);
        $this->db->delete('kitab');
    }

    public function checkDataExists($tableName, $col, $value = null)
    {
        if (is_array($col)) {
            $this->db->where($col);
        } else {
            $this->db->where($col, $value);
        }

        return $this->db
            ->count_all_results($tableName) > 0;
    }
}
