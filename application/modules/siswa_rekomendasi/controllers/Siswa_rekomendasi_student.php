<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_rekomendasi_student extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged_student') == NULL) {
      header("Location:" . site_url('student/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('siswa_rekomendasi/Siswa_rekomendasi_model', 'student/student_model', 'setting/Setting_model'));
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

    $data['siswa_rekomendasi'] = $this->Siswa_rekomendasi_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('student/siswa_rekomendasi/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Surat Rekomendasi';
    $data['main'] = 'siswa_rekomendasi/fontend/siswa_rekomendasi_list';
    $this->load->view('student/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $now = date('dmy');
        
    $query = $this->db->query("SELECT MAX(RIGHT(siswa_rekomendasi_no,2)) AS no_max FROM siswa_rekomendasi 
    WHERE SUBSTR(siswa_rekomendasi_no, -8, 6) = '$now'")->row();
    
    if (count(array($query))>0){
        $tmp = ((int)$query->no_max)+1;
        $norut = sprintf("%02s", $tmp);
    } else {
        $norut = "01";
    }

    $date =  date('Y-m');
    $data['siswa_rekomendasi_no']= 'SR/'.$date.'/'.$now.$norut;
    $data['student'] = $this->student_model->get();

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('siswa_rekomendasi_id')) {
        $params['siswa_rekomendasi_id'] = $this->input->post('siswa_rekomendasi_id');
      }

      $params['siswa_rekomendasi_no']   = $this->input->post('siswa_rekomendasi_no');
      $params['student_id']          = $this->input->post('student_id');
      $params['keperluan']           = $this->input->post('keperluan');
      $params['catatan']             = $this->input->post('catatan');
      $params['status']              = 1;
      $params['id_users']            = $this->input->post('id_users');
      $status                        = $this->Siswa_rekomendasi_model->add($params);

      if (!empty($_FILES['upl_surat_rekomendasi']['name'])) {
        $paramsupdate['file'] = $this->do_upload($name = 'upl_surat_rekomendasi', $fileName= $params['catatan']);
      }  
      
      $paramsupdate['siswa_rekomendasi_id'] = $status;
      $this->Siswa_rekomendasi_model->add($paramsupdate);

      $this->session->set_flashdata('success', $data['operation'] . ' Surat Rekomendasi');
      redirect('student/siswa_rekomendasi');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      // Edit mode
      if (!is_null($id)) {
        $object = $this->Siswa_rekomendasi_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('student/siswa_rekomendasi');
        } else {
          $data['siswa_rekomendasi'] = $object;
        }
      }

      $data['title'] = $data['operation'] . ' Surat Rekomendasi';
      $data['main'] = 'siswa_rekomendasi/fontend/siswa_rekomendasi_add';
      $this->load->view('student/layout', $data);
    }
  }

// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
      $this->Siswa_rekomendasi_model->delete($id);
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
      redirect('student/siswa_rekomendasi');
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
    $data['siswa_rekomendasi']     = $this->Siswa_rekomendasi_model->get($params);
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
    
    // $html = $this->load->view('siswa_rekomendasi/fontend/siswa_rekomendasi_pdf', $data, TRUE);
    // $data = pdf_create($html, 'Surat Perjalan Dinas'.date('Y-m-d'), TRUE, 'F4', TRUE);
    $filename = 'Surat Perjalan Dinas.pdf';
        
    $this->load->view('siswa_rekomendasi/fontend/siswa_rekomendasi_pdf', $data);
    
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

  public function report_siswa_rekomendasi($offset = NULL) {
        
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
		
      $data['siswa_rekomendasi'] = $this->Siswa_rekomendasi_model->get($params); 
      $data['title'] = 'Report Surat Cuti';
      $data['main'] = 'siswa_rekomendasi/fontend/report_siswa_rekomendasi';
      $this->load->view('student/layout', $data);
  }

  public function view(){
    $params['id']            = $this->uri->segment('4');

    $data['siswa_rekomendasi'] = $this->Siswa_rekomendasi_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('student/siswa_rekomendasi/fontend/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Detail Surat Keterangan Rekomendasi';
    $data['main'] = 'siswa_rekomendasi/fontend/siswa_rekomendasi_view';
    $this->load->view('student/layout', $data);
    
  }

  function cetak_siswa_rekomendasi(){
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
        $data['siswa_rekomendasi'] = $this->Siswa_rekomendasi_model->get($params); 
        
        $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();
    
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 
    
        $filename = 'Laporan Data Surat Cuti.pdf';
        
        $this->load->view('siswa_rekomendasi/fontend/siswa_rekomendasi_report_print', $data);
        
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
  function do_upload($name=NULL, $fileName=NULL) {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/siswa_rekomendasi/';

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

}