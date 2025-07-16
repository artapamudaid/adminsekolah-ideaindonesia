<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Period_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('period/Period_model', 'payment/Payment_model', 'pos/Pos_model', 'logs/Logs_model'));
    $this->load->library('upload');
  }

  // period view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['period'] = $f['n'];
    }

    //$paramsPage = $params;
    //$params['limit'] = 5;
    //$params['offset'] = $offset;
    $data['period'] = $this->Period_model->get($params);

    //$config['per_page'] = 5;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/period/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Period_model->get($paramsPage));
    //$this->pagination->initialize($config);

    $data['title'] = 'Tahun Ajaran';
    $data['main'] = 'period/period_list';
    $this->load->view('manage/layout', $data);
  }


  // Add period and Update
  public function add($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('period_start', 'Tahun Ajaran', 'trim|required|xss_clean');
    //$this->form_validation->set_rules('period_status', 'Status', 'trim|required|xss_clean');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('period_id')) {
        $params['period_id'] = $this->input->post('period_id');
      } else {
        $start = $this->input->post('period_start');
        $cekPeriod = $this->db->query(
          "SELECT * 
              FROM period
              WHERE period_start = $start"
        )->row_array();

        if ($cekPeriod) {
          $this->session->set_flashdata('failed', 'Tahun Ajaran ' . $start . ' sudah ada');
          redirect('manage/period');
        }
      }

      $params['period_start'] = $this->input->post('period_start');
      $params['period_end'] = $this->input->post('period_end');
      $params['period_status'] = 0;

      $status = $this->Period_model->add($params);
      $paramsupdate['period_id'] = $status;
      $this->Period_model->add($paramsupdate);

      // activity log
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('user_id'),
          'log_module' => 'Tahun Ajaran',
          'log_action' => $data['operation'],
          'log_info' => 'ID:null;Title:' . $params['period_start'] . '/' . $params['period_end']
        )
      );

      if ($this->input->post('generate_payment')) {
        $generatePayment = $this->input->post('generate_payment');
        if ($generatePayment == 'YA') {
          $pos = $this->Pos_model->get();

          foreach ($pos as $posRow) {
            $payment = $this->db->query("SELECT payment_type, payment_mode FROM `payment` WHERE pos_pos_id = '" . $posRow['pos_id'] . "' GROUP BY pos_pos_id")->row_array();

            $period = $this->db->query("SELECT MAX(period_id) AS period_id FROM period")->row_array();

            if (!empty($payment)) {
              $input = array(
                'payment_type'          => $payment['payment_type'],
                'payment_mode'          => $payment['payment_mode'],
                'period_period_id'      => $period['period_id'],
                'pos_pos_id'            => $posRow['pos_id'],
                'payment_input_date'    => date("Y-m-d h:i:s"),
                'payment_last_update'   => date("Y-m-d h:i:s"),
              );

              $this->db->insert('payment', $input);
            }
          }
        }
      }

      $this->session->set_flashdata('success', $data['operation'] . ' Tahun Ajaran berhasil');
      redirect('manage/period');
    } else {
      if ($this->input->post('period_id')) {
        redirect('manage/period/edit/' . $this->input->post('period_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $data['period'] = $this->Period_model->get(array('id' => $id));
      }
      $data['title'] = $data['operation'] . ' Tahun Ajaran';
      $data['main'] = 'period/period_add';
      $this->load->view('manage/layout', $data);
    }
  }

  function period_active($id = NULL)
  {

    $non = array(
      'period_status' => 0,
      'status_active' => TRUE
    );

    $this->Period_model->add($non);

    $active = array(
      'period_id' => $id,
      'period_status' => 1
    );

    $status = $this->Period_model->add($active);



    if ($this->input->is_ajax_request()) {
      echo $status;
    } else {
      $this->session->set_flashdata('success', 'Aktif Tahun Ajaran Berhasil');
      redirect('manage/period');
    }
  }


  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    if ($_POST) {

      $payment = $this->Payment_model->get(array('period_id' => $this->input->post('period_id')));

      if (count($payment) > 0) {
        $this->session->set_flashdata('failed', 'Tahun Ajaran tidak dapat dihapus');
        redirect('manage/period');
      }

      $this->Period_model->delete($this->input->post('period_id'));
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Tahun Ajaran',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
        )
      );
      $this->session->set_flashdata('success', 'Hapus Tahun Ajaran berhasil');
      redirect('manage/period');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/period/edit/' . $id);
    }
  }
}
