<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Bayarhutang_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('student/Student_model', 'settinghutang/Settinghutang_model', 'employees/Employees_model', 'bayarhutang/bayarhutang_model', 'period/Period_model', 'poshutang/Poshutang_model', 'bulan/Bulan_model', 'hutang/Hutang_model', 'hutang/Hutang_pay_model', 'setting/Setting_model', 'letter_hutang/Letter_hutang_model', 'logs/Logs_model', 'ltrx/Log_trx_model'));
  }

  // settinghutang view in list
  public function index($offset = NULL, $id = NULL)
  {
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f']      = $f;
    $majorsID       = '';
    $kreditur       = null;
    $periodID       = null;
    $params         = array();

    $data['dataHutang'] = $this->db->query("SELECT SUM(hutang_pay.hutang_pay_bill) as hutang_cicil, 
                                            `hutang_noref`, `hutang_date`, hutang_bill, `hutang_kreditur`, 
                                            `majors_short_name` 
                                            FROM `hutang` 
                                            LEFT JOIN `hutang_pay` ON `hutang_pay`.`hutang_pay_hutang_id` = `hutang`.`hutang_id` 
                                            LEFT JOIN `account` ON hutang.hutang_account_id = `account`.`account_id` 
                                            LEFT JOIN `majors` ON `majors`.`majors_id` = `account`.`account_majors_id` 
                                            GROUP BY hutang_noref 
                                            ORDER BY `hutang_id` DESC")->result_array();

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['hutang_noref']    = $f['r'];
      $kreditur       = $this->Hutang_model->get(array('hutang_noref' => $f['r']));

      $sumHutang      = $this->Hutang_model->get(array('hutang_noref' => $params['hutang_noref']));
      $sumHutangPay   = $this->Hutang_pay_model->get_sum(array('hutang_noref' => $params['hutang_noref']));

      $data['sumHutang']   = $sumHutang['hutang_bill'];

      $data['sumHutangPay']  = $sumHutangPay['hutang_dibayar'];

      $majorsID = $sumHutang['majors_id'];
    }

    if (!$kreditur) {
      $data['kreditur'] = null;
    } else {
      $data['kreditur'] = $this->Hutang_model->get(array('hutang_id' => $kreditur['hutang_id'],  'group' => TRUE));
    }

    if (!$kreditur) {
      $data['history'] = null;
    } else {
      $data['history']    = $this->Hutang_pay_model->get(array('hutang_id' => $kreditur['hutang_id']));
    }

    $data['hutang_pay']    = $this->Hutang_pay_model->get($params);

    $data['majors']     = $this->Settinghutang_model->get();

    $data['dataKas'] = $this->db->query("SELECT * FROM account WHERE account_category = '2' AND account_majors_id = '$majorsID' AND account_code LIKE '1%' AND account_note IN (SELECT account_id FROM account WHERE account_category = '0' AND account_majors_id = '$majorsID' AND account_code LIKE '1%' AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) ORDER BY account_code ASC")->result_array();

    $data['title']      = 'Hutang';
    $data['main']       = 'bayarhutang/bayarhutang_list';
    $this->load->view('manage/layout', $data);
  }

  function pay()
  {
    $hutang_pay_account_id  = $this->input->post('kas_account_id');
    $hutang_pay_hutang_id   = $this->input->post('hutang_pay_hutang_id');
    $hutang_account_id      = $this->input->post('hutang_account_id');
    $hutang_pay_date        = $this->input->post('hutang_pay_date');
    $hutang_pay_bill        = $this->input->post('hutang_pay_bill');
    $hutang_pay_note        = $this->input->post('hutang_pay_note');


    $lastletter     = $this->Letter_hutang_model->get(array('limit' => 1));
    $hutang         = $this->Hutang_model->get(array('id' => $hutang_pay_hutang_id));

    if ($lastletter['letter_hutang_year'] < date('Y') or count($lastletter) == 0) {
      $this->Letter_hutang_model->add(array('letter_hutang_number' => '00001', 'letter_hutang_month' => date('m'), 'letter_hutang_year' => date('Y')));
      $nomor    = sprintf('%05d', '00001');
      $noref   = date('Y') . date('m') . $nomor;
    } else {
      $nomor = sprintf('%05d', $lastletter['letter_hutang_number'] + 00001);
      $this->Letter_hutang_model->add(array('letter_hutang_number' => $nomor, 'letter_hutang_month' => date('m'), 'letter_hutang_year' => date('Y')));
      $noref = date('Y') . date('m') . $nomor;
    }

    $pay = array(
      'hutang_pay_hutang_id'    => $hutang_pay_hutang_id,
      'hutang_pay_noref'        => $noref,
      'hutang_pay_account_id'   => $hutang_pay_account_id,
      'hutang_pay_date'         => $hutang_pay_date,
      'hutang_pay_bill'         => $hutang_pay_bill,
      'hutang_pay_note'         => $hutang_pay_note,
      'hutang_pay_input_date'   => $hutang_pay_date,
      'hutang_pay_last_update'  => date('Y-m-d H:i:s'),
      'user_user_id'            => $this->session->userdata('uid')
    );

    $hutang_kreditur            = $hutang['hutang_kreditur'];
    $majors_id                  = $hutang['majors_id'];

    $this->db->trans_begin();

    $this->Hutang_pay_model->add($pay);

    $jurnal_umum = array(
      'sekolah_id'         => $majors_id,
      'noref'              => $noref,
      'keterangan'         => 'Bayar Hutang ' . $hutang_kreditur,
      'tanggal'            => $hutang_pay_date,
      'pencatatan'         => 'auto',
      'waktu_update'       => date('Y-m-d H:i:s'),
      'keterangan_lainnya' => '-'
    );

    $this->db->insert('jurnal_umum', $jurnal_umum);

    $jurum = $this->db->query("SELECT MAX(id) AS last_id FROM jurnal_umum")->row_array();
    $lastJurum = $jurum['last_id'];

    $kas = $this->db->query("SELECT account_code AS kas_akun
                          FROM account
                          WHERE account_id='$hutang_account_id'")->row_array();

    $dataKas = array(
      'id_jurnal'     => $lastJurum,
      'account_code'  => $kas['kas_akun'],
      'debet'         => $hutang_pay_bill,
      'kredit'        => 0,
    );

    $this->db->insert('jurnal_umum_detail', $dataKas);

    $trx = $this->db->query("SELECT account_code AS kas_akun
                                      FROM account
                                      WHERE account_id='$hutang_pay_account_id'")->row_array();

    $dataKasTrx = array(
      'id_jurnal'     => $lastJurum,
      'account_code'  => $trx['kas_akun'],
      'debet'         => 0,
      'kredit'        => $hutang_pay_bill,
    );

    $this->db->insert('jurnal_umum_detail', $dataKasTrx);


    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->session->set_flashdata('failed', ' Pembayaran Hutang gagal. Ulangi Lagi');
    } else {
      $this->db->trans_commit();
      $this->session->set_flashdata('success', ' Pembayaran Hutang berhasil');
    }

    redirect('manage/bayarhutang?r=' . $hutang['hutang_noref']);
  }

  function delete_pay($id)
  {
    $dataPay = $this->db->query(
      "SELECT hutang_noref,hutang_pay_noref 
        FROM hutang_pay
        JOIN hutang ON hutang.hutang_id = hutang_pay.hutang_pay_hutang_id
         WHERE hutang_pay_id = $id"
    )->row_array();
    $norefHutang = $dataPay['hutang_noref'];
    $norefPay = $dataPay['hutang_pay_noref'];

    if ($norefPay) {
      $this->db->query("DELETE FROM jurnal_umum WHERE noref = '$norefPay' ");
      $this->db->query("DELETE FROM hutang_pay WHERE hutang_pay_id = $id");
      $this->session->set_flashdata('success', ' Pembayaran berhasil dihapus');
    } else {
      $this->session->set_flashdata('failed', ' Pembayaran gagal dihapus');
    }

    redirect('manage/bayarhutang?r=' . $norefHutang);
  }

  function printBook()
  {
    $this->load->library('dompdflib');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $norefHutang = '';
    $kreditur = null;
    $periodID = null;
    $krediturID = null;
    $params = array();

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $norefHutang = $f['r'];
      $dataHutang = $this->db->query("SELECT SUM(hutang_pay.hutang_pay_bill) as hutang_pay_bill, 
                                            `hutang_noref`, `hutang_date`, hutang_bill, `hutang_kreditur`, 
                                            `majors_short_name` 
                                            FROM `hutang` 
                                            LEFT JOIN `hutang_pay` ON `hutang_pay`.`hutang_pay_hutang_id` = `hutang`.`hutang_id` 
                                            LEFT JOIN `account` ON hutang.hutang_account_id = `account`.`account_id` 
                                            LEFT JOIN `majors` ON `majors`.`majors_id` = `account`.`account_majors_id` 
                                            WHERE hutang_noref = '$norefHutang'")->row_array();
      // $kreditur = $this->Hutang_model->get(array('hutang_noref' => $f['r']));
      $krediturID = $kreditur['employee_id'];
      $data['hutang_noref']   = $dataHutang['hutang_noref'];
      $data['kreditur_name']  = $dataHutang['hutang_kreditur'];
      $data['hutang_date']    = $dataHutang['hutang_date'];
      $data['total_hutang']   = $dataHutang['hutang_bill'];
      $data['total_terbayar'] = $dataHutang['hutang_pay_bill'] ?? 0;
    }

    if (isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $date = $f['d'];
    }

    $data['hutang_pay'] = $this->Hutang_pay_model->get(array('hutang_noref' => $norefHutang));
    $data['period']     = $this->Period_model->get($params);
    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Settinghutang_model->get();

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $html = $this->load->view('bayarhutang/bayarhutang_book_pdf', $data, true);
    // $data = pdf_create($html, $kreditur['employee_full_name'], TRUE, 'A4', TRUE);

    $file_pdf = 'Buku_Pembayaran_Hutang_' . $data['kreditur']['hutang_kreditur'] . '_' . date('Y-m-d');

    $paper = 'A4';
    $orientation = "landscape";
    $this->dompdflib->generate($html, $file_pdf, $paper, $orientation);
  }

  function cetakBukti()
  {
    $this->load->library('dompdflib');
    $this->load->helper(array('tanggal', 'terbilang'));
    $this->load->model('employee/Employees_model');
    $this->load->model('setting/Setting_model');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $kreditur = null;
    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $noref = $f['r'];
    }

    if (isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $date = $f['d'];
    }

    $data['hutang_pay'] = $this->Hutang_pay_model->get(array('hutang_noref' => $noref, 'date' => $date));

    $data['hutang_paid'] = $this->Hutang_pay_model->get(array('hutang_noref' => $noref));

    foreach ($data['hutang_pay'] as $val) {
      $data['kreditur'] = array(
        'hutang_pay_noref'    => $val['hutang_pay_noref'],
        'hutang_pay_bill'     => $val['hutang_pay_bill'],
        'hutang_kreditur'     => $val['hutang_kreditur'],
        'hutang_noref'        => $val['hutang_noref'],
        'hutang_note'         => $val['hutang_note'],
        'hutang_bill'         => $val['hutang_bill'],
        'hutang_date'         => $val['hutang_date'],
        'account_description' => $val['account_description'],
        'petugas'             => $val['user_full_name'],
      );
    }

    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $this->barcode2($data['kreditur']['hutang_pay_noref'], '');

    $data['huruf'] = number_to_words($data['kreditur']['hutang_pay_bill']);

    $file_pdf = 'Cetak_Bukti_Bayar_Hutang_' . $data['kreditur']['hutang_kreditur'] . '_' . $data['kreditur']['hutang_pay_noref'] . '_' . date('Y-m-d');

    $paper = 'A5';

    $orientation = "landscape";
    $html = $this->load->view('bayarhutang/bayarhutang_cetak_pdf', $data, true);
    $this->dompdflib->generate($html, $file_pdf, $paper, $orientation);
  }

  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_hutang/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }
    $this->upload->initialize($config);

    // CREATE BARCODE GENERATOR
    // Including all required classes
    require_once(APPPATH . 'libraries/barcodegen/BCGFontFile.php');
    require_once(APPPATH . 'libraries/barcodegen/BCGColor.php');
    require_once(APPPATH . 'libraries/barcodegen/BCGDrawing.php');

    // Including the barcode technology
    // Ini bisa diganti-ganti mau yang 39, ato 128, dll, liat di folder barcodegen
    require_once(APPPATH . 'libraries/barcodegen/BCGcode39.barcode.php');

    // Loading Font
    // kalo mau ganti font, jangan lupa tambahin dulu ke folder font, baru loadnya di sini
    $font = new BCGFontFile(APPPATH . 'libraries/font/Arial.ttf', $fontsize);

    // Text apa yang mau dijadiin barcode, biasanya kode produk
    $text = $sparepart_code;

    // The arguments are R, G, B for color.
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $drawException = null;
    try {
      $code = new BCGcode39(); // kalo pake yg code39, klo yg lain mesti disesuaikan
      $code->setScale($scale); // Resolution
      $code->setThickness($thickness); // Thickness
      $code->setForegroundColor($color_black); // Color of bars
      $code->setBackgroundColor($color_white); // Color of spaces
      $code->setFont($font); // Font (or 0)
      $code->parse($text); // Text
    } catch (Exception $exception) {
      $drawException = $exception;
    }

    /* Here is the list of the arguments
        1 - Filename (empty : display on screen)
        2 - Background color */
    $drawing = new BCGDrawing('', $color_white);
    if ($drawException) {
      $drawing->drawException($drawException);
    } else {
      $drawing->setDPI($dpi);
      $drawing->setBarcode($code);
      $drawing->draw();
    }
    // ini cuma labeling dari sisi aplikasi saya, penamaan file menjadi png barcode.
    $filename_img_barcode = $sparepart_code . '_' . $barcode_type . '.png';
    // folder untuk menyimpan barcode
    $drawing->setFilename(FCPATH . 'media/barcode_hutang/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  public function report()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['employee_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Employees_model->get_class($params);
    $data['majors']     = $this->Settinghutang_model->get($params);
    $data['employee']    = $this->Employees_model->get($params);
    $data['banking']    = $this->Hutang_model->get_sum($params);

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Laporan Tabungan Siswa';
    $data['main'] = 'bayarhutang/bayarhutang_report';
    $this->load->view('manage/layout', $data);
  }

  public function banking_excel()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['employee_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Employees_model->get_class($params);
    $data['majors']     = $this->Settinghutang_model->get($params);
    $data['employee']    = $this->Employees_model->get($params);
    $data['hutang']    = $this->Hutang_model->get($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $this->load->view('bayarhutang/bayarhutang_xls', $data);
  }
}
