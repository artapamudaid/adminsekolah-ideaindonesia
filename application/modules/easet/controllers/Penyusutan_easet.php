<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyusutan_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('login'));
		}

		$this->load->model('ModelPenyusutan', 'mp');
		$this->load->model('ModelKategori', 'mk');
		$this->load->helper('terbilang');
	}

	public function index()
	{
		$data = array(
			'title' => 'Penyusutan',
			'active_menu_pys' => 'active',
			'pys' => $this->mp->getAsetWujud(),
			'kategori' => $this->mk->getKategoriBarang()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('penyusutan/v_penyusutan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Data Penyusutan Aset';
		$data['main']           = 'easet/penyusutan/v_penyusutan';
		$this->load->view('manage/layout', $data);
	}

	public function detailPenyusutan($id_aset)
	{
		$id_aset = $this->uri->segment(5);
		$data = array(
			'title' => 'Penyusutan',
			'active_menu_pys' => 'active',
			'd' => $this->mp->getDetailAsetWujud($id_aset),
			'item' => $this->mp->getDetailAsetWujud1($id_aset)
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('penyusutan/d_penyusutan', $data);
		// $this->load->view('layouts/footer');
		$data['title']          = 'Detail Penyusutan Aset';
		$data['main']           = 'easet/penyusutan/d_penyusutan';
		$this->load->view('manage/layout', $data);
	}

	public function filterPenyusutan()
	{
		$id_kategori = $this->input->post('id_kategori');
		$tahun_perolehan = $this->input->post('tahun_perolehan');

		$data = array(
			'title' => 'Penyusutan',
			'active_menu_pys' => 'active',
			'pys' => $this->mp->getFilterAsetWujud($id_kategori, $tahun_perolehan),
			'kategori' => $this->mk->getKategoriBarang()
		);

		if (count($data['pys']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('penyusutan/v_penyusutan', $data);
			// $this->load->view('layouts/footer');
			$data['title']          = 'Data Penyusutan Aset';
			$data['main']           = 'easet/penyusutan/v_penyusutan';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('manage/easet/penyusutan');
		}
	}

	public function penghapusanAset($id_aset)
	{
		$id_aset = $this->uri->segment(5);
		$data['status_aset'] = 'Dihapuskan';
		unset($data['id_aset']);
		$result = $this->mp->updateAset($id_aset, $data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Dihapuskan');
			redirect('manage/easet/penyusutan');
		} else {
			$this->session->set_flashdata('gagal', 'Dihapuskan');
			redirect('manage/easet/penyusutan');
		}
	}
}

/* End of file Penyusutan.php */
/* Location: ./application/controllers/Penyusutan.php */