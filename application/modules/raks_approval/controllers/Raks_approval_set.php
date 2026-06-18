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
        $this->load->model(array('period/Period_model', 'raks_approval/Raks_approval_model'));
    }

    function index()
    {
        $this->load->library('pagination');

        $s = $this->input->get(NULL, TRUE);

        $data['s'] = $s;

        $params = array();

        // Type RAKS ada dua. Unit dan Divisi.
        // RAKS unit itu berasal dari TK, SD, SMP.
        // RAKS divisi itu berasal dari YAYASAN.
        $data['typeRaks'] = '';

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['majors_id'] = $s['m'];
            $maj = $s['m'];
            $mj = $this->db->query("SELECT * FROM majors WHERE majors_status = 1 AND majors_id = $maj")->row_array();
            $data['typeRaks'] =  $mj['sekolah_id'];
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

        $uRole           = $this->session->userdata('uroleid');
        $roleSemuaMajors = [1, 11, 12, 13, 14];

        if (in_array($uRole, $roleSemuaMajors)) {
            $data['majors'] = $this->db->query("SELECT * FROM majors WHERE majors_status = 1")->result_array();
        } else {
            $majorsUser = $this->session->userdata('umajorsid');
            $data['majors'] = $this->db->query("SELECT * FROM majors WHERE majors_status = 1 AND majors_id = $majorsUser")->result_array();
        }

        if (!empty($params['majors_id']) and !empty($params['period_id'])) {
            $idMajor = $params['majors_id'];
            $data['dataMajors'] = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = $idMajor")->row_array();
            $idPeriode = $params['period_id'];
            $data['dataPeriod'] = $this->db->query("SELECT period_start, period_end FROM period WHERE period_id = $idPeriode")->row_array();
            $data['raks_entry'] = $this->Raks_approval_model->get_entry($params['majors_id'], $params['period_id']);
            $data['total_anggaran'] = $this->Raks_approval_model->get_total_anggaran($params['majors_id'], $params['period_id']);
            $data['status_approval'] = $this->Raks_approval_model->get_entry_status($params['majors_id'], $params['period_id']);
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

        $data['raks_entry'] = isset($data['raks_entry']) ? $data['raks_entry'] : array();

        $data['grade'] =  $grade;
        $data['title'] = 'RAKS Approval';
        $data['main'] = 'raks_approval/approval_list';
        $this->load->view('manage/layout', $data);
    }

    function approveRequest()
    {
        if ($_POST) {
            $typeRaks       = $this->input->post('typeRaks');
            $idDetail       = $this->input->post('idDetail');
            $statusDetail   = $this->input->post('status');

            $uRole       = $this->session->userdata('uroleid');
            $roleApprove = [1, 9, 11, 12, 13, 14];

            $updateStatus = '';
            $accept = false;

            if ($statusDetail == 'SUBMITED') {
                if ($typeRaks == '0') {
                    $updateStatus = 'KEPALA DIVISI';
                    if (in_array($uRole, [1, 13, 14])) {
                        $accept = true;
                    }
                } else if ($typeRaks == '1') {
                    $updateStatus = 'KEPALA SEKOLAH';
                    if (in_array($uRole, [1, 9])) {
                        $accept = true;
                    }
                }
            } else  if ($statusDetail == "KEPALA SEKOLAH") {
                $updateStatus = 'KEPALA AKADEMIK';
                if (in_array($uRole, [1, 11])) {
                    $accept = true;
                }
            } else if ($statusDetail == "KEPALA AKADEMIK") {
                $updateStatus = 'FINANCE';
                if (in_array($uRole, [1, 12])) {
                    $accept = true;
                }
            } else if ($statusDetail == "KEPALA DIVISI") {
                $updateStatus = 'FINANCE';
                if (in_array($uRole, [1, 12])) {
                    $accept = true;
                }
            }

            if ($accept) {
                $this->Raks_approval_model->updateStatusDetail($idDetail, $updateStatus);
                $res = [
                    'status' => 'success',
                    'detailId' => $idDetail,
                    'detailStatus' => $updateStatus,
                    'message' => 'Approved ' . $updateStatus . ' berhasil.'
                ];
            } else {
                $res = ['status' => 'failed', 'message' => 'Anda bukan sebagai ' . $updateStatus];
            }

            echo json_encode($res);
        }
    }

    function disapproveRequest()
    {
        $majors_id =  $this->input->post('majors_id');
        $period_id = $this->input->post('period_id');
        $typeRaks = $this->input->post('typeRaks');

        if ($_POST) {
            $params = [
                "detail_id"         => $this->input->post('detail_id'),
                "noPermintaan"      => $this->input->post('noPermintaan'),
                "notes"             => $this->input->post('notes')
            ];

            $accept = false;
            $this->Raks_approval_model->saveRequest($params);

            $statusDetail = $this->input->post('detail_status');

            $updateStatus = '';
            $uRole       = $this->session->userdata('uroleid');
            // $roleApprove = [1, 9, 11, 12, 13, 14];

            if ($statusDetail == "SUBMITED") {
                $updateStatus = 'NEW';

                if ($typeRaks == '0') {
                    if (in_array($uRole, [1, 13, 14])) {
                        $accept = true;
                    }
                } else if ($typeRaks == '1') {
                    // yang bisa membatalkan kepala sekolah adalah kepala akademik
                    if (in_array($uRole, [1, 9])) {
                        $accept = true;
                    }
                }
            } else if ($statusDetail == "KEPALA SEKOLAH") {
                $updateStatus = 'SUBMITED';

                // yang bisa membatalkan kepala sekolah adalah kepala akademik
                if (in_array($uRole, [1, 11])) {
                    $accept = true;
                }
            } else if ($statusDetail == "KEPALA AKADEMIK") {
                $updateStatus = 'KEPALA SEKOLAH';

                // yang bisa membatalkan kepala alademik adalah finance
                if (in_array($uRole, [1, 12])) {
                    $accept = true;
                }
            } else if ($statusDetail == "KEPALA DIVISI") {
                $updateStatus = 'SUBMITED';
                if (in_array($uRole, [1, 12])) {
                    $accept = true;
                }
            }


            if ($accept) {
                $this->Raks_approval_model->updateStatusDetail($params['detail_id'],  $updateStatus);
                $res = [
                    'status' => 'success',
                    'detailId' => $params['detail_id'],
                    'detailStatus' => $updateStatus,
                    'message' => 'Disapproved ' . $updateStatus . ' berhasil.'
                ];
            } else {
                $res = ['status' => 'failed', 'message' => 'Anda tidak memiliki akses'];
            }

            echo json_encode($res);
        }
    }
}
