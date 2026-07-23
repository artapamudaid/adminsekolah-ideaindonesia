<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_jurnal_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array(
            'student/Student_model',
            'setting/Setting_model',
            'employees/Employees_model',
            'period/Period_model',
            'semester/Semester_model'
        ));
        $this->load->library('upload');
        $this->load->helper(array('get_column'));
    }

    public function index($offset = NULL)
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $class_id = NULL;
        $lesson_id = NULL;
        $id_guru = NULL;
        $semester_id = NULL;
        $period_id = NULL;

        $params = array();

        if (isset($q['t']) && !empty($q['t']) && $q['t'] != '') {
            $id_guru  = $q['t'];
        }

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $period_id  = $q['p'];
        }

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $semester_id  = $q['s'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $class_id  = $q['c'];
            $params['class_id'] = $q['c'];
            $data['class_name'] = $this->db->query("SELECT class_name FROM class WHERE class_id = '$class_id'")->row_array();
        }

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id  = $q['l'];
            $data['lesson_name'] = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id =  '$lesson_id'")->row_array();
        }


        $kolomHarian = get_column_nilai('harian');
        $kolomSumatif = get_column_nilai('sumatif');

        $sumHarian = [];
        $caseHarian = [];
        $harians = [];
        foreach ($kolomHarian as $kh) {
            $sumHarian[] = "COALESCE(NULLIF($kh, 0), 0)";
            $caseHarian[] = "CASE WHEN $kh IS NOT NULL AND $kh != 0 THEN 1 ELSE 0 END";
            $harians[] = $kh;
        }

        $sumHarianAll = implode(" + ", $sumHarian);
        $countHarian = implode(" + ", $caseHarian);
        $getHarians = implode(", ", $harians);

        $sumSumatif = [];
        $caseSumatif = [];

        $sumatifs = [];
        foreach ($kolomSumatif as $ks) {
            $sumSumatif[] = "COALESCE(NULLIF($ks, 0), 0)";
            $caseSumatif[] = "CASE WHEN $ks IS NOT NULL AND $ks != 0 THEN 1 ELSE 0 END";
            $sumatifs[] = $ks;
        }

        $sumSumatifAll = implode(" + ", $sumSumatif);
        $countSumatif = implode(" + ", $caseSumatif);
        $getSumatifs = implode(", ", $sumatifs);

        if (isset($q['r']) && !empty($q['r']) && $q['r'] == 'presensi') {

            $data['pertemuan'] = $this->db->query("SELECT presensi_pelajaran_id FROM presensi_pelajaran
                                                JOIN schedule ON presensi_pelajaran_schedule_id = schedule_id
                                                JOIN teaching ON presensi_teaching_id = teaching_id
                                                WHERE teaching_employee_id = '$id_guru'
                                                AND presensi_pelajaran_class_id = '$class_id'
                                                AND schedule_lesson_id = $lesson_id
                                                AND presensi_pelajaran_semester_id = '$semester_id'
                                                GROUP BY teaching_date_create, presensi_pelajaran_schedule_id")->num_rows();

            $data['kehadiran'] = $this->db->query("SELECT presensi_pelajaran_status, presensi_pelajaran_student_id
                                                FROM presensi_pelajaran
                                                JOIN schedule ON presensi_pelajaran_schedule_id = schedule_id
                                                JOIN teaching ON presensi_teaching_id = teaching_id
                                                WHERE teaching_employee_id = '$id_guru'
                                                AND presensi_pelajaran_class_id = '$class_id'
                                                AND schedule_lesson_id = $lesson_id
                                                AND presensi_pelajaran_semester_id = '$semester_id'
                                                ORDER BY teaching_date_create ASC, presensi_pelajaran_schedule_id ASC")->result_array();
        } else if (isset($q['r']) && !empty($q['r']) && $q['r'] == 'harian') {
            $data['nilai_harian'] = $this->db->query("SELECT harian_student_id, $getHarians
                                                FROM harian
                                                WHERE harian_semester_id = '$semester_id' AND harian_teacher_id = '$id_guru'
                                                AND harian_lesson_id = '$lesson_id'")->result_array();
        } else if (isset($q['r']) && !empty($q['r']) && $q['r'] == 'sumatif') {
            $data['nilai_sumatif'] = $this->db->query("SELECT sumatif_student_id, $getSumatifs
                                                FROM sumatif
                                                WHERE sumatif_semester_id = '$semester_id' AND sumatif_teacher_id = '$id_guru'
                                                AND sumatif_lesson_id = '$lesson_id'")->result_array();
        } else if (isset($q['r']) && !empty($q['r']) && $q['r'] == 'akhir') {

            $data['bobot'] = $this->db->get_where('bobot', array('empl_id' => $id_guru))->row_array();

            // Bangun query lengkap
            $sqlHarian = "SELECT harian_student_id, ($sumHarianAll) / NULLIF(($countHarian), 0) AS rata_harian FROM harian";

            $data['rata_harian'] = $this->db->query("$sqlHarian 
                                                    WHERE harian_semester_id = '$semester_id' 
                                                    AND harian_teacher_id = '$id_guru'
                                                    AND harian_lesson_id = '$lesson_id'
                                                    GROUP BY harian_student_id")->result_array();

            // Bangun query lengkap
            $sqlSumatif = "SELECT sumatif_student_id, ($sumSumatifAll) / NULLIF(($countSumatif), 0) AS rata_sumatif FROM sumatif";

            $data['rata_sumatif'] = $this->db->query("$sqlSumatif
                                                    WHERE sumatif_semester_id = '$semester_id' 
                                                    AND sumatif_teacher_id = '$id_guru'
                                                    AND sumatif_lesson_id = '$lesson_id'
                                                    GROUP BY sumatif_student_id")->result_array();


            $data['kehadiran'] = $this->db->query("SELECT presensi_pelajaran_student_id, COUNT(presensi_pelajaran_student_id) AS presen 
                                                FROM presensi_pelajaran
                                                JOIN schedule ON presensi_pelajaran_schedule_id = schedule_id
                                                JOIN teaching ON presensi_teaching_id = teaching_id
                                                WHERE teaching_employee_id = '$id_guru'
                                                AND presensi_pelajaran_class_id = '$class_id'
                                                AND schedule_lesson_id = $lesson_id
                                                AND presensi_pelajaran_semester_id = '$semester_id'
                                                AND presensi_pelajaran_status = 'H'
                                                GROUP BY presensi_pelajaran_student_id")->result_array();
        }

        $data['period'] = $this->Period_model->get(array('order_by' => 'period_start'));

        $data['class']      = $this->db->query("SELECT DISTINCT class_id, class_name FROM schedule
                                                JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                WHERE lesson_teacher = '$id_guru'
                                                ")->result_array();

        $data['lesson']      = $this->db->query("SELECT DISTINCT lesson_id, lesson_name,lesson_code FROM schedule
                                                JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                WHERE lesson_teacher = '$id_guru'
                                                ")->result_array();


        $data['employees'] = $this->Employees_model->get();
        $data['semester'] = $this->Semester_model->get(array('period_id' => $period_id, 'order_by' => 'semester_name'));

        $data['student']     = $this->Student_model->get_jurnal($params);

        $data['class_id'] = $class_id;
        $data['lesson_id'] = $lesson_id;

        $data['title'] = 'Laporan';
        $data['main'] = 'report_jurnal/report_list';
        $this->load->view('manage/layout', $data);
    }



    function lesson_searching()
    {

        $guru_id    = $this->input->post('guru_id');
        $class_id   = $this->input->post('class_id');

        $lesson  = $this->db->query("SELECT DISTINCT lesson_id, lesson_name, lesson_code FROM schedule
        JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
        JOIN class ON schedule.schedule_class_id = class.class_id
        WHERE lesson_teacher = '$guru_id'
        AND schedule_class_id = '$class_id'")->result_array();

        echo '<div class="col-md-12 col-sm-12">  
				<div class="form-group">
				    <label>Mapel</label>
		            <select name="l" id="lesson_id" class="form-control" required="">
                    <option value="">- Pilih Mapel -</option>';
        foreach ($lesson as $row) {

            echo '<option value="' . $row['lesson_id'] . '">';

            echo $row['lesson_code'] . ' - ' . $row['lesson_name'];

            echo '</option>';
        }
        echo '</select>
				</div>
			</div>';
    }

    public function jurnal()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        $class_id = NULL;
        $teacher_id = NULL;
        $lesson_id = NULL;
        $semester_id = NULL;
        $period_id = NULL;

        if (isset($q['t']) && !empty($q['l']) && $q['t'] != '') {
            $teacher_id  = $q['t'];
            $data['employee_name'] = $this->db->query("SELECT employee_name FROM employee WHERE employee_id =  '$teacher_id'")->row_array();
        }

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $period_id  = $q['p'];
            $data['period_name'] = $this->db->query("SELECT CONCAT(period_start, '/', period_end) AS period_name FROM period WHERE period_id =  '$period_id'")->row_array();
        }

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $semester_id  = $q['s'];
            $data['semester_name'] = $this->db->query("SELECT semester_name FROM semester WHERE semester_id =  '$semester_id'")->row_array();
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id'] = $q['c'];
            $class_id  = $q['c'];
            $data['class_name'] = $this->db->query("SELECT class_name FROM class WHERE class_id = '$class_id'")->row_array();
        }

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id  = $q['l'];
            $data['lesson_name'] = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id =  '$lesson_id'")->row_array();
        }

        $data['pertemuan'] = $this->db->query("SELECT presensi_pelajaran_id FROM presensi_pelajaran
                                                JOIN schedule ON presensi_pelajaran_schedule_id = schedule_id
                                                JOIN teaching ON presensi_teaching_id = teaching_id
                                                WHERE teaching_employee_id = '$teacher_id'
                                                AND presensi_pelajaran_class_id = '$class_id'
                                                AND schedule_lesson_id = $lesson_id
                                                AND presensi_pelajaran_semester_id = '$semester_id'
                                                GROUP BY teaching_date_create, presensi_pelajaran_schedule_id")->num_rows();

        $data['kehadiran'] = $this->db->query("SELECT presensi_pelajaran_status, presensi_pelajaran_student_id FROM presensi_pelajaran
                                                JOIN schedule ON presensi_pelajaran_schedule_id = schedule_id
                                                JOIN teaching ON presensi_teaching_id = teaching_id
                                                WHERE teaching_employee_id = '$teacher_id'
                                                AND presensi_pelajaran_class_id = '$class_id'
                                                AND schedule_lesson_id = $lesson_id
                                                AND presensi_pelajaran_semester_id = '$semester_id'
                                                ORDER BY teaching_date_create ASC, presensi_pelajaran_schedule_id ASC")->result_array();

        $data['student']                = $this->Student_model->get_jurnal($params);

        $data['title']                  = 'Laporan Jurnal Santri';

        $this->load->view('report_jurnal/jurnal_report_xls', $data);
    }

    public function nilai_harian()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        $class_id = NULL;
        $teacher_id = NULL;
        $lesson_id = NULL;
        $semester_id = NULL;
        $period_id = NULL;

        if (isset($q['t']) && !empty($q['l']) && $q['t'] != '') {
            $teacher_id  = $q['t'];
            $data['employee_name'] = $this->db->query("SELECT employee_name FROM employee WHERE employee_id =  '$teacher_id'")->row_array();
        }

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $period_id  = $q['p'];
            $data['period_name'] = $this->db->query("SELECT CONCAT(period_start, '/', period_end) AS period_name FROM period WHERE period_id =  '$period_id'")->row_array();
        }

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $semester_id  = $q['s'];
            $data['semester_name'] = $this->db->query("SELECT semester_name FROM semester WHERE semester_id =  '$semester_id'")->row_array();
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id'] = $q['c'];
            $class_id  = $q['c'];
            $data['class_name'] = $this->db->query("SELECT class_name FROM class WHERE class_id = '$class_id'")->row_array();
        }

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id  = $q['l'];
            $data['lesson_name'] = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id =  '$lesson_id'")->row_array();
        }

        $kolomHarian = get_column_nilai('harian');

        $harians = [];
        foreach ($kolomHarian as $kh) {
            $harians[] = $kh;
        }

        $getHarians = implode(", ", $harians);


        $semester = $this->db->query("SELECT semester_id FROM semester WHERE semester_status = '1'")->row_array();
        $semester_id     = $semester['semester_id'];

        $data['nilai_harian'] = $this->db->query("SELECT harian_student_id, $getHarians
                                                FROM harian
                                                WHERE harian_semester_id = '$semester_id' AND harian_teacher_id = '$teacher_id'
                                                AND harian_lesson_id = '$lesson_id'")->result_array();

        $data['student']                = $this->Student_model->get_jurnal($params);

        $data['title']                  = 'Laporan Nilai Harian Santri';

        $data['columns']                = $harians;

        $this->load->view('report_jurnal/nilai_harian_xls', $data);
    }

    public function nilai_sumatif()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        $class_id = NULL;
        $teacher_id = NULL;
        $lesson_id = NULL;
        $semester_id = NULL;
        $period_id = NULL;

        if (isset($q['t']) && !empty($q['l']) && $q['t'] != '') {
            $teacher_id  = $q['t'];
            $data['employee_name'] = $this->db->query("SELECT employee_name FROM employee WHERE employee_id =  '$teacher_id'")->row_array();
        }

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $period_id  = $q['p'];
            $data['period_name'] = $this->db->query("SELECT CONCAT(period_start, '/', period_end) AS period_name FROM period WHERE period_id =  '$period_id'")->row_array();
        }

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $semester_id  = $q['s'];
            $data['semester_name'] = $this->db->query("SELECT semester_name FROM semester WHERE semester_id =  '$semester_id'")->row_array();
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id'] = $q['c'];
            $class_id  = $q['c'];
            $data['class_name'] = $this->db->query("SELECT class_name FROM class WHERE class_id = '$class_id'")->row_array();
        }

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id  = $q['l'];
            $data['lesson_name'] = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id =  '$lesson_id'")->row_array();
        }

        $semester = $this->db->query("SELECT semester_id FROM semester WHERE semester_status = '1'")->row_array();
        $semester_id     = $semester['semester_id'];

        $kolomSumatif = get_column_nilai('sumatif');

        $sumatifs = [];
        foreach ($kolomSumatif as $kh) {
            $sumatifs[] = $kh;
        }

        $getSumatifs = implode(", ", $sumatifs);

        $data['nilai_sumatif'] = $this->db->query("SELECT sumatif_student_id, $getSumatifs
                                                FROM sumatif
                                                WHERE sumatif_semester_id = '$semester_id' AND sumatif_teacher_id = '$teacher_id'
                                                AND sumatif_lesson_id = '$lesson_id'")->result_array();

        $data['student']                = $this->Student_model->get_jurnal($params);

        $data['title']                  = 'Laporan Nilai Sumatif Santri';

        $data['columns']                = $sumatifs;

        $this->load->view('report_jurnal/nilai_sumatif_xls', $data);
    }

    public function nilai_akhir()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        $class_id = NULL;
        $teacher_id = NULL;
        $lesson_id = NULL;
        $semester_id = NULL;
        $period_id = NULL;

        if (isset($q['t']) && !empty($q['l']) && $q['t'] != '') {
            $teacher_id  = $q['t'];
            $data['employee_name'] = $this->db->query("SELECT employee_name FROM employee WHERE employee_id =  '$teacher_id'")->row_array();
        }

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $period_id  = $q['p'];
            $data['period_name'] = $this->db->query("SELECT CONCAT(period_start, '/', period_end) AS period_name FROM period WHERE period_id =  '$period_id'")->row_array();
        }

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $semester_id  = $q['s'];
            $data['semester_name'] = $this->db->query("SELECT semester_name FROM semester WHERE semester_id =  '$semester_id'")->row_array();
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id'] = $q['c'];
            $class_id  = $q['c'];
            $data['class_name'] = $this->db->query("SELECT class_name FROM class WHERE class_id = '$class_id'")->row_array();
        }

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id  = $q['l'];
            $data['lesson_name'] = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id =  '$lesson_id'")->row_array();
        }


        $semester = $this->db->query("SELECT semester_id FROM semester WHERE semester_status = '1'")->row_array();
        $semester_id     = $semester['semester_id'];

        $data['bobot'] = $this->db->get_where('bobot', array('empl_id' => $teacher_id))->row_array();

        $kolomHarian = get_column_nilai('harian');
        $kolomSumatif = get_column_nilai('sumatif');

        $sumHarian = [];
        $caseHarian = [];
        $harians = [];
        foreach ($kolomHarian as $kh) {
            $sumHarian[] = "COALESCE(NULLIF($kh, 0), 0)";
            $caseHarian[] = "CASE WHEN $kh IS NOT NULL AND $kh != 0 THEN 1 ELSE 0 END";
            $harians[] = $kh;
        }

        $sumHarianAll = implode(" + ", $sumHarian);
        $countHarian = implode(" + ", $caseHarian);
        $getHarians = implode(", ", $harians);

        $sumSumatif = [];
        $caseSumatif = [];

        $sumatifs = [];
        foreach ($kolomSumatif as $ks) {
            $sumSumatif[] = "COALESCE(NULLIF($ks, 0), 0)";
            $caseSumatif[] = "CASE WHEN $ks IS NOT NULL AND $ks != 0 THEN 1 ELSE 0 END";
            $sumatifs[] = $ks;
        }

        $sumSumatifAll = implode(" + ", $sumSumatif);
        $countSumatif = implode(" + ", $caseSumatif);
        $getSumatifs = implode(", ", $sumatifs);

        // Bangun query lengkap
        $sqlHarian = "SELECT harian_student_id, ($sumHarianAll) / NULLIF(($countHarian), 0) AS rata_harian FROM harian";

        $data['rata_harian'] = $this->db->query("$sqlHarian 
                                                    WHERE harian_semester_id = '$semester_id' 
                                                    AND harian_teacher_id = '$teacher_id'
                                                    AND harian_lesson_id = '$lesson_id'
                                                    GROUP BY harian_student_id")->result_array();

        // Bangun query lengkap
        $sqlSumatif = "SELECT sumatif_student_id, ($sumSumatifAll) / NULLIF(($countSumatif), 0) AS rata_sumatif FROM sumatif";

        $data['rata_sumatif'] = $this->db->query("$sqlSumatif
                                                    WHERE sumatif_semester_id = '$semester_id' 
                                                    AND sumatif_teacher_id = '$teacher_id'
                                                    AND sumatif_lesson_id = '$lesson_id'
                                                    GROUP BY sumatif_student_id")->result_array();

        $data['kehadiran'] = $this->db->query("SELECT presensi_pelajaran_student_id, COUNT(presensi_pelajaran_student_id) AS presen FROM presensi_pelajaran
                                                JOIN schedule ON presensi_pelajaran_schedule_id = schedule_id
                                                JOIN teaching ON presensi_teaching_id = teaching_id
                                                WHERE teaching_employee_id = '$teacher_id'
                                                AND presensi_pelajaran_class_id = '$class_id'
                                                AND schedule_lesson_id = $lesson_id
                                                AND presensi_pelajaran_semester_id = '$semester_id'
                                                AND presensi_pelajaran_status = 'H'
                                                GROUP BY presensi_pelajaran_student_id")->result_array();

        $data['student']                = $this->Student_model->get_jurnal($params);

        $data['title']                  = 'Laporan Nilai Akhir Santri';

        $this->load->view('report_jurnal/nilai_akhir_report_xls', $data);
    }

    public function get_class()
    {
        $guru_id = $this->input->post('guru_id');
        $class      = $this->db->query("SELECT DISTINCT class_id, class_name FROM schedule
                                                JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                WHERE lesson_teacher = '$guru_id'
                                                ")->result_array();

        echo json_encode($class);
    }

    public function get_semester()
    {
        $period_id = $this->input->post('period_id', TRUE);
        $semester = $this->Semester_model->get(array('period_id' => $period_id, 'order_by' => 'semester_name'));
        if ($semester) {
            echo json_encode($semester);
        } else {
            echo json_encode(array());
        }
    }
}
