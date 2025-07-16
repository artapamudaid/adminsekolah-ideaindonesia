<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('manage/auth/login'));
		}

		//load model
		$this->load->model('ModelBarang', 'mb');
		$this->load->model('ModelKategori', 'mjb');
	}

	//menampilkan data barang
	public function index()
	{
		$data = array(
			'title' => 'Data Barang',
			'active_menu_master' => 'menu-open',
			'active_menu_mst' => 'active',
			'active_menu_brg' => 'active',
			'barang' => $this->mb->getDataBarang()
		);

		$data['title']          = 'Barang';
		$data['main']           = 'easet/master/v_barang';
		$this->load->view('manage/layout', $data);
	}

	public function tambahBarang()
	{
		$data = array(
			'title' => 'Data Barang',
			'active_menu_master' => 'menu-open',
			'active_menu_mst' => 'active',
			'active_menu_brg' => 'active',
			'jb' => $this->mjb->getKategoriBarang()
		);

		$data['title']          = 'Tambah Barang';
		$data['main']           = 'easet/master/c_barang';
		$this->load->view('manage/layout', $data);
	}

	public function simpanBarang()
	{
		$data = $this->input->post(null, true);
		$result = $this->mb->storeBarang($data);

		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Disimpan');
			redirect('manage/easet/barang');
		} else {
			$this->session->set_flashdata('gagal', 'Disimpan');
			redirect('manage/easet/barang/tambahBarang');
		}
	}

	public function editBarang($id_barang)
	{
		$id_barang = $this->uri->segment(5);

		$data = array(
			'title' => 'Data Barang',
			'active_menu_master' => 'menu-open',
			'active_menu_mst' => 'active',
			'active_menu_brg' => 'active',
			'brg' => $this->mb->getDetailBarang($id_barang),
			'jb' => $this->mjb->getKategoriBarang()
		);

		$data['title']          = 'Edit Barang';
		$data['main']           = 'easet/master/u_barang';
		$this->load->view('manage/layout', $data);
	}

	public function ubahBarang()
	{
		$id_barang = $this->input->post('id_barang');
		$data = $this->input->post(null, true);
		unset($data['id_barang']);
		$result = $this->mb->updateBarang($id_barang, $data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Diubah');
			redirect('manage/easet/barang');
		} else {
			$this->session->set_flashdata('gagal', 'Diubah');
			redirect('barang/edit/' . $id_barang);
		}
	}

	public function hapusBarang($id_barang)
	{
		$id_barang = $this->uri->segment(5);
		$where = array('id_barang' => $id_barang);
		$res = $this->mb->deleteBarang($where);
		if ($res >= 1) {
			// echo $id_barang;
			// exit;
			$this->session->set_flashdata('sukses', 'Dihapus');
			redirect('manage/easet/barang');
		} else {
			$this->session->set_flashdata('gagal', 'Dihapus');
			redirect('manage/easet/barang');
		}
	}
}

/* End of file Barang.php */
/* Location: ./application/controllers/Barang.php */