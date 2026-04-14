<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Akrual_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_period()
    {
        if (isset($params['id'])) {
            $this->db->where('period_id', $params['id']);
        }

        if (isset($params['status'])) {
            $this->db->where('period_status', $params['status']);
        }

        if (isset($params['period_start'])) {
            $this->db->where('period_start', $params['period_start']);
        }
        if (isset($params['period_end'])) {
            $this->db->where('period_end', $params['period_end']);
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
            $this->db->order_by('period_id', 'desc');
        }

        $this->db->select('period_id, period_start, period_end, period_status');
        $res = $this->db->get('period');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_majors($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('majors_id', $params['id']);
        }

        if (isset($params['majors_name'])) {
            $this->db->where('majors_name', $params['majors_name']);
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }

        if (isset($params['majors_short_name'])) {
            $this->db->where('majors_short_name', $params['majors_short_name']);
        }

        if (isset($params['majors_logo'])) {
            $this->db->where('majors_logo', $params['majors_logo']);
        }

        if (isset($params['majors_address'])) {
            $this->db->where('majors_address', $params['majors_address']);
        }

        if (isset($params['majors_phone'])) {
            $this->db->where('majors_phone', $params['majors_phone']);
        }

        if (isset($params['majors_wa_center'])) {
            $this->db->where('majors_wa_center', $params['majors_wa_center']);
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
            $this->db->order_by('majors_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('majors_id, majors_name, majors_short_name, majors_school_name, majors_status');
        $res = $this->db->get('majors');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_month($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('month_id', $params['id']);
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
            $this->db->order_by('month_id', 'asc');
        }

        $this->db->select('month_id, month_name');

        $res = $this->db->get('month');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_payment($params = array())
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
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = account.account_majors_id', 'left');
        $res = $this->db->get('payment');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
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

    public function get_list_akrual_bulan($filter = [])
    {
        $this->db->select("
            mj.majors_short_name,
            ps.pos_name,
            py.payment_type,
            pr.period_start,
            pr.period_end,
            mn.month_name,
            SUM(b.bulan_bill) AS total_tagihan,
            MAX(b.is_posted) AS is_posted
        ");
        $this->db->from('bulan b');
        $this->db->join('payment py', 'py.payment_id = b.payment_payment_id');
        $this->db->join('pos ps', 'ps.pos_id = py.pos_pos_id');
        $this->db->join('account ac', 'ac.account_id = ps.account_account_id');
        $this->db->join('majors mj', 'mj.majors_id = ac.account_majors_id');
        $this->db->join('period pr', 'pr.period_id = py.period_period_id');
        $this->db->join('month mn', 'mn.month_id = b.month_month_id');

        $this->db->where('is_posted', 0);

        if (!empty($filter['period_id'])) {
            $this->db->where('pr.period_id', $filter['period_id']);
        }

        if (!empty($filter['majors_id'])) {
            $this->db->where('mj.majors_id', $filter['majors_id']);
        }

        if (!empty($filter['month_month_id'])) {
            $this->db->where('b.month_month_id', $filter['month_month_id']);
        }

        if (!empty($filter['payment_payment_id'])) {
            $this->db->where('b.payment_payment_id', $filter['payment_payment_id']);
        }

        if (!empty($filter['payment_type'])) {
            $this->db->where('py.payment_type', $filter['payment_type']);
        }

        $this->db->group_by([
            'mj.majors_id',
            'py.payment_id',
            'pr.period_id',
            'b.month_month_id'
        ]);

        return $this->db->get()->result();
    }

    public function posting_akrual_bulan($filter = [])
    {
        $user_id = $this->session->userdata('uid');
        $tanggal = date('Y-m-d');

        $this->db->trans_begin();

        // 1. Ambil data agregat
        $list = $this->get_list_akrual_bulan($filter);

        if (empty($list)) {
            return [
                'status' => false,
                'message' => 'Tidak ada data untuk diposting'
            ];
        }

        // // 2. Simpan audit posting
        // $this->db->insert('akrual_posting', [
        //     'source_type'   => 'BULAN',
        //     'period_id'     => $filter['period_id'],
        //     'majors_id'     => $filter['majors_id'],
        //     'month_id'      => $filter['month_month_id'],
        //     'payment_id'    => $filter['payment_payment_id'],
        //     'total_nominal' => $list->total_tagihan,
        //     'tanggal'       => $tanggal,
        //     'user_id'       => $user_id
        // ]);

        foreach ($list as $row) {

            // 2. Cegah double posting
            if ($row->is_posted == 1) {
                $this->db->trans_rollback();
                return [
                    'status' => false,
                    'message' => 'Sebagian data sudah diposting'
                ];
            }

            // 3. Ambil akun POS (ulang untuk safety)
            $pos = $this->db
                ->select('ps.account_account_id, apen.account_code AS kodePendapatan ,ps.piutang_account_id, apiu.account_code AS kodePiutang')
                ->from('pos ps')
                ->join('payment py', 'py.pos_pos_id = ps.pos_id')
                ->join('account apiu', 'apiu.account_id = ps.piutang_account_id')
                ->join('account apen', 'apen.account_id = ps.account_account_id')
                ->where('py.payment_id', $filter['payment_payment_id'])
                ->get()
                ->row();

            if (!$pos) {
                $this->db->trans_rollback();
                return [
                    'status' => false,
                    'message' => 'POS / akun tidak ditemukan'
                ];
            }

            // 4. Insert LOG POSTING (batch akrual)
            $this->db->insert('akrual_posting', [
                'source_type'   => 'BULAN',
                'period_id'     => $filter['period_id'],
                'majors_id'     => $filter['majors_id'],
                'month_id'      => $filter['month_month_id'],
                'payment_id'    => $filter['payment_payment_id'],
                'total_nominal' => $row->total_tagihan,
                'tanggal'       => $tanggal,
                'user_id'       => $user_id
            ]);

            $posting_id = $this->db->insert_id();

            // 5. Insert JURNAL UMUM (HEADER)
            $jurnal = [
                'sekolah_id'   => 1, // helper kamu
                'majors_id'    => $filter['majors_id'],
                'keterangan'   => 'Akrual ' . $row->pos_name . ' Bulan ' . $row->month_name,
                'source_type'  => 'BULAN',
                'source_id'    => $posting_id,
                'noref'        => 'AKR-BLN-' . date('Ym') . '-' . $posting_id,
                'tanggal'      => date('Y-m-d'),
                'pencatatan'   => 'auto',
                'status_posting' => 'POSTED'
            ];
            $this->db->insert('jurnal_umum', $jurnal);
            $jurnal_id = $this->db->insert_id();

            // 6. JURNAL DETAIL - PIUTANG (DEBET)
            $this->db->insert('jurnal_umum_detail', [
                'id_jurnal'     => $jurnal_id,
                'account_code'  => $pos->kodePiutang,
                'debet'         => $row->total_tagihan,
                'kredit'        => 0
            ]);

            // 7. JURNAL DETAIL - PENDAPATAN (KREDIT)
            $this->db->insert('jurnal_umum_detail', [
                'id_jurnal'     => $jurnal_id,
                'account_code'  => $pos->kodePendapatan,
                'debet'         => 0,
                'kredit'        => $row->total_tagihan
            ]);

            // 8. UPDATE bulan → is_posted
            $this->db
                ->where('payment_payment_id', $filter['payment_payment_id'])
                ->where('month_month_id', $filter['month_month_id'])
                ->update('bulan', ['is_posted' => 1]);
        }

        // 9. Commit / Rollback
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return [
                'status' => false,
                'message' => 'Gagal posting jurnal'
            ];
        }

        $this->db->trans_commit();
        return [
            'status' => true,
            'message' => 'Posting jurnal akrual berhasil'
        ];
    }

    public function get_list_jurnal($filter = [])
    {
        $this->db->select("
           ja.id,ja.tanggal, mj.majors_short_name, ja.noref, ja.source_type, ja.keterangan, 
           SUM(jad.debet) AS total_debet,SUM(jad.kredit) AS total_kredit,
           ja.status_posting
        ");
        $this->db->from('jurnal_akrual ja');
        $this->db->join('majors mj', 'mj.majors_id = ja.majors_id');
        $this->db->join('jurnal_akrual_detail jad', 'jad.id_jurnal = ja.id');

        if (isset($filter['date_start']) and isset($filter['date_end'])) {
            $this->db->where('tanggal >=', $filter['date_start']);
            $this->db->where('tanggal <=', $filter['date_end']);
        }

        if (!empty($filter['unit'])) {
            $this->db->where('mj.majors_id', $filter['unit']);
        }

        if (!empty($filter['source'])) {
            $this->db->where('ja.source_type', $filter['source']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('ja.status_posting', $filter['status']);
        }

        $this->db->group_by([
            'ja.id',
            'ja.tanggal',
            'mj.majors_name',
            'ja.noref',
            'ja.source_type',
            'ja.keterangan',
            'ja.status_posting'
        ]);

        return $this->db->get()->result();
    }

    public function posting_jurnal($filter = [])
    {
        $this->db->set('status_posting', 'POSTED');

        if (isset($filter['date_start']) && isset($filter['date_end'])) {
            $this->db->where('tanggal >=', $filter['date_start']);
            $this->db->where('tanggal <=', $filter['date_end']);
        }

        if (!empty($filter['unit'])) {
            $this->db->where('majors_id', $filter['unit']);
        }

        if (!empty($filter['source'])) {
            $this->db->where('source_type', $filter['source']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('status_posting', $filter['status']);
        }

        return $this->db->update('jurnal_akrual');
    }
}
