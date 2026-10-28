<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pos_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'pos/Pos_model', 'payment/Payment_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    // pos view in list
    public function index($offset = NULL)
    {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);
        $s = $this->input->get(NULL, TRUE);

        $data['f'] = $f;
        $data['s'] = $s;

        $params = array();
        // Nip
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['pos_name'] = $f['n'];
        }

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
        }

        $data['pos'] = $this->Pos_model->get($params);

        $majors_id = $this->session->userdata('umajorsid');

        if ($majors_id != '0') {
            $data['account'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '4%' AND account_category = '1' AND account_majors_id = '$majors_id'")->result();
            $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' AND account_majors_id = '$majors_id'")->result();
        } else {
            $data['account'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '4%' AND account_category = '1'")->result();
            $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2'")->result();
        }

        $config['base_url'] = site_url('manage/pos/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Pos Bayar';
        $data['main'] = 'pos/pos_list';
        $this->load->view('manage/layout', $data);
    }

    public function add_glob()
    {
        if ($_POST == TRUE) {
            $posName = sanitizeStrict($_POST['pos_name']);
            $posKet = sanitizeStrict($_POST['pos_description']);
            $accountID = sanitizeStrict($_POST['account_account_id']);
            $piutangID = sanitizeStrict($_POST['piutang_account_id']);

            $cpt = count($posName);

            $added = 0;
            $skipped = 0;

            for ($i = 0; $i < $cpt; $i++) {

                if (
                    empty($posName[$i]) || empty($posKet[$i]) ||
                    empty($accountID[$i]) || empty($piutangID[$i])
                ) {
                    $skipped++;
                    continue;
                }

                $colWhere = ["account_account_id" => $accountID[$i]];
                if ($this->General_model->checkDataExists('pos', $colWhere)) {
                    $skipped++;
                    continue;
                }

                $params['pos_name'] = $posName[$i];
                $params['pos_description'] = $posKet[$i];
                $params['account_account_id'] = $accountID[$i];
                $params['piutang_account_id'] = $piutangID[$i];

                $insert = $this->Pos_model->add($params);
                if (!$insert) {
                    $skipped++;
                    continue;
                }
                $added++;
            }

            if ($added > 1) {
                $msg = "$added data berhasil ditambahkan";
            } elseif ($added == 1) {
                $msg = "1 data berhasil ditambahkan";
            } else {
                $msg = "Tidak ada yang ditambahkan";
            }

            if ($skipped > 0) {
                $msg .= "<br>($skipped data dilewati)";
            }

            $this->session->set_flashdata('success', $msg);
        }
        redirect('manage/pos');
    }


    // Add pos and Update
    public function add($id = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pos_name', 'Pos Bayar', 'trim|required|xss_clean');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST and $this->form_validation->run() == TRUE) {

            if ($this->input->post('pos_id')) {
                $id = sanitizeStrict($this->input->post('pos_id'));
                $params['pos_id'] = $id;
            }

            $accountID = sanitizeStrict($this->input->post('account_account_id'));

            $colWhere = ["account_account_id" => $accountID];

            if (!empty($id)) {
                $colWhere["pos_id <>"] = $id;
            }
            if ($this->General_model->checkDataExists('pos', $colWhere)) {
                $this->session->set_flashdata('failed', "Akun sudah ada.");
                redirect('manage/pos/edit/' . $id);
            }

            $params['pos_name'] = sanitizeStrict($this->input->post('pos_name'));
            $params['pos_description'] = sanitizeStrict($this->input->post('pos_description'));
            $params['account_account_id'] = $accountID;
            $params['piutang_account_id'] = sanitizeStrict($this->input->post('account_piutang_id'));

            $status = $this->Pos_model->add($params);
            $paramsupdate['pos_id'] = $status;
            $this->Pos_model->add($paramsupdate);


            // activity log
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Pos Bayar',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:null;Title:' . $params['pos_name']
                )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' Pos Bayar berhasil');
            redirect('manage/pos');
        } else {
            if ($this->input->post('pos_id')) {
                redirect('manage/pos/edit/' . $this->input->post('pos_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $data['pos'] = $this->Pos_model->get(array('id' => $id));
                $majors_id = $this->session->userdata('umajorsid');
                if ($majors_id != '0') {
                    $data['account'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '4%' AND account_category = '1' AND account_majors_id = '$majors_id'")->result_array();
                    $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' AND account_majors_id = '$majors_id'")->result_array();
                } else {
                    $data['account'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '4%' AND account_category = '1'")->result_array();
                    $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2'")->result_array();
                }
            }
            $data['title'] = $data['operation'] . ' Pos Bayar';
            $data['main'] = 'pos/pos_add';
            $this->load->view('manage/layout', $data);
        }
    }


    // Delete to database
    public function delete($id = NULL)
    {
        if ($this->session->userdata('uroleid') != SUPERUSER && $this->session->userdata('uroleid') != 3) {
            redirect('manage');
        }
        if ($_POST) {

            $payment = $this->Payment_model->get(array('pos_id' => $id));

            if (count($payment) > 0) {
                $this->session->set_flashdata('failed', 'Data POS tidak dapat dihapus');
                redirect('manage/pos');
            }

            $this->Pos_model->delete($id);
            // activity log
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'Pos Bayar',
                    'log_action' => 'Hapus',
                    'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
                )
            );
            $this->session->set_flashdata('success', 'Hapus Pos Bayar berhasil');
            redirect('manage/pos');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('manage/pos/edit/' . $id);
        }
    }

    public function cari_akun()
    {
        $majors_id = $this->input->post('majors_id');

        $account = $this->db->query("SELECT * FROM account WHERE account_code LIKE '4%' AND account_category = '1' AND account_majors_id = '$majors_id'")->result_array();

        echo '<select required="" name="account_account_id[]" class="form-control">
				   <option value="">-Pilih Kode Akun-</option>';
        foreach ($account as $row) {
            echo '<option value="' . $row['account_id'] . '">';
            echo $row['account_code'];
            echo ' - ';
            echo $row['account_description'];
            echo '</option>';
        }
        echo '</select>';
    }

    public function cari_piutang()
    {
        $majors_id = $this->input->post('majors_id');

        $piutang = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' AND account_majors_id = '$majors_id'")->result_array();

        echo '<select required="" name="piutang_account_id[]" class="form-control">
				   <option value="">-Pilih Akun Piutang-</option>';
        foreach ($piutang as $row) {
            echo '<option value="' . $row['account_id'] . '">';
            echo $row['account_code'];
            echo ' - ';
            echo $row['account_description'];
            echo '</option>';
        }
        echo '</select>';
    }

    public function cari_akun_edit()
    {
        $majors_id = $this->input->post('majors_id');

        $account = $this->db->query("SELECT * FROM account WHERE account_code LIKE '4%' AND account_category = '1' AND account_majors_id = '$majors_id'")->result_array();

        echo '<select required="" name="account_account_id" class="form-control">
				   <option value="">-Pilih Kode Akun-</option>';
        foreach ($account as $row) {
            echo '<option value="' . $row['account_id'] . '">';
            echo $row['account_code'];
            echo ' - ';
            echo $row['account_description'];
            echo '</option>';
        }
        echo '</select>';
    }

    public function cari_piutang_edit()
    {
        $majors_id = $this->input->post('majors_id');

        $piutang = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' AND account_majors_id = '$majors_id'")->result_array();

        echo '<select required="" name="piutang_account_id" class="form-control">
				   <option value="">-Pilih Akun Piutang-</option>';
        foreach ($piutang as $row) {
            echo '<option value="' . $row['account_id'] . '">';
            echo $row['account_code'];
            echo ' - ';
            echo $row['account_description'];
            echo '</option>';
        }
        echo '</select>';
    }
}
