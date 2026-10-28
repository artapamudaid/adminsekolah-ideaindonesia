<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('create_log')) {
    function create_log($data)
    {
        $CI =& get_instance();
        
        // Ambil token dari tabel setting
        $CI->db->group_start();
        $CI->db->where('setting_id', 801);
        $CI->db->or_where('setting_name', 'notiftele_api_key');
        $CI->db->group_end();
        $setting = $CI->db->get('setting')->row();
        
        if (!$setting || empty($setting->setting_value)) {
            return false;
        }
        
        $token = $setting->setting_value;
        $url = 'https://notiftele.indoweb.id/api/messages';
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Indoweb/1.0; +https://indoweb.id)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ));
        
        // Set timeout ke 5 detik agar tidak memutus koneksi terlalu cepat sebelum data terkirim
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        // Force IPv4 resolve (sering menjadi masalah di VM jika mencoba menggunakan IPv6)
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        
        // Debugging sementara
        file_put_contents(APPPATH . 'logs/curl_debug.log', date('Y-m-d H:i:s') . "\nURL: $url\nToken: $token\nPayload: " . json_encode($data) . "\nError: $err\nResponse: $response\n\n", FILE_APPEND);
        
        return true;
    }
}
