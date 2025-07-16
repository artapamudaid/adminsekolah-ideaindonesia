<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pendayagunaan_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'pendayagunaan/Pendayagunaan_model', 'logs/Logs_model', 'setting/Setting_model'));
        $this->load->library('upload');
    }

    // pendayagunaan view in list
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
            $params['pendayagunaan_name'] = $f['n'];
        }

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
        }

        $data['pendayagunaan'] = $this->Pendayagunaan_model->get($params);

        $config['base_url'] = site_url('manage/pendayagunaan/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Pendayagunaan';
        $data['main'] = 'pendayagunaan/pendayagunaan_list';
        $this->load->view('manage/layout', $data);
    }

    public function export()
    {

        $id = $_GET['program'];
        $start = $_GET['start'];
        $end = $_GET['end'];

        $data['program'] = $this->Pendayagunaan_model->get(array('id' => $id));
        $data['title'] = $data['program']['program_name'];

        $data['program_id'] = $data['program']['program_id'];

        $this->db->order_by('donasi_datetime', 'ASC');
        $data['donasi'] = $this->db->get_where('donasi', array(
            'program_program_id' => $data['program_id'],
            'donasi_status' => 1,
            'DATE(donasi_datetime) >=' => $start,
            'DATE(donasi_datetime) <=' => $end
        ))->result_array();

        $this->db->order_by('pendayagunaan_date', 'ASC');
        $data['pendayagunaan'] = $this->db->get_where('pendayagunaan', array(
            'program_program_id' => $data['program_id'],
            'DATE(pendayagunaan_date) >=' => $start,
            'DATE(pendayagunaan_date) <=' => $end
        ))->result_array();

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('pendayagunaan/pendayagunaan_xls', $data);
    }

    public function view($id = NULL)
    {

        $data['program'] = $this->Pendayagunaan_model->get(array('id' => $id));
        $data['title'] = $data['program']['program_name'];

        $data['program_id'] = $data['program']['program_id'];

        $account_id = $data['program']['pendayagunaan_account_id'];

        $account_detail = $this->get_account_detail($account_id);

        $majors_id = $account_detail['majors_id'];

        $whereMajors = NULL;
        if ($majors_id != '0') {
            $whereMajors = "AND account_majors_id = '$majors_id'";
        }

        $data['account'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_category = '2' $whereMajors")->result_array();

        $this->db->order_by('donasi_datetime', 'DESC');
        $data['donasi'] = $this->db->get_where('donasi', array(
            'program_program_id' => $data['program_id'],
            'donasi_status' => 1
        ))->result_array();

        $this->db->order_by('pendayagunaan_date', 'DESC');
        $data['pendayagunaan'] = $this->db->get_where('pendayagunaan', array(
            'program_program_id' => $data['program_id'],
        ))->result_array();

        $data['main'] = 'pendayagunaan/pendayagunaan_view';
        $this->load->view('manage/layout', $data);
    }

    public function add()
    {

        $data = array(
            'program_program_id' => $this->input->post('program_program_id'),
            'pendayagunaan_date' => $this->input->post('pendayagunaan_date'),
            'pendayagunaan_nominal' => $this->input->post('pendayagunaan_nominal'), 'pendayagunaan_note' => $this->input->post('pendayagunaan_note'),
        );

        $insert = $this->db->insert('pendayagunaan', $data);

        if ($insert) {
            $pendayagunaan_id = $this->db->insert_id();
            $nominal = $this->input->post('pendayagunaan_nominal');

            $program = $this->db->query("SELECT a.pendayagunaan_note, b.program_id, b.program_name, b.pendayagunaan_account_id FROM pendayagunaan a JOIN program b ON a.program_program_id = b.program_id WHERE a.pendayagunaan_id = '$pendayagunaan_id'")->row_array();

            $program_id = $program['program_id'];

            $earnProgram = $this->db->query("UPDATE program SET program_realisasi = program_realisasi + $nominal WHERE program_id = '$program_id'");

            if ($earnProgram) {

                $data_jurnal = array(
                    'note'       => $program['pendayagunaan_note'],
                    'nominal'    => $nominal,
                    'account_id' => $program['pendayagunaan_account_id'],
                    'aktiva_id'  => $this->input->post('aktiva_id')
                );

                if ($this->insert_jurnal($data_jurnal)) {
                    redirect('manage/pendayagunaan/view/' . $program_id);
                }
            }
        }
    }

    private function insert_jurnal(array $data_jurnal = []): bool
    {

        $note           = $data_jurnal['note'];
        $nominal        = $data_jurnal['nominal'];
        $account_id     = $data_jurnal['account_id'];
        $aktiva_id      = $data_jurnal['aktiva_id'];

        $account        = $this->get_account_detail($account_id);

        $majors_id      = $account['majors_id'];
        $account_code   = $account['account_code'];

        $aktiva_code    = $this->get_aktiva_code($aktiva_id);

        $jurnal_umum = array(
            'sekolah_id'         => $majors_id,
            'keterangan'         => $note,
            'tanggal'            => date('Y-m-d'),
            'pencatatan'         => 'auto',
            'waktu_update'       => date('Y-m-d H:i:s'),
            'keterangan_lainnya' => '-'
        );

        $this->db->insert('jurnal_umum', $jurnal_umum);

        $jurum = $this->db->query("SELECT MAX(id) AS last_id FROM jurnal_umum")->row_array();
        $lastJurum = $jurum['last_id'];

        $dataKas = array(
            'id_jurnal' => $lastJurum,
            'account_code' => $account_code,
            'debet' => $nominal,
            'kredit' => 0,
        );

        $this->db->insert('jurnal_umum_detail', $dataKas);

        $dataKas = array(
            'id_jurnal' => $lastJurum,
            'account_code' => $aktiva_code,
            'debet' => 0,
            'kredit' => $nominal,
        );

        if ($this->db->insert('jurnal_umum_detail', $dataKas)) {
            $status =  true;
        } else {
            $status = false;
        }

        return $status;
    }

    private function get_aktiva_code(int $id): string
    {
        $aktiva = $this->db->query("SELECT account_code FROM account WHERE account_id = '$id'")->row_array();

        return $aktiva['account_code'];
    }

    private function get_account_detail(int $id): array
    {
        $akun = $this->db->query("SELECT account_code,account_majors_id FROM account WHERE account_id = '$id'")->row_array();

        $data = array(
            'account_code' => $akun['account_code'],
            'majors_id' => $akun['account_majors_id']
        );

        return $data;
    }
}
