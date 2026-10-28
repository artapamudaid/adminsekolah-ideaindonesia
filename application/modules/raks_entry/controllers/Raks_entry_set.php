<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Raks_entry_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('period/Period_model', 'student/Student_model', 'raks_entry/Raks_entry_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    public function index($offset = NULL)
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

        $data['periodActive']   = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
        $data['period']         = $this->Period_model->get($params);

        $config['base_url'] = site_url('manage/raks_entry/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $majors_id = $this->session->userdata('umajorsid');
        if ($majors_id != 0) {
            $data['majors'][] = $this->Student_model->get_majors(array('id' => $majors_id));
        } else {
            $data['majors'] = $this->Student_model->get_majors();
        }


        if (!empty($params['majors_id']) and !empty($params['period_id'])) {
            $idMajor = $params['majors_id'];
            $data['dataMajors'] = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = $idMajor")->row_array();
            $idPeriode = $params['period_id'];
            $data['dataPeriod'] = $this->db->query("SELECT period_start, period_end FROM period WHERE period_id = $idPeriode")->row_array();
            $data['raks_entry'] = $this->Raks_entry_model->get_entry($params['majors_id'], $params['period_id']);
            // echo json_encode($data['raks_entry']);
            // exit;
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
        $data['title'] = 'RAKS Entry';
        $data['main'] = 'raks_entry/raks_entry_list';
        $this->load->view('manage/layout', $data);
    }

    function approval()
    {
        $data['majors'] = $this->Student_model->get_majors();
        $data['period'] = $this->Period_model->get(array());

        $data['title'] = 'RAKS Entry';
        $data['main'] = 'raks_entry/raks_approval';
        $this->load->view('manage/layout', $data);
    }

    function add_entry()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kode', 'Kode Entry', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nama', 'Nama Entry', 'trim|required|xss_clean');
        $this->form_validation->set_rules('parent', 'ID Parent', 'trim|required|xss_clean');
        $this->form_validation->set_rules('majors_id', 'ID Unit', 'trim|required|xss_clean');
        $this->form_validation->set_rules('period_id', 'Periode', 'trim|required|xss_clean');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

        if ($_POST) {
            if ($this->input->post('id')) {
                $params['id'] = $this->input->post('id');
            }

            $params['kode']         = $this->input->post('kode');
            $params['nama']         = $this->input->post('nama');
            $params['parent']       = $this->input->post('parent');
            $params['majors_id']    = $this->input->post('majors_id');
            $params['period_id']    = $this->input->post('period_id');
            $params['user']         = $this->session->userdata('uid');
            // echo json_encode($params);
            // exit;

            $this->Raks_entry_model->add($params);
            $this->session->set_flashdata('success', 'Tambah entry berhasil');
            redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
        } else {
            redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
        }
    }

    public function edit_entry()
    {
        if ($_POST) {
            $id_entry = $this->input->post("id_entry", true);
            $params['majors_id']    = $this->input->post('majors_id');
            $params['period_id']    = $this->input->post('period_id');

            if (!empty($id_entry)) {
                $nama = $this->input->post("nama", true);

                $this->db->set('nama', $nama);
                $this->db->where('id', $id_entry);
                $this->db->update('raks_entry');

                redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
            }
        }
    }

    public function delete($id = NULL)
    {
        if ($this->session->userdata('uroleid') != SUPERUSER) {
            redirect('manage');
        }
        if ($_POST) {

            if ($this->input->post('id')) {
                $params['id'] = $this->input->post('id');
            }

            // if (count($pos) > 0) {
            //     $this->session->set_flashdata('failed', 'Data Akun tidak dapat dihapus');
            //     redirect('manage/account/?m=' . $account['account_majors_id']);
            // }
            $params['majors_id'] = $this->input->post('majors_id');
            $params['period_id'] = $this->input->post('period_id');

            $del = $this->Raks_entry_model->delete($id);
            if ($del['status']) {
                $this->session->set_flashdata('success', 'Hapus entry berhasil');
            } else {
                $this->session->set_flashdata('failed', 'Tidak bisa dihapus karena memiliki detail');
            }
            redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
        } else {
            redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
        }
    }

    public function saveDetail()
    {
        if ($_POST) {
            $entry_id       = $this->input->post('entry_id');
            $detail_id       = $this->input->post('detail_id');
            $majors_id      = $this->input->post('majors_id');
            $period_id      = $this->input->post('period_id');
            $entryDetail    = $this->input->post('dataEntry');
            $sourceName     = $this->input->post('sourceName');
            $sourceValue    = $this->input->post('sourceValue');
            $executionTime  = $this->input->post('executionTime');

            $params['entry_id']         = $entry_id;
            $params['inventoryCode']    = $entryDetail['inventoryCode'];
            $params['nama']             = $entryDetail['detailName'];
            $params['qty']              = $entryDetail['qty'];
            $params['volume']           = $entryDetail['volumeDetail'];
            $params['unitPrice']        = $entryDetail['unitPrice'];
            $params['totalPrice']       = $entryDetail['totalPrice'];
            $params['executionTime']    = implode(",", $executionTime);

            if (!empty($detail_id)) {
                $params['id'] = $detail_id;
            }

            $saveID = $this->Raks_entry_model->saveDetail($params);
            if (!empty($sourceName) and !empty($sourceValue)  and $saveID) {
                $sourceDetail = array();
                for ($i = 0; $i < count($sourceName); $i++) {
                    $source['detail_id']    = $saveID;
                    $source['nama']         = $sourceName[$i];
                    $source['nominal']      = $sourceValue[$i];
                    array_push($sourceDetail, $source);
                }

                if (!empty($detail_id)) {
                    $this->Raks_entry_model->deleteSourceDetail($detail_id);
                }

                $this->Raks_entry_model->insertBatchSourceDetail($sourceDetail);
            }

            if ($saveID) {
                $this->Raks_entry_model->updateStatusProposalEntry($entry_id, 'CREATED');
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function getDetail($entry_id)
    {
        $data = $this->Raks_entry_model->getDetail($entry_id);
        if (count($data) == 1 and empty($data[0]['id'])) {
            $data = array();
        }

        echo json_encode($data);
    }

    function deleteDetail()
    {
        if ($_POST) {
            $id = $this->input->post('del_id');
            $this->Raks_entry_model->deleteDetail($id);

            $params['majors_id'] = $this->input->post('majors_id');
            $params['period_id'] = $this->input->post('period_id');

            redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
        } else {
            redirect('manage/raks_entry');
        }
    }

    function sendRequest()
    {
        $id_detail  = $this->input->post('detail_id');
        $status     = $this->input->post('status');

        $params = [
            "detail_id"         => $id_detail,
            "noPermintaan"      => $this->input->post('noPermintaan'),
            "status"            => $status,
            "majors_id"         => $this->input->post('majors_id'),
            "period_id"         => $this->input->post('period_id')
        ];

        $this->Raks_entry_model->updateStatusDetail($id_detail, $status);

        $req['id']              = 0;
        $req['detail_id']       = $params['detail_id'];
        $req['noPermintaan']    = $params['noPermintaan'];
        $req['user']            = $this->session->userdata('uid');
        $this->Raks_entry_model->saveRequest($req);

        redirect('manage/raks_entry/?m=' .  $params['majors_id'] . "&p=" . $params['period_id']);
    }

    function saveRequest()
    {
        if ($_POST) {
            $params = [
                "detail_id"         => $this->input->post('detail_id'),
                "majors_id"         => $this->input->post('majors_id'),
                "period_id"         => $this->input->post('period_id'),
                "noPermintaan"      => $this->input->post('noPermintaan'),
                "targetDate"        => $this->input->post('targetDate'),
                "requestType"       => $this->input->post('requestType'),
                "costCenter"        => $this->input->post('costCenter'),
                "notes"             => $this->input->post('notes'),
                "user"              => $this->session->userdata('uid')
            ];

            // $namaRincian = $this->input->post('namaRincian');
            $noPermintaan = $this->input->post('noPermintaan');

            if (!empty($_FILES['attachment1']['name'])) {
                $namaFile = 'attachment1-' . $noPermintaan . "-" . $params['detail_id'];
                $this->hapusFiles($namaFile);
                $extension = pathinfo($_FILES['attachment1']['name'], PATHINFO_EXTENSION);
                $params['attachment1'] = $namaFile . "." . $extension;
                $this->do_upload($name = 'attachment1', $fileName = $namaFile);
            }
            if (!empty($_FILES['attachment2']['name'])) {
                $namaFile = 'attachment2-' . $noPermintaan . "-" . $params['detail_id'];
                $this->hapusFiles($namaFile);
                $extension = pathinfo($_FILES['attachment2']['name'], PATHINFO_EXTENSION);
                $params['attachment2'] = $namaFile . "." . $extension;
                $this->do_upload($name = 'attachment2', $fileName = $namaFile);
            }
            if (!empty($_FILES['attachment3']['name'])) {
                $namaFile = 'attachment3-' . $noPermintaan . "-" . $params['detail_id'];
                $this->hapusFiles($namaFile);
                $extension = pathinfo($_FILES['attachment3']['name'], PATHINFO_EXTENSION);
                $params['attachment3'] = $namaFile . "." . $extension;
                $this->do_upload($name = 'attachment3', $fileName = $namaFile);
            }
            if (!empty($_FILES['attachment4']['name'])) {
                $namaFile = 'attachment4-' . $noPermintaan . "-" . $params['detail_id'];
                $this->hapusFiles($namaFile);
                $extension = pathinfo($_FILES['attachment4']['name'], PATHINFO_EXTENSION);
                $params['attachment4'] = $namaFile . "." . $extension;
                $this->do_upload($name = 'attachment4', $fileName = $namaFile);
            }
            if (!empty($_FILES['attachment5']['name'])) {
                $namaFile = 'attachment5-' . $noPermintaan . "-" . $params['detail_id'];
                $this->hapusFiles($namaFile);
                $extension = pathinfo($_FILES['attachment5']['name'], PATHINFO_EXTENSION);
                $params['attachment5'] = $namaFile . "." . $extension;
                $this->do_upload($name = 'attachment5', $fileName = $namaFile);
            }

            $res = $this->Raks_entry_model->saveRequest($params);
            $submitStatus = $this->input->post('submitStatus');
            if (isset($submitStatus) and $submitStatus == 1) {
                $this->Raks_entry_model->updateStatusDetail($params['detail_id'], 'SUBMITED');
            }
            echo $res;
        }
    }
    function approveRequest()
    {
        if ($_POST) {
            $idDetail   = $this->input->post('idDetail');
            $this->Raks_entry_model->updateStatusDetail($idDetail, 'APPROVED');
            $res = ['status' => 'success', 'message' => 'Approved berhasil.'];
            echo json_encode($res);
        }
    }

    function disapproveRequest()
    {
        $majors_id =  $this->input->post('majors_id');
        $period_id = $this->input->post('period_id');
        if ($_POST) {
            $params = [
                "detail_id"         => $this->input->post('detail_id'),
                "noPermintaan"      => $this->input->post('noPermintaan'),
                "notes"             => $this->input->post('notes')
            ];

            $dis = $this->Raks_entry_model->saveRequest($params);
            if ($dis) {
                $this->Raks_entry_model->updateStatusDetail($params['detail_id'], 'NEW');
            }
            $this->session->set_flashdata('success', 'Disapprove request berhasil');
            redirect('manage/raks_entry/?m=' .  $majors_id . "&p=" . $period_id);
        }
    }

    function deleteRequest()
    {
        if ($_POST) {
            $idDetail   = $this->input->post('idDetail');
            $noPermintaan   = $this->input->post('noPermintaan');

            $dataProposal = $this->Raks_entry_model->requestProposal($idDetail, $noPermintaan);
            $atc1 = pathinfo($dataProposal['attachment1'], PATHINFO_FILENAME);
            $atc2 = pathinfo($dataProposal['attachment2'], PATHINFO_FILENAME);
            $atc3 = pathinfo($dataProposal['attachment3'], PATHINFO_FILENAME);
            $atc4 = pathinfo($dataProposal['attachment4'], PATHINFO_FILENAME);
            $atc5 = pathinfo($dataProposal['attachment5'], PATHINFO_FILENAME);

            $proposal = $this->Raks_entry_model->deleteRequest($idDetail, $noPermintaan);
            if ($proposal['status'] === 'success') {
                if (!empty($atc1)) {
                    $delFile = $this->hapusFiles($atc1);
                }
                if (!empty($atc2)) {
                    $this->hapusFiles($atc2);
                }
                if (!empty($atc3)) {
                    $this->hapusFiles($atc3);
                }
                if (!empty($atc4)) {
                    $this->hapusFiles($atc4);
                }
                if (!empty($atc5)) {
                    $this->hapusFiles($atc5);
                }
            }
            echo json_encode($proposal);
        }
    }

    function requestProposal()
    {
        if ($_POST) {
            $idDetail       = $this->input->post('idDetail');
            $noPermintaan   = $this->input->post('noPermintaan');
            $proposal       = $this->Raks_entry_model->requestProposal($idDetail, $noPermintaan);
            echo json_encode($proposal);
        }
    }

    function do_upload($name = NULL, $fileName = NULL)
    {
        $this->load->library('upload');

        $config['upload_path'] = FCPATH . 'uploads/raks/';

        /* create directory if not exist */
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, TRUE);
        }

        $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png|xls|xlsx';
        $config['max_size'] = '3072';
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

    public function hapusFiles($file_name)
    {
        $directory = FCPATH . 'uploads/raks/';
        $res = array('status' => 'failed', 'message' => 'File deletion failed');
        $file_name = pathinfo(basename((string) $file_name), PATHINFO_FILENAME);
        $file_name = preg_replace('/[^A-Za-z0-9_.-]/', '', $file_name);

        if ($file_name) {
            // Cari semua file dengan nama yang sama dan ekstensi apa saja
            $files = glob($directory . $file_name . '.*'); // Misalnya: './uploads/file_name.*'

            if ($files) {
                $deleted_files = array();
                foreach ($files as $file) {
                    if (unlink($file)) {
                        $deleted_files[] = basename($file); // Simpan nama file yang berhasil dihapus
                    }
                }
                if (!empty($deleted_files)) {
                    $res['status']  = 'success';
                    $res['message'] = 'Files successfully deleted';
                    $res['deleted_files'] = $deleted_files; // Nama file yang berhasil dihapus
                } else {
                    $res['message'] = 'No files deleted';
                }
            } else {
                $res['message'] = 'No files found with the specified name';
            }
        } else {
            $res['message'] = 'File name is required';
        }

        return json_encode($res);
    }
}
