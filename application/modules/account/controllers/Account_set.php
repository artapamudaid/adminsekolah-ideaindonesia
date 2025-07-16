<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'account/Account_model', 'pos/Pos_model', 'payment/Payment_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    public function index($offset = NULL)
    {
        $this->load->library('pagination');

        $s = $this->input->get(NULL, TRUE);

        $data['s'] = $s;

        $params = array();

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
            //
        }

        $data['account'] = $this->Account_model->get($params);

        $config['base_url'] = site_url('manage/account/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Akun Biaya';
        $data['main'] = 'account/account_list';
        $this->load->view('manage/layout', $data);
    }

    public function add_glob()
    {
        if ($_POST == TRUE) {
            $accountCode = $_POST['account_code'];
            $accountKet = $_POST['account_description'];
            $accountNote = $_POST['account_note'];
            $accountCat = $_POST['account_category'];
            $accountMajorsId = $_POST['account_majors_id'];

            $majors   = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$accountMajorsId'")->row_array();
            $cekCode  = $this->db->query("SELECT COUNT(account_code) as numCode FROM account WHERE account_code = '$accountCode'")->row();

            if (substr($accountCode, 0, 3) == '5-5' && $accountCat != 0) {
                $akun = 'Biaya ' . $accountKet . ' ' . $majors['majors_short_name'];
            } else {
                $akun = $accountKet . ' ' . $majors['majors_short_name'];
            }

            if ($cekCode->numCode == '0') {
                $params['account_code']         = $accountCode;
                $params['account_description']  = $akun;
                $params['account_note']         = $accountNote;
                $params['account_category']     = $accountCat;
                $params['account_majors_id']    = $accountMajorsId;

                $this->Account_model->add($params);

                $account_id = $this->db->insert_id();

                $this->db->query("UPDATE account SET account_status = 0 WHERE account_id = '$accountNote'");

                if ($accountCat == '1') {

                    $akun_piutang = $this->akun_piutang($accountMajorsId);

                    if (isset($akun_piutang)) {

                        $check_account = $this->db->get_where('pos', array('account_account_id', $account_id))->num_rows();

                        if ($check_account == 0) {
                            $insert_pos = $this->db->query("INSERT INTO pos(pos_name, pos_description,
                                                            account_account_id, piutang_account_id)
                                                            VALUES
                                                            ('$accountKet','$accountKet','$account_id','$akun_piutang')");

                            if ($insert_pos) {
                                $pos_id = $this->db->insert_id();

                                $period = $this->db->query("SELECT period_id as id FROM period
                                                            WHERE period_status = '1'")->row_array();

                                $payment = array(
                                    'payment_type' => 'BULAN',
                                    'payment_mode' => 'TETAP',
                                    'payment_is_batch' => '0',
                                    'period_period_id' => $period['id'],
                                    'pos_pos_id' => $pos_id
                                );

                                $this->db->insert('payment', $payment);
                            }
                        }
                    }
                }

                $this->session->set_flashdata('success', ' Tambah Akun Berhasil');
                redirect('manage/account/?m=' . $accountMajorsId);
            } else {
                $this->session->set_flashdata('failed', ' Kode Akun Sudah Ada. Silahkan Buat Akun Lain');
                redirect('manage/account/?m=' . $accountMajorsId);
            }
        }
    }

    public function add($id = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('account_code', 'Akun Biaya', 'trim|required|xss_clean');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST and $this->form_validation->run() == TRUE) {

            if ($this->input->post('account_id')) {
                $params['account_id'] = $this->input->post('account_id');
            }

            $params['account_code'] = $this->input->post('account_code');
            $params['account_description'] = $this->input->post('account_description');
            $params['account_note'] = $this->input->post('account_note');
            $params['account_category'] = $this->input->post('account_category');
            $params['account_majors_id'] = $this->input->post('account_majors_id');

            $accountMajorsId = $this->input->post('account_majors_id');

            $accountNote = $this->input->post('account_note');

            $status = $this->Account_model->add($params);

            $paramsupdate['account_id'] = $status;

            $this->Account_model->add($paramsupdate);

            $this->db->query("UPDATE account SET account_status = 0 WHERE account_id = '$accountNote'");

            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Akun Biaya',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:null;Title:' . $params['account_code']
                )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' Akun Biaya berhasil');
            redirect('manage/account/?m=' . $accountMajorsId);
        } else {
            if ($this->input->post('account_id')) {
                redirect('manage/account/edit/' . $this->input->post('account_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $data['account'] = $this->Account_model->get(array('id' => $id));
                $data['majors'] = $this->Student_model->get_majors();
            }

            $data['title'] = $data['operation'] . ' Akun Biaya';
            $data['main'] = 'account/account_add';
            $this->load->view('manage/layout', $data);
        }
    }

    private function akun_piutang($id = null)
    {
        $akun_piutang = $this->db->query("SELECT account_id FROM account
                                            WHERE account_code LIKE '1-1%'
                                            AND account_description LIKE '%PIUTANG SANTRI%'
                                            AND account_majors_id ='$id'")->row_array();

        return $akun_piutang['account_id'];
    }

    public function delete($id = NULL)
    {
        if ($this->session->userdata('uroleid') != SUPERUSER) {
            redirect('manage');
        }
        if ($_POST) {

            $pos = $this->Pos_model->get(array('account_id' => $id));

            $account = $this->db->query("SELECT account_majors_id FROM account WHERE account_id = '$id'")->row_array();

            if (count($pos) > 0) {
                $this->session->set_flashdata('failed', 'Data Akun tidak dapat dihapus');
                redirect('manage/account/?m=' . $account['account_majors_id']);
            }

            $this->Account_model->delete($id);
            // activity log
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'Akun Bayar',
                    'log_action' => 'Hapus',
                    'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delCode')
                )
            );

            $this->session->set_flashdata('success', 'Hapus akun biaya berhasil');
            redirect('manage/account/?m=' . $account['account_majors_id']);
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('manage/account/edit/' . $id);
        }
    }
}
