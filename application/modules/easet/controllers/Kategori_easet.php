<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('manage/auth/login'));
		}
		$this->load->model('ModelKategori', 'mk');
	}

	public function index()
	{
		$data = array(
			'title' => 'Jenis Barang',
			'active_menu_master' => 'menu-open',
			'active_menu_mst' => 'active',
			'active_menu_jb' => 'active',
			'jb' => $this->mk->getKategoriBarang()
		);

		$data['title']          = 'Barang';
		$data['main']           = 'easet/master/v_jb';
		$this->load->view('manage/layout', $data);
	}

	public function store()
	{
		$data = array(
			'kode_kategori' => $this->input->post('kode_kategori'),
			'nama_kategori' => $this->input->post('nama_kategori'),
			'akun_id' => $this->input->post('akun_barang'),
			'updated_at' => date('Y-m-d H:i:s')
		);

		$result = $this->mk->storeKategoriBarang($data);

		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Disimpan');
			redirect('manage/easet/kategori');
		} else {
			$this->session->set_flashdata('gagal', 'Disimpan');
			redirect('manage/easet/kategori');
		}
	}

	public function ubah()
	{
		$id_kategori = $this->input->post('id_kategori');
		$data = array(
			'kode_kategori' => $this->input->post('kode_kategori'),
			'nama_kategori' => $this->input->post('nama_kategori'),
			'akun_id' => $this->input->post('akun_barang'),
			'updated_at' => date('Y-m-d H:i:s')
		);
		unset($data['id_kategori']);
		$result = $this->mk->updateKategoriBarang($id_kategori, $data);

		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Diubah');
			redirect('manage/easet/kategori');
		} else {
			$this->session->set_flashdata('gagal', 'Diubah');
			redirect('manage/easet/kategori');
		}
	}

	public function hapus($id_kategori)
	{
		$id_kategori = $this->uri->segment(5);
		$where = array('id_kategori' => $id_kategori);
		$res = $this->mk->deleteKategoriBarang($where);
		if ($res >= 1) {
			$this->session->set_flashdata('sukses', 'Dihapus');
			redirect('manage/easet/kategori');
		} else {
			$this->session->set_flashdata('gagal', 'Dihapus');
			redirect('manage/easet/kategori');
		}
	}
}

/* End of file JenisBarang.php */
/* Location: ./application/controllers/JenisBarang.php */