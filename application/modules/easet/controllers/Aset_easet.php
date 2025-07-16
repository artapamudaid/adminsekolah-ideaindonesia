<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aset_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('manage/auth/login'));
		}

		//load model
		$this->load->model('ModelAset', 'ma');
		$this->load->model('ModelBarang', 'mb');
		$this->load->model('ModelLokasi', 'ml');
		$this->load->model('ModelKategori', 'mk');

		//load library
		$this->load->library('ciqrcode');
		$this->load->library('uuid');
		$this->load->library('form_validation');
		$this->load->helper('terbilang');
	}

	public function index()
	{
		$data = array(
			'title' => 'Aset Berwujud',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_wujud' => 'active',
			'aset' => $this->ma->getAsetWujud(),
			'kategori' => $this->mk->getKategoriBarang()
		);

		$data['title']          = 'Aset Berwujud';
		$data['main']           = 'easet/aset/v_wujud';
		$this->load->view('manage/layout', $data);
	}

	public function tambahAset()
	{
		$data = array(
			'title' => 'Aset Berwujud',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_wujud' => 'active',
			'aset' => $this->ma->getAsetWujud(),
			'brg' => $this->mb->getDataBarang(),
			'lokasi' => $this->ml->getLokasi(),
			'majors' => $this->ma->getMajors(),
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('aset/c_wujud', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Tambah Aset Berwujud';
		$data['main']           = 'easet/aset/c_wujud';
		$this->load->view('manage/layout', $data);
	}

	public function getListAkunAset()
	{
		$bar = $this->input->get('bar');
		$majors = $this->input->get('unit');
		$query = $this->ma->getListAkunAset($majors, $bar);

		echo json_encode($query);
	}

	public function getListKas()
	{
		$bar = $this->input->get('bar');
		$majors = $this->input->get('unit');
		$query = $this->ma->getListKas($majors, $bar);

		echo json_encode($query);
	}

	public function simpanAset()
	{
		$this->form_validation->set_rules(
			'kode_aset',
			'Kode Aset',
			'required|trim|is_unique[asets.kode_aset]',
			array(
				'required' => "<p>Username tidak boleh kosong</p>",
				'is_unique' => "<p>Kode Aset sudah digunakan</p>",
			)
		);

		if ($this->form_validation->run() != FALSE) {
			$majors_id = $this->input->post('majors_id');
			$generate = $this->input->post('generate');
			if ($generate) {

				$kode_aset = $this->input->post('kode_aset');

				$config['cacheable']    = true; //boolean, the default is true
				$config['cachedir']     = './easet/src/'; //string, the default is application/cache/
				$config['errorlog']     = './easet/src/'; //string, the default is application/logs/
				$config['imagedir']     = './easet/src/img/qrcode/'; //direktori penyimpanan qr code
				$config['quality']      = true; //boolean, the default is true
				$config['size']         = '1024'; //interger, the default is 1024
				$config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
				$config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
				$this->ciqrcode->initialize($config);

				$id = $this->uuid->v4();
				$image = str_replace('-', '', $id);

				$id_as = $this->uuid->v4();
				$id_aset = str_replace('-', '', $id_as);

				$image_name = $image . '.png'; //buat name dari qr code sesuai dengan nim

				$url = 'http://urlkamu.com/' . $id_aset;

				$params['data'] = $url; //data yang akan di jadikan QR CODE
				$params['level'] = 'H'; //H=High
				$params['size'] = 10;
				$params['savename'] = FCPATH . $config['imagedir'] . $image_name;
				$this->ciqrcode->generate($params);

				$volume = $this->input->post('volume');
				$harga = $this->input->post('harga');
				$total = ($volume * $harga);

				$idBarang = $this->input->post('id_barang');
				$idKas = $this->input->post('akun_kas');
				$akunBiaya = $this->db->query(
					"SELECT kb.akun_id, ac.account_code
					 FROM barang br
					 JOIN kategori_barang kb ON kb.id_kategori = br.id_kategori
					 JOIN account ac ON ac.account_id = kb.akun_id
					 WHERE br.id_barang = $idBarang"
				)->row();
				$kodeBiaya = $akunBiaya->account_code;

				$data = array(
					'id_aset' => $id_aset,
					'kode_aset' => $kode_aset,
					'id_barang' => $idBarang,
					'id_lokasi' => $this->input->post('id_lokasi'),
					'kas_account_id' => $this->input->post('akun_kas'),
					'volume' => $volume,
					'satuan' => $this->input->post('satuan'),
					'harga' => $harga,
					'total_harga' => $total,
					'status_aset' => 'Aktif',
					'kondisi' => $this->input->post('kondisi'),
					'umur_ekonomis' => $this->input->post('umur_ekonomis'),
					'jenis_bantuan' => $this->input->post('jenis_bantuan'),
					'qr_code' => $image_name
				);

				echo 'Akun Biaya = ' . $akunBiaya->akun_id . '<br>';
				echo json_encode($data);
				exit;

				$result = $this->ma->storeAset($data);

				if ($result >= 1) {
					$this->session->set_flashdata('sukses', 'Disimpan');
					redirect('manage/easet/aset');
				} else {
					$this->session->set_flashdata('gagal', 'Disimpan');
					redirect('manage/easet/aset/tambahAset');
				}
			} else {

				$id = $this->uuid->v4();
				$id_aset = str_replace('-', '', $id);

				$volume = $this->input->post('volume');
				$harga = $this->input->post('harga');
				$total = ($volume * $harga);

				$idBarang = $this->input->post('id_barang');
				$idKas = $this->input->post('akun_kas');
				$akunBiaya = $this->db->query(
					"SELECT kb.akun_id, ac.account_code
					 FROM barang br
					 JOIN kategori_barang kb ON kb.id_kategori = br.id_kategori
					 JOIN account ac ON ac.account_id = kb.akun_id
					 WHERE br.id_barang = $idBarang"
				)->row();
				$kodeBiaya = $akunBiaya->account_code;

				$data = array(
					'id_aset' => $id_aset,
					'kode_aset' => $this->input->post('kode_aset'),
					'id_barang' => $idBarang,
					'id_lokasi' => $this->input->post('id_lokasi'),
					'kas_account_id' => $idKas,
					'volume' => $volume,
					'satuan' => $this->input->post('satuan'),
					'harga' => $harga,
					'total_harga' => $total,
					'status_aset' => 'Aktif',
					'kondisi' => $this->input->post('kondisi'),
					'umur_ekonomis' => $this->input->post('umur_ekonomis'),
					'jenis_bantuan' => $this->input->post('jenis_bantuan')
				);

				// echo 'Akun Biaya = ' . $kodeBiaya . '<br>';
				// echo json_encode($data);
				// $this->insertJurnal($majors_id, $id_aset, $idKas, $kodeBiaya, $harga);
				// exit;

				$result = $this->ma->storeAset($data);
				$this->insertJurnal($majors_id, $id_aset, $idKas, $kodeBiaya, $harga);

				if ($result >= 1) {
					$this->session->set_flashdata('sukses', 'Disimpan');
					redirect('manage/easet/aset');
				} else {
					$this->session->set_flashdata('gagal', 'Disimpan');
					redirect('manage/easet/aset/tambahAset');
				}
			}
		} else {
			$data = array(
				'title' => 'Aset Berwujud',
				'active_menu_open' => 'menu-open',
				'active_menu_aset' => 'active',
				'active_menu_wujud' => 'active',
				'aset' => $this->ma->getAsetWujud(),
				'brg' => $this->mb->getDataBarang(),
				'lokasi' => $this->ml->getLokasi()
			);
			// $this->load->view('layouts/header', $data);
			// $this->load->view('aset/c_wujud', $data);
			// $this->load->view('layouts/footer');

			$data['title']          = 'Tambah Aset Berwujud';
			$data['main']           = 'easet/aset/c_wujud';
			$this->load->view('manage/layout', $data);
		}
	}

	function insertJurnal($majors, $noref, $idKas, $kodeBiaya, $nominal)
	{
		$jurnal_umum = array(
			'sekolah_id' => $majors,
			'noref'       => $noref,
			'keterangan' => 'Penambahan Aset',
			'tanggal'    => date('Y-m-d'),
			'pencatatan' => 'auto',
			'waktu_update' => date('Y-m-d H:i:s'),
			'keterangan_lainnya' => 'ASET'
		);

		$this->db->insert('jurnal_umum', $jurnal_umum);

		$jurum = $this->db->query("SELECT MAX(id) AS last_id FROM jurnal_umum")->row_array();
		$lastJurum = $jurum['last_id'];

		$kas = $this->db->query("SELECT account_code AS kode_kas
									FROM account
									WHERE account_id='$idKas'")->row_array();

		$dataDebet = array(
			'id_jurnal' => $lastJurum,
			'account_code' => $kodeBiaya,
			'debet' => $nominal,
			'kredit' => 0,
		);

		// echo json_encode($dataDebet);

		$this->db->insert('jurnal_umum_detail', $dataDebet);

		$dataKredit = array(
			'id_jurnal' => $lastJurum,
			'account_code' => $kas['kode_kas'],
			'debet' => 0,
			'kredit' => $nominal,
		);

		// echo json_encode($dataKredit);
		$this->db->insert('jurnal_umum_detail', $dataKredit);
	}

	public function editAset($id_aset)
	{
		$id_aset = $this->uri->segment(5);

		$data = array(
			'title' => 'Aset Berwujud',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_wujud' => 'active',
			'aset' => $this->ma->getDetailAsetWujud($id_aset),
			'brg' => $this->mb->getDataBarang(),
			'lokasi' => $this->ml->getLokasi()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('aset/u_wujud', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Edit Aset Berwujud';
		$data['main']           = 'easet/aset/u_wujud';
		$this->load->view('manage/layout', $data);
	}

	public function ubahAset()
	{
		$this->form_validation->set_rules(
			'kode_aset',
			'Kode Aset',
			'required|trim',
			array(
				'required' => "<p>Kode Aset tidak boleh kosong</p>"
			)
		);

		$id_aset = $this->input->post('id_aset');

		if ($this->form_validation->run() != FALSE) {
			$generate = $this->input->post('generate');
			if ($generate) {

				$kode_aset = $this->input->post('kode_aset');

				$config['cacheable']    = true; //boolean, the default is true
				$config['cachedir']     = './easet/src/'; //string, the default is application/cache/
				$config['errorlog']     = './easet/src/'; //string, the default is application/logs/
				$config['imagedir']     = './easet/src/img/qrcode/'; //direktori penyimpanan qr code
				$config['quality']      = true; //boolean, the default is true
				$config['size']         = '1024'; //interger, the default is 1024
				$config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
				$config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
				$this->ciqrcode->initialize($config);

				$id = $this->uuid->v4();
				$image = str_replace('-', '', $id);

				$image_name = $image . '.png'; //buat name dari qr code sesuai dengan nim

				$url = 'http://urlkamu.com/' . $id_aset;

				$params['data'] = $url; //data yang akan di jadikan QR CODE
				$params['level'] = 'H'; //H=High
				$params['size'] = 10;
				$params['savename'] = FCPATH . $config['imagedir'] . $image_name;
				$this->ciqrcode->generate($params);

				$volume = $this->input->post('volume');
				$harga = $this->input->post('harga');
				$total = ($volume * $harga);

				$data = array(
					'kode_aset' => $kode_aset,
					'id_barang' => $this->input->post('id_barang'),
					'id_lokasi' => $this->input->post('id_lokasi'),
					'volume' => $volume,
					'satuan' => $this->input->post('satuan'),
					'harga' => $harga,
					'total_harga' => $total,
					'status_aset' => 'Aktif',
					'kondisi' => $this->input->post('kondisi'),
					'umur_ekonomis' => $this->input->post('umur_ekonomis'),
					'jenis_bantuan' => $this->input->post('jenis_bantuan'),
					'qr_code' => $image_name
				);

				unset($data['id_aset']);

				$result = $this->ma->updateAset($id_aset, $data);

				if ($result >= 1) {
					$this->session->set_flashdata('sukses', 'Diubah');
					redirect('manage/easet/aset');
				} else {
					$this->session->set_flashdata('gagal', 'Diubah');
					redirect('manage/easet/aset/editAset' . $id_aset);
				}
			} else {

				$this->db->where('id_aset', $id_aset);
				$get_image_file = $this->db->get('asets')->row();
				@unlink('easet/src/img/qrcode/' . $get_image_file->qr_code);

				$volume = $this->input->post('volume');
				$harga = $this->input->post('harga');
				$total = ($volume * $harga);

				$data = array(
					'kode_aset' => $this->input->post('kode_aset'),
					'id_barang' => $this->input->post('id_barang'),
					'id_lokasi' => $this->input->post('id_lokasi'),
					'volume' => $volume,
					'satuan' => $this->input->post('satuan'),
					'harga' => $harga,
					'total_harga' => $total,
					'status_aset' => 'Aktif',
					'kondisi' => $this->input->post('kondisi'),
					'umur_ekonomis' => $this->input->post('umur_ekonomis'),
					'jenis_bantuan' => $this->input->post('jenis_bantuan'),
					'qr_code' => NULL
				);

				unset($data['id_aset']);

				$result = $this->ma->updateAset($id_aset, $data);

				if ($result >= 1) {
					$this->session->set_flashdata('sukses', 'Disimpan');
					redirect('manage/easet/aset');
				} else {
					$this->session->set_flashdata('gagal', 'Disimpan');
					redirect('manage/easet/aset/editAset' . $id_aset);
				}
			}
		} else {
			$data = array(
				'title' => 'Aset Berwujud',
				'active_menu_open' => 'menu-open',
				'active_menu_aset' => 'active',
				'active_menu_wujud' => 'active',
				'aset' => $this->ma->getDetailAsetWujud($id_aset),
				'brg' => $this->mb->getDataBarang(),
				'lokasi' => $this->ml->getLokasi()
			);
			// $this->load->view('layouts/header', $data);
			// $this->load->view('aset/u_wujud', $data);
			// $this->load->view('layouts/footer');

			$data['title']          = 'Edit Aset Berwujud';
			$data['main']           = 'easet/aset/u_wujud';
			$this->load->view('manage/layout', $data);
		}
	}

	public function detailAset($id_aset)
	{
		$id_aset = $this->uri->segment(5);
		$data = array(
			'title' => 'Aset Berwujud',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_wujud' => 'active',
			'aset' => $this->ma->getDetailAsetWujud($id_aset)
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('aset/d_wujud', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Detail Aset Berwujud';
		$data['main']           = 'easet/aset/d_wujud';
		$this->load->view('manage/layout', $data);
	}

	public function hapusAset($id_aset)
	{
		$id_aset = $this->uri->segment(5);

		$this->db->where('id_aset', $id_aset);
		$get_image_file = $this->db->get('asets')->row();
		@unlink('easet/src/img/qrcode/' . $get_image_file->qr_code);

		$this->db->where('id_aset', $id_aset);
		$this->db->delete('asets');
		$this->session->set_flashdata('sukses', 'Dihapus');
		redirect('manage/easet/aset');
	}

	public function filterAset()
	{
		$id_kategori = $this->input->post('id_kategori');
		$tahun_perolehan = $this->input->post('tahun_perolehan');
		$kondisi = $this->input->post('kondisi');

		$data = array(
			'title' => 'Aset Berwujud',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_wujud' => 'active',
			'aset' => $this->ma->getFilterAsetWujud($id_kategori, $tahun_perolehan, $kondisi),
			'kategori' => $this->mk->getKategoriBarang()
		);
		if (count($data['aset']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('aset/v_wujud', $data);
			// $this->load->view('layouts/footer');

			$data['title']          = 'Tambah Aset Berwujud';
			$data['main']           = 'easet/aset/v_wujud';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('manage/easet/aset');
		}
	}

	public function asetDihapuskan()
	{
		$data = array(
			'title' => 'Aset Dihapuskan',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_hapuskan' => 'active',
			'kategori' => $this->mk->getKategoriBarang(),
			'aset' => $this->ma->getAsetDihapuskan()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('aset/v_dihapuskan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Aset Dihapuskan';
		$data['main']           = 'easet/aset/v_dihapuskan';
		$this->load->view('manage/layout', $data);
	}

	public function detailDihapuskanAset($id_aset)
	{
		$id_aset = $this->uri->segment(5);
		$data = array(
			'title' => 'Aset Berwujud',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_hapuskan' => 'active',
			'aset' => $this->ma->getDetailAsetWujud($id_aset)
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('aset/d_dihapuskan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Detail Aset Dihapuskan';
		$data['main']           = 'easet/aset/d_dihapuskan';
		$this->load->view('manage/layout', $data);
	}

	public function filterAsetDihapuskan()
	{
		$id_kategori = $this->input->post('id_kategori');
		$tgl_penghapusan = $this->input->post('tgl_penghapusan');

		$data = array(
			'title' => 'Aset Dihapuskan',
			'active_menu_open' => 'menu-open',
			'active_menu_aset' => 'active',
			'active_menu_hapuskan' => 'active',
			'kategori' => $this->mk->getKategoriBarang(),
			'aset' => $this->ma->getFilterAsetDihapuskan($id_kategori, $tgl_penghapusan)
		);
		if (count($data['aset']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('aset/v_dihapuskan', $data);
			// $this->load->view('layouts/footer');

			$data['title']          = 'Aset Dihapuskan';
			$data['main']           = 'easet/aset/v_dihapuskan';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('manage/easet/aset/asetDihapuskan');
		}
	}

	public function cariAset()
	{
		$bar = $this->input->get('bar');
		$query = $this->ma->searchAset($bar, 'nama_barang');

		echo json_encode($query);
	}
}

/* End of file Aset.php */
/* Location: ./application/controllers/Aset.php */