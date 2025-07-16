<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Survei_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('survei/Survei_model', 'student/student_model', 'position/Position_model', 'setting/Setting_model'));
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

    $data['survei'] = $this->Survei_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('manage/survei/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Survei Kepuasan';
    $data['main'] = 'survei/survei_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('survei_nama', 'Nama', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('survei_id')) {
        $params['survei_id'] = $this->input->post('survei_id');
      }

      $params['survei_nama']            = $this->input->post('survei_nama');
      $params['survei_alamat']          = $this->input->post('survei_alamat');
      $params['survei_respon']          = $this->input->post('survei_respon');
      $params['survei_catatan']         = $this->input->post('survei_catatan');
      $params['id_users']               = $this->input->post('id_users');
      $status                           = $this->Survei_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Layanan Tamu dan survei');
      redirect('manage/survei');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      // Edit mode
      if (!is_null($id)) {
        $object = $this->Survei_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/survei');
        } else {
          $data['survei']   = $object;
          $data['position'] = $this->Position_model->get();
        }
      }

      $data['title'] = $data['operation'] . ' Layanan survei';
      $data['main'] = 'survei/survei_add';
      $this->load->view('manage/layout', $data);
    }
  }

// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
      $this->Survei_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Data survei berhasil');
      redirect('manage/survei');
  }

  public function view(){
    $params['id']         = $this->uri->segment('4');

    $data['survei']    = $this->Survei_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url']   = site_url('manage/survei/index');
    $config['suffix']     = '?' . http_build_query($_GET, '', "&");
    
    $data['title']        = 'Detail survei';
    $data['main']         = 'survei/survei_view';
    $this->load->view('manage/layout', $data);
    
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
    $data['survei']     = $this->Survei_model->get($params);
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
    
    // $html = $this->load->view('survei/survei_pdf', $data, TRUE);
    // $data = pdf_create($html, 'Surat Perjalan Dinas'.date('Y-m-d'), TRUE, 'F4', TRUE);
    $filename = 'Surat Perjalan Dinas.pdf';
        
    $this->load->view('survei/survei_pdf', $data);
    
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

  public function report_survei($offset = NULL) {
        
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
		
      $data['survei'] = $this->Survei_model->get($params); 
      $data['title'] = 'Report Surat Cuti';
      $data['main'] = 'survei/report_survei';
      $this->load->view('manage/layout', $data);
  }

  function cetak_survei(){
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
        $data['survei'] = $this->Survei_model->get($params); 
        
        $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();
    
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 
    
        $filename = 'Laporan Data Surat Cuti.pdf';
        
        $this->load->view('survei/survei_report_print', $data);
        
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

  public function excel_rekap() {
      $params = array();
      
      $tanggal_awal   = $this->input->post('tanggal_awal');
      $tanggal_akhir  = $this->input->post('tanggal_akhir');
      $respon_id       = $this->input->post('respon_id');
      $rekap_type     = $this->input->post('rekap_type');
      
      // Kondisi untuk tipe rekap
      if ($rekap_type == '2') { // Per Tanggal Input
          if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
              $params['date_start'] = $tanggal_awal;
              $params['date_end']   = $tanggal_akhir;
          }
      }
      
      // Kondisi untuk student_id
      if ($respon_id != 'all') {
          $params['respon_id'] = $respon_id;
      }
      
      // Memanggil model untuk mendapatkan data surat tugas berdasarkan parameter
      $data['survei'] = $this->Survei_model->get($params);
      
      // Mendapatkan pengaturan lainnya
      $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
      $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
      
      // Memuat view dengan data yang sudah didapatkan
      $this->load->view('survei/excel_rekap', $data);
  }

  public function print_rekap(){
      ob_start();
      
      $params = array();
      
      $tanggal_awal   = $this->input->post('tanggal_awal');
      $tanggal_akhir  = $this->input->post('tanggal_akhir');
      $respon_id       = $this->input->post('respon_id');
      $rekap_type     = $this->input->post('rekap_type');
      
      // Kondisi untuk tipe rekap
      if ($rekap_type == '2') { // Per Tanggal Input
          if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
              $params['date_start'] = $tanggal_awal;
              $params['date_end']   = $tanggal_akhir;
          }
      }
      
      // Kondisi untuk student_id
      if ($respon_id != 'all') {
          $params['respon_id'] = $respon_id;
      }
      
      // Memanggil model untuk mendapatkan data surat tugas berdasarkan parameter
      $data['survei'] = $this->Survei_model->get($params);
      
      // Mendapatkan pengaturan lainnya*
      $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
      $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

      $filename = 'Data Rekap Surat Tugas.pdf';
      $this->load->view('survei/print_rekap',$data);
      
      $html = ob_get_contents();
      ob_end_clean();
      
      require_once('./assets/html2pdf/html2pdf.class.php');
      $pdf = new HTML2PDF('P','A4','en');
      $pdf->WriteHTML($html);
      $pdf->Output($filename);    
  }

}