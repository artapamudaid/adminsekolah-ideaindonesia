<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile_guru extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged_guru') == NULL) {
      header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('employees/Employees_model', 'logs/Logs_model'));
    $this->load->helper(array('form', 'url'));
  }

  public function index($offset = NULL)
  {
    $id = $this->session->userdata('uid_guru');
    if ($this->Employees_model->get_employee(array('id' => $id)) == NULL) {
      redirect('guru');
    }
    $data['guru'] = $this->Employees_model->get_employee(array('id' => $id));
    $data['title'] = 'Detail Profil guru';
    $data['main'] = 'profile/profile_view_guru';
    $this->load->view('guru/layout', $data);
  }

  // Add User and Update
  public function edit($id = NULL)
  {
    $data['operation'] = 'Edit No. HP/Whatsapp';

    if ($_POST == TRUE) {
      $phone = $this->input->post('employee_phone', TRUE);

      $phone = str_replace(" ", "", $phone);
      $phone = str_replace("(", "", $phone);
      $phone = str_replace(")", "", $phone);
      $phone = str_replace(".", "", $phone);

      if (!preg_match('/[^+0-9]/', trim($phone))) {
        if (substr(trim($phone), 0, 1) == '+') {
          $wa = trim($phone);
        } elseif (substr(trim($phone), 0, 1) == '0') {
          $wa = '62' . substr(trim($phone), 1);
        } elseif (substr(trim($phone), 0, 1) == '8') {
          $wa = '62' . trim($phone);
        } else {
          $wa = $phone;
        }
      }

      $paramsupdate['employee_phone'] = $wa;
      // $status = $this->Employees_model->add($params);

      $paramsupdate['employee_id'] = (int) $this->session->userdata('uid_guru');
      $this->Employees_model->add($paramsupdate);

      $this->session->set_flashdata('success', $data['operation'] . ' Guru Berhasil');
      redirect('guru/profile');
    } else {
      if ($this->input->post('employee_id')) {
        redirect('guru/profile/edit/' . (int) $this->session->userdata('uid_guru'));
      }

      // Edit mode
      $data['employee'] = $this->Employees_model->get_employee(array('id' => $this->session->userdata('uid_guru')));
      $data['title'] = $data['operation'] . ' Guru';
      $data['main'] = 'profile/profile_edit_guru';
      $this->load->view('guru/layout', $data);
    }
  }

  public function edit_bobot($id = NULL)
  {
    $data['operation'] = 'Update Bobot Nilai';

    if ($_POST == TRUE) {

      $params['absensi'] = (int) $this->input->post('absensi');
      $params['harian'] = (int) $this->input->post('harian');
      $params['sumatif'] = (int) $this->input->post('sumatif');
      $params['empl_id'] = (int) $this->session->userdata('uid_guru');


      $total = $params['absensi'] + $params['harian'] + $params['sumatif'];

      if ($total != 100) {
        $this->session->set_flashdata('failed', $data['operation'] . ' Guru Gagal. Total Harus 100');
        redirect('guru/profile/edit_bobot');
        exit;
      }

      $check = $this->db->get_where('bobot', array('empl_id' => $this->session->userdata('uid_guru')))->num_rows();

      if ($check > 0) {
        $this->db->where('empl_id', $params['empl_id']);
        $this->db->update('bobot', $params);
      } else {
        $this->db->insert('bobot', $params);
      }

      $this->session->set_flashdata('success', $data['operation'] . ' Guru Berhasil');
      redirect('guru/profile');
    } else {
      if ($this->input->post('employee_id')) {
        redirect('guru/profile/edit_bobot/' . (int) $this->session->userdata('uid_guru'));
      }

      // Edit mode
      $data['bobot'] = $this->db->get_where('bobot', array('empl_id' => $this->session->userdata('uid_guru')))->row_array();
      $data['title'] = $data['operation'] . ' Guru';
      $data['main'] = 'profile/add_bobot_guru';
      $this->load->view('guru/layout', $data);
    }
  }

  // Change Password student
  function cpw()
  {
    $this->load->model('Logs_model');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('employee_password', 'Password', 'required|matches[passconf]|min_length[6]');
    $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|min_length[6]');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    if ($_POST and $this->form_validation->run() == TRUE) {

      $params['employee_password'] = md5($this->input->post('employee_password'));
      $status = $this->Employees_model->change_password($this->session->userdata('uid_guru'), $params);

      $this->session->set_flashdata('success', 'Ubah password guru berhasil');
      redirect('guru/profile');
    } else {
      if ($this->Employees_model->get_employee(array('id' => $this->session->userdata('uid_guru'))) == NULL) {
        redirect('employee');
      }
      $data['employee'] = $this->Employees_model->get_employee(array('id' => $this->session->userdata('uid_guru')));
      $data['title'] = 'Ganti Password guru';
      $data['main'] = 'profile/change_pass_guru';
      $this->load->view('guru/layout', $data);
    }
  }

  function check_current_password()
  {

    $pass = $this->input->post('student_current_password');
    $student = $this->Employees_model->get_employee(array('id' => $this->session->userdata('uid_guru')));
    if (md5($pass) == $student['employee_password']) {
      return TRUE;
    } else {
      $this->form_validation->set_message('check_current_password', 'The Password did not same with the current password');
      return FALSE;
    }
  }
}
