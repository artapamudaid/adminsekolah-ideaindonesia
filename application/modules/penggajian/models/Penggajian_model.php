<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penggajian_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array()) {

        if (isset($params['id'])) {
            $this->db->where('employee.employee_id', $params['id']);
        }
        if (isset($params['employee_id'])) {
            $this->db->where('employee.employee_id', $params['employee_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('employee.employee_id', $params['employee_id']);
        }

        if(isset($params['employee_search']))
        {
            $this->db->where('employee_nip', $params['employee_search']);
            $this->db->or_like('employee_name', $params['employee_search']);
        }

        if (isset($params['employee_nip'])) {
            $this->db->where('employee.employee_nip', $params['employee_nip']);
        }

        if (isset($params['nip'])) {
            $this->db->like('employee_nip', $params['nip']);
        }

        if (isset($params['password'])) {
            $this->db->like('employee_password', $params['password']);
        }

        if (isset($params['employee_name'])) {
            $this->db->where('employee.employee_name', $params['employee_name']);
        }

        if (isset($params['status'])) {
            $this->db->where('employee.employee_status', $params['status']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('employee.majors_majors_id', $params['majors_id']);
        }

        if(isset($params['group']))
        {

            $this->db->group_by('majors.majors_id'); 

        }
        
        if($this->session->userdata('umajorsid') != '0')
        {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
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
            $this->db->order_by('employee_nip', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('employee.employee_id, employee_nip, employee_name, employee_strata, employee_majors_id, employee_category, employee_start, employee_end, employee_photo');
        $this->db->select('position.position_majors_id, majors.majors_id, majors.majors_name, majors_short_name, majors_status');
        $this->db->select('employee.employee_position_id, position.position_id, position.position_code, position_name, majors_status');
        
        $this->db->select('akun.akun_employee_id, akun.akun_id, akun.akun_account_id');
        
        $this->db->join('position', 'position.position_id = employee.employee_position_id', 'left');
        $this->db->join('majors', 'majors.majors_id = employee.employee_majors_id', 'left');
        
        $this->db->join('akun', 'akun.akun_employee_id = employee.employee_id', 'left');
        
        $res = $this->db->get('employee');

        if (isset($params['id'])) {
            return $res->row_array();
        } else if (isset($params['employee_nis'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }

    }

    // function set_premier($data = array()) {

    //     if (isset($data['employee_id'])) {
    //         $this->db->set('premier_employee_id', $data['employee_id']);
    //     }

    //     if (isset($data['premier_id'])) {
    //         $this->db->set('premier_id', $data['premier_id']);
    //     }

    //     if (isset($data['premier_pokok'])) {
    //         $this->db->set('premier_pokok', $data['premier_pokok']);
    //     }

    //     if (isset($data['premier_hamas'])) {
    //         $this->db->set('premier_hamas', $data['premier_hamas']);
    //     }
        
    //     if (isset($data['premier_transport'])) {
    //         $this->db->set('premier_transport', $data['premier_transport']);
    //     }
        
    //     if (isset($data['premier_kinerja'])) {
    //         $this->db->set('premier_kinerja', $data['premier_kinerja']);
    //     }
        
    //     if (isset($data['premier_gty'])) {
    //         $this->db->set('premier_gty', $data['premier_gty']);
    //     }
        
    //     if (isset($data['premier_struktural'])) {
    //         $this->db->set('premier_struktural', $data['premier_struktural']);
    //     }
        
    //     if (isset($data['premier_fungsional'])) {
    //         $this->db->set('premier_fungsional', $data['premier_fungsional']);
    //     }
        
    //     if (isset($data['premier_anis'])) {
    //         $this->db->set('premier_anis', $data['premier_anis']);
    //     }
        
    //     if (isset($data['premier_bpjs'])) {
    //         $this->db->set('premier_bpjs', $data['premier_bpjs']);
    //     }
        
    //     if (isset($data['premier_bpjstk'])) {
    //         $this->db->set('premier_bpjstk', $data['premier_bpjstk']);
    //     }
        
    //     if (isset($data['premier_bpjskr'])) {
    //         $this->db->set('premier_bpjskr', $data['premier_bpjskr']);
    //     }
        
    //     if (isset($data['premier_tambahan'])) {
    //         $this->db->set('premier_tambahan', $data['premier_tambahan']);
    //     }
        
    //     if (isset($data['premier_potongan'])) {
    //         $this->db->set('premier_potongan', $data['premier_potongan']);
    //     }
        
    //     $ie = $data['employee_id'];
        
    //     $query = $this->db->query("SELECT COUNT(premier_id) as numData FROM premier WHERE premier_employee_id = '$ie'")->row_array();

    //     if ($query['numData'] != 0) {
    //         $this->db->where('premier_employee_id', $data['employee_id']);
    //         $this->db->update('premier');
    //         $id = $data['employee_id'];
    //     } else {
    //         $this->db->insert('premier');
    //         $id = $this->db->insert_id();
    //     }

    //     $status = $this->db->affected_rows();
    //     return ($status == 0) ? FALSE : $id;
    // }
    
    // function set_potongan($data = array()) {

    //     if (isset($data['employee_id'])) {
    //         $this->db->set('potongan_employee_id', $data['employee_id']);
    //     }

    //     if (isset($data['potongan_id'])) {
    //         $this->db->set('potongan_id', $data['potongan_id']);
    //     }

    //     if (isset($data['potongan_thp'])) {
    //         $this->db->set('potongan_thp', $data['potongan_thp']);
    //     }

    //     if (isset($data['potongan_gaji'])) {
    //         $this->db->set('potongan_gaji', $data['potongan_gaji']);
    //     }
        
    //     if (isset($data['potongan_lambat'])) {
    //         $this->db->set('potongan_lambat', $data['potongan_lambat']);
    //     }
        
    //     if (isset($data['potongan_keterangan'])) {
    //         $this->db->set('potongan_keterangan', $data['potongan_keterangan']);
    //     }
        
    //     $ie = $data['employee_id'];
        
    //     $query = $this->db->query("SELECT COUNT(potongan_id) as numData FROM potongan WHERE potongan_employee_id = '$ie'")->row_array();

    //     if ($query['numData'] != 0) {
    //         $this->db->where('potongan_employee_id', $data['employee_id']);
    //         $this->db->update('potongan');
    //         $id = $data['employee_id'];
    //     } else {
    //         $this->db->insert('potongan');
    //         $id = $this->db->insert_id();
    //     }

    //     $status = $this->db->affected_rows();
    //     return ($status == 0) ? FALSE : $id;
    // }
    
    function set_akun($data = array()) {

        if (isset($data['employee_id'])) {
            $this->db->set('akun_employee_id', $data['employee_id']);
        }

        if (isset($data['akun_id'])) {
            $this->db->set('akun_id', $data['akun_id']);
        }

        if (isset($data['account_id'])) {
            $this->db->set('akun_account_id', $data['account_id']);
        }
        
        $ie = $data['employee_id'];
        
        $query = $this->db->query("SELECT COUNT(akun_id) as numData FROM akun WHERE akun_employee_id = '$ie'")->row_array();

        if ($query['numData'] != 0) {
            $this->db->where('akun_employee_id', $data['employee_id']);
            $this->db->update('akun');
            $id = $data['employee_id'];
        } else {
            $this->db->insert('akun');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    function get_report($params = array()){
        
        if(isset($params['month_id']))
        {
            $this->db->where('month_id', $params['month_id']);
        }
        
        if(isset($params['period_id']))
        {
            $this->db->where('period_id', $params['period_id']);
        }
        
        if(isset($params['majors_id']))
        {
            $this->db->where('majors_id', $params['majors_id']);
        }

        $this->db->select('employee.employee_id, employee_nip, employee_name, employee_strata, employee_category, employee_start, employee_end');
        $this->db->select('position.position_majors_id, majors.majors_id, majors.majors_name, majors_short_name, majors_status');
        $this->db->select('employee.employee_position_id, position.position_id, position.position_code, position_name, majors_status');
        
        $this->db->select('akun.akun_employee_id, akun.akun_id, akun.akun_account_id');
        
		$this->db->select('gaji_id, gaji_period_id, gaji_month_id, gaji_pokok, gaji_potongan, gaji_jumlah, gaji_tanggal');
        $this->db->select('account_description');
        $this->db->select('month_id, month_name');
		$this->db->select('period_id, period_start, period_end');
        $this->db->select('kredit_gaji_id, kredit_kas_noref');
        
        $this->db->join('position', 'position.position_id = employee.employee_position_id', 'left');
        $this->db->join('majors', 'majors.majors_id = employee.employee_majors_id', 'left');
        
        $this->db->join('akun', 'akun.akun_employee_id = employee.employee_id', 'left');
        
        $this->db->join('gaji', 'gaji.gaji_employee_id = employee.employee_id', 'left');
        
        $this->db->join('subsatu', 'subsatu.subsatu_gaji_id = gaji.gaji_id', 'left');
        $this->db->join('subtiga', 'subtiga.subtiga_gaji_id = gaji.gaji_id', 'left');
        
		$this->db->join('month','gaji.gaji_month_id = month.month_id', 'left');
		$this->db->join('period','gaji.gaji_period_id = period.period_id', 'left');
		$this->db->join('kredit','gaji.gaji_id = kredit.kredit_gaji_id', 'left');
		$this->db->join('kas','kredit.kredit_kas_noref = kas.kas_noref', 'left');
		$this->db->join('account','account.account_id = kas.kas_account_id', 'left');

        $res = $this->db->get('employee');
        
        return $res->result_array();
        
    }
    
    function get_report_employee($params = array()){
        
        if(isset($params['month_id']))
        {
            $this->db->where('month_id', $params['month_id']);
        }
        
        if(isset($params['period_id']))
        {
            $this->db->where('period_id', $params['period_id']);
        }
        
        if(isset($params['majors_id']))
        {
            $this->db->where('majors_id', $params['majors_id']);
        }
        
        if(isset($params['employee_id']))
        {
            $this->db->where('employee_id', $params['employee_id']);
        }

        $this->db->select('employee.employee_id, employee_nip, employee_name, employee_strata, employee_category, employee_start, employee_end');
        $this->db->select('position.position_majors_id, majors.majors_id, majors.majors_name, majors_short_name, majors_status');
        $this->db->select('employee.employee_position_id, position.position_id, position.position_code, position_name, majors_status');
        
        $this->db->select('akun.akun_employee_id, akun.akun_id, akun.akun_account_id');
        
		$this->db->select('gaji_id, gaji_period_id, gaji_month_id, gaji_pokok, gaji_potongan, gaji_jumlah, gaji_tanggal');
        $this->db->select('account_description');
        $this->db->select('month_id, month_name');
		$this->db->select('period_id, period_start, period_end');
        $this->db->select('kredit_gaji_id, kredit_kas_noref');
        
        $this->db->join('position', 'position.position_id = employee.employee_position_id', 'left');
        $this->db->join('majors', 'majors.majors_id = employee.employee_majors_id', 'left');
        
        $this->db->join('akun', 'akun.akun_employee_id = employee.employee_id', 'left');
        
        $this->db->join('gaji', 'gaji.gaji_employee_id = employee.employee_id', 'left');
        
        $this->db->join('subsatu', 'subsatu.subsatu_gaji_id = gaji.gaji_id', 'left');
        $this->db->join('subtiga', 'subtiga.subtiga_gaji_id = gaji.gaji_id', 'left');
        
		$this->db->join('month','gaji.gaji_month_id = month.month_id', 'left');
		$this->db->join('period','gaji.gaji_period_id = period.period_id', 'left');
		$this->db->join('kredit','gaji.gaji_id = kredit.kredit_gaji_id', 'left');
		$this->db->join('kas','kredit.kredit_kas_noref = kas.kas_noref', 'left');
		$this->db->join('account','account.account_id = kas.kas_account_id', 'left');

        $res = $this->db->get('employee');
        
        return $res->row_array();
        
    }

    
    function get_setting_gaji($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('gaji_setting_id', $params['id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('employee_id', $params['employee_id']);
        }

        if(isset($params['gaji_setting_name']))
        {
            $this->db->where('gaji_setting_name', $params['gaji_setting_name']);
        }
        
        if(isset($params['gaji_setting_default_id']))
        {
            $this->db->where('gaji_setting_default_id', $params['gaji_setting_default_id']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('gaji_setting_id', 'asc');
        }
        
        
        $this->db->select('gaji_setting_id, gaji_setting_default_id, gaji_setting_nominal');

        $this->db->join('employee', 'employee_id = gaji_setting_employee_id');
        $res = $this->db->get('gaji_setting');

        return $res->row_array();
    }

    function get_setting_potongan($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('potongan_setting_id', $params['id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('employee_id', $params['employee_id']);
        }

        if(isset($params['potongan_setting_name']))
        {
            $this->db->where('potongan_setting_name', $params['potongan_setting_name']);
        }
        
        if(isset($params['potongan_setting_default_id']))
        {
            $this->db->where('potongan_setting_default_id', $params['potongan_setting_default_id']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('potongan_setting_id', 'asc');
        }
        
        $this->db->select('potongan_setting_id, potongan_setting_default_id, potongan_setting_nominal');
        
        $this->db->join('employee', 'employee_id = potongan_setting_employee_id');
        $res = $this->db->get('potongan_setting');

        return $res->row_array();
    }


    function get_default_gaji($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('default_gaji_id', $params['id']);
        }

        if(isset($params['default_gaji_name']))
        {
            $this->db->where('default_gaji_name', $params['default_gaji_name']);
        }

        if(isset($params['default_gaji_majors_id']))
        {
            $this->db->where('default_gaji_majors_id', $params['default_gaji_majors_id']);
        }
        
        if(isset($params['default_gaji_mode']))
        {
            $this->db->where('default_gaji_mode', $params['default_gaji_mode']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('default_gaji_id', 'asc');
        }
        
        $this->db->select('default_gaji_id, default_gaji_name, default_gaji_mode, default_gaji_majors_id');
        $this->db->select('majors_short_name');
        $this->db->join('majors', 'majors.majors_id = gaji_default.default_gaji_majors_id', 'LEFT');
        $res = $this->db->get('gaji_default');

        if(isset($params['id']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }

    function add_default_gaji($data = array()) {

        if (isset($data['default_gaji_name'])) {
            $this->db->set('default_gaji_name', $data['default_gaji_name']);
        }

        if (isset($data['default_gaji_mode'])) {
            $this->db->set('default_gaji_mode', $data['default_gaji_mode']);
        }

        if (isset($data['default_gaji_majors_id'])) {
            $this->db->set('default_gaji_majors_id', $data['default_gaji_majors_id']);
        }

        if (isset($data['default_gaji_id'])) {
            $this->db->where('default_gaji_id', $data['default_gaji_id']);
            $this->db->update('gaji_default');
            $id = $data['default_gaji_id'];
        } else {
            $this->db->insert('gaji_default');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_default_gaji($id) {
        $this->db->where('default_gaji_id', $id);
        $this->db->delete('gaji_default');
    }

    function add_setting_gaji($data = array()){
        if (isset($data['gaji_setting_name'])) {
            $this->db->set('gaji_setting_name', $data['gaji_setting_name']);
        }

        if (isset($data['gaji_setting_default_id'])) {
            $this->db->set('gaji_setting_default_id', $data['gaji_setting_default_id']);
        }

        if (isset($data['gaji_setting_nominal'])) {
            $this->db->set('gaji_setting_nominal', $data['gaji_setting_nominal']);
        }

        if (isset($data['gaji_majors_id'])) {
            $this->db->set('gaji_majors_id', $data['gaji_majors_id']);
        }

        if (isset($data['gaji_setting_employee_id'])) {
            $this->db->set('gaji_setting_employee_id', $data['gaji_setting_employee_id']);
        }

        if ($data['gaji_setting_id'] != "") {
            $this->db->where('gaji_setting_id', $data['gaji_setting_id']);
            $this->db->update('gaji_setting');
            $id = $data['gaji_setting_id'];
        } else {
            $this->db->insert('gaji_setting');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    // public function check_id($data = array()){
    //     if ($data['gaji_setting_id'] != "") {
    //         $id = $data['gaji_setting_id'];
    //     } else {
    //         $id = "kosong";
    //     }

    //     return $id;
    // }


    function add_potongan_gaji($data = array()){
        if (isset($data['potongan_setting_name'])) {
            $this->db->set('potongan_setting_name', $data['potongan_setting_name']);
        }

        if (isset($data['potongan_setting_default_id'])) {
            $this->db->set('potongan_setting_default_id', $data['potongan_setting_default_id']);
        }

        if (isset($data['potongan_majors_id'])) {
            $this->db->set('potongan_majors_id', $data['potongan_majors_id']);
        }

        if (isset($data['potongan_setting_nominal'])) {
            $this->db->set('potongan_setting_nominal', $data['potongan_setting_nominal']);
        }

        if (isset($data['potongan_setting_employee_id'])) {
            $this->db->set('potongan_setting_employee_id', $data['potongan_setting_employee_id']);
        }

        if ($data['potongan_setting_id'] != "") {
            $this->db->where('potongan_setting_id', $data['potongan_setting_id']);
            $this->db->update('potongan_setting');
            $id = $data['potongan_setting_id'];
        } else {
            $this->db->insert('potongan_setting');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
}
