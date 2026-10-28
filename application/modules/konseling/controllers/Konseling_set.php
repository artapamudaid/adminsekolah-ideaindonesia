<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Konseling_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('student/Student_model', 'konseling/Konseling_model', 'period/Period_model', 'setting/Setting_model', 'logs/Logs_model'));
  }

  // payment view in list
  public function index($offset = NULL, $id = NULL)
  {
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f']  = $f;
    $siswa      = null;
    $periodID   = null;
    $siswaID    = null;
    $params     = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']      = $f['n'];
      $periodID                 = $f['n'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];

      $data['periodActive'] = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();

      $data['siswa'] = $this->Student_model->get(array('student_id' => $siswa['student_id'], 'group' => TRUE));

      $data['konseling']      = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'mode' => 'PELANGGARAN', 'order_by' => 'konseling_id'));
      $data['konselingSum']      = $this->Konseling_model->get_sum(array('period_id' => $periodID, 'mode' => 'PELANGGARAN', 'student_id' => $siswa['student_id']));
      $data['pelanggaran']      = $this->Konseling_model->listPelanggaran();
      $data['prestasi']      = $this->Konseling_model->listPrestasi();
      $data['prestasi_siswa']      = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'mode' => 'PRESTASI', 'order_by' => 'konseling_id'));
      $data['rekapPrestasiSiswa']      = $this->Konseling_model->get_sum(array('period_id' => $periodID, 'mode' => 'PRESTASI', 'student_id' => $siswa['student_id']));
      $data['konsultasi']      = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'mode' => 'KONSULTASI', 'order_by' => 'konseling_id'));
    }


    $data['kategori_pelanggaran']  = $this->Konseling_model->kategori_pelanggaran();
    $data['kategori_prestasi']     = $this->Konseling_model->kategori_prestasi();
    $data['period']                = $this->Period_model->get($params);

    $data['title']      = 'Konseling Siswa';
    $data['main']       = 'konseling/konseling_list';
    $this->load->view('manage/layout', $data);
  }

  function add_jenis_pelanggaran()
  {
    if ($_POST == TRUE) {
      $data = array();
      $data['id_kategori']  = $this->input->post('kategori_pelanggaran');
      $data['pelanggaran']  = $this->input->post('nama_pelanggaran');
      $data['poin']         = $this->input->post('poin_pelanggaran');

      $this->db->insert('pelanggaran', $data);

      $period = $this->input->post('period');
      $nis = $this->input->post('nis');
      $this->session->set_flashdata('success', ' Tambah Jenis Pelanggaran Berhasil');
      redirect('manage/konseling?n=' . $period . '&r=' . $nis . '&m=0');
    }
  }

  function add_jenis_prestasi()
  {
    if ($_POST == TRUE) {
      $data = array();
      $data['id_kategori']  = $this->input->post('kategori_prestasi');
      $data['prestasi']  = $this->input->post('nama_prestasi');
      $data['poin']         = $this->input->post('poin_prestasi');

      $this->db->insert('prestasi', $data);

      $period = $this->input->post('period');
      $nis = $this->input->post('nis');
      $this->session->set_flashdata('success', ' Tambah Jenis Prestasi Berhasil');
      redirect('manage/konseling?n=' . $period . '&r=' . $nis . '&m=3');
    }
  }

  public function add()
  {
    if ($_POST == TRUE) {

      $period     = $this->input->post('konseling_period_id');
      $nis        = $this->input->post('konseling_student_nis');
      $idStudent  = $this->input->post('konseling_student_id');
      $mode       = $this->input->post('konseling_mode');

      if ($mode == 0 or $mode == 'PELANGGARAN') {
        $params['konseling_mode'] = 'PELANGGARAN';
      } elseif ($mode == 1 or $mode == 'KONSULTASI') {
        $params['konseling_mode'] = 'KONSULTASI';
      } elseif ($mode == 2 or $mode == 'PRESTASI') {
        $params['konseling_mode'] = 'PRESTASI';
      }

      $params['konseling_date']       = $this->input->post('konseling_date');
      $params['konseling_foul']       = $this->input->post('konseling_foul');
      $params['konseling_action']     = $this->input->post('konseling_action');
      $params['konseling_poin']       = $this->input->post('konseling_poin');
      $params['konseling_note']       = $this->input->post('konseling_note');
      $params['konseling_student_id'] = $this->input->post('konseling_student_id');
      $params['konseling_period_id']  = $this->input->post('konseling_period_id');
      $params['konseling_user_id']    = $this->session->userdata('uid');

      if (!empty($_FILES['bukti']['name'])) {
        $idFile = '';
        $tToday = 1;
        if ($mode == 0 or $mode == 'PELANGGARAN') {
          $tPelanggaran = $this->db->query("SELECT COUNT(konseling_id) AS total FROM konseling WHERE konseling_student_id =  $idStudent AND konseling_mode = 'PELANGGARAN'")->row();
          $idFile = '0' . $params['konseling_foul'];
          $tToday += $tPelanggaran->total;
        } elseif ($mode == 1 or $mode == 'KONSULTASI') {
          $tKonsultasi = $this->db->query("SELECT COUNT(konseling_id) AS total FROM konseling WHERE konseling_student_id =  $idStudent AND konseling_mode = 'KONSULTASI'")->row();
          $tToday += $tKonsultasi->total;
          $idFile = 'konsul0' .  $tToday;
        } elseif ($mode == 2 or $mode == 'PRESTASI') {
          $tPrestasi = $this->db->query("SELECT COUNT(konseling_id) AS total FROM konseling WHERE konseling_student_id =  $idStudent AND konseling_mode = 'PRESTASI'")->row();
          $idFile = '0' . $params['konseling_foul'];
          $tToday += $tPrestasi->total;
        }

        $namaFile = 'bukti_' . $idFile . "_" . $params['konseling_date'] . "_" . $nis . "_0" . $tToday;

        $extension = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $params['bukti'] = $namaFile . "." . $extension;

        $this->do_upload('bukti', $namaFile);
      }

      $this->Konseling_model->add($params);
    }
    $this->session->set_flashdata('success', ' Tambah Konseling Berhasil');
    redirect('manage/konseling?n=' . $period . '&r=' . $nis . '&m=' . $mode);
  }

  function do_upload($name, $fileName)
  {
    $this->load->library('upload');
    $directory = FCPATH . 'uploads/konseling/';

    if (!is_dir($directory)) {
      if (mkdir($directory, 0755, true)) {
      } else {
        echo "Gagal membuat direktori $directory.";
        exit;
      }
    }

    $config['upload_path'] = $directory;
    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0755, TRUE);
    }

    $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
    $config['max_size'] = '1024';
    $fileName = preg_replace("/[^A-Za-z0-9_.-]/", "_", (string) $fileName);
    $config['file_name'] = $fileName;
    $config["file_ext_tolower"] = TRUE;
    $config["remove_spaces"] = TRUE;
    $config["detect_mime"] = TRUE;
    $config["mod_mime_fix"] = TRUE;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }

  public function delete($id = NULL)
  {
    if ($_POST) {
      $period = $this->input->post('period_id');
      $nis    = $this->input->post('student_nis');
      $bukti    = $this->input->post('hapus_bukti');
      if (!empty($bukti)) {
        $this->hapusFile($bukti);
      }

      $this->Konseling_model->delete($id);
      $this->session->set_flashdata('success', 'Hapus Konseling Berhasil');

      redirect('manage/konseling?n=' . $period . '&r=' . $nis);
    } elseif (!$_POST) {
      $this->session->set_flashdata('failed', 'Hapus Konseling Gagal');

      redirect('manage/konseling?n=' . $period . '&r=' . $nis);
    }
  }

  function hapusFile($fileName)
  {
    // Tentukan direktori tempat file berada
    $directory = FCPATH . 'uploads/pelanggaran/';

    // Gabungkan direktori dengan nama file
    $filePath = $directory . $fileName;

    // Cek apakah file ada
    if (file_exists($filePath)) {
      // Hapus file dan cek hasilnya
      if (unlink($filePath)) {
        return "File $fileName berhasil dihapus.";
      } else {
        return "Gagal menghapus file $fileName.";
      }
    } else {
      return "File $fileName tidak ditemukan.";
    }
  }

  function printBook()
  {
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $siswa = null;
    $siswaID = null;
    $periodID = null;
    $params = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']  = $f['n'];
      $periodID             = $f['n'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    $data['siswa'] = $this->Student_model->get(array('period_id' => $periodID, 'student_id' => $siswaID, 'group' => TRUE));

    $data['period']     = $this->Period_model->get($params);
    $data['konseling']        = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswaID, 'mode' => 'PELANGGARAN'));
    $data['konselingSum']      = $this->Konseling_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswaID, 'mode' => 'PELANGGARAN'));

    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Student_model->get_majors();
    // endtotal
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    //$tanggal = date_create($f['d']);
    //$dformat = date_format($tanggal,'dmYHis');
    //$this->barcode2('TAHFIDZ'.$siswa['student_nis'], '');
    $html = $this->load->view('konseling/konseling_cetak_pdf', $data, TRUE);
    $data = pdf_create($html, 'Buku_Konseling_' . $siswa['student_full_name'] . '_' . date('Y-m-d'), TRUE, 'A4', TRUE);
  }

  function printBookKonsultasi()
  {
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $siswa = null;
    $siswaID = null;
    $periodID = null;
    $params = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']  = $f['n'];
      $periodID             = $f['n'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    $data['siswa'] = $this->Student_model->get(array('period_id' => $periodID, 'student_id' => $siswaID, 'group' => TRUE));

    $data['period']     = $this->Period_model->get($params);
    $data['konseling']        = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswaID, 'mode' => 'KONSULTASI'));
    $data['konselingSum']      = $this->Konseling_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswaID, 'mode' => 'KONSULTASI'));

    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Student_model->get_majors();
    // endtotal
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    //$tanggal = date_create($f['d']);
    //$dformat = date_format($tanggal,'dmYHis');
    //$this->barcode2('TAHFIDZ'.$siswa['student_nis'], '');
    $html = $this->load->view('konseling/konseling_cetak_konsultasi_pdf', $data, TRUE);
    $data = pdf_create($html, 'Buku_Konsultasi_' . $siswa['student_full_name'] . '_' . date('Y-m-d'), TRUE, 'A4', TRUE);
  }

  function printBookPrestasi()
  {
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $siswa = null;
    $siswaID = null;
    $periodID = null;
    $params = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']  = $f['n'];
      $periodID             = $f['n'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    $data['siswa'] = $this->Student_model->get(array('period_id' => $periodID, 'student_id' => $siswaID, 'group' => TRUE));

    $data['period']     = $this->Period_model->get($params);
    $data['konseling']        = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswaID, 'mode' => 'PRESTASI'));
    $data['konselingSum']      = $this->Konseling_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswaID, 'mode' => 'PRESTASI'));

    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Student_model->get_majors();
    // endtotal
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    //$tanggal = date_create($f['d']);
    //$dformat = date_format($tanggal,'dmYHis');
    //$this->barcode2('TAHFIDZ'.$siswa['student_nis'], '');
    $html = $this->load->view('konseling/konseling_cetak_prestasi_pdf', $data, TRUE);
    $data = pdf_create($html, 'Buku_Prestasi_' . $siswa['student_full_name'] . '_' . date('Y-m-d'), TRUE, 'A4', TRUE);
  }

  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_konseling/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0755, TRUE);
    }
    $this->upload->initialize($config);

    // CREATE BARCODE GENERATOR
    // Including all required classes
    require_once(APPPATH . 'libraries/barcodegen/BCGFontFile.php');
    require_once(APPPATH . 'libraries/barcodegen/BCGColor.php');
    require_once(APPPATH . 'libraries/barcodegen/BCGDrawing.php');

    // Including the barcode technology
    // Ini bisa diganti-ganti mau yang 39, ato 128, dll, liat di folder barcodegen
    require_once(APPPATH . 'libraries/barcodegen/BCGcode39.barcode.php');

    // Loading Font
    // kalo mau ganti font, jangan lupa tambahin dulu ke folder font, baru loadnya di sini
    $font = new BCGFontFile(APPPATH . 'libraries/font/Arial.ttf', $fontsize);

    // Text apa yang mau dijadiin barcode, biasanya kode produk
    $text = $sparepart_code;

    // The arguments are R, G, B for color.
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $drawException = null;
    try {
      $code = new BCGcode39(); // kalo pake yg code39, klo yg lain mesti disesuaikan
      $code->setScale($scale); // Resolution
      $code->setThickness($thickness); // Thickness
      $code->setForegroundColor($color_black); // Color of bars
      $code->setBackgroundColor($color_white); // Color of spaces
      $code->setFont($font); // Font (or 0)
      $code->parse($text); // Text
    } catch (Exception $exception) {
      $drawException = $exception;
    }

    /* Here is the list of the arguments
    1 - Filename (empty : display on screen)
    2 - Background color */
    $drawing = new BCGDrawing('', $color_white);
    if ($drawException) {
      $drawing->drawException($drawException);
    } else {
      $drawing->setDPI($dpi);
      $drawing->setBarcode($code);
      $drawing->draw();
    }
    // ini cuma labeling dari sisi aplikasi saya, penamaan file menjadi png barcode.
    $filename_img_barcode = $sparepart_code . '_' . $barcode_type . '.png';
    // folder untuk menyimpan barcode
    $drawing->setFilename(FCPATH . 'media/barcode_konseling/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" onchange="get_siswa()">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="0"> Semua Kelas </option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>
				</div>
			</div>';
  }

  function student_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $id_class  = $this->input->post('id_class');
    $dataStudent  = $this->db->query("SELECT * FROM student WHERE majors_majors_id = '$id_majors' AND class_class_id = '$id_class' ORDER BY student_nis ASC")->result_array();

    echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Siswa</label>
		            <select name="m" id="student_id" class="form-control">
                    <option value="">-- Pilih Siswa --</option>
                    <option value="0"> Semua Siswa </option>';
    foreach ($dataStudent as $row) {

      echo '<option value="' . $row['student_id'] . '">';

      echo $row['student_full_name'];

      echo '</option>';
    }
    echo '</select>
				</div>
			</div>';
  }

  public function report()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '' && $q['k'] != '0') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    $data['student']    = $this->Student_model->get($params);

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['student_id'] = $q['m'];
    }

    if (isset($q['j'])) {
      $mode = $q['j'];

      if ($mode == 0 or $mode == 'PELANGGARAN') {
        $params['mode'] = 'PELANGGARAN';
      } elseif ($mode == 1 or $mode == 'KONSULTASI') {
        $params['mode'] = 'KONSULTASI';
      } elseif ($mode == 2 or $mode == 'PRESTASI') {
        $params['mode'] = 'PRESTASI';
      }
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['konseling']    = $this->Konseling_model->get_sum($params);
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Rekap Konseling Siswa';
    $data['main'] = 'konseling/konseling_report';
    $this->load->view('manage/layout', $data);
  }

  public function konseling_excel()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '' && $q['k'] != '0') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['student_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['student']    = $this->Student_model->get($params);
    $data['konseling']    = $this->Konseling_model->get_sum($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $this->load->view('konseling/konseling_xls', $data);
  }
}
