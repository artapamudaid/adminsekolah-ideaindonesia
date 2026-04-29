<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Standar_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('standar/Standar_model', 'student/Student_model', 'setting/Setting_model'));
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
      $params['name'] = $f['n'];
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
    }

    $data['standar'] = $this->Standar_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['majors'] = $this->Student_model->get_majors();

    $data['title'] = 'Setting Program/Standar';
    $data['main'] = 'standar/standar_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $name     = sanitizeStrict($_POST['name']);
      $majors   = sanitizeStrict($_POST['standar_majors_id']);

      $cpt = count($name);
      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {

        if (empty($name[$i])) {
          $skipped++;
          continue;
        }

        $colWhere = ['name' => $name[$i], 'standar_majors_id' => $majors[$i]];
        if ($this->General_model->checkDataExists("standar", $colWhere)) {
          $skipped++;
          continue;
        }
        $params['standar_majors_id']   = $majors;
        $params['name']           = $name[$i];

        $insert = $this->Standar_model->add($params);
        if (!$insert) {
          $skipped++;
          continue;
        }

        $added++;
      }

      if ($added > 1) {
        $msg = "$added Program/Standar berhasil ditambahkan";
      } elseif ($added == 1) {
        $msg = "1 Program/Standar berhasil ditambahkan";
      } else {
        $msg = "Tidak ada yang ditambahkan";
      }

      if ($skipped > 0) {
        $msg .= "<br>($skipped Program/Standar dilewati)";
      }

      $this->session->set_flashdata('success', $msg);
    }

    redirect('manage/standar');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('name', 'Nama Program/Standar', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('id')) {
        $id = sanitizeStrict($this->input->post('id'));
        $params['id'] = $id;
      }

      $name = sanitizeStrict($this->input->post('name'));
      $standar_majors_id = sanitizeStrict($this->input->post('standar_majors_id'));

      $colWhere = [
        'name' => $name,
        'standar_majors_id' => $standar_majors_id
      ];

      if (!empty($id)) {
        $colWhere['id <>'] = $id;
      }

      if ($this->General_model->checkDataExists("standar", $colWhere)) {
        $this->session->set_flashdata('failed', 'Data sudah ada');
        redirect('manage/standar/edit/' . $id);
      }

      $params['name'] = $name;
      $params['standar_majors_id'] = $standar_majors_id;

      $status = $this->Standar_model->add($params);

      if ($status) {

        $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Program/Standar');
      } else {
        $this->session->set_flashdata('failed', $data['operation'] . ' Keterangan Program/Standar');
      }
      redirect('manage/standar');
    } else {
      if ($this->input->post('id')) {
        redirect('manage/standar/edit/' . $this->input->post('id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Standar_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/standar');
        } else {
          $data['standar'] = $object;
        }
      }

      $data['majors'] = $this->Student_model->get_majors();

      $data['title'] = $data['operation'] . ' Keterangan Program/Standar';

      $data['main'] = 'standar/standar_add';

      $this->load->view('manage/layout', $data);
    }
  }

  // Delete to database
  public function delete($id = NULL)
  {

    if ($this->session->userdata('uroleid') != 1) {
      redirect('manage');
    }

    $cek  = $this->db->query("SELECT * FROM aktivitas WHERE aktivitas_standar_id = '$id'");

    if ($cek->num_rows() > 0) {
      $this->session->set_flashdata('failed', 'Gagal Hapus Program/Standar. Masih Ada Setting yang Belum Dihapus');
      redirect('manage/standar');
    }

    if ($_POST) {
      $this->Standar_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Program/Standar berhasil');
      redirect('manage/standar');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/standar/edit/' . $id);
    }
  }

  public function view_item($id = NULL)
  {

    $params = array();

    $item = $this->db->query("SELECT name, standar_majors_id, majors_short_name
                            FROM standar a
                            JOIN majors b ON a.standar_majors_id = b.majors_id
                            WHERE id = $id")->row_array();

    $majors_id = $item['standar_majors_id'];

    $data['account'] = $this->db->query("SELECT * FROM account
                                          WHERE account_category = '2'
                                          AND account_majors_id = '$majors_id'
                                          AND account_code LIKE '5%'
                                          ORDER BY account_code ASC")->result();

    $data['standar']  = $this->db->query("SELECT * FROM standar WHERE id = '$id'")->result_array();

    $data['list']   = $this->Standar_model->get_item(array('aktivitas_id' => $id));

    $data['judul']    = $item['name'] . ' ' . $item['majors_short_name'];
    $data['title']    = 'aktivitas';
    $data['main']     = 'standar/standar_view';

    $this->load->view('manage/layout', $data);
  }

  public function add_glob_item()
  {
    $itemID = null;
    if ($_POST == TRUE) {
      $itemID      = sanitizeStrict($_POST['standar_id']);
      $itemKode    = sanitizeStrict($_POST['kode']);
      $itemName    = sanitizeStrict($_POST['name']);
      $itemAccount = sanitizeStrict($_POST['aktivitas_account_id']);

      $cpt = count($itemKode);
      $added = 0;
      $skipped = 0;
      for ($i = 0; $i < $cpt; $i++) {

        if (empty($itemKode[$i])) {
          $skipped++;
          continue;
        }

        $colWhere = [
          'aktivitas_standar_id' => $itemID,
          'aktivitas_kode' => $itemKode[$i],
          'aktivitas_name' => $itemName[$i],
          'aktivitas_account_id' => $itemAccount[$i]
        ];

        if ($this->General_model->checkDataExists("aktivitas", $colWhere)) {
          $skipped++;
          continue;
        }

        $params['aktivitas_standar_id']  = $itemID;
        $params['aktivitas_kode']        = $itemKode[$i];
        $params['aktivitas_name']        = $itemName[$i];
        $params['aktivitas_account_id']  = $itemAccount[$i];

        $insert = $this->Standar_model->add_item($params);
        if (!$insert) {
          $skipped++;
          continue;
        }

        $added++;
      }

      if ($added > 1) {
        $msg = "$added Aktivitas berhasil ditambahkan";
      } elseif ($added == 1) {
        $msg = "1 Aktivitas berhasil ditambahkan";
      } else {
        $msg = "Tidak ada yang ditambahkan";
      }

      if ($skipped > 0) {
        $msg .= "<br>($skipped Aktivitas dilewati)";
      }

      $this->session->set_flashdata('success', $msg);
      $itemID = !empty($itemID) ? $itemID : sanitizeStrict($this->input->post('standar_id'));
    }

    redirect('manage/standar/view_item/' . $itemID);
  }

  public function delete_item($id = NULL)
  {

    if ($this->session->userdata('uroleid') != 1) {
      redirect('manage');
    }

    $itemID = $this->input->post('standar_id');
    $this->Standar_model->delete_item($id);
    $this->session->set_flashdata('success', ' Hapus Item Pembayaran Berhasil');
    redirect('manage/standar/view_item/' . $itemID);
  }
}
