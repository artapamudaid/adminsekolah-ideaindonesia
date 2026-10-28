<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cardregister_set extends CI_Controller
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

    $data['title'] = 'Data Siswa';
    $data['main'] = 'cardregister/cardregister_list';
    $this->load->view('manage/layout', $data);
  }

  function get_data()
  {
    $data = $this->db
      ->select('student.student_nis, student.student_full_name, majors.majors_short_name, class.class_name, cards.rfid, cards.pin')
      ->from('student')
      ->join('majors', 'majors.majors_id = student.majors_majors_id')
      ->join('class', 'class.class_id = student.class_class_id')
      ->join('cards', 'cards.nis = student.student_nis', 'left')
      ->get()
      ->result();

    echo json_encode($data);
  }

  function set_data()
  {
    $nis = preg_replace('/[^0-9A-Za-z_.-]/', '', (string) $this->input->post('nis', TRUE));
    $rfid = preg_replace('/[^0-9A-Za-z_.-]/', '', (string) $this->input->post('rfid', TRUE));
    $pin = (string) $this->input->post('pin', TRUE);

    if ($nis === '' || $rfid === '' || $pin === '') {
      echo json_encode(FALSE);
      return;
    }

    $student = $this->db->where('nis', $nis)->get('cards')->result_array();

    if (count($student) > 0) {

      $data = array(
        'rfid' => $rfid,
        'pin' => md5($pin),
        'updated_at' => date('Y-m-d H:i:s')
      );

      $this->db->where('nis', $nis);
      $data = $this->db->update('cards', $data);
    } else {

      $data = array(
        'rfid' => $rfid,
        'nis' => $nis,
        'pin' => md5($pin),
        'status' => 1
      );

      $data = $this->db->insert('cards', $data);
    }

    echo json_encode($data);
  }


  public function set_rfid()
  {
    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Import RFID Santri';
    $data['main']       = 'cardregister/cardregister_set';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download_rfid()
  {
    $params = array();

    $id_majors = (int) $this->input->post('xls_majors', TRUE);
    $id_kelas  = $this->input->post('xls_class', TRUE);

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $id_kelas = (int) $id_kelas;
      $params['class_id'] = $id_kelas;
      $data['kelas'] = $this->db->select('class_name')->where('class_id', $id_kelas)->get('class')->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors'] = $this->db->select('majors_short_name')->where('majors_id', $id_majors)->get('majors')->row_array();

    $this->load->view('cardregister/cardregister_rfid', $data);
  }

  public function set_mass()
  {
    $this->load->library('excel');
    if (isset($_FILES["file"]["name"])) {
      $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
      if (
        $_FILES['file']['error'] !== UPLOAD_ERR_OK ||
        $ext !== 'xls' ||
        $_FILES['file']['size'] > 5 * 1024 * 1024 ||
        !is_uploaded_file($_FILES['file']['tmp_name'])
      ) {
        $this->session->set_flashdata('failed', 'File RFID harus berformat .xls yang valid');
        redirect('manage/cardregister/set_rfid');
      }

      $path = $_FILES["file"]["tmp_name"];
      $reader = PHPExcel_IOFactory::createReader('Excel5');
      $reader->setReadDataOnly(TRUE);
      $object = $reader->load($path);
      foreach ($object->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        for ($row = 3; $row <= $highestRow; $row++) {
          $nis            = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
          $rfid           = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

          $nis = preg_replace('/[^0-9A-Za-z_.-]/', '', str_replace("'", "", (string) $nis));
          $rfid = preg_replace('/[^0-9A-Za-z_.-]/', '', str_replace("'", "", (string) $rfid));

          if ($nis === '' || $rfid === '') {
            continue;
          }

          $getRfid = $this->db->select('rfid')->where('nis', $nis)->get('cards')->result_array();

          if (count($getRfid) > 0) {
            $data = array(
              'rfid'        => $rfid
            );

            $this->db->where('nis', $nis);
            $this->db->update('cards', $data);
          } else {
            $data = array(
              'nis'         => $nis,
              'rfid'        => $rfid,
              'pin'         => md5('123456')
            );

            $this->db->insert('cards', $data);
          }
        }
      }

      $this->session->set_flashdata('success', 'Set Kartu RFID Berhasil');
      redirect('manage/cardregister');
    }
  }
}
