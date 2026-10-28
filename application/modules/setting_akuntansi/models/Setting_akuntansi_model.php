<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_akuntansi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_akrual_setting() {
        $query = $this->db->get_where('akrual_settings', array('id' => 1));
        if ($query->num_rows() == 0) {
            // Insert default data if empty
            $data = array(
                'id' => 1,
                'mode' => 'manual',
                'frequency' => 'daily',
                'run_time' => '00:00:00',
                'run_day' => NULL,
                'run_date' => NULL,
                'last_run' => NULL
            );
            $this->db->insert('akrual_settings', $data);
            return (object) $data;
        }
        return $query->row();
    }

    public function update_akrual_setting($data) {
        $this->db->where('id', 1);
        $this->db->update('akrual_settings', $data);
    }
}
