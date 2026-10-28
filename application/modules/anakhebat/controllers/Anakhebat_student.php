<?php
defined('BASEPATH') or exit('No direct script access allowed');

use SecureApiClient\SecureApiClient;

class Anakhebat_student extends CI_Controller
{

  public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_student') == NULL) {
            header("Location:" . site_url('student/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->helper(array('form', 'url'));
        $this->load->model(array('rekap_presensi/Rekap_presensi_model', 'student/Student_model', 'setting/Setting_model'));
    }

  // Update
  public function index()
  {

    $data['setting_level']              = $this->Setting_model->get(array('id' => 7));
    $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
    $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
    $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $request = [];

    $uri = '/v2/habits/all';

    $response = $this->encryption($uri, $request);

    $response = json_encode($response);

    if ($response === false) {
        $error = json_last_error_msg();
        echo json_encode(['error' => 'JSON encoding error: ' . $error]);
        return;
    }

    $getData = json_decode($response, true);

    $output = $getData['body'] ?? [];

    $output = json_decode($output, true);

    $habit = isset($output['data']) && is_array($output['data']) ? $output['data'] : [];

    $data['habit'] = $habit;

    $data['title'] = 'Input Kebiasaan Baik Anak';
    $data['main'] = 'anakhebat/anakhebat_add';

    $this->load->view('frontend/anakhebat', $data);
  }

  public function input($id)
  {


        if (empty($id)) {
            echo json_encode(['error' => 'ID tidak ditemukan']);
            return;
        }



    $data['setting_level']              = $this->Setting_model->get(array('id' => 7));
    $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
    $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
    $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $request = ['id' => $id];

        $uri = '/v2/habits/show';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $this->session->set_flashdata('failed', 'Gagal mendapatkan data kebiasaan baik');
            redirect('student/anakhebat');
        }

        $getData = json_decode($response, true);

        if (isset($getData['body'])) {
            $habit = json_decode($getData['body'], true);

            $data['habit'] = $habit['data'];

            $data['title'] = 'Input Kebiasaan Baik Anak';
            $data['main'] = 'anakhebat/anakhebat_input';

            $this->load->view('frontend/anakhebat', $data);

        } else {
            $this->session->set_flashdata('failed', 'Gagal mendapatkan data kebiasaan baik');
            redirect('student/anakhebat');
        }
  }

  public function save()
    {
        $id          = $this->input->post('id');

        $request = ['id' => $id];

        $uri = '/v2/habits/show';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $this->session->set_flashdata('failed', 'Gagal mendapatkan data kebiasaan baik');
            redirect('student/anakhebat');
        }

        $getData = json_decode($response, true);


        $output = json_decode($getData['body'], true);

        $habit = $output['data'];
        
        $description = null;


        if ($habit['is_checkbox'] == 1) {
            $descArr = $this->input->post('description'); // array checkbox
            if (!empty($descArr)) {
                $description = implode(", ", $descArr);
            }
        }

        // jika pakai time
        if ($habit['use_time'] == 1) {
            $time = $this->input->post('time');
            if (!empty($time)) {
                $description = $time; // override jadi waktu
            }
        }

        $description = $description ?? '';

        $data['student_id']     = $this->session->userdata('uid_student');
        $data['habit_id']       = $habit['id'];
        $data['date']           = date('Y-m-d');
        $data['note']           = $description;
        $data['created_by']     = $this->session->userdata('ufullname_student');

        // Cek jika ada file
        if (!empty($_FILES['photo']['tmp_name'])) {
            $data['photo'] = new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type'], $_FILES['photo']['name']);
        }

        $uri = '/v1/dailyreports/add';

        $response = $this->encryption($uri, $data, true);

        $response = json_encode($response);

        if ($response === false) {
            $this->session->set_flashdata('failed', ' Insert Kebiasaan Anak Gagal');
        }

        $this->session->set_flashdata('success', ' Insert Kebiasaan Anak Berhasil. Data berhasil disimpan!');
        redirect('student/anakhebat');
    }


    private function encryption($uri, $request, $isMultipart = false)
    {

        $key = $this->Setting_model->get(array('id' => 301));
        $api_key = $key['setting_value'];
        $secret = $this->Setting_model->get(array('id' => 302));
        $api_secret = $secret['setting_value'];

        $url = $this->config->item('anakhebat_url') . "/api";

        $client = new SecureApiClient(
            $url, // Base URL
            $api_key,  // API Key
            $api_secret // API Secret
        );

        // Kirim request POST
        $response = $client->request('POST', $uri, $request, $isMultipart);

        return $response;
    }
}
