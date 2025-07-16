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

    $data['title'] = 'Presensi Harian Siswa';
    $data['main'] = 'student_presence/student_presence_add';
    // $this->load->view('frontend/new_student_presence', $data);
    $this->load->view('frontend/student_presence', $data);
  }

  public function presence()
  {

    if ($_POST) {
      if (isset($_POST['nis'])) {

        $nis = $this->input->post('nis', TRUE);
        $sql = "SELECT student_full_name, student_nis, student_img, class_name, majors_short_name, majors_school_name FROM student a
              JOIN class c ON c.class_id = a.class_class_id
              JOIN majors d ON d.majors_id = a.majors_majors_id
              JOIN cards b ON a.student_nis = b.nis
              WHERE b.rfid = '$nis'";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
          $row = $result->row_array();

          $this->db->where('student_nis', $row['student_nis']);
          $this->db->select('student_id, student_full_name, class_class_id');
          $student = $this->db->get('student')->row();

          $period = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row();

          $thisMonth = pretty_date(date('Y-m-d'), 'F', FALSE);

          $month = $this->db->get_where('month', array('month_name' => $thisMonth))->row();

          $this->db->where('presensi_harian_period_id', $period->period_id);
          $this->db->where('presensi_harian_month_id', $month->month_id);
          $this->db->where('presensi_harian_date', date('Y-m-d'));
          $this->db->where('presensi_harian_student_id', $student->student_id);
          $this->db->where('presensi_harian_month_id', $month->month_id);
          $check = $this->db->get('presensi_harian')->num_rows();

          if ($check > 0) {
            $data = array(
              'status' => 'error',
              'message' => 'Hari Ini Anda Sudah Melakukan Presensi!'
            );

            echo json_encode($data);
            exit;
          }

          $params['presensi_harian_period_id']    = $period->period_id;
          $params['presensi_harian_month_id']     = $month->month_id;
          $params['presensi_harian_date']         = date('Y-m-d');
          $params['presensi_harian_class_id']     = $student->class_class_id;
          $params['presensi_harian_student_id']   = $student->student_id;
          $params['presensi_harian_status']       = 'H';
          // $params['presensi_harian_photo']        = $this->input->post('image');
          // $params['presensi_harian_lat']          = $this->input->post('longi');
          // $params['presensi_harian_lon']          = $this->input->post('lati');

          $result = $this->db->insert('presensi_harian', $params);

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
            'status'  => 'success',
            'nama'    => $row['student_full_name'],
            'foto'    => $row['student_img'],
            'unit'    => $row['majors_short_name'],
            'kelas'   => $row['class_name'],
            'keterangan' => 'Presensi Harian Siswa Berhasil'
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
