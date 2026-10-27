<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_umum_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'logs/Logs_model'));
        $this->load->library('upload');
        $this->load->helper('pembulatan_helper');
    }

    public function index($offset = NULL)
    {
        $this->load->library('pagination');
        $s = $this->input->get(NULL, TRUE);
        $data['s'] = $s;
        $params = array();
        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
        }
        $config['base_url'] = site_url('manage/jurnal_umum/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $data['majors'] = $this->Student_model->get_majors();
        $data['title'] = 'Jurnal Umum';
        $data['main'] = 'jurnal_umum/jurnal_umum_list';
        $this->load->view('manage/layout', $data);
    }

    public function list_jurnal()
    {
        $get = $this->input->get();
        $this->load->model('jurnal_umum_model');
        $tabelmodel = $this->jurnal_umum_model;
        $list = $tabelmodel->get_datatable('selectdata');
        $data = array();
        foreach ($list as $r) {
            $row = array();
            $row[] = pretty_date($r->tanggal, 'd F Y', false);
            $row[] = $r->noref;
            $row[] = $r->account_code;
            $row[] = $r->account_description;
            $row[] = $r->keterangan;
            $row[] = $r->debet;
            $row[] = $r->kredit;
            $row[] = ''; // Menginisialisasi nilai untuk kolom aksi

            if ($r->pencatatan == 'manual') { // Menambahkan aksi hanya jika pencatatan manual
                $row[7] = '
                    <a onclick="edit(this)" data-id="' . $r->id . '" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                    <a onclick="hapus(this)" data-id="' . $r->id . '" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                ';
            } else {
                $row[7] = '
                <span class="label label-success">Pencatan Auto</span>
                ';
            }

            $data[] = $row;
        }

        $total = $tabelmodel->get_datatable('alldata');

        $debet = 0;
        $kredit = 0;
        foreach ($total as $r) {
            $debet += $r->debet;
            $kredit += $r->kredit;
        }

        $result = array(
            "draw" => $get['draw'],
            "recordsTotal" => $tabelmodel->get_datatable('countall'),
            "recordsFiltered" => $tabelmodel->get_datatable('countfilter'),
            "data" => $data,
            "debet" => $debet,
            "kredit" => $kredit
        );

        echo json_encode($result);
    }

    public function list_all_coa_jurnal()
    {
        $array = array();
        $this->load->model('jurnal_umum_model');
        $kword = $this->input->get('kword');
        $account_majors_id = $this->input->get('kategori');
        $datas = $this->jurnal_umum_model->list_all_coa_jurnal($kword, $account_majors_id);

        // echo $this->db->last_query();
        // exit;
        foreach ($datas->result_array() as $r) {
            $row['id'] = $r['account_code'];
            $row['text'] = '[' . $r['account_code'] . '] ' . $r['account_description'];
            $array[] = $row;
        }

        echo '{"data":' . json_encode($array) . '}';
    }

    private function rules()
    {
        return [
            [
                'field' => 'keterangan',
                'label' => ' ',
                'rules' => 'required',
            ],
            [
                'field' => 'sekolah_id',
                'label' => ' ',
                'rules' => 'required',
            ],
        ];
    }

    public function tambahjurnal()
    {
        $post = $this->input->post();
        $this->load->library('form_validation');
        $validation = $this->form_validation;
        $validasi_tambahan = 0;
        $error2 = array();
        $debet_total = 0;
        $kredit_total = 0;
        $kode_akun = array();
        $kode_akun = $this->input->post("kode_akun");
        $debet = $this->input->post("debet");
        $kredit = $this->input->post("kredit");
        $tanggal = $post['tanggal'];

        if ($kode_akun != TRUE) {
            $error2['fail'] = "Mohon Pilih Akun";
            $validasi_tambahan = 1;
        } elseif ($kode_akun[0] == '') {
            $error2['fail'] = "Mohon Pilih Akun";
            $validasi_tambahan = 1;
        } else {
            for ($i = 0; $i < count($kode_akun); $i++) {
                $kredit_total = (float) $kredit_total + (float) _desimal($kredit[$i]);
                $debet_total = (float) $debet_total + (float) _desimal($debet[$i]);
            }
            if ($debet_total != $kredit_total) {
                $error2['fail'] = "debit dan redit tidak balance";
                $validasi_tambahan = 1;
            }

            if (!$tanggal && empty($tanggal)) {
                $error2['fail'] = 'Mohon Pilih Tanggal';
                $validasi_tambahan = 1;
            }
        }
        $validation->set_rules($this->rules());
        if ($this->form_validation->run() == FALSE || $validasi_tambahan > 0) {
            $error = $this->form_validation->error_array();
            $data['errors'] = $error + $error2;
        } else {
            $this->load->model('jurnal_umum_model');
            $simpan = $this->jurnal_umum_model;
            if ($simpan->simpanjurnal()) {
                $data['success'] = true;
                $data['message'] = "Berhasil Menyimpan Data";
            } else {
                $errors['fail'] = "Gagal Menyimpan Data";
                $data['errors'] = $errors;
            }
        }
        echo json_encode($data);
    }

    public function hapusjurnal()
    {
        $post = $this->input->post();
        $kondisi = array(
            'id' => $post["idd"],
        );

        $this->load->model('jurnal_umum_model');
        $hapus = $this->jurnal_umum_model;
        if ($hapus->hapusdata()) {
            $data['success'] = true;
            $data['message'] = "Berhasil Menghapus Data";
        } else {
            $errors['fail'] = "Gagal Menghapus Data";
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    }


    public function detaildata()
    {
        $get = $this->input->get();
        $this->load->model('jurnal_umum_model');
        $panggil = $this->jurnal_umum_model;
        $data = $panggil->_jurnal($get["id"]);
        $result = array(
            "id" => $data->row()->id,
            "sekolah_id" => $data->row()->sekolah_id,
            "keterangan" => $data->row()->keterangan,
            "tanggal" => $data->row()->tanggal,
            "keterangan_lainnya" => $data->row()->keterangan_lainnya
        );
        $array[] =  $result;

        $arraysub = array();
        $subitem = $this->jurnal_umum_model->_jurnal_detail($data->row()->id);
        foreach ($subitem as $r) {
            if ($r->debet > 0) {
                $debet = rp($r->debet);
                $debet_input = ubahkoma($r->debet);
            } else {
                $debet = '';
                $debet_input = '';
            }
            if ($r->kredit > 0) {
                $kredit = rp($r->kredit);
                $kredit_input = ubahkoma($r->kredit);
            } else {
                $kredit = '';
                $kredit_input = '';
            }
            $subArray['kode_akun'] = $r->account_code;
            $subArray['nama_akun'] = $r->account_description;
            $subArray['debet'] = $debet;
            $subArray['debet_input'] = $debet_input;
            $subArray['kredit'] = $kredit;
            $subArray['kredit_input'] = $kredit_input;
            $arraysub[] =  $subArray;
        }
        echo '{"datarows":' . json_encode($array) . ',"datasub":' . json_encode($arraysub) . '}';
    }

    public function edit()
    {
        $post = $this->input->post();
        $this->load->library('form_validation');
        $validation = $this->form_validation;
        $validasi_tambahan = 0;
        $error2 = array();
        $debet_total = 0;
        $kredit_total = 0;
        $kode_akun = array();
        $kode_akun = $this->input->post("kode_akun");
        $debet = $this->input->post("debet");
        $kredit = $this->input->post("kredit");
        $tanggal = $post['tanggal'];

        if ($kode_akun != TRUE) {
            $error2['fail'] = "Mohon Pilih Akun";
            $validasi_tambahan = 1;
        } elseif ($kode_akun[0] == '') {
            $error2['fail'] = "Mohon Pilih Akun";
            $validasi_tambahan = 1;
        } else {
            for ($i = 0; $i < count($kode_akun); $i++) {
                $kredit_total = (float) $kredit_total + (float) _desimal($kredit[$i]);
                $debet_total = (float) $debet_total + (float) _desimal($debet[$i]);
            }
            if ($debet_total != $kredit_total) {
                $error2['fail'] = "debit dan redit tidak balance";
                $validasi_tambahan = 1;
            }

            if (!$tanggal && empty($tanggal)) {
                $error2['fail'] = 'Mohon Pilih Tanggal';
                $validasi_tambahan = 1;
            }
        }
        $validation->set_rules($this->rules());
        if ($this->form_validation->run() == FALSE || $validasi_tambahan > 0) {
            $error = $this->form_validation->error_array();
            $data['errors'] = $error + $error2;
        } else {
            $this->load->model('jurnal_umum_model');
            $simpan = $this->jurnal_umum_model;
            if ($simpan->updatedata()) {
                $data['success'] = true;
                $data['message'] = "Berhasil Menyimpan Data";
            } else {
                $errors['fail'] = "Gagal Menyimpan Data";
                $data['errors'] = $errors;
            }
        }
        echo json_encode($data);
    }
}
