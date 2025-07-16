<?php

// include_once('function/arc_function.php');

// include('../config/config.php');
include_once('fungsi/tanggal.php');

$db_host='localhost';
$db_user='adminsek_userdemo';
$db_pass='PzKyeml8k3)^';
$db_name='adminsek_demo';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// date_default_timezone_set('Asia/Jakarta');
// date_default_timezone_set('Asia/Makassar');

function GetValue($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $result_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($result_get_value);
    return $row_get_value[0];
}

    // Path to move uploaded files
    //$target_path = "http://mobile.epesantren.co.id/uploads/";
    $target_path = "absensi/";
     
    // array for final json respone
    $response = array();
     
    // getting server ip address
    $server_ip = gethostbyname(gethostname());
     
    // final file url that is being uploaded
    $file_upload_url = $target_path;
    
    // $id_pegawai = '110';
    // $kode_sekolah = 2203007;

    $id_pegawai = isset($_REQUEST['id_pegawai']) ? $_REQUEST['id_pegawai'] : '';
    $kode_sekolah = isset($_REQUEST['kode_sekolah']) ? $_REQUEST['kode_sekolah'] : '';
    
    $database = GetValue("sekolahs","db","kode_sekolah='$kode_sekolah'").".";
    $folder = GetValue("sekolahs","folder","kode_sekolah='$kode_sekolah'");
    $waktuindonesia = GetValue("sekolahs","waktu_indonesia","kode_sekolah='$kode_sekolah'");
    
    if($waktuindonesia=="WIB"){
        date_default_timezone_set('Asia/Jakarta');
    }elseif($waktuindonesia=="WITA"){
        date_default_timezone_set('Asia/Makassar');
    }elseif($waktuindonesia=="WIT"){
        date_default_timezone_set('Asia/Jayapura');
    }
    
    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	$lokasi = isset($_REQUEST['lokasi']) ? $_REQUEST['lokasi'] : '';
    $longi = isset($_REQUEST['longi']) ? $_REQUEST['longi'] : '';
    $lati = isset($_REQUEST['lati']) ? $_REQUEST['lati'] : '';
    $keterangan = isset($_REQUEST['keterangan']) ? $_REQUEST['keterangan'] : '';
    $image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
    $created_date  = date("Y-m-d H:i:s");
    $time          = date("H:i:s");
    
    
    
    $response['longi'] = $longi;
    $response['lati'] = $lati;
    $response['lokasi'] = $lokasi;
    $response['type'] = $type;
    $response['keterangan'] = $keterangan;
    $response['id_pegawai'] = $id_pegawai;
    $response['kode_sekolah'] = $kode_sekolah;
    $response['database'] = $database;
    $response['folder'] = $folder;
    $response['waktu_indonesia'] = $waktuindonesia;
    $response['date'] = $create_date;
    $response['time'] = $time;
    
	$status = "SUKSES_UPLOAD";
	
    if (isset($_FILES['image']['name'])) {
        $target_path = $target_path . $id_pegawai."_".basename($_FILES['image']['name']);
        $response['file_name'] = $id_pegawai."_".basename($_FILES['image']['name']);
     
        try {
            // File successfully uploaded
            $response['message'] = 'File uploaded successfully!';
            $response['error'] = false;
            $response['file_path'] = $file_upload_url . $id_pegawai."_".basename($_FILES['image']['name']);
            // Throws exception incase file is not being moved
            $move_upload="../../$folder/uploads/absensi/". $id_pegawai."_".basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $move_upload)) {
                // make error flag true
                $response['error'] = true;
                $response['message'] = 'Could not move the file prod!';
                $status = "GAGAL_UPLOAD1";
            }
        } catch (Exception $e) {
            // Exception occurred. Make error flag true
            $response['error'] = true;
    		$status = "GAGAL_UPLOAD2";
            $response['message'] = $e->getMessage();
        }
        }  else {
        $response['message'] = 'Not received any image';
    	$status = "GAGAL_UPLOAD3";
    }
    
    $response['image'] = $image;
    // echo json_encode($response);
    
// tulis data ke database dulu
    $sql = "select employee.employee_id, employee_nip, employee_name, employee_password, employee_email, 
        employee_gender, employee_phone, employee_address, employee_born_place, employee_born_date, 
        employee_photo, employee_status, employee_strata, employee.employee_majors_id, employee_position_id, 
        employee_start, employee_end, employee_category, employee_input_date, employee_last_update,
        ".$database."get_status_absen(status_absen_temp,status_absen) validasi,employee.jarak_radius,
        employee.area_absen,'PEGAWAI' as employee_role_id_name,
        (select position_name from ".$database."position where position_id=employee.employee_position_id) 
        employee_position_id
        from ".$database."employee where employee_id='$id_pegawai'";
        
        // $response['sql_pegawai'] = $sql;
        // echo json_encode($response);
        
    $rs = mysqli_query($koneksi,$sql);
    // $response['rs'] = $rs;
    $num = mysqli_num_rows($rs);
    // $response['num'] = $num;
    if($num != 0) 
    {

        $row = mysqli_fetch_array($rs);

        $idp=$row["employee_id"];
        $nama= $row["employee_name"];

        if(strtoupper($row['validasi'])=="UNLOCK"){
            $v='N';
        }elseif(strtoupper($row['validasi'])=="LOCK"){
            $v='Y';
        }else{
            $v=$row['validasi'];
        }
    }
    
    $response['validasi'] = $v;
    $response['nama_pegawai'] = $nama;
    
    // echo json_encode($response);
    // exit;
    
    //Cek dan input db
    
    $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
    
    $titik = explode(",", $area_absen);
    $titik = $titik[0];
    
    // echo $titik;
    // exit;
    
    if($v=='Y'){
        $response['validasi'] = $v;
        // $tabel = $koneksi->query ("SHOW TABLES LIKE 'data_waktu'");
        $exists = "select 1 from ".$database."data_waktu";
        $tabel = mysqli_query($koneksi,$exists);
        if($tabel !== FALSE){

            // tulis data ke database dulu

            $majors_id = GetValue("".$database."employee","employee_majors_id","employee_id='$id_pegawai'");
            
            $date_pretty = pretty_date(date("Y-m-d"),  'l',  FALSE);
            
            $hadir = "SELECT data_waktu_masuk, data_waktu_pulang 
                      FROM ".$database."data_waktu 
                      JOIN ".$database."day ON data_waktu.data_waktu_day_id = day.day_id 
                      JOIN ".$database."majors ON data_waktu.data_waktu_majors_id = majors.majors_id
                      JOIN ".$database."employee ON data_waktu.data_waktu_employee_id = employee.employee_id
                      WHERE day_name = '$date_pretty' AND majors_id = '$majors_id'";
                      
            $result1 = mysqli_query($koneksi,$hadir);
            while($row = mysqli_fetch_assoc($result1)){
                $masuk = $row['data_waktu_masuk'];
                $pulang = $row['data_waktu_pulang'];
            }
            
            $result = mysqli_query($koneksi, "SHOW COLUMNS FROM ".$database." data_absensi LIKE 'statushadir'");
            $result_exists = (mysqli_num_rows($result))?TRUE:FALSE;

            $created_date   = date("Y-m-d H:i:s");   
            $bulan          = date("Y-m");
            $date           = date("Y-m-d");
            $month          = date("n");
            $tahun          = date("Y");
            $time           = date("H:i:s");
            $time_masuk     = strtotime("now");
            $jam_masuk      = $masuk;
            $jam_hadir      = strtotime($masuk);
            $jam_pulang     = $pulang;
            $status_absen   = $type;
            
            $response["date"]           = $date;
            $response["status_absen"]   = $status_absen;
            
            $diff  = $time_masuk - $jam_hadir;
            $jam   = floor($diff / (60 * 60));
            $menit = $diff - $jam * (60 * 60);

            if($time <= $jam_masuk) {
                $terlambat = 'Tepat Waktu';
            }else{
                $terlambat = 'Anda terlambat: ' . $jam .  ' jam dan ' . floor( $menit / 60 ) . ' menit';
            }
            
            if ($status_absen=='DATANG'){

            //Datang
            if($time <= $jam_masuk) {
                $status_hadir = 'Tepat Waktu';
            }else{
                $status_hadir = 'Terlambat';
            }
            }else if ($status_absen=='PULANG'){
            
            //Pulang
            if($time < $jam_pulang){
                $status_hadir =  'Pulang Awal';
            }else{
                $status_hadir =  'Pulang';
            }
                
            } else {
                $status_hadir ='Tidak Masuk';
            }

            // $file_path = $img;
            // if ($status == "GAGAL_UPLOAD3") $file_path = null;
            
            $file_path = $file_upload_url . $id_pegawai."_".basename($_FILES['image']['name']);
            if ($status == "GAGAL_UPLOAD3") $file_path = "../uploads/absensi/no_image.jpg";

            $id_pegawai     = $id_pegawai;
            $jenis_absen    = $type;
            $foto           = $file_path;
            $catatan_absen  = $keterangan;
            $remark         = $status;
            // $lokasi_absen   = $lokasi;
            $kehadiran      = $status_hadir;
            
            $result = mysqli_query($koneksi, "SHOW COLUMNS FROM ".$database." data_absensi LIKE 'statushadir'");
            $exists = (mysqli_num_rows($result))?TRUE:FALSE;
            
            $koneksi->query("delete from ".$database."data_absensi where id_pegawai='$id_pegawai' and tanggal='$date' and (jenis_absen='SAKIT' or jenis_absen='IJIN' or jenis_absen='CUTI')");
            
            //Datang
            // $absen_datang_lock = "SELECT * FROM ".$database."data_absensi WHERE id_pegawai='$id_pegawai' AND tanggal = '$date' AND jenis_absen = 'DATANG'";
            
            // $absensi_datang_lock = mysqli_num_rows(mysqli_query($koneksi, $absen_datang_lock));
            
            // if ($absensi_datang_lock==0) {
            // 	$hasil = 'masukkan absen';
                $koneksi->query("SET foreign_key_checks = 0");
                $status_dat_lock =   "insert into ".$database."data_absensi set 
                            id_pegawai = '$id_pegawai', 
                            area_absen = '$titik[0]', 
                            bulan = '$bulan', 
                            tanggal = '$date', 
                            time = '$time',
                            keterlambatan = '$terlambat', 
                            jenis_absen = '$jenis_absen',
                            statushadir = '$kehadiran', 
                            longi = '$longi', 
                            lati = '$lati', 
                            foto = '$foto', 
                            catatan_absen = '$catatan_absen', 
                            lokasi = '$lokasi', 
                            remark = '$remark',
                            created_date = '$created_date'";
                // $response["query"]      = $status_dat_lock;
                $rs = mysqli_query($koneksi,$status_dat_lock);
                $koneksi->query("SET foreign_key_checks = 1");
                $response["sql"]            = $rs;
        
        // Echo final json response to client
        // echo json_encode($response);

        }
            
    } else if ($v=='N') {

        $response['validasi'] = $v;
        // $tabel = $koneksi->query ("SHOW TABLES LIKE 'data_waktu'");
        $exists = "select 1 from ".$database."data_waktu";
        $tabel = mysqli_query($koneksi,$exists);
        if($tabel !== FALSE){

            // tulis data ke database dulu

            $majors_id = GetValue("".$database."employee","employee_majors_id","employee_id='$id_pegawai'");
            
            $date_pretty = pretty_date(date("Y-m-d"),  'l',  FALSE);
            
            $hadir = "SELECT data_waktu_masuk, data_waktu_pulang 
                      FROM ".$database."data_waktu 
                      JOIN ".$database."day ON data_waktu.data_waktu_day_id = day.day_id 
                      JOIN ".$database."majors ON data_waktu.data_waktu_majors_id = majors.majors_id 
                      WHERE day_name = '$date_pretty' AND majors_id = '$majors_id'";
                      
            $result1 = mysqli_query($koneksi,$hadir);
            while($row = mysqli_fetch_assoc($result1)){
                $masuk = $row['data_waktu_masuk'];
                $pulang = $row['data_waktu_pulang'];
            }
            
            $result = mysqli_query($koneksi, "SHOW COLUMNS FROM ".$database." data_absensi LIKE 'statushadir'");
            $result_exists = (mysqli_num_rows($result))?TRUE:FALSE;

            $created_date   = date("Y-m-d H:i:s");   
            $bulan          = date("Y-m");
            $date           = date("Y-m-d");
            $time           = date("H:i:s");
            $month          = date("n");
            $tahun          = date("Y");
            $time_masuk     = strtotime("now");
            $jam_masuk      = $masuk;
            $jam_hadir      = strtotime($masuk);
            $jam_pulang     = $pulang;
            $status_absen   = $type;
            $result         = $result_exists;
            
            $diff  = $time_masuk - $jam_hadir;
            $jam   = floor($diff / (60 * 60));
            $menit = $diff - $jam * (60 * 60);

            if($time <= $jam_masuk) {
                $terlambat = 'Tepat Waktu';
            }else{
                $terlambat = 'Anda terlambat: ' . $jam .  ' jam dan ' . floor( $menit / 60 ) . ' menit';
            }
            
            
            if ($status_absen=='DATANG'){

            //Datang
            if($time <= $jam_masuk) {
                $status_hadir = 'Tepat Waktu';
            }else{
                $status_hadir = 'Terlambat';
            }
            }else if ($status_absen=='PULANG'){
            
            //Pulang
            if($time < $jam_pulang){
                $status_hadir =  'Pulang Awal';
            }else{
                $status_hadir =  'Pulang';
            }
                
            } else {
                $status_hadir ='Tidak Masuk';
            }

            // $file_path = $img;
            // if ($status == "GAGAL_UPLOAD3") $file_path = null;
            
            $file_path = $file_upload_url . $id_pegawai."_".basename($_FILES['image']['name']);
            if ($status == "GAGAL_UPLOAD3") $file_path = "../uploads/absensi/no_image.jpg";

            $id_pegawai     = $id_pegawai;  
            $jenis_absen    = $type;  
            $foto           = $file_path;  
            $catatan_absen  = $keterangan;  
            $remark         = $status;  
            // $lokasi_absen   = $lokasi;
            $kehadiran      = $status_hadir;
            
            $result = mysqli_query($koneksi, "SHOW COLUMNS FROM ".$database." data_absensi LIKE 'statushadir'");
            $exists = (mysqli_num_rows($result))?TRUE:FALSE;
            
            $koneksi->query("delete from ".$database."data_absensi where id_pegawai='$id_pegawai' and tanggal='$date' and (jenis_absen='SAKIT' or jenis_absen='IJIN' or jenis_absen='CUTI')");
            
            	$koneksi->query("SET foreign_key_checks = 0");
                $status_dat_unlock =  "insert into ".$database."data_absensi set
                            id_pegawai = '$id_pegawai', 
                            area_absen = '$titik[0]',
                            bulan = '$bulan', 
                            tanggal = '$date', 
                            time = '$time',
                            keterlambatan = '$terlambat', 
                            jenis_absen = '$jenis_absen',
                            statushadir = '$kehadiran', 
                            longi = '$longi', 
                            lati = '$lati', 
                            foto = '$foto', 
                            catatan_absen = '$catatan_absen', 
                            lokasi = '$lokasi', 
                            remark = '$remark',
                            created_date = '$created_date'";
                // $response["query"]   = $status_dat_unlock;
                $rs = mysqli_query($koneksi,$status_dat_unlock);
                $koneksi->query("SET foreign_key_checks = 1");
                $response["sql"]     = $rs;
        }
        
    }else{
    // tulis data ke database dulu
    $created_date   = date("Y-m-d H:i:s");   
    $bulan          = date("Y-m");
    $date           = date("Y-m-d");
    $time           = date("H:i:s");
    $file_path      = $img;
    if ($status == "GAGAL_UPLOAD3") $file_path = null;
    
    $file_path = $file_upload_url . $id_pegawai."_".basename($_FILES['image']['name']);
    if ($status == "GAGAL_UPLOAD3") $file_path = "../uploads/absensi/no_image.jpg";

    $id_pegawai = $id_pegawai;  
    $jenis_absen = $type;  
    $foto = $file_path;  
    $catatan_absen = $keterangan;  
    $remark = $status;  

    $koneksi->query("delete from ".$database."data_absensi where id_pegawai='$id_pegawai' and tanggal='$date' and (jenis_absen='SAKIT' or jenis_absen='IJIN' or jenis_absen='CUTI')");

// Echo final json response to client

}
echo json_encode($response);
?>