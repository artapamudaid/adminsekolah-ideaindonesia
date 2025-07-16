<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registrasi_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array()) {   

        if (isset($params['id'])) {
            $this->db->where('registrasi.registrasi_id', $params['id']);
        }
        
        if (isset($params['registrasi_id'])) {
            $this->db->where('registrasi.registrasi_id', $params['registrasi_id']);
        }

        if (isset($params['registrasi_email'])) {
            $this->db->where('registrasi.registrasi_email', $params['registrasi_email']);
        }

        if (isset($params['registrasi_name'])) {
            $this->db->where('registrasi.registrasi_name', $params['registrasi_name']);
        }

        if (isset($params['dari_tanggal']) && isset($params['sampai_tanggal'])) {
            
        
            $this->db->where('date(registrasi_input_date) >=', $params['dari_tanggal']);
            $this->db->where('date(registrasi_last_update) <=', $params['sampai_tanggal']);
        }

    if (isset($params['search']) && !empty($params['search'])) {
      $this->db->group_start();
      $this->db->like('registrasi_name', $params['search']);
      $this->db->or_like('registrasi_school', $params['search']);
      $this->db->or_like('registrasi_email', $params['search']);
      $this->db->group_end();
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
            $this->db->order_by('registrasi_id', 'desc');
        }

        $this->db->select('registrasi.registrasi_id, registrasi_name, registrasi_school, registrasi_address, registrasi_email, registrasi_phone, registrasi_password, registrasi_sumber, date(registrasi_input_date) AS registrasi_date, registrasi_input_date, registrasi_last_update');
                            
        $this->db->select('city.city_id, city.city_name');
        $this->db->select('province_id, province_name');
        
        $this->db->join('city', 'city.city_id = registrasi.registrasi_city', 'left');
        $this->db->join('province', 'province.province_id = registrasi.registrasi_province', 'left');

        $res = $this->db->get('registrasi');

        if (isset($params['id'])) {
            return $res->row_array();
        } else if (isset($params['registrasi_email'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }

    }
    
    public function add($data = array()) {

        if (isset($data['registrasi_id'])) {
            $this->db->set('registrasi_id', $data['registrasi_id']);
        }

        if (isset($data['registrasi_name'])) {
            $this->db->set('registrasi_name', $data['registrasi_name']);
        }

        if (isset($data['registrasi_school'])) {
            $this->db->set('registrasi_school', $data['registrasi_school']);
        }

        if (isset($data['registrasi_address'])) {
            $this->db->set('registrasi_address', $data['registrasi_address']);
        }

        if (isset($data['registrasi_province'])) {
            $this->db->set('registrasi_province', $data['registrasi_province']);
        }

        if (isset($data['registrasi_city'])) {
            $this->db->set('registrasi_city', $data['registrasi_city']);
        }

        if (isset($data['registrasi_sumber'])) {
            $this->db->set('registrasi_sumber', $data['registrasi_sumber']);
        }

        if (isset($data['registrasi_phone'])) {
            $this->db->set('registrasi_phone', $data['registrasi_phone']);
        }

        if (isset($data['registrasi_email'])) {
            $this->db->set('registrasi_email', $data['registrasi_email']);
        }

        if (isset($data['registrasi_password'])) {
            $this->db->set('registrasi_password', $data['registrasi_password']);
        }

        if (isset($data['registrasi_input_date'])) {
            $this->db->set('registrasi_input_date', $data['registrasi_input_date']);
        }

        if (isset($data['registrasi_last_update'])) {
            $this->db->set('registrasi_last_update', $data['registrasi_last_update']);
        }

        if (isset($data['registrasi_id'])) {
            $this->db->where('registrasi_id', $data['registrasi_id']);
            $this->db->update('registrasi');
            $id = $data['registrasi_id'];
        } else {
            $this->db->insert('registrasi');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function add_user($data = array()) {

        if (isset($data['user_id'])) {
            $this->db->set('user_id', $data['user_id']);
        }

        if (isset($data['user_password'])) {
            $this->db->set('user_password', $data['user_password']);
        }

        if (isset($data['user_full_name'])) {
            $this->db->set('user_full_name', $data['user_full_name']);
        }

        if (isset($data['user_email'])) {
            $this->db->set('user_email', $data['user_email']);
        }

        if (isset($data['user_image'])) {
            $this->db->set('user_image', $data['user_image']);
        }

        if (isset($data['user_description'])) {
            $this->db->set('user_description', $data['user_description']);
        }

        if (isset($data['user_input_date'])) {
            $this->db->set('user_input_date', $data['user_input_date']);
        }

        if (isset($data['user_last_update'])) {
            $this->db->set('user_last_update', $data['user_last_update']);
        }

        if (isset($data['user_is_deleted'])) {
            $this->db->set('user_is_deleted', $data['user_is_deleted']);
        }

        if (isset($data['user_role_role_id'])) {
            $this->db->set('user_role_role_id', $data['user_role_role_id']);
        }

        if (isset($data['user_majors_id'])) {
            $this->db->set('user_majors_id', $data['user_majors_id']);
        }

        if (isset($data['user_id'])) {
            $this->db->where('user_id', $data['user_id']);
            $this->db->update('users');
            $id = $data['user_id'];
        } else {
            $this->db->insert('users');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function get_province($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('province_id', $params['id']);
        }

        if(isset($params['province_name']))
        {
            $this->db->where('province_name', $params['province_name']);
        }

        if(isset($params['province_id']))
        {
            $this->db->where('province_id', $params['province_id']);
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
            $this->db->order_by('province_id', 'asc');
        }
        
        $this->db->select('province_id, province_name');
        
        $res = $this->db->get('province');

        if(isset($params['id']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }
    
    function get_city($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('city_id', $params['id']);
        }

        if(isset($params['city_name']))
        {
            $this->db->where('city_name', $params['city_name']);
        }

        if(isset($params['city_id']))
        {
            $this->db->where('city_id', $params['city_id']);
        }

        if(isset($params['province_id']))
        {
            $this->db->where('province_id', $params['province_id']);
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
            $this->db->order_by('city_id', 'asc');
        }
        
        $this->db->select('city_id, city_name');
        $this->db->select('province_id, province_name');
        $this->db->join('province', 'province.province_id=city.city_province_id', 'left');
        
        $res = $this->db->get('city');

        if(isset($params['id']))
        {
            return $res->row_array();
        } else if(isset($params['email']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }
    
}