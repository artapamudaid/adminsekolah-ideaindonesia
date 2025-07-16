<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji_setting_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'employees/Employees_model', 'penggajian/Penggajian_model','setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

// User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['majors_name'] = $f['n'];
    }

    //$paramsPage = $params;
    //$params['limit'] = 10;
    //$params['offset'] = $offset;
    $data['majors']       = $this->db->query("SELECT * FROM majors WHERE majors_status = '1'")->result_array();
    $data['default_gaji'] = $this->Penggajian_model->get_default_gaji($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/majors/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);

    $data['title'] = 'Setting ';
    $data['main'] = 'gaji_setting/gaji_setting_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob(){
    if ($_POST == TRUE) {
      $defGajiName = $_POST['default_gaji_name'];
      $defaultMajorID = $_POST['default_gaji_majors_id'];
      $defGajiMode = $_POST['default_gaji_mode'];
      $cpt = count($_POST['default_gaji_name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['default_gaji_name'] = $defGajiName[$i];
        $params['default_gaji_majors_id'] = $defaultMajorID[$i];
        $params['default_gaji_mode'] = $defGajiMode[$i];

        $this->Penggajian_model->add_default_gaji($params);
      }
    }
    $this->session->set_flashdata('success',' Tambah Unit Sekolah Berhasil');
    redirect('manage/gaji_setting');
  }

  // Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('default_gaji_name', 'Nama Penggajian', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('default_gaji_id')) {
        $params['default_gaji_id'] = $this->input->post('default_gaji_id');
      }
      $params['default_gaji_name'] = $this->input->post('default_gaji_name');
      $params['default_gaji_majors_id'] = $this->input->post('default_gaji_majors_id');
      $params['default_gaji_mode'] = $this->input->post('default_gaji_mode');
      $status = $this->Penggajian_model->add_default_gaji($params);


      $this->session->set_flashdata('success', $data['operation'] . ' Default Gaji');
      redirect('manage/gaji_setting');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('default_gaji_id')) {
        redirect('manage/gaji_setting/edit/' . $this->input->post('default_gaji_id'));
      }

            // Edit mode
      if (!is_null($id)) {
        $object = $this->Penggajian_model->get_default_gaji(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/gaji_setting');
        } else {
          $data['gaji_setting'] = $object;
          $data['majors']       = $this->db->query("SELECT * FROM majors WHERE majors_status = '1'")->result_array();
        }
      }
      $data['title'] = $data['operation'] . ' Setting Penggajian';
      $data['main'] = 'gaji_setting/gaji_setting_add';
      $this->load->view('manage/layout', $data);
    }
  }
  
  

// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= 3){
      redirect('manage');
    }
    if ($_POST) {

      $siswa = $this->Penggajian_model->get_default_gaji(array('default_gaji_id'=>$id));

      if (count($siswa) > 0) {
        $this->session->set_flashdata('failed', 'Data Setting Penggajian tidak dapat dihapus');
        redirect('manage/gaji_setting');
      }

      $this->Penggajian_model->delete_default_gaji($id);
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
      $this->session->set_flashdata('success', 'Hapus Setting Penggajian Sekolah berhasil');
      redirect('manage/gaji_setting');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/gaji_setting/edit/' . $id);
    }  
  }
}
