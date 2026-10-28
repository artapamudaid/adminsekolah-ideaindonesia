<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Terbilang Helper
 *
 * @package	CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author	Achyar Anshorie
 */

if (! function_exists('number_to_words')) {
	function number_to_words($number)
	{
		$before_comma = trim(to_word($number));
		$after_comma = trim(comma($number));
		if ($after_comma == '') {
			return ucwords($results = $before_comma . ' rupiah');
		} else {
			return ucwords($results = $before_comma . ' koma ' . $after_comma . ' rupiah');
		}
	}

	function to_word($number)
	{
		$words = "";
		$arr_number = array(
			"",
			"satu",
			"dua",
			"tiga",
			"empat",
			"lima",
			"enam",
			"tujuh",
			"delapan",
			"sembilan",
			"sepuluh",
			"sebelas"
		);

		if ($number < 12) {
			$words = " " . $arr_number[$number];
		} else if ($number < 20) {
			$words = to_word($number - 10) . " belas";
		} else if ($number < 100) {
			$words = to_word($number / 10) . " puluh " . to_word($number % 10);
		} else if ($number < 200) {
			$words = "seratus " . to_word($number - 100);
		} else if ($number < 1000) {
			$words = to_word($number / 100) . " ratus " . to_word($number % 100);
		} else if ($number < 2000) {
			$words = "seribu " . to_word($number - 1000);
		} else if ($number < 1000000) {
			$words = to_word($number / 1000) . " ribu " . to_word($number % 1000);
		} else if ($number < 1000000000) {
			$words = to_word($number / 1000000) . " juta " . to_word($number % 1000000);
		} else {
			$words = "undefined";
		}
		return $words;
	}

	function comma($number)
	{
		$after_comma = stristr($number, ',');
		$arr_number = array(
			"nol",
			"satu",
			"dua",
			"tiga",
			"empat",
			"lima",
			"enam",
			"tujuh",
			"delapan",
			"sembilan"
		);

		$results = "";
		$length = strlen($after_comma);
		$i = 1;
		while ($i < $length) {
			$get = substr($after_comma, $i, 1);
			$results .= " " . $arr_number[$get];
			$i++;
		}
		return $results;
	}
}

function tgl_indo($tanggal)
{
	$bulan = array(
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);

	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun

	return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function rupiah($angka)
{

	$hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
	return $hasil_rupiah;
}

function laporan($angka)
{

	$hasil_rupiah = number_format($angka, 0, ',', '.');
	return $hasil_rupiah;
}

function count_content($where)
{
	// get main CodeIgniter object
	$ci = get_instance();

	$ci->db->select('*');
	$ci->db->from('asets a');
	$ci->db->join('barang b', 'b.id_barang = a.id_barang');
	$ci->db->where('status_aset', 'Aktif');
	$ci->db->where('Kondisi', $where);
	$query = $ci->db->get();

	$val = $query->num_rows();

	if ($val == 0) {
		return '-';
	} else {
		return $val;
	}
}
