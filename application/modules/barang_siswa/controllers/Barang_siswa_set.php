<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_siswa_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('barang_siswa/Barang_siswa_model', 'barang/Barang_model', 'student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

// User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;
    $data['student'] = $this->Student_model->get_student();

    $params = array();

    $data['barang_siswa'] = $this->Barang_siswa_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('manage/barang_siswa/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Data Permintaan Barang Siswa';
    $data['main'] = 'barang_siswa/barang_siswa_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $now = date('dmy');
        
    $query = $this->db->query("SELECT MAX(RIGHT(barang_siswa_no,2)) AS no_max FROM barang_siswa 
    WHERE SUBSTR(barang_siswa_no, -8, 6) = '$now'")->row();
    
    if (count(array($query))>0){
        $tmp = ((int)$query->no_max)+1;
        $norut = sprintf("%02s", $tmp);
    } else {
        $norut = "01";
    }

    $date =  date('Y-m');
    $data['barang_siswa_no']  = 'PBS-'.$now.$norut;
    $data['student']          = $this->Student_model->get();
    $data['barang']           = $this->Barang_model->get();

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('barang_siswa_id')) {
        $params['barang_siswa_id'] = $this->input->post('barang_siswa_id');
      }

      $params['barang_siswa_no']     = $this->input->post('barang_siswa_no');
      $params['student_id']          = $this->input->post('student_id');
      $params['catatan']             = $this->input->post('catatan');
      $params['id_users']            = $this->input->post('id_users');
      $status                        = $this->Barang_siswa_model->add($params);

      $cpt = count($_POST['barang_id']);
      for ($i = 0; $i < $cpt; $i++) {
          // Mengambil nilai barang_id dan jumlah dari array $_POST
          $barang_id = $_POST['barang_id'][$i];
          $jumlah_permintaan = $_POST['jumlah'][$i];

          // Lakukan query insert ke tabel 'barang_permintaan_siswa'
          $param = [
              'barang_siswa_id' => $status,
              'barang_id' => $barang_id,
              'jumlah' => $jumlah_permintaan
          ];
          $this->db->insert('barang_permintaan_siswa', $param);
          
          // Update stok di tabel 'barang' dengan mengurangi jumlah permintaan
          $this->db->set('barang_stok', 'barang_stok - ' . intval($jumlah_permintaan), false); // Kurangi stok
          $this->db->where('barang_id', $barang_id); // Kondisi update berdasarkan barang_id
          $this->db->update('barang'); // Jalankan update
      }

      $this->session->set_flashdata('success', $data['operation'] . ' Surat Rekomendasi');
      redirect('manage/barang_siswa');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      // Edit mode
      if (!is_null($id)) {
        $object = $this->Barang_siswa_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/barang_siswa');
        } else {
          $data['barang']    = $this->Barang_model->get();
          $data['barang_siswa']    = $object;
        }
      }

      $data['title'] = $data['operation'] . ' Surat Rekomendasi';
      $data['main'] = 'barang_siswa/barang_siswa_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
      $this->Barang_siswa_model->delete($id);

      $this->db->where('barang_siswa_id', $id);
      // Menghapus baris dari tabel 'barang_permintaan_siswa' sesuai dengan kondisi yang diberikan
      $this->db->delete('barang_permintaan_siswa');
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
      $this->session->set_flashdata('success', 'Hapus Data Barang berhasil');
      redirect('manage/barang_siswa');
  }

  public function view(){
    $params['id']            = $this->uri->segment('4');

    $data['barang_siswa'] = $this->Barang_siswa_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $config['base_url'] = site_url('manage/barang_siswa/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Detail Permintaan Barang';
    $data['main'] = 'barang_siswa/barang_siswa_view';
    $this->load->view('manage/layout', $data);
    
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
    $data['barang_siswa']     = $this->Barang_siswa_model->get($params);
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
    
    // $html = $this->load->view('barang_siswa/barang_siswa_pdf', $data, TRUE);
    // $data = pdf_create($html, 'Surat Perjalan Dinas'.date('Y-m-d'), TRUE, 'F4', TRUE);
    $filename = 'Surat Perjalan Dinas.pdf';
        
    $this->load->view('barang_siswa/barang_siswa_pdf', $data);
    
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

  public function report_barang_siswa($offset = NULL) {
        
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
		
      $data['barang_siswa'] = $this->Barang_siswa_model->get($params); 
      $data['title'] = 'Report Surat Cuti';
      $data['main'] = 'barang_siswa/report_barang_siswa';
      $this->load->view('manage/layout', $data);
  }

  function cetak_barang_siswa(){
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
        $data['barang_siswa'] = $this->Barang_siswa_model->get($params); 
        
        $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();
    
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 
    
        $filename = 'Laporan Data Surat Cuti.pdf';
        
        $this->load->view('barang_siswa/barang_siswa_report_print', $data);
        
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

    $config['upload_path'] = FCPATH . 'uploads/siswa_rekomenasi/';

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

  public function excel_rekap() {
      $params = array();
      
      $tanggal_awal   = $this->input->post('tanggal_awal');
      $tanggal_akhir  = $this->input->post('tanggal_akhir');
      $student_id     = $this->input->post('student_id');
      $rekap_type     = $this->input->post('rekap_type');
      
      // Kondisi untuk tipe rekap
      if ($rekap_type == '2') { // Per Tanggal Input
          if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
              $params['date_start'] = $tanggal_awal;
              $params['date_end']   = $tanggal_akhir;
          }
      }
      
      // Kondisi untuk student_id
      if ($student_id != 'all') {
          $params['student_id'] = $student_id;
      }
      
      // Memanggil model untuk mendapatkan data surat tugas berdasarkan parameter
      $data['barang_siswa'] = $this->Barang_siswa_model->get($params);
      
      // Mendapatkan pengaturan lainnya
      $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
      $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
      
      // Memuat view dengan data yang sudah didapatkan
      $this->load->view('barang_siswa/excel_rekap', $data);
  }

  public function print_rekap(){
      ob_start();
      
      $params = array();
      
      $tanggal_awal   = $this->input->post('tanggal_awal');
      $tanggal_akhir  = $this->input->post('tanggal_akhir');
      $student_id    = $this->input->post('student_id');
      $rekap_type     = $this->input->post('rekap_type');
      
      // Kondisi untuk tipe rekap
      if ($rekap_type == '2') { // Per Tanggal Input
          if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
              $params['date_start'] = $tanggal_awal;
              $params['date_end']   = $tanggal_akhir;
          }
      }
      
      // Kondisi untuk student_id
      if ($student_id != 'all') {
          $params['student_id'] = $student_id;
      }
      
      // Memanggil model untuk mendapatkan data surat tugas berdasarkan parameter
      $data['barang_siswa'] = $this->Barang_siswa_model->get($params);
      
      // Mendapatkan pengaturan lainnya*
      $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
      $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

      $filename = 'Data Rekap Surat Tugas.pdf';
      $this->load->view('barang_siswa/print_rekap',$data);
      
      $html = ob_get_contents();
      ob_end_clean();
      
      require_once('./assets/html2pdf/html2pdf.class.php');
      $pdf = new HTML2PDF('L','A4','en');
      $pdf->WriteHTML($html);
      $pdf->Output($filename);    
  }

}