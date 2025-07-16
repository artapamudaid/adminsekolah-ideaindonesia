<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Batchpayment_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('batchpayment/Batchpayment_model', 'student/Student_model', 'setting/Setting_model', 'period/Period_model'));
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


    $data['paket'] = $this->Batchpayment_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['period'] = $this->Period_model->get();
    $data['majors'] = $this->Student_model->get_majors();

    $data['title'] = 'Setting Paket Pembayaran';
    $data['main'] = 'batchpayment/batchpayment_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {
      $name     = $_POST['name'];
      $majors   = $_POST['bp_majors_id'];
      $period   = $_POST['bp_period_id'];

      $q = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$majors'")->row_array();

      $cpt = count($_POST['name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['bp_majors_id']   = $majors;
        $params['bp_period_id']   = $period;
        $params['name']           = $name[$i] . ' ' . $q['majors_short_name'];

        $this->Batchpayment_model->add($params);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Paket Pembayaran Berhasil');
    redirect('manage/batchpayment');
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('name', 'Nama Paket Pembayaran', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('id')) {
        $params['id'] = $this->input->post('id');
      }

      $params['name'] = $this->input->post('name');
      $params['bp_majors_id'] = $this->input->post('bp_majors_id');
      $params['bp_period_id'] = $this->input->post('bp_period_id');

      $status = $this->Batchpayment_model->add($params);

      if ($status) {

        $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Paket Pembayaran');
      } else {
        $this->session->set_flashdata('failed', $data['operation'] . ' Keterangan Paket Pembayaran');
      }
      redirect('manage/batchpayment');
    } else {
      if ($this->input->post('id')) {
        redirect('manage/batchpayment/edit/' . $this->input->post('id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Batchpayment_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/batchpayment');
        } else {
          $data['paket'] = $object;
        }
      }

      $data['period'] = $this->Period_model->get();
      $data['majors'] = $this->Student_model->get_majors();

      $data['title'] = $data['operation'] . ' Keterangan Paket Pembayaran';

      $data['main'] = 'batchpayment/batchpayment_add';

      $this->load->view('manage/layout', $data);
    }
  }

  // Delete to database
  public function delete($id = NULL)
  {

    if ($this->session->userdata('uroleid') != 1) {
      redirect('manage');
    }

    $cek_pos  = $this->db->query('SELECT * FROM batch_item WHERE batch_item_batchpayment_id ='.$id);

    if ($cek_pos->num_rows() > 0) {
      $this->session->set_flashdata('failed', 'Gagal Hapus Paket Pembayaran. Masih Ada Setting yang Belum Dihapus');
      redirect('manage/batchpayment');
    }

    if ($_POST) {
      $this->Batchpayment_model->delete($id);
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
      $this->session->set_flashdata('success', 'Hapus Paket Pembayaran berhasil');
      redirect('manage/batchpayment');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/batchpayment/edit/' . $id);
    }
  }

  public function view_item($id = NULL)
  {

    $params = array();

    $item = $this->db->query("SELECT name, bp_period_id, bp_majors_id, period_start, period_end
                            FROM batchpayment a
                            JOIN period b 
                            ON a.bp_period_id = b.period_id
                            WHERE id = $id")->row_array();

    $majors_id = $item['bp_majors_id'];
    $period_id = $item['bp_period_id'];

    $data['payment']  = $this->db->query("SELECT payment_id, pos_name, period_start, period_end FROM payment a 
                                          JOIN pos b ON b.pos_id = a.pos_pos_id 
                                          JOIN account c ON c.account_id = b.account_account_id 
                                          JOIN period d ON d.period_id = a.period_period_id
                                          WHERE c.account_majors_id = '$majors_id'
                                          AND a.period_period_id = '$period_id'
                                          AND a.payment_is_batch = '1'
                                          AND a.payment_mode = 'TETAP'
                                          ORDER BY b.pos_id ASC")->result_array();

    $data['list']   = $this->Batchpayment_model->get_item(array('batch_id' => $id));

    $data['judul']    = $item['name'] . ' ' . $item['period_start'] . '/' . $item['period_end'];
    $data['title']    = 'Setting Item Pembayaran';
    $data['main']     = 'batchpayment/batchpayment_view';

    $this->load->view('manage/layout', $data);
  }

  public function add_glob_item()
  {
    if ($_POST == TRUE) {
      $itemID           = $_POST['batchpayment_id'];
      $itemPaymentID    = $_POST['payment_id'];

      $cpt = count($_POST['payment_id']);
      for ($i = 0; $i < $cpt; $i++) {

        $params['batch_item_batchpayment_id']    = $itemID;
        $params['batch_item_payment_id'] = $itemPaymentID[$i];

        $this->Batchpayment_model->add_item($params);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Item Pembayaran Berhasil');
    redirect('manage/batchpayment/view_item/' . $itemID);
  }

  public function delete_item($id = NULL)
  {

    if ($this->session->userdata('uroleid') != 1) {
      redirect('manage');
    }

    $itemID = $this->input->post('batchpayment_id');
    $this->Batchpayment_model->delete_item($id);
    $this->session->set_flashdata('success', ' Hapus Item Pembayaran Berhasil');
    redirect('manage/batchpayment/view_item/' . $itemID);
  }
}
