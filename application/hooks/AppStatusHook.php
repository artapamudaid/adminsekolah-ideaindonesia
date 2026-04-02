<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppStatusHook {
    public function check_app_status() {
        $CI =& get_instance();
        $CI->load->database();
        
        // Dapatkan nama controller yang sedang diakses
        $class = $CI->router->fetch_class();

        // Jika controller yang diakses adalah 'block', hentikan proses pengecekan
        if ($class === 'block') {
            return;
        }

        $query = $CI->db->get_where('setting', ['setting_name' => 'setting_app_status'])->row_array();

        if (!isset($query['setting_value']) || $query['setting_value'] != 'Y') {
            redirect('block'); // Redirect jika aplikasi tidak aktif
            exit();
        }
    }
}

