<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Student_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'konseling/Konseling_model', 'setting/Setting_model', 'bulan/Bulan_model', 'bebas/Bebas_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['student_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }



    $data['student']    = $this->Student_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);

    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");


    $data['title'] = 'Siswa';
    $data['main'] = 'student/student_list';
    $this->load->view('manage/layout', $data);
  }

  function get_nis()
  {
    $id_majors   = $this->input->post('id_majors');

    $NisValue = $this->db->query("SELECT MAX(student_nis) as nis FROM student WHERE majors_majors_id = '$id_majors' AND student_status = '1'")->row_array();
    $nis = $NisValue['nis'] + 1;

    echo '<div class="form-group">
			<label>NIS <small data-toggle="tooltip" title="Wajib diisi">(Muncul Otomatis Setelah Unit Sekolah Dipilih)</small></label>
			<input name="student_nis" type="text" class="form-control" value="' . $nis . '" placeholder="NIS Siswa" readonly="">
		</div>';
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select style="width: 200px;" id="c" name="c" class="form-control" required>
    			<option value="">--- Pilih Kelas ---</option>
    			<option value="all">Semua Kelas</option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>';
  }

  function cari_kelas()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select class="form-control" name="pr" id="pr" onchange="this.form.submit()">
				<option value="">-- Pilih Kelas  --</option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }

    echo '</select>';
  }

  function cari_ke_kelas()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select class="form-control" name="c_id" id="c_id" onchange="ambil()">
    			<option value="">-- Ke Kelas  --</option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }

    echo '</select>';

    echo '
            <script>
                function ambil(){
                    var kls = $("#c_id").val();
                    
                    $("#class_id").val(kls);
                }
            </script>
        ';
  }

  function get_kelas()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<div class="form-group"> 
			<label >Kelas *</label>
            <select name="class_class_id" id="class_class_id" class="form-control">
				<option value=""> -- Pilih Kelas -- </option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }

    echo '</select>
            </div>';
  }

  // Add User and Update
  public function add($id = NULL)
  {

    $this->load->library('form_validation');

    if (!$this->input->post('student_id')) {
      $this->form_validation->set_rules(
        'student_nis',
        'NIS',
        'trim|required|xss_clean|is_unique[student.student_nis]',
        [
          'required'   => '%s wajib diisi.',
          'is_unique'  => '%s sudah terdaftar, gunakan yang lain.',
        ]
      );
    } else {
      $this->form_validation->set_rules(
        'student_nis',
        'NIS',
        'trim|required|xss_clean|callback_check_nis_unique'
      );
    }
    $this->form_validation->set_rules('class_class_id', 'Kelas', 'trim|required|xss_clean');
    $this->form_validation->set_rules('student_full_name', 'Nama lengkap', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button position="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('student_id')) {
        $params['student_id'] = $id;
      } else {
        $params['student_input_date'] = date('Y-m-d H:i:s');
        $default = "123456";
        $pass = $this->input->post('student_password');
        if ($pass != '') {
          $params['student_password'] = md5($pass);
        } else {
          $params['student_password'] = md5($default);
        }
      }

      $student_parent_phone = sanitizeStrict($this->input->post('student_parent_phone'));

      $student_parent_phone = str_replace(" ", "", $student_parent_phone);
      $student_parent_phone = str_replace("(", "", $student_parent_phone);
      $student_parent_phone = str_replace(")", "", $student_parent_phone);
      $student_parent_phone = str_replace(".", "", $student_parent_phone);

      if (!preg_match('/[^+0-9]/', trim($student_parent_phone))) {
        if (substr(trim($student_parent_phone), 0, 1) == '+') {
          $hp = trim($student_parent_phone);
        } elseif (substr(trim($student_parent_phone), 0, 1) == '0') {
          $hp = '+62' . substr(trim($student_parent_phone), 1);
        } elseif (substr(trim($student_parent_phone), 0, 2) == '62') {
          $hp = '+' . trim($student_parent_phone);
        } elseif (substr(trim($student_parent_phone), 0, 1) == '8') {
          $hp = '+62' . trim($student_parent_phone);
        } else {
          $hp = '';
        }
      }

      $studentNis = sanitizeStrict($this->input->post('student_nis'));
      $studentNisn = sanitizeStrict($this->input->post('student_nisn'));
      $studentFullName = sanitizeStrict($this->input->post('student_full_name'));
      $studentAddress = sanitizeStrict($this->input->post('student_address'));
      $studentClassId = sanitizeStrict($this->input->post('class_class_id'));
      $studentMajorsId = sanitizeStrict($this->input->post('majors_majors_id'));

      $redirectUrl = 'student/student_set/add';
      $colWhere = array(
        'student_nis' => $studentNis,
        'class_class_id' => $studentClassId,
        'majors_majors_id' => $studentMajorsId
      );
      if ($id != NULL) {
        $colWhere['student_id !='] = $id;
        $redirectUrl = 'student/student_set/edit/' . $id;
      }

      if ($this->General_model->checkDataExists("student", $colWhere)) {
        $this->session->set_flashdata('error', 'Data sudah ada');
        redirect($redirectUrl);
        exit;
      }

      $params['student_nis'] = $studentNis;
      $params['student_nisn'] = $studentNisn;
      $params['student_gender'] = sanitizeStrict($this->input->post('student_gender'));
      $params['student_phone'] = sanitizeStrict($this->input->post('student_phone'));
      $params['student_hobby'] = sanitizeStrict($this->input->post('student_hobby'));
      $params['class_class_id'] = sanitizeStrict($this->input->post('class_class_id'));
      $params['majors_majors_id'] = sanitizeStrict($this->input->post('majors_majors_id'));
      $params['student_last_update'] = date('Y-m-d H:i:s');
      $params['student_full_name'] = $studentFullName;
      $params['student_born_place'] = sanitizeStrict($this->input->post('student_born_place'));
      $params['student_born_date'] = sanitizeStrict($this->input->post('student_born_date'));
      $params['student_address'] = $studentAddress;
      $params['student_name_of_mother'] = sanitizeStrict($this->input->post('student_name_of_mother'));
      $params['student_name_of_father'] = sanitizeStrict($this->input->post('student_name_of_father'));
      $params['student_parent_phone'] = $hp;
      $params['student_status'] = sanitizeStrict($this->input->post('student_status'));
      $status = $this->Student_model->add($params);

      if (!empty($_FILES['student_img']['name'])) {
        $paramsupdate['student_img'] = $this->do_upload($name = 'student_img', $fileName = $params['student_full_name']);
      }

      $paramsupdate['student_id'] = $status;
      $this->Student_model->add($paramsupdate);

      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Student',
          'log_action' => $data['operation'],
          'log_info' => 'ID:' . $status . ';Name:' . $this->input->post('student_full_name')
        )
      );

      $this->session->set_flashdata('success', $data['operation'] . ' Siswa Berhasil');
      redirect('manage/student');
    } else {
      if ($this->input->post('student_id')) {
        redirect('manage/student/edit/' . $this->input->post('student_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_student(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/student');
        } else {
          $data['student'] = $object;
        }
        $id_unit = $object['majors_majors_id'];
        $data['class'] = $this->db->query("SELECT * FROM class WHERE class.majors_majors_id = '$id_unit'")->result_array();
      }
      $data['setting_level'] = $this->Setting_model->get(array('id' => 7));
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Siswa';
      $data['main'] = 'student/student_add';
      $this->load->view('manage/layout', $data);
    }
  }

  public function check_nis_unique($nis)
  {
    $id = $this->input->post('student_id'); // ID dari form, bisa kosong kalau tambah

    $this->db->where('student_nis', $nis);
    if ($id) {
      $this->db->where('student_id !=', $id); // hindari baris milik diri sendiri
    }

    $query = $this->db->get('student');

    if ($query->num_rows() > 0) {
      $this->form_validation->set_message('check_nis_unique', 'NIS sudah digunakan oleh siswa lain.');
      return FALSE;
    }

    return TRUE;
  }

  // View data detail
  public function view($id = NULL)
  {
    $data['student'] = $this->Student_model->get(array('id' => $id));

    $dataPeriod = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
    $periodID = $dataPeriod['period_id'];

    $data['konseling']      = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'mode' => 'PELANGGARAN', 'order_by' => 'konseling_id'));
    $data['prestasi_siswa'] = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'mode' => 'PRESTASI', 'order_by' => 'konseling_id'));
    $data['konsultasi']     = $this->Konseling_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'mode' => 'KONSULTASI', 'order_by' => 'konseling_id'));

    $data['title'] = 'Siswa';
    $data['main'] = 'student/student_view';
    $this->load->view('manage/layout', $data);
  }

  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    if ($_POST) {

      $bulan = $this->Bulan_model->get(array('student_id' => $this->input->post('student_id')));
      $bebas = $this->Bebas_model->get(array('student_id' => $this->input->post('student_id')));

      if (count($bulan) > 0 or count($bebas) > 0) {
        $this->session->set_flashdata('failed', 'Siswa tidak dapat dihapus');
        redirect('manage/student');
      }

      $this->Student_model->delete($this->input->post('student_id'));

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
      $this->session->set_flashdata('success', 'Hapus Siswa berhasil');
      redirect('manage/student');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/student/edit/' . $id);
    }
  }

  // Class view in list
  public function clasess($offset = NULL)
  {
    $this->load->library('pagination');

    $data['class'] = $this->Student_model->get_class(array('limit' => 10, 'offset' => $offset));
    $data['title'] = 'Daftar Kelas';
    $data['main'] = 'student/class_list';
    $config['total_rows'] = count($this->Student_model->get_class());
    $this->pagination->initialize($config);

    $this->load->view('manage/layout', $data);
  }

  function do_upload_xls($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/xls_tmp/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'xls|xlsx|csv';
    $config['max_size'] = '10240';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }

  // Setting Upload File Requied
  function do_upload($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/student/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
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


  // Add User_customer and Update
  public function add_class($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('class_name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('class_id')) {
        $params['class_id'] = $this->input->post('class_id');
      }
      $params['class_name'] = $this->input->post('class_name');
      $status = $this->Student_model->add_class($params);


      $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Kelas');
      redirect('manage/student/add');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('class_id')) {
        redirect('manage/student/class/edit/' . $this->input->post('class_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_ket(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/student/class');
        } else {
          $data['class'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Keterangan Kelas';
      $data['main'] = 'manage/student/class_add';
      $this->load->view('manage/layout', $data);
    }
  }

  public function import_student()
  {
    //redirect('manage/student/import');

    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Import Data Siswa';
    $data['main']       = 'student/student_import';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function do_import_old()
  {
    $pass_default = '123456';

    if (isset($_FILES["file"]["name"])) {
      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {
        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

        ini_set('memory_limit', '1024M'); // Alokasikan memory sementara untuk baca file excel

        $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($pathFile);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $reader->setReadDataOnly(true); // Baca data saja tanpa style untuk menghemat memory
        $spreadsheet = $reader->load($pathFile);
        $worksheet = $spreadsheet->getActiveSheet();

        //Ambil semua NIS dari DB sekali saja
        $existingNis = $this->db->select('student_nis')->get('student')->result_array();
        $existingNis = array_flip(array_column($existingNis, 'student_nis'));

        $numrow = 1;
        $imported = 0;
        $duplicated = 0;

        foreach ($worksheet->getRowIterator() as $rowObj) {
          $cellIterator = $rowObj->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(false);

          $row = [];
          foreach ($cellIterator as $cell) {
            $row[$cell->getColumn()] = $cell->getValue();
          }

          $student_nis          = isset($row['A']) ? trim($row['A']) : ''; // required
          $student_nisn         = isset($row['B']) ? trim($row['B']) : '';
          $student_full_name    = isset($row['C']) ? trim($row['C']) : ''; // required
          $class_class_id       = isset($row['D']) ? (int)trim($row['D']) : 0;
          $majors_majors_id     = isset($row['E']) ? (int)trim($row['E']) : 0;
          $student_parent_phone = isset($row['F']) ? trim($row['F']) : '';
          $gender               = isset($row['G']) ? trim($row['G']) : '';
          $tempat_lahir         = isset($row['H']) ? trim($row['H']) : '';
          $tgl_lahir            = isset($row['I']) ? trim($row['I']) : '';

          // Konversi nilai tgl lahir jika formatnya numerical serial excel
          if (is_numeric($tgl_lahir) && !empty($tgl_lahir)) {
            try {
              $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_lahir)->format('Y-m-d');
            } catch (\Exception $e) {
            }
          }

          $hobi                 = isset($row['J']) ? trim($row['J']) : '';
          $student_phone        = isset($row['K']) ? trim($row['K']) : '';
          $alamat               = isset($row['L']) ? trim($row['L']) : '';
          $ayah                 = isset($row['M']) ? trim($row['M']) : '';
          $ibu                  = isset($row['N']) ? trim($row['N']) : '';

          if ($numrow > 2) {
            // Validasi awal
            if (
              empty($student_nis) ||
              empty($student_full_name) ||
              $class_class_id === 0 ||
              $majors_majors_id === 0
            ) {
              $numrow++;
              continue;
            }

            // Cek duplikat via array_flip (lebih cepat daripada query per row)
            if (isset($existingNis[$student_nis])) {
              $duplicated++;
              $numrow++;
              continue;
            }

            $timestamp = date('Y-m-d H:i:s');

            $data = [
              'student_nis'        => trim($student_nis),
              'student_full_name'  => $student_full_name,
              'student_password'   => md5($pass_default), // bisa ganti password_hash
              'class_class_id'     => $class_class_id,
              'majors_majors_id'   => $majors_majors_id,
              'student_input_date' => $timestamp,
              'student_last_update' => $timestamp
            ];

            if (!empty($student_parent_phone)) {
              $hp = $this->phone_to_wa($student_parent_phone);
              $data['student_parent_phone'] = str_replace("'", "", $hp);
            }
            if (!empty($student_nisn)) {
              $data['student_nisn'] = str_replace("'", "", $student_nisn);
            }
            if (!empty($gender)) {
              $data['student_gender'] = $gender;
            }
            if (!empty($tempat_lahir)) {
              $data['student_born_place'] = $tempat_lahir;
            }
            if (!empty($tgl_lahir)) {
              $data['student_born_date'] = $tgl_lahir;
            }
            if (!empty($student_phone)) {
              $data['student_phone'] = $this->phone_to_wa($student_phone);
            }
            if (!empty($hobi)) {
              $data['student_hobby'] = $hobi;
            }
            if (!empty($alamat)) {
              $data['student_address'] = $alamat;
            }
            if (!empty($ayah)) {
              $data['student_name_of_father'] = $ayah;
            }
            if (!empty($ibu)) {
              $data['student_name_of_mother'] = $ibu;
            }

            // Insert ke DB
            $this->db->insert('student', $data);

            // Tambahkan ke daftar existing supaya row selanjutnya tahu
            $existingNis[$student_nis] = true;

            $imported++;
          }
          $numrow++;
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        unlink($pathFile);

        // Flash message akumulatif
        $this->session->set_flashdata(
          'success',
          "$imported siswa berhasil diimport, $duplicated duplikat diabaikan."
        );
      }
    }
    redirect('manage/student');
  }

  public function do_import()
  {
    // 1. Validasi keberadaan file
    if (!isset($_FILES['file']['name']) || $_FILES['file']['name'] === '') {
      $this->session->set_flashdata('failed', 'Tidak ada file yang diunggah.');
      redirect('manage/student/import_student');
      return;
    }

    // 1b. Validasi ekstensi
    $fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, ['xls', 'xlsx'])) {
      $this->session->set_flashdata('failed', 'Format file tidak valid. Hanya .xls dan .xlsx yang diperbolehkan.');
      redirect('manage/student/import_student');
      return;
    }

    $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));
    if (!$file) {
      $this->session->set_flashdata('failed', 'Gagal mengupload file.');
      redirect('manage/student/import_student');
      return;
    }

    $pathFile = FCPATH . 'uploads/xls_tmp/' . $file;
    $reader = IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($pathFile);
    $sheet    = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    // 2. Validasi struktur kolom (baris ke-1 sebagai header)
    $expectedHeaders = ['A' => 'NIS SISWA', 'B' => 'NISN SISWA', 'C' => 'NAMA SISWA', 'D' => 'ID KELAS', 'E' => 'ID UNIT'];
    $headerRow       = isset($sheet[1]) ? $sheet[1] : [];
    foreach ($expectedHeaders as $col => $expected) {
      $actual = isset($headerRow[$col]) ? trim($headerRow[$col]) : '';
      if (strcasecmp($actual, $expected) !== 0) {
        unlink($pathFile);
        $this->session->set_flashdata('failed', "Template tidak sesuai. Kolom $col harus '$expected', ditemukan '$actual'. Gunakan template resmi.");
        redirect('manage/student/import_student');
        return;
      }
    }

    // Kumpulkan semua NIS dari file excel untuk membatasi query (menghindari memory limit)
    $uploaded_nis = [];
    $nr = 1;
    foreach ($sheet as $row) {
      if ($nr <= 2) {
        $nr++;
        continue;
      }
      $nis = isset($row['A']) ? trim($row['A']) : '';
      if ($nis !== '') {
        $uploaded_nis[] = str_replace("'", '', $nis);
      }
      $nr++;
    }
    $uploaded_nis = array_unique($uploaded_nis);

    // Pre-load data existing NIS berdasarkan yang ada di excel saja
    $existingNis = [];
    if (!empty($uploaded_nis)) {
      $chunks = array_chunk($uploaded_nis, 1000);
      foreach ($chunks as $chunk) {
        $this->db->select('student_nis');
        $this->db->from('student');
        $this->db->where_in('student_nis', $chunk);
        $res = $this->db->get()->result_array();
        foreach ($res as $r) {
          $existingNis[$r['student_nis']] = true;
        }
      }
    }

    $allMajors = [];
    foreach ($this->db->select('majors_id, sekolah_id')->where('majors_status', 1)->get('majors')->result_array() as $m) {
      $allMajors[(int) $m['majors_id']] = $m;
    }

    $allClasses = [];
    foreach ($this->db->select('class_id')->get('class')->result_array() as $c) {
      $allClasses[(int) $c['class_id']] = true;
    }
    $pass_default = '123456';
    $numrow       = 1;
    $valid_data   = [];
    $error_rows   = [];

    foreach ($sheet as $row) {
      if ($numrow <= 2) {
        $numrow++;
        continue;
      } // skip header rows

      $student_nis          = isset($row['A']) ? trim($row['A']) : '';
      $student_nisn         = isset($row['B']) ? trim($row['B']) : '';
      $student_full_name    = isset($row['C']) ? trim($row['C']) : '';
      $kelas_id             = isset($row['D']) ? trim($row['D']) : '';
      $unit_id              = isset($row['E']) ? trim($row['E']) : '';
      $student_parent_phone = isset($row['F']) ? trim($row['F']) : '';
      $gender               = isset($row['G']) ? trim($row['G']) : '';
      $tempat_lahir         = isset($row['H']) ? trim($row['H']) : '';
      $tgl_lahir            = isset($row['I']) ? trim($row['I']) : '';
      $hobi                 = isset($row['J']) ? trim($row['J']) : '';
      $student_phone        = isset($row['K']) ? trim($row['K']) : '';
      $alamat               = isset($row['L']) ? trim($row['L']) : '';
      $ayah                 = isset($row['M']) ? trim($row['M']) : '';
      $ibu                  = isset($row['N']) ? trim($row['N']) : '';

      // Skip baris benar-benar kosong
      if ($student_nis === '' && $student_full_name === '' && $kelas_id === '' && $unit_id === '') {
        $numrow++;
        continue;
      }

      $errors = [];

      // 3. Validasi field wajib
      if ($student_nis === '')       $errors[] = 'NIS kosong';
      if ($student_full_name === '') $errors[] = 'Nama Lengkap kosong';
      if ($unit_id === '')           $errors[] = 'ID Unit kosong';
      if ($kelas_id === '')          $errors[] = 'ID Kelas kosong';

      // 4. Validasi relasi FK (lookup by ID)
      $majors_id = 0;
      $class_id  = 0;
      $sekolah_id = '';
      if ($unit_id !== '') {
        $unitKey = (int) $unit_id;
        if (isset($allMajors[$unitKey])) {
          $majors_id = $unitKey;
          $sekolah_id = $allMajors[$unitKey]['sekolah_id'];
        } else {
          $errors[] = "ID Unit '$unit_id' tidak ditemukan di database";
        }
      }
      if ($kelas_id !== '') {
        $kelasKey = (int) $kelas_id;
        if (isset($allClasses[$kelasKey])) {
          $class_id = $kelasKey;
        } else {
          $errors[] = "ID Kelas '$kelas_id' tidak ditemukan di database";
        }
      }

      // 5. Validasi duplikat NIS ke database
      if ($student_nis !== '' && isset($existingNis[$student_nis])) {
        $errors[] = "NIS '$student_nis' sudah terdaftar di database";
      }

      if (!empty($errors)) {
        $error_rows[] = ['baris' => $numrow, 'nis' => $student_nis, 'nama' => $student_full_name, 'keterangan' => implode('; ', $errors)];
        $numrow++;
        continue;
      }

      $timestamp = date('Y-m-d H:i:s');
      $data = [
        'student_nis'            => str_replace("'", '', $student_nis),
        'student_full_name'      => $student_full_name,
        'student_password'       => md5($pass_default),
        'class_class_id'         => $class_id,
        'majors_majors_id'       => $majors_id,
        'student_input_date'     => $timestamp,
        'student_last_update'    => $timestamp,
        'student_nisn'           => $student_nisn !== '' ? str_replace("'", '', $student_nisn) : null,
        'student_parent_phone'   => $student_parent_phone !== '' ? str_replace("'", '', $this->phone_to_wa($student_parent_phone)) : '',
        'student_gender'         => $gender !== '' ? $gender : '',
        'student_born_place'     => $tempat_lahir !== '' ? $tempat_lahir : '',
        'student_born_date'      => $tgl_lahir !== '' ? $tgl_lahir : null,
        'student_hobby'          => $hobi !== '' ? $hobi : '',
        'student_phone'          => $student_phone !== '' ? str_replace("'", '', $this->phone_to_wa($student_phone)) : '',
        'student_address'        => $alamat !== '' ? $alamat : '',
        'student_name_of_father' => $ayah !== '' ? $ayah : '',
        'student_name_of_mother' => $ibu !== '' ? $ibu : '',
        'sekolah_id'             => $sekolah_id,
      ];

      $valid_data[] = $data;
      // Tandai agar baris berikutnya tidak duplikat dalam file yang sama
      $existingNis[$student_nis] = true;

      $numrow++;
    }

    unlink($pathFile);

    // 6. Insert batch & pelaporan
    $imported = 0;
    if (!empty($valid_data)) {
      $this->db->insert_batch('student', $valid_data);
      $imported = count($valid_data);
    }

    if ($imported > 0) {
      $msg = "$imported siswa berhasil diimport.";
      if (!empty($error_rows)) {
        $msg .= ' Namun ada ' . count($error_rows) . ' baris gagal karena error.';
      }
      $this->session->set_flashdata('success', $msg);
    } else {
      if (!empty($error_rows)) {
        $this->session->set_flashdata('failed', 'Tidak ada siswa yang diimport. Terdapat error pada file Excel.');
      } else {
        $this->session->set_flashdata('failed', 'Tidak ada data valid yang ditemukan untuk diimport.');
      }
    }

    if (!empty($error_rows)) {
      $this->session->set_flashdata('import_error_rows', $error_rows);
    }

    redirect('manage/student/import_student');
  }

  public function import()
  {
    if ($_POST) {
      $rows = explode("\n", $this->input->post('rows'));
      $success = 0;
      $failled = 0;
      $exist = 0;
      $nis = '';
      foreach ($rows as $row) {
        $exp = explode("\t", $row);
        $count = (majors() == 'senior') ? 14 : 13;
        if (count($exp) != $count) continue;
        $nis = trim($exp[0]);
        $ttl = trim($exp[5]);
        $date = str_replace('-', '', $ttl);
        $arr = [
          'student_nis' => trim($exp[0]),
          'student_nisn' => trim($exp[1]),
          'student_password' => md5(date('dmY', strtotime($date))),
          'student_full_name' => trim($exp[2]),
          'student_gender' => trim($exp[3]),
          'student_born_place' => trim($exp[4]),
          'student_born_date' => trim($exp[5]),
          'student_hobby' => trim($exp[6]),
          'student_phone' => trim($exp[7]),
          'student_address' => trim($exp[8]),
          'student_name_of_mother' => trim($exp[9]),
          'student_name_of_father' => trim($exp[10]),
          'student_parent_phone' => trim($exp[11]),
          'class_class_id' => trim($exp[12]),
          'majors_majors_id' => (majors() == 'senior') ? trim($exp[13]) : NULL,
          'student_input_date' => date('Y-m-d H:i:s'),
          'student_last_update' => date('Y-m-d H:i:s')
        ];
        $class = $this->Student_model->get_class(array('id' => trim($exp[12])));
        $majors = $this->Student_model->get_majors(array('id' => trim($exp[13])));
        $check = $this->db
          ->where('student_nis', trim($exp[0]))
          ->count_all_results('student');
        if ($check == 0) {
          if (trim($exp[12]) == NULL or is_null($class)) {
            $this->session->set_flashdata('failed', 'ID Kelas tidak ada');
            redirect('manage/student/import');
          } else if ($this->db->insert('student', $arr)) {
            $success++;
          } else {
            $failled++;
          }
        } else {
          $exist++;
        }
      }
      $msg = 'Sukses : ' . $success . ' baris, Gagal : ' . $failled . ', Duplikat : ' . $exist;
      $this->session->set_flashdata('success', $msg);
      redirect('manage/student/import');
    } else {
      $data['title'] = 'Import Data Siswa';
      $data['main'] = 'student/student_upload';
      $data['action'] = site_url(uri_string());
      $this->load->view('manage/layout', $data);
    }
  }

  private function phone_to_wa($student_parent_phone = null)
  {
    $student_parent_phone = str_replace("'", "", $student_parent_phone);
    $student_parent_phone = str_replace(" ", "", $student_parent_phone);
    $student_parent_phone = str_replace("(", "", $student_parent_phone);
    $student_parent_phone = str_replace(")", "", $student_parent_phone);
    $student_parent_phone = str_replace(".", "", $student_parent_phone);
    $student_parent_phone = str_replace("-", "", $student_parent_phone);

    if (!preg_match('/[^+0-9]/', trim($student_parent_phone))) {
      if (substr(trim($student_parent_phone), 0, 1) == '+') {
        $hp = trim($student_parent_phone);
      } elseif (substr(trim($student_parent_phone), 0, 1) == '0') {
        $hp = '+62' . substr(trim($student_parent_phone), 1);
      } elseif (substr(trim($student_parent_phone), 0, 2) == '62') {
        $hp = '+' . trim($student_parent_phone);
      } elseif (substr(trim($student_parent_phone), 0, 1) == '8') {
        $hp = '+62' . trim($student_parent_phone);
      } else {
        $hp = '+' . trim($student_parent_phone);
      }
    }
    return $hp;
  }

  public function update_whatsapp()
  {
    //redirect('manage/student/import');

    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Update No. Whatsapp Ortu Siswa';
    $data['main']       = 'student/update_whatsapp';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download_wa()
  {
    $params = array();

    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $params['class_id']  = $id_kelas;
      $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    $this->load->view('student/whatsapp_student', $data);
  }

  public function update_phone()
  {
    if (isset($_FILES["file"]["name"])) {
      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

        ini_set('memory_limit', '1024M'); // Alokasikan memori sementara
        $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($pathFile);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $reader->setReadDataOnly(true); // Baca data tanpa style format
        $spreadsheet = $reader->load($pathFile);
        $worksheet = $spreadsheet->getActiveSheet();

        $numrow = 1;

        foreach ($worksheet->getRowIterator() as $rowObj) {
          $cellIterator = $rowObj->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(false);

          $row = [];
          foreach ($cellIterator as $cell) {
            $row[$cell->getColumn()] = $cell->getValue();
          }

          $student_nis            = isset($row['A']) ? trim($row['A']) : '';
          $student_address        = isset($row['E']) ? trim($row['E']) : '';
          $student_name_of_father = isset($row['F']) ? trim($row['F']) : '';
          $student_name_of_mother = isset($row['G']) ? trim($row['G']) : '';
          $student_parent_phone   = isset($row['H']) ? trim($row['H']) : '';

          if ($numrow > 2) {

            $student_nis = str_replace("'", "", $student_nis);

            $q = $this->db->query("SELECT COUNT(student_nis) as nisNum FROM student
          WHERE student_nis = '$student_nis'")->row_array();

            $nisNum = $q['nisNum'];

            if (empty($student_nis) || $nisNum == 0) {
              $this->session->set_flashdata('failed', 'Duplikasi No. Induk Siswa');
            } else {

              $student_nis            = str_replace("'", "", $student_nis);
              $student_parent_phone   = str_replace("'", "", $student_parent_phone);

              $hp = $this->phone_to_wa($student_parent_phone);

              $data = array(
                'student_address'         =>  $student_address,
                'student_name_of_father'  =>  $student_name_of_father,
                'student_name_of_mother'  =>  $student_name_of_mother,
                'student_parent_phone'    =>  $hp,
              );

              // var_dump($data);
              $this->db->where('student_nis', $student_nis);
              $this->db->update('student', $data);
            }
          }
          $numrow++;
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        unlink($pathFile);

        $this->session->set_flashdata('success', 'Update No. Whatsapp Ortu Siswa Berhasil');
      }
    }

    redirect('manage/student');
  }

  function rpw($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('student_password', 'Password', 'trim|required|xss_clean|min_length[6]');
    $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|xss_clean|min_length[6]|matches[student_password]');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    if ($_POST and $this->form_validation->run() == TRUE) {
      $id = $this->input->post('student_id');
      $default = "123456";
      $pass = $this->input->post('student_password');
      if ($pass != '') {
        $params['student_password'] = md5($pass);
      } else {
        $params['student_password'] = md5($default);
      }
      $status = $this->Student_model->change_password($id, $params);

      $this->session->set_flashdata('success', 'Reset Password Berhasil');
      redirect('manage/student');
    } else {
      if ($this->Student_model->get(array('id' => $id)) == NULL) {
        redirect('manage/student');
      }
      $data['student'] = $this->Student_model->get(array('id' => $id));
      $data['title'] = 'Reset Password';
      $data['main'] = 'student/change_pass';
      $this->load->view('manage/layout', $data);
    }
  }

  public function download()
  {
    while (ob_get_level()) {
      ob_end_clean();
    }

    // Buat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Template Siswa');

    /*
    |--------------------------------------------------------------------------
    | HEADER KOLOM (sesuaikan dengan kebutuhan import Anda)
    |--------------------------------------------------------------------------
    */
    $headers = [
      'A1' => 'NIS SISWA',
      'B1' => 'NISN SISWA',
      'C1' => 'NAMA SISWA',
      'D1' => 'ID KELAS',
      'E1' => 'ID UNIT',
      'F1' => 'NO. WA ORTU',
      'G1' => 'JENIS KELAMIN',
      'H1' => 'TEMPAT LAHIR',
      'I1' => 'TANGGAL LAHIR',
      'J1' => 'HOBI',
      'K1' => 'NO. HP SISWA',
      'L1' => 'ALAMAT',
      'M1' => 'NAMA AYAH',
      'N1' => 'NAMA IBU',
    ];

    foreach ($headers as $cell => $text) {
      $sheet->setCellValue($cell, $text);
    }

    /*
    |--------------------------------------------------------------------------
    | STYLE HEADER
    |--------------------------------------------------------------------------
    */
    $sheet->getStyle('A1:N1')->getFont()->setBold(true);
    $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getRowDimension(1)->setRowHeight(25);

    /*
    |--------------------------------------------------------------------------
    | STYLE HEADER (ROW 1)
    |--------------------------------------------------------------------------
    */
    $sheet->getStyle('A1:N1')->applyFromArray([
      'font' => [
        'bold'  => true,
        'color' => ['rgb' => 'FFFFFF'],
      ],
      'fill' => [
        'fillType'   => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '000000'],
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical'   => Alignment::VERTICAL_CENTER,
      ],
    ]);


    // Auto width kolom
    foreach (range('A', 'N') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | KETERANGAN
    |--------------------------------------------------------------------------
    */
    $sheet->setCellValue('A2', 'wajib diisi');
    $sheet->setCellValue('B2', 'tidak wajib diisi');
    $sheet->setCellValue('C2', 'wajib diisi');
    $sheet->setCellValue('D2', 'wajib diisi');
    $sheet->setCellValue('E2', 'wajib diisi');
    $sheet->setCellValue('F2', 'tidak wajib diisi');
    $sheet->setCellValue('G2', 'tidak wajib diisi');
    $sheet->setCellValue('H2', 'tidak wajib diisi');
    $sheet->setCellValue('I2', 'tidak wajib diisi');
    $sheet->setCellValue('J2', 'tidak wajib diisi');
    $sheet->setCellValue('K2', 'tidak wajib diisi');
    $sheet->setCellValue('L2', 'tidak wajib diisi');
    $sheet->setCellValue('M2', 'tidak wajib diisi');
    $sheet->setCellValue('N2', 'tidak wajib diisi');

    /*
    |--------------------------------------------------------------------------
    | STYLE KETERANGAN (ROW 2)
    |--------------------------------------------------------------------------
    */
    for ($col = 'A'; $col <= 'N'; $col++) {
      $value = strtolower(trim($sheet->getCell($col . '2')->getValue()));

      // WAJIB DIISI → KUNING
      if ($value === 'wajib diisi') {
        $sheet->getStyle($col . '2')->applyFromArray([
          'font' => [
            'color' => ['rgb' => '000000'],
          ],
          'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'FFD966'], // kuning
          ],
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
          ],
        ]);
      }

      // TIDAK WAJIB DIISI → HIJAU
      if ($value === 'tidak wajib diisi') {
        $sheet->getStyle($col . '2')->applyFromArray([
          'font' => [
            'color' => ['rgb' => 'FFFFFF'],
          ],
          'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '00B050'], // hijau
          ],
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
          ],
        ]);
      }
    }

    /*
    |--------------------------------------------------------------------------
    | CONTOH DATA (ROW 3)
    |--------------------------------------------------------------------------
    */
    $sheet->setCellValue('A3', '1111');          // NIS SANTRI (TEXT)
    $sheet->setCellValue('B3', '1234567890');    // NISN SANTRI (TEXT)
    $sheet->setCellValue('C3', 'Ahmad Fauzi');   // NAMA (TEXT)
    $sheet->setCellValue('D3', 1);               // ID KELAS (INTEGER)
    $sheet->setCellValue('E3', 1);               // ID UNIT (INTEGER)
    $sheet->setCellValue('F3', "08123456789");   // NO WA Ortu
    $sheet->setCellValue('G3', "L");             // JENIS KELAMIN
    $sheet->setCellValue('H3', 'Bandung');       // TEMPAT LAHIR
    $sheet->setCellValue('I3', '2010-05-12');    // TANGGAL LAHIR (yyyy-mm-dd)
    $sheet->setCellValue('J3', 'Bermain Bola');  // HOBI 
    $sheet->setCellValue('K3', '085123456789');  // NO. HP SISWA
    $sheet->setCellValue('L3', 'Jl. Jayabaya');  // ALAMAT
    $sheet->setCellValue('M3', 'Budi Santoso');  // NAMA AYAH
    $sheet->setCellValue('N3', 'Siti Aminah');   // NAMA IBU

    // Tentukan batas baris (misal sampai 1000 baris)
    $maxRow = 1000;

    // Semua kolom default TEXT
    $sheet->getStyle("A3:N{$maxRow}")
      ->getNumberFormat()
      ->setFormatCode(NumberFormat::FORMAT_TEXT);

    // ID KELAS
    $sheet->getStyle("D3:D{$maxRow}")
      ->getNumberFormat()
      ->setFormatCode(NumberFormat::FORMAT_NUMBER);

    // ID UNIT
    $sheet->getStyle("E3:E{$maxRow}")
      ->getNumberFormat()
      ->setFormatCode(NumberFormat::FORMAT_NUMBER);

    // TANGGAL LAHIR → yyyy-mm-dd
    $sheet->getStyle("I3:I{$maxRow}")
      ->getNumberFormat()
      ->setFormatCode('yyyy-mm-dd');


    /*
    |--------------------------------------------------------------------------
    | OUTPUT FILE (DOWNLOAD)
    |--------------------------------------------------------------------------
    */
    $filename = 'Template_Data_Siswa.xlsx';

    // Header download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // cache lama
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Pragma: public');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
  }

  public function pass($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['class_id'] = $f['pr'];
    }
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $id = $f['m'];
      $data['class']        = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id' ORDER BY class_name ASC")->result_array();
    }

    $paramsPage = $params;
    $params['status']     = TRUE;
    $params['offset']     = $offset;
    $data['notpass']      = $this->Student_model->get($params);
    $data['pass']         = $this->Student_model->get(array('status' => 0));
    $data['majors']       = $this->Student_model->get_majors();
    $config['base_url']   = site_url('manage/student/index');
    $config['suffix']     = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));


    $data['title']        = 'Kelulusan Siswa';
    $data['main']         = 'student/student_pass';
    $this->load->view('manage/layout', $data);
  }

  public function upgrade($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['class_id'] = $f['pr'];
    }
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $id = $f['m'];
      $data['class']        = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id' ORDER BY class_name ASC")->result_array();
    }

    $params['status'] = 1;

    $paramsPage = $params;
    $params['offset'] = $offset;
    $data['student'] = $this->Student_model->get($params);
    //$data['class'] = $this->Student_model->get_class($params);
    $data['majors'] = $this->Student_model->get_majors();
    //$data['upgrade'] = $this->Student_model->get_class();
    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));

    $data['title'] = 'Kenaikan Kelas';
    $data['main'] = 'student/student_upgrade';
    $this->load->view('manage/layout', $data);
  }

  function multiple()
  {
    $action         = $this->input->post('action');
    $period         = $this->db->query("SELECT period_id AS id FROM period WHERE period_status = 1")->row_array();
    $periodActive   = $period['id'];

    $print = array();
    $idcard = array();
    if ($action == "pass") {
      $pass   = $this->input->post('msg');
      $majors = sanitizeStrict($this->input->post('majors_id'));
      $asalKelas = sanitizeStrict($this->input->post('from_class'));
      if (!is_array($pass) || empty($pass)) {
        $this->session->set_flashdata('failed', 'Kenaikan kelas gagal. Tidak ada siswa yang dipilih untuk kenaikan kelas.');
        if ($majors != '' && $asalKelas != '') {
          redirect('manage/student/pass?m=' . $majors . '&pr=' . $asalKelas);
        } else {
          redirect('manage/student/pass');
        }
      }

      $msn    = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$majors'")->row_array();
      $like   = $msn['majors_short_name'] . ' Kelas Alumni';
      $cek    = $this->db->query("SELECT count(class_id) c_class FROM class WHERE class_name = '$like'")->row_array();

      if ($cek['c_class'] == 0) {
        $params = array(
          'class_name'       => $like,
          'majors_majors_id' => $majors
        );

        $this->db->insert('class', $params);
      }

      for ($i = 0; $i < count($pass); $i++) {
        $bebas = $this->db->query("SELECT SUM(bebas_bill - bebas_diskon - bebas_total_pay) as tagihan FROM bebas WHERE student_student_id = '$pass[$i]'")->row_array();
        $bulan = $this->db->query("SELECT COUNT(bulan_status) as tagihan FROM bulan WHERE bulan_status = 0 AND student_student_id = '$pass[$i]'")->row_array();

        if ($bebas['tagihan'] == 0 && $bulan['tagihan'] == 0) {
          $this->Student_model->add(array('student_id' => $pass[$i], 'student_status' => 0, 'student_last_update' => date('Y-m-d H:i:s')));

          $this->db->query("INSERT INTO alumni
                                (alumni_nis, alumni_nisn, alumni_full_name, alumni_gender, alumni_born_place, alumni_born_date, alumni_img, alumni_phone, alumni_hobby, alumni_address, alumni_name_of_mother, alumni_name_of_father, alumni_parent_phone, alumni_unit, alumni_kelas, alumni_tahun_id) 
                            SELECT 
                                student_nis, student_nisn, student_full_name, student_gender, student_born_place, student_born_date, student_img, student_phone, student_hobby, student_address, student_name_of_mother, student_name_of_father, student_parent_phone, majors_majors_id, class_class_id, '$periodActive'
                                FROM student
                                WHERE student_id = '$pass[$i]'");
          $this->session->set_flashdata('success', 'Proses Lulus berhasil');
        } else {
          $alumni = $this->db->query("SELECT class_id FROM class WHERE class_name = '$like'")->row_array();

          $this->db->query("UPDATE student SET class_class_id = '" . $alumni['class_id'] . "' WHERE student_id = '" . $pass[$i] . "'");

          $this->session->set_flashdata('failed', 'Siswa yang masih memiliki tunggakan dipindahkan ke kelas ' . $like);
        }
      }

      if ($majors != '' && $asalKelas != '') {
        redirect('manage/student/pass?m=' . $majors . '&pr=' . $asalKelas);
      } else {
        redirect('manage/student/pass');
      }
    } elseif ($action == "notpass") {
      $notpass = $this->input->post('msg');
      $asalUnit = sanitizeStrict($this->input->post('majors_id'));
      $asalKelas = sanitizeStrict($this->input->post('from_class'));
      if (!is_array($notpass) || empty($notpass)) {
        $this->session->set_flashdata('failed', 'Pembatalan kelulusan gagal. Tidak ada siswa yang dipilih.');
        redirect('manage/student/pass');
      }

      for ($i = 0; $i < count($notpass); $i++) {
        // $this->Student_model->add(array('student_id' => $notpass[$i], 'student_status' => 1, 'student_last_update' => date('Y-m-d H:i:s')));
        // $this->session->set_flashdata('success', 'Proses Kembali berhasil');

        $students = $this->Student_model->get_student(array('student_id' => $notpass[$i]));
        if ($students) {
          foreach ($students as $value) {
            $nis = $value['student_nis'];
            $nisn = $value['student_nisn'];

            $this->db->where(['alumni_nis' => $nis, 'alumni_nisn' => $nisn]);
            $this->db->delete('alumni');
          }

          $this->Student_model->add(array('student_id' => $notpass[$i], 'student_status' => 1, 'student_last_update' => date('Y-m-d H:i:s')));
          $this->session->set_flashdata('success', 'Proses Kembali berhasil');
        } else {
          $this->session->set_flashdata('failed', 'Data alumni tidak ditemukan. Pembatalan kelulusan gagal.');
        }
      }

      if ($asalUnit != '' && $asalKelas != '') {
        redirect('manage/student/pass?m=' . $asalUnit . '&pr=' . $asalKelas);
      } else {
        redirect('manage/student/pass');
      }
    } elseif ($action == "upgrade") {
      $asalUnit = $this->input->post('from_unit');
      $asalKelas = $this->input->post('from_class');
      $upgrade = $this->input->post('msg');
      $majors   = $this->input->post('m_id');
      $kelas   = $this->input->post('class_id');

      if (!is_array($upgrade) || empty($upgrade)) {
        $this->session->set_flashdata('failed', 'Kenaikan kelas gagal. Tidak ada siswa yang dipilih untuk kenaikan kelas.');
        redirect('manage/student/upgrade?m=' . $asalUnit . '&pr=' . $asalKelas);
      }

      if ($asalUnit == $majors && $asalKelas == $kelas) {
        $this->session->set_flashdata('failed', 'Kenaikan kelas gagal. Kelas tujuan tidak boleh sama dengan kelas asal.');
        redirect('manage/student/upgrade?m=' . $asalUnit . '&pr=' . $asalKelas);
      }

      if ($kelas == null || $kelas == '') {
        $this->session->set_flashdata('failed', 'Kenaikan kelas gagal. Kelas tujuan tidak boleh kosong.');
        redirect('manage/student/upgrade?m=' . $asalUnit . '&pr=' . $asalKelas);
      }

      //echo $kelas.'<br>';
      for ($i = 0; $i < count($upgrade); $i++) {
        //echo $upgrade[$i];
        $this->Student_model->add(array('student_id' => $upgrade[$i], 'class_class_id' => $kelas, 'student_last_update' => date('Y-m-d H:i:s')));
        $this->session->set_flashdata('success', 'Proses Kenaikan Kelas berhasil');
      }
      redirect('manage/student/upgrade?m=' . $majors . '&pr=' . $kelas);
    } elseif ($action == "printPdf") {
      $this->load->helper(array('dompdf'));
      $idcard = $this->input->post('msg');
      for ($i = 0; $i < count($idcard); $i++) {
        $print[] = $idcard[$i];
      }

      $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
      $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['student'] = $this->Student_model->get(array('multiple_id' => $print));

      for ($i = 0; $i < count($data['student']); $i++) {
        $this->barcode2($data['student'][$i]['student_nis'], '');
      }
      $html = $this->load->view('student/student_multiple_pdf', $data, true);
      $data = pdf_create($html, 'KARTU_' . date('d_m_Y'), TRUE, 'A4', 'potrait');
    }
  }
  // satuan
  function printPdf($id = NULL)
  {
    $this->load->library('dompdflib');
    $this->load->helper(array('tanggal'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    if ($id == NULL)
      redirect('manage/student');

    $data['setting_kartu_depan'] = $this->Setting_model->get(array('id' => 18));
    $data['setting_kartu_belakang'] = $this->Setting_model->get(array('id' => 19));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['student'] = $this->Student_model->get(array('id' => $id));
    $data['barcode_nis'] = str_replace('/', '-', $data['student']['student_nis']);
    $this->barcode2($data['barcode_nis'], '');

    // var_dump($data);
    // exit;

    // $this->load->library('ciqrcode'); //load library

    // $nis                = $data['student']['student_nis'];
    // $params['data']     = $nis; //data isian qr code
    // $params['level']    = 'H'; //tingkat enkripsi H (high)
    // $params['size']     = 4; //ukuran
    // $params['savename'] = FCPATH . 'media/qrcode/' . $nis . '.png'; // nama file
    // $this->ciqrcode->generate($params); //run generate qr code dan save ke direktori diatas
    $html = $this->load->view('student/student_pdf', $data, true);
    // $data = pdf_create($html, $data['student']['student_full_name'], TRUE, 'B6', 'potrait');

    $paper = 'B6';

    $orientation = "potrait";
    $html = $this->load->view('student/student_pdf', $data, true);
    $this->dompdflib->generate($html, $data['student']['student_full_name'], $paper, $orientation);
  }

  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_student/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
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
    $drawing->setFilename(FCPATH . 'media/barcode_student/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  public function get_mclass()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select required="" name="modal_class" id="modal_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>';
    foreach ($dataKelas as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }

  public function get_xls_class()
  {

    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select required="" name="xls_class" id="xls_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>
		  <option value="all">Semua Kelas</option>';
    foreach ($dataKelas as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }

  public function print_students()
  {
    ob_start();

    $params = array();

    $id_majors = $this->input->post('modal_majors');
    $id_kelas  = $this->input->post('modal_class');

    $params['majors_id'] = $id_majors;
    $params['class_id']  = $id_kelas;

    $data['student'] = $this->Student_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
    $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    $majors          = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
    $kelas           = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $filename = 'Data_Siswa_' . $majors . '_Kelas_' . $kelas . '.pdf';
    $this->load->view('student/print_students', $data);

    $html = ob_get_contents();
    ob_end_clean();

    require_once('./assets/html2pdf/html2pdf.class.php');
    $pdf = new HTML2PDF('l', 'A4', 'en');
    $pdf->WriteHTML($html);
    $pdf->Output($filename);
  }

  public function excel_students()
  {
    $params = array();

    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $params['class_id']  = $id_kelas;
      $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $this->load->view('student/excel_students', $data);
  }

  function datatablesSiswa()
  {
    $draw = $_REQUEST['draw'];
    $length = $_REQUEST['length'];
    $start = $_REQUEST['start'];
    $search = $_REQUEST['search']["value"];

    // Menangani sorting
    $order_column_index = $_REQUEST['order'][0]['column']; // Index kolom yang diklik
    $order_dir = $_REQUEST['order'][0]['dir']; // Arah sorting (asc/desc)
    $data['order_dir'] = $order_dir;

    $columns = ['student_nis', 'student_full_name', 'madin_name', 'class_name', 'student_full_name'];

    // Pastikan index yang dikirimkan oleh DataTables sesuai dengan daftar kolom yang kita punya
    $order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'student_nis';
    $data['order_column'] = $order_column;

    $totalBaris = $this->Student_model->count_all($search, array('status' => '1'));
    $output = array();
    $output['draw']   = $draw;
    $output['recordsTotal'] = $output['recordsFiltered'] = $totalBaris;
    $output['data'] = array();

    if ($search != "") {
      $query = $this->Student_model->list_siswa($length, $start, $search, $data);
    } else {
      $query = $this->Student_model->list_siswa($length, $start, $search, $data);
    }

    $nomor_urut = $start + 1;
    foreach ($query->result_array() as $dt) {
      $output['data'][] = array(
        // $nomor_urut,
        $dt['student_nis'],
        $dt['student_full_name'],
        $dt['majors_short_name'],
        $dt['class_name'],
        '<button type="button" class="btn btn-primary btn-xs" onclick="ambil_data(' . $dt['student_nis'] . ')">Pilih</button>'

      );
      $nomor_urut++;
    }

    echo json_encode($output);
  }
}
