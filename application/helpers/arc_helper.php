<?php

if (!function_exists('pretty_date')) {
    function select_month($month_id = null)
    {

        $month = NULL;

        switch ($month_id) {
            case '1':
                $month = '07';
                break;
            case '2':
                $month = '08';
                break;
            case '3':
                $month = '09';
                break;
            case '4':
                $month = '10';
                break;
            case '5':
                $month = '11';
                break;
            case '6':
                $month = '12';
                break;
            case '7':
                $month = '01';
                break;
            case '8':
                $month = '02';
                break;
            case '9':
                $month = '03';
                break;
            case '10':
                $month = '04';
                break;
            case '11':
                $month = '05';
                break;
            case '12':
                $month = '06';
                break;

            default:
                $month = null;
                break;
        }

        return $month;
    }

    function pretty_date($date = '', $format = '', $timezone = TRUE)
    {
        $date_str = strtotime($date);

        if (empty($format)) {
            $date_pretty = date('l, d/m/Y H:i', $date_str);
        } else {
            $date_pretty = date($format, $date_str);
        }

        if ($timezone) {
            $date_pretty .= ' WIB';
        }

        $date_pretty = str_replace('Sunday', 'Minggu', $date_pretty);
        $date_pretty = str_replace('Monday', 'Senin', $date_pretty);
        $date_pretty = str_replace('Tuesday', 'Selasa', $date_pretty);
        $date_pretty = str_replace('Wednesday', 'Rabu', $date_pretty);
        $date_pretty = str_replace('Thursday', 'Kamis', $date_pretty);
        $date_pretty = str_replace('Friday', 'Jumat', $date_pretty);
        $date_pretty = str_replace('Saturday', 'Sabtu', $date_pretty);

        $date_pretty = str_replace('January', 'Januari', $date_pretty);
        $date_pretty = str_replace('February', 'Februari', $date_pretty);
        $date_pretty = str_replace('March', 'Maret', $date_pretty);
        $date_pretty = str_replace('April', 'April', $date_pretty);
        $date_pretty = str_replace('May', 'Mei', $date_pretty);
        $date_pretty = str_replace('June', 'Juni', $date_pretty);
        $date_pretty = str_replace('July', 'Juli', $date_pretty);
        $date_pretty = str_replace('August', 'Agustus', $date_pretty);
        $date_pretty = str_replace('September', 'September', $date_pretty);
        $date_pretty = str_replace('October', 'Oktober', $date_pretty);
        $date_pretty = str_replace('November', 'November', $date_pretty);
        $date_pretty = str_replace('December', 'Desember', $date_pretty);

        return $date_pretty;
    }

    if (!function_exists('template_media_url')) {

        function template_media_url($name = '')
        {
            return base_url('media/templates/' . config_item('template') . '/' . $name);
        }
    }

    if (!function_exists('upload_url')) {

        function upload_url($name = '')
        {
            if (stristr($name, '://') !== FALSE) {
                return $name;
            } else {
                return base_url('uploads/' . $name);
            }
        }
    }

    if (!function_exists('media_url')) {

        function media_url($name = '')
        {
            return base_url('media/' . $name);
        }
    }
}

if (!function_exists('upload_prove_url')) {

    function upload_prove_url($name = '')
    {
        if (stristr($name, '://') !== FALSE) {
            // If it contains a full URL, strip any directory prefixes (like 'users/https://...')
            $parts = explode('://', $name);
            return 'https://' . ltrim($parts[1], '/'); // Rebuild using actual URL
        } else {
            return base_url('uploads/prove/' . $name);
        }
    }
}

if (! function_exists('idr_format')) {
    function idr_format($param = '')
    {
        return 'Rp. ' . number_format($param, 0, '.', '.') . ',-';
    }
}

if (! function_exists('posts_url')) {
    function posts_url($posts = array())
    {
        return site_url('posts/detail/' . $posts['posts_id'] . '/' . url_title($posts['posts_title'], '-', TRUE) . '.html');
    }
}

function getCell($cell)
{
    $letters = array_combine(range(1, 26), range('a', 'z'));
    $result = $letters[$cell];
    return $result;
}

function getValue($c, $t, $w)
{
    $CI = &get_instance();
    $get = $CI->db->query("select " . $c . " as res from " . $t . " where " . $w . "");
    $get = $get->row_array();
    $res = $get['res'];
    return $res;
}

function logo()
{
    $CI = &get_instance();
    $CI->load->model('setting/Setting_model');
    $result = $CI->Setting_model->get(array('id' => 6));
    return $result['setting_value'];
}
function majors()
{
    $CI = &get_instance();
    $CI->load->model('setting/Setting_model');
    $result = $CI->Setting_model->get(array('id' => 7));
    return $result['setting_value'];
}

if (!function_exists('sanitizeStrict')) {
    function sanitizeStrict($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $input[$key] = sanitizeStrict($val);
            }
            return $input;
        }

        $input = (string) $input;

        $input = strip_tags($input);

        $input = preg_replace('/&#[0-9]+;|&#x[0-9A-F]+;/i', '', $input);

        $input = preg_replace('/[<>]/', '', $input);

        $input = str_ireplace('[removed]', '', $input);

        $input = preg_replace('/[^a-zA-Z0-9\s\.\,\-\_\(\)\!\?\:\@\'\/]/', '', $input);

        return trim($input);
    }

    function pre($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    if (!function_exists('printQuery')) {
        function printQuery()
        {
            $CI = &get_instance();
            echo '<pre>';
            echo $CI->db->last_query();
            echo '</pre>';
            die;
        }
    }

    if (!function_exists('time_passed')) {
        function time_passed($datetime, $penuh = false)
        {
            date_default_timezone_set('Asia/Jakarta');
            $sekarang = new DateTime;
            $yangLalu = new DateTime($datetime);
            $diff = $sekarang->diff($yangLalu);
            $weeks = floor($diff->d / 7);
            $days = $diff->d % 7;
            $string = array(
                'y' => $diff->y > 0 ? $diff->y . ' tahun' : null,
                'm' => $diff->m > 0 ? $diff->m . ' bulan' : null,
                'w' => $weeks > 0 ? $weeks . ' minggu' : null,
                'd' => $days > 0 ? $days . ' hari' : null,
                'h' => $diff->h > 0 ? $diff->h . ' jam' : null,
                'i' => $diff->i > 0 ? $diff->i . ' menit' : null,
                's' => $diff->s > 0 ? $diff->s . ' detik' : null,
            );
            $string = array_filter($string);
            if (!$penuh) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' yang lalu' : 'Baru saja';
        }
    }
}
