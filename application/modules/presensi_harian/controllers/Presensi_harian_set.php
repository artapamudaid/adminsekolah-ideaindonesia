<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Presensi_harian_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('presensi_harian/Presensi_harian_model', 'student/Student_model', 'period/Period_model', 'bulan/Bulan_model', 'setting/Setting_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    // pos view in list
    public function index($offset = NULL)
    {
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $param = array();
        $params = array();

        $idClass = 0;
        $tanggal = date('Y-m-d');

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $param['period_id']  = $q['p'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
            $param['month_id'] = $q['m'];
        }

        if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
            $param['date'] = $q['d'];
            $tanggal = $q['d'];
        }

        if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
            $params['majors_id'] = $q['k'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id']  = $q['c'];
            $param['class_id']  = $q['c'];
            $idClass = $q['c'];
        }

        if (isset($q['a']) && !empty($q['a']) && $q['a'] != '') {
            $area = $this->db->query(
                "SELECT id_area AS area_id, nama_area AS area_name, longi, lati
                    FROM area_absensi"
            )->row_array();
            $data['longitude'] = $area['longi'];
            $data['latitude'] = $area['lati'];
        }

        $data['check']      = 1;
        $data['area']       = $this->db->query(
            "SELECT id_area AS area_id, nama_area AS area_name
            FROM area_absensi"
        )->result_array();


        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);

        $data['student']    = array();

        if (!empty($param['class_id']) && !empty($param['date'])) {
            $idClass = $param['class_id'];
            $tanggal = $param['date'];
            $data['student']    = $this->Presensi_harian_model->cekAbsenByClass($idClass, $tanggal);
        }

        if (count($data['student']) > 0) {
            $data['check'] = 0;
        }

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status = '1'")->row_array();

        $date = date('Y-m-d');
        $this_month = pretty_date($date, 'F', false);

        $data['bulan'] = $this->db->query("SELECT * FROM month WHERE month_name = '$this_month'")->row_array();

        $data['title'] = 'Presensi Harian Siswa';
        $data['main'] = 'presensi_harian/presensi_harian_list';
        $this->load->view('manage/layout', $data);
    }

    // public function report($offset = NULL)
    // {

    //     $q = $this->input->get(NULL, TRUE);

    //     $data['q'] = $q;

    //     $param = array();
    //     $params = array();

    //     $namaBulan  = NULL;
    //     $bulan      = date('m');
    //     $period     = NULL;
    //     $tahun      = date('Y');


    //     if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
    //         $param['period_id']  = $q['p'];
    //     }

    //     if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
    //         $param['month_id'] = $q['m'];
    //     }

    //     if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
    //         $param['date'] = $q['d'];
    //     }

    //     if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
    //         $params['majors_id'] = $q['k'];
    //     }

    //     if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
    //         $params['class_id']  = $q['c'];
    //         $param['class_id']  = $q['c'];
    //     }

    //     if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {


    //         $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = " . $q['m'] . "")->row_array();

    //         if ($q['m'] == 1) {
    //             $bulan = '07';
    //         } else if ($q['m'] == 2) {
    //             $bulan = '08';
    //         } else if ($q['m'] == 3) {
    //             $bulan = '09';
    //         } else if ($q['m'] == 4) {
    //             $bulan = '10';
    //         } else if ($q['m'] == 5) {
    //             $bulan = '11';
    //         } else if ($q['m'] == 6) {
    //             $bulan = '12';
    //         } else if ($q['m'] == 7) {
    //             $bulan = '01';
    //         } else if ($q['m'] == 8) {
    //             $bulan = '02';
    //         } else if ($q['m'] == 9) {
    //             $bulan = '03';
    //         } else if ($q['m'] == 10) {
    //             $bulan = '04';
    //         } else if ($q['m'] == 11) {
    //             $bulan = '05';
    //         } else if ($q['m'] == 12) {
    //             $bulan = '06';
    //         }

    //         $period = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = " . $q['p'] . "")->row_array();

    //         if ($q['m'] < 7) {
    //             $tahun               = $period['period_start'];
    //             $data['namaTahun']   = $period['period_start'];
    //         } else {
    //             $tahun               = $period['period_end'];
    //             $data['namaTahun']   = $period['period_end'];
    //         }

    //         $data['namaBulan']      = $namaBulan['month_name'];
    //     }

    //     $data['check']      = $this->Presensi_harian_model->check($param);

    //     $data['class']      = $this->Student_model->get_class($params);
    //     $data['majors']     = $this->Student_model->get_majors($params);
    //     $data['student']    = $this->Student_model->get($params);

    //     $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    //     $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();

    //     $awalbulan = $tahun . '-' . $bulan . '-01';
    //     $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

    //     $begin = new DateTime($awalbulan);
    //     $end = new DateTime($akhirbulan);

    //     $interval = DateInterval::createFromDateString('1 day');
    //     $data['daterange']  = new DatePeriod($begin, $interval, $end);

    //     $data['interval']   = date_diff(date_create($awalbulan), date_create($akhirbulan));

    //     $data['month'] = $this->db->query("SELECT * FROM month")->result_array();

    //     $data['title'] = 'Laporan Presensi Harian Siswa';
    //     $data['main'] = 'presensi_harian/presensi_harian_report';
    //     $this->load->view('manage/layout', $data);
    // }

    public function report($offset = NULL)
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $param = array();
        $params = array();

        $namaBulan  = NULL;
        $bulan      = date('m');
        $period     = NULL;
        $tahun      = date('Y');
        $idKelas = 0;

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $param['period_id']  = $q['p'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
            $param['month_id'] = $q['m'];
        }

        if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
            $param['date'] = $q['d'];
        }

        if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
            $params['majors_id'] = $q['k'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id']  = $q['c'];
            $param['class_id']  = $q['c'];
            $idKelas = $q['c'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {


            $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = " . $q['m'] . "")->row_array();

            if ($q['m'] == 1) {
                $bulan = '07';
            } else if ($q['m'] == 2) {
                $bulan = '08';
            } else if ($q['m'] == 3) {
                $bulan = '09';
            } else if ($q['m'] == 4) {
                $bulan = '10';
            } else if ($q['m'] == 5) {
                $bulan = '11';
            } else if ($q['m'] == 6) {
                $bulan = '12';
            } else if ($q['m'] == 7) {
                $bulan = '01';
            } else if ($q['m'] == 8) {
                $bulan = '02';
            } else if ($q['m'] == 9) {
                $bulan = '03';
            } else if ($q['m'] == 10) {
                $bulan = '04';
            } else if ($q['m'] == 11) {
                $bulan = '05';
            } else if ($q['m'] == 12) {
                $bulan = '06';
            }

            $period = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = " . $q['p'] . "")->row_array();

            if ($q['m'] < 7) {
                $tahun               = $period['period_start'];
                $data['namaTahun']   = $period['period_start'];
            } else {
                $tahun               = $period['period_end'];
                $data['namaTahun']   = $period['period_end'];
            }

            $data['namaBulan']      = $namaBulan['month_name'];
        }

        $awalbulan = $tahun . '-' . $bulan . '-01';
        $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

        $begin = new DateTime($awalbulan);
        $end = new DateTime($akhirbulan);

        $interval = DateInterval::createFromDateString('1 day');
        $data['daterange']  = new DatePeriod($begin, $interval, $end);

        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);
        $data['student']    = $this->Student_model->get($params);
        $presensi = $this->db->query(
            "SELECT id_student,id_class,jenis_absen,date,time 
            FROM presensi_student 
            WHERE MONTH(date) = $bulan AND id_class = $idKelas
            AND jenis_absen != 'PULANG'
            ORDER BY id_student,date ASC"
        )->result_array();

        $dataAbsen = [];
        foreach ($presensi as $absen) {
            $idSiswa = $absen['id_student'];
            if (!isset($groupedData[$idSiswa])) {
                $groupedData[$idSiswa] = [];
            }
            $jenisAbsen = '';
            if ($absen['jenis_absen'] == 'DATANG') {
                $jenisAbsen = 'H';
            } else if ($absen['jenis_absen'] == 'IZIN') {
                $jenisAbsen = 'I';
            } else if ($absen['jenis_absen'] == 'SAKIT') {
                $jenisAbsen = 'S';
            } else if ($absen['jenis_absen'] == 'ALPHA') {
                $jenisAbsen = 'A';
            }

            $date = $absen['date'];
            $day = substr($date, -2);
            $dataAbsen[$idSiswa][$day] =  $jenisAbsen;
        }

        $data['data_absen'] = $dataAbsen;

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();

        $data['interval']   = date_diff(date_create($awalbulan), date_create($akhirbulan));

        $data['month'] = $this->db->query("SELECT * FROM month")->result_array();

        $data['title'] = 'Laporan Presensi Harian Siswa';
        $data['main'] = 'presensi_harian/presensi_harian_report';
        $this->load->view('manage/layout', $data);
    }

    function report_print()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $param = array();
        $params = array();

        $namaBulan  = NULL;
        $bulan      = date('m');
        $period     = NULL;
        $tahun      = date('Y');
        $idKelas = 0;

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $param['period_id']  = $q['p'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
            $param['month_id'] = $q['m'];
        }

        if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
            $param['date'] = $q['d'];
        }

        if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
            $params['majors_id'] = $q['k'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id']  = $q['c'];
            $param['class_id']  = $q['c'];
            $data['namaKelas'] = $this->db->query("SELECT class_name FROM class WHERE class_id = " . $q['c'] . "")->row_array();
            $idKelas =  $q['c'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {


            $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = " . $q['m'] . "")->row_array();

            if ($q['m'] == 1) {
                $bulan = '07';
            } else if ($q['m'] == 2) {
                $bulan = '08';
            } else if ($q['m'] == 3) {
                $bulan = '09';
            } else if ($q['m'] == 4) {
                $bulan = '10';
            } else if ($q['m'] == 5) {
                $bulan = '11';
            } else if ($q['m'] == 6) {
                $bulan = '12';
            } else if ($q['m'] == 7) {
                $bulan = '01';
            } else if ($q['m'] == 8) {
                $bulan = '02';
            } else if ($q['m'] == 9) {
                $bulan = '03';
            } else if ($q['m'] == 10) {
                $bulan = '04';
            } else if ($q['m'] == 11) {
                $bulan = '05';
            } else if ($q['m'] == 12) {
                $bulan = '06';
            }

            $period = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = " . $q['p'] . "")->row_array();

            if ($q['m'] < 7) {
                $tahun               = $period['period_start'];
                $data['namaTahun']   = $period['period_start'];
            } else {
                $tahun               = $period['period_end'];
                $data['namaTahun']   = $period['period_end'];
            }

            $data['namaBulan']      = $namaBulan['month_name'];
        }

        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);
        $data['student']    = $this->Student_model->get($params);

        $presensi = $this->db->query(
            "SELECT id_student,id_class,jenis_absen,date,time 
            FROM presensi_student 
            WHERE MONTH(date) = $bulan AND id_class = $idKelas
            AND jenis_absen != 'PULANG'
            ORDER BY id_student,date ASC"
        )->result_array();

        $dataAbsen = [];
        foreach ($presensi as $absen) {
            $idSiswa = $absen['id_student'];
            if (!isset($groupedData[$idSiswa])) {
                $groupedData[$idSiswa] = [];
            }
            $jenisAbsen = '';
            if ($absen['jenis_absen'] == 'DATANG') {
                $jenisAbsen = 'H';
            } else if ($absen['jenis_absen'] == 'IZIN') {
                $jenisAbsen = 'I';
            } else if ($absen['jenis_absen'] == 'SAKIT') {
                $jenisAbsen = 'S';
            } else if ($absen['jenis_absen'] == 'ALPHA') {
                $jenisAbsen = 'A';
            }

            $date = $absen['date'];
            $day = substr($date, -2);
            $dataAbsen[$idSiswa][$day] =  $jenisAbsen;
        }

        $data['data_absen'] = $dataAbsen;

        // echo json_encode($dataAbsen);
        // exit;

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();

        $awalbulan = $tahun . '-' . $bulan . '-01';
        $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

        $begin = new DateTime($awalbulan);
        $end = new DateTime($akhirbulan);

        $interval = DateInterval::createFromDateString('1 day');
        $data['daterange']  = new DatePeriod($begin, $interval, $end);

        $data['interval']   = date_diff(date_create($awalbulan), date_create($akhirbulan));

        $data['month'] = $this->db->query("SELECT * FROM month")->result_array();

        $data['title'] = 'Laporan Presensi Harian Siswa';

        $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('presensi_harian/presensi_harian_report_print', $data);
    }

    function report_print_time()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $param = array();
        $params = array();

        $namaBulan  = NULL;
        $bulan      = date('m');
        $period     = NULL;
        $tahun      = date('Y');
        $idKelas    =  0;

        if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
            $param['period_id']  = $q['p'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
            $param['month_id'] = $q['m'];
        }

        if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
            $param['date'] = $q['d'];
        }

        if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
            $params['majors_id'] = $q['k'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id']  = $q['c'];
            $param['class_id']  = $q['c'];
            $data['namaKelas'] = $this->db->query("SELECT class_name FROM class WHERE class_id = " . $q['c'] . "")->row_array();
            $idKelas =  $q['c'];
        }

        if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {


            $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = " . $q['m'] . "")->row_array();

            if ($q['m'] == 1) {
                $bulan = '07';
            } else if ($q['m'] == 2) {
                $bulan = '08';
            } else if ($q['m'] == 3) {
                $bulan = '09';
            } else if ($q['m'] == 4) {
                $bulan = '10';
            } else if ($q['m'] == 5) {
                $bulan = '11';
            } else if ($q['m'] == 6) {
                $bulan = '12';
            } else if ($q['m'] == 7) {
                $bulan = '01';
            } else if ($q['m'] == 8) {
                $bulan = '02';
            } else if ($q['m'] == 9) {
                $bulan = '03';
            } else if ($q['m'] == 10) {
                $bulan = '04';
            } else if ($q['m'] == 11) {
                $bulan = '05';
            } else if ($q['m'] == 12) {
                $bulan = '06';
            }

            $period = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = " . $q['p'] . "")->row_array();

            if ($q['m'] < 7) {
                $tahun               = $period['period_start'];
                $data['namaTahun']   = $period['period_start'];
            } else {
                $tahun               = $period['period_end'];
                $data['namaTahun']   = $period['period_end'];
            }

            $data['namaBulan']      = $namaBulan['month_name'];
        }

        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);
        $data['student']    = $this->Student_model->get($params);

        $presensi = $this->db->query(
            "SELECT id_student,id_class,jenis_absen,date,time 
            FROM presensi_student 
            WHERE MONTH(date) = $bulan AND id_class = $idKelas
                AND jenis_absen != 'PULANG'
            ORDER BY id_student,date ASC"
        )->result_array();

        $dataAbsen = [];
        foreach ($presensi as $absen) {
            $idSiswa = $absen['id_student'];
            if (!isset($groupedData[$idSiswa])) {
                $groupedData[$idSiswa] = [];
            }
            $jenisAbsen = '';
            if ($absen['jenis_absen'] == 'DATANG') {
                $jenisAbsen = 'H';
            } else if ($absen['jenis_absen'] == 'IZIN') {
                $jenisAbsen = 'I';
            } else if ($absen['jenis_absen'] == 'SAKIT') {
                $jenisAbsen = 'S';
            } else if ($absen['jenis_absen'] == 'ALPHA') {
                $jenisAbsen = 'A';
            }

            $date = $absen['date'];
            $day = substr($date, -2);
            $dataAbsen[$idSiswa][$day]   =  $jenisAbsen;
            $waktu = 'time' . $day;
            $time = $absen['time'];
            $hour_minute = substr($time, 0, 5);
            $dataAbsen[$idSiswa][$waktu] =  $hour_minute;
        }

        $data['data_absen'] = $dataAbsen;

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();

        $awalbulan = $tahun . '-' . $bulan . '-01';
        $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

        $begin = new DateTime($awalbulan);
        $end = new DateTime($akhirbulan);

        $interval = DateInterval::createFromDateString('1 day');
        $data['daterange']  = new DatePeriod($begin, $interval, $end);

        $data['interval']   = date_diff(date_create($awalbulan), date_create($akhirbulan));

        $data['month'] = $this->db->query("SELECT * FROM month")->result_array();

        $data['title'] = 'Laporan Presensi Harian Siswa';

        $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('presensi_harian/presensi_harian_report_time', $data);
    }

    // public function add_glob()
    // {
    //     if ($_POST == TRUE) {

    //         $periodID     = $_POST['period_id'];
    //         $monthID      = $_POST['month_id'];
    //         $presensiDate = $_POST['presensi_date'];
    //         $majorsID     = $_POST['majors_id'];
    //         $classID      = $_POST['class_id'];
    //         $studentID    = $_POST['student_id'];

    //         $cpt = count($studentID);

    //         for ($i = 0; $i < $cpt; $i++) {

    //             $student_id         = $studentID[$i];
    //             $status_kehadiran   = 'presensi_harian_status' . $student_id;

    //             $status             = $_POST[$status_kehadiran];

    //             $params['presensi_harian_period_id']    = $periodID;
    //             $params['presensi_harian_month_id']     = $monthID;
    //             $params['presensi_harian_date']         = $presensiDate;
    //             $params['presensi_harian_class_id']     = $classID;
    //             $params['presensi_harian_student_id']   = $student_id;
    //             $params['presensi_harian_status']       = $status[0];

    //             if (!empty($status[0])) {
    //                 $this->Presensi_harian_model->add($params);
    //             }
    //         }
    //     }

    //     $this->session->set_flashdata('success', ' Input Presensi Berhasil');
    //     redirect('manage/presensi_harian?p=' . $periodID . '&m=' . $monthID . '&d=' . $presensiDate . '&k=' . $majorsID . '&c=' . $classID);
    // }

    public function add_glob()
    {
        if ($_POST == TRUE) {

            $studentID    = $_POST['student_id'];
            $classID      = $_POST['class_id'];
            $areaID       = $_POST['area_id'];
            $longi        = $_POST['longitude'];
            $lati         = $_POST['latitude'];

            $presensiDate = $_POST['presensi_date'];
            $majorsID     = $_POST['majors_id'];

            $cpt = count($studentID);

            for ($i = 0; $i < $cpt; $i++) {

                $student_id         = $studentID[$i];
                $status_kehadiran   = 'presensi_harian_status' . $student_id;

                $status             = $_POST[$status_kehadiran][0];
                if (!empty($status)) {
                    $jenisAbsen = '';
                    if ($status == 'H') {
                        $jenisAbsen = 'DATANG';
                    } else if ($status == 'I') {
                        $jenisAbsen = 'IZIN';
                    } else if ($status == 'S') {
                        $jenisAbsen = 'SAKIT';
                    } else if ($status == 'A') {
                        $jenisAbsen = 'ALPHA';
                    }

                    $params['presensi_student_id']   = $student_id;
                    $params['presensi_class_id']     = $classID;
                    $params['presensi_area']         = $areaID;
                    $params['presensi_jenis']        = $jenisAbsen;
                    $params['presensi_status']       = 'TEPAT WAKTU';
                    $params['presensi_date']         = date('Y-m-d');
                    $params['presensi_time']         = date('H:i:s');
                    $params['presensi_longi']        = $longi;
                    $params['presensi_lati']         = $lati;
                    $params['presensi_note']         = 'Absesnsi Siswa oleh admin';

                    // echo json_encode($params);
                    // exit;

                    $this->Presensi_harian_model->addAbsensi($params);

                    $this->load->helper('notiftele');
                
                    $student_info = $this->db->query("SELECT student_full_name, student_nis, class.class_name, majors.majors_short_name as unit 
                                                    FROM student 
                                                    LEFT JOIN class ON class.class_id = student.class_class_id 
                                                    LEFT JOIN majors ON majors.majors_id = student.majors_majors_id 
                                                    WHERE student_id = '$student_id'")->row_array();
                                                    
                    if ($student_info) {
                        $status_str = 'Hadir';
                        if ($status[0] == 'S') $status_str = 'Sakit';
                        elseif ($status[0] == 'I') $status_str = 'Izin';
                        elseif ($status[0] == 'A') $status_str = 'Alpha';
                        
                        $payload_tele = [
                            'topik'  => 'presensi_siswa',
                            'pesan'  => $student_info['student_full_name'] . ' (' . $student_info['class_name'] . ') ' . $status_str . ' pada ' . date('H:i:s'),
                            'detail' => [
                                'tanggal' => date('Y-m-d', strtotime($presensiDate)),
                                'nama'    => $student_info['student_full_name'],
                                'nis'     => $student_info['student_nis'] ?? '',
                                'kelas'   => $student_info['class_name'] ?? '',
                                'unit'    => $student_info['unit'] ?? ''
                            ]
                        ];
                        create_log($payload_tele);
                    }
                }
            }
        }

        $this->session->set_flashdata('success', ' Input Presensi Berhasil');
        redirect('manage/presensi_harian?p=' . $periodID . '&m=' . $monthID . '&d=' . $presensiDate . '&k=' . $majorsID . '&c=' . $classID . '&a=' . $areaID);
    }

    function class_searching()
    {
        $id_majors = $this->input->post('id_majors');
        $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

        echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" required="">
                    <option value="">-- Pilih Kelas --</option>';
        foreach ($dataKelas as $row) {

            echo '<option value="' . $row['class_id'] . '">';

            echo $row['class_name'];

            echo '</option>';
        }
        echo '</select>
				</div>
			</div>';
    }
}
