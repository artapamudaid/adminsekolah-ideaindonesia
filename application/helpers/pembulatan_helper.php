<?php if (!defined('BASEPATH')) exit('NO direct script access allowed');

function bulat_ratusan($uang)
{
    $pembulat = substr($uang, -2);

    if ($pembulat != '00') {
        $akhir = $uang + (100 - $pembulat);
    } else {
        $akhir = $uang;
    }
    echo number_format($akhir, 0, '', '');
}

function bulat_ribuan($uang)
{
    $pembulat = substr($uang, -3);
    if ($pembulat != '000') {
        $akhir = $uang + (1000 - $pembulat);
    } else {
        $akhir = $uang;
    }

    echo number_format($akhir, 0, '', '');
}

function _desimal($angka)
{
    $angka = preg_replace("/[^0-9.,]/", "", $angka);
    $angka = str_replace(".", "", $angka);
    $angka = str_replace(",", ".", $angka);
    return $angka;
}

/**
 * Format angka ke format Rupiah
 * Contoh: 1000000 => Rp 1.000.000
 */
if (!function_exists('rp')) {
    function rp($angka, $prefix = 'Rp ')
    {
        if ($angka === '' || $angka === null) return '';
        return $prefix . number_format((float)$angka, 2, ',', '.');
        // pakai 2 desimal biar konsisten dengan DECIMAL(30,2)
    }
}

/**
 * Ubah titik desimal ke koma
 * Contoh: 1234.56 => 1234,56
 */
if (!function_exists('ubahkoma')) {
    function ubahkoma($angka)
    {
        if ($angka === '' || $angka === null) return '';

        // pastikan ke float dulu agar aman
        $angka = (float)$angka;
        // Format dengan 2 desimal, ubah titik jadi koma
        return number_format($angka, 2, ',', '');
    }
}
