<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ars_jenis_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('ars_data/Ars_data_model', 'setting/Setting_model'));
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

    $data['jenis'] = $this->Ars_data_model->get_jenis($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/ars_jenis/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Jenis Arsip Dokumentasi';
    $data['main'] = 'ars_jenis/ars_jenis_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob(){
    if ($_POST == TRUE) {
      $periodActive =  $this->db->query("SELECT period_id FROM period WHERE period_status='1' ")->row_array();
      $majors_id    = $periodActive['period_id'];
      $jenisArsip = $_POST['jenis_arsip'];
      $createDate = date('Y-m-d');
      
      $cpt = count($_POST['jenis_arsip']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['jenis_arsip'] = $jenisArsip[$i];
        $params['create_date'] = $createDate;
        $params['majors_id']  = $majors_id;

        $this->Ars_data_model->add_jenis($params);
      }
    }
    $this->session->set_flashdata('success',' Tambah Jenis Berhasil');
    redirect('manage/ars_jenis');
  }

// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('jenis_arsip', 'Nama Jenis', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('id_jenis')) {
        $params['id_jenis'] = $this->input->post('id_jenis');
      }
      $params['jenis_arsip']  = $this->input->post('jenis_arsip');
      $params['majors_id']    = $this->input->post('majors_id');
      $status                 = $this->Ars_data_model->add_jenis($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Jenis Arsip');
      redirect('manage/ars_jenis');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_jenis')) {
        redirect('manage/ars_jenis/edit/' . $this->input->post('id_jenis'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Ars_data_model->get_jenis(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/ars_jenis');
        } else {
          $data['jenis'] = $object;
        }
      }
      // $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Jenis Arsip';
      $data['main'] = 'ars_jenis/jenis_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $satuan = $this->Ars_data_model->get_satuan(array('id_jenis'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/ars_jenis');
      // }

      $this->Ars_data_model->delete_jenis($id);
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
      $this->session->set_flashdata('success', 'Hapus Jenis berhasil');
      redirect('manage/ars_jenis');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/ars_jenis/edit/' . $id);
    }  
  }
}
