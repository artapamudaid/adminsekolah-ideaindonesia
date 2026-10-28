<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa_model extends CI_Model {

    // Get COA Data Hierarchy
    public function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('coa.coa_id', $params['id']);
            $this->db->select('coa.*, majors.majors_name, majors.majors_short_name, group_account.group_name');
            $this->db->from('coa');
            $this->db->join('majors', 'majors.majors_id = coa.coa_majors', 'left');
            $this->db->join('group_account', 'group_account.group_id = coa.group_id', 'left');
            $res = $this->db->get();
            return $res->row_array();
        }

        // Hierarchy query (CTEs require MySQL 8.0 or MariaDB 10.2+)
        // `parent_id` defaults to 0 for root elements.
        
        $sql = "
        WITH RECURSIVE coaHierarchy AS (
            SELECT 
                c1.coa_id, c1.group_id, c1.coa_code, c1.coa_name, 
                c1.coa_type, c1.coa_majors, c1.is_active, 
                c1.parent_id, c1.coa_category,
                0 AS level
            FROM coa c1
            WHERE c1.parent_id = 0 OR c1.parent_id IS NULL
            
            UNION ALL
            
            SELECT 
                c2.coa_id, c2.group_id, c2.coa_code, c2.coa_name, 
                c2.coa_type, c2.coa_majors, c2.is_active, 
                c2.parent_id, c2.coa_category,
                h.level + 1 AS level
            FROM coa c2
            INNER JOIN coaHierarchy h ON c2.parent_id = h.coa_id
        )
        SELECT 
            ch.*, 
            majors.majors_name, majors.majors_short_name, 
            group_account.group_name,
            CONCAT(REPEAT('&nbsp;', ch.level * 4)) AS indented
        FROM coaHierarchy ch
        LEFT JOIN majors ON majors.majors_id = ch.coa_majors
        LEFT JOIN group_account ON group_account.group_id = ch.group_id
        WHERE 1=1
        ";
        
        $bindings = array();

        if (isset($params['coa_majors']) && $params['coa_majors'] != 'all' && !empty($params['coa_majors'])) {
            $sql .= " AND ch.coa_majors = ?";
            $bindings[] = $params['coa_majors'];
        }

        // Order by coa_code will inherently order the tree nicely as 1-01001.1 follows 1-01001
        $sql .= " ORDER BY ch.coa_code ASC";

        $res = $this->db->query($sql, $bindings);
        return $res->result_array();
    }

    // Add & Update COA
    public function add($data)
    {
        if (isset($data['coa_id'])) {
            $this->db->where('coa_id', $data['coa_id']);
            $this->db->update('coa', $data);
            return $data['coa_id'];
        } else {
            // Jika ada parent_id > 0, ubah status parent jadi HEADER
            if (isset($data['parent_id']) && $data['parent_id'] > 0) {
                // Eksekusi update header parent
                $this->db->where('coa_id', $data['parent_id']);
                $this->db->update('coa', array('coa_category' => 'HEADER'));
            }
            
            $this->db->insert('coa', $data);
            return $this->db->insert_id();
        }
    }

    // Delete COA
    public function delete($id)
    {
        // Pengecekan jika parent punya anak
        $this->db->where('parent_id', $id);
        $count = $this->db->count_all_results('coa');
        if ($count > 0) {
            return false; // tidak bisa dihapus jika punya anak
        }

        $this->db->where('coa_id', $id);
        $this->db->delete('coa');
        return true;
    }

    // Get Majors function as requested
    function get_majors() {
        $this->db->select('majors_id, majors_name, majors_short_name, majors_logo, majors_address, majors_phone, majors_wa_center, majors_school_name, majors_status');
        $this->db->where('majors_status', '1');
        $res = $this->db->get('majors');
        return $res->result_array();
    }

    // Get Group Account
    public function get_groups()
    {
        $this->db->order_by('group_id', 'ASC');
        $res = $this->db->get('group_account');
        return $res->result_array();
    }

    // Generate COA Code (Auto-increment format)
    public function generate_code($group_id, $majors_id, $parent_id)
    {
        if ($parent_id > 0) {
            // Ambil parent code
            $this->db->select('coa_code');
            $this->db->where('coa_id', $parent_id);
            $parent = $this->db->get('coa')->row();
            
            if (!$parent) return '';
            
            $parent_code = $parent->coa_code;

            // Cari anak dari parent ini untuk mendapatkan urutan
            $this->db->select('coa_code');
            $this->db->where('parent_id', $parent_id);
            
            // Note: with string numbers like .2 and .10, DESC ordering by string might sort .9 above .10.
            // Using LENGTH(coa_code) helps with generic alpha-numeric sorting if necessary, but CI active record
            // orderBy string might be tricky. Let's just pull and process all or use PHP.
            $this->db->order_by('LENGTH(coa_code)', 'DESC');
            $this->db->order_by('coa_code', 'DESC');
            $this->db->limit(1);
            $child_q = $this->db->get('coa');

            if ($child_q->num_rows() > 0) {
                $last_child = $child_q->row()->coa_code;
                $prefix_len = strlen($parent_code . '.');
                $seq_str = substr($last_child, $prefix_len);
                $next_seq = intval($seq_str) + 1;
            } else {
                $next_seq = 1;
            }
            
            return $parent_code . '.' . $next_seq;

        } else {
            $majors_padded = sprintf('%02d', $majors_id);
            $prefix = $group_id . '-' . $majors_padded;

            $this->db->select('coa_code');
            $this->db->where('parent_id', 0);
            $this->db->like('coa_code', $prefix, 'after');
            $this->db->order_by('coa_code', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get('coa');

            if ($query->num_rows() > 0) {
                $row = $query->row();
                $last_code = $row->coa_code;
                
                $seq_str = substr($last_code, strlen($prefix));
                $seq = intval($seq_str);
                $next_seq = $seq + 1;
            } else {
                $next_seq = 1;
            }

            return $prefix . sprintf('%03d', $next_seq);
        }
    }

}
