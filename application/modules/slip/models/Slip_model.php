<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Slip_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_noref($like, $id_majors)
    {

        $query = $this->db->query("SELECT MAX(RIGHT(kas_noref,2)) AS no_max FROM kas WHERE DATE(kas_input_date)=CURDATE() AND kas_majors_id = '$id_majors' AND kas_noref LIKE '$like%' AND kas_category = '2'")->row();

        if ($query) {
            $tmp = ((int)$query->no_max) + 1;
            $noref = sprintf("%02s", $tmp);
        } else {
            $noref = "01";
        }

        return date('dmy') . $noref;
    }

    function get_bcode($kas = array())
    {

        if (isset($kas['date'])) {
            $this->db->where('kas_date', $kas['date']);
        }

        if (isset($kas['noref'])) {
            $this->db->where('kas_date', $kas['date']);
        }

        $this->db->select('kas_noref, kas_date, account_code, account_description');

        $this->db->join('account', 'account.account_id = kas.kas_account_id', 'left');

        $res = $this->db->get('kas');

        return $res->row_array();
    }

    function get_print($id)
    {

        $this->db->where('gaji.gaji_id', $id);

        $this->db->where('majors_status', '1');

        $this->db->select('employee.employee_id, employee_nip, employee_name, employee_strata, employee_category, employee_start, employee_end');
        $this->db->select('position.position_majors_id, majors.majors_id, majors.majors_name, majors_short_name, majors_status');
        $this->db->select('employee.employee_position_id, position.position_id, position.position_code, position_name, majors_status');

        $this->db->select('akun.akun_employee_id, akun.akun_id, akun.akun_account_id');

        $this->db->select('gaji_id, gaji_period_id, gaji_month_id, gaji_pokok, gaji_potongan, gaji_jumlah, gaji_catatan, gaji_tanggal');

        // $this->db->select('potongan_slip_name, potongan_slip_setting_id,potongan_slip_nominal, potongan_slip_gaji_id');
        // $this->db->select('gaji_slip_name, gaji_slip_setting_id, gaji_slip_nominal, gaji_slip_gaji_id');
        $this->db->select('month_id, month_name');
        $this->db->select('period_id, period_start, period_end');

        $this->db->select('kredit_gaji_id, kredit_kas_noref');

        $this->db->join('position', 'position.position_id = employee.employee_position_id', 'left');
        $this->db->join('majors', 'majors.majors_id = employee.employee_majors_id', 'left');

        $this->db->join('akun', 'akun.akun_employee_id = employee.employee_id', 'left');

        $this->db->join('gaji', 'gaji.gaji_employee_id = employee.employee_id', 'left');

        // $this->db->join('gaji_slip', 'gaji_slip.gaji_slip_gaji_id = gaji.gaji_id', 'left');
        // $this->db->join('potongan_slip', 'potongan_slip.potongan_slip_gaji_id = gaji.gaji_id', 'left');

        $this->db->join('month', 'gaji.gaji_month_id = month.month_id', 'left');
        $this->db->join('period', 'gaji.gaji_period_id = period.period_id', 'left');

        $this->db->join('kredit', 'gaji.gaji_id = kredit.kredit_gaji_id', 'left');
        $this->db->join('kas', 'kredit.kredit_kas_noref = kas.kas_noref', 'left');
        $this->db->join('account', 'account.account_id = kas.kas_account_id', 'left');

        $res = $this->db->get('employee');

        return $res->row_array();
    }

    function get_print_gaji($id)
    {
        $this->db->where('gaji.gaji_id', $id);

        // $this->db->where('majors_status', '1');

        $this->db->select('gaji_slip_name, gaji_slip_setting_id, gaji_slip_nominal, gaji_slip_gaji_id');

        $this->db->join('gaji_slip', 'gaji_slip.gaji_slip_gaji_id = gaji.gaji_id', 'left');

        $res = $this->db->get('gaji');

        return $res->result_array();
    }

    function get_print_potongan($id)
    {
        $this->db->where('gaji.gaji_id', $id);

        // $this->db->where('majors_status', '1');

        $this->db->select('potongan_slip_name, potongan_slip_setting_id,potongan_slip_nominal, potongan_slip_gaji_id');

        $this->db->join('potongan_slip', 'potongan_slip.potongan_slip_gaji_id = gaji.gaji_id', 'left');

        $res = $this->db->get('gaji');

        return $res->result_array();
    }


    function get_history($data = array())
    {
        if (isset($data['gaji_month_id'])) {
            $this->db->where('gaji_month_id', $data['gaji_month_id']);
        }

        if (isset($data['gaji_period_id'])) {
            $this->db->where('gaji_period_id', $data['gaji_period_id']);
        }

        if (isset($data['gaji_employee_id'])) {
            $this->db->where('gaji_employee_id', $data['gaji_employee_id']);
        }

        $this->db->order_by('period.period_start', 'desc');
        $this->db->order_by('month.month_id', 'desc');

        $this->db->select('gaji_id, gaji_period_id, gaji_month_id, gaji_pokok, gaji_potongan, gaji_jumlah, gaji_tanggal');
        $this->db->select('kredit_gaji_id, kredit_kas_noref');
        $this->db->select('month_id, month_name');
        $this->db->select('period_id, period_start, period_end');
        $this->db->join('month', 'gaji.gaji_month_id = month.month_id', 'left');
        $this->db->join('period', 'gaji.gaji_period_id = period.period_id', 'left');
        $this->db->join('kredit', 'gaji.gaji_id = kredit.kredit_gaji_id', 'left');
        $this->db->join('kas', 'kredit.kredit_kas_noref = kas.kas_noref', 'left');
        $this->db->join('account', 'account.account_id = kas.kas_account_id', 'left');

        $res = $this->db->get('gaji');

        if (isset($data['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function delete_history($id, $noref)
    {
        $this->db->where('gaji_id', $id);
        $this->db->delete('gaji');
        $this->db->where('gaji_slip_gaji_id', $id);
        $this->db->delete('gaji_slip');
        $this->db->where('potongan_slip_gaji_id', $id);
        $this->db->delete('potongan_slip');
        $this->db->where('kredit_gaji_id', $id);
        $this->db->delete('kredit');
        $this->db->where('kas_noref', $noref);
        $this->db->delete('kas');
    }

    function add($data = array())
    {

        if (isset($data['gaji_id'])) {
            $this->db->set('gaji_id', $data['gaji_id']);
        }

        if (isset($data['gaji_pokok'])) {
            $this->db->set('gaji_pokok', $data['gaji_pokok']);
        }

        if (isset($data['gaji_potongan'])) {
            $this->db->set('gaji_potongan', $data['gaji_potongan']);
        }

        if (isset($data['gaji_jumlah'])) {
            $this->db->set('gaji_jumlah', $data['gaji_jumlah']);
        }

        if (isset($data['gaji_catatan'])) {
            $this->db->set('gaji_catatan', $data['gaji_catatan']);
        }

        if (isset($data['gaji_month_id'])) {
            $this->db->set('gaji_month_id', $data['gaji_month_id']);
        }

        if (isset($data['gaji_period_id'])) {
            $this->db->set('gaji_period_id', $data['gaji_period_id']);
        }
        if (isset($data['gaji_employee_id'])) {
            $this->db->set('gaji_employee_id', $data['gaji_employee_id']);
        }

        if (isset($data['gaji_tanggal'])) {
            $this->db->set('gaji_tanggal', $data['gaji_tanggal']);
        }

        if (isset($data['user_user_id'])) {
            $this->db->set('user_user_id', $data['user_user_id']);
        }

        if (isset($data['gaji_id'])) {
            $this->db->where('gaji_id', $data['gaji_id']);
            $this->db->update('gaji');
            $id = $data['gaji_id'];
        } else {
            $this->db->insert('gaji');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function set_slip_gaji($data = array())
    {

        if (isset($data['gaji_slip_id'])) {
            $this->db->set('gaji_slip_id', $data['gaji_slip_id']);
        }

        if (isset($data['gaji_slip_name'])) {
            $this->db->set('gaji_slip_name', $data['gaji_slip_name']);
        }

        if (isset($data['gaji_slip_setting_id'])) {
            $this->db->set('gaji_slip_setting_id', $data['gaji_slip_setting_id']);
        }

        if (isset($data['gaji_slip_nominal'])) {
            $this->db->set('gaji_slip_nominal', $data['gaji_slip_nominal']);
        }

        if (isset($data['gaji_slip_gaji_id'])) {
            $this->db->set('gaji_slip_gaji_id', $data['gaji_slip_gaji_id']);
        }

        $this->db->insert('gaji_slip');
        $id = $this->db->insert_id();

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function set_slip_potongan($data = array())
    {

        if (isset($data['potongan_slip_id'])) {
            $this->db->set('potongan_slip_id', $data['potongan_slip_id']);
        }

        if (isset($data['potongan_slip_name'])) {
            $this->db->set('potongan_slip_name', $data['potongan_slip_name']);
        }

        if (isset($data['potongan_slip_setting_id'])) {
            $this->db->set('potongan_slip_setting_id', $data['potongan_slip_setting_id']);
        }

        if (isset($data['potongan_slip_nominal'])) {
            $this->db->set('potongan_slip_nominal', $data['potongan_slip_nominal']);
        }

        if (isset($data['potongan_slip_gaji_id'])) {
            $this->db->set('potongan_slip_gaji_id', $data['potongan_slip_gaji_id']);
        }

        $this->db->insert('potongan_slip');
        $id = $this->db->insert_id();

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function save($paramskas)
    {

        $this->db->insert('kas', $paramskas);
    }

    function get_slip_gaji($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('gaji_setting_id', $params['id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('gaji_setting_employee_id', $params['employee_id']);
        }

        if (isset($params['gaji_setting_name'])) {
            $this->db->where('gaji_setting_name', $params['gaji_setting_name']);
        }

        if (isset($params['gaji_setting_default_id'])) {
            $this->db->where('gaji_setting_default_id', $params['gaji_setting_default_id']);
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
            $this->db->order_by('gaji_setting_id', 'asc');
        }


        $this->db->select('gaji_setting_id, gaji_setting_name, gaji_setting_default_id, gaji_setting_nominal');

        // $this->db->join('gaji_default', 'default_gaji_id = gaji_setting_default_id');
        // $this->db->join('employee', 'employee_id = gaji_setting_employee_id');
        $res = $this->db->get('gaji_setting');

        return $res->result_array();
    }

    function get_slip_potongan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('potongan_setting_id', $params['id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('potongan_setting_employee_id', $params['employee_id']);
        }

        if (isset($params['potongan_setting_name'])) {
            $this->db->where('potongan_setting_name', $params['potongan_setting_name']);
        }

        if (isset($params['potongan_setting_default_id'])) {
            $this->db->where('potongan_setting_default_id', $params['potongan_setting_default_id']);
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
            $this->db->order_by('potongan_setting_id', 'asc');
        }

        $this->db->select('potongan_setting_id, potongan_setting_name, potongan_setting_default_id, potongan_setting_nominal');

        // $this->db->join('employee', 'employee_id = potongan_setting_employee_id');
        $res = $this->db->get('potongan_setting');

        return $res->result_array();
    }
}
