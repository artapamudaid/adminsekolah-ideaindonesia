<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Keterangan_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('keterangan/Keterangan_model', 'employees/Employees_model', 'setting/Setting_model'));
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

    $data['keterangan'] = $this->Keterangan_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/keterangan/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Jenis Surat Keterangan';
    $data['main'] = 'keterangan/keterangan_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('keterangan_nama', 'Nama Jenis Keterangan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    $data['kode'] = $this->db->query("SELECT * FROM kode_pengisian order by kode_pengisian_id DESC")->result_array();
    

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('keterangan_id')) {
        $params['keterangan_id'] = $this->input->post('keterangan_id');
      }

      $params['keterangan_nama']  = $this->input->post('keterangan_nama');
      $params['keterangan_isi']   = $this->input->post('keterangan_isi');
      $filename                       = str_replace(' ','_',$params['keterangan_nama']);
      if (!empty($_FILES['keterangan_kop']['name'])) {
          $params['keterangan_kop'] = $this->do_upload($name = 'keterangan_kop', $fileName= $params['keterangan_nama']);
      } 

      $status                     = $this->Keterangan_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Jenis Surat Keterangan');
      redirect('manage/keterangan');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('keterangan_id')) {
        redirect('manage/keterangan/edit/' . $this->input->post('keterangan_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Keterangan_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/keterangan');
        } else {
          $data['keterangan'] = $object;
        }
      }

      $data['title'] = $data['operation'] . ' Jenis Surat Keterangan';
      $data['main'] = 'keterangan/keterangan_add';
      $this->load->view('manage/layout', $data);
    }
  }

// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    $satuan = $this->Keterangan_model->get_satuan(array('id_satuan'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/ars_data');
      // }

      $this->Keterangan_model->delete($id);
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
      redirect('manage/keterangan');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/keterangan/edit/' . $id);
    }  
  }

  public function download($id = NULL){	
		force_download('uploads/arsip/'.$id.'',NULL);
	}

  public function printdata(){
    // ob_start();
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    
    // $data['pulang']   = $this->db->query("SELECT student_nis, student_full_name, class_name, pulang_id, pulang_date, pulang_days, pulang_note FROM pulang JOIN student ON student.student_id = pulang.pulang_student_id JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE pulang_id = '$id'")->row_array();
    $params['id']       = $this->uri->segment('4');
    $data['keterangan'] = $this->Keterangan_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['unit']       = $this->Student_model->get_unit(array('status' => 1));
    
    $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
    $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
    $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
    $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    $data['setting_logo']           = $this->Setting_model->get(array('id' => 6));
    
    $html = $this->load->view('keterangan/keterangan_pdf', $data, TRUE);
    $data = pdf_create($html, 'Surat Perjalan Dinas'.date('Y-m-d'), TRUE, 'F4', TRUE);
    // $filename = 'Surat Perjalan Dinas.pdf';
        
    // $this->load->view('keterangan/keterangan_pdf', $data);
    
    // $html = ob_get_contents();
    // ob_clean();
    
    // require_once('./assets/html2pdf/html2pdf.class.php');
    // $pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15',array(10, 5, 10, 5));
    
    // $pdf->setDefaultFont('arial');
    // $pdf->setTestTdInOnePage(false);
    // $pdf->pdf->SetDisplayMode('fullpage');
    // $pdf->WriteHTML($html);
    // $pdf->Output($filename, '');
  }

  // Setting Upload File Requied
  function do_upload($name=NULL, $fileName=NULL) {
      $this->load->library('upload');

      $config['upload_path'] = FCPATH . 'media/template_kop/';

      /* create directory if not exist */
      if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777, TRUE);
      }

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size'] = '4024';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
        $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
        redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }
}