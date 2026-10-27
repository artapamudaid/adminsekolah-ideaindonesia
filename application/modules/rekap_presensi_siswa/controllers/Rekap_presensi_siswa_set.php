<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_presensi_siswa_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('rekap_presensi_siswa/Rekap_presensi_siswa_model', 'student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  public function index()
  {
    $this->load->library('pagination');
    // Apply Filter
    $f = $this->input->post(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['rekap_presensi_search'] = $f['n'];
    }

    $data['tgl_awal'] = date('Y-m-d');
    $data['tgl_akhir'] = date('Y-m-d');
    if (isset($f['tgl_awal']) || isset($f['tgl_akhir'])) {
      $data['tgl_awal'] = $f['tgl_awal'];
      $data['tgl_akhir'] = $f['tgl_akhir'];
      if ($f['tgl_awal'] > $f['tgl_akhir']) {
        echo "<script>alert('Tanggal Awal Lebih Besar Dari Tanggal Akhir')</script>";
        $data['tgl_awal'] = $f['tgl_akhir'];
      }
      $params['tgl_awal'] = $f['tgl_awal'];
      $params['tgl_akhir'] = $f['tgl_akhir'];
    }

    // Filter Unit (Majors)
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $params['majors_id'] = $f['m'];
    }

    // Filter Kelas
    if (isset($f['c']) && !empty($f['c']) && $f['c'] != '' && $f['c'] != 'all') {
      $params['class_id'] = $f['c'];
    }

    $data['rekap_presensi'] = $this->Rekap_presensi_siswa_model->get($params);
    $data['majors'] = $this->Student_model->get_majors();

    $class_params = array();
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $class_params['majors_id'] = $f['m'];
    }
    $data['classes'] = $this->Student_model->get_class($class_params);

    $config['base_url'] = site_url('manage/rekap_presensi_siswa');
    $config['suffix'] = '?' . http_build_query($_GET ?? [], '', "&");

    $data['title'] = 'Rekap Presensi Siswa';
    $data['main'] = 'rekap_presensi_siswa/rekap_presensi_list';
    $this->load->view('manage/layout', $data);
  }

  public function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $params = array();
    if (!empty($id_majors)) {
      $params['majors_id'] = $id_majors;
    }
    $dataKelas = $this->Student_model->get_class($params);

    echo '<select id="c" name="c" class="form-control" required="">
            <option value="">--- Pilih Kelas ---</option>
            <option value="all">Semua Kelas</option>';
    foreach ($dataKelas as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }

  public function export_excel()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    $class_id = null;
    $majors_id = null;

    if (isset($f['class_id']) && !empty($f['class_id']) && $f['class_id'] != '' && $f['class_id'] != 'all') {
      $params['class_id'] = $f['class_id'];
      $class_id = $f['class_id'];
    }

    if (isset($f['majors_id']) && !empty($f['majors_id']) && $f['majors_id'] != '') {
      $params['majors_id'] = $f['majors_id'];
      $majors_id = $f['majors_id'];
    }

    $data['tgl_awal'] = date('Y-m-d');
    $data['tgl_akhir'] = date('Y-m-d');
    if (isset($f['tgl_awal']) || isset($f['tgl_akhir'])) {
      $data['tgl_awal'] = $f['tgl_awal'];
      $data['tgl_akhir'] = $f['tgl_akhir'];
      $params['tgl_awal'] = $f['tgl_awal'];
      $params['tgl_akhir'] = $f['tgl_akhir'];
    }

    $data['rekap_absensi'] = $this->Rekap_presensi_siswa_model->rekap(array('class_id' => $class_id, 'majors_id' => $majors_id));
    $this->load->view('rekap_presensi_siswa/rekap_presensi_excel', $data);
  }

  public function export_excel_bulan()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    $class_id = (isset($f['class_id']) && !empty($f['class_id']) && $f['class_id'] != 'all') ? $f['class_id'] : null;
    $majors_id = (isset($f['majors_id']) && !empty($f['majors_id'])) ? $f['majors_id'] : null;

    $data['tgl_awal'] = date('Y-m-d');
    $data['tgl_akhir'] = date('Y-m-d');
    if (isset($f['tgl_awal']) || isset($f['tgl_akhir'])) {
      $data['tgl_awal'] = $f['tgl_awal'];
      $data['tgl_akhir'] = $f['tgl_akhir'];
      $params['tgl_awal'] = $f['tgl_awal'];
      $params['tgl_akhir'] = $f['tgl_akhir'];

      $m_start = date('m', strtotime($f['tgl_awal']));
      $y_start = date('Y', strtotime($f['tgl_akhir']));

      if ($m_start == 1) {
        $bulan = '07';
      } else if ($m_start == 2) {
        $bulan = '08';
      } else if ($m_start == 3) {
        $bulan = '09';
      } else if ($m_start == 4) {
        $bulan = '10';
      } else if ($m_start == 5) {
        $bulan = '11';
      } else if ($m_start == 6) {
        $bulan = '12';
      } else if ($m_start == 7) {
        $bulan = '01';
      } else if ($m_start == 8) {
        $bulan = '02';
      } else if ($m_start == 9) {
        $bulan = '03';
      } else if ($m_start == 10) {
        $bulan = '04';
      } else if ($m_start == 11) {
        $bulan = '05';
      } else if ($m_start == 12) {
        $bulan = '06';
      }

      $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = " . $bulan . "")->row_array();
      $data['namaBulan']      = $namaBulan['month_name'] ?? '';
      $data['namaTahun']      = $y_start;

      $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();

      $awalbulan = $y_start . '-' . $m_start . '-01';
      $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

      $begin = new DateTime($awalbulan);
      $end = new DateTime($akhirbulan);

      $interval = DateInterval::createFromDateString('1 day');
      $data['daterange']  = new DatePeriod($begin, $interval, $end);

      $data['interval']   = date_diff(date_create($awalbulan), date_create($akhirbulan));

      $data['month'] = $this->db->query("SELECT * FROM month")->result_array();
    }

    $data['dt_arr'] = [0 => 'Abs. Datang',  1 => 'Status',  2 => 'Abs. Pulang',  3 => 'Status',  4 => 'Ket. Lain'];
    $data['rekap_absensi'] = $this->Rekap_presensi_siswa_model->rekap(['class_id' => $class_id, 'majors_id' => $majors_id]);

    $data['absensi'] = $this->Rekap_presensi_siswa_model
      ->get_absensi_bulan($class_id, $data['tgl_awal'], $data['tgl_akhir'], $majors_id);

    $data['counter'] = $this->Rekap_presensi_siswa_model
      ->get_rekap_counter($class_id, $data['tgl_awal'], $data['tgl_akhir'], $majors_id);

    $this->load->view('rekap_presensi_siswa/rekap_presensi_excel_bulan', $data);
  }

  public function rekap_kehadiran()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $c = $f['class_id'] ?? '';
    $m = $f['majors_id'] ?? '';
    $ds = $f['tgl_awal'] ?? '';
    $de = $f['tgl_akhir'] ?? '';

    $params = array();
    $data['tgl_awal'] = $ds;
    $data['tgl_akhir'] = $de;

    if (isset($ds) || isset($de)) {
      $data['tgl_awal'] = $ds;
      $data['tgl_akhir'] = $de;

      if ($ds > $de) {
        echo "<script>alert('Tanggal Awal Lebih Besar Dari Tanggal Akhir')</script>";
        $data['tgl_awal'] = $de;
      }
      $params['tgl_awal'] = $ds;
      $params['tgl_akhir'] = $de;
    }

    if (isset($m) && !empty($m) && $m != '') {
      $params['majors_id'] = $m;
    }

    if (isset($c) && !empty($c) && $c != '' && $c != 'all') {
      $params['class_id'] = $c;
    }

    $data['rekap_absensi'] = $this->Rekap_presensi_siswa_model->get($params);
    $data['classes'] = $this->Student_model->get_class();
    $data['majors'] = $this->Student_model->get_majors();

    $this->load->view('rekap_presensi_siswa/rekap_presensi_kehadiran', $data);
  }

  // View data detail
  public function view($id = NULL)
  {
    $data['rekap_presensi'] = $this->Rekap_presensi_siswa_model->get(array('id' => $id));
    $data['title'] = 'Detail Rekap Presensi Siswa';
    $data['main'] = 'rekap_presensi_siswa/rekap_presensi_view';
    $this->load->view('manage/layout', $data);
  }

}
