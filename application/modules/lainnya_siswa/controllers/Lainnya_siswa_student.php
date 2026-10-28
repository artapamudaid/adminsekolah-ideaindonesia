<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lainnya_siswa_student extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged_student') == NULL) {
      header("Location:" . site_url('student/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('lainnya_siswa/Lainnya_siswa_model', 'student/student_model', 'setting/Setting_model'));
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
    $params['student_id'] = $this->session->userdata('uid_student');

    $data['lainnya_siswa'] = $this->Lainnya_siswa_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('student/lainnya_siswa/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Lainnya';
    $data['main'] = 'lainnya_siswa/fontend/lainnya_siswa_list';
    $this->load->view('student/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $now = date('dmy');
        
    $query = $this->db->query("SELECT MAX(RIGHT(lainnya_siswa_no,2)) AS no_max FROM lainnya_siswa 
    WHERE SUBSTR(lainnya_siswa_no, -8, 6) = '$now'")->row();
    
    if (count(array($query))>0){
        $tmp = ((int)$query->no_max)+1;
        $norut = sprintf("%02s", $tmp);
    } else {
        $norut = "01";
    }

    $date =  date('Y-m');
    $data['lainnya_siswa_no']= 'LS/'.$date.'/'.$now.$norut;
    $data['student'] = $this->student_model->get();

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('lainnya_siswa_id')) {
        $params['lainnya_siswa_id'] = $this->input->post('lainnya_siswa_id');
      }

      $params['lainnya_siswa_no']   = $this->input->post('lainnya_siswa_no');
      $params['student_id']          = $this->input->post('student_id');
      $params['catatan']             = $this->input->post('catatan');
      $params['status']              = 1;
      $params['id_users']            = $this->input->post('id_users');
      $status                        = $this->Lainnya_siswa_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Surat Keterangan Prestasi');
      redirect('student/lainnya_siswa');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      // Edit mode
      if (!is_null($id)) {
        $object = $this->Lainnya_siswa_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('student/lainnya_siswa');
        } else {
          $data['lainnya_siswa'] = $object;
        }
      }

      $data['title'] = $data['operation'] . ' Surat Prestasi';
      $data['main'] = 'lainnya_siswa/fontend/lainnya_siswa_add';
      $this->load->view('student/layout', $data);
    }
  }

// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('student');
    }
      $this->Lainnya_siswa_model->delete($id);
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
      redirect('student/lainnya_siswa');
  }

  public function view(){
    $params['id']            = $this->uri->segment('4');

    $data['lainnya_siswa'] = $this->Lainnya_siswa_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('student/lainnya_siswa/fontend/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Detail Surat prestasi';
    $data['main'] = 'lainnya_siswa/fontend/lainnya_siswa_view';
    $this->load->view('student/layout', $data);
    
  }

  public function download($id = NULL){	
		force_download('uploads/arsip/'.$id.'',NULL);
	}

  public function printdata(){
    ob_start();
    // $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    
    // $data['pulang']   = $this->db->query("SELECT student_nis, student_full_name, class_name, pulang_id, pulang_date, pulang_days, pulang_note FROM pulang JOIN student ON student.student_id = pulang.pulang_student_id JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE pulang_id = '$id'")->row_array();
    $params['id']     = $this->uri->segment('4');
    $data['lainnya_siswa']     = $this->Lainnya_siswa_model->get($params);
    $data['majors']   = $this->Student_model->get_majors();
    $data['unit']     = $this->Student_model->get_unit(array('status' => 1));
    
    $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
    $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
    $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
    $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    $data['setting_nama_kepsek']    = $this->Setting_model->get(array('id' => 12));
    
    // $html = $this->load->view('lainnya_siswa/fontend/lainnya_siswa_pdf', $data, TRUE);
    // $data = pdf_create($html, 'Surat Perjalan Dinas'.date('Y-m-d'), TRUE, 'F4', TRUE);
    $filename = 'Surat Perjalan Dinas.pdf';
        
    $this->load->view('lainnya_siswa/fontend/lainnya_siswa_pdf', $data);
    
    $html = ob_get_contents();
    ob_clean();
    
    require_once('./assets/html2pdf/html2pdf.class.php');
    $pdf = new HTML2PDF('P','F4','en', false, 'ISO-8859-15',array(10, 5, 10, 5));
    
    $pdf->setDefaultFont('arial');
    $pdf->setTestTdInOnePage(false);
    $pdf->pdf->SetDisplayMode('fullpage');
    $pdf->WriteHTML($html);
    $pdf->Output($filename, '');
  }

  public function report_lainnya_siswa($offset = NULL) {
        
      $q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();
		
		if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '' && $q['ds'] != '0') {
			$params['date_start']  = $q['ds'];
			$data['date_start']	= $q['ds'];
		}	
    
    if (isset($q['de']) && !empty($q['de']) && $q['de'] != '' && $q['de'] != '0') {
			$params['date_end']  = $q['de'];
			$data['date_end']	= $q['de'];
		}	
		
      $data['lainnya_siswa'] = $this->Lainnya_siswa_model->get($params); 
      $data['title'] = 'Report Surat Cuti';
      $data['main'] = 'lainnya_siswa/fontend/report_lainnya_siswa';
      $this->load->view('student/layout', $data);
  }

  function cetak_lainnya_siswa(){
        ob_start();
          
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        // $param = array();
        $params = array();
        
        if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '' && $q['ds'] != '0') {
          $params['date_start']  = $q['ds'];
          $data['date_start']	= $q['ds'];
        }	
        
        if (isset($q['de']) && !empty($q['de']) && $q['de'] != '' && $q['de'] != '0') {
          $params['date_end']  = $q['de'];
          $data['date_end']	= $q['de'];
        }	
        $data['lainnya_siswa'] = $this->Lainnya_siswa_model->get($params); 
        
        $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();
    
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 
    
        $filename = 'Laporan Data Surat Cuti.pdf';
        
        $this->load->view('lainnya_siswa/fontend/lainnya_siswa_report_print', $data);
        
        $html = ob_get_contents();
        ob_clean();
        
        require_once('./assets/html2pdf/html2pdf.class.php');
        $pdf = new HTML2PDF('L','A4','en', false, 'ISO-8859-15',array(10, 5, 10, 5));
        
        $pdf->setDefaultFont('arial');
        $pdf->setTestTdInOnePage(false);
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->WriteHTML($html);
        $pdf->Output($filename, '');
  }

  // Setting Upload File Requied
  // function do_upload($name=NULL, $fileName=NULL) {
  //   $this->load->library('upload');

  //   $config['upload_path'] = FCPATH . 'uploads/lainnya_siswa/fontend/';

  //   /* create directory if not exist */
  //   if (!is_dir($config['upload_path'])) {
  //     mkdir($config['upload_path'], 0755, TRUE);
  //   }

  //   $config['allowed_types'] = 'jpg|jpeg|png|pdf';
  //   $config['max_size'] = '1024';
  //   $fileName = preg_replace("/[^A-Za-z0-9_.-]/", "_", (string) $fileName);
  //   $config['file_name'] = $fileName;
  //   $config["file_ext_tolower"] = TRUE;
  //   $config["remove_spaces"] = TRUE;
  //   $config["detect_mime"] = TRUE;
  //   $config["mod_mime_fix"] = TRUE;
  //   $this->upload->initialize($config);

  //   if (!$this->upload->do_upload($name)) {
  //     $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
  //     redirect(uri_string());
  //   }

  //   $upload_data = $this->upload->data();

  //   return $upload_data['file_name'];
  // }

}