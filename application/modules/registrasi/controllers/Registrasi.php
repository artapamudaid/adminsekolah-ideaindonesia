<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    
    $this->load->helper('security');
    $this->load->helper('date_helper');
	$this->load->helper('send_helper');
    $this->load->library('form_validation');
    
    $this->load->model(array('registrasi/Registrasi_model', 'setting/Setting_model', 'logs/Logs_model', 'users/Users_model'));
   }

    public function index($id = NULL) {
      
        $data['operation']  = 'Tambah';
        
        $this->form_validation->set_rules('registrasi_phone', 'Nomor Telepon', 'trim|required|xss_clean');
        $this->form_validation->set_rules('registrasi_name', 'Nama Lengkap', 'trim|required|xss_clean');
        $this->form_validation->set_rules('registrasi_school', 'Nama Sekolah', 'trim|required|xss_clean');
        $this->form_validation->set_rules('registrasi_address', 'Alamat', 'trim|required|xss_clean');
        $this->form_validation->set_rules('registrasi_email', 'Email', 'required|trim|valid_email|xss_clean|is_unique[registrasi.registrasi_email]', [
            'is_unique' => 'Email sudah terdaftar, silakan gunakan email lain.'
        ]);
      
        if ($_POST && $this->form_validation->run() == TRUE) {
			
            $userIP = $_SERVER['REMOTE_ADDR'];

            // Bypass reCAPTCHA jika di localhost
            if ($userIP == '127.0.0.1' || $userIP == '::1') {
                $responseData = (object)['success' => true];
            } else {

                $secretKey = '6LeuXAwrAAAAACYMXP855yPA1pNdu6-JHzKbrfPn';
                $responseKey = $_POST['g-recaptcha-response'];
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
                $response = file_get_contents($url);
                $responseData = json_decode($response);
            }

            if ($responseData->success && ENVIRONMENT=='production' || $userIP == '127.0.0.1' || $userIP == '::1') {
            
            $email  = $this->input->post('registrasi_email', TRUE);

            if ($this->db->where('registrasi_email', $email)->count_all_results('registrasi') > 0) {
                $this->session->set_flashdata('failed', 'Email sudah terdaftar, silakan gunakan email lain.');
                redirect('registrasi');
            }

            $phone  = $this->input->post('registrasi_phone', TRUE);
                
            $phone = str_replace(" ","",$phone);
            $phone = str_replace("(","",$phone);
            $phone = str_replace(")","",$phone);
            $phone = str_replace(".","",$phone);
            
            if(!preg_match('/[^+0-9]/',trim($phone)))
            {
            	 if(substr(trim($phone), 0, 1)=='+')
            	 {
            	 $hp = trim($phone);
            	 }
            	 elseif(substr(trim($phone), 0, 1)=='0')
            	 {
            	 $hp = '+62'.substr(trim($phone), 1);
            	 }
            	 elseif(substr(trim($phone), 0, 2)=='62')
            	 {
            	 $hp = '+'.trim($phone);
            	 }
            	 elseif(substr(trim($phone), 0, 1)=='8')
            	 {
            	 $hp = '+62'.trim($phone);
            	 }
            	 else
            	 {
            	 $hp = '+'.trim($phone);
            	 }		 
            }
                
                $pass_generate              = $this->password_generate(7);
                $pass                       = $pass_generate;
                
                $params['registrasi_name']          =  $this->input->post('registrasi_name', TRUE);
                $params['registrasi_school']        =  $this->input->post('registrasi_school', TRUE);
                $params['registrasi_address']       =  $this->input->post('registrasi_address', TRUE);
                $params['registrasi_city']          =  $this->input->post('registrasi_city', TRUE);
                $params['registrasi_province']      =  $this->input->post('registrasi_province', TRUE);
				$params['registrasi_sumber']        =  $this->input->post('registrasi_sumber', TRUE);
                $params['registrasi_phone']         =  $hp;
                $params['registrasi_email']         =  $email;
                $params['registrasi_password']      = $pass; 
                $params['registrasi_input_date']    = date('Y-m-d H:i:s');
                $params['registrasi_last_update']   = date('Y-m-d H:i:s');
            
            $this->Registrasi_model->add($params);
            
            $users = array(
                'user_password'     => md5($pass),
                'user_email'        => $this->input->post('registrasi_email', TRUE),
                'user_role_role_id' => '1',
                'user_majors_id'    => '0',
                'user_full_name'    => $this->input->post('registrasi_name', TRUE),
                'user_description'  => $this->input->post('registrasi_school', TRUE),
                'user_input_date'   => date('Y-m-d H:i:s'),
                'user_last_update'  => date('Y-m-d H:i:s'),
                    
                );
                
            
                $this->db->insert('users', $users);
            
                if(isset($hp)){
                    
                    $no_wa      = $hp;
                    $name       = $this->input->post('registrasi_name', TRUE);
                    $school     = $this->input->post('registrasi_school', TRUE);
                    $address    =  $this->input->post('registrasi_address', TRUE);
                    $username   = $this->input->post('registrasi_email', TRUE);
                    $sumber     = $this->input->post('registrasi_sumber', TRUE);
                    $city_id    = $this->input->post('registrasi_city', TRUE);
                    $prov_id    = $this->input->post('registrasi_province', TRUE);
                    $pswd       = $pass;
                    
                    // $pesan = 'Terima kasih Bpk/Ibu ' . $this->input->post('registrasi_name') . ' dari ' . 
                    //     $this->input->post('registrasi_school') . ', Anda telah terdaftar di demo.adminsekolah.net . Untuk login gunakan Username/Email : ' . 
                    //     $this->input->post('registrasi_email') . ' dan Password : ' .$pass;


                    $pesan = "Yth. Bapak/Ibu " . strtoupper($name)
                            ."\n"
                            . ' dari ' . strtoupper($school)
                            . "\n\n"
                            . "Terimakasih sudah mendaftar trial aplikasi AdminSekolah.net"
                            . "\n\n"
                            . "Perkenalkan saya Kiki, yang akan membantu dalam penggunaan aplikasi AdminSekolah"
                            . "\n\n"
                            . "Silahkan login menggunakan akses berikut:"
                            . "\n"
                            . "Login: demo.adminsekolah.net"
                            . "\n"
                            . "Username: " . $username
                            . "\n"
                            . "Password: " . $pass
                            . "\n\n"
                            . "Berikut panduan untuk mencoba fitur-fiturnya :"
                            . "\n"
                            . "- tutorial.adminsekolah.net "
                            . "\n"
                            . "- s.id/YoutubeTutorialAdminSekolah"
                            . "\n\n"
                            . "Apabila Bapak/Ibu berkenan silahkan coba juga demo aplikasi Mobile AdminSekolah secara GRATIS melalui akses berikut:"
                            . "\n"
                            . "iOS : s.id/AdminSekolahiOS"
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
                            . "*AKSES SISWA & WALI MURID*"
                            . "Kode Sekolah : 2020123"
                            . "\n"
                            ."NIS : 20250201"
                            . "\n"
                            . "Password : 123456"
                            . "\n\n"
                            . "Apabila terdapat kendala atau ada yang ingin ditanyakan jangan ragu untuk menghubungi nomor ini ☺️";
                
                    $wa_key                 = $this->Setting_model->get(array('name' => 'setting_wa_key'));
                    $setting_whatsapp       = $this->Setting_model->get(array('id' => 92));
                    $set_key                = $wa_key['setting_value'];
                    $set_wa                 = $setting_whatsapp['setting_value'];
 

                    // send_whatsapp($no_wa, $pesan, $set_key, $set_wa);

                    $this->insert_scheduled_wa($set_wa, $set_key, $no_wa, $pesan);

                    $this->insert_scheduled_email($email);
                        
                    $this->wa_admin($no_wa, $name, $school, $username, $pswd, $address, $city_id, $prov_id, $sumber);
                }
            
                $this->session->set_flashdata('success',' Registrasi Berhasil Silahkan Cek Email/Whatsapp Anda');
				
			} else {
				$this->session->set_flashdata('success',' Verifikasi gagal! Coba lagi.');
			}

			redirect('');
        
        }
        
        $data['setting_school']     = $this->Setting_model->get(array('id' => 1));
        $data['setting_address']    = $this->Setting_model->get(array('id' => 2));
        $data['setting_phone']      = $this->Setting_model->get(array('id' => 3));
        $data['setting_district']   = $this->Setting_model->get(array('id' => 4));
        $data['setting_city']       = $this->Setting_model->get(array('id' => 5));
        $data['setting_logo']       = $this->Setting_model->get(array('id' => 6));
        
        $data['province']   = $this->Registrasi_model->get_province();
        $data['city']       = $this->Registrasi_model->get_city();

        $data['medium']     = $_GET['utm_medium'] ?? '';
        
        $data['title']      = 'Form Pendaftaran';
        $data['main']       = 'registrasi/register';
        $this->load->view('registrasi/register', $data);
        
    }
    
    public function get_city(){
        $province_id = $this->input->post('province_id');
        $dataKota    = $this->db->query("SELECT * FROM city WHERE city_province_id = '$province_id' ORDER BY city_id ASC")->result_array();
    
        echo '
            <select name="registrasi_city" id="registrasi_city" class="form-control">
				<option value=""> -- Pilih Kota/Kabupaten -- </option>';
                  foreach($dataKota as $row){ 
    
                    echo '<option value="'.$row['city_id'].'">';
                        
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

		$wa_key                 = $this->Setting_model->get(array('name' => 'setting_wa_key'));
		$setting_whatsapp       = $this->Setting_model->get(array('id' => 92));
		$set_key                = $wa_key['setting_value'];
		$set_wa                 = $setting_whatsapp['setting_value'];

		// send_whatsapp($cs, $pesan, $set_key, $set_wa);
        $this->insert_scheduled_wa($set_wa, $set_key, $cs, $pesan);
  }

  private function insert_scheduled_email($email) {
    $data = array(
      'recipient_email' => $email,
      'status' => 'pending',
      'created_at' => date('Y-m-d H:i:s')
    );
    
    $this->db->insert('scheduled_emails', $data);
  }

  private function insert_scheduled_wa($set_wa, $set_key, $no_wa, $pesan) {
    $data = array(
      'receiver' => $no_wa,
      'msg' => $pesan,
      'status' => 'pending',
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    );
    
    $this->db->insert('scheduled_wa', $data);
  }
}
