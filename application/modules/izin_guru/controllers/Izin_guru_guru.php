<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Izin_guru_guru extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_guru') == NULL) {
            header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array(
            'setting/Setting_model',
        ));

        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {

        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $date = date('Y-m-d');

        if (isset($f['date']) && !empty($f['date']) && $f['date'] != '') {
            $date = $f['date'];
        }

        if ($date < date('Y-m-d')) {
            $this->session->set_flashdata('failed', 'Tanggal tidak valid');
            redirect('guru/izin_guru');
        }

        $id_guru = $this->session->userdata('uid_guru');

        $data['jurnals'] = $this->db->query("SELECT presensi_pelajaran_schedule_id
                                            FROM presensi_pelajaran
                                            JOIN teaching ON teaching.teaching_id = presensi_teaching_id
                                            WHERE presensi_pelajaran_date = '$date' AND teaching_employee_id = '$id_guru'")->result_array();

        $data['izins'] = $this->db->query("SELECT schedule_id, izin_detail_task FROM izin_guru
                                            JOIN izin_detail ON izin_guru.izin_guru_id = izin_detail.izin_detail_izin_id
                                            JOIN schedule ON schedule.schedule_id = izin_detail.izin_detail_schedule_id
                                            JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
                                            JOIN semester ON semester_id = schedule_semester_id
                                            JOIN period ON period_id = semester_period_id
                                            WHERE izin_guru_date = '$date' AND izin_guru_employee_id = '$id_guru'
                                            AND semester_status = '1' AND period_status = '1'
                                            ORDER BY jam_pelajaran_name ASC")->result_array();

        $today = pretty_date($date, 'l', false);
        $data['schedules'] = $this->db->query("SELECT schedule_id, jam_pelajaran_name, class_name, lesson_name
                                                FROM schedule
                                                JOIN day ON day.day_id = schedule.schedule_day
                                                JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id
                                                JOIN class ON class.class_id = schedule.schedule_class_id
                                                JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
                                                JOIN semester ON semester_id = schedule_semester_id
                                                JOIN period ON period_id = semester_period_id
                                                WHERE day.day_name = '$today' AND lesson_teacher = '$id_guru'
                                                AND semester_status = '1' AND period_status = '1'
                                                ORDER BY jam_pelajaran_name ASC")->result_array();


        $data['title'] = 'Perizinan';
        $data['main'] = 'izin_guru/izin_guru_view';
        $this->load->view('guru/layout', $data);
    }

    public function save()
    {
        if ($_POST) {

            $izin = array(
                'izin_guru_date' => $this->input->post('izin_date'),
                'izin_guru_note' => $this->input->post('izin_note'),
                'izin_guru_employee_id' => $this->session->userdata('uid_guru')
            );

            $this->load->library('upload');

            $config['upload_path'] = FCPATH . 'uploads/izin_guru/';

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size'] = 2048;
            $config['file_name'] = 'izin_guru_' . time();

            $this->upload->initialize($config);

            if ($this->upload->do_upload('camera')) {
                $uploadData = $this->upload->data();
                $izin['izin_guru_file'] = $uploadData['file_name'];
            }

            $insert_izin = $this->db->insert('izin_guru', $izin);

            $izin_date = $this->input->post('izin_date');

            if ($insert_izin) {

                $insert_id = $this->db->insert_id();

                $schedule = [];
                if (isset($_POST['izin_detail_schedule_id'])) {
                    $schedule = $_POST['izin_detail_schedule_id'];
                }

                $this->db->where_in('schedule_id', $schedule);
                $this->db->select('schedule_jampel, schedule_class_id');
                $this->db->from('schedule');
                $get_schedule = $this->db->get()->result_array();

                $jam_pelajaran = [];
                foreach ($get_schedule as $row) {
                    $jam_pelajaran[] = $row['schedule_jampel'];
                    $class_id = $row['schedule_class_id'];
                }

                $taks = array_filter($_POST['izin_detail_task'], function ($value) {
                    return $value !== "";
                });

                $task = array_values($taks);

                $cnt = count($schedule);

                for ($i = 0; $i < $cnt; $i++) {
                    $izin_detail = array(
                        'izin_detail_schedule_id' => $schedule[$i],
                        'izin_detail_task' => $task[$i],
                        'izin_detail_izin_id' => $insert_id
                    );

                    $insert_izin_detail = $this->db->insert('izin_detail', $izin_detail);

                    if ($insert_izin_detail) {
                        $this->db->set('presensi_guru_schedule_id', $schedule[$i]);
                        $this->db->set('presensi_guru_date', $izin_date);
                        $this->db->set('presensi_guru_attend', '2');
                        $this->db->set('presensi_guru_employee_id', $this->session->userdata('uid_guru'));
                        $this->db->set('presensi_guru_class_id', $class_id);
                        $this->db->set('presensi_guru_jampel_id', $jam_pelajaran[$i]);

                        $this->db->insert('presensi_guru');
                    }
                }

                $id_guru = $this->session->userdata('uid_guru');

                $mapels = $this->db->query("SELECT jam_pelajaran_name, class_name, lesson_name FROM izin_guru
                JOIN izin_detail ON izin_guru.izin_guru_id = izin_detail.izin_detail_izin_id
                JOIN schedule ON schedule.schedule_id = izin_detail.izin_detail_schedule_id
                JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id
                JOIN class ON class.class_id = schedule.schedule_class_id
                JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
                WHERE izin_guru_date = '$izin_date' AND izin_guru_employee_id = '$id_guru'
                ORDER BY jam_pelajaran_name ASC")->result_array();

                $notif = "### Notif Perizinan Guru ###" . "\n\n" .
                    "Guru atas nama " . $this->session->userdata('ufullname_guru') . " mengajukan izin tidak melaksanakan KBM pada " . pretty_date($izin_date, 'l, d F Y', false) . " :" . "\n";

                foreach ($mapels as $mapel) {
                    $notif .= " - " . $mapel['jam_pelajaran_name'] . " = Kelas " . $mapel['class_name'] . " – Mapel " . $mapel['lesson_name'] . "\n";
                }

                $notif .= "\n" . "Di karenakan : " . $this->input->post('izin_note');

                if (!empty($uploadData['file_name'])) {
                    $lampiran = base_url() . 'uploads/izin_guru/' . $uploadData['file_name'];

                    $notif .= "\n" . "Lampiran : " . $lampiran;
                }

                $wa  = $this->Setting_model->get(array('id' => 14));

                $pesan = array(
                    'no_wa'         => $wa['setting_value'],
                    'pesan'         => $notif,
                    'status_kirim'  => 'PENDING',
                    'created_date'  => date('Y-m-d H:i:s')
                );

                $this->db->insert('pesan', $pesan);

                $this->session->set_flashdata('success', ' Izin Berhasil');
            } else {
                $this->session->set_flashdata('failed', ' Izin Gagal');
            }

            redirect('guru/izin_guru?date=' . $izin_date);
        }
    }
}
