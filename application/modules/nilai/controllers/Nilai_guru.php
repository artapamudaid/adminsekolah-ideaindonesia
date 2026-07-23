<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nilai_guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged_guru') == NULL) {
            header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'period/Period_model', 'setting/Setting_model', 'logs/Logs_model'));
        $this->load->library('upload');
        $this->load->helper('get_column');
    }

    // pos view in list
    public function index($offset = NULL)
    {
        $q          = $this->input->get(NULL, TRUE);

        $data['q']  = $q;

        $class_id   = null;
        $lesson_id  = null;
        $nilai      = null;

        if (isset($q['l']) && !empty($q['l']) && $q['l'] != '') {
            $lesson_id              = $q['l'];
            $params['lesson_id']    = $q['l'];
            $param['lesson_id']     = $q['l'];
        }

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $class_id               = $q['c'];
            $params['class_id']     = $q['c'];
            $param['class_id']      = $q['c'];
        }

        if (isset($q['n']) && !empty($q['n']) && $q['n'] != '') {
            $nilai               = $q['n'];
        }

        $id_guru = $this->session->userdata('uid_guru');
        $data['student']    = $this->db->query("SELECT `student`.`student_id`, `student_nis`, `student_full_name`, `class`.`class_id`,  `class`.`class_name`, `majors_id`,`majors_short_name`, `majors_status` FROM `student` JOIN `class` ON `class`.`class_id` = `student`.`class_class_id` JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` WHERE `student`.`student_status` = '1' AND `class_class_id` = '$class_id' AND `majors_status` = '1' ORDER BY `student_nis` ASC")->result_array();

        $data['semester'] = $this->db->query("SELECT semester_id FROM semester WHERE semester_status = '1'")->row_array();
        $data['period'] = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
        $data['lesson_id'] = $lesson_id;
        $data['teacher_id'] = $id_guru;



        $data['class']      = $this->db->query("SELECT DISTINCT class_id, class_name
        FROM schedule
        JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
        JOIN class ON schedule.schedule_class_id = class.class_id
        WHERE lesson_teacher = '$id_guru'
        ORDER BY
            CASE
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'i' THEN 1
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'ii' THEN 2
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'iii' THEN 3
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'iv' THEN 4
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'v' THEN 5
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'vi' THEN 6
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'vii' THEN 7
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'viii' THEN 8
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'ix' THEN 9
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'x' THEN 10
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'xi' THEN 11
                WHEN SUBSTRING_INDEX(class_name, '-', 1) = 'xii' THEN 12
                ELSE 0
            END,
            CAST(SUBSTRING_INDEX(class_name, '-', -1) AS UNSIGNED) ASC")->result_array();

        $data['lesson']      = $this->db->query("SELECT DISTINCT lesson_id, lesson_name,lesson_code FROM schedule
                                                JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
                                                JOIN class ON schedule.schedule_class_id = class.class_id
                                                WHERE lesson_teacher = '$id_guru'
                                                ")->result_array();

        if (isset($nilai)) {
            $data['nilai_item'] = $this->nilai_item($nilai);

            $params = array(
                'table'         => $nilai,
                'lesson_id'     => $lesson_id,
                'class_id'      => $class_id,
                'period_id'     => $data['period']['period_id'],
                'semester_id'   => $data['semester']['semester_id'],
                'teacher_id'    => $this->session->userdata('uid_guru')
            );

            $data['nilai'] = $this->get_nilai($params);
            $data['title'] = 'Penilaian ' . ucfirst($nilai);
            $data['main'] = 'nilai/nilai_add';
        } else {
            $data['title'] = 'Penilaian';
            $data['main'] = 'nilai/nilai_list';
        }
        $this->load->view('guru/layout', $data);
    }

    private function nilai_item(String $val): array
    {
        $item = array();

        $item = get_column_nilai($val);

        usort($item, function ($a, $b) {
            return intval(substr(strrchr($a, '_'), 1)) - intval(substr(strrchr($b, '_'), 1));
        });

        return $item;
    }

    private function get_nilai(array $params): array
    {

        $this->db->where($params['table'] . '_lesson_id', $params['lesson_id']);
        $this->db->where($params['table'] . '_period_id', $params['period_id']);
        $this->db->where($params['table'] . '_semester_id', $params['semester_id']);
        $this->db->where($params['table'] . '_teacher_id', $params['teacher_id']);
        $this->db->where('class_class_id', $params['class_id']);

        $this->db->join('student', 'student.student_id = ' . $params['table'] . '.' . $params['table'] . '_student_id', 'left');
        $query = $this->db->get($params['table'])->result_array();

        return $query;
    }

    private function check_nilai(array $params): int
    {

        $this->db->where($params['table'] . '_lesson_id', $params['lesson_id']);
        $this->db->where($params['table'] . '_period_id', $params['period_id']);
        $this->db->where($params['table'] . '_semester_id', $params['semester_id']);
        $this->db->where($params['table'] . '_teacher_id', $params['teacher_id']);
        $this->db->where($params['table'] . '_student_id', $params['student_id']);

        $query = $this->db->get($params['table'])->num_rows();

        return $query;
    }

    public function save()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nilai = $_POST['nilai'];
            $student_id = $_POST['student_id'];
            $lesson_id = $_POST['lesson_id'];
            $class_id = $_POST['class_id'];
            $semester_id = $_POST['semester_id'];
            $period_id = $_POST['period_id'];
            $teacher_id = $this->session->userdata('uid_guru');


            for ($i = 0; $i < count($student_id); $i++) {

                $params = array(
                    'table'         => $nilai,
                    'lesson_id'     => $lesson_id,
                    'period_id'     => $period_id,
                    'semester_id'   => $semester_id,
                    'student_id'    => $student_id[$i],
                    'teacher_id'    => $this->session->userdata('uid_guru')
                );

                $items = $this->nilai_item($nilai);

                $check = $this->check_nilai($params);

                if ($check < 1) {

                    $data = array(
                        $nilai . '_teacher_id' => $teacher_id,
                        $nilai . '_semester_id' => $semester_id,
                        $nilai . '_period_id' => $period_id,
                        $nilai . '_lesson_id' => $lesson_id,
                        $nilai . '_student_id' => $student_id[$i],
                    );

                    foreach ($items as $item) {
                        ${$item} = $_POST[$item][$i];
                        $data[$item] = ${$item};
                    }

                    $this->db->insert($nilai, $data);
                } else {
                    $this->db->where($nilai . '_teacher_id', $teacher_id);
                    $this->db->where($nilai . '_semester_id', $semester_id);
                    $this->db->where($nilai . '_period_id', $period_id);
                    $this->db->where($nilai . '_lesson_id', $lesson_id);
                    $this->db->where($nilai . '_student_id', $student_id[$i]);

                    foreach ($items as $item) {
                        ${$item} = $_POST[$item][$i];
                        $data[$item] = ${$item};
                    }

                    $this->db->update($nilai, $data);
                }
            }


            $this->session->set_flashdata('success', ' Input Nilai ' . ucfirst($nilai) . ' Berhasil ');

            redirect('guru/nilai?c=' . $class_id . '&l=' . $lesson_id . '&n=' . $nilai);
        }
    }

    function lesson_searching()
    {


        $id_guru    = $this->session->userdata('uid_guru');
        $class_id   = $this->input->post('class_id');

        $lesson  = $this->db->query("SELECT DISTINCT lesson_id, lesson_name, lesson_code FROM schedule
        JOIN lesson ON schedule.schedule_lesson_id = lesson.lesson_id
        JOIN class ON schedule.schedule_class_id = class.class_id
        WHERE lesson_teacher = '$id_guru'
        AND schedule_class_id = '$class_id'")->result_array();

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
