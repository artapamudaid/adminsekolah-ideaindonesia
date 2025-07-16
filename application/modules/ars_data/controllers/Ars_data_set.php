<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ars_data_set extends CI_Controller {

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

    $data['data_arsip'] = $this->Ars_data_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/ars_data/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Arsip Dokumentasi';
    $data['main'] = 'ars_data/ars_data_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nama_arsip', 'Nama Arsip', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $data['jenis'] = $this->Ars_data_model->get_jenis();
    $data['satuan'] = $this->Ars_data_model->get_satuan();
    $data['lokasi'] = $this->Ars_data_model->get_lokasi();

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('id_arsip')) {
        $params['id_arsip'] = $this->input->post('id_arsip');
      }
      $params['id_jenis']       = $this->input->post('id_jenis');
      $params['nama_arsip']     = $this->input->post('nama_arsip');
      $params['jumlah']         = $this->input->post('jumlah');
      $params['id_satuan']      = $this->input->post('id_satuan');
      $params['lokasi']         = $this->input->post('lokasi');
      $params['tanggal']        = date('Y-m-d');
      $params['status']         = $this->input->post('status');
      $params['id_users']       = $this->input->post('id_users');
      $status                   = $this->Ars_data_model->add($params);

      if (!empty($_FILES['upl_arsip']['name'])) {
        $paramsupdate['upl_arsip'] = $this->do_upload($name = 'upl_arsip', $fileName= $params['nama_arsip']);
      } 

      $paramsupdate['id_arsip'] = $status;
      $this->Ars_data_model->add($paramsupdate);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Satuan Arsip');
      redirect('manage/ars_data');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_arsip')) {
        redirect('manage/ars_data/edit/' . $this->input->post('id_arsip'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Ars_data_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/ars_data');
        } else {
          $data['arsip'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Pengajuan Data Arsip';
      $data['main'] = 'ars_data/ars_data_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // Setting Upload File Requied
  function do_upload($name=NULL, $fileName=NULL) {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/arsip/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
    $config['max_size'] = '1024';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }

   // View data detail
   public function view($id = NULL) {
    $data['arsip'] = $this->Ars_data_model->get(array('id' => $id));
    $data['title'] = 'Data Arsip';
    $data['main'] = 'ars_data/ars_data_view';
    $this->load->view('manage/layout', $data);
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
      //   redirect('manage/ars_data');
      // }

      $this->Ars_data_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Data Arsip berhasil');
      redirect('manage/ars_data');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/ars_data/edit/' . $id);
    }  
  }

  //-----------------------------Download------------------------------------
  public function download_arsip(){
    $id_arsip   = $_POST['id_arsip'];
    $file_arsip = $_POST['file_arsip'];
    if($file_arsip ==''){
      $this->session->set_flashdata('failed',' File Tidak Ada')== TRUE;
      redirect('manage/arsip/view/'.$id_arsip);
    }else{
      $this->load->helper('download');
      $file = 'uploads/arsip/'.$file_arsip;
      force_download($file, null);
    }
    
  }
}
