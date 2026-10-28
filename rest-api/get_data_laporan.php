<?php
header("Content-type:application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_host=getenv('DB_HOST') ?: 'localhost';
$db_user=getenv('DB_USERNAME') ?: '';
$db_pass=getenv('DB_PASSWORD') ?: '';
$db_name=getenv('DB_DATABASE') ?: '';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name)  or die ('Unable to execute query. '. mysqli_error($koneksi));

// Check connection
if (!$koneksi) {
    /* Use your preferred error logging method here */
    error_log('Connection error: ' . mysqli_connect_error());
}
date_default_timezone_set('Asia/Jakarta');

function GetValue($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $rowult_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($rowult_get_value);
    return $row_get_value[0];
}

// $id_pegawai = trim($_REQUEST["id_pegawai"]);
// $tahun = trim($_REQUEST["tahun"]);

$id_pegawai = '1';
$tahun = '2022';

if(isset($_REQUEST['bulan']))
// 	$bulan = strtoupper(trim($_REQUEST["bulan"]));

if($bulan=="JANUARI"){
	$bulan="1";
}elseif($bulan=="FEBRUARI"){
	$bulan="2";
}elseif($bulan=="MARET"){
	$bulan="3";
}elseif($bulan=="APRIL"){
	$bulan="4";
}elseif($bulan=="MEI"){
	$bulan="5";
}elseif($bulan=="JUNI"){
	$bulan="6";
}elseif($bulan=="JULI"){
	$bulan="7";
}elseif($bulan=="AGUSTUS"){
	$bulan="8";
}elseif($bulan=="SEPTEMBER"){
	$bulan="9";
}elseif($bulan=="OKTOBER"){
	$bulan="10";
}elseif($bulan=="NOVEMBER"){
	$bulan="11";
}elseif($bulan=="DESEMBER"){
	$bulan="12";
}


$bulan = "7";

// $type = trim($_REQUEST["type"]);
$type = 'BULANAN';

$database = "adminsek_demo.";


function GetValue_2($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $rowult_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($rowult_get_value);
    return $row_get_value[0];
}

if($type == 'BULANAN') 
{
	$bulan_hari_total=cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

	$bulan_2=date('Y-m',strtotime($tahun."-".$bulan));

	$sql = "select 
	(select count(1) from ".$database."data_absensi where id_pegawai='$id_pegawai' and jenis_absen='DATANG' and bulan='$bulan_2') hadir, 
	(select count(1) from ".$database."data_absensi where id_pegawai='$id_pegawai' and jenis_absen='DATANG' and YEAR(tanggal)='$tahun') hadir_tahun_ini, 
	(select count(1) from ".$database."data_absensi where id_pegawai='$id_pegawai' and (jenis_absen='LAIN-LAIN' OR jenis_absen='CUTI' OR jenis_absen='SAKIT') and bulan='$bulan_2') izin_cuti 
	from dual";

	$rs = mysqli_query($koneksi,$sql);
	$row = mysqli_fetch_array($rs);

	$jml_hadir_cuti=$row['hadir']+$row['izin_cuti'];

	$terlambat=mysqli_query($koneksi,"select count(1) terlambat from data_absensi where id_pegawai='$id_pegawai' and bulan='$bulan_2' and jenis_absen='DATANG' and time >= '07:16:00'");
	$terlambat=mysqli_fetch_array($terlambat);
	$terlambat=$terlambat['terlambat'];

	$percentase=$jml_hadir_cuti*100/22;
	$percentase_hari=$jml_hadir_cuti."/22 Hari";

	//detail
	$detail=array();
	$q="SELECT
	a.id_pegawai,
    a.tanggal,
	case when a.jenis_absen='DATANG' then 'Hadir'
    	when a.jenis_absen='PULANG' then 'Pulang'
		when a.jenis_absen='CUTI' then 'Cuti'
		when a.jenis_absen='SAKIT' then 'Sakit'
		when a.jenis_absen='LAIN-LAIN' then 'Keperluan Lain' ELSE ''
    END jenis_absen,
    a.lokasi,
    a.catatan_absen,
    (select ifnull(min(time),'-;-') FROM data_absensi WHERE id_pegawai=a.id_pegawai AND tanggal=a.tanggal AND jenis_absen='DATANG') jam_datang,
    (select ifnull(max(time),'-;-') FROM data_absensi WHERE id_pegawai=a.id_pegawai AND tanggal=a.tanggal AND jenis_absen='PULANG') jam_pulang
FROM `data_absensi` a where bulan = '$bulan_2' AND a.id_pegawai = '$id_pegawai' GROUP BY id_pegawai, tanggal ORDER BY tanggal";
	
	$abc=mysqli_query($koneksi,$q);
	while($data=mysqli_fetch_array($abc)){
		$detail[] = array(
			"hari" => $data['tanggal'],
			"status" => $data['jenis_absen'],
			"detail" => array(
				"jam_datang" => $data['jam_datang'],
				"jam_pulang" => $data['jam_pulang'],
				"lokasi" => $data['lokasi'],
				"catatan_absen" => $data['catatan_absen']
			)
		);
	}

	$obj = (object) [
		'hadir' => $row['hadir'],
		'izin_cuti' => $row['izin_cuti'],
		'alpa' => 0,
		'terlambat' => $terlambat,
		'percentase' => number_format($percentase,2),
		'percentase_hari' => $percentase_hari,
		'hadir_tahun_ini' => $row['hadir_tahun_ini'],
		'rekap' => $detail,
// 		'sql'=> $sql,
// 		'detail'=> $q,
	];

}else{

	$sql_tahun = "select 
	(select count(1) from ".$database."data_absensi where id_pegawai='$id_pegawai' and jenis_absen='DATANG' and YEAR(tanggal)='$tahun') hadir, 
	(select count(1) from ".$database."data_absensi where id_pegawai='$id_pegawai' and (jenis_absen='LAIN-LAIN' OR jenis_absen='CUTI' OR jenis_absen='SAKIT') and YEAR(tanggal)='$tahun') izin_cuti 
	from dual";

	$rs = mysqli_query($koneksi,$sql_tahun);
	$row = mysqli_fetch_array($rs);

	$jml_hadir_cuti=$row['hadir']+$row['izin_cuti'];

	$terlambat=mysqli_query($koneksi,"select count(1) terlambat from data_absensi where id_pegawai='$id_pegawai' and YEAR(tanggal)='$tahun' and jenis_absen='DATANG' and time >= '07:16:00'");
	$terlambat=mysqli_fetch_array($terlambat);
	$terlambat=$terlambat['terlambat'];

	$percentase=$jml_hadir_cuti*100/264;
	$percentase_hari=$jml_hadir_cuti."/264 Hari";

	$obj = (object) [
		'hadir' => $row['hadir'],
		'izin_cuti' => $row['izin_cuti'],
		'alpa' => 0,
		'terlambat' => $terlambat,
		'percentase' => number_format($percentase,2),
		'sql' => $sql_tahun,
		'percentase_hari' => $percentase_hari
	];
} 

echo json_encode($obj, JSON_PRETTY_PRINT);
