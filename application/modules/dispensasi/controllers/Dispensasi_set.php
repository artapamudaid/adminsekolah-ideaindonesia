<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dispensasi_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('dispensasi/Dispensasi_model', 'student/Student_model', 'setting/Setting_model'));
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

    $data['student'] = $this->Student_model->get_student();
    $data['dispensasi'] = $this->Dispensasi_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    $config['base_url'] = site_url('manage/dispensasi/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Surat Dispensasi Siswa';
    $data['main'] = 'dispensasi/dispensasi_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('dispensasi_desc', 'Deskripsi', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $now = date('dmy');
    
    //perlu diperbaiki yaa untuk generate number nya....
    // Ambil nomor terakhir dari database atau set nilai awal jika belum ada nomor sebelumnya
    // $last_number = $this->db->select('nomor_id')->order_by('input_date', 'DESC')->limit(1)->get('surat_nomor')->row('nomor_id');
    $maxIdnomor     = $this->db->query("SELECT CONCAT(SUBSTRING(max(nomor_surat), -35, 4)) as maxNo FROM surat_nomor Where tahun = YEAR(now())")->row_array();
		$last_number 		= $maxIdnomor['maxNo'];
      
    if (!$last_number) {
      $last_number = 0;
    }

    // Tambahkan 1 pada nomor terakhir untuk mendapatkan nomor baru
    $new_number = $last_number + 1;

    // Buat format nomor
    $prefix       = 'B';
    $date         = date('dmy');
    $kode         = 'Ma.28.07.03.01';
    $kode2        = 'PP.00.6';
    $year         = date('Y');
    $month        = date('m');
    $nomor_urut   = sprintf('%04d', $new_number);
    $nomor_surat  = "$prefix-$nomor_urut/$kode/$kode2/$month/$year";

    $data['nomor_urut']  = $nomor_urut;
    $data['nomor_surat'] = $nomor_surat;
    $data['student'] = $this->Student_model->get_student();
    
    $data['siswaID']      = $siswaID;
    //sampai sini

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('dispensasi_id')) {
        $params['dispensasi_id'] = $this->input->post('dispensasi_id');
      }
      $dispensasi_student_id = implode(',', $this->input->post('dispensasi_student_id'));

      $params['nomor_surat']            = $this->input->post('nomor_surat');
      $params['tahun']                  = $year;
      $params['dispensasi_date']        = $this->input->post('dispensasi_date');
      $params['dispensasi_time_start']  = $this->input->post('dispensasi_time_start');
      $params['dispensasi_time_end']    = $this->input->post('dispensasi_time_end');
      $params['dispensasi_lokasi']      = $this->input->post('dispensasi_lokasi');
      $params['dispensasi_student_pj']  = $this->input->post('dispensasi_student_pj');
      $params['dispensasi_student_id']  = $dispensasi_student_id;
      $params['dispensasi_desc']        = $this->input->post('dispensasi_desc');
      $params['id_users']               = $this->input->post('id_users');
      
      $this->Dispensasi_model->add($params);
      $this->Dispensasi_model->add_nomor($params);
      // var_dump($params);
      // exit;

      $this->session->set_flashdata('success', $data['operation'] . ' Surat Dispensasi');
      redirect('manage/dispensasi');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('dispensasi_id')) {
        redirect('manage/dispensasi/edit/' . $this->input->post('dispensasi_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Dispensasi_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/dispensasi');
        } else {
          $data['dispensasi'] = $object;
          $data['student'] = $this->Student_model->get_student();
        }
      }

      $data['title'] = $data['operation'] . ' Surat Dispensasi';
      $data['main'] = 'dispensasi/dispensasi_add';
      $this->load->view('manage/layout', $data);
    }
  }

// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    $satuan = $this->Dispensasi_model->get_satuan(array('id_satuan'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/ars_data');
      // }

      $this->Dispensasi_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Data Dispensasi Berhasil');
      redirect('manage/dispensasi');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/dispensasi/edit/' . $id);
    }  
  }

  public function download($id = NULL){	
		force_download('uploads/arsip/'.$id.'',NULL);
	}

  public function printdata($id){
    
    ob_start();

    $data_surat_dispensasi = $this->Dispensasi_model->get(array('id' => $id));
    $cek_jenis = $data_surat_dispensasi['keterangan_jenis'];
    $data['surat_dispensasi'] = $data_surat_dispensasi;

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_nama_kepsek'] = $this->Setting_model->get(array('id' => 12));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));  

    $filename = 'Surat Keterangan Dispensasi.pdf';
    $this->load->view('dispensasi/dispensasi_pdf',$data);
    
    $html = ob_get_contents();
    ob_clean();
    
    require_once('./assets/html2pdf/html2pdf.class.php');
    $pdf = new HTML2PDF('p','A4','en');
    $pdf->WriteHTML($html);
    $pdf->Output($filename); 
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
      $data['dispensasi'] = $this->Dispensasi_model->get($params);
      
      // Mendapatkan pengaturan lainnya
      $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
      $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
      
      // Memuat view dengan data yang sudah didapatkan
      $this->load->view('dispensasi/excel_rekap', $data);
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
      $data['dispensasi'] = $this->Dispensasi_model->get($params);
      
      // Mendapatkan pengaturan lainnya*
      $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
      $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

      $filename = 'Data Rekap Surat Tugas.pdf';
      $this->load->view('dispensasi/print_rekap',$data);
      
      $html = ob_get_contents();
      ob_end_clean();
      
      require_once('./assets/html2pdf/html2pdf.class.php');
      $pdf = new HTML2PDF('L','A4','en');
      $pdf->WriteHTML($html);
      $pdf->Output($filename);    
  }
}
