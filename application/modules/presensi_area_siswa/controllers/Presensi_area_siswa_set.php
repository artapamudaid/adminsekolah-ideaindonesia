<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presensi_area_siswa_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('presensi_area_siswa/Presensi_area_siswa_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  public function index()
  {
    $this->load->library('pagination');

    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;

    $params = array();
    if (isset($f['n']) && !empty($f['n'])) {
      $params['presensi_area_search'] = $f['n'];
    }

    $data['presensi_area_siswa'] = $this->Presensi_area_siswa_model->get($params);
    $config['base_url'] = site_url('manage/presensi_area_siswa');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Setting Presensi Area Siswa';
    $data['main'] = 'presensi_area_siswa/presensi_area_siswa_list';
    $this->load->view('manage/layout', $data);
  }

  // Update / Add
  public function add($id = NULL)
  {
    if (is_null($id) && !$this->input->post('id')) {
      $existing = $this->Presensi_area_siswa_model->get();
      if (!empty($existing)) {
        $this->session->set_flashdata('failed', 'Data setting presensi area siswa sudah ada.');
        redirect('manage/presensi_area_siswa');
      }
    }

    $this->load->library('form_validation');

    $this->form_validation->set_rules('id_area_absensi', 'Area Absen', 'trim|required|xss_clean');
    $this->form_validation->set_rules('radius', 'Jarak Radius Absen', 'trim|required|numeric|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      $data_post = array(
        'id_area_absensi' => $this->input->post('id_area_absensi'),
        'radius' => $this->input->post('radius')
      );

      if ($this->input->post('id')) {
        $data_post['id'] = $this->input->post('id');
      } else if (!is_null($id)) {
        $data_post['id'] = $id;
      }

      $status = $this->Presensi_area_siswa_model->add($data_post);

      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Presensi Area Siswa',
          'log_action' => $data['operation'],
          'log_info' => 'ID:' . $status
        )
      );

      $this->session->set_flashdata('success', $data['operation'] . ' Setting Presensi Area Siswa Berhasil');
      redirect('manage/presensi_area_siswa');
    } else {
      if (!is_null($id)) {
        $object = $this->Presensi_area_siswa_model->get(array('id' => $id));
        if ($object != NULL) {
          $data['presensi_area_siswa'] = $object;
        }
      }
      $data['area_absen'] = $this->Presensi_area_siswa_model->get_area_absen();
      $data['title'] = $data['operation'] . ' Setting Presensi Area Siswa';
      $data['main'] = 'presensi_area_siswa/presensi_area_siswa_edit';
      $this->load->view('manage/layout', $data);
    }
  }

  // Delete
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') == USER) {
      redirect('manage');
    }

    if ($_POST) {
      $this->Presensi_area_siswa_model->delete($id);
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Presensi Area Siswa',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id
        )
      );
      $this->session->set_flashdata('success', 'Hapus Setting Presensi Area Siswa berhasil');
    }
    redirect('manage/presensi_area_siswa');
  }
}

