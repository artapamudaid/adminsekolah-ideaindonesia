<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    // Get pos from database
    function get($params = array())
    {
        $cache_key = get_session_cache_key('pos', $params);
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
            log_message('error', 'Cache read error in Pos_model::get: ' . $e->getMessage());
        }

        if(isset($params['id']))
        {
            $this->db->where('pos_id', $params['id']);
        }

        if(isset($params['pos_name']))
        {
            $this->db->like('pos_name', $params['pos_name']);
        }
        
        if(isset($params['account_id']))
        {
            $this->db->where('pos.account_account_id', $params['account_id']);
        }
        
        if(isset($params['account_majors_id']))
        {
            $this->db->where('majors_id', $params['account_majors_id']);
        }
        
        if($this->session->userdata('umajorsid') != '0')
        {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
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
            $this->db->order_by('pos_id', 'desc');
        }
        
        $this->db->where('majors_status', '1');

        $this->db->select('pos_id, pos_name, pos_description, a.account_id as account_id, a.account_code as account_code, majors_id,
        a.account_description as account_description, p.account_id as piutang_id, p.account_code as piutang_code, 
        p.account_description as piutang_description');
        $this->db->join('account as a', 'a.account_id = pos.account_account_id', 'left');
        $this->db->join('account as p', 'p.account_id = pos.piutang_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = a.account_majors_id', 'left');
        $res = $this->db->get('pos');

        if(isset($params['id']))
        {
            $result = $res->row_array();
        }
        else
        {
            $result = $res->result_array();
        }

        try {
            if (isset($this->cache)) {
                $this->cache->save($cache_key, $result, CACHE_TTL);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cache write error in Pos_model::get: ' . $e->getMessage());
        }

        return $result;
    }
    
    // Add and update to database
    function add($data = array()) {
        
         if(isset($data['pos_id'])) {
            $this->db->set('pos_id', $data['pos_id']);
        }
        
         if(isset($data['pos_name'])) {
            $this->db->set('pos_name', $data['pos_name']);
        }

        if(isset($data['pos_description'])) {
            $this->db->set('pos_description', $data['pos_description']);
        }
        
        if(isset($data['account_account_id'])) {
            $this->db->set('account_account_id', $data['account_account_id']);
        }
        
        if(isset($data['piutang_account_id'])) {
            $this->db->set('piutang_account_id', $data['piutang_account_id']);
        }
        
        if (isset($data['pos_id'])) {
            $this->db->where('pos_id', $data['pos_id']);
            $this->db->update('pos');
            $id = $data['pos_id'];
        } else {
            $this->db->insert('pos');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        bump_cache_version('pos');
        return ($status == 0) ? FALSE : $id;
    }
    
    // Delete pos to database
    function delete($id) {
        $this->db->where('pos_id', $id);
        $this->db->delete('pos');
        bump_cache_version('pos');
    }

    public function invalidate_cache() {
        bump_cache_version('pos');
    }
    
}
