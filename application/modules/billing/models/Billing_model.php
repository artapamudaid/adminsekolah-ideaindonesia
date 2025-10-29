<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Billing_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_student($params = array())
    {

        $this->db->select(
            '
			student.student_id,
			student.student_nis,
			student.student_nisn,
			student.student_parent_phone,
			student.student_full_name,
			student.student_status,
			class.class_id,
			student.class_class_id,
			class.class_name,
			class.majors_majors_id,
			student.majors_majors_id,
			majors.majors_id,
			majors.majors_short_name,
			SUM(COALESCE(CASE WHEN bulan.bulan_status = 0 THEN bulan.bulan_bill ELSE 0 END, 0)) AS total_bulan_bill,
			(COALESCE(bebas.bebas_bill, 0) - COALESCE(bebas.bebas_diskon, 0) - COALESCE(bebas.bebas_total_pay, 0)) AS bebas_sisa'
        );

        $this->db->from('student');
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('bulan', 'bulan.student_student_id = student.student_id', 'left');
        $this->db->join('bebas', 'bebas.student_student_id = student.student_id', 'left');
        $this->db->join('payment as paybul', 'paybul.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('payment as paybeb', 'paybeb.payment_id = bebas.payment_payment_id', 'left');

        if (isset($params['class_id'])) {
            $this->db->where('class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->group_start();
            $this->db->group_start();
            if (isset($params['month_start']) and isset($params['month_end'])) {
                $this->db->where('month_month_id >=', $params['month_start']);
                $this->db->where('month_month_id <=', $params['month_end']);
            }
            $this->db->where('paybul.period_period_id', $params['period_id']);
            $this->db->group_end();
            $this->db->or_where('paybeb.period_period_id', $params['period_id']);
            $this->db->group_end();
        }

        // Group by all non-aggregated columns
        $this->db->group_by('student.student_id');

        if ($params['status'] == 'belum') {
            $this->db->having('(total_bulan_bill + bebas_sisa)  >', 0);
        } else if ($params['status'] == 'lunas') {
            $this->db->having('(total_bulan_bill + bebas_sisa)  =', 0);
        }

        $this->db->order_by('majors.majors_id', 'ASC');
        $this->db->order_by('class.class_name', 'ASC');
        $this->db->order_by('student.student_nis', 'ASC');

        $query = $this->db->get();

        return $query->result_array();
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

        if (isset($params['student_status'])) {
            $this->db->where_in('student.student_status', $params['student_status']);
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

        if (isset($params['student_status'])) {
            $this->db->where_in('student.student_status', $params['student_status']);
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

        if (isset($params['student_status'])) {
            $this->db->where_in('student.student_status', $params['student_status']);
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
        $this->db->order_by('period.period_start', 'asc');

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
        $this->db->order_by('period.period_start', 'asc');

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

        if (isset($param['period_id'])) {
            $this->db->where('payment.period_period_id', $param['period_id']);
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
