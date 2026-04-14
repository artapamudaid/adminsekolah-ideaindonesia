<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Payment_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function cari_tagihan($id_periode)
    {
        $query = "SELECT * FROM `payment` JOIN period ON payment.period_period_id = period.period_id JOIN pos ON payment.pos_pos_id = pos.pos_id WHERE payment.payment_type = 'BULAN' AND payment.period_period_id = '$id_periode'";
        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_tagihan($id_periode, $id_majors)
    {
        $query = "SELECT * FROM `payment` JOIN period ON payment.period_period_id = period.period_id JOIN pos ON payment.pos_pos_id = pos.pos_id JOIN account ON account.account_id = pos.account_account_id JOIN majors ON majors.majors_id = account.account_majors_id WHERE payment.period_period_id = '$id_periode' AND majors.majors_id = '$id_majors' AND majors.majors_status = '1' ";
        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // Get From Databases
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('payment.payment_id', $params['id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['pos_id'])) {
            $this->db->where('payment.pos_pos_id', $params['pos_id']);
        }

        if (isset($params['search'])) {
            $this->db->like('pos_name', $params['search']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }

        if (isset($params['period_start'])) {
            $this->db->where('period_start', $params['period_start']);
        }

        if (isset($params['period_end'])) {
            $this->db->where('period_end', $params['period_end']);
        }


        if (isset($params['payment_input_date'])) {
            $this->db->where('payment_input_date', $params['payment_input_date']);
        }

        if (isset($params['payment_last_update'])) {
            $this->db->where('payment_last_update', $params['payment_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('payment_input_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('payment_input_date <=', $params['date_end'] . ' 23:59:59');
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }

        if (isset($params['status'])) {
            $this->db->where('payment_input_date', $params['status']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('payment_last_update', 'desc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('payment.payment_id, payment_type, payment_is_batch, payment_mode, payment_input_date, payment_last_update');
        $this->db->select('majors_id, majors_short_name');
        $this->db->select('pos_pos_id, pos_name, pos_description');
        $this->db->select('period_period_id, period.period_start, period.period_end, period.period_status');

        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('coa', 'coa.coa_id = pos.account_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = coa.coa_majors', 'left');
        $res = $this->db->get('payment');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    public function cek_jurnal_akrual($source_type, $source_id)
    {
        $this->db->select('id');
        $this->db->from('jurnal_akrual');
        $this->db->where('source_type', $source_type);
        $this->db->where('source_id', $source_id);

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->row()->id; // return id jurnal
        }

        return false;
    }

    public function update_status_tagihan_bulan($bulan_ids, $status = 'AKTIF')
    {
        $this->db->set('status_tagihan', $status);
        $this->db->where_in('bulan_id', $bulan_ids);
        $this->db->update('bulan');

        return $this->db->affected_rows();
    }

    public function get_data_tagihan_aktivasi($id_payment, $id_kelas = null, $id_bulan = null)
    {
        $this->db->select("
        bulan.bulan_id,
        bulan.month_month_id,
        CONCAT('Tagihan ', ps.pos_name,' Bulan ', m.month_name, ' A/N ', s.student_full_name) AS keterangan,
        py.payment_id,
        py.payment_type,
        apen.coa_code AS akun_pendapatan,
        apiu.coa_code AS akun_piutang,
        bulan.bulan_bill AS tagihan
    ");

        $this->db->from('bulan');
        $this->db->join('student s', 's.student_id = bulan.student_student_id');
        $this->db->join('class c', 'c.class_id = s.class_class_id');
        $this->db->join('month m', 'm.month_id = bulan.month_month_id');
        $this->db->join('payment py', 'py.payment_id = bulan.payment_payment_id');
        $this->db->join('pos ps', 'ps.pos_id = py.pos_pos_id');
        $this->db->join('coa apen', 'apen.coa_id = ps.account_account_id');
        $this->db->join('coa apiu', 'apiu.coa_id = ps.piutang_account_id');

        $this->db->where('bulan.payment_payment_id', $id_payment);
        $this->db->where('bulan.status_tagihan', 'NONAKTIF');

        if (!empty($id_kelas)) {
            $this->db->where('c.class_id', $id_kelas);
        }

        if (!empty($id_bulan)) {
            $this->db->where('bulan.month_month_id', $id_bulan);
        }

        return $this->db->get()->result_array();
    }

    public function insert_jurnal_akrual_tagihan($data, $sekolah_id, $majors_id)
    {
        // CEK DUPLIKAT
        $cek = $this->cek_jurnal_akrual('BULAN', $data['bulan_id']);
        if ($cek) {
            return $cek; // sudah ada, return id jurnal lama
        }

        // insert jurnal_akrual
        $insertJurnal = [
            'sekolah_id' => $sekolah_id,
            'majors_id' => $majors_id,
            'keterangan' => $data['keterangan'],
            'source_type' => 'BULAN',
            'source_id' => $data['bulan_id'],
            'noref' => 'TAG-' . $data['bulan_id'],
            'tanggal' => date('Y-m-d'),
            'pencatatan' => 'auto',
            'status_posting' => $data['status'] ?? 'POSTED',
            'keterangan_lainnya' => ''
        ];

        $this->db->insert('jurnal_akrual', $insertJurnal);
        $id_jurnal = $this->db->insert_id();

        // detail debet piutang
        $detail_debet = [
            'id_jurnal' => $id_jurnal,
            'account_code' => $data['akun_piutang'],
            'debet' => $data['tagihan'],
            'kredit' => 0
        ];

        // detail kredit pendapatan
        $detail_kredit = [
            'id_jurnal' => $id_jurnal,
            'account_code' => $data['akun_pendapatan'] ?? $data['akun_diterima_dimuka'],
            'debet' => 0,
            'kredit' => $data['tagihan']
        ];

        $this->db->insert('jurnal_akrual_detail', $detail_debet);
        $this->db->insert('jurnal_akrual_detail', $detail_kredit);

        return $id_jurnal;
    }

    public function aktivasi_tagihan_bulan($id_payment, $id_kelas = null, $id_bulan = null)
    {
        // ambil bulan_id yang statusnya NONAKTIF sesuai filter
        $this->db->select('bulan.bulan_id');
        $this->db->from('bulan');
        $this->db->join('student', 'student.student_id = bulan.student_student_id');
        $this->db->join('class', 'class.class_id = student.class_class_id');

        $this->db->where('bulan.status_tagihan', 'NONAKTIF');
        $this->db->where('bulan.payment_payment_id', $id_payment);

        // filter kelas jika ada
        if (!empty($id_kelas)) {
            $this->db->where('class.class_id', $id_kelas);
        }

        // filter bulan jika ada
        if (!empty($id_bulan)) {
            $this->db->where('bulan.month_month_id', $id_bulan);
        }

        $query = $this->db->get();
        $rows  = $query->result_array();

        if (!$rows) {
            return 0;
        }

        $ids = array_column($rows, 'bulan_id');

        // update berdasarkan bulan_id
        $this->db->set('status_tagihan', 'AKTIF');
        $this->db->where_in('bulan_id', $ids);
        $this->db->update('bulan');

        return $this->db->affected_rows();
    }

    public function get_bulan_mendatang_by_id($id_bulan = null)
    {
        $this->db->select("
            bulan.bulan_id,
            bulan.month_month_id,
            CONCAT('Tagihan ', ps.pos_name,' Bulan ', m.month_name, ' A/N ', s.student_full_name) AS keterangan,
            py.payment_id,
            py.payment_type,
            amuk.coa_majors,
            amuk.coa_code AS akun_diterima_dimuka,
            apiu.coa_code AS akun_piutang,
            bulan.bulan_bill AS tagihan
        ");

        $this->db->from('bulan');
        $this->db->join('student s', 's.student_id = bulan.student_student_id');
        $this->db->join('class c', 'c.class_id = s.class_class_id');
        $this->db->join('month m', 'm.month_id = bulan.month_month_id');
        $this->db->join('payment py', 'py.payment_id = bulan.payment_payment_id');
        $this->db->join('pos ps', 'ps.pos_id = py.pos_pos_id');
        $this->db->join('coa amuk', 'amuk.coa_id = ps.pendapatan_dimuka_account_id');
        $this->db->join('coa apiu', 'apiu.coa_id = ps.piutang_account_id');

        $this->db->where('bulan.bulan_id', $id_bulan);
        $this->db->where('bulan.status_tagihan', 'NONAKTIF');

        return $this->db->get()->row_array();
    }

    // Add and update to database
    function add($data = array())
    {

        if (isset($data['payment_id'])) {
            $this->db->set('payment_id', $data['payment_id']);
        }

        if (isset($data['payment_type'])) {
            $this->db->set('payment_type', $data['payment_type']);
        }

        if (isset($data['payment_mode'])) {
            $this->db->set('payment_mode', $data['payment_mode']);
        }

        if (isset($data['payment_is_batch'])) {
            $this->db->set('payment_is_batch', $data['payment_is_batch']);
        }

        if (isset($data['period_id'])) {
            $this->db->set('period_period_id', $data['period_id']);
        }

        if (isset($data['pos_id'])) {
            $this->db->set('pos_pos_id', $data['pos_id']);
        }

        if (isset($data['payment_input_date'])) {
            $this->db->set('payment_input_date', $data['payment_input_date']);
        }

        if (isset($data['payment_last_update'])) {
            $this->db->set('payment_last_update', $data['payment_last_update']);
        }

        if (isset($data['payment_id'])) {
            $this->db->where('payment_id', $data['payment_id']);
            $this->db->update('payment');
            $id = $data['payment_id'];
        } else {
            $this->db->insert('payment');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    // Delete all to database
    function delete_all()
    {
        $this->db->truncate('payment');
    }

    // Delete to database
    function delete($id)
    {
        $this->db->where('payment_id', $id);
        $this->db->delete('payment');
    }
}
