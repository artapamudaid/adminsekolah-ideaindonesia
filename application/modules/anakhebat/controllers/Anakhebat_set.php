<?php
defined('BASEPATH') or exit('No direct script access allowed');


use SecureApiClient\SecureApiClient;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

//V1 tanpa field dinamis
//V2 dengan field dinamis
class Anakhebat_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array('setting/Setting_model', 'student/Student_model'));
        $this->load->helper(array('form', 'url', 'curl'));
        $this->load->library('session');
    }

    public function report()
    {
        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Kebiasaan Anak Indonesia Hebat';
        $data['main'] = 'anakhebat/anakhebat_report';
        $this->load->view('manage/layout', $data);
    }

    function class_searching()
    {
        $id_majors = $this->input->post('id_majors');
        $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

        echo '<div class="col-md-2">  
				<div class="form-group">
		            <select name="filter_class" id="filter_class" class="form-control" required="">
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

    public function habit_student_list()
    {

        $date = $this->input->get('date', TRUE);
        $majors_majors_id = $this->input->get('majors_majors_id', TRUE);
        $class_class_id = $this->input->get('class_class_id', TRUE);

        $students = $this->Student_model->get(
            array(
                'majors_id' => $majors_majors_id,
                'class_id' => $class_class_id
            )
        );

        $studentIds = array();

        foreach ($students as $student) {
            $studentIds[] = $student['student_id'];
        }

        $request = [
            'date' => $date,
            'student_id' => $studentIds
        ];

        $uri = '/v1/dailyreports/report';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $getData = json_decode($response, true);


        $data = $getData['body'] ?? [];

        $reports = json_decode($data, true);

        $cnt = isset($students) && is_array($students) ? $students : [];

        if (empty($cnt)) {
            echo json_encode(["draw" => intval($this->input->get("draw")), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            return;
        }

        echo json_encode([
            "draw" => intval($this->input->get("draw")),
            "recordsTotal" => count($cnt),
            "recordsFiltered" => count($cnt),
            "data" => $this->makeReport($students, $reports)
        ]);
    }

    function makeReport($students, $reports)
    {
        // Gabungkan report dengan student berdasarkan student_id
        $combined = [];
        foreach ($students as $student) {
            // Cari report student
            $report = array_values(array_filter($reports, function ($r) use ($student) {
                return $r['student_id'] === $student['student_id'];
            }));

            $data = [
                'date' => $report[0]['date'],
                'student_id' => $student['student_id'],
                'student_nis' => $student['student_nis'],
                'student_full_name' => $student['student_full_name'],
                'class_name' => $student['class_name'],
                'capaian' => '',
                'presentase' => 0,
                'color' => '#CCCCCC', // default abu-abu
            ];

            $label = 'primary';

            if (!empty($report)) {
                if ($report[0]['total'] > 0) {
                    $data['capaian'] = $report[0]['sudah'] . '/' . $report[0]['total'] . ' Sudah Dikerjakan';
                    $percent = ($report[0]['sudah'] / $report[0]['total']) * 100;
                    $data['presentase'] = strval(round($percent, 2)) . ' %';

                    if ($report[0]['warna'] == 'merah') {
                        $label = 'danger';
                    } else if ($report[0]['warna'] == 'kuning') {
                        $label = 'warning';
                    } else if ($report[0]['warna'] == 'hijau') {
                        $label = 'success';
                    }

                    $data['color'] = $label;
                }
            }

            $combined[] = $data;
        }

        // Urutkan berdasarkan presentase DESC
        usort($combined, function ($a, $b) {
            return $b['presentase'] <=> $a['presentase'];
        });

        // Tambahkan ranking
        foreach ($combined as $i => &$row) {
            $row['rank'] = $i + 1;
        }

        return $combined;
    }

    public function habit_report_detail()
    {
        $date = $this->input->post('date', TRUE);
        $student_id = $this->input->post('student_id', TRUE);

        $request = [
            'date' => $date,
            'student_id' => array($student_id)
        ];

        $uri = '/v1/dailyreports/report';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $getData = json_decode($response, true);

        $data = $getData['body'] ?? [];

        $data = json_decode($data, true);

        $dataHabits = $data[0]['habits'];

        $report = isset($dataHabits) && is_array($dataHabits) ? $dataHabits : [];

        if (empty($report)) {
            echo json_encode(["draw" => intval($this->input->get("draw")), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            return;
        }

        echo json_encode([
            "draw" => intval($this->input->get("draw")),
            "recordsTotal" => count($report),
            "recordsFiltered" => count($report),
            "data" => $report
        ]);
    }


    public function habit()
    {
        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

        $data['title'] = 'Kebiasaan Anak Indonesia Hebat';
        $data['main'] = 'anakhebat/anakhebat_list';
        $this->load->view('manage/layout', $data);
    }

    public function habit_list()
    {

        $request = [];

        $uri = '/v2/habits/all';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $getData = json_decode($response, true);

        $data = $getData['body'] ?? [];

        $data = json_decode($data, true);

        $habit = isset($data['data']) && is_array($data['data']) ? $data['data'] : [];

        if (empty($habit)) {
            echo json_encode(["draw" => intval($this->input->get("draw")), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            return;
        }

        echo json_encode([
            "draw" => intval($this->input->get("draw")),
            "recordsTotal" => count($habit),
            "recordsFiltered" => count($habit),
            "data" => $habit
        ]);
    }

    public function habit_by_id()
    {
        $id = $this->input->post('id', TRUE);

        if (empty($id)) {
            echo json_encode(['error' => 'ID tidak ditemukan']);
            return;
        }

        $request = ['id' => $id];

        $uri = '/v2/habits/show';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $getData = json_decode($response, true);

        if (isset($getData['body'])) {
            $data = json_decode($getData['body'], true);
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Data tidak ditemukan']);
        }
    }

    public function habit_insert()
    {

        $request = [
            'name' => $this->input->post('name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'is_checkbox' => $this->input->post('is_checkbox', TRUE),
            'use_photo' => $this->input->post('use_photo', TRUE),
            'use_time' => $this->input->post('use_time', TRUE),
        ];

        $uri = '/v2/habits/add';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $output = json_decode($response);

        $res = json_decode($output->body);

        echo $res->status == 'success' ? json_encode(['is_correct' => true]) : json_encode(['is_correct' => false, 'message' => $res->message]);
    }

    public function habit_update()
    {

        $request = [
            'id' => $this->input->post('id', TRUE),
            'name' => $this->input->post('name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'is_checkbox' => $this->input->post('is_checkbox', TRUE),
            'use_photo' => $this->input->post('use_photo', TRUE),
            'use_time' => $this->input->post('use_time', TRUE),
        ];

        $uri = '/v2/habits/update';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $output = json_decode($response);

        $res = json_decode($output->body);

        echo $res->status == 'success' ? json_encode(['is_correct' => true]) : json_encode(['is_correct' => false, 'message' => $res->message]);
    }

    public function habit_delete()
    {

        $request = [
            'id' => $this->input->post('id', TRUE),
        ];

        $uri = '/v2/habits/delete';

        $response = $this->encryption($uri, $request);

        $response = json_encode($response);

        if ($response === false) {
            $error = json_last_error_msg();
            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
            return;
        }

        $output = json_decode($response);

        $res = json_decode($output->body);

        echo $res->status == 'success' ? json_encode(['is_correct' => true]) : json_encode(['is_correct' => false, 'message' => $res->message]);
    }

    public function import_data()
    {
        $data['title']      = 'Import Kebiasaan Anak Indonesia Hebat';
        $data['main']       = 'anakhebat/anakhebat_import';
        $data['action']     = site_url(uri_string());
        $this->load->view('manage/layout', $data);
    }

    public function download()
    {
        $data = file_get_contents("./media/template_excel/Template_Data_Kebiasaan_Baik.xlsx");
        $name = 'Template_Data_Kebiasaan_Baik.xlsx';

        $this->load->helper('download');
        force_download($name, $data);
    }

    public function do_import()
    {

        if (isset($_FILES["file"]["name"])) {

            $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('kebiasaanbaik' . date('ymdHis')));
            $uri = '/v2/habits/add';

            if ($file) {

                $path = FCPATH . 'uploads/xls_tmp/';
                $pathFile = $path . $file;
                $realPath = realpath($pathFile);
                $realBase = realpath($path);
                if ($realPath === FALSE || $realBase === FALSE || strpos($realPath, $realBase) !== 0) {
                    $this->session->set_flashdata('failed', 'File import kebiasaan baik tidak valid');
                    redirect('manage/anakhebat/habit', 'refresh');
                }

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $reader->setReadDataOnly(TRUE);
                $spreadsheet = $reader->load($realPath);
                $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $numrow = 1;
                foreach ($sheet as $row) {

                    $name                 = trim((string) $row['A']); //required
                    $description          = trim((string) $row['B']);

                    if ($numrow > 2) {

                        $request = [
                            'name' => $name,
                            'description' => $description
                        ];

                        $response = $this->encryption($uri, $request);

                        $response = json_encode($response);

                        if ($response === false) {
                            $error = json_last_error_msg();
                            echo json_encode(['error' => 'JSON encoding error: ' . $error]);
                            return;
                        }


                        $this->session->set_flashdata('success', 'Import Data Kebiasaan Baik Berhasil');
                    }
                    $numrow++;
                }
                unlink($realPath);

                redirect('manage/anakhebat/habit', 'refresh');
            }
        }
    }

    private function do_upload_xls($name = NULL, $fileName = NULL)
    {
        $this->load->library('upload');

        $config['upload_path'] = FCPATH . 'uploads/xls_tmp/';

        /* create directory if not exist */
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, TRUE);
        }

        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '10240';
        $fileName = preg_replace("/[^A-Za-z0-9_.-]/", "_", (string) $fileName);
    $config['file_name'] = $fileName;
    $config["file_ext_tolower"] = TRUE;
    $config["remove_spaces"] = TRUE;
    $config["detect_mime"] = TRUE;
    $config["mod_mime_fix"] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($name)) {
            $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
            redirect(uri_string());
        }

        $upload_data = $this->upload->data();

        return $upload_data['file_name'];
    }


    private function encryption($uri, $request)
    {

        $key = $this->Setting_model->get(array('id' => 301));
        $api_key = $key['setting_value'];
        $secret = $this->Setting_model->get(array('id' => 302));
        $api_secret = $secret['setting_value'];

        $url = $this->config->item('anakhebat_url') . "/api";

        $client = new SecureApiClient(
            $url, // Base URL
            $api_key,  // API Key
            $api_secret // API Secret
        );

        // Kirim request POST
        $response = $client->request('POST', $uri, $request);

        return $response;
    }
}
