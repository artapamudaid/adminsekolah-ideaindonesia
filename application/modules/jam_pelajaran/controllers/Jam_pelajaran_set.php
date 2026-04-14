<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jam_pelajaran_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('jam_pelajaran/Jam_pelajaran_model', 'setting/Setting_model'));
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
      $params['jam_pelajaran_name'] = $f['n'];
    }

    $data['jam_pelajaran'] = $this->Jam_pelajaran_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $config['base_url'] = site_url('manage/jam_pelajaran/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Jam Pelajaran';
    $data['main'] = 'jam_pelajaran/jam_pelajaran_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $jam_pelajaranName = sanitizeStrict($_POST['jam_pelajaran_name']);
      $jam_pelajaranStart = sanitizeStrict($_POST['jam_pelajaran_start']);
      $jam_pelajaranEnd = sanitizeStrict($_POST['jam_pelajaran_end']);
      $cpt = count($jam_pelajaranName);

      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {
        if (empty($jam_pelajaranName[$i])) {
          $skipped++;
          continue;
        }

        $colWhere = [
          'jam_pelajaran_start' => $jam_pelajaranStart[$i],
          'jam_pelajaran_end' => $jam_pelajaranEnd[$i],
        ];
        if ($this->General_model->checkDataExists('jam_pelajaran', $colWhere)) {
          $skipped++;
          continue;
        }

        $params['jam_pelajaran_name'] = $jam_pelajaranName[$i];
        $params['jam_pelajaran_start'] = $jam_pelajaranStart[$i];
        $params['jam_pelajaran_end'] = $jam_pelajaranEnd[$i];

        $insert = $this->Jam_pelajaran_model->add($params);
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
    redirect('manage/jam_pelajaran');
  }

  public function add($id = NULL)
  {
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST) {

      if ($this->input->post('jam_pelajaran_id')) {
        $id = sanitizeStrict($this->input->post('jam_pelajaran_id'));
        $params['jam_pelajaran_id'] = $id;
      }

      $jampelName = sanitizeStrict($this->input->post('jam_pelajaran_name'));
      $jampelStart = sanitizeStrict($this->input->post('jam_pelajaran_start'));
      $jampelEnd = sanitizeStrict($this->input->post('jam_pelajaran_end'));

      $colWhere = [
        'jam_pelajaran_start' => $jampelStart,
        'jam_pelajaran_end' => $jampelEnd,
      ];

      if (!is_null($id)) {
        $colWhere['jam_pelajaran_id !='] = $id;
      }

      if ($this->General_model->checkDataExists("jam_pelajaran", $colWhere)) {
        $this->session->set_flashdata('failed', 'Data Jam Pelajaran sudah ada sebelumnya dengan jam yang sama.');
        $redirectUrl = is_null($id) ? 'manage/jam_pelajaran' : 'manage/jam_pelajaran/edit/' . $id;
        redirect($redirectUrl);
      }

      $params['jam_pelajaran_name'] = $jampelName;
      $params['jam_pelajaran_start'] = $jampelStart;
      $params['jam_pelajaran_end'] = $jampelEnd;
      $status = $this->Jam_pelajaran_model->add($params);


      $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Jam Pelajaran');
      redirect('manage/jam_pelajaran');
    } else {
      if ($this->input->post('jam_pelajaran_id')) {
        redirect('manage/jam_pelajaran/edit/' . $this->input->post('jam_pelajaran_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Jam_pelajaran_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/jam_pelajaran');
        } else {
          $data['jam_pelajaran'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Keterangan Jam Pelajaran';
      $data['main'] = 'jam_pelajaran/jam_pelajaran_add';
      $this->load->view('manage/layout', $data);
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {

    if ($_POST) {
      $namaPel = $this->input->post('delName');
      $this->Jam_pelajaran_model->delete($id);
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'user',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $namaPel
        )
      );
      $this->session->set_flashdata('success', 'Hapus Jam Pelajaran berhasil');
      redirect('manage/jam_pelajaran');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/jam_pelajaran/edit/' . $id);
    }
  }
}
