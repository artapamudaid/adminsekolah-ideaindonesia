<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Layanan_publik_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    // Get program from database
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('layanan_publik_id', $params['id']);
        }

        if (isset($params['layanan_nama'])) {
            $this->db->like('layanan_nama', $params['layanan_nama']);
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
            $this->db->order_by('layanan_order', 'ASC');
        }

        $this->db->select('layanan_publik_id, layanan_nama, layanan_order, layanan_link');
        $res = $this->db->get('layanan_publik');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // Add and update to database
    function add($data = array())
    {

        if (isset($data['layanan_publik_id'])) {
            $this->db->set('layanan_publik_id', $data['layanan_publik_id']);
        }

        if (isset($data['layanan_nama'])) {
            $this->db->set('layanan_nama', $data['layanan_nama']);
        }

        if (isset($data['program_description'])) {
            $this->db->set('program_description', $data['program_description']);
        }

        if (isset($data['program_end'])) {
            $this->db->set('program_end', $data['program_end']);
        }

        if (isset($data['program_target'])) {
            $this->db->set('program_target', $data['program_target']);
        }

        if (isset($data['program_gambar'])) {
            $this->db->set('program_gambar', $data['program_gambar']);
        }

        if (isset($data['program_status'])) {
            $this->db->set('program_status', $data['program_status']);
        }

        if (isset($data['account_account_id'])) {
            $this->db->set('account_account_id', $data['account_account_id']);
        }

        if (isset($data['pendayagunaan_account_id'])) {
            $this->db->set('pendayagunaan_account_id', $data['pendayagunaan_account_id']);
        }

        if (isset($data['layanan_publik_id'])) {
            $this->db->where('layanan_publik_id', $data['layanan_publik_id']);
            $this->db->update('program');
            $id = $data['layanan_publik_id'];
        } else {
            $this->db->insert('program');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    // Delete program to database
    function delete($id)
    {
        $this->db->where('layanan_publik_id', $id);
        $this->db->delete('program');
    }
}
