<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Registrasi_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    if ($this->session->userdata('uroleid') != 3) {
      redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->helper(array('send_helper'));

    $this->load->model(array('registrasi/Registrasi_model', 'setting/Setting_model', 'logs/Logs_model'));
  }

  public function index($offset = NULL)
  {
    // Konfigurasi pagination
    $this->load->library('pagination');

    // Perbaiki base_url agar tidak double index pada segment
    $config['base_url'] = site_url('manage/registrasi_set/index');

    // Jika ada query string, tambahkan ke base_url
    $queryString = $this->input->get(NULL, TRUE);
    if (!empty($queryString)) {
      $config['base_url'] .= '?' . http_build_query($queryString, '', "&");
    }

    $search = (string) $this->input->get('q', TRUE);
    if ($search !== '') {
      $this->db->group_start();
      $this->db->like('registrasi_name', $search);
      $this->db->or_like('registrasi_school', $search);
      $this->db->or_like('registrasi_email', $search);
      $this->db->group_end();
    }

    // Gunakan query builder untuk menghitung total_rows
    $config['total_rows'] = $this->db->from('registrasi')->count_all_results();
    $config['per_page'] = 10;
    $config['uri_segment'] = 4;
    $config['use_page_numbers'] = FALSE; // offset, bukan page number


    $config['base_url'] = site_url('manage/registrasi/index');
    $config['suffix'] = '?' . http_build_query($queryString, '', "&");

    // Bootstrap 3 Pagination Styles
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = 'Pertama';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_link'] = 'Terakhir';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = array('class' => '');

    $this->pagination->initialize($config);

    // Ambil offset dari segment ke-4, default 0
    $offset = ($this->uri->segment(4) !== null) ? (int)$this->uri->segment(4) : 0;

    $params = array(
      'limit' => $config['per_page'],
      'offset' => $offset
    );

    if ($search !== '') {
      $params['search'] = $search;
    }

    $data['registrasi_list'] = $this->Registrasi_model->get($params);
    $data['pagination'] = $this->pagination->create_links();

    $data['setting_school']   = $this->Setting_model->get(array('id' => 1));
    $data['setting_address']  = $this->Setting_model->get(array('id' => 2));
    $data['setting_phone']    = $this->Setting_model->get(array('id' => 3));
    $data['setting_district'] = $this->Setting_model->get(array('id' => 4));
    $data['setting_city']     = $this->Setting_model->get(array('id' => 5));
    $data['setting_logo']     = $this->Setting_model->get(array('id' => 6));

    $data['title'] = 'List Calon Pelanggan';
    $data['main'] = 'registrasi/registrasi_list_all';
    $this->load->view('manage/layout', $data);
  }

  public function copy_msg()
  {
    $id = (int) $this->input->post('id', TRUE);
    if ($id == NULL) {
      echo json_encode(array('status' => 'error', 'message' => 'ID tidak ditemukan'));
      exit;
    }

    $registrasi = $this->Registrasi_model->get(array('id' => $id));
    if ($registrasi == NULL) {
      echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
      exit;
    }

    $name     = $registrasi['registrasi_name'];
    $school   = $registrasi['registrasi_school'];
    $username = $registrasi['registrasi_email'];
    $pswd     = $registrasi['registrasi_password'];

    // $pesan = 'Terima kasih Bapak/Ibu ' . $name . ' dari ' . $school .
    //          ', Anda telah terdaftar di demo.adminsekolah.net. Untuk login gunakan Username/Email: ' .
    //          $username . ' dan Password: ' . $pswd;

     $greetings = 'Selamat Pagi';
      $current_hour = date('H');
      if ($current_hour >= 11 && $current_hour < 15) {
          $greetings = 'Selamat Siang';
      } else if ($current_hour >= 15 && $current_hour < 18) {
          $greetings = 'Selamat Sore';
      } else if ($current_hour >= 18 || $current_hour < 3) {
          $greetings = 'Selamat Malam';
      }
        
    $pesan = $greetings . " Bapak/Ibu " . strtoupper($name)
      . "\n"
      . ' dari ' . strtoupper($school)
      . "\n\n"
      . "Terimakasih sudah telah mendaftar Demo AdminsekolahNet "
      . "\n\n"
      . "Perkenalkan saya Tiya, yang akan membantu Bapak/Ibu dalam penggunaan aplikasi AdminSekolah "
      . "\n\n"
      . "Untuk Login Demo gunakan:"
      . "\n"
      . "URL : https://demo.adminsekolah.net"
      . "\n"
      . "Username: " . $username
      . "\n"
      . "Password: " . $pswd
      . "\n\n"
      . "Silakan login menggunakan username dan password tersebut. Jika mengalami kendala atau ada yang ingin didiskusikan, jangan ragu untuk menghubungi saya kembali ya, Pak/Bu 😊🙏🏻";

    // Set header agar browser tahu ini JSON
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => $pesan));
    exit;
  }


  public function send_wa($id = NULL)
  {
    $data['registrasi'] = $this->Registrasi_model->get(array('id' => $id));
    if ($data['registrasi'] == NULL) {
      redirect('manage/registrasi');
    }

    $registrasi = $data['registrasi'];
    $no_wa      = $this->formatPhoneNumber($registrasi['registrasi_phone']);
    $name       = $registrasi['registrasi_name'];
    $school     = $registrasi['registrasi_school'];
    $username   = $registrasi['registrasi_email'];
    $pswd       = $registrasi['registrasi_password'];

    $pesan = 'Terima kasih Bapak/Ibu ' . $name . ' dari ' . $school . ', Anda telah terdaftar di demo.adminsekolah.net . Untuk login gunakan Username/Email : ' . $username . ' dan Password : ' . $pswd;

    // Kirim pesan WhatsApp menggunakan API eksternal di tab baru
    $api_url = "https://wa.me/" . ltrim($no_wa, '+') . "?text=" . urlencode($pesan);

    // Redirect ke tab baru (window.open via JavaScript)
    echo "<script>window.open('{$api_url}', '_blank');
        window.location.href='" . site_url('manage/registrasi') . "';
        </script>";
    exit;
  }

  function rpw($id)
  {

    $id = (int) $id;
    if ($id <= 0) {
      redirect('manage/registrasi');
    }

    $registrasi_password = '123456';

    $this->db
      ->where('registrasi_id', $id)
      ->update('registrasi', array('registrasi_password' => $registrasi_password));

    $registrasi = $this->db
      ->select('registrasi_name, registrasi_email')
      ->where('registrasi_id', $id)
      ->get('registrasi')
      ->row_array();

    $this->session->set_flashdata('success', 'Reset Password Berhasil');
    redirect('manage/registrasi');
  }

  public function export_excel()
  {

    $dari = $this->input->post('dari_tanggal', TRUE);
    $sampai = $this->input->post('sampai_tanggal', TRUE);

    $params['dari_tanggal']   = $dari;
    $params['sampai_tanggal'] = $sampai;

    $data['registrasi'] = $this->Registrasi_model->get($params);

    $data['dari_tanggal']     = pretty_date($dari, 'd-m-Y', false);
    $data['sampai_tanggal']   = pretty_date($sampai, 'd-m-Y', false);

    $data['setting_school']   = $this->Setting_model->get(array('id' => 1));
    $data['setting_address']  = $this->Setting_model->get(array('id' => 2));
    $data['setting_phone']    = $this->Setting_model->get(array('id' => 3));
    $data['setting_district'] = $this->Setting_model->get(array('id' => 4));
    $data['setting_city']     = $this->Setting_model->get(array('id' => 5));
    $data['setting_logo']     = $this->Setting_model->get(array('id' => 6));

    $this->load->view('registrasi/registrasi_excel', $data);
  }

   function formatPhoneNumber($phoneNumber)
  {
    // Cek apakah nomor sudah diawali dengan "+62"
    if (substr($phoneNumber, 0, 3) === '+62') {
      return $phoneNumber; // Nomor sudah sesuai, kembalikan nomor aslinya
    }

    // Jika nomor diawali dengan "0", ubah menjadi "+62"
    if (substr($phoneNumber, 0, 1) === '0') {
      return '+62' . substr($phoneNumber, 1); // Ganti "0" dengan "+62"
    }

    // Jika format tidak sesuai, kembalikan pesan error (opsional)
    return 'Format nomor tidak valid';
  }

  public function get_city()
  {
    $province_id = (int) $this->input->post('province_id', TRUE);
    $dataKota = $this->db
      ->where('city_province_id', $province_id)
      ->order_by('city_id', 'ASC')
      ->get('city')
      ->result_array();

    echo '<select name="registrasi_city" id="registrasi_city" class="form-control" required="">
      <option value=""> -- Pilih Kota/Kabupaten -- </option>';
    foreach ($dataKota as $row) {

      echo '<option value="' . (int) $row['city_id'] . '">';

      echo html_escape(ucwords(strtolower($row['city_name'])));

      echo '</option>';
    }
    echo '</select>';
  }

  public function password_generate($chars)
  {
    $data = '1234567890';
    return substr(str_shuffle($data), 0, $chars);
  }

  private function wa_admin($no_wa, $name, $school, $username, $pswd, $address, $city_id, $prov_id, $sumber)
  {
    $cs = "+6281233640003";

    $province = $this->db->get_where('province', array('province_id' => $prov_id))->row_array();
    $prov_name = $province['province_name'];
    $city = $this->db->get_where('city', array('city_id' => $city_id))->row_array();
    $city_name = $city['city_name'];

    $pesan = 'Pendaftar Baru demo.adminsekolah.net ' . "\n\n" . 'Nama ' . $name . "\n" . 'Sekolah : ' . $school . "\n" . 'Alamat : ' . $address . "\n" . 'Kota/Kab : ' . $city_name . "\n" . 'Provinsi : ' . $prov_name . "\n" . 'Sumber : ' . $sumber . "\n\n" . 'Username/Email : ' . $username . "\n" . 'Password : ' . $pswd . "\n\n" .
      'Hubungi Pendaftar : http://wa.me/' . $no_wa;

    send_whatsapp($cs, $pesan, '', '');
  }
}
