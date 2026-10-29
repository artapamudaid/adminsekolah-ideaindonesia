<?php

defined('BASEPATH') or exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Cardemployee_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('employees/Employees_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  public function index($offset = NULL)
  {
    $this->load->library('pagination');

    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();

    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['majors_name'] = $f['n'];
    }

    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['title'] = 'Data Pegawai';
    $data['main'] = 'cardemployee/cardemployee_list';
    $this->load->view('manage/layout', $data);
  }

  function get_data()
  {
    $data = $this->db->query("SELECT employee.employee_nip, employee.employee_name,
    majors.majors_short_name, position.position_name,
    employee_cards.rfid, employee_cards.pin FROM employee
    JOIN position ON position.position_id = employee.employee_position_id
    JOIN majors ON majors.majors_id = position.position_majors_id
    LEFT JOIN employee_cards ON employee_cards.nip = employee.employee_nip")->result();

    echo json_encode($data);
  }

  function set_data()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('nip', 'NIP', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rfid', 'Nomor Kartu RFID', 'trim|required|xss_clean|exact_length[10]');
    $this->form_validation->set_rules('pin', 'PIN', 'trim|required|xss_clean|exact_length[6]');

    if ($_POST and $this->form_validation->run() == TRUE) {
      $nip = sanitizeStrict($this->input->post('nip', TRUE));
      $rfid = sanitizeStrict($this->input->post('rfid', TRUE));
      $pin = sanitizeStrict($this->input->post('pin', TRUE));

      if (!ctype_digit($rfid) || strlen($rfid) != 10) {
        echo json_encode([
          'status' => false,
          'message' => 'RFID harus berupa angka dan memiliki panjang 10 karakter.'
        ]);
        return;
      }

      $check_rfid = $this->db
        ->where('rfid', $rfid)
        ->where('nip !=', $nip)
        ->get('employee_cards')
        ->num_rows();

      if ($check_rfid > 0) {
        echo json_encode([
          'status' => false,
          'message' => 'RFID sudah digunakan oleh pegawai lain.'
        ]);
        return;
      }

      if (!ctype_digit($pin) || strlen($pin) != 6) {
        echo json_encode([
          'status' => false,
          'message' => 'PIN harus berupa angka dan memiliki panjang 6 karakter.'
        ]);
        return;
      }

      $employee = $this->db->get_where("employee_cards", ['nip' => $nip])->row_array();

      if ($employee) {

        $payload = array(
          'rfid' => $rfid,
          'pin' => md5($pin),
          'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('nip', $nip);
        $this->db->update('employee_cards', $payload);

        echo json_encode([
          'status' => true,
          'message' => 'Data berhasil diperbarui.'
        ]);
      } else {

        $payload = array(
          'rfid' => $rfid,
          'nip' => $nip,
          'pin' => md5($pin),
          'status' => 1
        );

        $this->db->insert('employee_cards', $payload);

        echo json_encode([
          'status' => true,
          'message' => 'Data berhasil disimpan.'
        ]);
      }
    }
  }


  public function set_rfid()
  {
    $data['majors']     = $this->db->get_where('majors', array('majors_status' => 1))->result_array();
    $data['title']      = 'Import RFID Santri';
    $data['main']       = 'cardemployee/cardemployee_set';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download_rfid()
  {
    $params = array();

    $id_majors = $this->input->post('xls_majors');

    $params['majors_id'] = $id_majors;

    $data['employee'] = $this->Employees_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    $this->load->view('cardemployee/cardemployee_rfid', $data);
  }

  public function set_mass()
  {
    if (isset($_FILES["file"]["name"])) {

      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {
          $nip            = $row['A'];
          $rfid           = $row['E'];

          $nip            = str_replace("'", "", $nip ?? '');
          $rfid           = str_replace("'", "", $rfid ?? '');

          $getRfid       = $this->db->query("SELECT rfid FROM employee_cards WHERE nip = '$nip'")->result_array();

          if (count($getRfid) > 0) {
            $data = array(
              'rfid'        => $rfid
            );

            $this->db->where('nip', $nip);
            $this->db->update('employee_cards', $data);
          } else {
            $data = array(
              'nip'         => $nip,
              'rfid'        => $rfid,
              'pin'         => md5('123456')
            );

            $this->db->insert('employee_cards', $data);
          }

          $numrow++;
        }

        unlink($pathFile);


        $this->session->set_flashdata('success', 'Set Kartu RFID Berhasil');
        redirect('manage/cardemployee');
      }
    }
  }

  function do_upload_xls($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/xls_tmp/';

    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'xls|xlsx|csv';
    $config['max_size'] = '10240';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }
}
