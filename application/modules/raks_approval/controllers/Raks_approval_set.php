<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Raks_approval_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('period/Period_model', 'student/Student_model', 'raks_entry/Raks_entry_model'));
    }

    function index()
    {
        $this->load->library('pagination');

        $s = $this->input->get(NULL, TRUE);

        $data['s'] = $s;

        $params = array();

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['majors_id'] = $s['m'];
        }

        if (isset($s['p']) && !empty($s['p']) && $s['m'] != 'all') {
            $id_period = $s['p'];
            $tahunPeriod = $this->db->query("SELECT period_start,period_end FROM period WHERE period_id = '$id_period'")->row_array();
            $data['periodStart'] = $tahunPeriod['period_start'];
            $data['periodEnd']   = $tahunPeriod['period_end'];
            $params['period_id'] = $s['p'];
        }
        // echo json_encode($data['s']);
        // exit;
        $data['periodActive']   = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
        $data['period']         = $this->Period_model->get($params);

        // echo json_encode($data['raks_entry']);
        // exit;
        $config['base_url'] = site_url('manage/raks_entry/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['majors'] = $this->Student_model->get_majors();

        if (!empty($params['majors_id']) and !empty($params['period_id'])) {
            $idMajor = $params['majors_id'];
            $data['dataMajors'] = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = $idMajor")->row_array();
            $idPeriode = $params['period_id'];
            $data['dataPeriod'] = $this->db->query("SELECT period_start, period_end FROM period WHERE period_id = $idPeriode")->row_array();
            $data['raks_entry'] = $this->Raks_entry_model->get_entry($params['majors_id'], $params['period_id']);
            $data['status_approval'] = $this->Raks_entry_model->get_entry_status($params['majors_id'], $params['period_id']);
        }

        $grade = ["FOUNDATION", "SEKOLAH", "ACTIVITY FEE", "OTHERS"];
        if (!empty($params['majors_id']) and !empty($params['period_id'])) {
            if ($params['majors_id'] == 1) {
                $tk = ["K1", "K2", "PG", "PRE KG"];
                for ($i = 0; $i < count($tk); $i++) {
                    array_push($grade, $tk[$i]);
                }
            } elseif ($params['majors_id'] == 2) {
                $sd = ["GRADE 1", "GRADE 2", "GRADE 3", "GRADE 4", "GRADE 5", "GRADE 6"];
                for ($i = 0; $i < count($sd); $i++) {
                    array_push($grade, $sd[$i]);
                }
            } elseif ($params['majors_id'] == 3) {
                $smp = ["GRADE 7", "GRADE 8", "GRADE 9"];
                for ($i = 0; $i < count($smp); $i++) {
                    array_push($grade, $smp[$i]);
                }
            }
        }

        $data['grade'] =  $grade;
        $data['title'] = 'RAKS Approval';
        $data['main'] = 'raks_approval/approval_list';
        $this->load->view('manage/layout', $data);
    }

    function approve()
    {
        $update = FALSE;
        if ($_POST) {
            $majors_id      = $this->input->post('majors_id');
            $period_id      = $this->input->post('period_id');
            $approval       = $this->input->post('approval');
            $update         = $this->Raks_entry_model->updateEntryStatus($majors_id, $period_id, $approval);
        }

        echo $update;
    }
}
