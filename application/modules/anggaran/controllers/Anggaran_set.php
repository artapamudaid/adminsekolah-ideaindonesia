<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Anggaran_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('anggaran/Anggaran_model', 'student/Student_model', 'bulan/Bulan_model', 'period/Period_model', 'logs/Logs_model'));
  }

  // anggaran view in list
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
    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['search'] = $f['n'];
    }

    if (isset($s['p']) && !empty($s['p']) && $s['p'] != 'all') {
      $params['anggaran_period_id'] = $s['p'];
    } else if (isset($s['p']) && !empty($s['p']) && $s['p'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['account_majors_id'] = $s['m'];
    } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
    }

    $data['anggaran'] = $this->Anggaran_model->get($params);

    $data['majors'] = $this->Student_model->get_majors();
    $data['period'] = $this->Period_model->get();

    $data['title'] = 'Anggaran Belanja';
    $data['main'] = 'anggaran/anggaran_list';
    $this->load->view('manage/layout', $data);
  }

  public function get_account()
  {

    $majors_id = $this->input->post('id_majors');
    $account = $this->db->query("SELECT account_id, account_code, account_description
                              FROM account
                              WHERE account_majors_id = '$majors_id'
                              AND account_code LIKE '5-5%'
                              AND account_note != 0")->result_array();

    echo '<div class="form-group">
				<label>Akun <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
				<select name="account_id" class="form-control">
					<option value="">-Pilih Akun-</option>';
    foreach ($account as $row) {
      echo  '<option value="' . $row['account_id'] . '" >' . $row['account_code'] . ' - ' . $row['account_description'] . '</option>';
    }
    echo '</select>
			</div>';
  }

  // Add anggaran and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('account_id', 'Akun', 'trim|required|xss_clean');
    $this->form_validation->set_rules('period_id', 'Tahun Ajaran', 'trim|required|xss_clean');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('anggaran_id')) {
        $params['anggaran_id'] = $this->input->post('anggaran_id');
      } else {
        $params['anggaran_input_date'] = date('Y-m-d H:i:s');
      }

      $params['anggaran_last_update'] = date('Y-m-d H:i:s');
      $params['period_id'] = $this->input->post('period_id');
      $params['account_id'] = $this->input->post('account_id');

      $status = $this->Anggaran_model->add($params);

      $paramsupdate['anggaran_id'] = $status;
      $this->Anggaran_model->add($paramsupdate);

      // activity log
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('user_id'),
          'log_module' => 'Anggaran',
          'log_action' => $data['operation'],
          'log_info' => 'ID:null;Title:'
        )
      );

      $this->session->set_flashdata('success', $data['operation'] . ' Anggaran berhasil');
      redirect('manage/anggaran');
    } else {
      if ($this->input->post('anggaran_id')) {
        redirect('manage/anggaran/edit/' . $this->input->post('anggaran_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Anggaran_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/anggaran');
        } else {
          $data['anggaran'] = $object;
        }
        $id_unit = $object['majors_id'];

        $data['account'] = $this->db->query("SELECT account_id, account_code, account_description
        FROM account
        WHERE account_majors_id = '$id_unit'
        AND account_code LIKE '5-5%'
        AND account_note != 0")->result_array();
      }

      $data['majors'] = $this->Student_model->get_majors();
      $data['period'] = $this->Period_model->get();
      $data['title'] = $data['operation'] . ' Anggaran';
      $data['main'] = 'anggaran/anggaran_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // View data detail
  public function view_detail($id = NULL, $student_id = NULL)
  {

    if ($id == NULL) {
      redirect('manage/anggaran');
    }

    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;
    $params = array();

    $params['anggaran_id'] = $id;
    $params['group'] = TRUE;

    $data['student_id'] = $student_id;

    $data['detail'] = $this->Anggaran_model->get_detail($params);
    $data['anggaran'] = $this->Anggaran_model->get(array('id' => $id));

    $data['title'] = 'Nominal Anggaran';
    $data['main'] = 'anggaran/anggaran_view_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_anggaran_detail($id = NULL)
  {
    if ($id == NULL) {
      redirect('manage/anggaran');
    }
    $this->load->library('form_validation');

    $this->form_validation->set_rules('nominal[]', 'Tarif Bulanan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

    if ($_POST and $this->form_validation->run() == TRUE) {

      if (!$this->input->post('anggaran_id')) {

        $month = $this->Bulan_model->get_month();
        $anggaran_id = $this->input->post('anggaran_id');
        $nominal = $_POST['nominal'];
        $cpt = count($_POST['nominal']);
        $month = $_POST['month_id'];

        $check = $this->db->query("SELECT COUNT(anggaran_detail_anggaran_id) AS num FROM anggaran_detail WHERE anggaran_detail_anggaran_id = '$id'")->row_array();

        for ($i = 0; $i < $cpt; $i++) {
          $param['nominal'] = $nominal[$i];
          $param['month_id'] = $month[$i];
          $param['anggaran_id'] = $id;

          if ($check['num'] == 0) {
            $this->Anggaran_model->add_detail($param);
            $this->session->set_flashdata('success', ' Setting Tarif berhasil');
          } else {
            $this->session->set_flashdata('failed', ' Duplikat Data');
          }
        }
      }

      redirect('manage/anggaran/view_detail/' . $id);
    } else {
      $pay_id = $this->uri->segment(4);

      $data['anggaran'] = $this->Anggaran_model->get(array('id' => $id));
      $data['month'] = $this->Bulan_model->get_month();
      $data['title'] = 'Nominal Anggaran';
      $data['main'] = 'anggaran/anggaran_add_detail';
      $this->load->view('manage/layout', $data);
    }
  }

  public function edit_anggaran_detail($id = NULL)
  {
    if ($id == NULL) {
      redirect('manage/anggaran');
    }

    if ($_POST  == TRUE) {

      $nominal            = $_POST['nominal'];
      $anggaran_detail_id = $_POST['anggaran_detail_id'];
      $cpt                = count($_POST['nominal']);

      for ($i = 0; $i < $cpt; $i++) {
        $param['anggaran_detail_id']  = $anggaran_detail_id[$i];
        $param['nominal']             = $nominal[$i];

        $this->Anggaran_model->add_detail($param);
      }

      $this->session->set_flashdata('success', 'Edit Anggaran berhasil');
      redirect('manage/anggaran/view_detail/' . $id);
    } else {

      $data['anggaran'] = $this->Anggaran_model->get(array('id' => $id));
      $data['detail'] = $this->Anggaran_model->get_anggaran(array('anggaran_id' => $id));
      $data['title'] = 'Edit Nominal Anggaran';
      $data['main'] = 'anggaran/anggaran_edit_detail';
      $this->load->view('manage/layout', $data);
    }
  }

  public function delete_anggaran_detail($id = NULL, $student_id = NULL)
  {
    $this->db->query("DELETE FROM anggaran_detail WHERE anggaran_detail_anggaran_id = '$id'");

    $this->session->set_flashdata('success', 'Hapus Nominal Anggaran berhasil');
    redirect('manage/anggaran/view_detail/' . $id);
  }

  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    if ($_POST) {

      $detail = $this->db->get_where('anggaran_detail', array('anggaran_detail_anggaran_id' => $id))->num_rows();

      if ($detail > 0) {
        redirect('manage/anggaran');
      }

      $this->Anggaran_model->delete($this->input->post('anggaran_id'));
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Anggaran',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
        )
      );
      $this->session->set_flashdata('success', 'Hapus Anggaran berhasil');
      redirect('manage/anggaran');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/anggaran/edit/' . $id);
    }
  }
}
