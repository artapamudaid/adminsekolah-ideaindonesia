<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_presence extends CI_Controller
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

    $data['setting_level']              = $this->Setting_model->get(array('id' => 7));
    $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
    $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
    $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    // NOTE: Check RFID Subscription
    $data['is_rfid_subscription'] = table_exists_cached('cards');
    $data['is_rfid_subscription_employee'] = table_exists_cached('employee_cards');

    $data['title'] = 'Presensi Harian Siswa';
    $data['main'] = 'student_presence/student_presence_add';
    // $this->load->view('frontend/new_student_presence', $data);
    $this->load->view('frontend/student_presence', $data);
  }

  public function presence()
  {

    if ($_POST) {
      if (isset($_POST['rfid'])) {
        $jenis_absen  = $this->input->post('jenis_absen', TRUE);
        $rfid         = $this->input->post('rfid', TRUE);
        $longi        = $this->input->post('longitude', TRUE);
        $lati         = $this->input->post('latitude', TRUE);

        $sql = "SELECT student_id, student_full_name, student_nis, student_img, class_name,
                       majors_short_name, majors_school_name, class_class_id, a.majors_majors_id 
                  FROM student a
                  JOIN class c ON c.class_id = a.class_class_id
                  JOIN majors d ON d.majors_id = a.majors_majors_id
                  JOIN cards b ON a.student_nis = b.nis
                  WHERE b.rfid = '$rfid'";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
          $student = $result->row_array();

          $this->db->where('id_student', $student['student_id']);
          $this->db->where('date', date('Y-m-d'));
          $this->db->where('jenis_absen', $jenis_absen);
          $check = $this->db->get('presensi_student')->num_rows();

          if ($check > 0) {
            $data = array(
              'status' => 'error',
              'message' => 'Hari Ini Anda Sudah Melakukan Presensi!'
            );

            echo json_encode($data);
            exit;
          }

          $area = $this->db->query("SELECT MIN(id_area) AS id FROM `area_absensi`")->row_array();
          $majorsSiswa = $student['majors_majors_id'];
          $idHariIni = date('N');

          $waktu = $this->db->query(
            "SELECT data_waktu_masuk, data_waktu_pulang 
              FROM data_waktu
              WHERE data_waktu_majors_id = $majorsSiswa
                AND data_waktu_day_id = $idHariIni"
          )->row_array();

          $status     = 'TEPAT WAKTU';
          $keterangan = 'Absen Datang Berhasil';
          $pesan      = 'Selamat Datang, ' . $student['student_full_name'];

          if ($waktu) {
            $setWaktuMasuk = date('H:i', strtotime($waktu['data_waktu_masuk']));
            $setWaktuPulang = date('H:i', strtotime($waktu['data_waktu_pulang']));
            $waktuAbsen = date('H:i');

            if ($jenis_absen == 'DATANG') {
              if ($waktuAbsen > $setWaktuMasuk) {
                $status = 'TERLAMBAT';
              }
            } elseif ($jenis_absen == 'PULANG') {
              $keterangan = 'Absen Pulang Berhasil';
              $pesan      = 'Hati-hati di jalan, ' . $student['student_full_name'];
              if ($waktuAbsen < $setWaktuPulang) {
                $status = 'PULANG AWAL';
              }
            }
          }

          $params['id_student']       = $student['student_id'];
          $params['id_class']         = $student['class_class_id'];
          $params['id_area_absensi '] = $area['id'];
          $params['jenis_absen']      = $jenis_absen;
          $params['status']           = $status;
          $params['date']             = date('Y-m-d');
          $params['time']             = date('H:i:s');
          $params['longi']            = $longi;
          $params['lati']             = $lati;
          $params['note']             = 'Presensi Siswa RFID';

          $result = $this->db->insert('presensi_student', $params);

          if ($result) {

            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
              array(
                'log_date' => date('Y-m-d H:i:s'),
                'user_id' => $student->student_id,
                'log_module' => 'Tambah Presensi',
                'log_action' => 'Presensi Harian Siswa',
                'log_info' => 'ID:' . date('Ymdhis') . ';Name:' . $student->student_full_name
              )
            );
          } else {
            $data = array(
              'status' => 'error',
              'message' => 'Presensi Harian Siswa Gagal'
            );

            echo json_encode($data);
            exit;
          }

          $data = array(
            'status'      => 'success',
            'nama'        => $student['student_full_name'],
            'foto'        => $student['student_img'],
            'unit'        => $student['majors_short_name'],
            'kelas'       => $student['class_name'],
            'keterangan'  => $keterangan,
            'pesan'       => $pesan
          );
        } else {
          $data = array(
            'status'  => 'error',
            'message' => 'Siswa tidak ditemukan!'
          );
        }

        echo json_encode($data);

        exit;
      }
    }
  }
}
