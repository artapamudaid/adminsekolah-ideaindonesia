<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Slip_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('slip/Slip_model', 'kredit/Kredit_model', 'payment/Payment_model', 'student/Student_model', 'period/Period_model', 'bulan/Bulan_model', 'employees/Employees_model', 'penggajian/Penggajian_model', 'setting/Setting_model', 'logs/Logs_model', 'ltrx/Log_trx_model'));

    $this->load->helper(array('send_helper'));
  }

  // payment view in list
  public function index($offset = NULL, $id = NULL)
  {

    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params   = array();
    $param    = array();
    $fee      = array();
    $month    = array();
    $logs     = array();
    // $employee = '';
    $employee['majors_id'] = '';
    $employee['employee_nip'] = '';
    $employee['majors_short_name'] = '';

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['period_id'] = $f['n'];
      $tahun = $f['n'];
      $nip = $f['r'];
      $bln = $f['d'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['employee_nip'] = $f['r'];
      $nip = $f['r'];
      $employee = $this->Employees_model->get(array('employee_nip' => $f['r']));
    }

    // tanggal
    if (isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $params['month_id'] = $f['d'];
      $bln = $f['d'];
    }

    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && isset($f['r']) && !empty($f['r']) && $f['r'] != '' && isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $data['dataMonth']  = $this->db->query("SELECT * FROM month")->result();
      $data['employee']   = $this->Employees_model->get(array('employee_id' => $employee['employee_id']));
      $gaji = $this->Penggajian_model->get(array('employee_nip' => $f['r']));

      foreach ($gaji as $row) {
        $x = $row['employee_id'];
      }



      $data['account']    = $this->db->query("SELECT * FROM account WHERE account_note = '7' AND account_category = '2' AND account_description LIKE '%gaji%' ")->result_array();
      $data['gaji']       = $this->Penggajian_model->get(array('id' => $x));
      $cek                = $this->db->query("SELECT COUNT(gaji_setting_id) AS cek_id FROM gaji_setting a JOIN employee b ON b.employee_id = a.gaji_setting_employee_id WHERE employee_id = '$x'")->row_array();


      $data['data_gaji']      = $this->Slip_model->get_slip_gaji(array('employee_id' => $x));
      $data['data_potongan']  = $this->Slip_model->get_slip_potongan(array('employee_id' => $x));
      $data['sumGaji']        = $this->db->query("SELECT SUM(gaji_setting_nominal) as sumGaji FROM gaji_setting WHERE gaji_setting_employee_id = '$x'")->row_array();
      $data['sumPotongan']        = $this->db->query("SELECT SUM(potongan_setting_nominal) as sumPotongan FROM potongan_setting WHERE potongan_setting_employee_id = '$x'")->row_array();

      $data['anak']           = $this->db->query("SELECT COUNT(fam_desc) as numAnak FROM employee_fam WHERE fam_desc = '3' AND fam_employee_id = '$x'")->row_array();
      $data['istri']          = $this->db->query("SELECT COUNT(fam_desc) as numIstri FROM employee_fam WHERE fam_desc = '1' AND fam_employee_id = '$x'")->row_array();

      $params['gaji_period_id']   = $f['n'];
      $params['gaji_employee_id'] = $x;

      $data['history'] = $this->Slip_model->get_history($params);
    }

    $majorsID = $employee['majors_id'];
    $config['base_url'] = site_url('manage/payment/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['periodActive'] = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
    $monthNow = date('n');
    if ($monthNow == '1') {
      $data['monthActive'] = '7';
    } else if ($monthNow == '2') {
      $data['monthActive'] = '8';
    } else if ($monthNow == '3') {
      $data['monthActive'] = '9';
    } else if ($monthNow == '4') {
      $data['monthActive'] = '10';
    } else if ($monthNow == '5') {
      $data['monthActive'] = '11';
    } else if ($monthNow == '6') {
      $data['monthActive'] = '12';
    } else if ($monthNow == '7') {
      $data['monthActive'] = '1';
    } else if ($monthNow == '8') {
      $data['monthActive'] = '2';
    } else if ($monthNow == '9') {
      $data['monthActive'] = '3';
    } else if ($monthNow == '10') {
      $data['monthActive'] = '4';
    } else if ($monthNow == '11') {
      $data['monthActive'] = '5';
    } else if ($monthNow == '12') {
      $data['monthActive'] = '6';
    }

    $data['period'] = $this->Period_model->get($params);
    $data['bulan'] = $this->Bulan_model->get_month($params);
    $data['dataKas'] = $this->db->query("SELECT * FROM account WHERE account_category = '2' AND account_majors_id = '$majorsID' AND account_code LIKE '1%' AND account_note IN (SELECT account_id FROM account WHERE account_category = '0' AND account_majors_id = '$majorsID' AND account_code LIKE '1%' AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) ORDER BY account_code ASC")->result_array();

    $data['dataKasActive'] = $this->db->query("SELECT account_id FROM account WHERE account_category = '2' AND account_majors_id = '$majorsID' AND account_code LIKE '1%' AND account_description LIKE '%Tunai%'")->row_array();

    $like   = $data['noref'] = 'GK' . $employee['majors_short_name'] . $employee['employee_nip'];
    $tmp    = $this->Slip_model->get_noref($like, $majorsID);
    $data['noref'] = 'GK' . $employee['majors_short_name'] . $employee['employee_nip'] . $tmp;
    $data['majorsID'] = $majorsID;

    //$data['majors'] = $this->Student_model->get_majors();

    $data['title'] = 'Penggajian Pegawai';
    $data['main'] = 'slip/slip_list';
    $this->load->view('manage/layout', $data);
  }

  function delete_history($id = NULL)
  {
    if ($_POST) {
      $period = $this->input->post('delPeriod');
      $month  = $this->input->post('delMonth');
      $nip    = $this->input->post('delNIP');
      $noref    = $this->input->post('noRef');

      //delete jurnal umum
      $id_jurnal = $this->db->query("SELECT id FROM jurnal_umum WHERE noref = '$noref'")->row_array();
      $this->db->where('id_jurnal', $id_jurnal['id'])->delete("jurnal_umum_detail");
      $this->db->where('id', $id_jurnal['id'])->delete("jurnal_umum");

      $this->Slip_model->delete_history($id, $noref);
      $this->session->set_flashdata('success', 'Hapus History Penggajian Berhasil');
      redirect('manage/slip?n=' . $period . '&d=' . $month . '&r=' . $nip);
    } elseif (!$_POST) {
      $this->session->set_flashdata('failed', 'Hapus History Penggajian Gagal');
      // redirect('manage/slip?n='.$period.'&d='.$month.'&r='.$nip);
    }
  }

  function add_slip()
  {
    if ($_POST == TRUE) {

      $params['gaji_pokok']        = $_POST['gaji'];
      $params['gaji_potongan']     = $_POST['potongan'];
      $params['gaji_jumlah']       = $_POST['pembulatan_gaji'];
      $params['gaji_catatan']      = $_POST['catatan_gaji'];
      $params['gaji_month_id']     = $_POST['month_id'];
      $params['gaji_period_id']    = $_POST['period_id'];
      $params['gaji_employee_id']  = $_POST['employee_id'];
      $params['gaji_tanggal']      = date('Y-m-d H:i:s');
      $params['user_user_id']      = $this->session->userdata('uid');

      $this->Slip_model->add($params);

      $query = $this->Employees_model->get(array('id' => $_POST['employee_id']));

      $month_id     = $_POST['month_id'];
      $period_id    = $_POST['period_id'];
      $employee_id  = $_POST['employee_id'];

      $q = $this->db->query("SELECT gaji_id FROM gaji WHERE gaji_month_id = '$month_id' 
                    AND gaji_period_id = '$period_id' 
                    AND gaji_employee_id = '$employee_id'")->row_array();

      $paramskredit['kredit_date']          = date('Y-m-d H:i:s');
      $paramskredit['kredit_value']         = $_POST['pembulatan_gaji'];
      $paramskredit['kredit_desc']          = 'Gaji ' . $query['position_name'] . ' ' . $query['employee_name'];
      $paramskredit['kredit_account_id']    = $_POST['gaji_account_id'];
      $paramskredit['kredit_gaji_id']       = $q['gaji_id'];
      $paramskredit['kredit_kas_account_id'] = $this->input->post('kas_account_id');
      $paramskredit['kredit_kas_noref']     = $_POST['kas_noref'];
      $paramskredit['kredit_input_date']    = date('Y-m-d H:i:s');
      $paramskredit['kredit_last_update']   = date('Y-m-d H:i:s');
      $paramskredit['user_user_id']         = $this->session->userdata('uid');

      $this->Kredit_model->add($paramskredit);

      $date = date('Y-m-d');
      $bulan = $this->db->query("SELECT MONTH('$date') as n")->row_array();

      if ($bulan['n'] == '1') {
        $id_bulan = '7';
      } else if ($bulan['n'] == '2') {
        $id_bulan = '8';
      } else if ($bulan['n'] == '3') {
        $id_bulan = '9';
      } else if ($bulan['n'] == '4') {
        $id_bulan = '10';
      } else if ($bulan['n'] == '5') {
        $id_bulan = '11';
      } else if ($bulan['n'] == '6') {
        $id_bulan = '12';
      } else if ($bulan['n'] == '7') {
        $id_bulan = '1';
      } else if ($bulan['n'] == '8') {
        $id_bulan = '2';
      } else if ($bulan['n'] == '9') {
        $id_bulan = '3';
      } else if ($bulan['n'] == '10') {
        $id_bulan = '4';
      } else if ($bulan['n'] == '11') {
        $id_bulan = '5';
      } else if ($bulan['n'] == '12') {
        $id_bulan = '6';
      }

      $paramskas['kas_majors_id'] = $this->input->post('kas_majors_id');
      $paramskas['kas_noref']     = $this->input->post('kas_noref');
      $paramskas['kas_period']    = $this->input->post('kas_period');
      $paramskas['kas_date']      = date('Y-m-d');
      $paramskas['kas_input_date'] = date('Y-m-d');
      $paramskas['kas_month_id']  = $id_bulan;
      $paramskas['kas_account_id'] = $this->input->post('kas_account_id');
      $paramskas['kas_note']      = 'Gaji ' . $query['position_name'] . ' ' . $query['employee_name'];
      $paramskas['kas_kredit']    = $_POST['jumlah_gaji'];
      $paramskas['kas_category']  = '2';
      $paramskas['kas_user_id']   = $user_id = $this->session->userdata('uid');

      $this->Slip_model->save($paramskas);

      $mid = $this->input->post('kas_majors_id');

      $this->db->query("DELETE a FROM kas a INNER JOIN kas b WHERE a.kas_id > b.kas_id AND a.kas_noref = b.kas_noref AND a.kas_majors_id = '" . $mid . "' AND DATE(a.kas_input_date)=CURDATE()");

      $jurnal_umum = array(
        'sekolah_id' => $this->input->post('kas_majors_id'),
        'noref'      => $this->input->post('kas_noref'),
        'keterangan' => 'Gaji ' . $query['position_name'] . ' ' . $query['employee_name'],
        'tanggal'    => date('Y-m-d'),
        'pencatatan' => 'auto',
        'waktu_update' => date('Y-m-d H:i:s'),
        'keterangan_lainnya' => '-'
      );

      $this->db->insert('jurnal_umum', $jurnal_umum);

      $jurum      = $this->db->query("SELECT MAX(id) AS last_id FROM jurnal_umum")->row_array();
      $lastJurum  = $jurum['last_id'];
      $noref      = $this->input->post('kas_noref');
      $kas = $this->db->query("SELECT account.account_code AS kas_akun,
                                  kas_kredit AS kas_value
                                  FROM kas
                                  JOIN account ON account.account_id = kas.kas_account_id
                                  WHERE kas_noref='$noref'
                                  GROUP BY kas_account_id")->row_array();

      $trxs = $this->db->query("SELECT account.account_code AS trx_akun,
                                  kredit_value AS trx_value
                                  FROM kredit 
                                  JOIN account ON account.account_id = kredit.kredit_account_id
                                  WHERE kredit_kas_noref='$noref'")->result_array();

      $dataKas = array(
        'id_jurnal' => $lastJurum,
        'account_code' => $kas['kas_akun'],
        'debet' => $kas['kas_value'],
        'kredit' => 0,
      );

      $this->db->insert('jurnal_umum_detail', $dataKas);
      foreach ($trxs as $trx) :
        $dataKas = array(
          'id_jurnal' => $lastJurum,
          'account_code' => $trx['trx_akun'],
          'debet' => 0,
          'kredit' => $trx['trx_value'],
        );

        $this->db->insert('jurnal_umum_detail', $dataKas);
      endforeach;

      // $employee_id           = $_POST['employee_id'];
      //add data gaji======================================================================================================
      $gaji_setting_id       = $this->input->post('gaji_setting_id');
      $name_gaji             = $this->input->post('name_gaji');
      $nominal_gaji          = $this->input->post('nominal_gaji');
      $gaji_id               = $q['gaji_id'];
      $count_gaji            = count($_POST['gaji_setting_id']);

      for ($i = 0; $i < $count_gaji; $i++) {
        $prm['gaji_slip_name']         = $name_gaji[$i];
        $prm['gaji_slip_setting_id']   = $gaji_setting_id[$i];
        $prm['gaji_slip_nominal']      = $nominal_gaji[$i];
        $prm['gaji_slip_gaji_id']      = $gaji_id;

        // var_dump($prm);

        $this->Slip_model->set_slip_gaji($prm);
        // echo $this->db->last_query().'<br>';
      }
      // exit;

      //add data  Sampai disini potongan========================================================================================================

      $potongan_setting_id       = $this->input->post('potongan_setting_id');
      $potongan_name             = $this->input->post('potongan_name');
      $potongan_nominal          = $this->input->post('potongan_nominal');
      $potongan_id                   = $q['gaji_id'];
      $count_potongan           = count($_POST['potongan_setting_id']);

      for ($i = 0; $i < $count_potongan; $i++) {
        $param['potongan_slip_name']         = $potongan_name[$i];
        $param['potongan_slip_setting_id']   = $potongan_setting_id[$i];
        $param['potongan_slip_nominal']      = $potongan_nominal[$i];
        $param['potongan_slip_gaji_id']      = $potongan_id;
        //simpan data setting potongan
        $this->Slip_model->set_slip_potongan($param);
      }

      $this->load->helper('notiftele');
      
      $rincian_gaji = [
          [
              'nama' => 'Gaji Bersih Diterima',
              'jumlah' => (int)$_POST['pembulatan_gaji']
          ]
      ];

      $nip = $query['employee_nip'] ?? '';
      $unit = $query['majors_short_name'] ?? ($query['majors_name'] ?? '');

      $kas_account_id = $this->input->post('kas_account_id');
      $acc_info = $this->db->query("SELECT account_code, account_description FROM account WHERE account_id = '$kas_account_id'")->row_array();
      $acc_str = $acc_info ? ' [' . $acc_info['account_code'] . ' ' . $acc_info['account_description'] . ']' : '';

      $noref_tele = 'PAY-' . date('YmdHis') . '-' . $employee_id;

      $payload_tele = [
          'topik'  => 'gaji',
          'pesan'  => 'Pembayaran Gaji untuk ' . $query['employee_name'] . $acc_str,
          'posisi' => 'kredit',
          'jumlah' => (int)$_POST['pembulatan_gaji'],
          'detail' => [
              'tanggal' => date('Y-m-d'),
              'no_ref'  => $noref_tele,
              'nama'    => $query['employee_name'],
              'nip'     => $nip,
              'unit'    => $unit,
              'jabatan' => $query['position_name'] ?? '',
              'rincian' => $rincian_gaji
          ]
      ];
      create_log($payload_tele);


      redirect('manage/slip/print_slip/' . $q['gaji_id']);
    }
  }

  function print_slip($id)
  {
    // ob_start();

    $print = $this->Slip_model->get_print($id);
    $data['print']          = $this->Slip_model->get_print($id);
    $data['print_gaji']     = $this->Slip_model->get_print_gaji($id);
    $data['print_potongan'] = $this->Slip_model->get_print_potongan($id);

    if ($print['month_id'] > 0 && $print['month_id'] < 7) {
      $tahun = $print['period_start'];
    } else if ($print['month_id'] > 6 && $print['month_id'] < 13) {
      $tahun = $print['period_end'];
    } else {
      $tahun = '?';
    }
    $this->barcode2($print['kredit_kas_noref'], '');
    /*
	    $tanggal = date_create($print['gaji_tanggal']);
        $dformat = date_format($tanggal,'dmYHis');
        $this->barcode2($dformat.$print['employee_nip'], '');
        */

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $this->load->library('html2pdflib');

    $filename = 'Slip Gaji Bulan ' . $print['month_name'] . ' (' . $print['position_name'] . ') ' . $print['employee_name'] . '.pdf';

    $html = $this->load->view('slip_print', $data, TRUE);

    $paper = array('216.5', '158');
    $orientation = 'L';
    $download = TRUE;

    $this->html2pdflib->generate($html, $filename, $download, $paper, $orientation);
  }

  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_fee/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0755, TRUE);
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
    $drawing->setFilename(FCPATH . 'media/barcode_fee/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  // pos view in list
  public function send_slip($offset = NULL)
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '' && $q['k'] != 'all') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
      $params['month_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['month']      = $this->Bulan_model->get_month($params);
    $data['employee']   = $this->Penggajian_model->get_report($params);

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Kirim Notif Gaji';
    $data['main'] = 'slip/slip_send_list';
    $this->load->view('manage/layout', $data);
  }

  function get_form()
  {

    for ($count = 0; $count < count($_POST["employee_id"]); $count++) {
      $employee = $this->db->query("SELECT employee_id
                                        FROM employee
                                        WHERE employee_id IN (" . $_POST['employee_id'][$count] . ")")->result_array();

      foreach ($employee as $row) {
        echo '<input type="hidden" name="employee_id[]" id="employee_id" value="' . $row['employee_id'] . '">';
      }
    }
  }

  public function send()
  {
    if ($_POST == TRUE) {

      $wa_center  = $this->Setting_model->get(array('id' => 17));
      $wa_key                = $this->Setting_model->get(array('name' => 'setting_wa_key'));
      $setting_whatsapp      = $this->Setting_model->get(array('id' => 92));
      $set_key = $wa_key['setting_value'];
      $set_wa  = $setting_whatsapp['setting_value'];
      $period_id      = $_POST['period_id'];
      $employee_id    = $_POST['employee_id'];
      $month_id       = $_POST['month_id'];

      $cpt = count($_POST['employee_id']);
      for ($i = 0; $i < $cpt; $i++) {

        $params['employee_id']  = $employee_id[$i];
        $params['period_id']    = $period_id;
        $params['month_id']     = $month_id;

        $bul  = $this->db->query("SELECT month_name FROM month WHERE month_id = '" . $_POST['month_id'] . "'")->row_array();

        $ta   = $this->db->query("SELECT period_start, period_end FROM period WHERE period_id = '" . $_POST['period_id'] . "'")->row_array();


        $query  = $this->Employees_model->get(array('id' => $employee_id[$i]));
        $employee   = $this->Penggajian_model->get_report_employee($params);

        if (isset($query['employee_phone']) and $query['employee_phone'] != '+62') {

          $school = $this->Setting_model->get(array('id' => SCHOOL_NAME));

          $total   = $employee['gaji_jumlah'];
          $gaji_id = $employee['gaji_id'];

          $no_wa = $query['employee_phone'];
          $psn = 'Assalamu alaikum Ustadz/Ustadzah ' . $query['employee_name'] . ' (' . $query['position_name'] . ') kami dari ' . $school['setting_value'] . ' mengucapkan terima kasih atas dedikasi dan kinerjanya Ustadz/Ustadzah. Maka dari itu kami serahkan hak Ustadz/Ustadzah di bulan ' . $bul['month_name'] . ' tahun ajaran ' . $ta['period_start'] . '/' . $ta['period_end'] . ' sebesar  Rp ' . number_format($total, 0, ",", ".") . '. Syukron wa jazakumullahu khoiran.' . "\n\n" .
            'Download Slip : ' . base_url() . 'slip/download?key=' . base64_encode($gaji_id) . "\n\n" .
            'WA Center Sekolah : http://wa.me/' . $wa_center['setting_value'];

          send_whatsapp($no_wa, $psn, $set_key, $set_wa);
        }

        if (isset($query['employee_idtele'])) {
          /*----------------------
              only basic POST method :
              -----------------------*/
          $telegram_id = $query['employee_idtele'];

          $psn = 'Assalamu alaikum Ustadz/Ustadzah ' . $query['employee_name'] . ' (' . $query['position_name'] . ') kami dari ' . $school['setting_value'] . ' mengucapkan terima kasih atas dedikasi dan kinerjanya Ustadz/Ustadzah. Maka dari itu kami serahkan hak Ustadz/Ustadzah di bulan ' . $bul['month_name'] . ' tahun ajaran ' . $ta['period_start'] . '/' . $ta['period_end'] . ' sebesar  Rp ' . number_format($total, 0, ",", ".") . '. Syukron wa jazakumullahu khoiran.' . "\n\n" .
            // 'Download Slip : ' . base_url() . 'slip/download?key=' . base64_encode($gaji_id) . "\n\n" .
            'WA Center Sekolah : http://wa.me/' . $wa_center['setting_value'];

          /*--------------------------------
              Kirim Bot telegram: 
              --------------------------------*/
          send_telegram($telegram_id, $psn);
        }
      }

      $this->session->set_flashdata('success', ' Notif Slip Gaji Berhasil Dikirim');
      redirect('manage/slip/send_slip');
    }
  }
}
