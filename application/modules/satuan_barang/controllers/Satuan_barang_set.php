<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Satuan_barang_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('barang/Barang_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    $data['satuan']       = $this->Barang_model->get_satuan($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['title'] = 'Data Satuan Barang';
    $data['main'] = 'satuan_barang/satuan_barang_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $namaSatuan       = $_POST['nama_satuan'];
      $keteranganSatuan = $_POST['keterangan'];

      $cpt = count($_POST['nama_satuan']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['nama_satuan'] = $namaSatuan[$i];
        $params['keterangan'] = $keteranganSatuan[$i];

        $this->Barang_model->add_satuan($params);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Satuan Berhasil');
    redirect('manage/satuan_barang');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nama_satuan', 'Nama Satuan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('satuan_barang_id')) {
        $params['satuan_barang_id'] = $this->input->post('satuan_barang_id');
      }
      $params['nama_satuan']  = $this->input->post('nama_satuan');
      $params['keterangan']    = $this->input->post('keterangan');
      $status                 = $this->Barang_model->add_satuan($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Satuan Barang');
      redirect('manage/satuan_barang');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('satuan_barang_id')) {
        redirect('manage/satuan_barang/edit/' . $this->input->post('satuan_barang_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Barang_model->get_satuan(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/satuan_barang');
        } else {
          $data['satuan'] = $object;
        }
      }
      // $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Satuan Barang';
      $data['main'] = 'satuan_barang/satuan_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    $satuan = $this->Barang_model->get_satuan(array('satuan_barang_id' => $id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/satuan_barang');
      // }

      $this->Barang_model->delete_satuan($id);
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
      redirect('manage/satuan_barang');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/satuan_barang/edit/' . $id);
    }
  }
}
