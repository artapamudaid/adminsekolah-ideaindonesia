<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengumuman_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('setting/Setting_model', 'student/Student_model'));
        $this->load->library('upload');
        $this->load->helper(array('send_helper'));
    }

    // pos view in list


    // pos view in list
    public function index($offset = NULL)
    {
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
            $params['class_id']  = $q['c'];
        }

        if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
            $params['majors_id'] = $q['k'];
        }
        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);
        $data['student']    = $this->Student_model->get($params);

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['title'] = 'Kirim Pengumuman Siswa';
        $data['main'] = 'pengumuman/pengumuman_send_list';
        $this->load->view('manage/layout', $data);
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

    function get_form()
    {

        for ($count = 0; $count < count($_POST["student_id"]); $count++) {
            $student = $this->db->query("SELECT student_id
                                        FROM student
                                        WHERE student_id IN (" . $_POST['student_id'][$count] . ")")->result_array();

            foreach ($student as $row) {
                echo '<input type="hidden" name="student_id[]" id="student_id" value="' . $row['student_id'] . '">';
            }
        }

        echo '<div class="form-group">
                <label>Pengumuman</label>
				<textarea class="form-control" name="pengumuman" placeholder="Masukkan Pengumuman"></textarea>
			    </div>';
    }

    public function blast()
    {
        if ($_POST == TRUE) {

            $id             = $_POST['id_info'];
            $student_id     = $_POST['student_id'];
            $kode_sekolah   = $_POST['kode_sekolah'];
            $judul          = $_POST['judul'];
            $pengumuman     = $_POST['pengumuman'];

            $pengumuman = str_replace('(kode pesantren)', $kode_sekolah, $pengumuman);
            $pengumuman = str_replace('<p>', '', $pengumuman);
            $pengumuman = str_replace('</p>', '', $pengumuman);
            $pengumuman = str_replace('<span class="text_exposed_show">', '', $pengumuman);
            $pengumuman = str_replace('</span>', '', $pengumuman);
            $pengumuman = str_replace('<br />', "\n", $pengumuman);

            $nip_bendahara  = $this->Setting_model->get(array('id' => 15));
            $nama_bendahara = $this->Setting_model->get(array('id' => 16));
            $wa_center      = $this->Setting_model->get(array('id' => 17));

            $now = pretty_date(date('d-m-Y'), 'F', false);

            $month = $this->db->query("SELECT month_id FROM month WHERE month_name = '$now'")->row_array();

            $cpt = count($_POST['student_id']);
            for ($i = 0; $i < $cpt; $i++) {

                $params['student_id']   = $student_id[$i];

                $siswa = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_school_name, class_name, student_parent_phone FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_id = '$student_id[$i]'")->row_array();

                $wa_key             = $this->Setting_model->get(array('name' => 'setting_wa_key'));
                $setting_whatsapp   = $this->Setting_model->get(array('id' => 92));
                $set_key             = $wa_key['setting_value'];
                $set_wa              = $setting_whatsapp['setting_value'];

                if (isset($siswa['student_parent_phone']) and $siswa['student_parent_phone'] != '+62') {

                    $no_wa = $siswa['student_parent_phone'];
                    $pengumuman = str_replace('(nis santri)', $siswa['student_nis'], $pengumuman);
                    //$no_wa='+6283851378225';

                    // $info_wa = "\n\n" .
                    //     'Nomor WA Sekolah : http://wa.me/' . $wa_center['setting_value'];
                    // $psn = $pengumuman . $info_wa;

                    $info_wa = "\n\n" . 'Untuk Informasi hubungi No. WA Pesantren : http://wa.me/' . $wa_center['setting_value'] . "\n\n" . 'NB : Jika link tidak aktif silahkan simpan No. HP ini terlebih dahulu.';

                    $psn = $judul . "\n\n" . $pengumuman . $info_wa;


                    send_whatsapp($no_wa, $psn, $set_key, $set_wa);
                }
            }


            $this->session->set_flashdata('success', ' Kirim Pengumuman Siswa Berhasil');
            redirect('manage/pengumuman');
        }
    }
}
