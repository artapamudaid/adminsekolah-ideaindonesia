<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Template_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id', $params['id']);
        }

        if (isset($params['location'])) {
            $this->db->where('location', $params['location']);
        }

        if (isset($params['title'])) {
            $this->db->where('title', $params['title']);
        }
        
        if (isset($params['content'])) {
            $this->db->where('content', $params['content']);
        }
        
        if (isset($params['is_active'])) {
            $this->db->where('is_active', $params['is_active']);
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
            $this->db->order_by('templates.is_active', 'desc');
            $this->db->order_by('templates.location', 'asc');
        }

        $this->db->select('id, title, location, content, is_active');

        $res = $this->db->get('templates');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['id'])) {
            $this->db->set('id', $data['id']);
        }

        if (isset($data['location'])) {
            $this->db->set('location', $data['location']);
        }

        if (isset($data['title'])) {
            $this->db->set('title', $data['title']);
        }

        if (isset($data['content'])) {
            $this->db->set('content', $data['content']);
        }

        if (isset($data['is_active'])) {
            $this->db->set('is_active', $data['is_active']);
        }

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('templates');
            $id = $data['id'];
        } else {
            $this->db->insert('templates');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('templates');
    }
}
