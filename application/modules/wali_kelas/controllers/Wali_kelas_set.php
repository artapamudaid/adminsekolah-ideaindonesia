<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wali_kelas_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array(
      'student/Student_model',
      'wali_kelas/Wali_kelas_model',
      'employees/Employees_model',
      'setting/Setting_model',
      'period/Period_model',
      'semester/Semester_model'

    ));

    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    if (isset($s['p']) && !empty($s['p']) && $s['p'] != 'all') {
      $params['period_id'] = $s['p'];
      $data['semester'] = $this->Semester_model->get(array('order_by' => 'semester_name', 'period_id' => $s['p']));
    }

    if (isset($s['s']) && !empty($s['s']) && $s['s'] != 'all') {
      $params['semester_id'] = $s['s'];
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    }



    $data['wali_kelas'] = $this->Wali_kelas_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $config['base_url'] = site_url('manage/wali_kelas/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['period'] = $this->Period_model->get(array('order_by' => 'period_start'));
    $data['majors'] = $this->Student_model->get_majors();
    $data['classes'] = $this->Student_model->get_class();
    $data['employees'] = $this->Employees_model->get();

    $data['title'] = 'Wali Kelas';
    $data['main'] = 'wali_kelas/wali_kelas_list';
    $this->load->view('manage/layout', $data);
  }

  public function create()
  {
    if ($_POST == TRUE) {
      $wali_kelas_employee_id = $this->input->post('wali_kelas_employee_id', TRUE);
      $wali_kelas_class_id = $this->input->post('wali_kelas_class_id', TRUE);
      $wali_kelas_semester_id = $this->input->post('wali_kelas_semester_id', TRUE);

      $params['wali_kelas_employee_id'] = $wali_kelas_employee_id;
      $params['wali_kelas_class_id'] = $wali_kelas_class_id;
      $params['wali_kelas_semester_id'] = $wali_kelas_semester_id;

      $created = $this->Wali_kelas_model->add($params);
      if ($created) {
        $this->db->query("UPDATE employee SET employee_is_wali = 1 WHERE employee_id = '$wali_kelas_employee_id'");
      }
    }
    $this->session->set_flashdata('success', ' Tambah Wali Kelas Berhasil');
    redirect('manage/wali_kelas');
  }

  public function add($id = null)
  {

    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST == TRUE) {
      $wali_kelas_id = $_POST['wali_kelas_id'];
      $wali_kelas_employee_id = $_POST['wali_kelas_employee_id'];
      $wali_kelas_old = $_POST['wali_kelas_old'];
      $wali_kelas_class_id = $_POST['wali_kelas_class_id'];
      $wali_kelas_semester_id = $_POST['wali_kelas_semester_id'];

      $params['wali_kelas_id'] = $wali_kelas_id;
      $params['wali_kelas_employee_id'] = $wali_kelas_employee_id;
      $params['wali_kelas_class_id'] = $wali_kelas_class_id;
      $params['wali_kelas_semester_id'] = $wali_kelas_semester_id;

      $updated = $this->Wali_kelas_model->add($params);

      if ($updated) {
        $this->db->query("UPDATE employee SET employee_is_wali = 0 WHERE employee_id = '$wali_kelas_old'");
        $this->db->query("UPDATE employee SET employee_is_wali = 1 WHERE employee_id = '$wali_kelas_employee_id'");
      }

      $this->session->set_flashdata('success', ' Edit Wali Kelas Berhasil');
      redirect('manage/wali_kelas');
    } else {

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Wali_kelas_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/wali_kelas');
        } else {
          $data['wali_kelas'] = $object;
        }

        $data['period'] = $this->Period_model->get(array('order_by' => 'period_start'));
        $data['semester'] = $this->Semester_model->get(array('order_by' => 'semester_name', 'period_id' => $data['wali_kelas']['period_id']));

        $data['majors'] = $this->Student_model->get_majors();
        $data['classes'] = $this->Student_model->get_class(array('majors_id' => $data['wali_kelas']['majors_majors_id'], 'order_by' => 'class_name'));
        $data['employees'] = $this->Employees_model->get();
        $data['title'] = $data['operation'] . ' Keterangan Kelas';
        $data['main'] = 'wali_kelas/wali_kelas_add';
        $this->load->view('manage/layout', $data);
      }
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != 1) {
      redirect('admin');
    }

    if ($_POST) {

      $wali_kelas = $this->db->get_where('wali_kelas', array('wali_kelas_id' => $id))->row_array();
      $wali_kelas_employee_id = $wali_kelas['wali_kelas_employee_id'];

      $deleted = $this->Wali_kelas_model->delete($id);
      if ($deleted) {
        $this->db->query("UPDATE employee SET employee_is_wali = 0 WHERE employee_id = '$wali_kelas_employee_id'");
      }
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'user',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
        )
      );
      $this->session->set_flashdata('success', 'Hapus Wali Kelas berhasil');
      redirect('manage/wali_kelas');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/wali_kelas/edit/' . $id);
    }
  }

  public function get_semester()
  {
    $period_id = $this->input->post('period_id', TRUE);
    $semester = $this->Semester_model->get(array('period_id' => $period_id, 'order_by' => 'semester_name'));
    if ($semester) {
      echo json_encode($semester);
    } else {
      echo json_encode(array());
    }
  }

  public function get_class()
  {
    $majors_id = $this->input->post('majors_id', TRUE);
    $kelas = $this->Student_model->get_class(array('majors_id' => $majors_id, 'order_by' => 'class_name'));
    if ($kelas) {
      echo json_encode($kelas);
    } else {
      echo json_encode(array());
    }
  }
}
