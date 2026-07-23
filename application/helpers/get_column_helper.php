
<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_column_nilai($table)
{
    $CI = &get_instance();
    $kolom = [];

    $query = $CI->db->query("SHOW COLUMNS FROM `$table`");

    foreach ($query->result_array() as $row) {
        if (preg_match('/^(harian|sumatif)_\d+$/', $row['Field'])) {
            $kolom[] = $row['Field'];
        }
    }

    return $kolom;
}
