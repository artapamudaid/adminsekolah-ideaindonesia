<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Program_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'program/Program_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    // program view in list
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
        // Nip
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['program_name'] = $f['n'];
        }

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
        }

        $data['program'] = $this->Program_model->get($params);

        $config['base_url'] = site_url('manage/program/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Program';
        $data['main'] = 'program/program_list';
        $this->load->view('manage/layout', $data);
    }

    // Add program and Update
    public function add($id = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('program_name', 'Program', 'trim|required|xss_clean');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST and $this->form_validation->run() == TRUE) {

            if ($this->input->post('program_id')) {
                $params['program_id'] = $this->input->post('program_id');
            }

            $params['program_name'] = $this->input->post('program_name');
            $params['program_description'] = $this->input->post('program_description');
            $params['account_account_id'] = $this->input->post('account_account_id');
            $params['pendayagunaan_account_id'] = $this->input->post('pendayagunaan_account_id');
            $params['program_end'] = $this->input->post('program_end');
            $params['program_target'] = $this->input->post('program_target');
            $params['program_status'] = $this->input->post('program_status');

            $status = $this->Program_model->add($params);

            $paramsupdate['program_id'] = $status;
            if (!empty($_FILES['program_gambar']['name'])) {
                $paramsupdate['program_gambar'] = $this->do_upload($name = 'program_gambar', $fileName = base64_encode($params['program_name']));
            }

            $this->Program_model->add($paramsupdate);

            // activity log
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Program',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:null;Title:' . $params['program_name']
                )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' Program berhasil');
            redirect('manage/program');
        } else {
            if ($this->input->post('program_id')) {
                redirect('manage/program/edit/' . $this->input->post('program_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $data['program'] = $this->Program_model->get(array('id' => $id));
            }


            $majors_id = $this->session->userdata('umajorsid');

            $whereMajors = NULL;
            if ($majors_id != '0') {
                $whereMajors = "AND account_majors_id = '$majors_id'";
            }

            $data['account'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '6%' AND account_category = '1' $whereMajors")->result_array();
            $data['pendayagunaan'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '7%' AND account_category = '1' $whereMajors")->result_array();

            $data['title'] = $data['operation'] . ' Program';
            $data['main'] = 'program/program_add';
            $this->load->view('manage/layout', $data);
        }
    }

    // Setting Upload File Requied
    function do_upload($name = NULL, $fileName = NULL)
    {
        $this->load->library('upload');

        $config['upload_path'] = FCPATH . 'uploads/program/';

        /* create directory if not exist */
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '2048';
        $config['file_name'] = $fileName;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($name)) {
            $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
            redirect(uri_string());
        }

        $upload_data = $this->upload->data();

        return $upload_data['file_name'];
    }

    public function create_donasi()
    {

        $donasi_name        = $this->input->post('donasi_name');
        $donasi_email       = $this->input->post('donasi_email');
        $donasi_hp          = $this->input->post('donasi_hp');
        $program_name       = $this->input->post('program_name');
        $donasi_nominal     = $this->input->post('donasi_nominal');
        $donasi_ref_id      = idate("U");
        $program_program_id = $this->input->post('program_program_id');

        if ($donasi_nominal < 10000) {
            $this->session->set_flashdata('failed', 'Nominal Donasi Minimal Rp 10.000');
            redirect('manage/program');
        }

        if ($this->input->post('as_anonim') !== null) {
            $donasi_name = $this->input->post('as_anonim');
        }

        $hp = $this->set_phone($donasi_hp);

        $va           = '0000003851378225';
        $apiKey       = 'SANDBOXE38739E0-C35F-4E62-AA36-DF18A2DD9356-20220509140520';

        $url          = 'https://sandbox.ipaymu.com/api/v2/payment';

        $method       = 'POST';

        $body['product']        = array($program_name);
        $body['qty']            = array('1');
        $body['price']          = array($donasi_nominal);
        $body['notifyUrl']      = base_url() . 'response/callback';
        $body['referenceId']    = strval($donasi_ref_id);
        $body['feeDirection']   = 'BUYER';

        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp    = Date('YmdHis');


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);

        if ($err) {
            var_dump($err);
            exit;
        } else {
            $ret = json_decode($ret);
            if ($ret->Status == 200) {
                $url        =  $ret->Data->Url;

                $insert_donasi = array(
                    'program_program_id' => $program_program_id,
                    'donasi_nominal'    => $donasi_nominal,
                    'donasi_status' => 0,
                    'donasi_ref_id' => $donasi_ref_id,
                    'donasi_name' => $donasi_name,
                    'donasi_email' => $donasi_email,
                    'donasi_hp' => $hp,
                    'donasi_datetime' => date('Y-m-d H:i:s'),
                );

                if ($this->db->insert('donasi', $insert_donasi)) {

                    $pesan = "Terima kasih kepada saudara/i " . $donasi_name . " telah memilih untuk berdonasi " . $program_name . " sebesar Rp " . number_format($donasi_nominal, 0, '.', ',') . " Silahkan berdonasi melalui link : " . $url;

                    $this->send_wa($hp, $pesan);

                    header('Location:' . $url);
                } else {
                    echo 'Failed to Insert Data';
                    exit;
                }
            } else {
                var_dump($ret);
                exit;
            }
        }
    }

    private function set_phone(String $phone): string
    {

        $phone = str_replace("-", "", $phone);
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("(", "", $phone);
        $phone = str_replace(")", "", $phone);
        $phone = str_replace(".", "", $phone);

        if (!preg_match('/[^+0-9]/', trim($phone))) {
            if (substr(trim($phone), 0, 1) == '+') {
                $hp = trim($phone);
            } elseif (substr(trim($phone), 0, 1) == '0') {
                $hp = '+62' . substr(trim($phone), 1);
            } elseif (substr(trim($phone), 0, 2) == '62') {
                $hp = '+' . trim($phone);
            } elseif (substr(trim($phone), 0, 1) == '8') {
                $hp = '+62' . trim($phone);
            } else {
                $hp = '+' . trim($phone);
            }
        }

        return $hp;
    }


    private function send_wa($no_wa, $pesan)
    {
        $api_key    = 'yQQcVtMVmE2R532CqXNolvD8rt5hjyEI';
        $sender     = '6281233315959';

        $no_send    = str_replace('+', '', $no_wa);
        $data = [
            'api_key' => $api_key,
            'sender' => $sender,
            'number' => $no_send,
            'message' => $pesan
        ];

        $url    = 'https://indoweb.notifwa.com/send-message';

        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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
        return $res = curl_exec($ch);
        curl_close($ch);
    }
}
