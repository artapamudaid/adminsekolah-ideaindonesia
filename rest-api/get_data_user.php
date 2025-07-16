<?php
header("Content-type:application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_host='localhost';
$db_user='adminsek_userdemo';
$db_pass='PzKyeml8k3)^';
$db_name='adminsek_demo';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

date_default_timezone_set('Asia/Jakarta');

function GetValue($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $rowult_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($rowult_get_value);
    return $row_get_value[0];
}

$nip = trim($_REQUEST["nip"]);
$password = md5(trim($_REQUEST["password"]));

//menampilkan data dari database, user wp
// $sql = "select employee.employee_id, employee_nip, employee_name, employee_password, employee_email, employee_gender, employee_phone, employee_address, employee_born_place, employee_born_date, employee_photo, employee_status, employee_strata, employee.employee_majors_id, employee_position_id, employee_start, employee_end, employee_category, employee_input_date, employee_last_update,get_status_absen(employee.status_absen_temp,employee.status_absen) validasi,employee.jarak_radius,employee.area_absen,'PEGAWAI' as employee_role_id_name
// from employee
// where employee_nip='$nip' and employee_password='$password'";

$sql = "select employee.employee_id, employee_nip, employee_name, employee_password, employee_email, employee_gender, employee_phone, employee_address, employee_born_place, employee_born_date, employee_photo, employee_status, employee_strata, employee.employee_majors_id, employee_position_id, employee_start, employee_end, employee_category, employee_input_date, employee_last_update,get_status_absen(status_absen,status_absen_temp) validasi,employee.jarak_radius,employee.area_absen,'PEGAWAI' as employee_role_id_name
from employee
where employee_nip='$nip'";

$rs = mysqli_query($koneksi,$sql);
$row = mysqli_fetch_array($rs);

$longitude = GetValue("area_absensi","longi","id_area = '".$row['area_absen']."'");
$latitude = GetValue("area_absensi","lati","id_area = '".$row['area_absen']."'");
$lokasi = GetValue("area_absensi","nama_area","id_area = '".$row['area_absen']."'");

$idp = $row['employee_id'];

$q2="SELECT (SELECT ifnull(MIN(time),'-:-') FROM data_absensi WHERE id_pegawai='$idp' AND jenis_absen='DATANG' AND tanggal=STR_TO_DATE(SYSDATE(), '%Y-%m-%d')) datang,(SELECT ifnull(MAX(time),'-:-') FROM data_absensi WHERE id_pegawai='$idp' AND jenis_absen='PULANG' AND tanggal=STR_TO_DATE(SYSDATE(), '%Y-%m-%d')) pulang FROM dual";

$sql2=mysqli_query($koneksi,$q2);
$data2=mysqli_fetch_array($sql2);

$max_datang=substr($data2['datang'],0,5);
$max_pulang=substr($data2['pulang'],0,5);

$num = mysqli_num_rows($rs);
if($num != 0) 
{
    $obj = (object) [
            'is_correct' => true,
            'username' => $row["employee_id"],
            'nama' => $row["employee_name"],
            'nip' => $row["employee_nip"],
            'role_id' => $row["employee_role_id_name"],
            'lokasi' => $lokasi,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'validasi' => $row["validasi"],
            'jarak_radius' => $row["jarak_radius"],
            'message' => 'Data login anda valid',
            'max_datang' => $max_datang,
            'max_pulang' => $max_pulang,
            'email' => $row['employee_email'],
            'phone' => $row['employee_phone'],
        ];
        
} else {
    
   $obj = (object) [
	   'is_correct' => false,
	   'message' => 'Data login anda salah'
   ];
   
}

echo json_encode($obj, JSON_PRETTY_PRINT);