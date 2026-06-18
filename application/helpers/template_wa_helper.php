<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper dinamis untuk parsing template dengan token [KEY].
 * - Case-insensitive
 * - Bisa handle token dengan spasi / karakter aneh
 * - Auto-convert \n literal jadi line break
 */
if (!function_exists('parse_template')) {
    function parse_template($template, $data = [], $convert_newline = true)
    {
        // Ganti semua [KEY] dengan value dari $data
        foreach ($data as $key => $value) {
            $template = preg_replace('/\[' . preg_quote($key, '/') . '\]/i', $value, $template);
        }

        // Jika perlu ubah newline ke \n atau <br>
        if ($convert_newline) {
            $template = nl2br($template); // untuk HTML
        } else {
            $template = str_replace(["\\n", "\\r"], ["\n", "\r"], $template);
        }

        return $template;
    }
}
