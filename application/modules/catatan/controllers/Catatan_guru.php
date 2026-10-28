<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Catatan_guru extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged_guru') == NULL) {
      header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    if ($this->session->userdata('uiswali_guru') == 0) {
      redirect('guru');
    }

    $this->load->model(array('student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $guru_id = $this->session->userdata('uid_guru');

    $params = array();
    // Nip
    if (isset($f['c']) && !empty($f['c']) && $f['c'] != '') {
      $params['class_id'] = $f['c'];
    }

    $data['class'] = $this->db->query("SELECT b.* FROM wali_kelas a
                                      JOIN class b ON a.wali_kelas_class_id = b.class_id
                                      WHERE a.wali_kelas_employee_id = '$guru_id'")->result_array();

    $data['student'] = $this->Student_model->get_catatan($params);

    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));


    $data['title'] = 'Catatan';
    $data['main'] = 'catatan/catatan_guru';
    $this->load->view('guru/layout', $data);
  }

  public function change_catatan()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $student_id = $_POST['student_id'];
      $catatan    = $_POST['catatan'];

      $check_catatan = $this->db->get_where('catatan', array('catatan_student_id' => $student_id))->num_rows();

      if ($check_catatan == 0) {
        $sql = "INSERT INTO catatan (catatan_student_id, catatan_text) VALUES ('$student_id', '$catatan')";
      } else {
        $sql = "UPDATE catatan SET catatan_text = '$catatan' WHERE catatan_student_id = '$student_id'";
      }

      if ($this->db->query($sql)) {
        echo "Catatan Terisi";
      } else {
        echo "Terjadi Kesalahan";
      }
    }
  }
}
