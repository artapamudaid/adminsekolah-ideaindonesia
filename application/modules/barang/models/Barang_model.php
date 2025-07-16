<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('barang_id', $params['id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('user_id', $params['user_id']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['student_id'])) {
			$this->db->where('student_id', $params['student_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('barang_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('barang.barang_id, barang_no, barang.barang_name, barang_stok, barang.keterangan, gambar, satuan_id, nama_satuan');

		$this->db->join('satuan_barang', 'barang.satuan_id = satuan_barang.satuan_barang_id ', 'left');
		$this->db->join('users', 'barang.user_id = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('barang');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

    // Add and update to database
	function add($data = array()) {

		if(isset($data['barang_id'])) {
			$this->db->set('barang_id', $data['barang_id']);
		}

		if(isset($data['barang_no'])) {
			$this->db->set('barang_no', $data['barang_no']);
		}

		if(isset($data['barang_name'])) {
			$this->db->set('barang_name', $data['barang_name']);
		}

		if(isset($data['barang_stok'])) {
			$this->db->set('barang_stok', $data['barang_stok']);
		}

		if(isset($data['satuan_id'])) {
			$this->db->set('satuan_id', $data['satuan_id']);
		}

		if(isset($data['keterangan'])) {
			$this->db->set('keterangan', $data['keterangan']);
		}

		if(isset($data['gambar'])) {
			$this->db->set('gambar', $data['gambar']);
		}

		if(isset($data['user_id'])) {
			$this->db->set('user_id', $data['user_id']);
		}

		if (isset($data['barang_id'])) {
			$this->db->where('barang_id', $data['barang_id']);
			$this->db->update('barang');
			$id = $data['barang_id'];
		} else {
			$this->db->insert('barang');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('barang_id', $id);
		$this->db->delete('barang');

	}//========================== satuan Barang model====================

	function get_satuan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('satuan_barang_id', $params['id']);
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
            $this->db->order_by('satuan_barang_id', 'asc');
        }

        $this->db->select('satuan_barang_id, nama_satuan, keterangan');

        $res = $this->db->get('satuan_barang');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

	function add_satuan($data = array()) {

        if (isset($data['satuan_barang_id'])) {
            $this->db->set('satuan_barang_id', $data['satuan_barang_id']);
        }

        if (isset($data['nama_satuan'])) {
            $this->db->set('nama_satuan', $data['nama_satuan']);
        }

		if (isset($data['keterangan'])) {
            $this->db->set('keterangan', $data['keterangan']);
        }

        if (isset($data['satuan_barang_id'])) {
            $this->db->where('satuan_barang_id', $data['satuan_barang_id']);
            $this->db->update('satuan_barang');
            $id = $data['satuan_barang_id'];
        } else {
            $this->db->insert('satuan_barang');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_satuan($id) {
        $this->db->where('satuan_barang_id', $id);
        $this->db->delete('satuan_barang');
    }

}
