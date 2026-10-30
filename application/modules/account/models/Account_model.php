<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class Account_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    // Get account from database
    function get($params = array())
    {
        $cache_key = get_session_cache_key('account', $params);
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
            log_message('error', 'Cache read error in Account_model::get: ' . $e->getMessage());
        }

        if (isset($params['id'])) {
            $this->db->where('account_id', $params['id']);
        }

        if (isset($params['account_code'])) {
            $this->db->like('account_code', $params['account_code']);
        }

        if (isset($params['account_category'])) {
            $this->db->where('account_category', $params['account_category']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
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
            $this->db->order_by('account_id', 'desc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('account_id, account_code, account_description, account_note, account_category, account_majors_id, majors_id, majors_short_name');
        $this->db->join('majors', 'majors.majors_id = account.account_majors_id', 'left');
        $res = $this->db->get('account');

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
            log_message('error', 'Cache write error in Account_model::get: ' . $e->getMessage());
        }

        return $result;
    }

    // Add and update to database
    function add($data = array())
    {

        if (isset($data['account_id'])) {
            $this->db->set('account_id', $data['account_id']);
        }

        if (isset($data['account_code'])) {
            $this->db->set('account_code', $data['account_code']);
        }

        if (isset($data['account_description'])) {
            $this->db->set('account_description', $data['account_description']);
        }

        if (isset($data['account_note'])) {
            $this->db->set('account_note', $data['account_note']);
        }

        if (isset($data['account_category'])) {
            $this->db->set('account_category', $data['account_category']);
        }

        if (isset($data['account_majors_id'])) {
            $this->db->set('account_majors_id', $data['account_majors_id']);
        }

        if (isset($data['account_id'])) {
            $this->db->where('account_id', $data['account_id']);
            $this->db->update('account');
            $id = $data['account_id'];
        } else {
            $this->db->insert('account');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        bump_cache_version('account');
        return ($status == 0) ? FALSE : $id;
    }

    // Delete account to database
    function delete($id)
    {
        $this->db->where('account_id', $id);
        $this->db->delete('account');
        bump_cache_version('account');
    }

    // Helper to invalidate cache from external callers
    public function invalidate_cache()
    {
        bump_cache_version('account');
    }

    public function getDefaultAccount()
    {
        $cache_key = get_session_cache_key('account', array('scope' => 'default'));
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
            log_message('error', 'Cache read error in Account_model::getDefaultAccount: ' . $e->getMessage());
        }

        $this->db->where('account_majors_id', 0)
            ->order_by('account_id', 'asc');
        $res = $this->db->get('account');
        $result = $res->result_array();

        try {
            if (isset($this->cache)) {
                $this->cache->save($cache_key, $result, CACHE_TTL);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cache write error in Account_model::getDefaultAccount: ' . $e->getMessage());
        }

        return $result;
    }

    // Get all descendants IDs recursively
    public function get_descendants_ids($ids)
    {
        if (empty($ids)) {
            return array();
        }

        $all_ids = array();
        $current_level_ids = $ids;

        while (!empty($current_level_ids)) {
            $this->db->select('account_id');
            $this->db->where_in('account_note', $current_level_ids);
            $res = $this->db->get('account')->result_array();

            $next_level_ids = array();
            foreach ($res as $row) {
                if (!in_array($row['account_id'], $all_ids)) {
                    $all_ids[] = $row['account_id'];
                    $next_level_ids[] = $row['account_id'];
                }
            }
            $current_level_ids = $next_level_ids;
        }

        return $all_ids;
    }

    /**
     * Mengambil seluruh data COA (Chart of Accounts) dalam 1 batch query
     * dan menyusun indeks hierarki di memori PHP untuk menghindari nested query N+1 di view.
     * Hasil disimpan di cache agar pemanggilan berulang sangat cepat.
     */
    public function get_coa_tree_data($filter_majors = null)
    {
        $cache_key = get_session_cache_key('account', array('coa_tree_majors' => $filter_majors));
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
            log_message('error', 'Cache read error in Account_model::get_coa_tree_data: ' . $e->getMessage());
        }

        // 1. Ambil semua akun + majors_short_name (1 query)
        $this->db->select('account.*, majors.majors_id, majors.majors_short_name');
        $this->db->from('account');
        $this->db->join('majors', 'majors.majors_id = account.account_majors_id', 'left');
        $this->db->order_by('account.account_code', 'ASC');
        $all_accounts = $this->db->get()->result();

        // 2. Ambil semua account_account_id dari tabel POS (1 query)
        $pos_records = $this->db->select('account_account_id')->get('pos')->result_array();
        $pos_account_map = array();
        foreach ($pos_records as $pr) {
            $pos_account_map[$pr['account_account_id']] = true;
        }

        // 3. Ambil periode aktif (1 query)
        $periods = $this->db->get_where('period', array('period_status' => '1'))->result();

        // 4. Ambil akun piutang (1 query)
        $this->db->like('account_code', '1', 'after');
        $this->db->like('account_description', 'Piutang');
        $this->db->where('account_category', '2');
        $piutangs = $this->db->get('account')->result();

        $piutangs_by_majors = array();
        foreach ($piutangs as $p) {
            $piutangs_by_majors[$p->account_majors_id][] = $p;
        }

        // Hitung max account_code per parent dari SEMUA akun (unfiltered)
        $max_code_by_parent = array();
        foreach ($all_accounts as $acc) {
            $pId = (!empty($acc->account_note)) ? (string)$acc->account_note : '0';
            if (!isset($max_code_by_parent[$pId]) || strcmp($acc->account_code, $max_code_by_parent[$pId]) > 0) {
                $max_code_by_parent[$pId] = $acc->account_code;
            }
        }

        // Kelompokkan akun untuk tampilan hierarki
        $main_accounts = array();
        $accounts_by_parent = array();

        foreach ($all_accounts as $acc) {
            $pId = (!empty($acc->account_note)) ? (string)$acc->account_note : '0';

            if ($pId === '0') {
                $main_accounts[] = $acc;
            } else {
                if ($filter_majors === null || $acc->account_majors_id == $filter_majors) {
                    $accounts_by_parent[$pId][] = $acc;
                }
            }
        }

        $result = array(
            'main_accounts'      => $main_accounts,
            'accounts_by_parent' => $accounts_by_parent,
            'max_code_by_parent' => $max_code_by_parent,
            'pos_account_map'    => $pos_account_map,
            'periods'            => $periods,
            'piutangs_all'       => $piutangs,
            'piutangs_by_majors' => $piutangs_by_majors,
        );

        try {
            if (isset($this->cache)) {
                $this->cache->save($cache_key, $result, CACHE_TTL);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cache write error in Account_model::get_coa_tree_data: ' . $e->getMessage());
        }

        return $result;
    }
}
