<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Template_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('template/Template_model', 'setting/Setting_model', 'student/Student_model'));
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
      $params['content'] = $f['n'];
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];
      $param['id'] = $s['m'];
    }

    $data['templates'] = $this->Template_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $config['base_url'] = site_url('manage/template/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Template Notifikasi WA';
    $data['main'] = 'template/template_list';
    $this->load->view('manage/layout', $data);
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('content', 'Nama Template Notifikasi WA', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('id')) {
        $params['id'] = $this->input->post('id');
      } else {
        if ($this->input->post('location') != 'PENGUMUMAN') {
          $check = $this->db->get_where('templates', array('location' => $this->input->post('location')))->num_rows();
          if ($check > 0) {
            $this->session->set_flashdata('failed', 'Tambah template ' . $this->input->post('location') . ' yang sudah ada.');
            redirect('manage/template');
          }
        }
      }

      $params['content'] = $this->input->post('content');
      $params['location'] = $this->input->post('location');
      $params['title'] = $this->input->post('title');
      $params['is_active'] = $this->input->post('is_active');

      $status = $this->Template_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Template Notif WA');
      redirect('manage/template');
    } else {
      if ($this->input->post('id')) {
        redirect('manage/template/edit/' . $this->input->post('id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Template_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/template');
        } else {
          $data['template']   = $object;
          $data['majors']   = $this->Student_model->get_majors();
        }
      }

      $data['title'] = $data['operation'] . ' Template Notif WA';

      $data['locations'] = array(
        array(
          'kode' => 'PENGUMUMAN',
          'value' => 'PENGUMUMAN'
        ),
        array(
          'kode' => 'PAYOUT',
          'value' => 'PEMBAYARAN'
        ),
        array(
          'kode' => 'BILLING',
          'value' => 'TAGIHAN'
        ),
        array(
          'kode' => 'BANKING_IN',
          'value' => 'SETOR TABUNGAN'
        ),
        array(
          'kode' => 'BANKING_OUT',
          'value' => 'TARIK TABUNGAN'
        ),
        array(
          'kode' => 'SLIP',
          'value' => 'SLIP GAJI'
        )
      );

      $data['main'] = 'template/template_add';
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

      $location = $this->input->post('location', TRUE);

      if ($location != "PENGUMUMAN") {
        $this->session->set_flashdata('failed', 'Template ' . $this->input->post('location') . ' tidak dapat dihapus, karena template tersebut wajib ada');
        redirect('manage/template');
      }

      $this->Template_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Template Notifikasi WA berhasil');
      redirect('manage/template');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/template/edit/' . $id);
    }
  }
}
