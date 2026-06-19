<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Pengajuan_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('student/Student_model', 'pengajuan/Pengajuan_model', 'period/Period_model', 'setting/Setting_model', 'logs/Logs_model'));
  }

  public function index($offset = NULL, $id = NULL)
  {

    $f = $this->input->get(NULL, TRUE);

    $data['f']  = $f;
    $majors_id  = null;
    $status     = null;
    $params     = array();

    $periodActive       = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();

    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $majors_id = $f['m'];
    }

    if (isset($f['s']) && !empty($f['s']) && $f['s'] != '') {
      $status = $f['s'];
    }

    if (isset($f['k']) && !empty($f['k']) && $f['k'] != '' && $f['k'] == 'izin') {
      $data['izin']  = $this->Pengajuan_model->get_izin(array('period_id' => $periodActive['period_id'], 'status' => $status, 'majors_id' => $majors_id, 'order_by' => 'pengajuan_izin_id'));
    } else if (isset($f['k']) && !empty($f['k']) && $f['k'] != '' && $f['k'] == 'pulang') {
      $data['pulang']  = $this->Pengajuan_model->get_pulang(array('period_id' => $periodActive['period_id'], 'status' => $status, 'majors_id' => $majors_id, 'order_by' => 'pengajuan_pulang_id'));
    }

    $data['majors']     = $this->Student_model->get_majors();

    $data['title']      = 'Pengajuan Izin Siswa';

    $data['main']       = 'pengajuan/pengajuan_list';

    $this->load->view('manage/layout', $data);
  }

  public function status_izin()
  {

    $user = $this->session->userdata('uid');

    $id         = $_POST['id'];
    $majors_id  = $_POST['majors_id'];
    $kategori   = $_POST['kategori'];
    $status     = $_POST['status'];
    $nama       = $_POST['nama'];
    $kelas      = $_POST['kelas'];
    $phone      = $_POST['phone'];
    $izin       = $_POST['izin'];
    $date       = $_POST['date'];
    $time       = $_POST['time'];

    $wa_center              = $this->Setting_model->get(array('id' => 17));

    $this->db->query("UPDATE pengajuan_izin SET pengajuan_izin_status = '$status', pengajuan_izin_user_id = '$user' WHERE pengajuan_izin_id = '$id'");

    $pesan = "Pengajuan Izin Keluar ananda " . $nama . " kelas " . $kelas . " Tanggal " . $date . " Jam " . $time  . " untuk keperluan " . $izin . ", kami *" . $status . "*" . "\n\n" . 'Nomor WA Sekolah : http://wa.me/' . $wa_center;

    $key1 = 'fce9862deac8554e58028c35011e14214b47496d05fbd61c'; //decareptil
    $url = 'http://116.203.92.59/api/send_message';

    $data = array(
      "phone_no" => $phone,
      "key"    => $key1,
      "message"  => $pesan
    );

    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
      )
    );
    $res = curl_exec($ch);
    curl_close($ch);

    redirect('manage/pengajuan?m=' . $majors_id . '&k=' . str_replace(' ', '', $kategori) . '&s=' . $status);
  }

  public function status_pulang()
  {

    $user = $this->session->userdata('uid');

    $id         = $_POST['id'];
    $majors_id  = $_POST['majors_id'];
    $kategori   = $_POST['kategori'];
    $status     = $_POST['status'];
    $nama       = $_POST['nama'];
    $kelas      = $_POST['kelas'];
    $phone      = $_POST['phone'];
    $pulang     = $_POST['pulang'];
    $date       = $_POST['date'];
    $days       = $_POST['days'];

    $wa_center              = $this->Setting_model->get(array('id' => 17));


    $this->db->query("UPDATE pengajuan_pulang SET pengajuan_pulang_status = '$status', pengajuan_pulang_user_id = '$user' WHERE pengajuan_pulang_id = '$id'");

    $pesan = "Pengajuan Izin Pulang ananda " . $nama . " kelas " . $kelas . " Tanggal " . $date . " untuk keperluan " . $pulang . " selama " . $days . " hari, kami *" . $status . "*" . "\n\n" . 'Nomor WA Sekolah : http://wa.me/' . $wa_center;

    $key1 = 'fce9862deac8554e58028c35011e14214b47496d05fbd61c'; //decareptil
    $url = 'http://116.203.92.59/api/send_message';

    $data = array(
      "phone_no" => $phone,
      "key"    => $key1,
      "message"  => $pesan
    );

    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
      )
    );
    $res = curl_exec($ch);
    curl_close($ch);

    redirect('manage/pengajuan?m=' . $majors_id . '&k=' . str_replace(' ', '', $kategori) . '&s=' . $status);
  }
}
