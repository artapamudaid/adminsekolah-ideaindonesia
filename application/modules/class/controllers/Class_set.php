<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Class_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'setting/Setting_model'));
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
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['class_name'] = $f['n'];
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
      //$params['majors_id'] = $s['m'];
    }

    //$paramsPage = $params;
    //$params['limit'] = 10;
    //$params['offset'] = $offset;
    $data['classes'] = $this->Student_model->get_class($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/class/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    $data['majors'] = $this->Student_model->get_majors();

    $data['title'] = 'Kelas';
    $data['main'] = 'class/class_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $className = sanitizeStrict($_POST['class_name']);
      $majorsMajorsId = sanitizeStrict($_POST['majors_majors_id']);
      $cpt = count($className);
      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {
        if (empty($className[$i])) {
          $skipped++;
          continue;
        }

        $colWhere = [
          'class_name' => $className[$i],
          'majors_majors_id' => $majorsMajorsId,
        ];
        if ($this->General_model->checkDataExists("class", $colWhere)) {
          $skipped++;
          continue;
        }
        $params['class_name'] = $className[$i];
        $params['majors_majors_id'] = $majorsMajorsId;



        $insert = $this->Student_model->add_class($params);
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
    redirect('manage/class');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('class_name', 'Nama Kelas', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('class_id')) {
        $id = sanitizeStrict($this->input->post('class_id'));
        $params['class_id'] = $id;
      }
      $className = sanitizeStrict($this->input->post('class_name'));
      $majorsMajorsId = sanitizeStrict($this->input->post('majors_majors_id'));

      $colWhere = [
        'class_name' => $className,
        'majors_majors_id' => $majorsMajorsId,
        'class_id != ' => $id,
      ];
      if ($this->Student_model->checkDataExists("class", $colWhere)) {
        $this->session->set_flashdata('failed', 'Data sudah ada');
        $redirectUrl = ($id) ? "manage/class/edit/$id" : "manage/class";
        redirect($redirectUrl);
      }

      $params['class_name'] = $className;
      $params['majors_majors_id'] = $majorsMajorsId;
      $status = $this->Student_model->add_class($params);


      $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Kelas');
      redirect('manage/class');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('class_id')) {
        redirect('manage/class/edit/' . $this->input->post('class_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_class(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/class');
        } else {
          $data['class'] = $object;
        }
      }
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Keterangan Kelas';
      $data['main'] = 'class/class_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    $siswa = $this->Student_model->get(array('class_id' => $id));

    if ($_POST) {

      if (count($siswa) > 0) {
        $this->session->set_flashdata('failed', 'Data Kelas tidak dapat dihapus');
        redirect('manage/class');
      }

      $this->Student_model->delete_class($id);
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
      $this->session->set_flashdata('success', 'Hapus Kelas berhasil');
      redirect('manage/class');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/class/edit/' . $id);
    }
  }
}
