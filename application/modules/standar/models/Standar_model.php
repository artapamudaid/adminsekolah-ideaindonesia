<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Standar_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id', $params['id']);
        }

        if (isset($params['name'])) {
            $this->db->where('name', $params['name']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('standar.standar_majors_id', $params['majors_id']);
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
            $this->db->order_by('standar.standar_majors_id', 'asc');
            $this->db->order_by('standar.name', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('id, name, standar_majors_id, majors_id, majors_short_name');
        $this->db->join('majors', 'majors.majors_id=standar.standar_majors_id', 'left');

        $res = $this->db->get('standar');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_item($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('aktivitas_id', $params['id']);
        }

        if (isset($params['aktivitas_id'])) {
            $this->db->where('id', $params['aktivitas_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('majors_id', $params['majors_id']);
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
            $this->db->order_by('aktivitas.aktivitas_kode', 'asc');
        }

        $this->db->select('aktivitas_id, aktivitas_kode, aktivitas_name, id, name');
        $this->db->select('account_code, account_description');

        $this->db->join('standar', 'standar.id=aktivitas.aktivitas_standar_id', 'left');
        $this->db->join('majors', 'majors.majors_id=standar.standar_majors_id', 'left');
        $this->db->join('account', 'account.account_id=aktivitas.aktivitas_account_id', 'left');

        $res = $this->db->get('aktivitas');

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

        if (isset($data['name'])) {
            $this->db->set('name', $data['name']);
        }

        if (isset($data['standar_majors_id'])) {
            $this->db->set('standar_majors_id', $data['standar_majors_id']);
        }

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('standar');
            $id = $data['id'];
        } else {
            $this->db->insert('standar');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function add_item($data = array())
    {

        if (isset($data['aktivitas_standar_id'])) {
            $this->db->set('aktivitas_standar_id', $data['aktivitas_standar_id']);
        }

        if (isset($data['aktivitas_kode'])) {
            $this->db->set('aktivitas_kode', $data['aktivitas_kode']);
        }

        if (isset($data['aktivitas_account_id'])) {
            $this->db->set('aktivitas_account_id', $data['aktivitas_account_id']);
        }

        if (isset($data['aktivitas_name'])) {
            $this->db->set('aktivitas_name', $data['aktivitas_name']);
        }

        if (isset($data['aktivitas_id'])) {
            $this->db->where('aktivitas_id', $data['aktivitas_id']);
            $this->db->update('aktivitas');
            $id = $data['aktivitas_id'];
        } else {
            $this->db->insert('aktivitas');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('standar');
    }

    function delete_item($id)
    {
        $this->db->where('aktivitas_id', $id);
        $this->db->delete('aktivitas');
    }
}
