<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Banking_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('payment/Payment_model', 'student/Student_model', 'banking/Banking_model', 'period/Period_model', 'pos/Pos_model', 'bulan/Bulan_model', 'bebas/Bebas_model', 'bebas/Bebas_pay_model', 'setting/Setting_model', 'letter/Letter_model', 'logs/Logs_model', 'ltrx/Log_trx_model'));

    $this->load->helper(array('send_helper'));
  }

  // payment view in list
  public function index($offset = NULL, $id = NULL)
  {
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f']            = $f;
    $student_nis          = null;
    $siswa                = null;
    $siswaID              = null;
    $siswa['student_id']  = null;
    $params               = array();

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $student_nis = $f['r'];


      $siswa = $this->Student_model->get(array('student_nis' => $student_nis));
      $siswaID = $siswa['student_id'];
      $majors_siswa = $siswa['majors_majors_id'] ?? 0;

      $data['siswa'] = $this->Student_model->get_student(array('student_id' => $siswa['student_id'], 'group' => TRUE));

      $data['history']    = $this->Banking_model->get(array('student_id' => $siswa['student_id'], 'order_by' => 'desc'));
      $data['setor']      = $this->Banking_model->get(array('student_id' => $siswa['student_id'], 'code' => '1'));
      $data['tarik']      = $this->Banking_model->get(array('student_id' => $siswa['student_id'], 'code' => '2'));

      $sum = $this->db->query("SELECT SUM(banking_debit) AS sumDeb, SUM(banking_kredit) AS sumKrd FROM banking WHERE banking_student_id = '$siswaID'")->row_array();

      $data['sumDebit']   = $sum['sumDeb'];
      $data['sumKredit']  = $sum['sumKrd'];
    }

    // $data['akun_kas']   = $this->db->query("SELECT * FROM account WHERE account_category != 0 AND account_code LIKE '1-%' AND account_description LIKE 'Kas%' AND account_majors_id = $majors_siswa")->result_array();

    // $data['akun_hutang']   = $this->db->query("SELECT * FROM account WHERE account_category != 0 AND account_description LIKE 'Kas Tabungan%' AND account_majors_id = $majors_siswa")->result_array();

    $data['majors']     = $this->Student_model->get_majors();

    $data['title']      = 'Tabungan Siswa';
    $data['main']       = 'banking/banking_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_debit()
  {
    if ($_POST == TRUE) {
      if ($this->input->post('debit_id')) {
        $params['banking_id'] = $this->input->post('debit_id');
      }

      $period = $this->input->post('debit_period_id');
      $nis    = $this->input->post('debit_student_nis');

      $params['banking_date'] = $this->input->post('debit_date');
      $params['banking_debit'] = $this->input->post('debit_val');
      $params['banking_kredit'] = '0';
      $params['banking_note'] = $this->input->post('debit_note');
      $params['banking_code'] = $this->input->post('debit_code');
      $params['banking_student_id'] = $this->input->post('debit_student_id');
      $params['banking_period_id'] = $this->input->post('debit_period_id');
      $params['user_user_id'] = $this->session->userdata('uid');

      $this->Banking_model->add($params);

      $student = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_id, majors_school_name, class_name,  student_parent_phone, student_id_telegram FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_nis = '$nis'")->row_array();
      $wa_key             = $this->Setting_model->get(array('name' => 'setting_wa_key'));
      $setting_whatsapp   = $this->Setting_model->get(array('id' => 92));
      $set_key       = $wa_key['setting_value'];
      $set_wa        = $setting_whatsapp['setting_value'];

      // $jurnal_umum = array(
      //   'sekolah_id' => $student['majors_id'],
      //   'keterangan' => 'Setoran Tabungan ' . $student['student_full_name'],
      //   'tanggal'    => $this->input->post('debit_date'),
      //   'pencatatan' => 'auto',
      //   'waktu_update' => date('Y-m-d H:i:s'),
      //   'keterangan_lainnya' => $this->input->post('debit_note')
      // );

      // $this->db->insert('jurnal_umum', $jurnal_umum);

      // $jurum = $this->db->query("SELECT MAX(id) AS last_id FROM jurnal_umum")->row_array();
      // $lastJurum = $jurum['last_id'];

      // $dataKredit = array(
      //   'id_jurnal' => $lastJurum,
      //   'account_code' => $this->get_akun($this->input->post('akun_kas_debit')),
      //   'debet' => $this->input->post('debit_val'),
      //   'kredit' => 0,
      // );

      // $this->db->insert('jurnal_umum_detail', $dataKredit);

      // $dataKas = array(
      //   'id_jurnal' => $lastJurum,
      //   'account_code' => $this->get_akun($this->input->post('akun_hutang_debit')),
      //   'debet' => 0,
      //   'kredit' => $this->input->post('debit_val'),
      // );

      // $this->db->insert('jurnal_umum_detail', $dataKas);


      if (isset($student['student_parent_phone']) and $student['student_parent_phone'] != '+62') {
        // $params_msg['pesan'] = 'Setoran Tabungan ananda ' . $student['student_full_name'] . ' kelas ' . $student['class_name'] . ' unit ' . $student['majors_school_name'] . ' sudah kami terima.' . "\n\n" .
        // 'Berikut Bukti Transaksi Tabungan nya.' . "\n\n" . ' Download Bukti : ' . base_url() . 'banking/cetakBukti?r=' . base64_encode($nis) . '&d=' . base64_encode($params['banking_date']);

        $params_msg['pesan'] = 'Setoran Tabungan ananda ' . $student['student_full_name'] . ' kelas ' . $student['class_name'] . ' unit ' . $student['majors_school_name'] . ' sudah kami terima.' . "\n\n" .
          'Berikut Bukti Transaksi Tabungan nya.';



        $template_query = $this->db->get_where('templates', array('location' => 'BANKING_IN'));

        $check_template         = $template_query->num_rows();

        if ($check_template > 0) {
          $get_template = $template_query->row_array();

          $data = [
            'STUDENT_NIS' => $student['student_nis'],
            'STUDENT_FULL_NAME' => $student['student_full_name'],
            'EMPLOYEE_NIP' => '',
            'EMPLOYEE_NAME' => '',
            'POSITION_NAME' => '',
            'CLASS_NAME' => $student['class_name'],
            'MAJORS_SCHOOL_NAME' => $student['majors_school_name'],
            'MAJORS_SHORT_NAME' => $student['majors_short_name'],
            'NOMINAL' => '',
            'TRX_DATE' => '',
            'NOREF' => '',
            'MONTH_NAME' => pretty_date(date('Y-m-d'), 'F', false),
            'YEAR' => date('Y')
          ];

          $template = $get_template['content'];

          $debit_template = parse_template($template, $data, false);

          $debit_template = str_replace(['\\n', '\\r'], ["\n", "\r"], $debit_template);
        }

        $params_msg['pesan'] = $debit_template;

        $params_msg['pesan'] .= "\n\n" . ' Download Bukti : ' . base_url() . 'banking/cetakBukti?r=' . base64_encode($nis) . '&d=' . base64_encode($params['banking_date']);

        $params_msg['no_wa']                = $student['student_parent_phone'];
        $params_msg['status_kirim']         = 'PENDING';
        // $params_msg['id_funding']           = $noref;
        $params_msg['created_by']           = $user_id = $this->session->userdata('uid');
        $params_msg['created_date']         = date("Y-m-d H:i:s");

        $this->Log_trx_model->send_pesan($params_msg);

        $no_wa = $student['student_parent_phone'];
        $psn = $params_msg['pesan'];

        send_whatsapp($no_wa, $psn, $set_key, $set_wa);
      }

      if (isset($student['student_id_telegram'])) {
        $params_msg['pesan'] = 'Setoran Tabungan ananda ' . $student['student_full_name'] . ' kelas ' . $student['class_name'] . ' unit ' . $student['majors_school_name'] . ' sudah kami terima.' . "\n\n" .
          'Berikut Bukti Transaksi Tabungan nya.' . "\n\n" . ' Download Bukti : ' . base_url() . 'banking/cetakBukti?r=' . base64_encode($nis) . '&d=' . base64_encode($params['banking_date']);


        $telegram_id = $student['student_id_telegram'];
        $psn = $params_msg['pesan'];
        // 'Berikut Bukti Transaksi Tabungan nya.'."\n\n". ' Download Bukti : ' . base_url() . 'banking/cetakBukti?n='. base64_encode($params['banking_period_id']).'&r='. base64_encode($nis).'&d='. base64_encode($params['banking_date']);
        /*--------------------------------
            Kirim Bot telegram: 
            --------------------------------*/
        send_telegram($telegram_id, $psn);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Setoran Berhasil');
    redirect('manage/banking?r=' . $nis);
  }

  public function add_kredit()
  {
    $use_limit       = $this->Setting_model->get(array('name' => 'use_limit'));
    $setLimitGlobal  = $this->Setting_model->get(array('name' => 'limit_tabungan'));
    $isLimitUse      = $use_limit['setting_value'];

    if ($_POST == TRUE) {
      $is_confirmed = $this->input->post('confirm') == 1;
      $nis = $this->input->post('kredit_student_nis');
      $period = $this->input->post('kredit_period_id');
      $student_id = $this->input->post('kredit_student_id');
      $nominal = $this->input->post('kredit_val');

      // Cek limit
      $limit = 0;
      $v_limit = $this->db->get_where('v_limit_tabungan_siswa', ['student_nis' => $nis])->row();
      if ($v_limit && $v_limit->withdraw_limit > 0) {
        $limit = $v_limit->withdraw_limit;
      }

      if ($limit == 0 && $isLimitUse == 'Y') {
        $limit = (int)$setLimitGlobal['setting_value'];
      }

      // Cek saldo
      $saldo = $this->db->query(
        "SELECT IFNULL(SUM(b.banking_debit - b.banking_kredit), 0) AS saldo 
       FROM banking b 
       WHERE b.banking_student_id = $student_id"
      )->row();

      if ($nominal > $saldo->saldo) {
        return $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => 'failed', 'message' => 'Penarikan Melebihi Saldo']));
      }

      // Total hari ini
      $banking = $this->db->query(
        "SELECT IFNULL(SUM(b.banking_kredit), 0) AS totalTransaksi 
       FROM banking b 
       WHERE b.banking_student_id = $student_id 
         AND b.banking_code = 2 
         AND b.banking_date = CURDATE()"
      )->row();

      $totalTrs = $banking->totalTransaksi + $nominal;

      if ($limit > 0) {
        if (!$is_confirmed && $limit > 0 && ($nominal > $limit || $totalTrs > $limit)) {
          // Minta konfirmasi dulu
          return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
              'status' => 'confirm',
              'message' => 'Nominal melebihi limit harian. Apakah Anda yakin ingin melanjutkan?'
            ]));
        }
      }

      // Proses penarikan
      $params = [
        'banking_date'       => $this->input->post('kredit_date'),
        'banking_debit'      => 0,
        'banking_kredit'     => $nominal,
        'banking_note'       => $this->input->post('kredit_note'),
        'banking_code'       => '2',
        'banking_student_id' => $student_id,
        'banking_period_id'  => $period,
        'user_user_id'       => $this->session->userdata('uid')
      ];

      if ($this->input->post('kredit_id')) {
        $params['banking_id'] = $this->input->post('kredit_id');
      }

      $this->Banking_model->add($params);

      $student = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_id, majors_school_name, class_name,  student_parent_phone, student_id_telegram FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_nis = '$nis'")->row_array();
      $wa_key             = $this->Setting_model->get(array('name' => 'setting_wa_key'));
      $setting_whatsapp   = $this->Setting_model->get(array('id' => 92));
      $set_key       = $wa_key['setting_value'];
      $set_wa        = $setting_whatsapp['setting_value'];

      if (isset($student['student_parent_phone']) and $student['student_parent_phone'] != '+62') {

        $kredit_template = 'Penarikan Tabungan ananda ' . $student['student_full_name'] . ' kelas ' . $student['class_name'] . ' unit ' . $student['majors_school_name'] . ' sudah diproses.';

        $template_query = $this->db->get_where('templates', array('location' => 'BANKING_OUT'));

        $check_template         = $template_query->num_rows();

        if ($check_template > 0) {
          $get_template = $template_query->row_array();

          $data = [
            'STUDENT_NIS' => $student['student_nis'],
            'STUDENT_FULL_NAME' => $student['student_full_name'],
            'EMPLOYEE_NIP' => '',
            'EMPLOYEE_NAME' => '',
            'POSITION_NAME' => '',
            'CLASS_NAME' => $student['class_name'],
            'MAJORS_SCHOOL_NAME' => $student['majors_school_name'],
            'MAJORS_SHORT_NAME' => $student['majors_short_name'],
            'NOMINAL' => '',
            'TRX_DATE' => '',
            'NOREF' => '',
            'MONTH_NAME' => pretty_date(date('Y-m-d'), 'F', false),
            'YEAR' => date('Y')
          ];

          $template = $get_template['content'];

          $kredit_template = parse_template($template, $data, false);

          $kredit_template = str_replace(['\\n', '\\r'], ["\n", "\r"], $kredit_template);
        }

        $params_msg['pesan'] = $kredit_template;

        $params_msg['pesan'] .= "\n\n" . ' Download Bukti : ' . base_url() . 'banking/cetakBukti?r=' . base64_encode($nis) . '&d=' . base64_encode($params['banking_date']);

        $params_msg['no_wa']                = $student['student_parent_phone'];
        $params_msg['status_kirim']         = 'PENDING';
        // $params_msg['id_funding']           = $noref;
        $params_msg['created_by']           = $user_id = $this->session->userdata('uid');
        $params_msg['created_date']         = date("Y-m-d H:i:s");

        $this->Log_trx_model->send_pesan($params_msg);

        $no_wa = $student['student_parent_phone'];
        $psn = $params_msg['pesan'];

        send_whatsapp($no_wa, $psn, $set_key, $set_wa);
      }

      return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'success', 'message' => 'Penarikan berhasil.']));
    }
  }

  function kirim_notif()
  {
    $nis = $this->input->post('nis');
    $tgl = $this->input->post('tgl');

    $student = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_school_name, class_name, student_parent_phone FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_nis = '$nis'")->row_array();

    if (isset($student['student_parent_phone']) and $student['student_parent_phone'] != '+62') {
      $no_wa = $student['student_parent_phone'];

      $wa_key             = $this->Setting_model->get(array('name' => 'setting_wa_key'));
      $setting_whatsapp   = $this->Setting_model->get(array('id' => 92));
      $set_key       = $wa_key['setting_value'];
      $set_wa        = $setting_whatsapp['setting_value'];

      $pesan = 'Penarikan Tabungan ananda ' . $student['student_full_name'] . ' kelas ' . $student['class_name'] . ' unit ' . $student['majors_school_name'] . ' sudah berhasil.' . "\n\n" .
        'Berikut Bukti Transaksi Tabungan nya.' . "\n\n" . ' Download Bukti : ' . base_url() . 'banking/cetakBukti?r=' . base64_encode($nis) . '&d=' . base64_encode($tgl);

      send_whatsapp($no_wa, $pesan, $set_key, $set_wa);
    }
  }

  public function delete($id = NULL)
  {
    if ($_POST) {
      $period = $this->input->post('period_id');
      $nis    = $this->input->post('student_nis');
      $code    = $this->input->post('code');

      $this->Banking_model->delete($id);
      if ($code == '1') {
        $this->session->set_flashdata('success', 'Hapus Setoran berhasil');
      } else {
        $this->session->set_flashdata('success', 'Hapus Penarikan berhasil');
      }

      redirect('manage/banking?r=' . $nis);
    } elseif (!$_POST) {
      $this->session->set_flashdata('failed', 'Hapus Setoran Gagal');
      // if($code == '1'){
      //       $this->session->set_flashdata('failed', 'Hapus Setoran Gagal');    
      // }else{
      //     $this->session->set_flashdata('failed', 'Hapus Penarikan Gagal');
      // }
      // redirect('manage/banking?n='.$period.'&r='.$nis);
    }
  }

  function printBook()
  {
    $this->load->helper(array('dompdf'));
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $siswa = null;
    $periodID = null;
    $siswaID = null;
    $params = array();

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    if (isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $date = $f['d'];
    }

    $data['siswa'] = $this->Student_model->get(array('student_id' => $siswa['student_id'], 'group' => TRUE));

    $data['book']   = $this->db->query("SELECT banking_date as date, banking_note as note, banking_code as code, banking_debit AS debit, banking_kredit AS kredit, @saldo:=@saldo+banking_debit-banking_kredit AS saldo FROM banking JOIN (SELECT @saldo:=0) a WHERE banking_student_id = '$siswaID'")->result_array();


    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Student_model->get_majors();

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $html = $this->load->view('banking/banking_book_pdf', $data, true);
    $data = pdf_create($html, $siswa['student_full_name'], TRUE, 'A4', TRUE);
  }

  function cetakBukti()
  {
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $siswa = null;
    $siswaID = null;
    $periodID = null;
    $params = array();

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    if (isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $date = $f['d'];
    }

    $data['siswa'] = $this->Student_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'group' => TRUE));

    $data['trx']        = $this->Banking_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'date' => $date));

    $sum = $this->db->query("SELECT SUM(banking_debit) AS sumDeb, SUM(banking_kredit) AS sumKrd FROM banking WHERE banking_student_id = '$siswaID' AND banking_date = '$date'")->row_array();


    $data['sumDebit']   = $sum['sumDeb'];
    $data['sumKredit']  = $sum['sumKrd'];


    $qSaldo = $this->db->query("SELECT SUM(banking_debit) AS debit, SUM(banking_kredit) AS kredit FROM banking WHERE banking_student_id = '$siswaID'")->row_array();

    $data['saldo']   = $qSaldo['debit'] - $qSaldo['kredit'];
    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Student_model->get_majors();
    // endtotal
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    $tanggal = date_create($f['d']);
    $dformat = date_format($tanggal, 'dmYHis');
    $this->barcode2('TAB-' . $dformat . $siswa['student_nis'], '');
    $html = $this->load->view('banking/banking_cetak_pdf', $data, TRUE);
    $data = pdf_create($html, 'Cetak_Struk_' . $siswa['student_full_name'] . '_' . date('Y-m-d'), TRUE, 'A4', TRUE);
  }

  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_transaction/';

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
    $drawing->setFilename(FCPATH . 'media/barcode_transaction/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" onchange="get_siswa()">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="0"> Semua Kelas </option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>
				</div>
			</div>';
  }

  function student_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $id_class  = $this->input->post('id_class');
    $dataStudent  = $this->db->query("SELECT * FROM student WHERE majors_majors_id = '$id_majors' AND class_class_id = '$id_class' ORDER BY student_nis ASC")->result_array();

    echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Siswa</label>
		            <select name="m" id="student_id" class="form-control">
                    <option value="">-- Pilih Siswa --</option>
                    <option value="0"> Semua Siswa </option>';
    foreach ($dataStudent as $row) {

      echo '<option value="' . $row['student_id'] . '">';

      echo $row['student_full_name'];

      echo '</option>';
    }
    echo '</select>
				</div>
			</div>';
  }

  public function report()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['student_id'] = $q['m'];
    }

    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['student']    = $this->Student_model->get($params);
    $data['banking']    = $this->Banking_model->get_sum($params);

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Laporan Tabungan Siswa';
    $data['main'] = 'banking/banking_report';
    $this->load->view('manage/layout', $data);
  }

  public function report_date()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
      $params['start'] = $q['s'];
    }

    if (isset($q['e']) && !empty($q['e']) && $q['e'] != '') {
      $params['end'] = $q['e'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['banking']    = $this->Banking_model->get($params);

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Laporan Tabungan Siswa Per Tanggal';
    $data['main'] = 'banking/banking_report_date';
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
      $params['student_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['student']    = $this->Student_model->get($params);
    $data['banking']    = $this->Banking_model->get_sum($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $this->load->view('banking/banking_xls', $data);
  }


  public function banking_date_excel()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
      $params['start'] = $q['s'];
    }

    if (isset($q['e']) && !empty($q['e']) && $q['e'] != '') {
      $params['end'] = $q['e'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['banking']    = $this->Banking_model->get($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $this->load->view('banking/banking_date_xls', $data);
  }

  private function get_akun(Int $id = null)
  {
    $code = $this->db->query("SELECT account_code FROM account WHERE account_id = '$id'")->row_array();

    return $code['account_code'];
  }
}
