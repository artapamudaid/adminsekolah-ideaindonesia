<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Surat_keterangan_set extends CI_Controller {

  public function __construct() {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('student/Student_model', 'keterangan/Keterangan_model', 'employees/Employees_model', 'surat_keterangan/Surat_keterangan_model', 'period/Period_model', 'setting/Setting_model', 'logs/Logs_model'));

  }

  // payment view in list
  public function index($offset = NULL, $id =NULL) {
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f']  = $f;
    $siswa      = null;
    $periodID   = null;
    $siswaID    = null;
    $params     = array();

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis'=>$f['r']));
      $siswaID = $siswa['student_id'];
    }

    // Ambil nomor terakhir dari database atau set nilai awal jika belum ada nomor sebelumnya
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
    
    $data['siswaID']      = $siswaID;
    $data['siswa']        = $this->Student_model->get(array('student_id'=>$siswa['student_id'], 'group'=>TRUE));
    
    // $data['periodActive'] = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
    // $data['period']       = $this->Period_model->get($params);
    $data['surat']        = $this->Surat_keterangan_model->get(array('student_id'=>$siswa['student_id'], 'order_by' => 'keterangan_id'));
    $data['suratSum']     = $this->Surat_keterangan_model->get_sum(array('student_id'=>$siswa['student_id']));

    $data['majors']       = $this->Student_model->get_majors();
    $data['keterangan']   = $this->Keterangan_model->get();
    $data['employee']     = $this->Employees_model->get();

    $data['title']        = 'Surat Keterangan Siswa';
    $data['main']         = 'surat_keterangan/surat_keterangan_list';
    $this->load->view('manage/layout', $data);
  } 
  
  public function add(){
    $period = $this->input->post('keterangan_period_id');
    $nis    = $this->input->post('keterangan_student_nis');
		if ($_POST == TRUE) {
      $params['keterangan_period_id']     = $this->input->post('keterangan_period_id');
      $params['keterangan_student_nis']   = $this->input->post('keterangan_student_nis');
			$params['keterangan_student_id']    = $this->input->post('keterangan_student_id');
			$params['keterangan_jenis']         = $this->input->post('keterangan_jenis');
			$params['keterangan_input_date']    = $this->input->post('keterangan_input_date');
			$params['keterangan_employee_id']   = $this->input->post('keterangan_employee_id');
			$params['nomor_surat']              = $this->input->post('nomor_surat');
			$params['tahun']                    = date('Y');

      $this->Surat_keterangan_model->add_nomor($params);
			$this->Surat_keterangan_model->add($params);
		}
		
		$this->session->set_flashdata('success',' Tambah Surat Pengganti kartu Berhasil');
		redirect('manage/surat_keterangan?n='.$period.'&r='.$nis);
	}
	
	public function delete($id = NULL) {
		if ($_POST) {
		  $period = $this->input->post('period_id');
			$nis    = $this->input->post('student_nis');
			$nosur  = $this->input->post('nomor_surat');
			
			$this->Surat_keterangan_model->delete($id, $nosur);
		  $this->session->set_flashdata('success', 'Hapus Surat Pengganti Kartu Berhasil');
			
			redirect('manage/surat_keterangan?n='.$period.'&r='.$nis);
		} elseif (!$_POST) {
		        $this->session->set_flashdata('failed', 'Hapus Surat Pengganti Kartu Gagal');
		        
			redirect('manage/surat_keterangan?n='.$period.'&r='.$nis);
		}
	}

  //-============================================================= DIsposisi ------==

  //add_Lampiran
  public function add_lampiran($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('lampiran_isi', 'Isi Lampiran', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Sunting' :'Tambah' ;

    if ($_POST AND $this->form_validation->run() == TRUE) {
      //1. if dsini tidak dipakai hanya sebatas konfirmasi saja
      if ($this->input->post('lampiran_surat_keterangan_id')) {
        //post tujuan 
        if ($this->input->post('lampiran_id')) {
          $params['lampiran_id']                    = $this->input->post('lampiran_id');
        }
        $nis                                        = $this->input->post('student_nis');
        $params['lampiran_surat_keterangan_id']     = $this->input->post('lampiran_surat_keterangan_id');
        $params['lampiran_isi']                     = $this->input->post('lampiran_isi');

        $this->Surat_keterangan_model->add_lampiran($params);
        $this->session->set_flashdata('success', $data['operation'] . ' Data Lampiran');
        redirect('manage/surat_keterangan?r='.$nis);
      }
    
    } else {
      //2. else disin untuk mengeksekusi edit data, akan tetapi edit data akan mengambil id surat yng nantinya data akan masuk ke tambah disposisi  
      

      //3. perintah Tambah Lampiran diambil dari query keterangan
      if (!is_null($id)) {
        // echo $id;
        // exit;
        $object = $this->Surat_keterangan_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/surat_keterangan');
        } else {
          $data['lampiran'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Lampiran';
      $data['main'] = 'surat_keterangan/lampiran_add';
      $this->load->view('manage/layout', $data);
    }
  }

  public function print_file(){
    // ob_start();
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');


    $params['id']       = $this->uri->segment('4');
    $data['keterangan'] = $this->Surat_keterangan_model->getPrint($params);
    $keterangan         = $data['keterangan'];

    $id_siswa           = $keterangan['keterangan_student_id'];
    $data['student']    = $this->Student_model->get_student(array('id'=>$id_siswa));
    $id_pegawai         = $keterangan['keterangan_employee_id'];
    $data['employee']   = $this->Employees_model->get(array('id'=>$id_pegawai));
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
    $data['setting_nama_kepsek']    = $this->Setting_model->get(array('id' => 12));
    
    $html = $this->load->view('surat_keterangan/surat_keterangan_pdf', $data, TRUE);
    $data = pdf_create($html, 'Surat Perjalan Dinas'.date('Y-m-d'), TRUE, 'A4', TRUE);
    
  }

}