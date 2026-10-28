<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use SecureApiClient\SecureApiClient;

class Presensi_kebiasaan_guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged_guru') == NULL) {
            header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('setting/Setting_model', 'presensi_kebiasaan/Presensi_kebiasaan_model', 'student/Student_model'));
    }

    // pos view in list
    public function index()
    {
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        if (isset($q['kelas']) && !empty($q['kelas']) && $q['kelas'] != '') {
            $params['class_id'] = $q['kelas'];
            $data['student']    = $this->Student_model->get($params);
            // echo json_encode($data['student']);
            // exit;
            $data['habit']      = $this->habit_list();
        }

        $id_guru = $this->session->userdata('uid_guru');

        $day        = pretty_date(date('Y-m-d'), 'l', false);

        $data['kelas'] = $this->Presensi_kebiasaan_model->getClassBySceduleTeacher($day, $id_guru);

        $data['title'] = 'Presensi Kebiasaan Baik Siswa';
        $data['main']  = 'presensi_kebiasaan/presensi_kebiasaan_list';
        $this->load->view('guru/layout', $data);
    }

    public function habit_list()
    {

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

        return $habit;
    }

    public function add_glob()
    {
        $uri  = '/v1/dailyreports/bulk-add';
        $data_presensi = $this->input->post('presensi');

        $data['reports'] = $data_presensi;
        // echo json_encode($data);
        // exit;
        $response = $this->encryption($uri, $data, true);
        // $response = json_encode($response);
        if (isset($response['body'])) {
            $response['body'] = json_decode($response['body'], true);
        }
        echo json_encode($response);
    }

    private function encryption($uri, $request)
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
        $response = $client->request('POST', $uri, $request);

        return $response;
    }
}
