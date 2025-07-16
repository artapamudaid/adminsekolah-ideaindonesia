<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_presensi_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('rekap_presensi/Rekap_presensi_model', 'student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  public function index()
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->post(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    $param = array();
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

    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $params['majors_id'] = $f['m'];
    }

    $data['rekap_presensi'] = $this->Rekap_presensi_model->get($params);
    $data['majors'] = $this->Student_model->get_majors();
    $config['base_url'] = site_url('manage/rekap_presensi');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Rekap Presensi Di WEB';
    $data['main'] = 'rekap_presensi/rekap_presensi_list';
    $this->load->view('manage/layout', $data);
  }

  public function export_excel()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

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

    // update penambahan get params array
    $data['rekap_absensi'] = $this->Rekap_presensi_model->rekap(array('majors_id' => $majors_id));
    $this->load->view('rekap_presensi/rekap_presensi_excel', $data);
  }

  public function export_excel_bulan()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

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
      $data['namaBulan']      = $namaBulan['month_name'];
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
    // update penambahan get params array
    $data['rekap_absensi'] = $this->Rekap_presensi_model->rekap(array('majors_id' => $majors_id));
    $this->load->view('rekap_presensi/rekap_presensi_excel_bulan', $data);
  }

  public function rekap_kehadiran()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $m = $f['majors_id'];
    $ds = $f['tgl_awal'];
    $de = $f['tgl_akhir'];

    echo $m . 'tes data ';

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

    $data['rekap_absensi'] = $this->Rekap_presensi_model->get($params);
    $data['majors'] = $this->Student_model->get_majors();


    $this->load->view('rekap_presensi/rekap_presensi_kehadiran', $data);
  }

  public function rekapitulasi_presensi_excel()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

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

    // update penambahan get params array
    $data['rekap_absensi'] = $this->Rekap_presensi_model->rekap(array('majors_id' => $majors_id));
    $this->load->view('rekap_presensi/rekapitulasi_presensi_excel', $data);
  }

  public function export_pdf()
  {
    $this->load->helper(array('dompdf'));
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

    if (isset($f['majors_id']) && !empty($f['majors_id']) && $f['majors_id'] != '') {
      $params['majors_id'] = $f['majors_id'];
    }

    $data['tgl_awal'] = date('Y-m-d');
    $data['tgl_akhir'] = date('Y-m-d');
    if (isset($f['tgl_awal']) || isset($f['tgl_akhir'])) {
      $data['tgl_awal'] = $f['tgl_awal'];
      $data['tgl_akhir'] = $f['tgl_akhir'];
      $params['tgl_awal'] = $f['tgl_awal'];
      $params['tgl_akhir'] = $f['tgl_akhir'];
    }
    $data['rekap_absensi'] = $this->Rekap_presensi_model->get_rekap($params);

    $html = $this->load->view('rekap_presensi/rekap_presensi_pdf', $data, TRUE);
    $data = pdf_create($html, 'Rekap_Presensi_' . $f['tgl_awal'] . '_sd_' . $f['tgl_akhir'], TRUE, 'A4', 'landscape');
  }

  public function rekap()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

    if (isset($f['majors_id']) && !empty($f['majors_id']) && $f['majors_id'] != '') {
      $params['majors_id'] = $f['majors_id'];
    }

    $data['tgl_awal'] = date('Y-m-d');
    $data['tgl_akhir'] = date('Y-m-d');
    if (isset($f['tgl_awal']) || isset($f['tgl_akhir'])) {
      $data['tgl_awal'] = $f['tgl_awal'];
      $data['tgl_akhir'] = $f['tgl_akhir'];
      $params['tgl_awal'] = $f['tgl_awal'];
      $params['tgl_akhir'] = $f['tgl_akhir'];
    }
    $data['rekap_absensi'] = $this->Rekap_presensi_model->rekap();
    $this->load->view('rekap_presensi/rekap_presensi_rekap', $data);
  }

  public function rekap_detil()
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

    if (isset($f['employee_id']) && !empty($f['employee_id']) && $f['employee_id'] != '') {
      $params['employee_id'] = $f['employee_id'];
    }

    // $params['group']='id_pegawai';

    $data['tgl_awal'] = date('Y-m-d');
    $data['tgl_akhir'] = date('Y-m-d');
    if (isset($f['tgl_awal']) || isset($f['tgl_akhir'])) {
      $data['tgl_awal'] = $f['tgl_awal'];
      $data['tgl_akhir'] = $f['tgl_akhir'];
      $params['tgl_awal'] = $f['tgl_awal'];
      $params['tgl_akhir'] = $f['tgl_akhir'];
    }
    $data['rekap_absensi_detil'] = $this->Rekap_presensi_model->get_rekap($params);
    $this->load->view('rekap_presensi/rekap_presensi_rekap_detil', $data);
  }

  // View data detail
  public function view($id = NULL)
  {
    $data['rekap_presensi'] = $this->Rekap_presensi_model->get(array('id' => $id));
    $data['title'] = 'Detail Rekap Presensi';
    $data['main'] = 'rekap_presensi/rekap_presensi_view';
    $this->load->view('manage/layout', $data);
  }

  //Delete
  //   public function delete($id){
  //          $this->Rekap_presensi_model->delete($id);
  //          redirect('manage/rekap_presensi');
  //     }

  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    $presensi = $this->Rekap_presensi_model->get(array('id' => $id));

    if ($_POST) {

      $this->Rekap_presensi_model->hapus($id);
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Data Rekap Presensi',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
        )
      );
      $this->session->set_flashdata('success', 'Hapus Data Rekap Presensi berhasil');
      redirect('manage/rekap_presensi');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/rekap_presensi');
    }
  }

  // Update
  public function add($id = NULL)
  {

    $this->load->library('form_validation');
    $this->form_validation->set_rules('type', 'Jenis', 'trim|required|xss_clean');
    $this->form_validation->set_rules('id_pegawai', 'Pegawai', 'trim|required|xss_clean');
    $this->form_validation->set_rules('image', 'Foto', 'trim|required|xss_clean');
    $this->form_validation->set_rules('longi', 'Longi', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lati', 'Lati', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button position="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $data['employee'] = $this->db->get('employee')->result_array();

    if ($_POST and $this->form_validation->run() == TRUE) {
      $area = $this->db->query("SELECT MIN(id_area) AS id FROM `area_absensi`")->row_array();
      $dt         = date('Y-m-d');
      $tanggal    = date('d-m-Y');
      $date       = pretty_date($dt, 'l', FALSE);
      //ambil kode day_id
      $day        = $this->db->query("SELECT day_id from day WHERE day_name = '$date'")->row_array();
      //ambil data waktu
      $data_waktu = $this->db->query("SELECT * from data_waktu WHERE data_waktu_day_id = $day[day_id]")->row_array();
      $idpeg      = $this->input->post('id_pegawai');
      //timer saat ini uptodate
      $timer      = date('H:i:s');
      // ambil data pegawai
      $emp        = $this->db->query("SELECT * from employee a JOIN position b ON a.employee_position_id=b.position_id WHERE employee_id = '$idpeg'")->row_array();
      //kondisi untuk sudah prepare dan belum prepare
      if ($timer <= $data_waktu['data_waktu_masuk']) {
        $statusprepare = 'Sudah Prepare pekerjaan';
      } else {
        $statusprepare = 'Belum Prepare pekerjaan';
      }

      // cek data absen PUlang harus datang dahulu
      $cekpulang = $this->db->query("SELECT * FROM data_absensi WHERE jenis_absen = 'DATANG' AND id_pegawai = $idpeg AND tanggal= '$dt'")->num_rows();

      //cek data absensi datang
      $cekdatang = $this->db->query("SELECT * FROM data_absensi WHERE id_pegawai = $idpeg AND tanggal= '$dt'")->num_rows();
      // echo $cekdatang;
      // exit;

      if ($cekpulang > 0) {
        if ($timer >= $data_waktu['data_waktu_pulang']) {
          $status = 'Pulang';
        } else {
          $status = 'Pulang Cepat';
        }
      } elseif ($cekdatang == 0) {
        if ($timer <= $data_waktu['data_waktu_masuk']) {
          $status = 'Tepat Waktu';
        } else {
          $status = 'Telambat';
        }
      } elseif ($cekpulang == 0) {
        $this->session->set_flashdata('failed', 'Anda harus absen datang terlebih dahulu');
        redirect('manage/rekap_presensi/add');
      }


      $params['statushadir']    = $status;
      $params['created_date'] = date('Y-m-d H:i:s');
      $params['created_by'] = $this->session->userdata('uid');
      $params['jenis_absen'] = $this->input->post('type');
      $params['id_pegawai'] = $this->input->post('id_pegawai');
      $params['foto'] = $this->input->post('image');
      $params['longi'] = $this->input->post('longi');
      $params['lati'] = $this->input->post('lati');
      $params['catatan_absen'] = $this->input->post('keterangan');
      $params['area_absen'] = $area['id'];
      $params['bulan'] = date('Y-m');
      $params['tanggal'] = date('Y-m-d');
      $params['time'] = date('H:i:s');


      $result = $this->db->insert('data_absensi', $params);

      if ($result) {
        // activity log
        $this->load->model('logs/Logs_model');
        $this->Logs_model->add(
          array(
            'log_date' => date('Y-m-d H:i:s'),
            'user_id' => $this->session->userdata('uid'),
            'log_module' => 'Tambah Presensi WEB',
            'log_action' => $data['operation'],
            'log_info' => 'ID:' . date('Ymdhis') . ';Name:' . $this->input->post('jenis')
          )
        );

        $this->session->set_flashdata('success', $data['operation'] . ' Presensi WEB Berhasil');
      } else {
        $this->session->set_flashdata('failed', $data['operation'] . ' Presensi WEB Gagal');
      }
      redirect('manage/rekap_presensi');
    } else {
      $data['setting_level'] = $this->Setting_model->get(array('id' => 7));
      $data['title'] = $data['operation'] . ' Presensi WEB';
      $data['main'] = 'rekap_presensi/rekap_presensi_add';
      $this->load->view('manage/layout', $data);
    }
  }
}
