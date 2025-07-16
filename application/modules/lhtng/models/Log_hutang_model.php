<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_hutang_model extends CI_Model {

	function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array()) {
        if (isset($params['id'])) {
            $this->db->where('log_hutang.log_hutang_id', $params['id']);
        }
        
        if (isset($params['settinghutang_noref'])) {
            $this->db->where('hutang_pay_noref !=', $params['settinghutang_noref']);
        }
        
        if (isset($params['hutang_pay_id'])) {
            $this->db->where('log_hutang_pay_id', $params['hutang_pay_id']);
        }

        if (isset($params['settinghutang_id'])) {
            $this->db->where('settinghutang_settinghutang_id', $params['settinghutang_id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('log_hutang.log_hutang_employee_id', $params['employee_id']);
        }

        if (isset($params['employee_nip'])) {
            $this->db->where('employee_nip', $params['employee_nip']);
        }


        if (isset($params['log_hutang_input_date'])) {
            $this->db->where('log_hutang_input_date', $params['log_hutang_input_date']);
        }

        if (isset($params['log_hutang_last_update'])) {
            $this->db->where('log_hutang_last_update', $params['log_hutang_last_update']);
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
            $this->db->order_by('log_hutang_id', 'desc');
        }
        
        $this->db->select('log_hutang.log_hutang_id, log_hutang_input_date, log_hutang_last_update');
        $this->db->select('log_hutang.log_hutang_employee_id, employee_nip, hutang_pay_bill, hutang_pay_account_id, hutang_pay_noref');

        $this->db->select('poshutang.poshutang_name, period.period_start, period.period_end');
        
        $this->db->join('hutang_pay', 'hutang_pay.hutang_pay_id = log_hutang.log_hutang_pay_id', 'left');
        $this->db->join('hutang', 'hutang.hutang_id = hutang_pay.hutang_hutang_id', 'left');
        $this->db->join('employee', 'employee.employee_id = log_hutang.log_hutang_employee_id', 'left');
        $this->db->join('settinghutang', 'settinghutang.settinghutang_id = hutang.settinghutang_settinghutang_id', 'left');
        $this->db->join('poshutang', 'poshutang.poshutang_id = settinghutang.poshutang_poshutang_id', 'left');
        $this->db->join('period', 'period.period_id = settinghutang.period_period_id', 'left');
        $this->db->join('account', 'account.account_id = hutang_pay.hutang_pay_account_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = hutang_pay.hutang_pay_noref', 'left');

        $res = $this->db->get('log_hutang');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
        
    }
    
    function get_history($params = array()){
        
        if (isset($params['settinghutang_noref'])) {
            $noref = $params['settinghutang_noref'];
        } else {
            $noref = NULL;
        }

        if (isset($params['employee_id'])) {
            $employee_id = $params['employee_id'];
        } else {
            $employee_id = NULL;
        }

        if (isset($params['employee_nip'])) {
            $employee_nip = $params['employee_nip'];
        } else {
            $employee_nip = NULL;
        }
        
        $res = $this->db->query("SELECT `log_hutang`.`log_hutang_id`, `log_hutang_input_date`, `log_hutang_last_update`, `log_hutang`.`log_hutang_employee_id`,
        `employee_nip`, `hutang_pay_bill`, `hutang_pay_account_id`, `hutang_pay_noref`, `poshutang`.`poshutang_name`, 
        `poshutang`.`poshutang_name` AS `poshutang_name`, `period`.`period_start`, 
        `period`.`period_end` FROM `log_hutang` 
        LEFT JOIN `hutang_pay` ON `hutang_pay`.`hutang_pay_id` = `log_hutang`.`log_hutang_pay_id` 
        LEFT JOIN `hutang` ON `hutang`.`hutang_id` = `hutang_pay`.`hutang_hutang_id` 
        LEFT JOIN `employee` ON `employee`.`employee_id` = `log_hutang`.`log_hutang_employee_id` 
        LEFT JOIN `settinghutang` ON `settinghutang`.`settinghutang_id` = `hutang`.`settinghutang_settinghutang_id`
        LEFT JOIN `poshutang` AS `poshutang` ON `poshutang`.`poshutang_id` = `settinghutang`.`poshutang_poshutang_id` 
        LEFT JOIN `period` ON `period`.`period_id` = `settinghutang`.`period_period_id` 
        LEFT JOIN `account` ON `account`.`account_id` = `hutang_pay`.`hutang_pay_account_id` 
        LEFT JOIN `kas` ON `kas`.`kas_noref` = `hutang_pay`.`hutang_pay_noref` 
        WHERE (`log_hutang`.`log_hutang_employee_id` = '$employee_id' AND `employee_nip` = '$employee_nip') 
        OR (`hutang_pay_noref` != '$noref' AND `log_hutang`.`log_hutang_employee_id` = '$employee_id' AND `employee_nip` = '$employee_nip') 
        ORDER BY `log_hutang_id` DESC");
        
        return $res->result_array();
            
    }
    
    function get_hutang($params = array()) {
        if (isset($params['id'])) {
            $this->db->where('log_hutang.log_hutang_id', $params['id']);
        }
        
        if (isset($params['settinghutang_noref'])) {
            $this->db->where('hutang_pay_noref =', $params['settinghutang_noref']);
        }

        if (isset($params['hutang_pay_id'])) {
            $this->db->where('log_hutang_pay_id', $params['hutang_pay_id']);
        }

        if (isset($params['settinghutang_id'])) {
            $this->db->where('settinghutang_settinghutang_id', $params['settinghutang_id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('log_hutang.log_hutang_employee_id', $params['employee_id']);
        }

        if (isset($params['employee_nip'])) {
            $this->db->where('employee_nip', $params['employee_nip']);
        }


        if (isset($params['log_hutang_input_date'])) {
            $this->db->where('log_hutang_input_date', $params['log_hutang_input_date']);
        }

        if (isset($params['log_hutang_last_update'])) {
            $this->db->where('log_hutang_last_update', $params['log_hutang_last_update']);
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
            $this->db->order_by('log_hutang_id', 'desc');
        }
        $this->db->select('log_hutang.log_hutang_id, log_hutang_input_date, log_hutang_last_update');
        $this->db->select('log_hutang.log_hutang_employee_id, employee_nip, hutang_pay_bill, hutang_pay_account_id, hutang_pay_noref');

        $this->db->select('poshutang.poshutang_name, poshutang.poshutang_name AS poshutang_name, period.period_start, period.period_end');
        $this->db->select('period.period_start AS period_start_hutang, period.period_end AS period_end_hutang');

        $this->db->join('hutang_pay', 'hutang_pay.hutang_pay_id = log_hutang.log_hutang_pay_id', 'left');
        $this->db->join('hutang', 'hutang.hutang_id = hutang_pay.hutang_hutang_id', 'left');
        $this->db->join('employee', 'employee.employee_id = log_hutang.log_hutang_employee_id', 'left');
        $this->db->join('settinghutang', 'settinghutang.settinghutang_id = hutang.settinghutang_settinghutang_id', 'left');
        $this->db->join('poshutang', 'poshutang.poshutang_id = settinghutang.poshutang_poshutang_id', 'left');
        $this->db->join('period', 'period.period_id = settinghutang.period_period_id', 'left');
        $this->db->join('account', 'account.account_id = hutang_pay.hutang_pay_account_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = hutang_pay.hutang_pay_noref', 'left');

        $res = $this->db->get('log_hutang');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // Insert some data to table
    function add($data = array()) {

        if (isset($data['log_hutang_id'])) {
            $this->db->set('log_hutang_id', $data['log_hutang_id']);
        }

        if (isset($data['log_hutang_pay_id'])) {
            $this->db->set('log_hutang_pay_id', $data['log_hutang_pay_id']);
        }

        if (isset($data['log_hutang_hutang_id'])) {
            $this->db->set('log_hutang_hutang_id', $data['log_hutang_hutang_id']);
        }

        if (isset($data['log_hutang_employee_id'])) {
            $this->db->set('log_hutang_employee_id', $data['log_hutang_employee_id']);
        }

        if (isset($data['log_hutang_input_date'])) {
            $this->db->set('log_hutang_input_date', $data['log_hutang_input_date']);
        }

        if (isset($data['log_hutang_last_update'])) {
            $this->db->set('log_hutang_last_update', $data['log_hutang_last_update']);
        }


        if (isset($data['log_hutang_id'])) {
            $this->db->where('log_hutang_id', $data['log_hutang_id']);
            $this->db->update('log_hutang');
            $id = $data['log_hutang_id'];
        } else {
            $this->db->insert('log_hutang');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

      // Delete log_hutang to database
    function delete($id) {
        $this->db->where('log_hutang_id', $id);
        $this->db->delete('log_hutang');
    }

      // Delete log_hutang to database
    function delete_log($params = array()) {

        if (isset($params['hutang_pay_id'])) {
            $this->db->where('log_hutang_pay_id', $params['hutang_pay_id']);
        }

        if (isset($params['employee_id'])) {
            $this->db->where('log_hutang.log_hutang_employee_id', $params['employee_id']);
        }

        $this->db->delete('log_hutang');
    }
    
    function hutang_finish($data = array()) {

        if (isset($data['kas_id'])) {
            $this->db->set('kas_id', $data['kas_id']);
        }

        if (isset($data['kas_noref'])) {
            $this->db->set('kas_noref', $data['kas_noref']);
        }

        if (isset($data['kas_period'])) {
            $this->db->set('kas_period', $data['kas_period']);
        }

        if (isset($data['kas_date'])) {
            $this->db->set('kas_date', $data['kas_date']);
        }

        if (isset($data['kas_month_id'])) {
            $this->db->set('kas_month_id', $data['kas_month_id']);
        }

        if (isset($data['kas_account_id'])) {
            $this->db->set('kas_account_id', $data['kas_account_id']);
        }

        if (isset($data['kas_majors_id'])) {
            $this->db->set('kas_majors_id', $data['kas_majors_id']);
        }
        
        if (isset($data['kas_debit'])) {
            $this->db->set('kas_debit', $data['kas_debit']);
        }
        
        if (isset($data['kas_note'])) {
            $this->db->set('kas_note', $data['kas_note']);
        }

        if (isset($data['kas_category'])) {
            $this->db->set('kas_category', $data['kas_category']);
        }

        if (isset($data['kas_user_id'])) {
            $this->db->set('kas_user_id', $data['kas_user_id']);
        }

        if (isset($data['kas_input_date'])) {
            $this->db->set('kas_input_date', $data['kas_input_date']);
        }

        if (isset($data['kas_last_update'])) {
            $this->db->set('kas_last_update', $data['kas_last_update']);
        }
        
        $this->db->insert('kas');
        $id = $this->db->insert_id();

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

}

/* End of file Log_hutang_model.php */
/* Location: ./application/modules/lhutang/models/Log_hutang_model.php */