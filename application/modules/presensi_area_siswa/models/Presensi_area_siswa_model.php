<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Presensi_area_siswa_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Database
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('setting_presensi_siswa.id', $params['id']);
        }

        if (isset($params['id_area_absensi'])) {
            $this->db->where('setting_presensi_siswa.id_area_absensi', $params['id_area_absensi']);
        }

        if (isset($params['presensi_area_search'])) {
            $this->db->like('area_absensi.nama_area', $params['presensi_area_search']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }
            $this->db->limit($params['limit'], $params['offset']);
        }

        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by']);
        } else {
            $this->db->order_by('setting_presensi_siswa.id', 'asc');
        }

        $this->db->select('setting_presensi_siswa.*, area_absensi.nama_area, area_absensi.longi, area_absensi.lati, area_absensi.remark');
        $this->db->join('area_absensi', 'area_absensi.id_area = setting_presensi_siswa.id_area_absensi', 'left');

        $res = $this->db->get('setting_presensi_siswa');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('setting_presensi_siswa', $data);
            $status = $data['id'];
        } else {
            $this->db->insert('setting_presensi_siswa', $data);
            $status = $this->db->insert_id();
        }

        return $status;
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('setting_presensi_siswa');
    }

    function get_area_absen()
    {
        $this->db->order_by('nama_area', 'asc');
        $this->db->select('id_area, nama_area');
        $res = $this->db->get('area_absensi');
        return $res->result_array();
    }
}

