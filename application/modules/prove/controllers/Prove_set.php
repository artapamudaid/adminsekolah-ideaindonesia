<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Prove_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('prove/Prove_model', 'setting/Setting_model', 'student/Student_model'));
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
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['prove_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }

    $data['prove']      = $this->Prove_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);

    $config['base_url'] = site_url('manage/prove/index');
    $config['suffix']   = '?' . http_build_query($_GET, '', "&");


    $data['title'] = 'Bukti Bayar Transfer';
    $data['main'] = 'prove/prove_list';
    $this->load->view('manage/layout', $data);
  }

  function datatable_prove()
  {
    $m = $this->input->post('m');
    $c = $this->input->post('c');
    $s = $this->input->post('s');

    $data = array();

    if (isset($m) && !empty($m) && $m != 'all') {
      $data['majors_id'] = $m;
    }

    if (isset($c) && !empty($c) && $c != 'all') {
      $data['class_id'] = $c;
    }

    if (isset($s) && $s !== '' && $s != 'all') {
      $data['status'] = $s;
    }

    $draw = $this->input->post('draw');
    $length = $this->input->post('length') ? $this->input->post('length') : 10;
    $start = $this->input->post('start') ? $this->input->post('start') : 0;
    $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

    if (!empty($search)) {
      $data['search'] = $search;
    }

    if (isset($_POST['order'][0]['column'])) {
      $order_column_index = $_POST['order'][0]['column'];
      $order_dir = $_POST['order'][0]['dir'];
      $columns = [
        0 => 'prove.prove_id',
        1 => 'student_nis',
        2 => 'student_full_name',
        3 => 'class_name',
        4 => 'prove_date',
        5 => 'prove_img',
        6 => 'prove_note',
        7 => 'prove_status',
        8 => 'prove.prove_id'
      ];
      $data['order_column'] = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'prove_date';
      $data['order_dir'] = $order_dir;
    } else {
      $data['order_column'] = 'prove_date';
      $data['order_dir'] = 'desc';
    }

    $recordsTotal = $this->Prove_model->count_all(array());
    $recordsFiltered = $this->Prove_model->count_all($data);

    $list = $this->Prove_model->list_prove($length, $start, $search, $data);

    $data_output = array();
    $i = $start + 1;

    foreach ($list as $row) {
      $date = date_create($row['prove_date']);
      $formatted_date = date_format($date, "d-m-Y H:i:s");

      $img_url = upload_prove_url($row['prove_img']);
      $bukti_bayar = '<a href="' . $img_url . '" target="_blank"><img src="' . $img_url . '" width="70"></a>';

      $status_label = '';
      if ($row['prove_status'] == 1) {
        $status_label = '<label class="label label-success">DIVERIFIKASI [Sudah Diverifikasi Admin]</label>';
      } else if ($row['prove_status'] == 0) {
        $status_label = '<label class="label label-danger">DIBATALKAN [Dibatalkan oleh Pengirim]</label>';
      } else if ($row['prove_status'] == 2) {
        $status_label = '<label class="label label-warning">DIBAYAR [Menunggu Verifikasi Admin]</label>';
      } else if ($row['prove_status'] == 3) {
        $status_label = '<label class="label label-danger">DITOLAK [Bukti Transfer Ditolak Admin]</label>';
      }

      $aksi = '';
      if ($row['prove_status'] == 2) {
        $aksi = '<a href="#verificationProve' . $row['prove_id'] . '" data-toggle="modal" class="btn btn-xs btn-success">Verifikasi Transaksi</a>';
        $aksi .= '<div class="modal modal-default fade" id="verificationProve' . $row['prove_id'] . '">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                          <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi Verifikasi Transfer Wali Murid</h3>
                        </div>
                        <div class="modal-body">
                          <center>
                            <a href="' . site_url('manage/prove/approve/' . $row['prove_id']) . '" type="button" class="btn btn-success">Verifikasi</a>
                            <a href="' . site_url('manage/prove/ignore/' . $row['prove_id']) . '" type="button" class="btn btn-danger">Tolak</a>
                          </center>
                        </div>
                        <div class="modal-footer">
                        </div>
                      </div>
                    </div>
                  </div>';
      }

      $data_output[] = array(
        $i++,
        $row['student_nis'],
        $row['student_full_name'],
        $row['majors_short_name'] . ' - ' . $row['class_name'],
        $formatted_date,
        $bukti_bayar,
        $row['prove_note'],
        $status_label,
        $aksi
      );
    }

    $output = array(
      "draw" => intval($draw),
      "recordsTotal" => $recordsTotal,
      "recordsFiltered" => $recordsFiltered,
      "data" => $data_output
    );

    echo json_encode($output);
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select style="width: 200px;" id="c" name="c" class="form-control" required>
    			<option value="">--- Pilih Kelas ---</option>
    			<option value="all">Semua Kelas</option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>';
  }

  public function approve($id = NULL)
  {

    $params['prove_id']           = $id;
    $params['prove_note']         = "Terima kasih, pembayaran Telah Diverifikasi";
    $params['prove_status']       = '1';

    $status = $this->Prove_model->add($params);

    $this->session->set_flashdata('success', 'Transaksi Berhasil Diverifkasi');

    redirect('manage/prove');
  }

  public function ignore($id = NULL)
  {

    $params['prove_id']           = $id;
    $params['prove_note']         = "Mohon maaf, bukti transfer kami tolak karena tidak sesuai";
    $params['prove_status']       = '3';

    $status = $this->Prove_model->add($params);

    $this->session->set_flashdata('success', 'Transaksi Berhasil Ditolak');

    redirect('manage/prove');
  }

  // Setting Upload File Requied
  function do_upload($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/prove/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size'] = '1024';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }
}
