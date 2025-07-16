<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Satuan_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('satuan/Satuan_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['satuan_name'] = $f['n'];
    }

    $data['satuan'] = $this->Satuan_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['title'] = 'Satuan';
    $data['main'] = 'satuan/satuan_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $satuanName = $_POST['satuan_name'];
      $cpt = count($_POST['satuan_name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['satuan_name'] = $satuanName[$i];

        $this->Satuan_model->add($params);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Satuan Berhasil');
    redirect('manage/satuan');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('satuan_name', 'Nama Satuan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('satuan_id')) {
        $params['satuan_id'] = $this->input->post('satuan_id');
      }

      $params['satuan_name'] = $this->input->post('satuan_name');

      $status = $this->Satuan_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Kelas');
      redirect('manage/satuan');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('satuan_id')) {
        redirect('manage/satuan/edit/' . $this->input->post('satuan_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Satuan_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/satuan');
        } else {
          $data['satuan'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Satuan';
      $data['main'] = 'satuan/satuan_add';
      $this->load->view('manage/layout', $data);
    }
  }

  public function delete($id = NULL)
  {
    if ($_POST) {

      $this->Satuan_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Satuan berhasil');
      redirect('manage/satuan');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/satuan/edit/' . $id);
    }
  }
}
