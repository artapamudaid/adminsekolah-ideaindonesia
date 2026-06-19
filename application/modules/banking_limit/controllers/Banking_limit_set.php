<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Banking_limit_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');

    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['majors_name'] = $f['n'];
    }

    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['majors']     = $this->Student_model->get_majors();

    $data['title'] = 'Data Limit Tabungan Siswa';
    $data['main'] = 'banking_limit/banking_limit_list';
    $this->load->view('manage/layout', $data);
  }

  function get_data()
  {
    $data = $this->db->query("SELECT * FROM v_limit_tabungan_siswa")->result();

    echo json_encode($data);
  }

  function set_data()
  {
    $nis = $this->input->post('nis');
    $limit = $this->input->post('limit');

    $student     = $this->db->query("SELECT * FROM banking_limit WHERE nis = '$nis'")->result_array();

    if (count($student) > 0) {

      $data = array(
        'limit' => $limit,
        'updated_at' => date('Y-m-d H:i:s')
      );

      $this->db->where('nis', $nis);
      $data = $this->db->update('banking_limit', $data);
    } else {

      $data = array(
        'limit' => $limit,
        'nis' => $nis
      );

      $data = $this->db->insert('banking_limit', $data);
    }

    echo json_encode($data);
  }

  function set_data_batch()
  {
    $class_id = $this->input->post('class_id');
    $limit    = $this->input->post('limit');

    $whereClass = "";

    if ($class_id != "all") {
      $whereClass = " AND class_class_id = '$class_id'";
    }

    $students = $this->db->query("SELECT student_nis FROM student WHERE 1 $whereClass")->result();

    foreach ($students as $val) {
      $nis = $val->student_nis;
      $student     = $this->db->query("SELECT id FROM banking_limit WHERE nis = '$nis'")->result_array();

      if (count($student) > 0) {

        $data = array(
          'limit' => $limit,
          'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('nis', $nis);
        $data = $this->db->update('banking_limit', $data);
      } else {

        $data = array(
          'limit' => $limit,
          'nis' => $nis
        );

        $data = $this->db->insert('banking_limit', $data);
      }
    }

    // echo json_encode($data);
    $this->session->set_flashdata('success', 'Setting Limit Tabungan Berhasil');
    redirect('manage/banking_limit');
  }


  public function set_limit()
  {
    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Import Limit Tarik Tabungan Santri';
    $data['main']       = 'banking_limit/banking_limit_set';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download_limit()
  {
    $params = array();

    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $params['class_id']  = $id_kelas;
      $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    $this->load->view('banking_limit/banking_limit_xls', $data);
  }

  public function set_mass()
  {

    if (isset($_FILES["file"]["name"])) {
      $arr_file = explode('.', $_FILES['file']['name']);
      $extension = end($arr_file);

      $reader = NULL;
      if ('xls' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
      } elseif ('xlsx' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      }

      $spreadSheet = $reader->load($_FILES['file']['tmp_name']);
      $excelSheet = $spreadSheet->getActiveSheet();
      $spreadSheetAry = $excelSheet->toArray();
      $sheetCount = count($spreadSheetAry);

      for ($i = 2; $i < $sheetCount; $i++) {
        $nis            = $spreadSheetAry[$i][0];
        $limit           = $spreadSheetAry[$i][4];

        $nis            = str_replace("'", "", $nis);
        $limit           = str_replace("'", "", $limit);

        $getRfid       = $this->db->query("SELECT banking_limit.limit FROM banking_limit WHERE nis = '$nis'")->result_array();

        if (count($getRfid) > 0) {
          $data = array(
            'limit'        => $limit
          );

          $this->db->where('nis', $nis);
          $this->db->update('banking_limit', $data);
        } else {
          $data = array(
            'nis'         => $nis,
            'limit'        => $limit,
          );

          $this->db->insert('banking_limit', $data);
        }
      }


      $this->session->set_flashdata('success', 'Set Limit Tabungan Berhasil');
      redirect('manage/banking_limit');
    }
  }

  public function get_class()
  {

    $majors_id  = $this->input->post('majors_id');
    $dataClass  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$majors_id' ORDER BY class_name ASC")->result_array();

    echo '<select required="" name="class_id" id="class_id" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>
		  <option value="all">Semua Kelas</option>';
    foreach ($dataClass as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }
}
