<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Layanan_publik extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);

        $this->load->model(array('publik/Publik_model', 'survei/Survei_model', 'layanan_publik/Layanan_publik_model', 'pengaduan/Pengaduan_model', 'position/Position_model', 'setting/Setting_model'));
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
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

        $data['layanan_publik'] = $this->Layanan_publik_model->get();
        $data['title'] = 'layanan_publik';
        $data['main'] = 'layanan_publik/layanan_publik_list';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('frontend/layanan', $data);
    } 
    
    // program view in list
    public function tiket($offset = NULL)
    {
        $status             = $_GET['status'];
        if($status == 0){
            $data['status']   = 'Menunggu Respon Dari Admin';
        }elseif ($status== 1){
            $data['status']   = 'Sedang Di proses Admin';
        }else{
            $data['status']   = 'Layanan Selesai';
        }
        $data['no_tiket']   = $_GET['tiket'];
        $data['title']      = 'Kode Tiket Antrian';
        $data['main']       = 'tiket';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('layanan_publik/main_form', $data);
    }

    // Publik
    public function publik($offset = NULL)
    {
        $this->load->library('pagination');

        $now = date('dmy');
        
        $query = $this->db->query("SELECT MAX(RIGHT(publik_no,2)) AS no_max FROM publik 
        WHERE SUBSTR(publik_no, -8, 6) = '$now'")->row();
        
        if (count(array($query))>0){
            $tmp = ((int)$query->no_max)+1;
            $norut = sprintf("%02s", $tmp);
        } else {
            $norut = "01";
        }
    
        $date =  date('Y-m');
        $data['publik_no']= 'LTP/'.$date.'/'.$now.$norut;
        $data['position'] = $this->Position_model->get_position();

        $simpan = $this->input->post('simpan');
        if(!empty($simpan)){
    
            $params['publik_no']              = $this->input->post('publik_no');
            $params['publik_nama']            = $this->input->post('publik_nama');
            $params['publik_alamat']          = $this->input->post('publik_alamat');
            $params['publik_telepon']         = $this->input->post('publik_telepon');
            $params['publik_nik']             = $this->input->post('publik_nik');
            $params['publik_position_id']     = $this->input->post('publik_position_id');
            $params['publik_status']          = 0;
            $params['keperluan']              = $this->input->post('keperluan');
            $params['id_users']               = $this->input->post('id_users');
            $status                           = $this->Publik_model->add($params);

            if ($status) {
                // Jika penambahan data berhasil
                echo "
                    <script>
                        alert('Terima Kasih Sudah menggunakan Layanan Publik Kami');
                        window.location.href = '" . site_url('layanan_publik/tiket?tiket='.$this->input->post('publik_no') .'&status=0') . "'; 
                    </script>
                ";
            } else {
                // Jika penambahan data gagal
                echo "
                    <script>
                        alert('Gagal Input Layanan Publik');
                        window.history.back();
                    </script>
                ";
            }
        }  
        $data['layanan_publik'] = $this->Layanan_publik_model->get();

        $data['title']  = 'LAYANAN TAMU DAN PUBLIK';
        $data['main']   = 'publik';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('layanan_publik/main_form', $data);
    }

    // Survei
    public function survei($offset = NULL)
    {
        $this->load->library('pagination');

        $simpan = $this->input->post('simpan');
        if(!empty($simpan)){
            $params['survei_nama']            = $this->input->post('survei_nama');
            $params['survei_alamat']          = $this->input->post('survei_alamat');
            $params['survei_respon']          = $this->input->post('survei_respon');
            $params['survei_catatan']         = $this->input->post('survei_catatan');
            $params['id_users']               = $this->input->post('id_users');
            $status                           = $this->Survei_model->add($params);
            if ($status) {
                // Jika penambahan data berhasil
                echo "
                    <script>
                        alert('Terima Kasih Sudah melakukan Survei Kepuasan Terhadap Sekolah Kami');
                        window.location.href = '" . site_url('layanan_publik') . "'; 
                    </script>
                ";
            } else {
                // Jika penambahan data gagal
                echo "
                    <script>
                        alert('Gagal mengirim melakukan Survei Kepuasan');
                        window.history.back();
                    </script>
                ";
            }
        }       

        // if ($this->input->post('from_angular')) {
        //     echo $status;
        // }

        $data['layanan_publik'] = $this->Layanan_publik_model->get();

        $data['title']  = 'SURVEI KEPUASAN PELAYANAN ';
        $data['main']   = 'survei';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('layanan_publik/main_form', $data);
    }

    // Pengaduan
    public function pengaduan($offset = NULL)
    {
        $this->load->library('pagination');
        $now = date('dmy');
        
        $query = $this->db->query("SELECT MAX(RIGHT(pengaduan_no,2)) AS no_max FROM pengaduan 
        WHERE SUBSTR(pengaduan_no, -8, 6) = '$now'")->row();
        
        if (count(array($query))>0){
            $tmp = ((int)$query->no_max)+1;
            $norut = sprintf("%02s", $tmp);
        } else {
            $norut = "01";
        }
    
        $date =  date('Y-m');
        $data['pengaduan_no']= 'LP/'.$date.'/'.$now.$norut;
    

        $simpan = $this->input->post('simpan');
        if(!empty($simpan)){
            $params['pengaduan_no']     = $this->input->post('pengaduan_no');
            $params['pengaduan_nama']   = $this->input->post('pengaduan_nama');
            $params['pengaduan_tujuan'] = $this->input->post('pengaduan_tujuan');
            $params['pengaduan_detail'] = $this->input->post('pengaduan_detail');
            $params['pengaduan_status'] = 0;
            $params['id_users']         = $this->input->post('id_users');
            $status                     = $this->Pengaduan_model->add($params);
            if ($status) {
                // Jika penambahan data berhasil
                echo "
                    <script>
                        alert('Terima Kasih Sudah Menggunakan Layanan Pengaduan Terhadap Sekolah Kami');
                        window.location.href = '" . site_url('layanan_publik') . "'; 
                    </script>
                ";
            } else {
                // Jika penambahan data gagal
                echo "
                    <script>
                        alert('Gagal mengirim Menggunakan Layanan Survei Kepuasan');
                        window.history.back();
                    </script>
                ";
            }
        }  

        $data['layanan_publik'] = $this->Layanan_publik_model->get();

        $data['title']  = 'LAYANAN PENGADUAN ';
        $data['main']   = 'pengaduan';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('layanan_publik/main_form', $data);
    }

    // Cek layanan
    public function pencarian($offset = NULL)
    {
        $this->load->library('pagination');

        $cari = $this->input->post('cari');
        if(!empty($cari)){
            $tiket = $this->input->post('tiket');

            // Menggunakan query binding untuk menghindari SQL injection
            $query = $this->db->query("SELECT * FROM publik WHERE publik_no = ?", array($tiket));

            // Memeriksa apakah ada hasil dari query
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                $status = $result['publik_status'];

                echo "
                    <script>
                        window.location.href = '" . site_url('layanan_publik/tiket?tiket='.$tiket .'&status='.$status) . "'; 
                    </script>
                ";
            } else {
                // Jika tidak ada hasil dari query
                echo "
                    <script>
                        alert('Nomor Antrian Tidak Ditemukan');
                        window.history.back();
                    </script>
                ";
            }
        }  

        // Mengambil data layanan publik
        $data['layanan_publik'] = $this->Layanan_publik_model->get();

        // Mengatur judul dan tampilan
        $data['title'] = 'Cek Status Layanan';
        $data['main'] = 'pencarian';

        // Mengambil pengaturan dari model Setting_model
        $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
        $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        // Memuat tampilan view dengan data yang sudah diambil
        $this->load->view('layanan_publik/main_form', $data);
    }


}
