<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Satuan_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('satuan_id', $params['id']);
        }

        if (isset($params['satuan_name'])) {
            $this->db->where('satuan_name', $params['satuan_name']);
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
            $this->db->order_by('satuan_id', 'asc');
        }

        $this->db->select('satuan_id, satuan_name');
        $res = $this->db->get('satuan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['satuan_id'])) {
            $this->db->set('satuan_id', $data['satuan_id']);
        }

        if (isset($data['satuan_name'])) {
            $this->db->set('satuan_name', $data['satuan_name']);
        }

        if (isset($data['satuan_id'])) {
            $this->db->where('satuan_id', $data['satuan_id']);
            $this->db->update('satuan');
            $id = $data['satuan_id'];
        } else {
            $this->db->insert('satuan');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('satuan_id', $id);
        $this->db->delete('satuan');
    }
}
