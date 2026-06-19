<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Role_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('role/Role_model', 'setting/Setting_model'));
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
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['role_name'] = $f['n'];
    }

    // if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
    //   $params['majors_id'] = $s['m'];
    // }else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
    //   //$params['majors_id'] = $s['m'];
    // }

    //$paramsPage = $params;
    //$params['limit'] = 10;
    //$params['offset'] = $offset;
    $data['role'] = $this->Role_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/role/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Role_model->get($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Role_model->get_majors();

    $data['title'] = 'Role';
    $data['main'] = 'role/role_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $roleName = $_POST['role_name'];
      //$madinMajorsId = $_POST['role_majors_id'];
      $cpt = count($_POST['role_name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['role_name'] = $roleName[$i];
        //$params['role_majors_id'] = $madinMajorsId;

        $this->Role_model->add($params);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Role Berhasil');
    redirect('manage/role');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('role_name', 'Nama Role', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('role_id')) {
        $params['role_id'] = $this->input->post('role_id');
      }
      $params['role_name'] = $this->input->post('role_name');
      //$params['role_majors_id'] = $this->input->post('role_majors_id');
      $status = $this->Role_model->add($params);


      $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Role');
      redirect('manage/role');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('role_id')) {
        redirect('manage/role/edit/' . $this->input->post('role_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Role_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/role');
        } else {
          $data['role'] = $object;
        }
      }
      // $data['majors'] = $this->Role_model->get_majors();
      $data['title'] = $data['operation'] . ' Keterangan Role';
      $data['main'] = 'role/role_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    $siswa = $this->Role_model->get(array('role_id' => $id));

    if ($_POST) {

      if (count($siswa) > 0) {
        $this->session->set_flashdata('failed', 'Data Role tidak dapat dihapus');
        redirect('manage/role');
      }

      $this->Role_model->delete_madin($id);
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
      $this->session->set_flashdata('success', 'Hapus Role berhasil');
      redirect('manage/role');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/role/edit/' . $id);
    }
  }
}
