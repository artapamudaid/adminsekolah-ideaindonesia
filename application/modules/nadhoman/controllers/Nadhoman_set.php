<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Nadhoman_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('student/Student_model', 'nadhoman/Nadhoman_model', 'period/Period_model', 'setting/Setting_model', 'kitab/Kitab_model', 'logs/Logs_model'));
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
    $params     = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']      = $f['n'];
      $periodID                 = $f['n'];
    }

    // Santri
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
    }

    $data['siswa'] = $this->Student_model->get(array(
      'student_id' => @$siswa['student_id'],
      'group' => TRUE
    ));

    $data['periodActive']   = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
    $data['period']         = $this->Period_model->get($params);
    $data['kitab']          = $this->Kitab_model->get($params);
    $data['nadhoman']       = $this->Nadhoman_model->get(array(
      'period_id' => $periodID,
      'student_id' => @$siswa['student_id'],
      'order_by' => 'nadhoman_id'
    ));
    $data['nadhomanSum']    = $this->Nadhoman_model->get_sum(array(
      'period_id' => $periodID,
      'student_id' => @$siswa['student_id']
    ));

    $data['majors']     = $this->Student_model->get_majors();

    $data['title']      = 'Nadhoman Siswa';
    $data['main']       = 'nadhoman/nadhoman_list';
    $this->load->view('manage/layout', $data);
  }

  public function add()
  {
    if ($_POST == TRUE) {

      $period = $this->input->post('nadhoman_period_id');
      $nis    = $this->input->post('nadhoman_student_nis');

      $params['nadhoman_date']          = $this->input->post('nadhoman_date');
      $params['nadhoman_new']           = sanitizeStrict($this->input->post('nadhoman_new'));
      $params['nadhoman_note']          = sanitizeStrict($this->input->post('nadhoman_note'));
      $params['nadhoman_student_id']    = $this->input->post('nadhoman_student_id');
      $params['nadhoman_period_id']     = $this->input->post('nadhoman_period_id');
      $params['nadhoman_kitab_id']      = $this->input->post('nadhoman_kitab_id');
      $params['nadhoman_user_id']       = $this->session->userdata('uid');

      $this->Nadhoman_model->add($params);
    }

    $this->session->set_flashdata('success', ' Tambah Setoran Nadhoman Berhasil');
    redirect('manage/nadhoman?n=' . $period . '&r=' . $nis);
  }
  // update disini untuk update menu thafidz
  public function update()
  {
    $period = $this->input->post('period_id');
    $nis = $this->input->post('student_nis');
    $nadhoman_id = $this->input->post('nadhoman_id');

    $nadhoman_date = $this->input->post('nadhoman_date');
    $nadhoman_kitab = $this->input->post('nadhoman_kitab_id');
    $nadhoman_new           = sanitizeStrict($this->input->post('nadhoman_new'));
    $nadhoman_note          = sanitizeStrict($this->input->post('nadhoman_note'));
    $id = ['nadhoman_id' => $nadhoman_id];

    $tabel = 'nadhoman';

    $data = [
      'nadhoman_date' => $nadhoman_date,
      'nadhoman_new' => $nadhoman_new,
      'nadhoman_note' => $nadhoman_note,
      'nadhoman_kitab_id' => $nadhoman_kitab
    ];

    $this->Nadhoman_model->update($tabel, $data, $id);

    $this->session->set_flashdata('success', ' Update Nadhoman Berhasil');
    redirect('manage/nadhoman?n=' . $period . '&r=' . $nis);
  }

  public function delete($id = NULL)
  {
    if ($_POST) {
      $period = $this->input->post('period_id');
      $nis    = $this->input->post('student_nis');

      $this->Nadhoman_model->delete($id);
      $this->session->set_flashdata('success', 'Hapus Nadhoman Berhasil');

      redirect('manage/nadhoman?n=' . $period . '&r=' . $nis);
    } elseif (!$_POST) {
      $this->session->set_flashdata('failed', 'Hapus Nadhoman Gagal');

      redirect('manage/nadhoman');
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
    $periodID = null;
    $params = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']  = $f['n'];
      $periodID             = $f['n'];
    }

    // Santri
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
    }

    $data['siswa'] = $this->Student_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'group' => TRUE));

    $data['period']     = $this->Period_model->get($params);
    $data['nadhoman']        = $this->Nadhoman_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswa['student_id']));
    // $data['nadhomanSum']      = $this->Nadhoman_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswa['student_id']));

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
    // $this->load->view('nadhoman/nadhoman_cetak_pdf', $data);
    $html = $this->load->view('nadhoman/nadhoman_cetak_pdf', $data, TRUE);
    $data = pdf_create($html, 'Buku_Nadhoman_' . $siswa['student_full_name'] . '_' . date('Y-m-d'), TRUE, 'A4', TRUE);
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

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != 'all') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != 'all') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != 'all') {
      $params['class_id']  = $q['c'];
    }

    $data['student']    = $this->Student_model->get($params);

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != 'all') {
      $params['student_id'] = $q['m'];
    }

    if (isset($q['b']) && !empty($q['b']) && $q['b'] != '' && $q['b'] != 'all') {
      $params['kitab_id'] = $q['b'];
    }


    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['nadhoman']   = $this->Nadhoman_model->get_sum($params);
    $data['kitab']      = $this->Kitab_model->get();

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Laporan Nadhoman Siswa';
    $data['main'] = 'nadhoman/nadhoman_report';
    $this->load->view('manage/layout', $data);
  }

  public function nadhoman_excel()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != 'all') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != 'all') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != 'all') {
      $params['class_id']  = $q['c'];
    }

    $data['student']    = $this->Student_model->get($params);

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != 'all') {
      $params['student_id'] = $q['m'];
    }

    if (isset($q['b']) && !empty($q['b']) && $q['b'] != '' && $q['b'] != 'all') {
      $params['kitab_id'] = $q['b'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['nadhoman']    = $this->Nadhoman_model->get_sum($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $this->load->view('nadhoman/nadhoman_xls', $data);
  }
}
