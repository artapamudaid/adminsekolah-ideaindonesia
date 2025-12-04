<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Billing_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_tagihan_siswa($params = array())
    {
        // Default values
        $period_id    = isset($params['period_id']) ? $params['period_id'] : null;
        $period_start = isset($params['period_start']) ? $params['period_start'] : null;
        $month_start  = isset($params['month_start']) ? $params['month_start'] : 1;
        $month_end    = isset($params['month_end']) ? $params['month_end'] : 12;

        // Kondisi dasar untuk subquery
        $whereCondition = '';

        if ($period_id == 'all') {
            // Semua periode
            $whereCondition = "
                (
                    vts.idBulan = 0
                    OR (
                        vts.period_start = '$period_start'
                        AND vts.idBulan BETWEEN $month_start AND $month_end
                    )
                    OR vts.period_start <> '$period_start'
                )
            ";
        } else {
            // Periode tertentu
            $whereCondition = "
                vts.period_start <= '$period_start' AND
                (
                    vts.idBulan = 0
                    OR (
                        vts.period_start = '$period_start'
                        AND vts.idBulan BETWEEN $month_start AND $month_end
                    )
                    OR vts.period_start < '$period_start'
                )
            ";
        }

        // Subquery tagihan
        $sub_tagihan = "
            (
                SELECT 
                    vts.idStudent, 
                    SUM(vts.total) AS totalTagihan
                FROM v_tagihan_siswa vts
                WHERE $whereCondition
                GROUP BY vts.idStudent
            ) AS tagihan_siswa
        ";

        // Query utama
        $this->db->select([
            'std.student_id',
            'std.student_nis',
            'std.student_full_name',
            'std.class_class_id',
            'cls.class_name',
            'std.majors_majors_id',
            'mjr.majors_short_name',
            'COALESCE(tagihan_siswa.totalTagihan, 0) AS total_tagihan'
        ])
            ->from('student std')
            ->join('class cls', 'cls.class_id = std.class_class_id')
            ->join('majors mjr', 'mjr.majors_id = std.majors_majors_id')
            ->join($sub_tagihan, 'tagihan_siswa.idStudent = std.student_id', 'left');

        // Filter berdasarkan class_id
        if (!empty($params['class_id'])) {
            $this->db->where_in('std.class_class_id', (array)$params['class_id']);
        }

        // Filter berdasarkan majors_id
        if (!empty($params['majors_id'])) {
            $this->db->where_in('std.majors_majors_id', (array)$params['majors_id']);
        }

        $this->db->where_in('std.student_status', [1]);

        // Grouping
        $this->db->group_by([
            'std.student_id',
            'std.student_nis',
            'std.student_full_name',
            'std.class_class_id',
            'cls.class_name',
            'std.majors_majors_id',
            'mjr.majors_short_name'
        ]);

        $this->db->order_by("std.student_full_name ASC");

        return $this->db->get()->result_array();
    }

    public function get_rincian_tagihan($params = array())
    {
        $student_id    = isset($params['student_id']) ? (int)$params['student_id'] : 0;
        $period_id     = isset($params['period_id']) ? $params['period_id'] : null; // bisa 'all' atau id
        $period_start  = isset($params['period_start']) ? $params['period_start'] : null; // misal 2025
        $month_start   = isset($params['month_start']) ? (int)$params['month_start'] : 1;
        $month_end     = isset($params['month_end']) ? (int)$params['month_end'] : 12;

        // pastikan nilai valid
        if ($month_start < 1) $month_start = 1;
        if ($month_end > 12) $month_end = 12;
        if ($month_end < $month_start) $month_end = $month_start;

        // bangun kondisi WHERE sesuai logika:
        // - jika period_id == 'all' -> ambil semua period_start,
        //   tetapi BETWEEN hanya diterapkan pada baris dengan period_start = $period_start
        // - jika period_id != 'all' -> ambil period_start <= $period_start,
        //   dan BETWEEN hanya untuk period_start = $period_start
        if ($period_id === 'all' || $period_id == 'all') {
            $where = "
                WHERE idStudent = {$student_id}
                AND (
                    idBulan = 0
                    OR (
                        period_start = " . $this->db->escape($period_start) . "
                        AND idBulan BETWEEN {$month_start} AND {$month_end}
                    )
                    OR period_start <> " . $this->db->escape($period_start) . "
                )
            ";
        } else {
            // periode spesifik: batasi sampai period_start yang dipilih
            $where = "
                WHERE idStudent = {$student_id}
                AND period_start <= " . $this->db->escape($period_start) . "
                AND (
                    idBulan = 0
                    OR (
                        period_start = " . $this->db->escape($period_start) . "
                        AND idBulan BETWEEN {$month_start} AND {$month_end}
                    )
                    OR period_start < " . $this->db->escape($period_start) . "
                )
            ";
        }

        $sql = "
            SELECT 
                idStudent, 
                period_start,
                period_end,
                idBulan,
                keterangan,
                total
            FROM v_tagihan_siswa
            $where
            ORDER BY period_start DESC, idBulan ASC
        ";

        // debug: jika mau cek query terakhir saat error, kamu bisa uncomment baris di bawah ini
        // log_message('debug', 'SQL get_rincian_tagihan: ' . $sql);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPosBayar($params = array())
    {
        $this->db->select('pos.pos_id, pos.pos_name');
        $this->db->from('pos');
        $this->db->join('account ac', 'ac.account_id = pos.account_account_id');

        if (isset($params['majors_id'])) {
            $this->db->where('ac.account_majors_id', $params['majors_id']);
        }

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function getKelas($params = array())
    {
        $this->db->select('class.class_id, class.class_name');
        $this->db->from('class');

        if (isset($params['majors_id'])) {
            $this->db->where('class.majors_majors_id', $params['majors_id']);
        }

        $this->db->order_by('class.class_name', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function getTagihanPerKelas($params = array())
    {
        $query1 = $this->db->select('ps.pos_id, ps.pos_name, c.class_id, c.class_name, SUM(b.bulan_bill) AS totalTagihan', false)
            ->from('bulan b')
            ->join('student s', 's.student_id = b.student_student_id')
            ->join('class c', 'c.class_id = s.class_class_id')
            ->join('payment py', 'py.payment_id = b.payment_payment_id')
            ->join('pos ps', 'ps.pos_id = py.pos_pos_id')
            ->where('b.bulan_status', 0)
            ->where('b.bulan_bill >', 0)
            ->where('s.majors_majors_id', $params['majors_id'])
            ->where('py.period_period_id', $params['period_id'])
            ->group_by(['ps.pos_name', 'c.class_name'])
            ->get_compiled_select();

        $query2 = $this->db->select('ps.pos_id, ps.pos_name, c.class_id, c.class_name, SUM(bb.bebas_bill - bb.bebas_total_pay) AS totalTagihan', false)
            ->from('bebas bb')
            ->join('student s', 's.student_id = bb.student_student_id')
            ->join('class c', 'c.class_id = s.class_class_id')
            ->join('payment py', 'py.payment_id = bb.payment_payment_id')
            ->join('pos ps', 'ps.pos_id = py.pos_pos_id')
            ->where('s.majors_majors_id', $params['majors_id'])
            ->where('py.period_period_id', $params['period_id'])
            ->group_by(['ps.pos_name', 'c.class_name'])
            ->get_compiled_select();

        $finalQuery = $this->db->query($query1 . " UNION " . $query2);
        $result = $finalQuery->result_array();

        return $result;
    }

    function get_tagihan_bulan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }
        if (isset($params['student_id'])) {
            $this->db->where('student.student_id', $params['student_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        //$this->db->distinct();

        $this->db->order_by('bulan.payment_payment_id', 'asc');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');
        $this->db->where('payment_is_batch', '0');

        $this->db->where('bulan.bulan_status', '0');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');
        $this->db->select('payment_payment_id, period_period_id, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_tagihan_bebas($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bebas.bebas_id', $params['id']);
        }
        if (isset($params['student_id'])) {
            $this->db->where('student.student_id', $params['student_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        //$this->db->distinct();

        $this->db->order_by('bebas.payment_payment_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->where('(bebas.bebas_bill - bebas.bebas_diskon) != bebas.bebas_total_pay');

        $this->db->select('bebas.bebas_id, bebas_bill, bebas_diskon, bebas_total_pay, bebas_input_date, bebas_last_update');
        $this->db->select('payment_payment_id, period_period_id, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('account_id, account_code');

        $this->db->join('student', 'student.student_id = bebas.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bebas.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bebas');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_tagihan_paket($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('student.student_id', $params['student_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        //$this->db->distinct();

        $this->db->group_by('month_month_id');

        $this->db->order_by('batchpayment.id', 'asc');
        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');
        $this->db->where('payment_is_batch', '1');

        $this->db->where('bulan.bulan_status', '0');

        $this->db->select('name');
        $this->db->select('SUM(bulan_bill) AS paket_bill');
        $this->db->select('period_start, period_end');
        $this->db->select('month_month_id, month_name');

        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');
        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('batch_item', 'payment.payment_id = batch_item.batch_item_payment_id', 'left');
        $this->db->join('batchpayment', 'batchpayment.id = batch_item.batch_item_batchpayment_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');

        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_tagihan_bulan_lama($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }
        if (isset($param['student_id'])) {
            $this->db->where('student.student_id', $param['student_id']);
        }

        if (isset($param['period_id'])) {
            $this->db->where_in('payment.period_period_id', $param['period_id']);
        } else {
            $this->db->where_in('payment.period_period_id', '');
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['majors_id'])) {
            $this->db->where('student.majors_majors_id', $param['majors_id']);
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        //$this->db->distinct();
        $this->db->order_by('period.period_start', 'desc');

        $this->db->order_by('bulan.payment_payment_id', 'asc');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->where('student_status', '1');

        $this->db->where('bulan.bulan_status', '0');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');
        $this->db->select('payment_payment_id, period_period_id, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_tagihan_bebas_lama($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bebas.bebas_id', $param['id']);
        }
        if (isset($param['student_id'])) {
            $this->db->where('student.student_id', $param['student_id']);
        }

        if (isset($param['period_id'])) {
            $this->db->where_in('payment.period_period_id', $param['period_id']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['majors_id'])) {
            $this->db->where('student.majors_majors_id', $param['majors_id']);
        }

        //$this->db->distinct();
        $this->db->order_by('period.period_start', 'desc');

        $this->db->order_by('bebas.payment_payment_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->where('student_status', '1');

        $this->db->where('(bebas.bebas_bill - bebas.bebas_diskon) != bebas.bebas_total_pay');

        $this->db->select('bebas.bebas_id, bebas_bill, bebas_diskon, bebas_total_pay, bebas_input_date, bebas_last_update');
        $this->db->select('payment_payment_id, period_period_id, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('account_id, account_code');

        $this->db->join('student', 'student.student_id = bebas.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bebas.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bebas');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }



    function get_tagihan_paket_lama($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }

        if (isset($param['student_id'])) {
            $this->db->where('student.student_id', $param['student_id']);
        }

        if (isset($param['period_id'])) {
            $this->db->where_in('payment.period_period_id', $param['period_id']);
        } else {
            $this->db->where_in('payment.period_period_id', '');
        }

        // if (isset($param['period_id'])) {
        //     $this->db->where('payment.period_period_id', $param['period_id']);
        // }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['majors_id'])) {
            $this->db->where('student.majors_majors_id', $param['majors_id']);
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        //$this->db->distinct();

        $this->db->group_by('month_month_id');

        $this->db->order_by('batchpayment.id', 'asc');
        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');
        $this->db->where('payment_is_batch', '1');

        $this->db->where('bulan.bulan_status', '0');

        $this->db->select('name');
        $this->db->select('SUM(bulan_bill) AS paket_bill');
        $this->db->select('period_start, period_end');
        $this->db->select('month_month_id, month_name');

        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');
        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('batch_item', 'payment.payment_id = batch_item.batch_item_payment_id', 'left');
        $this->db->join('batchpayment', 'batchpayment.id = batch_item.batch_item_batchpayment_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');

        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }
}
