<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penghapusan_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('login'));
		}

		//load model
		$this->load->model('ModelPenghapusan', 'mp');
		$this->load->model('ModelPenyusutan', 'mpy');
		$this->load->model('ModelAset', 'ma');
		$this->load->helper('terbilang');
	}

	public function index()
	{
		$data = array(
			'title' => 'Penghapusan Aset',
			'active_menu_penghapusan' => 'active',
			'aset' => $this->ma->getAsetWujud(),
			'pys' => $this->mpy->getAsetWujud()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('penghapusan/v_penghapusan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Data Penghapusan Aset';
		$data['main']           = 'easet/penghapusan/v_penghapusan';
		$this->load->view('manage/layout', $data);
	}

	public function simpanPenghapusan()
	{
		$id_aset = $this->input->post('id_aset');
		$jumlah = $this->input->post('jumlah');
		$faktor = $this->input->post('faktor');
		$tgl_penghapusan = $this->input->post('tgl_penghapusan');

		$row = $this->mp->getDetailAsetWujud($id_aset);

		if ($jumlah > $row['volume']) {
			$this->session->set_flashdata('gagal_store', 'Data gagal disimpan, jumlah dihapuskan melebihi jumlah volume dari aset..');
			redirect('manage/easet/penghapusan');
		} else if ($jumlah == $row['volume']) {
			$opr = $row['volume'] - $jumlah;

			$x['volume'] = $opr;
			$this->mp->updateAset($id_aset, $x);

			$data = array(
				'id_aset' => $id_aset,
				'jumlah' => $jumlah,
				'faktor' => $faktor,
				'tgl_penghapusan' => $tgl_penghapusan,
				'status' => 'Dihapuskan'
			);

			$res = $this->mp->storePenghapusan($data);

			if ($res >= 1) {
				$this->session->set_flashdata('sukses', 'Disimpan');
				redirect('manage/easet/aset/asetDihapuskan');
			} else {
				$this->session->set_flashdata('gagal', 'Disimpan');
				redirect('manage/easet/aset/asetDihapuskan');
			}
		} else {
			$opr = $row['volume'] - $jumlah;

			$x['volume'] = $opr;
			$this->mp->updateAset($id_aset, $x);

			$data = array(
				'id_aset' => $id_aset,
				'jumlah' => $jumlah,
				'faktor' => $faktor,
				'tgl_penghapusan' => $tgl_penghapusan,
				'status' => 'Dihapuskan'
			);

			$res = $this->mp->storePenghapusan($data);

			if ($res >= 1) {
				$this->session->set_flashdata('sukses', 'Disimpan');
				redirect('manage/easet/aset/asetDihapuskan');
			} else {
				$this->session->set_flashdata('gagal', 'Disimpan');
				redirect('manage/easet/aset/asetDihapuskan');
			}
		}
	}
}

/* End of file Penghapusan.php */
/* Location: ./application/controllers/Penghapusan.php */