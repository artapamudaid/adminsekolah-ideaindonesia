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
    if (!empty($_GET)) {
      $config['base_url'] .= '?' . http_build_query($_GET, '', "&");
    }

    if ($this->input->get('q')) {
      $this->db->group_start();
      $this->db->like('registrasi_name', $this->input->get('q'));
      $this->db->or_like('registrasi_school', $this->input->get('q'));
      $this->db->or_like('registrasi_email', $this->input->get('q'));
      $this->db->group_end();
    }

    // Gunakan query builder untuk menghitung total_rows
    $config['total_rows'] = $this->db->from('registrasi')->count_all_results();
    $config['per_page'] = 10;
    $config['uri_segment'] = 4;
    $config['use_page_numbers'] = FALSE; // offset, bukan page number


    $config['base_url'] = site_url('manage/registrasi/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

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

    if ($this->input->get('q')) {
      $params['search'] = $this->input->get('q');
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
    $id = $this->input->post('id');
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

    $pesan = "Yth. Bapak/Ibu " . strtoupper($name)
      . "\n"
      . ' dari ' . strtoupper($school)
      . "\n\n"
      . "Terimakasih sudah mendaftar trial aplikasi AdminSekolah.net "
      . "\n\n"
      . "Perkenalkan saya Kiki, yang akan membantu dalam penggunaan aplikasi AdminSekolah "
      . "\n\n"
      . "Silahkan login menggunakan akses berikut:"
      . "\n"
      . "Login: demo.adminsekolah.net"
      . "\n"
      . "Username: " . $username
      . "\n"
      . "Password: " . $pswd
      . "\n\n"
      . "Berikut panduan untuk mencoba fitur-fiturnya :"
      . "\n"
      . "- tutorial.adminsekolah.net "
      . "\n"
      . "- s.id/YoutubeTutorialAdminSekolah"
      . "\n\n"
      . "Apabila Bapak/Ibu berkenan silahkan coba juga demo aplikasi Mobile AdminSekolah secara GRATIS melalui akses berikut:"
      . "\n"
      . "iOs : s.id/AdminSekolahiOS"
      . "\n"
      . "Android : s.id/AdminSekolahAndroid"
      . "\n\n"
      . "*AKSES PEGAWAI*"
      . "\n"
      . "Kode Sekolah : 2020123"
      . "\n"
      . "NIP : 123"
      . "\n"
      . "Password : 123456"
      . "\n\n"
      . "*AKSES SISWA & WALIMURID*"
      . "Kode Sekolah : 2020123"
      . "\n"
      . "NIS : 20250201"
      . "\n"
      . "Password : 123456"
      . "\n\n"
      . "Apabila terdapat kendala atau ada yang ingin ditanyakan jangan ragu untuk menghubungi nomor ini ☺️";

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

    $registrasi_password = '123456';

    $this->db->query("UPDATE registrasi SET registrasi_password = '$registrasi_password' WHERE registrasi_id = '$id'");

    $registrasi = $this->db->query("SELECT registrasi_name, registrasi_email FROM registrasi WHERE registrasi_id = '$id'")->row_array();

    $this->session->set_flashdata('success', 'Reset Password Berhasil');
    redirect('manage/registrasi');
  }

  public function export_excel()
  {

    $dari = $this->input->post('dari_tanggal');
    $sampai = $this->input->post('sampai_tanggal');

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

  // public function regis()
  // {
  //   $data['operation']  = 'Tambah';

  //   if ($_POST) {

  //     $email  = $this->input->post('registrasi_email');
  //     $phone  = $this->input->post('registrasi_phone');

  //     $phone = str_replace(" ", "", $phone);
  //     $phone = str_replace("(", "", $phone);
  //     $phone = str_replace(")", "", $phone);
  //     $phone = str_replace(".", "", $phone);

  //     if (!preg_match('/[^+0-9]/', trim($phone))) {
  //       if (substr(trim($phone), 0, 1) == '+') {
  //         $hp = trim($phone);
  //       } elseif (substr(trim($phone), 0, 1) == '0') {
  //         $hp = '+62' . substr(trim($phone), 1);
  //       } elseif (substr(trim($phone), 0, 2) == '62') {
  //         $hp = '+' . trim($phone);
  //       } elseif (substr(trim($phone), 0, 1) == '8') {
  //         $hp = '+62' . trim($phone);
  //       } else {
  //         $hp = '+' . trim($phone);
  //       }
  //     }

  //     $cek    = $this->db->query("SELECT COUNT(registrasi_email) AS count FROM registrasi WHERE registrasi_email = '$email'")->row_array();

  //     $cek2   = $this->db->query("SELECT COUNT(registrasi_phone) AS count FROM registrasi WHERE registrasi_phone = '$hp'")->row_array();

  //     //if($cek['count'] == 0 && $cek2['count'] == 0){

  //     $pass_generate              = $this->password_generate(7);
  //     $pass                       = $pass_generate;

  //     $params['registrasi_name']          =  $this->input->post('registrasi_name');
  //     $params['registrasi_school']        =  $this->input->post('registrasi_school');
  //     $params['registrasi_address']       =  $this->input->post('registrasi_address');
  //     $params['registrasi_city']          =  $this->input->post('registrasi_city');
  //     $params['registrasi_province']      =  $this->input->post('registrasi_province');
  //   $params['registrasi_sumber']        =  $this->input->post('registrasi_sumber');
  //     $params['registrasi_phone']         =  $hp;
  //     $params['registrasi_email']         =  $email;
  //     $params['registrasi_password']      = $pass;
  //     $params['registrasi_input_date']    = date('Y-m-d H:i:s');
  //     $params['registrasi_last_update']   = date('Y-m-d H:i:s');

  //     $this->Registrasi_model->add($params);

  //     $users = array(
  //       'user_email'        => $this->input->post('registrasi_email'),
  //       'user_password'     => md5($pass),
  //       'user_email'        => $this->input->post('registrasi_email'),
  //       'user_role_role_id' => '1',
  //       'user_majors_id'    => '0',
  //       'user_full_name'    => $this->input->post('registrasi_name'),
  //       'user_description'  => $this->input->post('registrasi_school'),
  //       'user_input_date'   => date('Y-m-d H:i:s'),
  //       'user_last_update'  => date('Y-m-d H:i:s'),

  //     );


  //     $this->db->insert('users', $users);

  //     if (isset($hp)) {

  //       $wa_center              = $this->Setting_model->get(array('id' => 17));
  //       $wa_key                 = $this->Setting_model->get(array('name' => 'setting_wa_key'));
  //       $setting_whatsapp       = $this->Setting_model->get(array('id' => 92));
  //       $set_key                = $wa_key['setting_value'];
  //       $set_wa                 = $setting_whatsapp['setting_value'];

  //       $no_wa      = $this->formatPhoneNumber($hp);
  //       $name       = $this->input->post('registrasi_name');
  //       $school     = $this->input->post('registrasi_school');
  // 	$address    =  $this->input->post('registrasi_address');
  //       $username   = $this->input->post('registrasi_email');
  // 	$sumber     = $this->input->post('registrasi_sumber');
  // 	$city_id    = $this->input->post('registrasi_city');
  // 	$prov_id    = $this->input->post('registrasi_province');
  //       $pswd       = $pass;

  //       //$pesan = 'Terima kasih Bapak/Ibu ' . $this->input->post('registrasi_name') . ' dari ' . $this->input->post('registrasi_school') . ', Anda telah terdaftar di demo.adminsekolah.net . Untuk login gunakan Username/Email : ' . $this->input->post('registrasi_email') . ' dan Password : ' . $pass;

  //       $pesan = "Yth. Bapak/Ibu " . strtoupper($this->input->post('registrasi_name'))
  //               ."\n"
  //               . ' dari ' . strtoupper($this->input->post('registrasi_school'))
  //               . "\n\n"
  //               . "Terimakasih sudah mendaftar trial aplikasi AdminSekolah.net 📑"
  //               . "\n\n"
  //               . "Perkenalkan saya Kiki, yang akan membantu dalam penggunaan aplikasi AdminSekolah 👩🏻‍💻"
  //               . "\n\n"
  //               . "Silahkan login menggunakan akses berikut:"
  //               . "\n"
  //               . "Login: demo.adminsekolah.net"
  //               . "\n"
  //               . "Username: " . $this->input->post('registrasi_email')
  //               . "\n"
  //               . "Password: " . $pswd
  //               . "\n\n"
  //               . "Berikut panduan untuk mencoba fitur-fiturnya :"
  //               . "\n"
  //               . "- tutorial.adminsekolah.net "
  //               . "\n"
  //               . "- s.id/YoutubeTutorialAdminSekolah"
  //               . "\n\n"
  //               . "Apabila Bapak/Ibu berkenan silahkan coba juga demo aplikasi Mobile AdminSekolah secara GRATIS melalui akses berikut:"
  //               . "\n"
  //               . "📌 iOs : s.id/AdminSekolahiOS"
  //               . "\n"
  //               . "📌 Android : s.id/AdminSekolahAndroid"
  //               . "\n\n"
  //               . "🔐 AKSES PEGAWAI"
  //               . "\n"
  //               . "Kode Sekolah : 2020123"
  //               . "\n"
  //               . "NIP : 123"
  //               . "\n"
  //               . "Password : 123456"
  //               . "\n\n"
  //               . "🔐 AKSES SISWA & WALIMURID"
  //               . "Kode Sekolah : 2020123"
  //               . "\n"
  //               ."NIS : 20250201"
  //               . "\n"
  //               . "Password : 123456"
  //               . "\n\n"
  //               . "Apabila terdapat kendala atau ada yang ingin ditanyakan jangan ragu untuk menghubungi nomor ini ☺️🙏🏻";

  //       // send_whatsapp($no_wa, $pesan, '', '');
  //       send_whatsapp($no_wa, $pesan, $set_key, $set_wa);

  //       $this->wa_admin($no_wa, $name, $school, $username, $pswd, $address, $city_id, $prov_id, $sumber);
  //     }
  //     $this->session->set_flashdata('success', ' Registrasi Berhasil Silahkan Cek Whatsapp-mu');
  //     redirect('manage/auth/login');

  //     // } else if($cek['count'] != 0 && $cek2['count'] == 0) {
  //     //     $this->session->set_flashdata('failed', ' Email Sudah Pernah Didaftarakan');
  //     //     redirect('registrasi');
  //     // } else if($cek['count'] == 0 && $cek2['count'] != 0) {
  //     //     $this->session->set_flashdata('failed', ' No. Whatsapp Sudah Pernah Didaftarakan');
  //     //     redirect('registrasi');
  //     // } else {
  //     //     $this->session->set_flashdata('failed', ' Email dan No. Whatsapp Sudah Pernah Didaftarakan');
  //     //     redirect('registrasi');
  //     // }

  //   }

  //   $data['setting_school']     = $this->Setting_model->get(array('id' => 1));
  //   $data['setting_address']    = $this->Setting_model->get(array('id' => 2));
  //   $data['setting_phone']      = $this->Setting_model->get(array('id' => 3));
  //   $data['setting_district']   = $this->Setting_model->get(array('id' => 4));
  //   $data['setting_city']       = $this->Setting_model->get(array('id' => 5));
  //   $data['setting_logo']       = $this->Setting_model->get(array('id' => 6));

  //   $data['province']   = $this->Registrasi_model->get_province();
  //   $data['city']       = $this->Registrasi_model->get_city();

  //   $data['title']      = 'Form Pendaftaran';
  //   $data['main']       = 'registrasi/register';
  //   $this->load->view('registrasi/register', $data);
  // }

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
    $province_id = $this->input->post('province_id');
    $dataKota    = $this->db->query("SELECT * FROM city WHERE city_province_id = '$province_id' ORDER BY city_id ASC")->result_array();

    echo '<select name="registrasi_city" id="registrasi_city" class="form-control" required="">
      <option value=""> -- Pilih Kota/Kabupaten -- </option>';
    foreach ($dataKota as $row) {

      echo '<option value="' . $row['city_id'] . '">';

      echo ucwords(strtolower($row['city_name']));

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
