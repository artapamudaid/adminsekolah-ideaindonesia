<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee_presence extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array('rekap_presensi/Rekap_presensi_model', 'student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // Update
  public function index()
  {
    $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
    $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $data['title'] = 'Presensi Harian Pegawai';
    $data['main'] = 'employee_presence/employee_presence_add';
    $this->load->view('frontend/employee_presence', $data);
  }

  public function presence()
  {

    if ($_POST) {
      if (isset($_POST['rfid'])) {
        $jenis_absen  = $this->input->post('jenis_absen', TRUE);
        $rfid = $this->input->post('rfid', TRUE);

        $sql = "SELECT employee_id, employee_nip, employee_photo, employee_name 
                FROM employee
                WHERE employee_nip = '$rfid'";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
          $pegawai = $result->row_array();

          $this->db->where('tanggal', date('Y-m-d'));
          $this->db->where('id_pegawai', $pegawai['employee_id']);
          $this->db->where('jenis_absen', $jenis_absen);
          $check = $this->db->get('data_absensi')->num_rows();

          if ($check > 0) {
            $data = array(
              'status' => 'error',
              'message' => 'Hari Ini Anda Sudah Melakukan Presensi!'
            );

            echo json_encode($data);
            exit;
          }

          $timer      = date('H:i:s');

          $status     = 'Tepat Waktu';
          $keterangan = 'Absen Datang Berhasil';
          $pesan      = 'Selamat Datang, ' . $pegawai['employee_name'];

          $dt         = date('Y-m-d');
          $date       = pretty_date($dt, 'l', FALSE);
          $day        = $this->db->query("SELECT day_id from day WHERE day_name = '$date'")->row_array();
          $data_waktu = $this->db->query("SELECT * from data_waktu WHERE data_waktu_day_id = $day[day_id]")->row_array();
          $area = $this->db->query("SELECT MIN(id_area) AS id FROM `area_absensi`")->row_array();

          if ($jenis_absen == 'DATANG') {
            if ($timer > $data_waktu['data_waktu_masuk']) {
              $status = 'TERLAMBAT';
            }
          } elseif ($jenis_absen == 'PULANG') {
            $keterangan = 'Absen Pulang Berhasil';
            $pesan      = 'Hati-hati di jalan, ' . $pegawai['employee_name'];
            if ($timer < $data_waktu['data_waktu_pulang']) {
              $status = 'PULANG AWAL';
            }
          }


          $params['statushadir']    = $status;
          $params['created_date']   = date('Y-m-d H:i:s');
          $params['created_by']     = $pegawai['employee_id'];
          $params['jenis_absen']    = $jenis_absen;
          $params['id_pegawai']     = $pegawai['employee_id'];
          $params['longi']          = $this->input->post('longi');
          $params['lati']           = $this->input->post('lati');
          $params['catatan_absen']  = 'Absen Datang';
          $params['area_absen']     = $area['id'];
          $params['bulan']          = date('Y-m');
          $params['tanggal']        = date('Y-m-d');
          $params['time']           = date('H:i:s');


          $result = $this->db->insert('data_absensi', $params);
          $insert_id = $this->db->insert_id();

          if ($result) {

            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
              array(
                'log_date' => date('Y-m-d H:i:s'),
                'user_id' => $pegawai['employee_id'],
                'log_module' => 'Tambah Presensi WEB',
                'log_action' => 'Presensi Harian Pegawai',
                'log_info' => 'ID:' . $insert_id . ';Name:' . $pegawai['employee_name']
              )
            );
          } else {
            $data = array(
              'status' => 'error',
              'message' => 'Presensi Harian Pegawai Gagal'
            );

            echo json_encode($data);
            exit;
          }

          $data = array(
            'status'  => 'success',
            'nama'    => $pegawai['employee_name'],
            'foto'    => $pegawai['employee_photo'],
            'keterangan'  => $keterangan,
            'pesan'       => $pesan
          );
        } else {
          $data = array(
            'status'  => 'error',
            'message' => 'Pegawai tidak ditemukan!'
          );
        }

        echo json_encode($data);

        exit;
      }
    }
  }
}
