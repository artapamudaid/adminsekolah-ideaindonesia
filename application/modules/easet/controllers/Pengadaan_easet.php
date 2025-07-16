<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengadaan_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('login'));
		}

		//load model
		$this->load->model('ModelPengadaan', 'mp');
		$this->load->model('ModelLokasi', 'ml');
		$this->load->model('ModelMonitoring', 'mm');

		$this->load->helper('terbilang');
	}

	public function index()
	{
		$data = array(
			'title' => 'Data Kriteria',
			'active_menu_kp' => 'menu-open',
			'active_menu_kpn' => 'active',
			'active_menu_dk' => 'active',
			'spek' => $this->mp->getSpesifikasi(),
			'klts' => $this->mp->getKualitas()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('pengadaan/v_kriteria', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Data Kriteria';
		$data['main']           = 'easet/pengadaan/v_kriteria';
		$this->load->view('manage/layout', $data);
	}

	public function ubahSpesifikasi()
	{
		$id_spesifikasi = $this->input->post('id_spesifikasi');
		$data = array(
			'keterangan' => $this->input->post('keterangan'),
			'nilai' => $this->input->post('nilai')
		);
		unset($data['id_spesifikasi']);
		$result = $this->mp->updateSpesifikasi($id_spesifikasi, $data);

		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Diubah');
			redirect('manage/easet/pengadaan');
		} else {
			$this->session->set_flashdata('gagal', 'Diubah');
			redirect('manage/easet/pengadaan');
		}
	}

	public function ubahKualitas()
	{
		$id_kualitas = $this->input->post('id_kualitas');
		$data = array(
			'keterangan' => $this->input->post('keterangan'),
			'nilai' => $this->input->post('nilai')
		);
		unset($data['id_kualitas']);
		$result = $this->mp->updateKualitas($id_kualitas, $data);

		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Diubah');
			redirect('manage/easet/pengadaan');
		} else {
			$this->session->set_flashdata('gagal', 'Diubah');
			redirect('manage/easet/pengadaan');
		}
	}

	public function aset()
	{
		$data = array(
			'title' => 'Data Aset',
			'active_menu_kp' => 'menu-open',
			'active_menu_kpn' => 'active',
			'active_menu_da' => 'active',
			'aset' => $this->mp->getAset(),
			'spek' => $this->mp->getSpesifikasi(),
			'klts' => $this->mp->getKualitas(),
			'nilai' => $this->mp->getPenilaian()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('pengadaan/v_aset', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Data Aset';
		$data['main']           = 'easet/pengadaan/v_aset';
		$this->load->view('manage/layout', $data);
	}

	public function simpanAset()
	{
		$data = $this->input->post(null, true);
		$result = $this->mp->storeAset($data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Disimpan');
			redirect('manage/easet/pengadaan/aset');
		} else {
			$this->session->set_flashdata('gagal', 'Disimpan');
			redirect('manage/easet/pengadaan/aset');
		}
	}

	public function ubahAset()
	{
		$id_aset = $this->input->post('id_aset');
		$data = $this->input->post(null, true);
		unset($data['id_aset']);
		$result = $this->mp->updateAset($id_aset, $data);

		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Diubah');
			redirect('manage/easet/pengadaan/aset');
		} else {
			$this->session->set_flashdata('gagal', 'Diubah');
			redirect('manage/easet/pengadaan/aset');
		}
	}

	public function hapusAset($id_aset)
	{
		$id_aset = $this->uri->segment(5);
		$where = array('id_aset' => $id_aset);
		$res = $this->mp->deleteAset($where);
		if ($res >= 1) {
			$this->session->set_flashdata('sukses', 'Dihapus');
			redirect('manage/easet/pengadaan/aset');
		} else {
			$this->session->set_flashdata('gagal', 'Dihapus');
			redirect('manage/easet/pengadaan/aset');
		}
	}

	public function simpanPenilaian()
	{
		$data = $this->input->post(null, true);
		$result = $this->mp->storePenilaian($data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Disimpan');
			redirect('manage/easet/pengadaan/aset');
		} else {
			$this->session->set_flashdata('gagal', 'Disimpan');
			redirect('manage/easet/pengadaan/aset');
		}
	}

	public function ubahPenilaian()
	{
		$id_nilai = $this->input->post('id_nilai');
		$data = $this->input->post(null, true);
		unset($data['id_nilai']);
		$result = $this->mp->updatePenilaian($id_nilai, $data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Diubah');
			redirect('manage/easet/pengadaan/aset');
		} else {
			$this->session->set_flashdata('gagal', 'Diubah');
			redirect('manage/easet/pengadaan/aset');
		}
	}

	public function hapusPenilaian($id_nilai)
	{
		$id_nilai = $this->uri->segment(5);
		$where = array('id_nilai' => $id_nilai);
		$res = $this->mp->deletePenilaian($where);
		if ($res >= 1) {
			$this->session->set_flashdata('sukses', 'Dihapus');
			redirect('manage/easet/pengadaan/aset');
		} else {
			$this->session->set_flashdata('gagal', 'Dihapus');
			redirect('manage/easet/pengadaan/aset');
		}
	}

	public function spk()
	{
		$data = array(
			'title' => 'Data Aset',
			'active_menu_kp' => 'menu-open',
			'active_menu_kpn' => 'active',
			'active_menu_spk' => 'active',
			'nilai' => $this->mp->getPenilaian(),
			'maxspek' => $this->mp->getMaxSpesifikasi(),
			'maxkual' => $this->mp->getMaxKualitas(),
			'minharga' => $this->mp->getMinHarga()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('pengadaan/v_spk', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Data Aset';
		$data['main']           = 'easet/pengadaan/v_spk';
		$this->load->view('manage/layout', $data);
	}

	//Pengadaan
	public function pengajuan()
	{
		$data = array(
			'title' => 'Pengajuan',
			'active_menu_open_pnd' => 'menu-open',
			'active_pengadaan' => 'active',
			'active_menu_pnd' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'nilai' => $this->mp->getPenilaian(),
			'maxspek' => $this->mp->getMaxSpesifikasi(),
			'maxkual' => $this->mp->getMaxKualitas(),
			'minharga' => $this->mp->getMinHarga(),
			'mt' => $this->mm->getMonitoring()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('pengadaan/c_pengadaan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Pengajuan';
		$data['main']           = 'easet/pengadaan/c_pengadaan';
		$this->load->view('manage/layout', $data);
	}

	public function pengadaan()
	{
		$id_user = $this->session->userdata('uid');
		$data = array(
			'title' => 'Pengadaan',
			'active_menu_open_pnd' => 'menu-open',
			'active_pengadaan' => 'active',
			'active_menu_pgd' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'item' => $this->mp->getPengadaanAset(),
			'item_user' => $this->mp->getPengadaanAsetUser($id_user)
		);

		// $this->load->view('layouts/header', $data);
		// $this->load->view('pengadaan/v_pengadaan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Pengadaan';
		$data['main']           = 'easet/pengadaan/v_pengadaan';
		$this->load->view('manage/layout', $data);
	}

	public function simpanPengadaan()
	{
		$id_user = $this->session->userdata('uid');
		$data = array(
			'id_lokasi' => $this->input->post('id_lokasi'),
			'id_user' => $id_user,
			'nama_aset' => $this->input->post('nama_aset'),
			'volume' => $this->input->post('volume'),
			'satuan' => $this->input->post('satuan'),
			'harga_satuan' => $this->input->post('harga_satuan'),
			'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
			'status' => '0',
			'created_at' => date('Y-m-d H:i:s')
		);

		$result = $this->mp->storePengadaan($data);

		if ($result >= 1) {
			$this->session->set_flashdata('success', 'Disimpan');
			redirect('manage/easet/pengadaan/pengajuan');
		} else {
			$this->session->set_flashdata('failed', 'Disimpan');
			redirect('manage/easet/pengadaan/pengajuan');
		}
	}

	public function detailPengadaan($id_pengadaan)
	{
		$id_pengadaan = $this->uri->segment(5);
		$data = array(
			'title' => 'Pengadaan',
			'active_menu_open_pnd' => 'menu-open',
			'active_pengadaan' => 'active',
			'active_menu_pgd' => 'active',
			'item' => $this->mp->getDetailPengadaanAset($id_pengadaan)
		);
		$data['title']          = 'Detail Pengadaan';
		$data['main']           = 'easet/pengadaan/d_pengadaan';
		$this->load->view('manage/layout', $data);
	}

	public function setujuiPengadaan($id_pengadaan)
	{
		$id_pengadaan = $this->uri->segment(5);
		$data['status'] = '1';
		unset($data['id_pengadaan']);
		$result = $this->mp->updatePengadaan($id_pengadaan, $data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Disetujui');
			redirect('/manage/easet/pengadaan/pengadaan');
		} else {
			$this->session->set_flashdata('gagal', 'Disetujui');
			redirect('/manage/easet/pengadaan/pengadaan');
		}
	}

	public function tolakPengadaan($id_pengadaan)
	{
		$id_pengadaan = $this->uri->segment(5);
		$data['status'] = '2';
		unset($data['id_pengadaan']);
		$result = $this->mp->updatePengadaan($id_pengadaan, $data);
		if ($result >= 1) {
			$this->session->set_flashdata('sukses', 'Disetujui');
			redirect('/manage/easet/pengadaan/pengadaan');
		} else {
			$this->session->set_flashdata('gagal', 'Disetujui');
			redirect('/manage/easet/pengadaan/pengadaan');
		}
	}

	public function hapusPengadaan($id_pengadaan)
	{
		$id_pengadaan = $this->uri->segment(5);
		$where = array('id_pengadaan' => $id_pengadaan);
		$res = $this->mp->deletePengadaan($where);
		if ($res >= 1) {
			$this->session->set_flashdata('sukses', 'Dihapus');
			redirect('/manage/easet/pengadaan/pengadaan');
		} else {
			$this->session->set_flashdata('gagal', 'Dihapus');
			redirect('/manage/easet/pengadaan/pengadaan');
		}
	}

	public function filterPengadaan()
	{
		$id_lokasi = $this->input->post('id_lokasi');
		$tahun_pengadaan = $this->input->post('tahun_pengadaan');

		$data = array(
			'title' => 'Pengadaan',
			'active_menu_open_pnd' => 'menu-open',
			'active_pengadaan' => 'active',
			'active_menu_pgd' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'item' => $this->mp->getFilterPengadaanAset($id_lokasi, $tahun_pengadaan)
		);

		if (count($data['item']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('pengadaan/v_pengadaan', $data);
			// $this->load->view('layouts/footer');
			$data['title']          = 'Pengadaan';
			$data['main']           = 'easet/pengadaan/v_pengadaan';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('/manage/easet/pengadaan/pengadaan');
		}
	}

	public function testpk()
	{
		$spk = $this->mp->getPenilaian();
		$maxspek = $this->mp->getMaxSpesifikasi();
		$maxkual = $this->mp->getMaxKualitas();
		$minharga = $this->mp->getMinHarga();

		$arr = array();
		$no = 1;
		foreach ($spk as $row) {
			$spek = ($row['nilai_spek'] / $maxspek['maks_spek']);
			$kual = ($row['nilai_kualitas'] / $maxkual['maks_kualitas']);
			$hrg = ($row['harga'] / $minharga['min_harga']);
			$nilai = round(($spek * 0.3) + ($kual * 0.3) + ($hrg * 0.4), 3);

			$arr[] = $nilai . ' yaitu V' . $no++ . ' dengan nama aset ' . $row['nama_aset'];
		}

		echo "<p>Dari hasil perhitungan ranking diatas, maka pemilihan aset terbaik untuk pengadaan dengan nilai tertinggi " . $output;
	}
}

/* End of file Pengadaan.php */
/* Location: ./application/controllers/Pengadaan.php */