<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lesson_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('lesson/Lesson_model', 'setting/Setting_model', 'student/Student_model', 'employees/Employees_model', 'schedule/Schedule_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();
    $param  = array();

    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['lesson_name'] = $f['n'];
    }

    $data['employee'] = $this->Employees_model->get($params);
    $data['lessons'] = $this->Lesson_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    $config['base_url'] = site_url('manage/lesson/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Mata Pelajaran';
    $data['main'] = 'lesson/lesson_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $lessonCode    = sanitizeStrict($_POST['lesson_code']);
      $lessonName    = sanitizeStrict($_POST['lesson_name']);
      $lessonTeacher = sanitizeStrict($_POST['lesson_teacher']);
      $lessonMajors  = isset($_POST['lesson_majors_id']) ? sanitizeStrict($_POST['lesson_majors_id']) : '0';

      $cpt = count($lessonName);

      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {

        if (empty($lessonName[$i])) {
          $skipped++;
          continue;
        }

        $colWhere = [
          'lesson_name' => $lessonName[$i],
          'lesson_majors_id' => $lessonMajors[$i],
          'lesson_teacher' => $lessonTeacher[$i],
          'lesson_code' => $lessonCode[$i],
        ];

        if ($this->General_model->checkDataExists('lesson', $colWhere)) {
          $skipped++;
          continue;
        }

        $params['lesson_code']      = $lessonCode[$i];
        $params['lesson_name']      = $lessonName[$i];
        $params['lesson_teacher']   = $lessonTeacher[$i];
        $params['lesson_majors_id'] = $lessonMajors[$i];

        $insert = $this->Lesson_model->add($params);
        if (!$insert) {
          $skipped++;
          continue;
        }
        $added++;
      }

      if ($added > 1) {
        $msg = "$added Mata Pelajaran berhasil ditambahkan";
      } elseif ($added == 1) {
        $msg = "1 Mata Pelajaran berhasil ditambahkan";
      } else {
        $msg = "Tidak ada yang ditambahkan";
      }

      if ($skipped > 0) {
        $msg .= "<br>($skipped Mata Pelajaran dilewati)";
      }

      $this->session->set_flashdata('success', $msg);
    }
    redirect('manage/lesson');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('lesson_name', 'Nama Mata Pelajaran', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('lesson_id')) {
        $id = sanitizeStrict($this->input->post('lesson_id'));
        $params['lesson_id'] = $id;
      }

      $lessonCode    = sanitizeStrict($this->input->post('lesson_code'));
      $lessonName    = sanitizeStrict($this->input->post('lesson_name'));
      $lessonTeacher = sanitizeStrict($this->input->post('lesson_teacher'));
      $lessonMajors  = sanitizeStrict($this->input->post('lesson_majors_id'));

      $colWhere = [
        'lesson_code' => $lessonCode,
        'lesson_name' => $lessonName,
        'lesson_teacher' => $lessonTeacher,
        'lesson_majors_id' => $lessonMajors
      ];
      if (!empty($id)) {
        $colWhere['lesson_id !='] = $id;
      }
      if ($this->General_model->checkDataExists("lesson", $colWhere)) {
        $this->session->set_flashdata('failed', 'Data Mata Pelajaran sudah ada');
        $redirectUrl = ($id) ? "manage/lesson/edit/$id" : "manage/lesson?m=" . $lessonMajors;
        redirect($redirectUrl);
      }

      $params['lesson_name'] = $lessonName;
      $params['lesson_teacher'] = $lessonTeacher;
      $params['lesson_code'] = $lessonCode;
      // $params['lesson_majors_id'] = $lessonMajors;
      $status = $this->Lesson_model->add($params);


      $this->session->set_flashdata('success', $data['operation'] . 'Mata Pelajaran');
      redirect('manage/lesson');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('lesson_id')) {
        redirect('manage/lesson/edit/' . $this->input->post('lesson_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Lesson_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/lesson');
        } else {
          $data['lesson']   = $object;
        }
      }

      $data['title'] = $data['operation'] . 'Mata Pelajaran';
      $data['main'] = 'lesson/lesson_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }

    $jadwal = $this->Schedule_model->get(array('schedule_lesson_id' => $id));

    if ($_POST) {


      if (count($jadwal) > 0) {
        $this->session->set_flashdata('failed', 'Mata Pelajaran tidak dapat dihapus karena sudah dimasukkan ke dalam jadwal pelajaran');
        redirect('manage/lesson');
      }

      $this->Lesson_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Mata Pelajaran berhasil');
      redirect('manage/lesson');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/lesson/edit/' . $id);
    }
  }

  public function import_lesson()
  {
    $data['title'] = 'Import Pelajaran';
    $data['main'] = 'lesson/lesson_import';
    $data['action'] = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function do_import()
  {

    if (isset($_FILES["file"]["name"])) {

      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imless' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {

          $lesson_code            = $row['A'];
          $lesson_name            = $row['B'];
          $lesson_teacher         = $row['C'];

          if ($numrow > 2) {
            if (empty($lesson_code) or empty($lesson_name) or empty($lesson_teacher)) {
              continue;
            }
            $data = array(
              'lesson_code'       => $lesson_code,
              'lesson_name'       => $lesson_name,
              'lesson_teacher'    => $this->getTeacherId($lesson_teacher)
            );

            $this->db->insert('lesson', $data);
          }
          $numrow++;
        }

        unlink($pathFile);

        $this->session->set_flashdata('success', 'Import Pelajaran Berhasil');
      }
    }
    redirect('manage/lesson');
  }

  private function getTeacherId($nip)
  {
    $empl = $this->db->query("SELECT employee_id FROM employee WHERE employee_nip = '$nip'")->row_array();

    return $empl['employee_id'];
  }

  public function download()
  {
    $data = file_get_contents("./media/template_excel/Template_Data_Pelajaran.xlsx");
    $name = 'Template_Data_Pelajaran.xlsx';

    $this->load->helper('download');
    force_download($name, $data);
  }

  private function do_upload_xls($name = NULL, $fileName = NULL)
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
}
