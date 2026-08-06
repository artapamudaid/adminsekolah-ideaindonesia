<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Presensi_pelajaran_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array('presensi_pelajaran/Presensi_pelajaran_model', 'student/Student_model', 'lesson/Lesson_model', 'period/Period_model', 'bulan/Bulan_model', 'setting/Setting_model', 'logs/Logs_model', 'semester/Semester_model', 'bulan/Bulan_model'));
		$this->load->library('upload');
	}

	// pos view in list
	public function index($offset = NULL)
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();
		$class_id = NULL;


		$day        = pretty_date(date('Y-m-d'), 'l', false);

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
			$class_id  = $q['c'];
		}

		// if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
		// 	$params['lesson_id']  = $q['l'];
		// 	$param['lesson_id']  = $q['l'];

		// 	$data['student']    = $this->Student_model->get($params);
		// }

		if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
			$params['schedule_id']  = $q['l'];
			$param['schedule_id']  = $q['l'];
			$idJadwal = $q['l'];
			$jadwal = $this->db->query("SELECT schedule_lesson_id FROM schedule WHERE schedule_id = '$idJadwal'")->row_array();
			$data['id_mapel'] 	= $jadwal['schedule_lesson_id'];
			$data['student']    = $this->Student_model->get($params);
			$data['check']      = $this->Presensi_pelajaran_model->check($param);
		}


		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);

		$this->db->select("s.semester_id")
			->where([
				"s.semester_status" => 1
			]);
		$semesterAktif = $this->db->get("semester s")->row_array();
		$idSemesterAktif   = $semesterAktif['semester_id'];

		$data['lesson']     = $this->db->query(
			"SELECT schedule_id, lesson_id, lesson_code, lesson_name, jam_pelajaran_start, jam_pelajaran_end
				FROM schedule 
				JOIN lesson ON lesson_id = schedule_lesson_id 
				JOIN day ON day.day_id = schedule.schedule_day 
				JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
				WHERE schedule_class_id = '$class_id' AND day_name = '$day' AND schedule_semester_id = '$idSemesterAktif'
				ORDER BY jam_pelajaran_start ASC"
		)->result_array();

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status = '1'")->row_array();

		$date = date('Y-m-d');
		$this_month = pretty_date($date, 'F', false);

		$data['bulan'] = $this->db->query("SELECT * FROM month WHERE month_name = '$this_month'")->row_array();

		$data['title'] = 'Presensi Pelajaran Siswa';
		$data['main'] = 'presensi_pelajaran/presensi_pelajaran_list';
		$this->load->view('manage/layout', $data);
	}

	public function report($offset = NULL)
	{

		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();
		$class_id = NULL;


		$day        = pretty_date(date('Y-m-d'), 'l', false);

		$namaBulan  = NULL;
		$bulan      = date('m');
		$period     = NULL;
		$tahun      = date('Y');


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
			$class_id  = $q['c'];

			$this->db->select("s.semester_id")
				->where([
					"s.semester_status" => 1
				]);
			$semesterAktif = $this->db->get("semester s")->row_array();
			$idSemesterAktif   = $semesterAktif['semester_id'];

			$listPelajaran  = $this->db->query(
				"SELECT lesson_id, lesson_code, lesson_name
				FROM schedule 
				JOIN lesson ON lesson_id = schedule_lesson_id
				WHERE schedule_class_id = '$class_id' AND schedule_semester_id = '$idSemesterAktif'
				GROUP BY lesson_id
				ORDER BY lesson_name ASC"
			)->result_array();

			$data['lesson']     = $listPelajaran;
		}

		if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
			$params['lesson_id']  = $q['l'];
			$param['lesson_id']  = $q['l'];
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

		// $data['check']      = $this->Presensi_pelajaran_model->check($param);

		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['student']    = $this->Student_model->get($params);
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");


		// --- buat rentang tanggal
		$awalbulan = $tahun . '-' . $bulan . '-01';
		$akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

		$begin = new DateTime($awalbulan);
		$end = new DateTime($akhirbulan);
		$interval = DateInterval::createFromDateString('1 day');
		$daterange = new DatePeriod($begin, $interval, $end);

		$data['daterange'] = $daterange;
		$data['interval'] = date_diff(date_create($awalbulan), date_create($akhirbulan));
		$data['month'] = $this->db->query("SELECT * FROM month")->result_array();

		if (!empty($data['student'])) {
			$student_ids = array_column($data['student'], 'student_id');
			$period_id = $q['p'];
			$month_id = $q['m'];
			$lesson_id = $q['l'];
			// $id_mapel = $data['id_mapel'] ?? null;

			// ambil semua presensi dalam 1x query
			$this->db->select('presensi_pelajaran_date, presensi_pelajaran_student_id, presensi_pelajaran_status');
			$this->db->from('presensi_pelajaran');
			$this->db->join(
				'teaching',
				'teaching.teaching_id = presensi_pelajaran.presensi_teaching_id'
			);

			$this->db->where('presensi_pelajaran_period_id', $period_id);
			$this->db->where('presensi_pelajaran_month_id', $month_id);
			$this->db->where('presensi_pelajaran_class_id', $class_id);
			$this->db->where_in('presensi_pelajaran_student_id', $student_ids);
			$this->db->where("presensi_pelajaran_date BETWEEN '$awalbulan' AND '$akhirbulan'");

			$this->db->where(
				"teaching.teaching_id = (
					SELECT MIN(t2.teaching_id)
					FROM teaching t2
					WHERE t2.teaching_lesson_id = '$lesson_id'
					 AND t2.teaching_class_id = teaching.teaching_class_id
        			 AND DATE(t2.teaching_date_create) = DATE(teaching.teaching_date_create)
				)",
				null,
				false
			);

			$presensiList = $this->db->get()->result_array();

			// buat map [student_id][date] => status
			$presensiMap = [];
			foreach ($presensiList as $p) {
				$presensiMap[$p['presensi_pelajaran_student_id']][$p['presensi_pelajaran_date']] = $p['presensi_pelajaran_status'];
			}

			$data['presensiMap'] = $presensiMap;
		} else {
			$data['presensiMap'] = [];
		}


		$data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();


		$data['title'] = 'Laporan Presensi Pelajaran Siswa';
		$data['main'] = 'presensi_pelajaran/presensi_pelajaran_report';
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
		$class_id = NULL;


		$day        = pretty_date(date('Y-m-d'), 'l', false);


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
			$class_id  = $q['c'];
			$data['namaKelas'] = $this->db->query("SELECT class_name FROM class WHERE class_id = " . $q['c'] . "")->row_array();

			$this->db->select("s.semester_id")
				->where([
					"s.semester_status" => 1
				]);
			$semesterAktif = $this->db->get("semester s")->row_array();
			$idSemesterAktif   = $semesterAktif['semester_id'];

			$listPelajaran  = $this->db->query(
				"SELECT lesson_id, lesson_code, lesson_name
				FROM schedule 
				JOIN lesson ON lesson_id = schedule_lesson_id
				WHERE schedule_class_id = '$class_id' AND schedule_semester_id = '$idSemesterAktif'
				GROUP BY lesson_id
				ORDER BY lesson_name ASC"
			)->result_array();

			$data['lesson']     = $listPelajaran;
		}

		if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
			$params['lesson_id']  = $q['l'];
			$param['lesson_id']  = $q['l'];
			$data['namaPelajaran'] = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id = " . $q['l'] . "")->row_array();
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

		$data['check']      = $this->Presensi_pelajaran_model->check($param);

		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['student']    = $this->Student_model->get($params);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();

		// --- buat rentang tanggal
		$awalbulan = $tahun . '-' . $bulan . '-01';
		$akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));

		$begin = new DateTime($awalbulan);
		$end = new DateTime($akhirbulan);
		$interval = DateInterval::createFromDateString('1 day');
		$daterange = new DatePeriod($begin, $interval, $end);

		$data['daterange'] = $daterange;
		$data['interval'] = date_diff(date_create($awalbulan), date_create($akhirbulan));
		$data['month'] = $this->db->query("SELECT * FROM month")->result_array();

		if (!empty($data['student'])) {
			$student_ids = array_column($data['student'], 'student_id');
			$period_id = $q['p'];
			$month_id = $q['m'];
			$lesson_id = $q['l'];
			// $id_mapel = $data['id_mapel'] ?? null;

			// ambil semua presensi dalam 1x query
			$this->db->select('presensi_pelajaran_date, presensi_pelajaran_student_id, presensi_pelajaran_status');
			$this->db->from('presensi_pelajaran');
			$this->db->join(
				'teaching',
				'teaching.teaching_id = presensi_pelajaran.presensi_teaching_id'
			);

			$this->db->where('presensi_pelajaran_period_id', $period_id);
			$this->db->where('presensi_pelajaran_month_id', $month_id);
			$this->db->where('presensi_pelajaran_class_id', $class_id);
			$this->db->where_in('presensi_pelajaran_student_id', $student_ids);
			$this->db->where("presensi_pelajaran_date BETWEEN '$awalbulan' AND '$akhirbulan'");

			$this->db->where(
				"teaching.teaching_id = (
					SELECT MIN(t2.teaching_id)
					FROM teaching t2
					WHERE t2.teaching_lesson_id = '$lesson_id'
					 AND t2.teaching_class_id = teaching.teaching_class_id
        			 AND DATE(t2.teaching_date_create) = DATE(teaching.teaching_date_create)
				)",
				null,
				false
			);

			$presensiList = $this->db->get()->result_array();

			// buat map [student_id][date] => status
			$presensiMap = [];
			foreach ($presensiList as $p) {
				$presensiMap[$p['presensi_pelajaran_student_id']][$p['presensi_pelajaran_date']] = $p['presensi_pelajaran_status'];
			}

			$data['presensiMap'] = $presensiMap;
		} else {
			$data['presensiMap'] = [];
		}

		$data['title'] = 'Laporan Presensi Pelajaran Siswa';

		$data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
		$data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$this->load->view('presensi_pelajaran/presensi_pelajaran_report_print', $data);
	}

	public function add_glob()
	{
		if ($_POST == TRUE) {

			$periodID     = $_POST['period_id'];
			$monthID      = $_POST['month_id'];
			$presensiDate = $_POST['presensi_date'];
			$majorsID     = $_POST['majors_id'];
			$classID      = $_POST['class_id'];
			$lessonID     = $_POST['lesson_id'];
			$scheduleID   = $_POST['schedule_id'];
			$studentID    = $_POST['student_id'];
			$materi    	  = $_POST['materi'];
			$employee_id  = $_POST['employee_id'];
			$semester	  = $this->db->query("SELECT semester_id FROM semester WHERE semester_status = '1'")->row_array();
			$semesterID	  = $semester['semester_id'];

			$dateM = date('m');

			if ($dateM == '01') {
				$month_id = '7';
			} else if ($dateM == '02') {
				$month_id = '8';
			} else if ($dateM == '03') {
				$month_id = '9';
			} else if ($dateM == '04') {
				$month_id = '10';
			} else if ($dateM == '05') {
				$month_id = '11';
			} else if ($dateM == '06') {
				$month_id = '12';
			} else if ($dateM == '07') {
				$month_id = '1';
			} else if ($dateM == '08') {
				$month_id = '2';
			} else if ($dateM == '09') {
				$month_id = '3';
			} else if ($dateM == '10') {
				$month_id = '4';
			} else if ($dateM == '11') {
				$month_id = '5';
			} else if ($dateM == '12') {
				$month_id = '6';
			}

			$param['teaching_employee_id'] = $employee_id;
			$param['teaching_class_id'] =  $classID;
			$param['teaching_materi'] =  $materi;
			$param['teaching_lesson_id'] = $lessonID;
			$param['teaching_schedule_id'] = $scheduleID;
			$param['teaching_month_id'] = $month_id;
			$param['teaching_semester_id'] = $semesterID;

			$this->Presensi_pelajaran_model->add_teaching($param);

			//Mengambil ID Teaching utk dimasukkan ke presensi pelajaran
			$teaching	  = $this->db->query("SELECT teaching_id FROM teaching WHERE teaching_semester_id = '$semesterID' AND teaching_lesson_id = '$lessonID' AND teaching_class_id='$classID' AND DATE(teaching_date_create)='$presensiDate' AND teaching_schedule_id = '$scheduleID'")->row_array();
			$teachingID	  = $teaching['teaching_id'];
			$cpt = count($studentID);

			for ($i = 0; $i < $cpt; $i++) {

				$student_id         = $studentID[$i];
				$status_kehadiran   = 'presensi_pelajaran_status' . $student_id;

				$status             = $_POST[$status_kehadiran];

				$params['presensi_pelajaran_period_id']    = $periodID;
				$params['presensi_pelajaran_month_id']     = $monthID;
				$params['presensi_teaching_id']     	   = $teachingID;
				$params['presensi_pelajaran_date']         = $presensiDate;
				$params['presensi_pelajaran_class_id']     = $classID;
				$params['presensi_pelajaran_lesson_id']    = $lessonID;
				$params['presensi_pelajaran_student_id']   = $student_id;
				$params['presensi_pelajaran_status']       = $status[0];

				$this->Presensi_pelajaran_model->add($params);
			}
		}

		$this->session->set_flashdata('success', ' Input Presensi Berhasil');
		redirect('manage/presensi_pelajaran?p=' . $periodID . '&m=' . $monthID . '&d=' . $presensiDate . '&k=' . $majorsID . '&c=' . $classID . '&l=' . $scheduleID);
	}

	function class_searching()
	{
		$id_majors = $this->input->post('id_majors');
		$dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

		echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" required="" onchange="get_pelajaran()">
                    <option value="">-- Pilih Kelas --</option>
					<option value="0">Semua Kelas</option>';
		foreach ($dataKelas as $row) {

			echo '<option value="' . $row['class_id'] . '">';

			echo $row['class_name'];

			echo '</option>';
		}
		echo '</select>
				</div>
			</div>';
	}

	function lesson_searching()
	{
		$class_id   = $this->input->post('class_id');
		$day        = pretty_date(date('Y-m-d'), 'l', false);
		$dataKelas  = $this->db->query("SELECT lesson_id, lesson_code, lesson_name FROM schedule JOIN lesson ON lesson_id = schedule_lesson_id JOIN day ON day.day_id = schedule.schedule_day WHERE schedule_class_id = '$class_id' AND day_name = '$day' ORDER BY schedule_time ASC")->result_array();

		echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Mata Pelajaran</label>
		            <select name="l" id="lesson_id" class="form-control" required="">
                    <option value="">-- Pilih Mata Pelajaran --</option>';
		foreach ($dataKelas as $row) {

			echo '<option value="' . $row['lesson_id'] . '">';

			echo $row['lesson_code'] . ' - ' . $row['lesson_name'];

			echo '</option>';
		}
		echo '</select>
				</div>
			</div>';
	}

	function lesson_input_jurnal()
	{
		$class_id   = $this->input->post('class_id');
		$day        = pretty_date(date('Y-m-d'), 'l', false);

		$this->db->select("s.semester_id")
			->where([
				"s.semester_status" => 1
			]);
		$semesterAktif = $this->db->get("semester s")->row_array();
		$idSemesterAktif   = $semesterAktif['semester_id'];

		$listJamPelajaran  = $this->db->query(
			"SELECT schedule_id, lesson_id, lesson_code, lesson_name, jam_pelajaran_start, jam_pelajaran_end
				FROM schedule 
				JOIN lesson ON lesson_id = schedule_lesson_id 
				JOIN day ON day.day_id = schedule.schedule_day
				JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
				WHERE schedule_class_id = '$class_id' AND day_name = '$day' AND schedule_semester_id = '$idSemesterAktif'
				ORDER BY jam_pelajaran_start ASC"
		)->result_array();

		echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Mata Pelajaran</label>
		            <select name="l" id="lesson_id" class="form-control" required="">
                    <option value="">-- Pilih Mata Pelajaran --</option>';
		foreach ($listJamPelajaran as $row) {

			echo '<option value="' . $row['schedule_id'] . '">';

			echo $row['lesson_code'] . ' - ' . $row['lesson_name'] . ' (' . $row['jam_pelajaran_start'] . '-' . $row['jam_pelajaran_end'] . ')';

			echo '</option>';
		}
		echo '</select>
				</div>
			</div>';
	}

	function lesson_report_jurnal()
	{
		$class_id   = $this->input->post('class_id');

		$this->db->select("s.semester_id")
			->where([
				"s.semester_status" => 1
			]);
		$semesterAktif = $this->db->get("semester s")->row_array();
		$idSemesterAktif   = $semesterAktif['semester_id'];

		$listPelajaran  = $this->db->query(
			"SELECT lesson_id, lesson_code, lesson_name
				FROM schedule 
				JOIN lesson ON lesson_id = schedule_lesson_id
				WHERE schedule_class_id = '$class_id' AND schedule_semester_id = '$idSemesterAktif'
				GROUP BY lesson_id
				ORDER BY lesson_name ASC"
		)->result_array();

		echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Mata Pelajaran</label>
		            <select name="l" id="lesson_id" class="form-control" required="">
                    <option value="">-- Pilih Mata Pelajaran --</option>';
		foreach ($listPelajaran as $row) {

			echo '<option value="' . $row['lesson_id'] . '">';

			echo $row['lesson_code'] . ' - ' . $row['lesson_name'];

			echo '</option>';
		}
		echo '</select>
				</div>
			</div>';
	}

	public function report_mengajar($offset = NULL)
	{

		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '' && $q['s'] != '0') {
			$params['semester_id']  = $q['s'];
			$semester_id	= $q['s'];
		}

		// if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
		// 	$params['month_id']  = $q['m'];
		// 	$month_id	= $q['m'];
		// }	

		if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '' && $q['ds'] != '0') {
			$params['date_start']  = $q['ds'];
		}

		if (isset($q['de']) && !empty($q['de']) && $q['de'] != '' && $q['de'] != '0') {
			$params['date_end']  = $q['de'];
		}

		if (isset($q['e']) && !empty($q['e']) && $q['e'] != '' && $q['e'] != '0') {
			$params['employee_id']  = $q['e'];
			$employee_id	= $q['e'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
			$majors_id			 = $q['k'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id'] = $q['c'];
			$param['class_id']  = $q['c'];
			$class_id  			= $q['c'];
		}

		$data['check']      = $this->Presensi_pelajaran_model->check($param);
		$data['presensi']	= $this->Presensi_pelajaran_model->get_jurnal_mengajar($params);
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['student']    = $this->Student_model->get($params);
		$data['month']    	= $this->Bulan_model->get_month($params);
		$data['semester']  	= $this->Semester_model->get($params);
		$data['lesson']     = $this->db->query("SELECT lesson_id, lesson_code, lesson_name, employee_name, employee_id FROM lesson JOIN employee ON lesson.lesson_teacher=employee.employee_id GROUP BY employee_id ORDER BY lesson_id ASC")->result_array();

		$data['teaching']	= $this->Presensi_pelajaran_model->get_teaching($params);
		// echo json_encode($data['teaching']);
		// echo json_encode($params);
		// exit;
		// $data['schedule']	= $this->Presensi_pelajaran_model->get_jurnal_schedule($params);
		$data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();


		$data['title'] = 'Jurnal Mengajar';
		$data['main'] = 'presensi_pelajaran/report_mengajar';
		$this->load->view('manage/layout', $data);
	}

	function report_jurnal()
	{
		// ob_start();

		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();

		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '' && $q['s'] != '0') {
			$params['semester_id']  = $q['s'];
			$semester_id	= $q['s'];
		}

		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
			$params['month_id']  = $q['m'];
			$month_id	= $q['m'];
		}

		if (isset($q['e']) && !empty($q['e']) && $q['e'] != '' && $q['e'] != '0') {
			$params['employee_id']  = $q['e'];
			$employee_id	= $q['e'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
			$majors_id			 = $q['k'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id'] = $q['c'];
			$param['class_id']  = $q['c'];
			$class_id  			= $q['c'];
		}

		if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '' && $q['ds'] != '0') {
			$params['date_start']  = $q['ds'];
		}

		if (isset($q['de']) && !empty($q['de']) && $q['de'] != '' && $q['de'] != '0') {
			$params['date_end']  = $q['de'];
		}

		$data['check']      = $this->Presensi_pelajaran_model->check($param);
		$data['presensi']	= $this->Presensi_pelajaran_model->get_jurnal_mengajar($params);
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['student']    = $this->Student_model->get($params);
		$data['month']    	= $this->Bulan_model->get_month($params);
		$data['semester']  	= $this->Semester_model->get($params);
		$data['lesson']     = $this->db->query("SELECT lesson_id, lesson_code, lesson_name, employee_name FROM lesson JOIN employee ON lesson.lesson_teacher=employee.employee_id ORDER BY lesson_id ASC")->result_array();

		$data['teaching']	= $this->Presensi_pelajaran_model->get_teaching($params);
		$data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();

		$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$filename = 'Rekap Jurnal Mengajar ' . pretty_date($q['ds'], 'd-m-Y', FALSE) . ' Sampai ' . pretty_date($q['de'], 'd-m-Y', FALSE) . '.pdf';

		$this->load->library('dompdflib');

		$paper = 'A4';
		$orientation = "potrait";

		$html = $this->load->view('presensi_pelajaran/mengajar_report_print', $data, TRUE);
		$this->dompdflib->generate($html, $filename, $paper, $orientation);

		// $html = ob_get_contents();
		// ob_clean();

		// require_once('./assets/html2pdf/html2pdf.class.php');
		// $pdf = new HTML2PDF('L','A4','en', false, 'ISO-8859-15',array(10, 5, 10, 5));

		// $pdf->setDefaultFont('arial');
		// $pdf->setTestTdInOnePage(false);
		// $pdf->pdf->SetDisplayMode('fullpage');
		// $pdf->WriteHTML($html);
		// $pdf->Output($filename, '');

	}
}
