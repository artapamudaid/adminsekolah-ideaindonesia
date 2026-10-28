<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
    $id_majors = (int) $this->input->post('id_majors');

    $NisValue = $this->db
      ->select('MAX(student_nis) as nis')
      ->where('majors_majors_id', $id_majors)
      ->where('student_status', '1')
      ->get('student')
      ->row_array();
    $nis = $NisValue['nis'] + 1;

    echo '<div class="form-group">
			<label>NIS <small data-toggle="tooltip" title="Wajib diisi">(Muncul Otomatis Setelah Unit Sekolah Dipilih)</small></label>
			<input name="student_nis" type="text" class="form-control" value="' . $nis . '" placeholder="NIS Siswa" readonly="">
		</div>';
  }

  function class_searching()
  {
    $id_majors = (int) $this->input->post('id_majors');
    $dataKelas = $this->db->where('majors_majors_id', $id_majors)->order_by('class_name', 'ASC')->get('class')->result_array();

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
    $id_majors = (int) $this->input->post('id_majors');
    $dataKelas = $this->db->where('majors_majors_id', $id_majors)->order_by('class_name', 'ASC')->get('class')->result_array();

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
    $id_majors = (int) $this->input->post('id_majors');
    $dataKelas = $this->db->where('majors_majors_id', $id_majors)->order_by('class_name', 'ASC')->get('class')->result_array();

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
    $id_majors = (int) $this->input->post('id_majors');
    $dataKelas = $this->db->where('majors_majors_id', $id_majors)->order_by('class_name', 'ASC')->get('class')->result_array();

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
      $this->form_validation->set_rules('student_nis', 'NIS', 'trim|required|xss_clean|is_unique[student.student_nis]');
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

      $student_parent_phone = $this->input->post('student_parent_phone');

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

      $params['student_nis'] = $this->input->post('student_nis');
      $params['student_nisn'] = $this->input->post('student_nisn');
      $params['student_gender'] = $this->input->post('student_gender');
      $params['student_phone'] = $this->input->post('student_phone');
      $params['student_hobby'] = $this->input->post('student_hobby');
      $params['class_class_id'] = $this->input->post('class_class_id');
      $params['majors_majors_id'] = $this->input->post('majors_majors_id');
      $params['student_last_update'] = date('Y-m-d H:i:s');
      $params['student_full_name'] = $this->input->post('student_full_name');
      $params['student_born_place'] = $this->input->post('student_born_place');
      $params['student_born_date'] = $this->input->post('student_born_date');
      $params['student_address'] = $this->input->post('student_address');
      $params['student_name_of_mother'] = $this->input->post('student_name_of_mother');
      $params['student_name_of_father'] = $this->input->post('student_name_of_father');
      $params['student_parent_phone'] = $hp;
      $params['student_status'] = $this->input->post('student_status');
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
	        redirect('manage/student/edit/' . (int) $this->input->post('student_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_student(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/student');
        } else {
          $data['student'] = $object;
        }
	        $id_unit = (int) $object['majors_majors_id'];
	        $data['class'] = $this->db->where('majors_majors_id', $id_unit)->get('class')->result_array();
      }
      $data['setting_level'] = $this->Setting_model->get(array('id' => 7));
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Siswa';
      $data['main'] = 'student/student_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // View data detail
  public function view($id = NULL)
  {
	    $data['student'] = $this->Student_model->get(array('id' => (int) $id));
	    $siswa = $data['student'];
	
	    $dataPeriod = $this->db->select('period_id')->where('period_status', '1')->get('period')->row_array();
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
      mkdir($config['upload_path'], 0755, TRUE);
    }

    $config['allowed_types'] = 'xls|xlsx|csv';
    $config['max_size'] = '10240';
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

  // Setting Upload File Requied
  function do_upload($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/student/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0755, TRUE);
    }

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
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

  public function old_do_import()
  {
    redirect('manage/student/import_student');
  }

  public function do_import()
  {
    $pass_default = '123456';

    if (isset($_FILES["file"]["name"])) {

      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

	        $extension = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));
	        if ($extension === 'xls') {
	          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
	        } elseif ($extension === 'xlsx') {
	          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	        } elseif ($extension === 'csv') {
	          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
	        } else {
	          $this->session->set_flashdata('failed', 'File import siswa tidak valid');
	          redirect('manage/student');
	        }
	        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {

          $student_nis            = $row['A']; //required
          $student_nisn           = $row['B'];
          $student_full_name      = $row['C']; //required
          $class_class_id         = $row['D']; //required
          $majors_majors_id       = $row['E']; //required
          $student_parent_phone   = $row['F'];
          $gender                 = $row['G'];
          $tempat_lahir           = $row['H'];
          $tgl_lahir              = $row['I'];
          $hobi                   = $row['J'];
          $student_phone          = $row['K'];
          $alamat                 = $row['L'];
          $ayah                   = $row['M'];
          $ibu                    = $row['N'];

          if ($numrow > 2) {
            if (
              empty($student_nis) ||
              empty($student_full_name) ||
              empty($class_class_id) ||
              empty($majors_majors_id)
            ) {
              continue;
            }
	            $q = $this->db
	              ->select('COUNT(student_nis) as nisNum')
	              ->where('student_nis', $student_nis)
	              ->get('student')
	              ->row_array();

            $nisNum = $q['nisNum'];

            if (empty($student_nis) || $nisNum > 0) {
              $this->session->set_flashdata('failed', 'Duplikasi No. Induk Siswa');
            } else {

              $timestamp = date('Y-m-d H:i:s');

              $data = array(
                'student_nis'             =>  $student_nis,
                'student_full_name'       =>  $student_full_name,
                'student_password'        =>  md5($pass_default),
                'class_class_id'          =>  $class_class_id,
                'majors_majors_id'        =>  $majors_majors_id,
                'student_input_date'      => $timestamp,
                'student_last_update'      => $timestamp
              );

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

              $this->db->insert('student', $data);


              $this->session->set_flashdata('success', 'Import Data Santri Berhasil');
            }
          }
          $numrow++;
        }

	        if (is_file($pathFile) && strpos(realpath($pathFile), realpath($path)) === 0) {
	          unlink($pathFile);
	        }
      }
    }
    redirect('manage/student');
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

	    $id_majors = (int) $this->input->post('xls_majors');
	    $id_kelas  = $this->input->post('xls_class', TRUE);

    $params['majors_id'] = $id_majors;

	    if ($id_kelas != 'all') {
	      $params['class_id']  = (int) $id_kelas;
	      $data['kelas'] = $this->db
	        ->select('class_name')
	        ->where('class_id', (int) $id_kelas)
	        ->get('class')
	        ->row_array();
	    } else {
	      $data['kelas']   = array('class_name' => 'Semua Kelas');
	    }
	
	    $data['student'] = $this->Student_model->get($params);
	    $data['majors'] = $this->db
	      ->select('majors_short_name')
	      ->where('majors_id', $id_majors)
	      ->get('majors')
	      ->row_array();

    $this->load->view('student/whatsapp_student', $data);
  }

  public function update_phone()
  {
    if (isset($_FILES["file"]["name"])) {
      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

	        $extension = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));
	        if ($extension === 'xls') {
	          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
	        } elseif ($extension === 'xlsx') {
	          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	        } elseif ($extension === 'csv') {
	          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
	        } else {
	          $this->session->set_flashdata('failed', 'File update nomor whatsapp tidak valid');
	          redirect('manage/student');
	        }
	        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {

          $student_nis            = $row['A'];
          $student_address        = $row['E'];
          $student_name_of_father = $row['F'];
          $student_name_of_mother = $row['G'];
          $student_parent_phone   = $row['H'];

          if ($numrow > 2) {

            $student_nis = str_replace("'", "", $student_nis);

	            $q = $this->db
	              ->select('COUNT(student_nis) as nisNum')
	              ->where('student_nis', $student_nis)
	              ->get('student')
	              ->row_array();

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

	        if (is_file($pathFile) && strpos(realpath($pathFile), realpath($path)) === 0) {
	          unlink($pathFile);
	        }

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
    $data = file_get_contents("./media/template_excel/Template_Data_Siswa.xlsx");
    $name = 'Template_Data_Siswa.xlsx';

    $this->load->helper('download');
    force_download($name, $data);
  }

  public function pass($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
	      $params['class_id'] = (int) $f['pr'];
	    }
	    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
	      $id = (int) $f['m'];
	      $data['class'] = $this->db->where('majors_majors_id', $id)->order_by('class_name', 'ASC')->get('class')->result_array();
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
	      $params['class_id'] = (int) $f['pr'];
	    }
	    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
	      $id = (int) $f['m'];
	      $data['class'] = $this->db->where('majors_majors_id', $id)->order_by('class_name', 'ASC')->get('class')->result_array();
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
	    $action = $this->input->post('action', TRUE);
	    $period = $this->db->select('period_id AS id')->where('period_status', 1)->get('period')->row_array();
	    $periodActive = (int) $period['id'];

	    $print = array();
	    $idcard = array();
	    if ($action == "pass") {
	      $majors = (int) $this->input->post('majors_id');
	      $pass = array_map('intval', (array) $this->input->post('msg'));
	      $msn = $this->db->select('majors_short_name')->where('majors_id', $majors)->get('majors')->row_array();
	      $like   = $msn['majors_short_name'] . ' Kelas Alumni';
	      $cek = $this->db->select('COUNT(class_id) c_class')->where('class_name', $like)->get('class')->row_array();

      if ($cek['c_class'] == 0) {
        $params = array(
          'class_name'       => $like,
          'majors_majors_id' => $majors
        );

        $this->db->insert('class', $params);
	      }
	
	      for ($i = 0; $i < count($pass); $i++) {
	        $studentId = (int) $pass[$i];
	        $bebas = $this->db->select('SUM(bebas_bill - bebas_diskon - bebas_total_pay) as tagihan', FALSE)->where('student_student_id', $studentId)->get('bebas')->row_array();
	        $bulan = $this->db->select('COUNT(bulan_status) as tagihan')->where('bulan_status', 0)->where('student_student_id', $studentId)->get('bulan')->row_array();
	
	        if ($bebas['tagihan'] == 0 && $bulan['tagihan'] == 0) {
	          $this->Student_model->add(array('student_id' => $studentId, 'student_status' => 0, 'student_last_update' => date('Y-m-d H:i:s')));
	
	          $this->db->query("INSERT INTO alumni
	                                (alumni_nis, alumni_nisn, alumni_full_name, alumni_gender, alumni_born_place, alumni_born_date, alumni_img, alumni_phone, alumni_hobby, alumni_address, alumni_name_of_mother, alumni_name_of_father, alumni_parent_phone, alumni_unit, alumni_kelas, alumni_tahun_id) 
	                            SELECT 
	                                student_nis, student_nisn, student_full_name, student_gender, student_born_place, student_born_date, student_img, student_phone, student_hobby, student_address, student_name_of_mother, student_name_of_father, student_parent_phone, majors_majors_id, class_class_id, ?
	                                FROM student
	                                WHERE student_id = ?", array($periodActive, $studentId));
	          $this->session->set_flashdata('success', 'Proses Lulus berhasil');
	        } else {
	          $alumni = $this->db->select('class_id')->where('class_name', $like)->get('class')->row_array();
	
	          $this->db->where('student_id', $studentId)->update('student', array('class_class_id' => (int) $alumni['class_id']));

          $this->session->set_flashdata('failed', 'Siswa yang masih memiliki tunggakan dipindahkan ke kelas ' . $like);
        }
      }
      redirect('manage/student/pass');
	    } elseif ($action == "notpass") {
	      $notpass = array_map('intval', (array) $this->input->post('msg'));
	      for ($i = 0; $i < count($notpass); $i++) {
	        $this->Student_model->add(array('student_id' => $notpass[$i], 'student_status' => 1, 'student_last_update' => date('Y-m-d H:i:s')));
        $this->session->set_flashdata('success', 'Proses Kembali berhasil');
      }
      redirect('manage/student/pass');
	    } elseif ($action == "upgrade") {
	      $upgrade = array_map('intval', (array) $this->input->post('msg'));
	      $majors = (int) $this->input->post('m_id');
	      $kelas = (int) $this->input->post('class_id');
      //echo $kelas.'<br>';
      for ($i = 0; $i < count($upgrade); $i++) {
        //echo $upgrade[$i];
        $this->Student_model->add(array('student_id' => $upgrade[$i], 'class_class_id' => $kelas, 'student_last_update' => date('Y-m-d H:i:s')));
        $this->session->set_flashdata('success', 'Proses Kenaikan Kelas berhasil');
      }
      redirect('manage/student/upgrade?m=' . $majors . '&pr=' . $kelas);
	    } elseif ($action == "printPdf") {
	      $this->load->helper(array('dompdf'));
	      $idcard = array_map('intval', (array) $this->input->post('msg'));
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
    $drawing->setFilename(FCPATH . 'media/barcode_student/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  public function get_mclass()
  {
    $id_majors = (int) $this->input->post('id_majors');
    $dataKelas = $this->db->where('majors_majors_id', $id_majors)->order_by('class_name', 'ASC')->get('class')->result_array();

    echo '<select required="" name="modal_class" id="modal_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>';
    foreach ($dataKelas as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }

  public function get_xls_class()
  {

    $id_majors = (int) $this->input->post('id_majors');
    $dataKelas = $this->db->where('majors_majors_id', $id_majors)->order_by('class_name', 'ASC')->get('class')->result_array();

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

    $id_majors = (int) $this->input->post('modal_majors');
    $id_kelas = (int) $this->input->post('modal_class');

    $params['majors_id'] = $id_majors;
    $params['class_id']  = $id_kelas;

    $data['student'] = $this->Student_model->get($params);
    $data['majors'] = $this->db->select('majors_short_name')->where('majors_id', $id_majors)->get('majors')->row_array();
    $data['kelas'] = $this->db->select('class_name')->where('class_id', $id_kelas)->get('class')->row_array();
    $majors = $data['majors'];
    $kelas = $data['kelas'];

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

    $id_majors = (int) $this->input->post('xls_majors');
    $id_kelas = $this->input->post('xls_class', TRUE);

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $params['class_id'] = (int) $id_kelas;
      $data['kelas'] = $this->db->select('class_name')->where('class_id', (int) $id_kelas)->get('class')->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors'] = $this->db->select('majors_short_name')->where('majors_id', $id_majors)->get('majors')->row_array();

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $this->load->view('student/excel_students', $data);
  }
}
