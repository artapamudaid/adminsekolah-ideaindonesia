<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Presensi_guru_guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_guru') == NULL) {
            header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }

        if ($this->session->userdata('uiswali_guru') == 0) {
            redirect('guru');
        }

        $this->load->model(array('student/Student_model', 'setting/Setting_model'));
        $this->load->helper(array('form', 'url'));
    }


    public function index()
    {

        $f = $this->input->get(NULL, TRUE);

        $data['f']  = $f;

        $guru_id = $this->session->userdata('uid_guru');

        $params     = array();
        $start      = date('Y-m-d');
        $class_id   = NULL;

        if (isset($f['date']) && !empty($f['date']) && $f['date'] != '') {
            $start  = $f['date'];
        }

        // Nip
        if (isset($f['c']) && !empty($f['c']) && $f['c'] != '') {
            $params['class_id'] = $f['c'];
            $class_id = $f['c'];


            $begin = new DateTime($start);
            $finish = new DateTime($start);

            $finish->modify('+1 day');

            $interval = new DateInterval('P1D');
            $data['daterange']  = new DatePeriod($begin, $interval, $finish);

            $data['student']    = $this->Student_model->get_jurnal(array('class_id' => $class_id));

            $semester           = $this->db->query("SELECT semester_id
                                                    FROM semester
                                                    WHERE semester_status = '1'")->row_array();
            $semester_id        = $semester['semester_id'];

            $data['jampel']     = $this->db->query("SELECT DISTINCT c.day_id, c.day_name, b.jam_pelajaran_id,
                                                    b.jam_pelajaran_name FROM schedule a
                                                    JOIN jam_pelajaran b ON b.jam_pelajaran_id = a.schedule_jampel
                                                    JOIN day c ON c.day_id = a.schedule_day
                                                    ORDER BY c.day_id ASC, b.jam_pelajaran_name ASC")->result_array();

            $data['presensi']   = $this->db->query("SELECT jurnal.jurnal_date, class.class_id, class.class_name,
                                                    student.student_id, student.student_full_name,jam_pelajaran.jam_pelajaran_id,
                                                    REPLACE(jam_pelajaran.jam_pelajaran_name, 'Jam Ke-', '') jampel,
                                                    jurnal.jurnal_status
                                                    FROM jurnal
                                                    JOIN schedule ON schedule.schedule_id = jurnal.jurnal_schedule_id
                                                    JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = `schedule`.schedule_jampel
                                                    JOIN student ON student.student_id = jurnal.jurnal_student_id
                                                    JOIN class ON class.class_id = student.class_class_id
                                                    WHERE jurnal_date BETWEEN '$start' AND '$start'
                                                    AND jurnal_semester_id = '$semester_id'
                                                    AND class.class_id = $class_id
                                                    ORDER BY class_name ASC, jampel ASC, student_full_name ASC")->result_array();
        }


        $data['class'] = $this->db->query("SELECT b.* FROM wali_kelas a
                                      JOIN class b ON a.wali_kelas_class_id = b.class_id
                                      WHERE a.wali_kelas_employee_id = '$guru_id'")->result_array();

        $data['title']          = 'Presensi Kelas';
        $data['main']       = 'presensi_guru/presensi_guru_view';
        $this->load->view('guru/layout', $data);
    }
}
