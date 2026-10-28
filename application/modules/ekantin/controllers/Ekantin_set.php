<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ekantin_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array('setting/Setting_model'));
        $this->load->helper(array('form', 'url', 'curl'));
        $this->load->library('session');
    }

    public function index()
    {
        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

        $data['categories'] = $this->get_category();
        $data['title'] = 'Data Penjual';
        $data['main'] = 'ekantin/ekantin_list';
        $this->load->view('manage/layout', $data);
    }

    public function detail_penjual($hp = null)
    {
        if ($hp == null) {
            redirect('manage/ekantin');
        }

        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

        $data['detail'] = $this->fetch_penjual_detail($hp);
        $data['title'] = 'Detail Penjual';
        $data['main'] = 'ekantin/ekantin_detail';
        $this->load->view('manage/layout', $data);
    }

    public function fetch_penjual()
    {

        $key = "mysecretkey1234567890123456";
        $nama_db = $this->db->database;

        $encrypted = $this->enkripsi($nama_db, $key);

        if (empty($nama_db) || empty($key)) {
            echo json_encode(["error" => "Parameter nama_db dan key harus diisi"]);
            return;
        }


        $base_url = $this->config->item('kantin_url') . "/api/auth/penjual";
        $params = [
            'nama_db' => urlencode($encrypted),
            'key'     => urlencode($key),
        ];

        $url = build_url_with_params($base_url, $params);

        $response = curl_request($url, 'GET');

        $data = json_decode($response, true);
        /*
        if (!$data['is_correct']) {
            echo json_encode(["error" => $data['message']]);
            return;
        }
*/
        $penjual = isset($data['penjual']) && is_array($data['penjual']) ? $data['penjual'] : [];

        if (empty($penjual)) {
            echo json_encode(["draw" => intval($this->input->get("draw")), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            return;
        }

        echo json_encode([
            "draw" => intval($this->input->get("draw")),
            "recordsTotal" => count($penjual),
            "recordsFiltered" => count($penjual),
            "data" => $penjual
        ]);
    }

    public function insert_data()
    {

        $key = "mysecretkey1234567890123456";
        $nama_db = $this->db->database;

        $encrypted = $this->enkripsi($nama_db, $key);

        if (empty($nama_db) || empty($key)) {
            echo json_encode(["error" => "Parameter nama_db dan key harus diisi"]);
            return;
        }


        $base_url = $this->config->item('kantin_url') . "/api/auth/penjual-store";

        $nama_sekolah = $this->Setting_model->get(array('id' => 1));

        $data = [
            'langganan' => $this->input->post('langganan', TRUE),
            'no_hp' => $this->input->post('no_hp', TRUE),
            'nama_toko'  => $this->input->post('nama_toko', TRUE),
            'nama_pemilik'  => $this->input->post('nama_pemilik', TRUE),
            'email'  => $this->input->post('email', TRUE),
            'nama_sekolah'  => $nama_sekolah['setting_value'],
            'nama_db'  => $encrypted,
            'key'   => $key,
            'hostname' => $this->db->hostname,
            'id_server' => '3'
        ];


        $response = curl_request($base_url, 'POST', $data);

        echo $response;
    }

    public function aktif_perdana()
    {
        if (!$_POST) {
            redirect('manage/ekantin');
        }

        $id_toko = $this->input->post('id_toko', TRUE);
        $id_langganan = $this->input->post('id_langganan', TRUE);
        $awal_langganan = $this->input->post('awal_langganan', TRUE);
        $durasi = $this->input->post('durasi', TRUE);

        $firstMonth = new DateTime($awal_langganan);
        $firstExpired = clone $firstMonth;
        $firstExpired->modify('+1 month');

        $base_url = $this->config->item('kantin_url') . "/api/auth/langganan/" . $id_langganan;
        $data = [
            'awal_langganan' => $firstMonth->format('Y-m-d'),  // typo diperbaiki
            'expired'        => $firstExpired->format('Y-m-d'),
            'status'         => 'active',
        ];

        curl_request($base_url, 'PATCH', $data);


        $base_url = $this->config->item('kantin_url') . "/api/auth/tambah-langganan/" . $id_toko;
        if ($durasi > 1) {

            for ($i = 1; $i < $durasi; $i++) {
                $start = new DateTime($awal_langganan);
                $start->modify("+$i month");

                $expired = clone $start;
                $expired->modify('+1 month');

                $data = [
                    'awal_langganan' => $start->format('Y-m-d'),
                    'expired'        => $expired->format('Y-m-d'),
                    'status'         => 'not-active',
                ];

                curl_request($base_url, 'POST', $data);
            }
        }

        $this->session->set_flashdata('success', ' Toko Berhasil Diaktifkan');
        redirect('manage/ekantin/detail_penjual/' . $id_toko);
    }

    public function aktifkan_langganan()
    {
        if (!$_POST) {
            redirect('manage/ekantin');
        }

        $id_langganan = $this->input->post('id_langganan', TRUE);


        if ($id_langganan == null) {
            redirect('manage/ekantin');
        }

        $data_langganan = $this->data_langganan();

        $selected_langganan = array_filter($data_langganan, function ($langganan) use ($id_langganan) {
            return isset($langganan['id_langganan']) && $langganan['id_langganan'] == $id_langganan;
        });

        if (empty($selected_langganan)) {
            echo json_encode(["error" => "Data langganan tidak ditemukan"]);
            return;
        }

        $selected_langganan = array_values($selected_langganan)[0];

        $base_url = $this->config->item('kantin_url') . "/api/auth/langganan/" . $id_langganan;
        $data = [
            'status'     => 'active',
        ];

        $response = curl_request($base_url, 'PATCH', $data);

        echo $response;
    }

    public function cancel_langganan()
    {
        if (!$_POST) {
            redirect('manage/ekantin');
        }

        $id_langganan = $this->input->post('id_langganan', TRUE);


        if ($id_langganan == null) {
            redirect('manage/ekantin');
        }

        $data_langganan = $this->data_langganan();

        $selected_langganan = array_filter($data_langganan, function ($langganan) use ($id_langganan) {
            return isset($langganan['id_langganan']) && $langganan['id_langganan'] == $id_langganan;
        });

        if (empty($selected_langganan)) {
            echo json_encode(["error" => "Data langganan tidak ditemukan"]);
            return;
        }

        $selected_langganan = array_values($selected_langganan)[0];

        $base_url = $this->config->item('kantin_url') . "/api/auth/langganan/" . $id_langganan;
        $data = [
            'status'     => 'not-active',
        ];

        $response = curl_request($base_url, 'PATCH', $data);

        echo $response;
    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function enkripsi($data, $key)
    {
        $iv = random_bytes(12); // 12 bytes IV untuk AES-GCM
        $tag = '';
        $ciphertext = openssl_encrypt(
            $data,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        $payload = $iv . $ciphertext . $tag;
        return $this->base64url_encode($payload);
    }

    /*
    private function enkripsi($data, $key)
    {
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
*/
    private function get_category()
    {
        $base_url = $this->config->item('kantin_url') . "/api/auth/kategori";
        $params = [];

        $url = build_url_with_params($base_url, $params);

        $response = curl_request($url, 'GET');

        $data = json_decode($response, true);

        if (!$data['is_correct']) {
            echo json_encode(["error" => "Gagal menghubungi API "]);
            return;
        }

        $category = isset($data['kategori']) && is_array($data['kategori']) ? $data['kategori'] : [];

        return $category;
    }


    private function data_langganan()
    {
        $base_url = $this->config->item('kantin_url') . "/api/auth/langganan";
        $params = [];

        $url = build_url_with_params($base_url, $params);

        $response = curl_request($url, 'GET');

        $data = json_decode($response, true);

        if (!$data['is_correct']) {
            echo json_encode(["error" => "Gagal menghubungi API "]);
            return;
        }

        return $data['kategori'];
    }

    private function fetch_penjual_detail($hp = null)
    {

        if ($hp == null) {
            redirect('manage/ekantin');
        }

        $key = "mysecretkey1234567890123456";
        $nama_db = $this->db->database;

        $encrypted = $this->enkripsi($nama_db, $key);

        if (empty($nama_db) || empty($key)) {
            echo json_encode(["error" => "Parameter nama_db dan key harus diisi"]);
            return;
        }


        $base_url = $this->config->item('kantin_url') . "/api/auth/penjual/" . $hp;
        $params = [
            'nama_db' => urlencode($encrypted),
            'key'     => urlencode($key),
        ];

        $url = build_url_with_params($base_url, $params);

        $response = curl_request($url, 'GET');

        $data = json_decode($response, true);

        if (!$data['is_correct']) {
            echo json_encode(["error" => "Gagal menghubungi API "]);
            return;
        }

        return $data['penjual'];
    }

    public function pencairan()
    {
        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

        $data['categories'] = $this->get_category();
        $data['title'] = 'Data Penjual';
        $data['main'] = 'ekantin/ekantin_pencairan';
        $this->load->view('manage/layout', $data);
    }

    public function detail_pencairan($hp = null)
    {
        if ($hp == null) {
            redirect('manage/ekantin');
        }

        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

        $detail = $this->fetch_pencairan_detail($hp);

        $data['detail'] = $detail[0];

        $data['title'] = 'Detail Pencairan';
        $data['main'] = 'ekantin/ekantin_detail_pencairan';
        $this->load->view('manage/layout', $data);
    }



    public function fetch_pencairan()
    {

        $key = "mysecretkey1234567890123456";
        $nama_db = $this->db->database;

        $encrypted = $this->enkripsi($nama_db, $key);

        if (empty($nama_db) || empty($key)) {
            echo json_encode(["error" => "Parameter nama_db dan key harus diisi"]);
            return;
        }


        $base_url = $this->config->item('kantin_url') . "/api/auth/pencairan-penjual";
        $params = [
            'nama_db' => urlencode($encrypted),
            'key'     => urlencode($key),
        ];

        $url = build_url_with_params($base_url, $params);

        $response = curl_request($url, 'GET');

        $data = json_decode($response, true);

        if (!$data['is_correct']) {
            echo json_encode(["error" => "Gagal menghubungi API "]);
            return;
        }

        $pencairan = isset($data['data']) && is_array($data['data']) ? $data['data'] : [];

        if (empty($pencairan)) {
            echo json_encode(["draw" => intval($this->input->get("draw")), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            return;
        }

        echo json_encode([
            "draw" => intval($this->input->get("draw")),
            "recordsTotal" => count($pencairan),
            "recordsFiltered" => count($pencairan),
            "data" => $pencairan
        ]);
    }

    private function fetch_pencairan_detail($hp = null)
    {

        if ($hp == null) {
            redirect('manage/ekantin/pencairan');
        }

        $key = "mysecretkey1234567890123456";
        $nama_db = $this->db->database;

        $encrypted = $this->enkripsi($nama_db, $key);

        if (empty($nama_db) || empty($key)) {
            echo json_encode(["error" => "Parameter nama_db dan key harus diisi"]);
            return;
        }


        $base_url = $this->config->item('kantin_url') . "/api/auth/pencairan-penjual-detail/" . $hp;
        $params = [
            'nama_db' => urlencode($encrypted),
            'key'     => urlencode($key),
        ];

        $url = build_url_with_params($base_url, $params);

        $response = curl_request($url, 'GET');

        $data = json_decode($response, true);

        if (!$data['is_correct']) {
            echo json_encode(["error" => "Gagal menghubungi API "]);
            return;
        }

        return $data['data'];
    }

    public function get_form()
    {
        if ($_POST) {
            $pencairan = $_POST["pencairan"];

            foreach ($pencairan as $itemPencairan) {

                echo '<input type="hidden" name="item_pencairan[]" id="item_pencairan" value="' . $itemPencairan . '">';
            }
        }
    }

    public function do_pencairan()
    {
        if ($_POST) {
            $no_hp = $this->input->post('no_hp', TRUE);
            $item_pencairan = $this->input->post('item_pencairan', TRUE);

            $cntPencairan = count($item_pencairan);
            for ($i = 0; $i < $cntPencairan; $i++) {
                $itemPencairan = $this->removeLeadingZeroFromYmd($item_pencairan[$i]);
                $item = explode('-', $itemPencairan);
                $year = $item[0];
                $month = $item[1];
                $date = $item[2];

                $base_url = $this->config->item('kantin_url') . "/api/auth/pencairan-web";
                $params = [
                    'tahun'     => $year,
                    'bulan'     => $month,
                    'tanggal'   => $date,
                    'no_hp'     => $no_hp
                ];


                $url = build_url_with_params($base_url, $params);

                curl_request($url, 'PATCH');
            }

            $this->session->set_flashdata('success', ' Pencairan Dana Berhasil Dilakukan');
            redirect('manage/ekantin/detail_pencairan/' . $no_hp);
        }
    }

    private function removeLeadingZeroFromYmd($date)
    {
        $parts = explode('-', $date);
        if (count($parts) !== 3) return $date; // Tidak valid, kembalikan original

        list($year, $month, $day) = $parts;

        return (int)$year . '-' . (int)$month . '-' . (int)$day;
    }
}
