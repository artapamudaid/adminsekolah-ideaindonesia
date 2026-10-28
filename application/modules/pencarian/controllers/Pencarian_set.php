<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pencarian_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
        $this->load->model(array(
            'student/Student_model',
            'period/Period_model',
            'employees/Employees_model'
        ));
    }

    public function index()
    {
        $keyword = $this->input->get('q', TRUE) ? trim($this->security->xss_clean($this->input->get('q', TRUE))) : '';

        $data['keyword'] = $keyword;
        $data['students'] = array();
        $data['employees'] = array();
        $data['payments'] = array();
        $data['periodActive'] = $this->db->query("SELECT period_id FROM period WHERE period_status = '1' LIMIT 1")->row_array();

        if ($keyword !== '') {
            // Siswa: cari berdasarkan NIS (exact) atau nama (LIKE)
            $data['students'] = $this->Student_model->get(array(
                'student_search' => $keyword,
                'limit' => 20
            ));

            // Pegawai: cari berdasarkan NIP (exact) atau nama (LIKE)
            $data['employees'] = $this->Employees_model->get(array(
                'employee_search' => $keyword,
                'limit' => 20
            ));

            // Pembayaran Siswa: menampilkan siswa dengan tautan payout
            $data['payments'] = $data['students'];
        }

        $data['title'] = 'Pencarian';
        $data['main'] = 'pencarian/pencarian_list';
        $this->load->view('manage/layout', $data);
    }
}

