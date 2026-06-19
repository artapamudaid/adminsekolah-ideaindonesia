<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Statistik_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('users/Users_model', 'holiday/Holiday_model'));
    // $this->load->model(array('student/Student_model', 'billing/Billing_model', 'kredit/Kredit_model', 'debit/Debit_model', 'bulan/Bulan_model', 'setting/Setting_model', 'information/Information_model', 'bebas/Bebas_model', 'bebas/Bebas_pay_model'));
    $this->load->model(array('student/Student_model',  'kredit/Kredit_model', 'debit/Debit_model', 'bulan/Bulan_model', 'setting/Setting_model', 'information/Information_model', 'bebas/Bebas_pay_model'));
    $this->load->model(array('period/Period_model'));
    $this->load->model(array('statistik/Statistik_model'));
    $this->load->library('user_agent');
  }

  public function index()
  {
    $id = $this->session->userdata('uid');
    $data['user'] = count($this->Users_model->get());
    $data['student'] = count($this->Student_model->get(array('status' => 1)));
    $data['kredit'] = $this->Kredit_model->get(array('date' => date('Y-m-d')));
    $data['information'] = $this->Information_model->get(array('information_publish' => 1));
    $data['debit'] = $this->Debit_model->get(array('date' => date('Y-m-d')));
    $data['bulan_day'] = $this->Bulan_model->get_total(array('status' => 1, 'date' => date('Y-m-d')));
    $data['bebas_day'] = $this->Bebas_pay_model->get(array('date' => date('Y-m-d')));

    $data['total_kredit'] = 0;
    foreach ($data['kredit'] as $row) {
      $data['total_kredit'] += $row['kredit_value'];
    }

    $data['total_debit'] = 0;
    foreach ($data['debit'] as $row) {
      $data['total_debit'] += $row['debit_value'];
    }

    $data['total_bulan'] = 0;
    foreach ($data['bulan_day'] as $row) {
      $data['total_bulan'] += $row['total'];
    }

    $data['total_bebas'] = 0;
    foreach ($data['bebas_day'] as $row) {
      $data['total_bebas'] += $row['bebas_pay_bill'];
    }

    $this->load->library('form_validation');
    if ($this->input->post('add', TRUE)) {
      $this->form_validation->set_rules('date', 'Tanggal', 'required');
      $this->form_validation->set_rules('info', 'Info', 'required');
      $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

      if ($_POST and $this->form_validation->run() == TRUE) {
        list($tahun, $bulan, $tanggal) = explode('-', $this->input->post('date', TRUE));

        $params['year'] = $tahun;
        $params['date'] = $this->input->post('date');
        $params['info'] = $this->input->post('info');

        $ret = $this->Holiday_model->add($params);

        $this->session->set_flashdata('success', 'Tambah Agenda berhasil');
        redirect('manage');
      }
    } elseif ($this->input->post('del', TRUE)) {
      $this->form_validation->set_rules('id', 'ID', 'required');
      $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

      if ($_POST and $this->form_validation->run() == TRUE) {
        $id = $this->input->post('id', TRUE);
        $this->Holiday_model->delete($id);

        $this->session->set_flashdata('success', 'Hapus Agenda berhasil');
        redirect('manage');
      }
    }
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    $data['holiday'] = $this->Holiday_model->get();
    $data['title'] = 'Statistik';
    $data['main'] = 'statistik/statistik';
    $this->load->view('manage/layout', $data);
  }

  public function get()
  {
    $events = $this->Holiday_model->get();
    foreach ($events as $i => $row) {
      $data[$i] = array(
        'id' => $row['id'],
        'title' => strip_tags($row['info']),
        'start' => $row['date'],
        'end' => $row['date'],
        'year' => $row['year'],
        //'url' => event_url($row)
      );
    }
    echo json_encode($data, TRUE);
  }

  public function tagihan_siswa($offset = null)
  {
    $s = $this->input->get(NULL, TRUE);
    $data['s'] = $s;

    $params = array();

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];
      $majors_id = $s['m'];
      $majorsSelect = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = $majors_id")->row_array();
      $data['unit'] = $majorsSelect['majors_short_name'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }

    $periodAktif = $this->db->query("SELECT period_id,period_start,period_end FROM period WHERE period_status = 1")->row_array();
    $params['id_period'] = $periodAktif['period_id'];

    $data['majors']     = $this->Student_model->get_majors();
    // $data['class']      = $this->Student_model->get_class($params);

    // $bulan = ["Juli", "Agustus", "September", "Oktober", "November", "Desember", "Januari", "Februari", "Maret", "April", "Mei", "Juni"];
    $bulan_sekarang = date('n');
    $index_bulan = ($bulan_sekarang + 4) % 12;

    $idBulan = $index_bulan + 1;
    $params['id_bulan'] = $idBulan;
    $data['tagihan'] = $this->Statistik_model->tagihanSiswaRekapPerKelas($params);

    if (isset($s['mode']) && !empty($s['mode']) && $s['mode'] == 'pdf') {
      $data['period'] =  $periodAktif;
      $this->cetak_tagihan('pdf', $data);
    } else if (isset($s['mode']) && !empty($s['mode']) && $s['mode'] == 'excel') {
      $data['period'] =  $periodAktif;
      $this->cetak_tagihan('excel', $data);
    } else {
      $data['title'] = 'Tagihan Kelas';
      $data['main'] = 'statistik/tagihan_siswa';
      $this->load->view('manage/layout', $data);
    }
  }

  function cetak_tagihan($type, $data = array())
  {
    $data['setting_city']     = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school']   = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address']  = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone']    = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    if ($type == 'pdf') {
      $this->load->library('dompdflib');
      $paper = 'A4';
      $orientation = "potrait";
      $html = $this->load->view('statistik/tagihan_pdf', $data, true);
      $this->dompdflib->generate($html, 'Data Tagihan', $paper, $orientation);
    } elseif ($type == 'excel') {
      $this->load->view('statistik/tagihan_excel', $data);
    }
  }

  public function jumlah_siswa()
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['student_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }

    $data['student']    = $this->Student_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);
    // $data['madin']      = $this->Student_model->get_madin($params);
    $data['period'] = $this->Period_model->get($params);

    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Jumlah Siswa';
    $data['main'] = 'statistik/jumlah_siswa';
    $this->load->view('manage/layout', $data);
  }

  public function search_report_jurnal()
  {
    $ds         = $this->input->post('ds');
    $de         = $this->input->post('de');
    $majors_id  = $this->input->post('majors_id');

    $data = array();

    // Date start
    if (isset($ds) && !empty($ds) && $ds != '') {
      $data['date_start'] = $ds;
    }

    if (isset($de) && !empty($de) && $ds != '') {
      $data['date_end'] = $de;
    }

    if (isset($majors_id) && !empty($majors_id) && $majors_id != 'all') {
      $data['majors_id'] = $majors_id;
    }

    $param = $_POST['param'];

    $draw = $_REQUEST['draw'];
    $length = $_REQUEST['length'];
    $start = $_REQUEST['start'];
    $search = $_REQUEST['search']["value"];

    if ($search != "") {
      $data['search'] = $search;
    }

    // Menangani sorting
    $order_column_index = $_REQUEST['order'][0]['column']; // Index kolom yang diklik
    $order_dir = $_REQUEST['order'][0]['dir']; // Arah sorting (asc/desc)
    $data['order_dir'] = $order_dir;

    $columns = ['akunBayar', 'penerimaan', 'pengeluaran'];

    // Pastikan index yang dikirimkan oleh DataTables sesuai dengan daftar kolom yang kita punya
    $order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'tanggal';
    $data['order_column'] = $order_column;

    $output = array();
    $output['draw']   = $draw;
    $output['recordsTotal'] = $output['recordsFiltered'] = 0;
    $output['data'] = array();

    $output['totalPenerimaan'] = 0;
    $output['totalPengeluaran'] = 0;

    $output['saldoAwal'] = 0;
    $saldoAwal = $this->Statistik_model->getSaldoAwal($data);
    if ($saldoAwal) {
      $output['saldoAwal'] = $saldoAwal['saldoAwal'] ?? 0;
    }

    $totalTrx = $this->Statistik_model->count_trx($data);

    if ($totalTrx) {
      $output['totalPenerimaan'] = $totalTrx['totalPenerimaan'] ?? 0;
      $output['totalPengeluaran'] = $totalTrx['totalPengeluaran'] ?? 0;

      $laporanJurnal = $this->Statistik_model->rekap_jurnal($length, $start, $search, $data);
      $output['recordsTotal'] = $output['recordsFiltered'] = $totalTrx['totalBaris'];

      foreach ($laporanJurnal as $dt) {
        $penerimaan = '-';
        $pengeluaran = '-';
        if ($dt['penerimaan'] != 0) {
          $penerimaan = 'Rp ' . number_format($dt['penerimaan'], 0, ",", ".");
        }
        if ($dt['pengeluaran'] != 0) {
          $pengeluaran = 'Rp ' . number_format($dt['pengeluaran'], 0, ",", ".");
        }

        $output['data'][] = array(
          $dt['jenisBayar'],
          $penerimaan,
          $pengeluaran
        );
      }
    }

    echo json_encode($output);
  }

  public function search_jurnal_keuangan()
  {
    $ds         = $this->uri->segment('4');
    $de         = $this->uri->segment('5');
    $majors_id  = $this->uri->segment('6');

    $data = array();

    $data['date_start']      = $ds;
    $data['date_end']        = $de;
    $data['majors_id']     = $majors_id;

    $data['saldoAwal'] = 0;
    $saldoAwal = $this->Statistik_model->getSaldoAwal($data);
    if ($saldoAwal) {
      $data['saldoAwal'] = $saldoAwal['saldoAwal'] ?? 0;
    }

    $laporanJurnal = $this->Statistik_model->rekap_jurnal($data);
    $data['data_jurnal'] = $laporanJurnal;


    // if ($majors_id == "all") {
    //   $data['unit'] = "Semua Unit";
    // } else {
    //   $majors  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id='$majors_id'")->row_array();
    //   $data['unit'] = $majors['majors_short_name'];
    // }

    // $setting = $this->db->query("SELECT setting_name, setting_value FROM setting WHERE setting_name IN ('setting_school','setting_district','setting_city','setting_address','setting_phone','setting_nip_bendahara','setting_nama_bendahara')")->result();

    // foreach ($setting as $value) {
    //   $data[$value->setting_name] = $value->setting_value;
    // }
  }

  public function jurnal_keuangan()
  {
    $params = array();

    $data['majors']     = $this->Student_model->get_majors();

    $data['title'] = 'Jurnal Keuangan';
    $data['main'] = 'statistik/jurnal_keuangan';
    $this->load->view('manage/layout', $data);
  }

  // public function jurnal_keuangan()
  // {
  //   $this->load->library('pagination');
  //   // Apply Filter
  //   // Get $_GET variable
  //   $f = $this->input->get(NULL, TRUE);
  //   $s = $this->input->get(NULL, TRUE);

  //   $data['f'] = $f;
  //   $data['s'] = $s;

  //   $params = array();
  //   $param = array();
  //   // Nip
  //   if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
  //     $params['student_search'] = $f['n'];
  //   }

  //   if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
  //     $params['class_id'] = $s['c'];
  //   } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
  //   }

  //   if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
  //     $params['madin_id'] = $s['d'];
  //   } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
  //   }

  //   if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
  //     $params['majors_id'] = $s['m'];

  //     if ($s['s'] != '1') {
  //       $params['status'] = $s['s'];
  //     } else {
  //       $params['status']   = '1';
  //     }
  //   }

  //   $data['student']    = $this->Student_model->get($params);
  //   $data['majors']     = $this->Student_model->get_majors();
  //   $data['class']      = $this->Student_model->get_class($params);
  //   // $data['madin']      = $this->Student_model->get_madin($params);
  //   $data['period'] = $this->Period_model->get($params);
  //   $data['month'] = $this->Bulan_model->get_month($params);

  //   $config['base_url'] = site_url('manage/student/index');
  //   $config['suffix'] = '?' . http_build_query($_GET, '', "&");

  //   $data['title'] = 'Jurnal Keuangan';
  //   $data['main'] = 'statistik/jurnal_keuangan';
  //   $this->load->view('manage/layout', $data);
  // }

  public function laporan_akunting()
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['student_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }

    $data['student']    = $this->Student_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);
    // $data['madin']      = $this->Student_model->get_madin($params);
    $data['period'] = $this->Period_model->get($params);
    $data['month'] = $this->Bulan_model->get_month($params);

    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Laporan Akunting';
    $data['main'] = 'statistik/lap_akunting';
    $this->load->view('manage/layout', $data);
  }

  public function laporan_kas()
  {
    $majors_id   = $this->input->post('majors_id');
    $account_ids = $this->input->post('kas_account_id');
    $params = array();
    if (!empty($majors_id)) {
      $params['majors_id'] = $majors_id;
    }
    if (!empty($account_ids)) {
      $params['account_id']  = implode(',', $this->input->post('kas_account_id'));
    }

    $data['majors']     = $this->Student_model->get_majors();
    $data['pemasukan']  = $this->Statistik_model->pemasukanKas($params);

    $data['title'] = 'Laporan KAS Tiap Unit';
    $data['main'] = 'statistik/lap_kas';
    $this->load->view('manage/layout', $data);
  }

  public function cari_kas()
  {
    $id_majors = $this->input->post('id_majors');

    if ($id_majors != 'all') {
      $dataKas = $this->db->query("SELECT * FROM account WHERE account_category = '2' AND account_majors_id = '$id_majors' AND account_code LIKE '1%' AND account_note IN (SELECT account_id FROM account WHERE account_category = '0' AND account_majors_id = '$id_majors' AND account_code LIKE '1%' AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) ORDER BY account_code ASC")->result_array();
    } else {
      $dataKas = $this->db->query("SELECT * FROM account WHERE account_category = '2' AND account_code LIKE '1%' AND account_note IN (SELECT account_id FROM account WHERE account_category = '0' AND account_code LIKE '1%' AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) ORDER BY account_code ASC")->result_array();
    }
    echo '
			<select class="form-control multiple-select" id="kas_account_id" name="kas_account_id[]" multiple="multiple">';
    foreach ($dataKas as $row) {
      echo '<option value="' . $row['account_id'] . '">';
      echo $row['account_code'];
      echo ' - ';
      echo $row['account_description'];
      echo '</option>';
    }
    echo '</select>';
  }

  // public function old_laporan_kas()
  // {
  //   $this->load->library('pagination');
  //   // Apply Filter
  //   // Get $_GET variable
  //   $f = $this->input->get(NULL, TRUE);
  //   $s = $this->input->get(NULL, TRUE);

  //   $data['f'] = $f;
  //   $data['s'] = $s;

  //   $params = array();
  //   $param = array();
  //   // Nip
  //   if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
  //     $params['student_search'] = $f['n'];
  //   }

  //   if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
  //     $params['class_id'] = $s['c'];
  //   } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
  //   }

  //   if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
  //     $params['madin_id'] = $s['d'];
  //   } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
  //   }

  //   if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
  //     $params['majors_id'] = $s['m'];

  //     if ($s['s'] != '1') {
  //       $params['status'] = $s['s'];
  //     } else {
  //       $params['status']   = '1';
  //     }
  //   }

  //   $data['student']    = $this->Student_model->get($params);
  //   $data['majors']     = $this->Student_model->get_majors();
  //   $data['class']      = $this->Student_model->get_class($params);
  //   // $data['madin']      = $this->Student_model->get_madin($params);

  //   $config['base_url'] = site_url('manage/student/index');
  //   $config['suffix'] = '?' . http_build_query($_GET, '', "&");

  //   $data['title'] = 'Laporan KAS Tiap Unit';
  //   $data['main'] = 'statistik/lap_kas';
  //   $this->load->view('manage/layout', $data);
  // }

  public function pemasukan_per_akun()
  {
    $majors_id   = $this->input->post('majors_id');
    $account_ids = $this->input->post('kas_account_id');
    $params = array();
    if (!empty($majors_id)) {
      $params['majors_id'] = $majors_id;
    }
    if (!empty($account_ids)) {
      $params['account_id']  = implode(',', $this->input->post('kas_account_id'));
    }

    $data['majors']     = $this->Student_model->get_majors();
    $data['pemasukan']  = $this->Statistik_model->pemasukanAkun($params);

    $data['title'] = 'Pemasukan per Akun';
    $data['main'] = 'statistik/pemasukan_akun';
    $this->load->view('manage/layout', $data);
  }

  public function cari_akun()
  {
    $id_majors = $this->input->post('id_majors');
    $kategori  = $this->input->post('kategori');

    $like = '';
    if ($kategori == 'debit') {
      $like = '4-%';
    } else if ($kategori == 'kredit') {
      $like = '5-%';
    }

    if ($id_majors != 'all') {
      $dataKas = $this->db->query("SELECT * FROM account WHERE account_majors_id = '$id_majors' AND account_code LIKE  '$like'")->result_array();
    } else {
      $dataKas = $this->db->query("SELECT * FROM account WHERE account_code LIKE '$like'")->result_array();
    }
    echo '
			<select class="form-control multiple-select" id="kas_account_id" name="kas_account_id[]" multiple="multiple">';
    foreach ($dataKas as $row) {
      echo '<option value="' . $row['account_id'] . '">';
      echo $row['account_code'];
      echo ' - ';
      echo $row['account_description'];
      echo '</option>';
    }
    echo '</select>';
  }

  public function pengeluaran_per_akun()
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['student_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }

    $data['student']    = $this->Student_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);
    // $data['madin']      = $this->Student_model->get_madin($params);

    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Pengeluaran per Akun';
    $data['main'] = 'statistik/pengeluaran_akun';
    $this->load->view('manage/layout', $data);
  }

  public function total_guru_pegawai()
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['student_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }

    $data['student']    = $this->Student_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);
    // $data['madin']      = $this->Student_model->get_madin($params);

    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Jumlah Guru & Pegawai';
    $data['main'] = 'statistik/jumlah_guru';
    $this->load->view('manage/layout', $data);
  }
}
