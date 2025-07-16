<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array()) {

        if(isset($params['id']))
        {
            $this->db->where('role_id', $params['id']);
        }

        if(isset($params['role_name']))
        {
            $this->db->where('role_name', $params['role_name']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
            
        }
        else
        {
            //$this->db->order_by('user_roles.role_majors_id', 'asc');
            $this->db->order_by('user_roles.role_name', 'asc');
        }
        
        //$this->db->where('majors_status', '1');
        
        $this->db->select('role_id, role_name');
        $res = $this->db->get('user_roles');

        if(isset($params['id']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }

    }

    
    function add($data = array()) {

        if (isset($data['role_id'])) {
            $this->db->set('role_id', $data['role_id']);
        }

        if (isset($data['role_name'])) {
            $this->db->set('role_name', $data['role_name']);
        }

        /*if (isset($data['role_majors_id'])) {
            $this->db->set('role_majors_id', $data['role_majors_id']);
        }*/

        if (isset($data['role_id'])) {
            $this->db->where('role_id', $data['role_id']);
            $this->db->update('user_roles');
            $id = $data['role_id'];
        } else {
            $this->db->insert('user_roles');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

}
