<?php
if (!function_exists('add_workdays')) {
    function add_workdays($start_date, $days = 3) {
        $date = new DateTime($start_date);
        while ($days > 0) {
            $date->modify('+1 day');
            if ($date->format('N') < 6) { // 1-5 = Senin–Jumat
                $days--;
            }
        }
        return $date->format('Y-m-d');
    }
}
