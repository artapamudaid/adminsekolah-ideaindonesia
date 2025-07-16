<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_jenis_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
    //sampai disini
	function get($params = array())
	{
		if (isset($params['id'])) {
            $this->db->where('id_jenis', $params['id']);
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
            $this->db->order_by('id_jenis', 'asc');
        }

        $this->db->select('id_jenis, nama_jenis, id_user, kode_surat, tanggal_create, users.user_full_name');
		
        $this->db->join('users', 'users.user_id = surat_jenis.id_user', 'left');

        $res = $this->db->get('surat_jenis');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
	}

    // Add and update to database
	function add($data = array()) {

		if(isset($data['id_jenis'])) {
			$this->db->set('id_jenis', $data['id_jenis']);
		}

		if(isset($data['nama_jenis'])) {
			$this->db->set('nama_jenis', $data['nama_jenis']);
		}

		if(isset($data['id_user'])) {
			$this->db->set('id_user', $data['id_user']);
		}

		if(isset($data['kode_surat'])) {
			$this->db->set('kode_surat', $data['kode_surat']);
		}

		if(isset($data['tanggal_create'])) {
			$this->db->set('tanggal_create', $data['tanggal_create']);
		}

		if (isset($data['id_jenis'])) {
			$this->db->where('id_jenis', $data['id_jenis']);
			$this->db->update('surat_jenis');
			$id = $data['id_jenis'];
		} else {
			$this->db->insert('surat_jenis');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('id_jenis ', $id);
		$this->db->delete('surat_jenis');
	}
}
