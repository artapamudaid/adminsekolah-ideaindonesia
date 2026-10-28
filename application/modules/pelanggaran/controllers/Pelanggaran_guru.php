<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Pelanggaran_guru extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged_guru') == NULL) {
      header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    if ($this->session->userdata('uiswali_guru') == 0) {
      redirect('guru');
    }
    $this->load->model(array('student/Student_model', 'pelanggaran/Pelanggaran_model', 'period/Period_model', 'setting/Setting_model', 'logs/Logs_model'));
  }

  // payment view in list
  public function index($offset = NULL, $id = NULL)
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

    $data['konselings']      = $this->Pelanggaran_model->get($params);

    $data['title']      = 'Pelanggaran Santri';
    $data['main']       = 'pelanggaran/pelanggaran_guru';
    $this->load->view('guru/layout', $data);
  }
}
