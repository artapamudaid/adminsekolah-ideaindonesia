<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('tbl_surat_masuk.id_surat', $params['id']);
		}

		if (isset($params['no_agenda'])) {
			$this->db->where('no_agenda', $params['no_agenda']);
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
		    $this->db->order_by('tbl_surat_masuk.id_surat', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('tbl_surat_masuk.id_surat, no_agenda, no_surat, asal_surat, isi, kode, indeks, tgl_surat, tgl_diterima, file, keterangan, tbl_surat_masuk.id_user, disposisi');
        $this->db->select('user_full_name');
		$this->db->select('id_disposisi, tujuan, isi_disposisi, sifat, batas_waktu, catatan');

        $this->db->join('users', 'users.user_id = tbl_surat_masuk.id_user', 'left');
		$this->db->join('tbl_disposisi', 'tbl_disposisi.id_surat = tbl_surat_masuk.id_surat', 'left');

		$res = $this->db->get('tbl_surat_masuk');

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

		if(isset($data['no_agenda'])) {
			$this->db->set('no_agenda', $data['no_agenda']);
		}

		if(isset($data['no_surat'])) {
			$this->db->set('no_surat', $data['no_surat']);
		}

		if(isset($data['asal_surat'])) {
			$this->db->set('asal_surat', $data['asal_surat']);
		}

		if(isset($data['isi'])) {
			$this->db->set('isi', $data['isi']);
		}

		if(isset($data['kode'])) {
			$this->db->set('kode', $data['kode']);
		}

		if(isset($data['indeks'])) {
			$this->db->set('indeks', $data['indeks']);
		}

		if(isset($data['tgl_surat'])) {
			$this->db->set('tgl_surat', $data['tgl_surat']);
		}

		if(isset($data['tgl_diterima'])) {
			$this->db->set('tgl_diterima', $data['tgl_diterima']);
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

		if(isset($data['disposisi'])) {
			$this->db->set('disposisi', $data['disposisi']);
		}

		if (isset($data['id_surat'])) {
			$this->db->where('id_surat', $data['id_surat']);
			$this->db->update('tbl_surat_masuk');
			$id = $data['id_surat'];
		} else {
			$this->db->insert('tbl_surat_masuk');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
	}

    // Delete tbl_surat_masuk to database
	function delete($id) {
		$this->db->where('id_surat', $id);
		$this->db->delete('tbl_surat_masuk');
	}

	//========================================== DISPOSISI MODUL ----------------

	function get_disposisi($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('id_disposisi', $params['id']);
		}

		if (isset($params['tujuan'])) {
			$this->db->where('tujuan', $params['tujuan']);
		}

		if (isset($params['isi_disposisi'])) {
			$this->db->where('isi_disposisi', $params['isi_disposisi']);
		}

		if (isset($params['sifat'])) {
			$this->db->where('sifat', $params['sifat']);
		}

		if (isset($params['batas_waktu'])) {
			$this->db->where('batas_waktu', $params['batas_waktu']);
		}

		if (isset($params['catatan'])) {
			$this->db->where('catatan', $params['catatan']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('id_disposisi', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('id_disposisi, tujuan, isi_disposisi, sifat, batas_waktu, catatan, id_surat, id_user');
        $this->db->select('user_full_name');
		
		$this->db->join('users', 'users.user_id = tbl_disposisi.id_user', 'left');

		$res = $this->db->get('tbl_disposisi');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

	// Add and update Disposisi to database
	function add_disposisi($data = array()) {

		if(isset($data['id_disposisi'])) {
			$this->db->set('id_disposisi', $data['id_disposisi']);
		}

		if(isset($data['tujuan'])) {
			$this->db->set('tujuan', $data['tujuan']);
		}

		if(isset($data['isi_disposisi'])) {
			$this->db->set('isi_disposisi', $data['isi_disposisi']);
		}

		if(isset($data['sifat'])) {
			$this->db->set('sifat', $data['sifat']);
		}

		if(isset($data['batas_waktu'])) {
			$this->db->set('batas_waktu', $data['batas_waktu']);
		}

		if(isset($data['catatan'])) {
			$this->db->set('catatan', $data['catatan']);
		}

		if(isset($data['id_surat'])) {
			$this->db->set('id_surat', $data['id_surat']);
		}

		if(isset($data['id_user'])) {
			$this->db->set('id_user', $data['id_user']);
		}

		if (isset($data['id_disposisi'])) {
			$this->db->where('id_disposisi', $data['id_disposisi']);
			$this->db->update('tbl_disposisi');
			$id = $data['id_disposisi'];
		} else {
			$this->db->insert('tbl_disposisi');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}
}
