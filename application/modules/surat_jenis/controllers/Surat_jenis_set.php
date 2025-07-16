<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class surat_jenis_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('surat_jenis/Surat_jenis_model', 'setting/Setting_model'));
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

    $data['jenis'] = $this->Surat_jenis_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/surat_jenis/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Jenis Format Nomor Surat';
    $data['main'] = 'surat_jenis/surat_jenis_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob(){
    if ($_POST == TRUE) {
      $periodActive     =  $this->db->query("SELECT period_id FROM period WHERE period_status='1' ")->row_array();
      $majors_id        = $periodActive['period_id'];
      $jenisNamajenis   = $_POST['nama_jenis'];
      $jenisKodesurat   = $_POST['kode_surat'];
      $id_user          = $this->session->userdata('uid');
      $createDate       = date('Y-m-d');
      
      $cpt = count($_POST['nama_jenis']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['nama_jenis'] = $jenisNamajenis[$i];
        $params['kode_surat'] = $jenisKodesurat[$i];
        $params['tanggal_create'] = $createDate;
        $params['id_user'] = $id_user;

        $this->Surat_jenis_model->add($params);
      }
    }
    $this->session->set_flashdata('success',' Tambah Jenis Surat Managemen Berhasil');
    redirect('manage/surat_jenis');
  }

// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nama_jenis', 'Nama Jenis', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('id_jenis')) {
        $params['id_jenis'] = $this->input->post('id_jenis');
      }
      $params['nama_jenis']     = $_POST['nama_jenis'];
      $params['kode_surat']     = $_POST['kode_surat'];
      $params['id_user']        = $this->session->userdata('uid');
      $params['tanggal_create'] = date('Y-m-d');
      $status                   = $this->Surat_jenis_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Jenis Surat Managemen');
      redirect('manage/surat_jenis');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_jenis')) {
        redirect('manage/surat_jenis/edit/' . $this->input->post('id_jenis'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Surat_jenis_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/surat_jenis');
        } else {
          $data['jenis'] = $object;
        }
      }
      // $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Jenis Surat Managemen';
      $data['main'] = 'surat_jenis/jenis_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $satuan = $this->Surat_jenis_model->get_satuan(array('id_jenis'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/surat_jenis');
      // }

      $this->Surat_jenis_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Jenis Surat Mangemen berhasil');
      redirect('manage/surat_jenis');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/surat_jenis/edit/' . $id);
    }  
  }
}
