<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Hutang_pay_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('hutang_pay.hutang_pay_id', $params['id']);
        }

        if (isset($params['hutang_noref'])) {
            $this->db->where('hutang_noref', $params['hutang_noref']);
        }

        if (isset($params['date'])) {
            $this->db->where('hutang_pay_date', $params['date']);
        }

        if (isset($params['settinghutang_id'])) {
            $this->db->where('hutang.hutang_settinghutang_id', $params['settinghutang_id']);
        }

        if (isset($params['hutang_id'])) {
            $this->db->where('hutang_pay.hutang_pay_hutang_id', $params['hutang_id']);
        }

        if (isset($params['hutang_pay_input_date'])) {
            $this->db->where('hutang_pay_input_date', $params['hutang_pay_input_date']);
        }

        if (isset($params['hutang_pay_last_update'])) {
            $this->db->where('hutang_pay_last_update', $params['hutang_pay_last_update']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('hutang_pay_input_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('hutang_pay_input_date <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['date'])) {
            $this->db->where('hutang_pay_input_date', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('hutang_pay.hutang_pay_hutang_id');
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
            $this->db->order_by('account_id', 'asc');
            $this->db->order_by('hutang_pay_last_update', 'asc');
        }

        $this->db->select('hutang_pay.hutang_pay_id, hutang_pay_noref, hutang_pay_date, hutang_pay_note, hutang_pay_account_id, 
        hutang_pay_bill, hutang_pay_input_date, hutang_pay_last_update');
        $this->db->select('hutang_id, hutang_noref, hutang_bill, hutang_kreditur, hutang_date');
        $this->db->select('hutang_settinghutang_id, settinghutang.settinghutang_poshutang_id, majors_id');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code, account_description');

        $this->db->join('hutang', 'hutang.hutang_id = hutang_pay.hutang_pay_hutang_id', 'left');
        $this->db->join('settinghutang', 'settinghutang.settinghutang_id = hutang.hutang_settinghutang_id', 'left');
        $this->db->join('users', 'users.user_id = hutang_pay.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = hutang_pay.hutang_pay_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = account.account_majors_id', 'left');

        $res = $this->db->get('hutang_pay');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_sum($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('hutang_pay.hutang_pay_id', $params['id']);
        }

        if (isset($params['hutang_noref'])) {
            $this->db->where('hutang_noref', $params['hutang_noref']);
        }

        if (isset($params['date'])) {
            $this->db->where('hutang_pay_input_date', $params['date']);
        }

        if (isset($params['settinghutang_id'])) {
            $this->db->where('hutang.hutang_settinghutang_id', $params['settinghutang_id']);
        }

        if (isset($params['hutang_id'])) {
            $this->db->where('hutang_pay.hutang_pay_hutang_id', $params['hutang_id']);
        }
        if (isset($params['hutang_pay_input_date'])) {
            $this->db->where('hutang_pay_input_date', $params['hutang_pay_input_date']);
        }

        if (isset($params['hutang_pay_last_update'])) {
            $this->db->where('hutang_pay_last_update', $params['hutang_pay_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('hutang_pay_input_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('hutang_pay_input_date <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['date'])) {
            $this->db->where('hutang_pay_input_date', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('hutang_pay.hutang_pay_hutang_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        }

        $this->db->select('SUM(hutang_pay_bill) as hutang_dibayar');

        $this->db->join('hutang', 'hutang.hutang_id = hutang_pay.hutang_pay_hutang_id', 'left');

        $res = $this->db->get('hutang_pay');

        if (isset($params['id']) || isset($params['hutang_noref'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // Add and update to database
    function add($data = array())
    {

        if (isset($data['hutang_pay_id'])) {
            $this->db->set('hutang_pay_id', $data['hutang_pay_id']);
        }

        if (isset($data['hutang_pay_hutang_id'])) {
            $this->db->set('hutang_pay_hutang_id', $data['hutang_pay_hutang_id']);
        }

        if (isset($data['hutang_pay_date'])) {
            $this->db->set('hutang_pay_date', $data['hutang_pay_date']);
        }

        if (isset($data['hutang_pay_bill'])) {
            $this->db->set('hutang_pay_bill', $data['hutang_pay_bill']);
        }

        if (isset($data['hutang_pay_noref'])) {
            $this->db->set('hutang_pay_noref', $data['hutang_pay_noref']);
        }

        if (isset($data['hutang_pay_account_id'])) {
            $this->db->set('hutang_pay_account_id', $data['hutang_pay_account_id']);
        }

        if (isset($data['hutang_pay_note'])) {
            $this->db->set('hutang_pay_note', $data['hutang_pay_note']);
        }

        if (isset($data['user_user_id'])) {
            $this->db->set('user_user_id', $data['user_user_id']);
        }

        if (isset($data['hutang_pay_input_date'])) {
            $this->db->set('hutang_pay_input_date', $data['hutang_pay_input_date']);
        }

        if (isset($data['hutang_pay_last_update'])) {
            $this->db->set('hutang_pay_last_update', $data['hutang_pay_last_update']);
        }

        if (isset($data['hutang_pay_id'])) {
            $this->db->where('hutang_pay_id', $data['hutang_pay_id']);
            $this->db->update('hutang_pay');
            $id = $data['hutang_pay_id'];
        } else {
            $this->db->insert('hutang_pay');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }


    // Delete to database
    function delete($params = array())
    {

        if (isset($params['id'])) {
            $this->db->where('hutang_pay_hutang_id', $params['id']);
        }
        $this->db->delete('hutang_pay');
    }
}
