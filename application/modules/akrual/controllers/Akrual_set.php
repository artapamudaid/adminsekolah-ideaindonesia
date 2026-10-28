<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Akrual_set extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('akrual/Akrual_model'));
    }

    public function index()
    {
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '') {
            $params['date_start'] = $q['ds'];
        } else {
            $params['date_start'] = date('Y-m-d');
        }

        if (isset($q['de']) && !empty($q['de']) && $q['de'] != '') {
            $params['date_end'] = $q['de'];
        } else {
            $params['date_end'] = date('Y-m-d');
        }

        if (isset($q['unit']) && !empty($q['unit']) && $q['unit'] != '') {
            $params['majors_id'] = $q['unit'];
        }

        if (isset($q['source']) && !empty($q['source']) && $q['source'] != 'all') {
            $params['source'] = $q['source'];
        }

        if (isset($q['status']) && !empty($q['status']) && $q['status'] != '') {
            $params['status'] = $q['status'];
        } else {
            $params['status'] = 'DRAFT';
        }

        $data['list'] = $this->Akrual_model->get_list_jurnal($params);

        $data['period']     = $this->Akrual_model->get_period();
        $data['majors']     = $this->Akrual_model->get_majors();
        $data['bulan']      = $this->Akrual_model->get_month();
        $data['payment']    = $this->Akrual_model->get_payment();

        $jenis_transaksi = array(
            'BULAN' => 'Transaksi Pembayaran Bulanan',
            'BEBAS' => 'Transaksi Pembayaran Bebas',
            'KAS'   => 'Transaksi Kas'
        );
        $data['jenis_transaksi']     = $jenis_transaksi;


        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['title']  = 'Posting Jurnal';
        $data['main']   = 'akrual/akrual_list';
        $this->load->view('manage/layout', $data);
    }

    public function posting()
    {
        $q = $this->input->get(NULL, TRUE);

        $data['q'] = $q;

        $params = array();

        if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '') {
            $params['date_start'] = $q['ds'];
        } else {
            $params['date_start'] = date('Y-m-d');
        }

        if (isset($q['de']) && !empty($q['de']) && $q['de'] != '') {
            $params['date_end'] = $q['de'];
        } else {
            $params['date_end'] = date('Y-m-d');
        }

        if (isset($q['unit']) && !empty($q['unit']) && $q['unit'] != '') {
            $params['majors_id'] = $q['unit'];
        }

        if (isset($q['source']) && !empty($q['source']) && $q['source'] != 'all') {
            $params['source'] = $q['source'];
        }

        if (isset($q['status']) && !empty($q['status']) && $q['status'] != '') {
            $params['status'] = $q['status'];
        } else {
            $params['status'] = 'DRAFT';
        }

        $posted = $this->Akrual_model->posting_jurnal($params);
        if ($posted) {
            $this->session->set_flashdata('success', 'Posting Jurnal Berhasil');
        } else {
            $this->session->set_flashdata('failed', 'Posting Jurnal Gagal');
        }

        // echo json_encode($params);
        redirect('manage/akrual');
    }

    function get_tagihan()
    {
        $id_periode     = $this->input->post('id_periode');
        $id_majors      = $this->input->post('id_majors');
        $dataTagihan    = $this->Akrual_model->get_tagihan($id_periode, $id_majors);

        echo ' <select name="pembayaran" id="pembayaran" class="form-control" onchange="toggleBulan()" required>
                    <option value="">--Pilih Jenis Pembayaran--</option>';
        foreach ($dataTagihan as $row) {
            echo '<option data-type="' . $row['payment_type'] . '" value="' . $row['payment_id'] . '">';
            echo $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'];
            echo '</option>';
        }
        echo '</select>';
    }

    function get_jurnal()
    {
        $ds         = $this->input->post('ds');
        $de         = $this->input->post('de');
        $majors_id  = $this->input->post('majors_id');
        $source     = $this->input->post('source');
        $status     = $this->input->post('status');

        if (isset($ds) && !empty($ds) && $ds != '') {
            $data['date_start'] = $ds;
        } else {
            $data['date_start'] = date('Y-m-d');
        }

        if (isset($de) && !empty($de) && $ds != '') {
            $data['date_end'] = $de;
        } else {
            $data['date_end'] = date('Y-m-d');
        }

        if (isset($majors_id) && !empty($majors_id) && $majors_id != 'all') {
            $data['majors_id'] = $majors_id;
        }

        if (isset($source) && !empty($source) && $source != 'all') {
            $data['source'] = $source;
        }

        if (isset($status) && !empty($status)) {
            $data['status'] = $status;
        } else {
            $data['status'] = 'DRAFT';
        }
    }
}
