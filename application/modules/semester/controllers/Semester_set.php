<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Semester_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('semester/Semester_model', 'setting/Setting_model', 'period/Period_model'));
    $this->load->helper(array('form', 'url'));
  }

  public function index($offset = NULL)
  {
    $this->load->library('pagination');

    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['semester_name'] = $f['n'];
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['period_id'] = $s['m'];
    } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
    }

    $data['semester'] = $this->Semester_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $config['base_url'] = site_url('manage/semester/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['period'] = $this->Period_model->get();

    $data['title'] = 'Semester';
    $data['main'] = 'semester/semester_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $semesterName   = sanitizeStrict($_POST['semester_name']);
      $semesterPeriodId = sanitizeStrict($_POST['semester_period_id']);

      $cpt = count($semesterName);

      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {

        if (empty($semesterName[$i])) {
          $skipped++;
          continue;
        }

        $col_where = [
          'semester_name' => $semesterName[$i],
          'semester_period_id' => $semesterPeriodId,
        ];

        if ($this->General_model->checkDataExists('semester', $col_where)) {
          $skipped++;
          continue;
        }

        $params['semester_name']     = $semesterName[$i];
        $params['semester_period_id'] = $semesterPeriodId;

        $insert = $this->Semester_model->add($params);
        if (!$insert) {
          $skipped++;
          continue;
        }
        $added++;
      }

      if ($added > 1) {
        $msg = "$added Semester berhasil ditambahkan";
      } elseif ($added == 1) {
        $msg = "1 Semester berhasil ditambahkan";
      } else {
        $msg = "Tidak ada yang ditambahkan";
      }

      if ($skipped > 0) {
        $msg .= "<br>($skipped Semester dilewati)";
      }

      $this->session->set_flashdata('success', $msg);
      redirect('manage/semester');
    }
  }

  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('semester_name', 'Nama Semester', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('semester_id')) {
        $id = sanitizeStrict($this->input->post('semester_id'));
        $params['semester_id'] = $id;
      }

      $semesterName = sanitizeStrict($this->input->post('semester_name'));
      $semesterPeriodId = sanitizeStrict($this->input->post('semester_period_id'));
      $semesterStatus = sanitizeStrict($this->input->post('semester_status'));

      $colWhere = [
        'semester_name' => $semesterName,
        'semester_period_id' => $semesterPeriodId
      ];
      if (!empty($id)) {
        $colWhere['semester_id !='] = $id;
      }
      if ($this->General_model->checkDataExists("semester", $colWhere)) {
        $this->session->set_flashdata('failed', 'Data sudah ada');
        $redirectUrl = ($id) ? "manage/semester/edit/$id" : "manage/semester";
        redirect($redirectUrl);
      }

      $params['semester_name']     = $semesterName;
      $params['semester_period_id']   = $semesterPeriodId;
      $params['semester_status']     = $semesterStatus;

      $status = $this->Semester_model->add($params);


      $this->session->set_flashdata('success', $data['operation'] . 'Semester');
      redirect('manage/semester');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('semester_id')) {
        redirect('manage/semester/edit/' . $this->input->post('semester_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Semester_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/semester');
        } else {
          $data['semester'] = $object;
        }
      }
      $data['period'] = $this->Period_model->get();
      $data['title'] = $data['operation'] . 'Semester';
      $data['main'] = 'semester/semester_add';
      $this->load->view('manage/layout', $data);
    }
  }



  function semester_active($id = NULL)
  {

    $non = array(
      'semester_status' => 0,
      'status_active' => TRUE
    );

    $this->Semester_model->add($non);

    $active = array(
      'semester_id' => $id,
      'semester_status' => 1
    );

    $status = $this->Semester_model->add($active);

    if ($this->input->is_ajax_request()) {
      echo $status;
    } else {
      $this->session->set_flashdata('success', 'Aktif Tahun Ajaran Berhasil');
      redirect('manage/semester');
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {

    if ($_POST) {
      $check = $this->db->query("SELECT COUNT(*) AS num FROM `jurnal` WHERE jurnal_semester_id = '$id'")->row_array();

      if ($check['num'] < 1) {
        $this->Semester_model->delete($id);
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
        $this->session->set_flashdata('success', 'Hapus Semester berhasil');
      } else {
        $this->session->set_flashdata('delete', 'Hapus Semester gagal');
      }
      redirect('manage/semester');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/semester/edit/' . $id);
    }
  }
}
