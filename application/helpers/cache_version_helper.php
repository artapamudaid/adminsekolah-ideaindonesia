<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('CACHE_TTL') or define('CACHE_TTL', 86400);

if (!function_exists('get_cache_version')) {
    function get_cache_version($type = 'siswa')
    {
        static $versions = array();

        if (isset($versions[$type])) {
            return $versions[$type];
        }

        $CI = &get_instance();
        
        try {
            if (!isset($CI->cache)) {
                $CI->load->driver('cache', array('adapter' => 'file'));
            }
            
            $version = $CI->cache->get($type . '_cache_version');
            if (!$version) {
                $version = time();
                $CI->cache->save($type . '_cache_version', $version, CACHE_TTL * 30);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cache helper driver load or read failed: ' . $e->getMessage());
            $version = time();
        }

        $versions[$type] = $version;
        return $version;
    }
}

if (!function_exists('bump_cache_version')) {
    function bump_cache_version($type = 'siswa')
    {
        $CI = &get_instance();
        try {
            if (!isset($CI->cache)) {
                $CI->load->driver('cache', array('adapter' => 'file'));
            }
            $CI->cache->save($type . '_cache_version', time(), CACHE_TTL * 30);
        } catch (\Throwable $e) {
            log_message('error', 'Cache bump version failed: ' . $e->getMessage());
        }
    }
}

if (!function_exists('get_session_cache_key')) {
    function get_session_cache_key($module, $params = array())
    {
        $CI = &get_instance();
        $umajorsid = 0;
        if (isset($CI->session)) {
            $sess_val = $CI->session->userdata('umajorsid');
            if ($sess_val !== NULL && $sess_val !== FALSE) {
                $umajorsid = $sess_val;
            }
        }
        $session_hash = md5(json_encode($params) . '_majors_' . $umajorsid);
        return strtolower($module . '_get_' . $session_hash . '_' . get_cache_version($module));
    }
}

