<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('surat_masuk/Surat_masuk_model', 'setting/Setting_model'));
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

    $data['surat_masuk'] = $this->Surat_masuk_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/surat_masuk/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Surat Masuk';
    $data['main'] = 'surat_masuk/surat_masuk_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('no_surat', 'No. Surat', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('id_surat')) {
        $params['id_surat'] = $this->input->post('id_surat');
      }
      $params['no_agenda']       = $this->input->post('no_agenda');
      $params['no_surat']     = $this->input->post('no_surat');
      $params['asal_surat']         = $this->input->post('asal_surat');
      $params['isi']      = $this->input->post('isi');
      $params['kode']         = $this->input->post('kode');
      $params['indeks']       = $this->input->post('indeks');
      $params['tgl_surat']     = $this->input->post('tgl_surat');
      $params['tgl_diterima']         = $this->input->post('tgl_diterima');
      $params['keterangan']      = $this->input->post('keterangan');
      $params['tanggal']        = date('Y-m-d');
      $params['status']         = $this->input->post('status');
      $params['id_users']       = $this->input->post('id_users');
      $status                   = $this->Surat_masuk_model->add($params);

      if (!empty($_FILES['file']['name'])) {
        $paramsupdate['file'] = $this->do_upload($name = 'file', $fileName= $params['file']);
      } 

      if($status==''){
        $paramsupdate['id_surat'] = $this->input->post('id_surat');
      }
      else{
        $paramsupdate['id_surat'] = $status;
      }
      
      $this->Surat_masuk_model->add($paramsupdate);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Satuan Arsip');
      redirect('manage/surat_masuk');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_surat')) {
        redirect('manage/surat_masuk/edit/' . $this->input->post('id_surat'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Surat_masuk_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/surat_masuk');
        } else {
          $data['surat_masuk'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Surat Masuk';
      $data['main'] = 'surat_masuk/surat_masuk_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // Setting Upload File Requied
  function do_upload($name=NULL, $fileName=NULL) {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/surat_masuk/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'pdf|jpg|jpeg|png';
    $config['max_size'] = '2024';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }


// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $satuan = $this->Surat_masuk_model->get(array('id_surat'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/surat_masuk');
      // }

      $this->Surat_masuk_model->delete($id);
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
      redirect('manage/surat_masuk');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/surat_masuk/edit/' . $id);
    }  
  }

//-============================================================= DIsposisi ------==

  //add_disposisi
  public function add_disposisi($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('tujuan', 'Tujuan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Sunting' :'Tambah' ;

    if ($_POST AND $this->form_validation->run() == TRUE) {
      //1. if dsini tidak dipakai hanya sebatas konfirmasi saja
    
    } else {
      //2. else disin untuk mengeksekusi edit data, akan tetapi edit data akan mengambil id surat yng nantinya data akan masuk ke tambah disposisi  
      
      if ($this->input->post('id_surat')) {
        //post tujuan 
        $tujuan                 = implode(',', $this->input->post('tujuan'));
        $params['tujuan']       = $tujuan;
        $params['isi_disposisi']     = $this->input->post('isi_disposisi');
        $params['sifat']         = $this->input->post('sifat');
        $params['batas_waktu']      = $this->input->post('batas_waktu');
        $params['catatan']         = $this->input->post('catatan');
        $params['id_surat']       = $this->input->post('id_surat');
        $params['id_user']     = $this->input->post('id_users');
        $this->Surat_masuk_model->add_disposisi($params);
        $this->session->set_flashdata('success', $data['operation'] . ' Data Disposisi');
        redirect('manage/surat_masuk');
      }

      //3. perintah Tambah Disposisi diambil dari query surat masuk
      if (!is_null($id)) {
        $object = $this->Surat_masuk_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/surat_masuk');
        } else {
          $data['disposisi'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Disposisi';
      $data['main'] = 'surat_masuk/disposisi_add';
      $this->load->view('manage/layout', $data);
    }
  }

  public function surat_history($offset = NULL) {
    $this->load->library('pagination');
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    $data['surat_masuk'] = $this->Surat_masuk_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/surat_masuk/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Surat Masuk (Disposisi)';
    $data['main'] = 'surat_masuk/surat_history';
    $this->load->view('manage/layout', $data);
  }

  //-----------------------------Download------------------------------------
  public function download_sm(){
    $id_surat   = $_POST['id_surat'];
    $file = $_POST['file'];
    if($file ==''){
      $this->session->set_flashdata('failed',' File Tidak Ada')== TRUE;
      redirect('manage/surat_masuk/surat_masuk_list');
    }else{
      $this->load->helper('download');
      $file = 'uploads/surat_masuk/'.$file;
      force_download($file, null);
    }
    
  }

  //============================Print data disposisi===============
  public function printdata(){
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    
    // $data['pulang']   = $this->db->query("SELECT student_nis, student_full_name, class_name, pulang_id, pulang_date, pulang_days, pulang_note FROM pulang JOIN student ON student.student_id = pulang.pulang_student_id JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE pulang_id = '$id'")->row_array();
    $params['id']     = $this->uri->segment('4');
    $data['surat_masuk'] = $this->Surat_masuk_model->get($params);
    
    $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
    $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
    $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
    $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    
    $html = $this->load->view('surat_masuk/disposisi_pdf', $data, TRUE);
    $data = pdf_create($html, 'Disposisi'.date('Y-m-d'), TRUE, 'A4', TRUE);
  }

  public function report_suratmasuk($offset = NULL) {
        
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
		
      $data['sppd'] = $this->Sppd_model->get($params); 
      $data['title'] = 'Report Surat perjalanan dinas';
      $data['main'] = 'sppd/report_sppd';
      $this->load->view('manage/layout', $data);
  }

  function cetak_sppd(){
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
        $data['sppd'] = $this->Sppd_model->get($params); 
        
        $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();
    
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 
    
        $filename = 'Laporan Data SPPD.pdf';
        
        $this->load->view('sppd/sppd_report_print', $data);
        
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

  

  function printRekap(){
      ob_start();
        
      $q = $this->input->get(NULL, TRUE);

      $data['q'] = $q;

      // $param = array();
      $params = array();
      
      $s = $this->input->get(NULL, TRUE);
      $f = $this->input->get(NULL, TRUE);

      $data['s'] = $s;
      $data['f'] = $f;

      $params = array();

      $data['surat_masuk'] = $this->Surat_masuk_model->get($params);
      
      $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();

      $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
      $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
      $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 

      $filename = 'Laporan Data Surat Masuk.pdf';
      
      $this->load->view('surat_masuk/surat_masuk_print', $data);
      
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
}
