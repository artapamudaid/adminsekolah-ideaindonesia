<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transfer_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array(
            'payment/Payment_model',
            'student/Student_model',
            'transfer/Transfer_model',
            'period/Period_model',
            'kredit/Kredit_model',
            'debit/Debit_model',
            'setting/Setting_model',
            'logs/Logs_model',
            'ltrx/Log_trx_model',
            'debit/Debit_trx_model',
            'kredit/Kredit_trx_model'
        ));
    }

    public function index($offset = NULL, $id = NULL)
    {

        $f = $this->input->get(NULL, TRUE);

        $data['f']  = $f;
        $params     = array();

        $acc = NULL;

        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
            $params['majors_id']      = $f['n'];
            $data['account']    = $this->db->query("SELECT * FROM account JOIN majors ON account.account_majors_id = majors.majors_id
                                                WHERE account_category = '2' AND account_majors_id = '" . $f['n'] . "' 
                                                AND account_code LIKE '1%' AND account_note IN (SELECT account_id FROM account 
                                                WHERE account_category = '0' AND account_majors_id = '" . $f['n'] . "' AND account_code LIKE '1%' 
                                                AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) ORDER BY account_code ASC")->result_array();

            $data['acc']    = $this->db->query("SELECT account_id, account_majors_id FROM account JOIN majors ON account.account_majors_id = majors.majors_id
                                                WHERE account_category = '2' AND account_code LIKE '5%' AND account_description LIKE '%Kirim Setoran%' 
                                                AND account_majors_id = '" . $f['n'] . "'")->row_array();
        }

        if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
            $params['account_id']    = $f['r'];
            $acc    = $f['r'];
        }

        $data['majors']     = $this->Student_model->get_majors();
        $data['akun']       = $this->db->query("SELECT * FROM account JOIN majors ON account.account_majors_id = majors.majors_id
                                                WHERE account_category = '2' AND account_code LIKE '1%' 
                                                AND account_note IN (SELECT account_id FROM account 
                                                WHERE account_category = '0' AND account_code LIKE '1%' 
                                                AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) AND account_id != '" . $acc . "' ORDER BY account_code ASC")->result_array();

        $data['history']    = $this->Transfer_model->get($params);

        $data['title']      = 'Transfer Kas';
        $data['main']       = 'transfer/transfer_list';
        $this->load->view('manage/layout', $data);
    }

    function find_account()
    {
        $id_majors = $this->input->post('id_majors');
        $majors = $this->db->query("SELECT * FROM account JOIN majors ON account.account_majors_id = majors.majors_id
                                    WHERE account_category = '2' AND account_majors_id = $id_majors 
                                    AND account_code LIKE '1%' AND account_note IN (SELECT account_id FROM account 
                                    WHERE account_category = '0' AND account_majors_id = $id_majors AND account_code LIKE '1%' 
                                    AND account_description LIKE '%Aktiva%' ORDER BY account_code ASC) ORDER BY account_code ASC")->result_array();

        echo '<label for="" class="col-sm-2 control-label">Akun Kas</label>
				<div class="col-sm-2">
					<select class="form-control" name="r" id="kas_account_id">';

        foreach ($majors as $row) {
            echo '<option  value="' . $row['account_id'] . '">' . $row['account_description'] . '</option>';
        }

        echo '</select>
					
				</div>
		        <span class="input-group-btn">
				<button class="btn btn-success" type="submit">Cari</button>
				</span>';
    }

    function transfer_process()
    {
        $this->db->trans_begin();

        $kredit_kas         = $this->input->post('kredit_kas_account_id');
        $kredit_account_id  = $this->input->post('kredit_account_id');
        $kredit_date        = $this->input->post('kredit_date');
        //$kredit_note        = $this->input->post('kredit_note');
        $kredit_val         = $this->input->post('kredit_val');
        $debit_kas          = $this->input->post('transfer_kas_account_id');

        $id_majors          = $this->input->post('kredit_majors_id');

        $periodActive = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();

        $date = date('Y-m-d');
        $bulan = $this->db->query("SELECT MONTH('$date') as n")->row_array();

        if ($bulan['n'] == '1') {
            $id_bulan = '7';
        } else if ($bulan['n'] == '2') {
            $id_bulan = '8';
        } else if ($bulan['n'] == '3') {
            $id_bulan = '9';
        } else if ($bulan['n'] == '4') {
            $id_bulan = '10';
        } else if ($bulan['n'] == '5') {
            $id_bulan = '11';
        } else if ($bulan['n'] == '6') {
            $id_bulan = '12';
        } else if ($bulan['n'] == '7') {
            $id_bulan = '1';
        } else if ($bulan['n'] == '8') {
            $id_bulan = '2';
        } else if ($bulan['n'] == '9') {
            $id_bulan = '3';
        } else if ($bulan['n'] == '10') {
            $id_bulan = '4';
        } else if ($bulan['n'] == '11') {
            $id_bulan = '5';
        } else if ($bulan['n'] == '12') {
            $id_bulan = '6';
        }

        //$unit
        $accountFrom         = $this->db->query("SELECT account_majors_id, majors_short_name, account_description FROM account
                                                JOIN majors ON majors.majors_id = account.account_majors_id
                                                WHERE account.account_id = '$kredit_kas'")->row_array();

        //$majors
        $accountTo             = $this->db->query("SELECT account_majors_id, majors_short_name, account_description FROM account
                                                JOIN majors ON majors.majors_id = account.account_majors_id
                                                WHERE account_id = '$debit_kas'")->row_array();

        $account            = $this->db->query("SELECT account_id FROM account 
                                                JOIN majors ON account.account_majors_id = majors.majors_id
                                                WHERE account_category = '2' AND account_code LIKE '4%' AND account_description LIKE '%Terima Setoran%' 
                                                AND account_majors_id = '" . $accountTo['account_majors_id'] . "'")->row_array();

        $debit_account_id   = $account['account_id'];

        //Transfer Balance
        $likeTransfer       = date('dmy');
        $tmpTransfer        = $this->Transfer_model->get_noref($likeTransfer);

        //$majorsKredit       = $this->Student_model->get_majors(array('id'=>$unit['account_majors_id']));
        $likeKredit         = 'JK' . $accountFrom['majors_short_name'] . date('dmy');
        $tmpKredit          = $this->Kredit_trx_model->get_noref($accountFrom['account_majors_id'], $likeKredit);
        $norefKredit        = 'JK' . $accountFrom['majors_short_name'] . $tmpKredit;

        //$majorsDebit       = $this->Student_model->get_majors(array('id'=>$majors['account_majors_id']));
        $likeDebit         = 'JM' . $accountTo['majors_short_name'] . date('dmy');
        $tmpDebit          = $this->Debit_trx_model->get_noref($accountTo['account_majors_id'], $likeDebit);
        $norefDebit        = 'JM' . $accountTo['majors_short_name'] . $tmpDebit;

        //Transfer Kas Keluar (Kredit)
        $paramkredit['kredit_kas_noref']      = $norefKredit;
        $paramkredit['kredit_date']           = $kredit_date;
        $paramkredit['kredit_value']          = $kredit_val;
        $paramkredit['kredit_kas_account_id'] = $kredit_kas;
        $paramkredit['kredit_desc']           = 'Transfer Kas dari Sumber Akun ' . $accountFrom['account_description'] . ' ke akun Tujuan ' . $accountTo['account_description'];
        $paramkredit['kredit_account_id']     = $kredit_account_id;
        $paramkredit['kredit_input_date']     = date('Y-m-d');
        $paramkredit['kredit_last_update']    = date('Y-m-d');
        $paramkredit['user_user_id']          = $this->session->userdata('uid');

        $paramskaskredit['kas_majors_id']     = $accountFrom['account_majors_id'];
        $paramskaskredit['kas_noref']         = $norefKredit;
        $paramskaskredit['kas_date']          = $kredit_date;
        $paramskaskredit['kas_period']        = $periodActive['period_id'];
        $paramskaskredit['kas_month_id']      = $id_bulan;
        $paramskaskredit['kas_account_id']    = $kredit_kas;
        $paramskaskredit['kas_note']          = 'Transfer Kas dari Sumber Akun ' . $accountFrom['account_description'] . ' ke akun Tujuan ' . $accountTo['account_description'];
        $paramskaskredit['kas_kredit']        = $kredit_val;
        $paramskaskredit['kas_category']      = '2';
        $paramskaskredit['kas_user_id']       = $user_id = $this->session->userdata('uid');
        $paramskaskredit['kas_input_date']    = $kredit_date;


        //Transfer Kas Masuk (Debit)
        $paramdebit['debit_kas_noref']        = $norefDebit;
        $paramdebit['debit_date']             = $kredit_date;
        $paramdebit['debit_value']            = $kredit_val;
        $paramdebit['debit_desc']             = 'Terima Transfer Kas dari Sumber akun ' . $accountFrom['account_description'] . ' ke akun Tujuan ' . $accountTo['account_description'];
        $paramdebit['debit_account_id']       = $debit_account_id;
        $paramdebit['debit_kas_account_id']   = $debit_kas;
        $paramdebit['debit_input_date']       = date('Y-m-d');
        $paramdebit['debit_last_update']      = date('Y-m-d');
        $paramdebit['user_user_id']           = $this->session->userdata('uid');

        $paramskasdebit['kas_majors_id']      = $accountTo['account_majors_id'];
        $paramskasdebit['kas_noref']          = $norefDebit;
        $paramskasdebit['kas_date']           = $kredit_date;
        $paramskasdebit['kas_period']         = $periodActive['period_id'];
        $paramskasdebit['kas_month_id']       = $id_bulan;
        $paramskasdebit['kas_account_id']     = $debit_kas;
        $paramskasdebit['kas_note']           = 'Terima Transfer Kas dari Sumber akun ' . $accountFrom['account_description'] . ' ke akun Tujuan ' . $accountTo['account_description'];
        $paramskasdebit['kas_debit']          = $kredit_val;
        $paramskasdebit['kas_category']       = '1';
        $paramskasdebit['kas_user_id']        = $user_id = $this->session->userdata('uid');
        $paramskasdebit['kas_input_date']     = $kredit_date;

        $this->Kredit_model->add($paramkredit);
        $this->Transfer_model->save_kas($paramskaskredit);
        $this->Debit_model->add($paramdebit);
        $this->Transfer_model->save_kas($paramskasdebit);

        $kredit = $this->db->query("SELECT kredit_id FROM kredit WHERE kredit_kas_noref = '$norefKredit'")->row_array();
        $debit  = $this->db->query("SELECT debit_id FROM debit WHERE debit_kas_noref = '$norefDebit'")->row_array();

        //Log Transfer Kas Keluar (Kredit)
        $paramstfkredit['log_tf_kredit_id']   = $kredit['kredit_id'];
        $paramstfkredit['log_tf_date']        = $kredit_date;
        $paramstfkredit['log_tf_account_id']  = $kredit_kas;
        $paramstfkredit['log_tf_balance_id']  = $tmpTransfer;
        $paramstfkredit['log_tf_majors_id']   = $accountFrom['account_majors_id'];
        $paramstfkredit['log_tf_note']        = 'Transfer Kas ke akun ' . $accountTo['account_description'] . ' Sebesar ' . $kredit_val . $this->input->post('transfer_detail');
        $paramstfkredit['log_tf_user_id']     = $user_id = $this->session->userdata('uid');
        $paramstfkredit['log_tf_last_update'] = date('Y-m-d H:i:s');
        $paramstfkredit['log_tf_last_update'] = date('Y-m-d H:i:s');

        //Log Transfer Kas Masuk (Debit)
        $paramstfdebit['log_tf_debit_id']    = $debit['debit_id'];
        $paramstfdebit['log_tf_date']        = $kredit_date;
        $paramstfdebit['log_tf_account_id']  = $debit_kas;
        $paramstfdebit['log_tf_balance_id']  = $tmpTransfer;
        $paramstfdebit['log_tf_majors_id']   = $accountTo['account_majors_id'];
        $paramstfdebit['log_tf_note']        = 'Terima Transfer Kas dari akun ' . $accountFrom['account_description'] . ' Sebesar ' . $kredit_val . $this->input->post('transfer_detail');
        $paramstfdebit['log_tf_user_id']     = $user_id = $this->session->userdata('uid');
        $paramstfdebit['log_tf_last_update'] = date('Y-m-d H:i:s');
        $paramstfdebit['log_tf_last_update'] = date('Y-m-d H:i:s');

        $this->Transfer_model->save_log_tf($paramstfkredit);
        $this->Transfer_model->save_log_tf($paramstfdebit);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $this->session->set_flashdata('success', 'Transfer Kas Berhasil Dilakukan');

        redirect('manage/transfer?n=' . $accountFrom['account_majors_id'] . '&r=' . $kredit_kas);
    }

    public function delete_kredit($kredit_id = NULL)
    {
        $query_satu = $this->db->query("SELECT log_tf_id AS id FROM log_tf WHERE log_tf_kredit_id = $kredit_id")->row_array();

        $unit = $this->input->post('delUnit');
        $akun = $this->input->post('delAkun');

        $log_id_satu = $query_satu['id'];
        $log_id_dua  = $query_satu['id'] + 1;

        $query_dua = $this->db->query("SELECT log_tf_debit_id AS debit_id FROM log_tf WHERE log_tf_id = $log_id_dua")->row_array();

        $debit_id = $query_dua['debit_id'];

        $this->db->query("DELETE FROM kredit WHERE kredit_id = $kredit_id");
        $this->db->query("DELETE FROM debit WHERE debit_id = $debit_id");
        $this->db->query("DELETE FROM log_tf WHERE log_tf_id = $log_id_satu");
        $this->db->query("DELETE FROM log_tf WHERE log_tf_id = $log_id_dua");

        $this->session->set_flashdata('success', 'Transfer Kas Berhasil Dihapus');

        redirect('manage/transfer?n=' . $unit . '&r=' . $akun);
    }

    public function delete_debit($debit_id = NULL)
    {
        $query_satu = $this->db->query("SELECT log_tf_id AS id FROM log_tf WHERE log_tf_debit_id = $debit_id")->row_array();

        $unit = $this->input->post('delUnit');
        $akun = $this->input->post('delAkun');

        $log_id_satu = $query_satu['id'];
        $log_id_dua  = $query_satu['id'] - 1;

        $query_dua = $this->db->query("SELECT log_tf_kredit_id AS kredit_id FROM log_tf WHERE log_tf_id = $log_id_dua")->row_array();

        $kredit_id = $query_dua['kredit_id'];

        $this->db->query("DELETE FROM kredit WHERE kredit_id = $kredit_id");
        $this->db->query("DELETE FROM debit WHERE debit_id = $debit_id");
        $this->db->query("DELETE FROM log_tf WHERE log_tf_id = $log_id_satu");
        $this->db->query("DELETE FROM log_tf WHERE log_tf_id = $log_id_dua");

        $this->session->set_flashdata('success', 'Transfer Kas Berhasil Dihapus');

        redirect('manage/transfer?n=' . $unit . '&r=' . $akun);
    }

    function log_tf()
    {

        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $params['start'] = $q['s'];
        }

        if (isset($q['e']) && !empty($q['e']) && $q['e'] != '') {
            $params['end'] = $q['e'];
        }

        // if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
        // $params['majors_id'] = $q['k'];
        // }

        // if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
        // $params['class_id']  = $q['c'];
        // }

        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);
        $data['transfer']   = $this->Transfer_model->get_transfer($params);

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['title'] = 'Laporan Transaksi Transfer Kas';
        $data['main'] = 'transfer/transfer_report_date';
        $this->load->view('manage/layout', $data);
    }

    public function transfer_date_excel()
    {
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
            $params['start'] = $q['s'];
        }

        if (isset($q['e']) && !empty($q['e']) && $q['e'] != '') {
            $params['end'] = $q['e'];
        }

        $data['class']      = $this->Student_model->get_class($params);
        $data['majors']     = $this->Student_model->get_majors($params);
        $data['transfer']   = $this->Transfer_model->get_transfer($params);

        $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $this->load->view('transfer/transfer_date_xls', $data);
    }


    function print_mutasi()
    {

        $this->load->helper(array('dompdf'));

        $f = $this->input->get(NULL, TRUE);

        $data['f']  = $f;
        $id_unit    = null;
        $id_akun    = null;
        $params     = array();

        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
            $params['majors_id']     = $f['n'];
            $id_unit                 = $f['n'];
        }

        if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
            $params['account_id']    = $f['r'];
            $id_akun                 = $f['r'];
        }

        $data['history']    = $this->Transfer_model->get($params);

        $unit       = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = $id_unit")->row_array();
        $akun       = $this->db->query("SELECT account_description FROM account WHERE account_id = $id_akun")->row_array();

        $data['unit'] = $unit['majors_short_name'];
        $data['akun'] = $akun['account_description'];

        $data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $html = $this->load->view('transfer/transfer_mutasi', $data, true);
        $data = pdf_create($html, 'Mutasi ' . $akun['account_description'] . ' [' . $unit['majors_short_name'] . ']', TRUE, 'A4', TRUE);
    }
}
