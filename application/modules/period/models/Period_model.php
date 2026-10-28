<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class Period_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    // Get period from database
    function get($params = array())
    {
        $cache_key = get_session_cache_key('period', $params);
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
            log_message('error', 'Cache read error in Period_model::get: ' . $e->getMessage());
        }

        if (isset($params['id'])) {
            $this->db->where('period_id', $params['id']);
        }

        if (isset($params['status'])) {
            $this->db->where('period_status', $params['status']);
        }

        if (isset($params['period_start'])) {
            $this->db->where('period_start', $params['period_start']);
        }
        if (isset($params['period_end'])) {
            $this->db->where('period_end', $params['period_end']);
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
        }

        $this->db->select('period_id, period_start, period_end, period_status');
        $res = $this->db->get('period');

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
            log_message('error', 'Cache write error in Period_model::get: ' . $e->getMessage());
        }

        return $result;
    }

    // Add and update to database
    function add($data = array())
    {

        if (isset($data['period_id'])) {
            $this->db->set('period_id', $data['period_id']);
        }

        if (isset($data['period_start'])) {
            $this->db->set('period_start', $data['period_start']);
        }

        if (isset($data['period_end'])) {
            $this->db->set('period_end', $data['period_end']);
        }

        if (isset($data['period_status'])) {
            $this->db->set('period_status', $data['period_status']);
        }

        if (isset($data['period_id'])) {
            $this->db->where('period_id', $data['period_id']);
            $this->db->update('period');
            $id = $data['period_id'];
        } else if (isset($data['status_active'])) {
            $this->db->where('period_id !=', $data['period_id']);
            $this->db->set('period_status', 0);
            $this->db->update('period');
            $id = NULL;
        } else {
            $this->db->insert('period');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        bump_cache_version('period');
        return ($status == 0) ? FALSE : $id;
    }

    // Delete period to database
    function delete($id)
    {
        $this->db->where('period_id', $id);
        $this->db->delete('period');
        bump_cache_version('period');
    }
}
