<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Anggaran_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('anggaran.anggaran_id', $params['id']);
        }

        if (isset($params['anggaran_period_id'])) {
            $this->db->where('anggaran.anggaran_period_id', $params['anggaran_period_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }
        if (isset($params['anggaran_input_date'])) {
            $this->db->where('anggaran_input_date', $params['anggaran_input_date']);
        }

        if (isset($params['anggaran_last_update'])) {
            $this->db->where('anggaran_last_update', $params['anggaran_last_update']);
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }

        if (isset($params['status'])) {
            $this->db->where('anggaran_input_date', $params['status']);
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
            $this->db->order_by('anggaran_last_update', 'desc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('anggaran_period_id, anggaran_account_id, anggaran.anggaran_id,
                            anggaran_input_date, anggaran_last_update');
        $this->db->select('majors_id, majors_short_name');
        $this->db->select('account_id, account_code, account_description');
        $this->db->select('period.period_start, period.period_end, period.period_status');

        $this->db->join('period', 'period.period_id = anggaran.anggaran_period_id', 'left');
        $this->db->join('account', 'account.account_id = anggaran.anggaran_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = account.account_majors_id', 'left');

        $res = $this->db->get('anggaran');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_anggaran($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('anggaran_detail_id', $params['id']);
        }

        if (isset($params['nominal'])) {
            $this->db->where('anggaran_detail_nominal', $params['nominal']);
        }

        if (isset($params['anggaran_id'])) {
            $this->db->where('anggaran_detail_anggaran_id', $params['anggaran_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('anggaran_detail_month_id', $params['month_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account.account_majors_id', $params['account_majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('anggaran_period_id', $params['period_id']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('month_id', 'asc');

        $this->db->select('anggaran_id, anggaran_detail_id, anggaran_detail_nominal');
        $this->db->select('period_id, period_start, period_end');
        $this->db->select('account_id, account_code, account_description');
        $this->db->select('month_id, month_name');


        $this->db->join('anggaran', 'anggaran.anggaran_id = anggaran_detail.anggaran_detail_anggaran_id', 'left');
        $this->db->join('month', 'month.month_id = anggaran_detail.anggaran_detail_month_id', 'left');
        $this->db->join('period', 'period.period_id = anggaran.anggaran_period_id', 'left');
        $this->db->join('account', 'account.account_id = anggaran.anggaran_account_id', 'left');

        $res = $this->db->get('anggaran_detail');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_detail($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('anggaran_detail_id', $params['id']);
        }

        if (isset($params['anggaran_detail_nominal'])) {
            $this->db->where('anggaran_detail_nominal', $params['anggaran_detail_nominal']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        $this->db->group_by('anggaran_id');

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('month_id', 'asc');


        $this->db->select('anggaran_detail.anggaran_detail_id, sum(anggaran_detail_nominal) as nominal');
        $this->db->select('month_name, anggaran_id, account_code, account_description');
        $this->db->select('period_start, period_end');

        $this->db->join('anggaran', 'anggaran.anggaran_id = anggaran_detail.anggaran_detail_anggaran_id', 'left');
        $this->db->join('month', 'month.month_id = anggaran_detail.anggaran_detail_month_id', 'left');
        $this->db->join('period', 'period.period_id = anggaran.anggaran_period_id', 'left');
        $this->db->join('account', 'account.account_id = anggaran.anggaran_account_id', 'left');

        $res = $this->db->get('anggaran_detail');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['anggaran_id'])) {
            $this->db->set('anggaran_id', $data['anggaran_id']);
        }

        if (isset($data['period_id'])) {
            $this->db->set('anggaran_period_id', $data['period_id']);
        }

        if (isset($data['account_id'])) {
            $this->db->set('anggaran_account_id', $data['account_id']);
        }

        if (isset($data['anggaran_input_date'])) {
            $this->db->set('anggaran_input_date', $data['anggaran_input_date']);
        }

        if (isset($data['anggaran_last_update'])) {
            $this->db->set('anggaran_last_update', $data['anggaran_last_update']);
        }

        if (isset($data['anggaran_id'])) {
            $this->db->where('anggaran_id', $data['anggaran_id']);
            $this->db->update('anggaran');
            $id = $data['anggaran_id'];
        } else {
            $this->db->insert('anggaran');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function add_detail($data = array())
    {

        if (isset($data['anggaran_detail_id'])) {
            $this->db->set('anggaran_detail_id', $data['anggaran_detail_id']);
        }

        if (isset($data['anggaran_id'])) {
            $this->db->set('anggaran_detail_anggaran_id', $data['anggaran_id']);
        }

        if (isset($data['month_id'])) {
            $this->db->set('anggaran_detail_month_id', $data['month_id']);
        }

        if (isset($data['nominal'])) {
            $this->db->set('anggaran_detail_nominal', $data['nominal']);
        }

        if (isset($data['anggaran_detail_id'])) {
            $this->db->where('anggaran_detail_id', $data['anggaran_detail_id']);
            $this->db->update('anggaran_detail');
            $id = $data['anggaran_detail_id'];
        } else {
            $this->db->insert('anggaran_detail');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('anggaran_id', $id);
        $this->db->delete('anggaran');
    }
}
