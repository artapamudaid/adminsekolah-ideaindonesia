<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ars_pengajuan_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('ars_data/Ars_data_model', 'ars_pengajuan/Pengajuan_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

// User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    $data['pengajuan'] = $this->Pengajuan_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/ars_pengajuan/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Satuan Arsip Dokumentasi';
    $data['main'] = 'ars_pengajuan/ars_pengajuan_list';
    $this->load->view('manage/layout', $data);
  }

  // public function add_glob(){
  //   if ($_POST == TRUE) {
  //     $namaSatuan = $_POST['nama_satuan'];
  //     $keteranganSatuan = $_POST['keterangan'];
      
  //     $cpt = count($_POST['nama_satuan']);
  //     for ($i = 0; $i < $cpt; $i++) {
  //       $params['nama_satuan'] = $namaSatuan[$i];
  //       $params['keterangan'] = $keteranganSatuan[$i];

  //       $this->Ars_data_model->add_satuan($params);
  //     }
  //   }
  //   $this->session->set_flashdata('success',' Tambah Satuan Berhasil');
  //   redirect('manage/ars_pengajuan');
  // }

// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nama_pengajuan', 'Nama Pengajuan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('id_satuan')) {
        $params['id_satuan'] = $this->input->post('id_satuan');
      }
      $params['nama_satuan']  = $this->input->post('nama_satuan');
      $params['keterangan']    = $this->input->post('keterangan');
      $status                 = $this->Ars_data_model->add_satuan($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Satuan Arsip');
      redirect('manage/ars_pengajuan');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_satuan')) {
        redirect('manage/ars_pengajuan/edit/' . $this->input->post('id_satuan'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Ars_data_model->get_satuan(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/ars_pengajuan');
        } else {
          $data['satuan'] = $object;
        }
      }
      // $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Pengajuan Data Arsip';
      $data['main'] = 'ars_pengajuan/pengajuan_add';
      $this->load->view('manage/layout', $data);
    }
  }


// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    $satuan = $this->Ars_data_model->get_satuan(array('id_satuan'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/ars_pengajuan');
      // }

      $this->Ars_data_model->delete_satuan($id);
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
      redirect('manage/ars_pengajuan');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/ars_pengajuan/edit/' . $id);
    }  
  }
}
