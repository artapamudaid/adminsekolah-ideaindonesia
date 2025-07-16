<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Worksheet_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array(
      'worksheet/Worksheet_model', 'standar/Standar_model', 'student/Student_model',
      'period/Period_model',  'setting/Setting_model', 'bulan/Bulan_model'
    ));
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

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
    }

    if (isset($s['p']) && !empty($s['p']) && $s['p'] != 'all') {
      $params['period_id'] = $s['p'];
    } else if (isset($s['p']) && !empty($s['p']) && $s['p'] == 'all') {
    }

    $data['worksheet'] = $this->Worksheet_model->get($params);

    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $config['base_url'] = site_url('manage/worksheet/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['majors'] = $this->Student_model->get_majors();
    $data['period'] = $this->Period_model->get();

    $data['title'] = 'Kertas Kerja';
    $data['main'] = 'worksheet/worksheet_list';
    $this->load->view('manage/layout', $data);
  }

  public function nominal_anggaran()
  {
    if ($_POST) {
      $getAnggaran = $this->Worksheet_model->get_anggaran();

      $dataAnggaran  = json_decode($getAnggaran, true);

      $rBulan = $dataAnggaran['rp_bulan'];
      $rBebas = $dataAnggaran['rp_bebas'];

      $sumBulan = 0;

      foreach ($rBulan as $dBulan) {

        $totalBulan = $dBulan['terbayar'] + $dBulan['belum'];

        $sumBulan += $totalBulan;
      }

      $sumBebas = 0;

      foreach ($rBebas as $dBebas) {

        $totalBebas = $dBebas['terbayar'] + $dBebas['belum'];

        $sumBebas += $totalBebas;
      }

      $sum = $sumBulan + $sumBebas;

      echo '<div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>Nominal Anggaran</label>
          <input type="number" required="" name="worksheet_nominal" id="worksheet_nominal" class="form-control" value="' . $sum . '">
        </div>
      </div>
    </div>';
    }
  }

  public function add_glob()
  {
    if ($_POST == TRUE) {

      $params['worksheet_majors_id']      = $_POST['worksheet_majors_id'];
      $params['worksheet_period_id']      = $_POST['worksheet_period_id'];
      $params['worksheet_nama_kepsek']    = $_POST['worksheet_nama_kepsek'];
      $params['worksheet_nama_bendahara'] = $_POST['worksheet_nama_bendahara'];
      $params['worksheet_nama_komite']    = $_POST['worksheet_nama_komite'];
      $params['worksheet_nip_kepsek']     = $_POST['worksheet_nip_kepsek'];
      $params['worksheet_nip_bendahara']  = $_POST['worksheet_nip_bendahara'];
      $params['worksheet_email_komite']   = $_POST['worksheet_email_komite'];
      $params['worksheet_nominal']        = $_POST['worksheet_nominal'];
      $params['worksheet_status']         = $_POST['worksheet_status'];

      $this->Worksheet_model->add($params);

      $this->session->set_flashdata('success', ' Tambah Kertas Kerja Berhasil');
      redirect('manage/worksheet');
    }
  }

  // Add User_customer and Update
  public function add($id = NULL)
  {
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST) {

      if ($this->input->post('worksheet_id')) {
        $params['worksheet_id'] = $this->input->post('worksheet_id');
      }

      // $params['worksheet_majors_id']      = $this->input->post('worksheet_majors_id');
      // $params['worksheet_period_id']      = $this->input->post('worksheet_period_id');
      $params['worksheet_nama_kepsek']    = $this->input->post('worksheet_nama_kepsek');
      $params['worksheet_nama_bendahara'] = $this->input->post('worksheet_nama_bendahara');
      $params['worksheet_nama_komite']    = $this->input->post('worksheet_nama_komite');
      $params['worksheet_nip_kepsek']     = $this->input->post('worksheet_nip_kepsek');
      $params['worksheet_nip_bendahara']  = $this->input->post('worksheet_nip_bendahara');
      $params['worksheet_email_komite']   = $this->input->post('worksheet_email_komite');
      // $params['worksheet_nominal']        = $this->input->post('worksheet_nominal');
      $params['worksheet_status']         = $this->input->post('worksheet_status');

      $status = $this->Worksheet_model->add($params);

      $this->session->set_flashdata('success', $data['operation'] . ' Kertas Kerja');
      redirect('manage/worksheet');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('worksheet_id')) {
        redirect('manage/worksheet/edit/' . $this->input->post('worksheet_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Worksheet_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/worksheet');
        } else {
          $data['worksheet'] = $object;
        }
      }

      // $data['majors'] = $this->Student_model->get_majors();
      // $data['period'] = $this->Period_model->get();

      $data['title'] = $data['operation'] . ' Kertas Kerja';
      $data['main'] = 'worksheet/worksheet_add';
      $this->load->view('manage/layout', $data);
    }
  }

  function set_active($id = NULL)
  {

    $active = array(
      'worksheet_id' => $id,
      'worksheet_status' => 'A'
    );

    $status = $this->Worksheet_model->add($active);

    if ($this->input->is_ajax_request()) {
      echo $status;
    } else {
      $this->session->set_flashdata('success', 'Kertas Kerja Diaktifkan');
      redirect('manage/worksheet');
    }
  }

  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }

    if ($_POST) {

      $this->Worksheet_model->delete($id);
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

      $this->session->set_flashdata('success', 'Hapus Kertas Kerja berhasil');
      redirect('manage/worksheet');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/worksheet/edit/' . $id);
    }
  }

  public function alokasi(Int $id = null)
  {


    if ($this->status_worksheet($id) != "A") {
      redirect('manage/worksheet');
    }

    $params = array();

    $data['worksheet']  = $this->Worksheet_model->get(array('id' => $id));

    $data['bulan'] = $this->db->query("SELECT * FROM month")->result_array();

    $data['satuan'] = $this->db->get('satuan')->result();

    $params['majors_id'] = $data['worksheet']['worksheet_majors_id'];

    $data['aktivitas'] = $this->Standar_model->get_item($params);

    $data['sum_alokasi'] = $this->db->query("SELECT SUM(c.budget_detail_jumlah * c.budget_detail_nominal) AS total 
    FROM `budgeting` a
    JOIN budget_detail c ON a.budgeting_id = c.budget_detail_budgeting_id
    WHERE budgeting_worksheet_id = '$id'")->row_array();

    $data['alokasi'] = $this->db->query("SELECT e.name, b.aktivitas_name, f.account_code, f.account_description,
    a.budgeting_uraian, c.budget_detail_jumlah, d.satuan_name, c.budget_detail_nominal,
    c.budget_detail_bulan_id
    FROM `budgeting` a
    JOIN aktivitas b ON b.aktivitas_id = a.budgeting_aktivitas_id
    JOIN budget_detail c ON a.budgeting_id = c.budget_detail_budgeting_id
    JOIN satuan d ON d.satuan_id = c.budget_detail_satuan_id
    JOIN standar e ON e.id = b.aktivitas_standar_id
    JOIN account f ON f.account_id = b.aktivitas_account_id
    WHERE budgeting_worksheet_id = '$id'")->result_array();

    $data['title']      = 'Susun Kertas Kerja';
    $data['main']       = 'worksheet/worksheet_alokasi';
    $this->load->view('manage/layout', $data);
  }

  public function add_alokasi()
  {

    if ($_POST) {

      $budget = array(
        'budgeting_worksheet_id' => $this->input->post('worksheet_id'),
        'budgeting_uraian' => $this->input->post('uraian'),
        'budgeting_aktivitas_id' => $this->input->post('aktivitas_id')
      );

      $insert_budgeting = $this->db->insert('budgeting', $budget);
      if ($insert_budgeting) {
        $budgeting_id = $this->db->insert_id();

        $bulan_id = $_POST['bulan_id'];
        $nominal  = $_POST['nominal'];

        $jumlah   = $_POST['jumlah'];
        $satuan   = $_POST['satuan'];
        $cnt      = count($bulan_id);

        for ($i = 0; $i < $cnt; $i++) {
          $detail = array(
            'budget_detail_budgeting_id'  => $budgeting_id,
            'budget_detail_bulan_id'      => $bulan_id[$i],
            'budget_detail_nominal'       => $nominal,
            'budget_detail_jumlah'        => $jumlah[$i],
            'budget_detail_satuan_id'     => $satuan[$i]
          );

          $this->db->insert('budget_detail', $detail);
        }

        $this->session->set_flashdata('success', ' Tambah Alokasi Anggaran Berhasil');
        redirect('manage/worksheet/alokasi/' . $this->input->post('worksheet_id'));
      }
    }

    redirect('manage/worksheet');
  }
  public function review($id = NULL)
  {

    if ($this->status_worksheet($id) != "A") {
      redirect('manage/worksheet');
    }

    $params = array();

    $params['id'] = $id;

    $anggaran  = $this->db->query("SELECT worksheet_nominal AS nominal FROM worksheet WHERE worksheet_id = '$id'")->row_array();

    $alokasi = $this->db->query("SELECT SUM(c.budget_detail_jumlah * c.budget_detail_nominal) AS total 
    FROM `budgeting` a
    JOIN budget_detail c ON a.budgeting_id = c.budget_detail_budgeting_id
    WHERE budgeting_worksheet_id = '$id'")->row_array();

    $per_anggaran = $this->db->query("SELECT a.name, COALESCE(SUM(d.budget_detail_nominal*d.budget_detail_jumlah), 0) total
                                        FROM `standar` a
                                        LEFT JOIN aktivitas b ON b.aktivitas_standar_id = a.id
                                        LEFT JOIN budgeting c ON c.budgeting_aktivitas_id = b.aktivitas_id
                                        LEFT JOIN budget_detail d ON d.budget_detail_budgeting_id = c.budgeting_id
                                        GROUP BY a.id")->result_array();

    $standar = array();

    foreach ($per_anggaran as $val) {
      $standar[] = $val['name'];
    }

    $nominal = array();

    foreach ($per_anggaran as $val) {
      $nominal[] = round(($val['total'] / $anggaran['nominal'] * 100), 2);
    }

    $data = array(
      'standar' => $standar,
      'nominal' => $nominal,
      'color' => $this->standar_color(),
    );

    $data['grafik_anggaran'] = array(
      'alokasi' => round(($alokasi['total'] / $anggaran['nominal'] * 100), 2),
      'sisa' => round((($anggaran['nominal'] - $alokasi['total']) / $anggaran['nominal'] * 100), 2)
    );

    $data['title'] = 'Review Kertas Kerja';
    $data['main'] = 'worksheet/worksheet_review';
    $this->load->view('manage/layout', $data);
  }

  public function ajukan()
  {
    if ($_POST) {
      $worksheet_id = $this->input->post('worksheet_id');

      $this->db->where('worksheet_id', $worksheet_id);
      $ajukan = $this->db->update('worksheet', array('worksheet_status' => 'D'));

      if ($ajukan) {
        $this->session->set_flashdata('success', ' Pengajuan Kertas Kerja Berhasil');
        redirect('manage/worksheet');
      }
    }

    redirect('manage/worksheet');
  }

  private function status_worksheet(Int $id = null)
  {
    $check_status =  $this->db->query("SELECT worksheet_status
                                        FROM worksheet
                                        WHERE worksheet_id = '$id'")->row_array();

    return $check_status['worksheet_status'];
  }

  private function get_bulan()
  {
    $bulan = array(
      '1' => 'Januari', '2' => 'Februari',
      '3' => 'Maret', '4' => 'April',
      '5' => 'Mei', '6' => 'Juni',
      '7' => 'Juli', '8' => 'Agustus',
      '9' => 'September', '10' => 'Oktober',
      '11' => 'November', '12' => 'Desember'
    );

    return $bulan;
  }

  private function standar_color()
  {
    return $color = array(
      '#D63484',
      '#F6D776',
      '#3468C0',
      '#F6B17A',
      '#AAD7D9',
      '#756AB6',
      '#BF3131',
    );
  }
}
