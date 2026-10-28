<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('login'));
		}

		//load model
		$this->load->model('setting/Setting_model');
		$this->load->model('ModelLaporan', 'ml');
		$this->load->helper('terbilang');
	}

	public function aset()
	{
		$data = array(
			'title' => 'Laporan Aset',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_ast' => 'active',
			'lokasi' => $this->ml->getLokasi()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('laporan/v_laporan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Laporan Aset';
		$data['main']           = 'easet/laporan/r_aset';
		$this->load->view('manage/layout', $data);
	}

	public function searchAset()
	{
		$id_lokasi = $this->input->post('id_lokasi');
		$tahun_perolehan = $this->input->post('tahun_perolehan');

		$data = array(
			'title' => 'Laporan Data Aset',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_ast' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'lok' => $this->ml->getLokasiId($id_lokasi),
			'aset' => $this->ml->getAsetWujud($id_lokasi, $tahun_perolehan)
		);

		if (count($data['aset']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('laporan/r_aset', $data);
			// $this->load->view('layouts/footer');
			$data['title']          = 'Laporan Aset';
			$data['main']           = 'easet/laporan/r_aset';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('laporan/aset');
		}
	}

	public function printAset($id_lokasi, $tahun_perolehan)
	{
		$this->load->library('Dompdflib');

		$id_lokasi = $this->uri->segment(5);
		$tahun_perolehan = $this->uri->segment(6);

		$data['aset'] = $this->ml->getAsetWujud($id_lokasi, $tahun_perolehan);
		$data['lokasi'] = $this->ml->getLokasiId($id_lokasi);

		$data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$data['nama_kepsek'] = $this->Setting_model->get(array('id' => 12));
		$data['nip_kepsek'] = $this->Setting_model->get(array('id' => 11));

		$data['nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['nip_bendahara'] = $this->Setting_model->get(array('id' => 15));

		$file_pdf = 'Laporan Data Aset.pdf';
		$paper = 'A4';

		$orientation = "potrait";
		$html = $this->load->view('easet/laporan/p_aset', $data, TRUE);

		$this->dompdflib->generate($html, $file_pdf, $paper, $orientation, TRUE);
	}

	public function exportAset($id_lokasi, $tahun_perolehan)
	{
		$id_lokasi = $this->uri->segment(5);
		$tahun_perolehan = $this->uri->segment(6);

		$aset = $this->ml->getAsetWujudExcel($id_lokasi, $tahun_perolehan);

		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'NO')
			->setCellValue('B1', 'NAMA')
			->setCellValue('C1', 'VOLUME')
			->setCellValue('D1', 'SATUAN')
			->setCellValue('E1', 'HARGA (Rp.)')
			->setCellValue('F1', 'JUMLAH (Rp.)');

		$kolom = 2;
		$nomor = 1;

		foreach ($aset as $item) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue("A{$kolom}", $nomor)
				->setCellValue("B{$kolom}", $item->nama_barang)
				->setCellValue("C{$kolom}", $item->volume)
				->setCellValue("D{$kolom}", $item->satuan)
				->setCellValue("E{$kolom}", $item->harga)
				->setCellValue("F{$kolom}", ($item->volume * $item->harga));

			$kolom++;
			$nomor++;
		}

		// ===== Tambahan tanda tangan =====
		$lastRow = $kolom + 2;

		$setting_city	= $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$kota 		    = $setting_city['setting_value'];
		$nama_kepsek 	= $this->Setting_model->get(array('id' => 12));
		$nip_kepsek     = $this->Setting_model->get(array('id' => 11));

		// Kolom kiri
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue("A" . ($lastRow + 1), 'Kepala Sekolah');
		$sheet->setCellValue("A" . ($lastRow + 7), $nama_kepsek['setting_value'] ?? '');
		$sheet->setCellValue("A" . ($lastRow + 8), 'NIP. ' . ($nip_kepsek['setting_value'] ?? ''));

		// Kolom kanan
		$sheet->setCellValue("E{$lastRow}", "{$kota}, " . tgl_indo(date('Y-m-d')));
		$sheet->setCellValue("E" . ($lastRow + 1), 'Mengetahui,');
		$sheet->setCellValue("E" . ($lastRow + 2), 'Waka Sarpras');
		$sheet->setCellValue("E" . ($lastRow + 7), 'Sarino, M.M');
		$sheet->setCellValue("E" . ($lastRow + 8), 'NIP. 35783412742');

		// Export
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Data Aset.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}


	public function penghapusan()
	{
		$data = array(
			'title' => 'Laporan Aset',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_php' => 'active',
			'lokasi' => $this->ml->getLokasi()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('laporan/v_laporan_ph', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Laporan Pengadaan';
		$data['main']           = 'easet/laporan/v_laporan_ph';
		$this->load->view('manage/layout', $data);
	}

	public function searchPenghapusan()
	{
		$id_lokasi = $this->input->post('id_lokasi');
		$tahun_perolehan = $this->input->post('tahun_perolehan');

		$data = array(
			'title' => 'Laporan Data Aset',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_php' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'lok' => $this->ml->getLokasiId($id_lokasi),
			'aset' => $this->ml->getAsetDihapuskan($id_lokasi, $tahun_perolehan)
		);

		if (count($data['aset']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('laporan/r_penghapusan', $data);
			// $this->load->view('layouts/footer');
			$data['title']          = 'Laporan Pengadaan';
			$data['main']           = 'easet/laporan/r_penghapusan';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('manage/easet/laporan/aset');
		}
	}

	public function printPenghapusan($id_lokasi, $tahun_perolehan)
	{
		$this->load->library('Dompdflib');

		$id_lokasi = $this->uri->segment(5);
		$tahun_perolehan = $this->uri->segment(6);

		$data['aset'] = $this->ml->getAsetDihapuskan($id_lokasi, $tahun_perolehan);
		$data['lokasi'] = $this->ml->getLokasiId($id_lokasi);

		if (count($data['aset']) > 0) {
			$data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
			$data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
			$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
			$data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
			$data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

			$data['nama_kepsek'] = $this->Setting_model->get(array('id' => 12));
			$data['nip_kepsek'] = $this->Setting_model->get(array('id' => 11));

			$data['nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
			$data['nip_bendahara'] = $this->Setting_model->get(array('id' => 15));

			$file_pdf = 'Laporan Penghapusan Aset.pdf';
			$paper = 'A4';

			$orientation = "potrait";
			$html = $this->load->view('easet/laporan/p_penghapusan', $data, TRUE);

			$this->dompdflib->generate($html, $file_pdf, $paper, $orientation, TRUE);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('manage/easet/laporan/penghapusan');
		}
	}

	public function exportPenghapusan($id_lokasi, $tahun_perolehan)
	{
		$id_lokasi = $this->uri->segment(5);
		$tahun_perolehan = $this->uri->segment(6);

		$aset = $this->ml->getAsetDihapuskanExcel($id_lokasi, $tahun_perolehan);

		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'NO')
			->setCellValue('B1', 'NAMA')
			->setCellValue('C1', 'VOLUME')
			->setCellValue('D1', 'SATUAN')
			->setCellValue('E1', 'HARGA (Rp.)')
			->setCellValue('F1', 'JUMLAH (Rp.)');

		$kolom = 2;
		$nomor = 1;
		foreach ($aset as $item) {

			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $nomor)
				->setCellValue('B' . $kolom, $item->nama_barang)
				->setCellValue('C' . $kolom, $item->jumlah)
				->setCellValue('D' . $kolom, $item->satuan)
				->setCellValue('E' . $kolom, $item->harga)
				->setCellValue('F' . $kolom, ($item->jumlah * $item->harga));

			$kolom++;
			$nomor++;
		}

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Data Aset Dihapuskan.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function qrcodeAset()
	{
		$data = array(
			'title' => 'Laporan Aset',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_qr' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'tahun_perolehan' => $this->ml->getTahunPerolehan()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('laporan/v_qrcode', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'QR Code Aset';
		$data['main']           = 'easet/laporan/v_qrcode';
		$this->load->view('manage/layout', $data);
	}

	public function printQrcode()
	{
		$id_lokasi = $this->input->post('id_lokasi');
		$tahun_perolehan = $this->input->post('tahun_perolehan');

		$data['aset'] = $this->ml->getAsetQr($id_lokasi, $tahun_perolehan);
		$data['lokasi'] = $this->ml->getLokasiId($id_lokasi);
		$lokasi = $data['lokasi'];
		$namaLokasi = $lokasi['nama_lokasi'];

		if (count($data['aset']) > 0) {
			$this->load->library('dompdflib');
			$filename = 'QR Code Aset-' . $namaLokasi;
			$paper = 'A4';
			$orientation = "potrait";
			$html = $this->load->view('easet/laporan/p_qrcode', $data, TRUE);
			$this->dompdflib->download($html, $filename, $paper, $orientation);
		} else {
			$this->session->set_flashdata('failed', 'Data Aset Tidak Ditemukan');
			redirect('manage/easet/laporan/qrcodeAset');
		}
	}

	public function pengadaan()
	{
		$data = array(
			'title' => 'Laporan Aset',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_lpnd' => 'active',
			'lokasi' => $this->ml->getLokasi()
		);
		// $this->load->view('layouts/header', $data);
		// $this->load->view('laporan/v_pengadaan', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Laporan Pengadaan';
		$data['main']           = 'easet/laporan/v_pengadaan';
		$this->load->view('manage/layout', $data);
	}

	public function searchPengadaan()
	{
		$id_lokasi = $this->input->post('id_lokasi');
		$tahun_pengadaan = $this->input->post('tahun_pengadaan');

		$data = array(
			'title' => 'Laporan Pengadaan',
			'active_menu_lp' => 'menu-open',
			'active_menu_lpr' => 'active',
			'active_menu_lpnd' => 'active',
			'lokasi' => $this->ml->getLokasi(),
			'lok' => $this->ml->getLokasiId($id_lokasi),
			'pnd' => $this->ml->getPengadaan($id_lokasi, $tahun_pengadaan)
		);

		if (count($data['pnd']) > 0) {
			// $this->load->view('layouts/header', $data);
			// $this->load->view('laporan/r_pengadaan', $data);
			// $this->load->view('layouts/footer');

			$data['title']          = 'Laporan Pengadaan';
			$data['main']           = 'easet/laporan/r_pengadaan';
			$this->load->view('manage/layout', $data);
		} else {
			$this->session->set_flashdata('gagal', 'Ditemukan');
			redirect('manage/easet/laporan/pengadaan');
		}
	}

	public function printPengadaan($id_lokasi, $tahun_pengadaan)
	{
		$this->load->library('Dompdflib');

		$id_lokasi = $this->uri->segment(5);
		$tahun_pengadaan = $this->uri->segment(6);

		$data['pnd'] = $this->ml->getPengadaan($id_lokasi, $tahun_pengadaan);
		$data['lokasi'] = $this->ml->getLokasiId($id_lokasi);

		$data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$data['nama_kepsek'] = $this->Setting_model->get(array('id' => 12));
		$data['nip_kepsek'] = $this->Setting_model->get(array('id' => 11));

		$data['nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['nip_bendahara'] = $this->Setting_model->get(array('id' => 15));

		$file_pdf = 'Laporan Pengadaan Aset.pdf';
		$paper = 'A4';

		$orientation = "potrait";
		$html = $this->load->view('easet/laporan/p_pengadaan', $data, TRUE);

		$this->dompdflib->generate($html, $file_pdf, $paper, $orientation, TRUE);
	}

	public function exportPengadaan($id_lokasi, $tahun_pengadaan)
	{
		$id_lokasi = $this->uri->segment(5);
		$tahun_pengadaan = $this->uri->segment(6);

		$pnd = $this->ml->getPengadaanExcel($id_lokasi, $tahun_pengadaan);

		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'NO')
			->setCellValue('B1', 'NAMA')
			->setCellValue('C1', 'VOLUME')
			->setCellValue('D1', 'SATUAN')
			->setCellValue('E1', 'HARGA (Rp.)')
			->setCellValue('F1', 'JUMLAH (Rp.)');

		$kolom = 2;
		$nomor = 1;
		foreach ($pnd as $item) {

			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $nomor)
				->setCellValue('B' . $kolom, $item->nama_aset)
				->setCellValue('C' . $kolom, $item->volume)
				->setCellValue('D' . $kolom, $item->satuan)
				->setCellValue('E' . $kolom, $item->harga_satuan)
				->setCellValue('F' . $kolom, ($item->volume * $item->harga_satuan));

			$kolom++;
			$nomor++;
		}

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Pengadaan Aset.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}

/* End of file Laporan.php */
/* Location: ./application/controllers/Laporan.php */