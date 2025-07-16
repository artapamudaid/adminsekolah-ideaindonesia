<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_keluar_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('id_surat', $params['id']);
		}

		if (isset($params['no_surat'])) {
			$this->db->where('no_surat', $params['no_surat']);
		}

		if (isset($params['tgl_surat'])) {
			$this->db->where('tgl_surat', $params['tgl_surat']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('id_surat', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('id_surat, id_jenis_surat, tujuan, no_surat, isi, kode, tgl_surat, tgl_catat, file, keterangan, tbl_surat_keluar.id_user, no_agenda');
        $this->db->select('user_full_name, nama_jenis, kode_surat');
		
        $this->db->join('users', 'users.user_id = tbl_surat_keluar.id_user', 'left');
		$this->db->join('surat_jenis', 'surat_jenis.id_jenis = tbl_surat_keluar.id_jenis_surat');
		$res = $this->db->get('tbl_surat_keluar');

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

		if(isset($data['id_surat'])) {
			$this->db->set('id_surat', $data['id_surat']);
		}

		if(isset($data['id_jenis_surat'])) {
			$this->db->set('id_jenis_surat', $data['id_jenis_surat']);
		}

		if(isset($data['no_surat'])) {
			$this->db->set('no_surat', $data['no_surat']);
		}

		if(isset($data['tujuan'])) {
			$this->db->set('tujuan', $data['tujuan']);
		}

		if(isset($data['isi'])) {
			$this->db->set('isi', $data['isi']);
		}

		if(isset($data['kode'])) {
			$this->db->set('kode', $data['kode']);
		}

		if(isset($data['tgl_surat'])) {
			$this->db->set('tgl_surat', $data['tgl_surat']);
		}

		if(isset($data['tgl_catat'])) {
			$this->db->set('tgl_catat', $data['tgl_catat']);
		}

		if(isset($data['file'])) {
			$this->db->set('file', $data['file']);
		}

		if(isset($data['keterangan'])) {
			$this->db->set('keterangan', $data['keterangan']);
		}

		if(isset($data['id_user'])) {
			$this->db->set('id_user', $data['id_user']);
		}

		if(isset($data['no_agenda'])) {
			$this->db->set('no_agenda', $data['no_agenda']);
		}

		if (isset($data['id_surat'])) {
			$this->db->where('id_surat', $data['id_surat']);
			$this->db->update('tbl_surat_keluar');
			$id = $data['id_surat'];
		} else {
			$this->db->insert('tbl_surat_keluar');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete tbl_surat_keluar to database
	function delete($id) {
		$this->db->where('id_surat', $id);
		$this->db->delete('tbl_surat_keluar');
	}

}
