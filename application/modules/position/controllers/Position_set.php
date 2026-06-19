<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Position_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('logs/Logs_model', 'position/Position_model', 'student/Student_model', 'setting/Setting_model'));
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
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['position_name'] = $f['n'];
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    }

    //$paramsPage = $params;
    //$params['limit'] = 10;
    //$params['offset'] = $offset;
    $data['position'] = $this->Position_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/position/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);

    $data['majors'] = $this->Student_model->get_majors();

    $data['title'] = 'Jabatan Pegawai';
    $data['main'] = 'position/position_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $positionCode = sanitizeStrict($_POST['position_code']);
      $positionName = sanitizeStrict($_POST['position_name']);
      $positionMajorId = sanitizeStrict($_POST['position_majors_id']);

      $cpt = count($positionName);
      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {
        if (empty($positionCode[$i]) || empty($positionName[$i])) {
          $skipped++;
          continue;
        }

        $colWhere = [
          'position_name' => $positionName[$i],
          'position_majors_id' => $positionMajorId
        ];
        if ($this->General_model->checkDataExists("position", $colWhere)) {
          $skipped++;
          continue;
        }
        $params['position_code']      = $positionCode[$i];
        $params['position_name']      = $positionName[$i];
        $params['position_majors_id'] = $positionMajorId;

        $insert = $this->Position_model->add($params);
        if (!$insert) {
          $skipped++;
          continue;
        }

        $added++;
      }

      if ($added > 1) {
        $msg = "$added data berhasil ditambahkan";
      } elseif ($added == 1) {
        $msg = "1 data berhasil ditambahkan";
      } else {
        $msg = "Tidak ada yang ditambahkan";
      }

      if ($skipped > 0) {
        $msg .= "<br>($skipped data dilewati)";
      }

      $this->session->set_flashdata('success', $msg);
    }
    redirect('manage/position');
  }

  // Add User_customer and Update

  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('position_code', 'Jabatan Pegawai', 'trim|required|xss_clean');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('position_id')) {
        $id = sanitizeStrict($this->input->post('position_id'));
        $params['position_id'] = $id;
      }

      $positionCode = sanitizeStrict($this->input->post('position_code'));
      $positionName = sanitizeStrict($this->input->post('position_name'));
      $positionMajorId = sanitizeStrict($this->input->post('position_majors_id'));

      $colWhere = [
        'position_name' => $positionName,
        'position_majors_id' => $positionMajorId,
      ];
      if (!empty($id)) {
        $colWhere['position_id !='] = $id;
      }

      if ($this->General_model->checkDataExists("position", $colWhere)) {
        $this->session->set_flashdata('failed', ' Data sudah ada');
        $redirectUrl = (!empty($id)) ? "manage/position/edit/$id" : "manage/position";
        redirect($redirectUrl);
      }

      $params['position_code'] = $positionCode;
      $params['position_name'] = $positionName;
      $params['position_majors_id'] = $positionMajorId;

      $status = $this->Position_model->add($params);
      $paramsupdate['position_id'] = $status;
      $this->Position_model->add($paramsupdate);


      // activity log
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('user_id'),
          'log_module' => 'Jabatan Pegawai',
          'log_action' => $data['operation'],
          'log_info' => 'ID:null;Title:' . $params['position_code']
        )
      );

      $this->session->set_flashdata('success', $data['operation'] . ' Jabatan Pegawai berhasil');
      redirect('manage/position');
    } else {
      if ($this->input->post('position_id')) {
        redirect('manage/position/edit/' . $this->input->post('position_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $data['position'] = $this->Position_model->get(array('id' => $id));
        $data['majors'] = $this->Student_model->get_majors();
      }
      $data['title'] = $data['operation'] . ' Jabatan Pegawai';
      $data['main'] = 'position/position_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    if ($_POST) {

      $this->Position_model->delete($id);
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Jabatan Pegawai',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delCode')
        )
      );
      $this->session->set_flashdata('success', 'Hapus Jabatan pegawai berhasil');
      redirect('manage/position');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/position/edit/' . $id);
    }
  }
}
