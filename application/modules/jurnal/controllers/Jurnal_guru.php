<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged_guru') == NULL) {
            header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('jurnal/Jurnal_model', 'student/Student_model', 'period/Period_model', 'bulan/Bulan_model', 'setting/Setting_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    // pos view in list
    public function index($offset = NULL)
    {
        $q          = $this->input->get(NULL, TRUE);

        $data['q']  = $q;

        $param      = array();
        $class_id   = null;
        $lesson_id  = null;
        // $jam_pelajaran_id = null;

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id              = $q['l'];
            $param['lesson_id']     = $q['l'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $class_id               = $q['c'];
            $param['class_id']      = $q['c'];
        }

        $id_guru = $this->session->userdata('uid_guru');

        $date = date('Y-m-d');
        $this_month = pretty_date($date, 'F', false);

        $data['bulan'] = $this->db->query("SELECT * FROM month WHERE month_name = '$this_month'")->row_array();

        $param['teacher_id'] = $id_guru;

        $param['date'] = $date;

        $day        = pretty_date($date, 'l', false);

        $data['note'] = array(
            array(
                'id' => 'H',
                'name' => 'Hadir'
            ),
            array(
                'id' => 'S',
                'name' => 'Sakit'
            ),
            array(
                'id' => 'I',
                'name' => 'Izin'
            ),
            array(
                'id' => 'A',
                'name' => 'Alpha'
            ),
        );

        $data['check']      = $this->Jurnal_model->get($param);

        $data['class']      = $this->db->query("SELECT DISTINCT class_id, class_name FROM schedule
                                                JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                JOIN day ON schedule.schedule_day = day.day_id
                                                JOIN semester ON semester_id = schedule_semester_id
                                                JOIN period ON period_id = semester_period_id
                                                WHERE lesson_teacher = '$id_guru' AND day_name = '$day'
                                                AND semester_status = '1' AND period_status = '1'
                                                ")->result_array();

        $data['lesson']      = $this->db->query("SELECT DISTINCT lesson_id, lesson_name,lesson_code FROM schedule
                                                JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                JOIN day ON schedule.schedule_day = day.day_id
                                                JOIN semester ON semester_id = schedule_semester_id
                                                JOIN period ON period_id = semester_period_id
                                                WHERE lesson_teacher = '$id_guru'
                                                AND day_name = '$day'
                                                AND schedule_class_id = '$class_id'
                                                AND semester_status = '1' AND period_status = '1'
                                                ")->result_array();

        $data['j_pelajaran']  = $this->db->query("SELECT jam_pelajaran_id, jam_pelajaran_start, jam_pelajaran_end
                                                    FROM schedule
                                                    JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                    JOIN class ON schedule.schedule_class_id = class.class_id
                                                    JOIN day ON schedule.schedule_day = day.day_id
                                                    JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
                                                    JOIN semester ON semester_id = schedule_semester_id
                                                    JOIN period ON period_id = semester_period_id
                                                    WHERE lesson_teacher = '$id_guru'
                                                    AND day_name = '$day'
                                                    AND lesson_id = '$lesson_id'
                                                    AND schedule_class_id = '$class_id'
                                                    AND semester_status = '1'
                                                    AND period_status = '1'
                                                    ORDER BY jam_pelajaran_start ASC")->result_array();

        $data['schedule']      = $this->db->query("SELECT schedule_id, jam_pelajaran_name, jam_pelajaran_id FROM schedule
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                JOIN day ON schedule.schedule_day = day.day_id
                                                JOIN jam_pelajaran ON schedule.schedule_jampel = jam_pelajaran.jam_pelajaran_id
                                                JOIN semester ON semester_id = schedule_semester_id
                                                JOIN period ON period_id = semester_period_id
                                                WHERE schedule_lesson_id = '$lesson_id'
                                                AND day_name = '$day'
                                                AND schedule_class_id = '$class_id'
                                                AND semester_status = '1' AND period_status = '1'
                                                ORDER BY jam_pelajaran_name ASC")->result_array();

        $data['materi_terakhir'] = $this->db->query("SELECT teaching_materi FROM teaching
                                                    JOIN class ON teaching_class_id = class_id
                                                    JOIN semester ON teaching_semester_id = semester_id
                                                    JOIN lesson ON teaching_lesson_id = lesson.lesson_id
                                                    JOIN schedule ON schedule.schedule_lesson_id = lesson.lesson_id
                                                    WHERE schedule_lesson_id = '$lesson_id'
                                                    AND teaching_employee_id = '$id_guru'
                                                    AND teaching_class_id = '$class_id'
                                                    AND semester_status = '1'
                                                    ORDER BY teaching_date_create DESC LIMIT 1")->row_array();

        $latest = $this->db->query("SELECT MAX(presensi_pelajaran_date) date
                                                FROM presensi_pelajaran a
                                                JOIN `schedule` b ON a.presensi_pelajaran_schedule_id = b.schedule_id
                                                JOIN lesson c ON c.lesson_id = b.schedule_lesson_id
                                                JOIN teaching d ON d.teaching_id = a.presensi_teaching_id
                                                WHERE a.presensi_pelajaran_class_id = '$class_id' 
                                                AND d.teaching_employee_id = '$id_guru'
                                                AND c.lesson_id = '$lesson_id'")->row_array();

        $data['latest'] = $this->db->query("SELECT presensi_pelajaran_student_id, presensi_pelajaran_status, schedule_id
                                            FROM presensi_pelajaran a
                                            JOIN `schedule` b ON a.presensi_pelajaran_schedule_id = b.schedule_id
                                            JOIN lesson c ON c.lesson_id = b.schedule_lesson_id
                                            JOIN teaching d ON d.teaching_id = a.presensi_teaching_id
                                            WHERE a.presensi_pelajaran_class_id = '$class_id' 
                                            AND d.teaching_employee_id = '$id_guru'
                                            AND c.lesson_id = '$lesson_id'
                                            AND a.presensi_pelajaran_date = '$latest[date]'")->result_array();

        $data['student']    = $this->db->query("SELECT `student`.`student_id`, `student_nis`, `student_full_name`, `class`.`class_id`,  `class`.`class_name`, `majors_id`,`majors_short_name`, `majors_status` FROM `student` JOIN `class` ON `class`.`class_id` = `student`.`class_class_id` JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` WHERE `student`.`student_status` = '1' AND `class_class_id` = '$class_id' AND `majors_status` = '1' ORDER BY `student_nis` ASC")->result_array();
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['semester'] = $this->db->query("SELECT semester_id FROM semester WHERE semester_status = '1'")->row_array();

        $data['title'] = 'Jurnal Mengajar';
        $data['main'] = 'jurnal/jurnal_list';
        $this->load->view('guru/layout', $data);
    }

    public function save()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $student = $_POST['student_id'];
            $schedule = $_POST['schedule'];
            $jam_pelajaran = $_POST['jam_pelajaran'];
            $lesson_id = $_POST['lesson_id'];
            $class_id = $_POST['class_id'];
            $materi = $_POST['materi'];
            $month_id = $_POST['month_id'];
            $semester_id = $_POST['semester_id'];
            $date = date('Y-m-d');
            $teacher_id = $this->session->userdata('uid_guru');

            $period = $this->db->get_where('semester', array('semester_id' => $semester_id))->row_array();
            $period_id = $period['semester_period_id'];

            $this->db->insert('teaching', array(
                'teaching_employee_id' => $teacher_id,
                'teaching_semester_id' => $semester_id,
                'teaching_month_id' => $month_id,
                'teaching_class_id' => $class_id,
                'teaching_lesson_id' => $lesson_id,
                'teaching_materi' => $materi,
                'teaching_date_create' => date('Y-m-d H:i:s')
            ));

            $materi_id = $this->db->insert_id();

            $jumlah_siswa_masuk = 0;
            $jumlah_siswa_sakit = 0;
            $jumlah_siswa_izin  = 0;
            $jumlah_siswa_alpha = 0;
            $siswa_tidak_masuk  = [];

            for ($i = 0; $i < count($student); $i++) {
                for ($j = 0; $j < count($schedule); $j++) {
                    $schedule_id = $schedule[$j];
                    $student_id = $student[$i];
                    $status = $_POST['schedule_id' . $schedule[$j] . $student[$i]][0];

                    $data = array(
                        'presensi_teaching_id' => $materi_id,
                        'presensi_pelajaran_semester_id' => $semester_id,
                        'presensi_pelajaran_period_id' => $period_id,
                        'presensi_pelajaran_month_id' => $month_id,
                        'presensi_pelajaran_date' => $date,
                        'presensi_pelajaran_class_id' => $class_id,
                        'presensi_pelajaran_lesson_id' => $lesson_id,
                        'presensi_pelajaran_schedule_id' => $schedule_id,
                        'presensi_pelajaran_student_id' => $student_id,
                        'presensi_pelajaran_status' => $status
                    );

                    $this->db->insert('presensi_pelajaran', $data);
                }

                if ($status == 'H') {
                    $jumlah_siswa_masuk++;
                } else {
                    $student_info = $this->db->query("SELECT student_full_name FROM student WHERE student_id = '$student_id'")->row_array();
                    $nama_siswa = $student_info ? $student_info['student_full_name'] : 'Unknown';
                    if ($status == 'S') {
                        $jumlah_siswa_sakit++;
                        $siswa_tidak_masuk[] = ['nama' => $nama_siswa, 'keterangan' => 'sakit'];
                    } elseif ($status == 'I') {
                        $jumlah_siswa_izin++;
                        $siswa_tidak_masuk[] = ['nama' => $nama_siswa, 'keterangan' => 'izin'];
                    } elseif ($status == 'A') {
                        $jumlah_siswa_alpha++;
                        $siswa_tidak_masuk[] = ['nama' => $nama_siswa, 'keterangan' => 'alpha'];
                    }
                }
            }

            $this->load->helper('notiftele');
            $employee = $this->db->query("SELECT employee_name, employee_nip, majors.majors_short_name as unit, position.position_name as jabatan 
                                          FROM employee 
                                          LEFT JOIN majors ON majors.majors_id = employee.employee_majors_id 
                                          LEFT JOIN position ON position.position_id = employee.employee_position_id 
                                          WHERE employee_id = '$teacher_id'")->row_array();
            
            $class_info = $this->db->query("SELECT class_name FROM class WHERE class_id = '$class_id'")->row_array();
            $lesson_info = $this->db->query("SELECT lesson_name FROM lesson WHERE lesson_id = '$lesson_id'")->row_array();

            if ($employee && $class_info && $lesson_info) {
                $payload_tele = [
                    'topik'  => 'jurnal_mengajar',
                    'pesan'  => 'Jurnal: Mengajar ' . $lesson_info['lesson_name'] . ' Kelas ' . $class_info['class_name'] . ' (Materi ' . $materi . ') pada ' . date('H:i:s'),
                    'detail' => [
                        'tanggal' => date('Y-m-d', strtotime($date)),
                        'nama'    => $employee['employee_name'],
                        'nip'     => $employee['employee_nip'] ?? '',
                        'unit'    => $employee['unit'] ?? '',
                        'kelas'   => $class_info['class_name'],
                        'jabatan' => $employee['jabatan'] ?? '',
                        'jumlah_siswa_masuk' => $jumlah_siswa_masuk,
                        'jumlah_siswa_sakit' => $jumlah_siswa_sakit,
                        'jumlah_siswa_izin'  => $jumlah_siswa_izin,
                        'jumlah_siswa_alpha' => $jumlah_siswa_alpha,
                        'siswa_tidak_masuk'  => $siswa_tidak_masuk
                    ]
                ];
                create_log($payload_tele);
            }

            $cnt = count($schedule);

            for ($i = 0; $i < $cnt; $i++) {
                $this->db->set('presensi_guru_schedule_id', $schedule[$i]);
                $this->db->set('presensi_guru_date', $date);
                $this->db->set('presensi_guru_attend', '1');
                $this->db->set('presensi_guru_employee_id', $teacher_id);
                $this->db->set('presensi_guru_class_id', $class_id);
                $this->db->set('presensi_guru_jampel_id', $jam_pelajaran[$i]);

                $this->db->insert('presensi_guru');
            }

            $jampel = $this->db->get_where('schedule', array('schedule_id' => $schedule_id))->row_array();
            $jam_pelajaran_id = $jampel['schedule_jampel'];


            $this->session->set_flashdata('success', ' Input Jurnal Berhasil');

            redirect('guru/jurnal?c=' . $class_id . '&l=' . $lesson_id . '&j=' . $jam_pelajaran_id);
        }
    }

    function lesson_searching()
    {

        $date       = date('Y-m-d');
        $id_guru    = $this->session->userdata('uid_guru');
        $class_id   = $this->input->post('class_id');
        $day        = pretty_date($date, 'l', false);
        $lesson  = $this->db->query("SELECT DISTINCT lesson_id, lesson_name, lesson_code FROM schedule
        JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
        JOIN class ON schedule.schedule_class_id = class.class_id
        JOIN day ON schedule.schedule_day = day.day_id
        JOIN semester ON semester_id = schedule_semester_id
        JOIN period ON period_id = semester_period_id
        WHERE lesson_teacher = '$id_guru'
        AND day_name = '$day'
        AND schedule_class_id = '$class_id'
        AND semester_status = '1'
        AND period_status = '1'")->result_array();

        echo '<div class="col-md-12 col-sm-12">  
				<div class="form-group">
				    <label>Mapel</label>
		            <select name="l" id="lesson_id" class="form-control" required="">
                    <option value="">-- Pilih Mapel --</option>';
        foreach ($lesson as $row) {

            echo '<option value="' . $row['lesson_id'] . '">';

            echo $row['lesson_code'] . ' - ' . $row['lesson_name'];

            echo '</option>';
        }
        echo '</select>
				</div>
			</div>';
    }
}
