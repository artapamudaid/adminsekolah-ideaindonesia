<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Pendayagunaan_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    // Get program from database
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('program_id', $params['id']);
        }

        if (isset($params['program_name'])) {
            $this->db->like('program_name', $params['program_name']);
        }

        if (isset($params['account_id'])) {
            $this->db->where('program.account_account_id', $params['account_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('majors_id', $params['account_majors_id']);
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
            $this->db->order_by('program_status', 'DESC');
            $this->db->order_by('program_end', 'ASC');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('program_id, program_name, program_description, program_end, program_target,program_earn, program_gambar, program_status, account_account_id, pendayagunaan_account_id, account_id as account_id, program_realisasi, account_code as account_code, account_description as account_description');
        $this->db->join('account', 'account_id = program.account_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = account_majors_id', 'left');
        $res = $this->db->get('program');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // Add and update to database
    function add($data = array())
    {

        if (isset($data['program_id'])) {
            $this->db->set('program_id', $data['program_id']);
        }

        if (isset($data['program_name'])) {
            $this->db->set('program_name', $data['program_name']);
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

        if (isset($data['program_id'])) {
            $this->db->where('program_id', $data['program_id']);
            $this->db->update('program');
            $id = $data['program_id'];
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
        $this->db->where('program_id', $id);
        $this->db->delete('program');
    }
}
