<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Package_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    // Get Role From Databases
    function get($params = array()) {
        
        $this->db->select('package_name, package_id');

        if (isset($params['id'])) {
            $this->db->where('package_id', $params['id']);
        }
        
        if (isset($params['status'])) {
            $this->db->where('package_status', $params['status']);
        }
        
        if (isset($params['package_id'])) {
            $this->db->where('package_id', $params['package_id']);
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
            $this->db->order_by('package_id', 'ASC');
        }
        
        $res = $this->db->get('package');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

}
