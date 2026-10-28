<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_guru') == NULL) {
            header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('employees/Employees_model', 'setting/Setting_model'));
    }

    public function index()
    {

        $jam_pelajaran = $this->db->get('jam_pelajaran')->result_array();

        $jam_ke = 'Tidak Ada Jam';

        $waktu_sekarang = time();

        foreach ($jam_pelajaran as $jampel) {

            $waktu_awal = strtotime($jampel['jam_pelajaran_start']);
            $waktu_akhir = strtotime($jampel['jam_pelajaran_end']);

            if ($waktu_sekarang >= $waktu_awal && $waktu_sekarang <= $waktu_akhir) {
                $jam_ke = str_replace('Jam Ke-', '', $jampel['jam_pelajaran_name']);
                break;
            }
        }

        $tanggal = date('Y-m-d');

        $hari    = pretty_date($tanggal, 'l', false);
        $guru_id = $this->session->userdata('uid_guru');

        $data['presensi_guru'] = $this->db->get_where(
            'presensi_guru',
            array(
                'presensi_guru_date' => $tanggal,
                'presensi_guru_employee_id' => $guru_id
            )
        )->result_array();

        $data['jadwal'] = $this->db->query("SELECT jam_pelajaran_name, class_name, schedule_id, lesson_name,jam_pelajaran_start,jam_pelajaran_end FROM schedule
                                            JOIN class ON class_id = schedule_class_id
                                            JOIN lesson ON lesson_id = schedule_lesson_id
                                            JOIN day ON day_id = schedule_day
                                            JOIN jam_pelajaran ON jam_pelajaran_id = schedule_jampel
                                            JOIN semester ON semester_id = schedule_semester_id
                                            JOIN period ON period_id = semester_period_id
                                            WHERE day_name = '$hari' AND lesson_teacher = '$guru_id'
                                            AND semester_status = '1' AND period_status = '1'
                                            ORDER BY jam_pelajaran_name ASC")->result_array();

        $data['jam_ke'] = $jam_ke;
        $data['title'] = 'Dashboard';
        $data['main'] = 'dashboard/dashboard_guru';
        $this->load->view('guru/layout', $data);
    }
}
