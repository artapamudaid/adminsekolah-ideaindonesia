<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_cache_version')) {
    function get_cache_version($type = 'siswa')
    {
        static $versions = array();

        if (isset($versions[$type])) {
            return $versions[$type];
        }

        $CI = &get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));

        $version = $CI->cache->get($type . '_cache_version');
        if (!$version) {
            $version = time();
            $CI->cache->save($type . '_cache_version', $version, CACHE_TTL * 30);
        }

        $versions[$type] = $version;
        return $version;
    }
}

if (!function_exists('bump_cache_version')) {
    function bump_cache_version($type = 'siswa')
    {
        $CI = &get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));
        $CI->cache->save($type . '_cache_version', time(), CACHE_TTL * 30);
    }
}
