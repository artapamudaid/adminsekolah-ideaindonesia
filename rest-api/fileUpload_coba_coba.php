<?php

include('fungsi/tanggal.php');
require_once __DIR__ . '/upload_security.php';
require_upload_token();
// include('../config/config.php');

$db_host=getenv('DB_HOST') ?: 'localhost';
$db_user=getenv('DB_USERNAME') ?: '';
$db_pass=getenv('DB_PASSWORD') ?: '';
$db_name=getenv('DB_DATABASE') ?: '';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

date_default_timezone_set('Asia/Jakarta');

function GetValue($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $result_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($result_get_value);
    return $row_get_value[0];
}

// $response['row'] = $row_get_value;
// echo json_encode($response);
 
// Path to move uploaded files
//$target_path = "http://demo.epesantren.co.id/uploads/";
$target_path = "absensi/";
 
// array for final json respone
$response = array();
 
// getting server ip address
$server_ip = gethostbyname(gethostname());
 
// final file url that is being uploaded
$file_upload_url = $target_path;

    $kode_sekolah = isset($_REQUEST['kode_sekolah']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_REQUEST['kode_sekolah']) : '';
    $id_pegawai = isset($_REQUEST['id_pegawai']) ? preg_replace('/[^0-9]/', '', $_REQUEST['id_pegawai']) : '';
    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	$lokasi = isset($_REQUEST['lokasi']) ? $_REQUEST['lokasi'] : '';
    $longi = isset($_REQUEST['longi']) ? $_REQUEST['longi'] : '';
    $lati = isset($_REQUEST['lati']) ? $_REQUEST['lati'] : '';
    $keterangan = isset($_REQUEST['keterangan']) ? $_REQUEST['keterangan'] : '';
    $created_date  = date("Y-m-d H:i:s");
    $database = GetValue("sekolahs","db","kode_sekolah='$kode_sekolah'").".";
    $folder = GetValue("sekolahs","folder","kode_sekolah='$kode_sekolah'");
 
    $response['longi'] = $longi;
    $response['lati'] = $lati;
    $response['lokasi'] = $lokasi;
    $response['type'] = $type;
    $response['keterangan'] = $keterangan;
    $response['id_pegawai'] = $id_pegawai;
    $response['kode_sekolah'] = $kode_sekolah;
	$status = "SUKSES_UPLOAD";
    $safeFileName = 'no_image.jpeg';
    // echo json_encode($response);
 
 
if (isset($_FILES['image']['name'])) {
    $safeFileName = safe_uploaded_image_name('image', $id_pegawai);
    $target_path = $target_path . $safeFileName;
    $response['file_name'] = $safeFileName;
 
    try {
        // Throws exception incase file is not being moved
        $uploadDir = __DIR__ . '/../uploads/absensi';
        ensure_upload_directory($uploadDir);
        $move_upload = $uploadDir . '/' . $safeFileName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $move_upload)) {
            // make error flag true
            $response['error'] = true;
            $response['message'] = 'Could not move the file!';
			$status = "GAGAL_UPLOAD1";

        }
 
        // File successfully uploaded
        $response['message'] = 'File uploaded successfully!';
        $response['error'] = false;
        $response['file_path'] = $file_upload_url . $safeFileName;
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
    
    $nama= $row["employee_name"];

    $rs = mysqli_query($koneksi,$sql);

    $num = mysqli_num_rows($rs);
    if($num != 0) 
    {

        $row = mysqli_fetch_array($rs);

        $idp=$row["employee_id"];

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
    // $response['query'] = $sql;
    // echo json_encode($response);
    
    // Ngecek
    
    // if($v=='Y'){
    //     $response['validasi'] = $v;
    //     // $tabel = $koneksi->query ("SHOW TABLES LIKE 'data_waktu'");
    //     $exists = "select 1 from ".$database."data_waktu";
    //     $tabel = mysqli_query($koneksi,$exists);
    //     if($tabel !== FALSE)
    //     {
            
    //         $majors_id = GetValue("".$database."employee","employee_majors_id","employee_id='$id_pegawai'");
            
    //         $date_pretty = pretty_date(date("Y-m-d"),  'l',  FALSE);
            
    //         $hadir = "SELECT data_waktu_masuk, data_waktu_pulang 
    //                   FROM ".$database."data_waktu 
    //                   JOIN ".$database."day ON data_waktu.data_waktu_day_id = day.day_id 
    //                   JOIN ".$database."majors ON data_waktu.data_waktu_majors_id = majors.majors_id 
    //                   WHERE day_name = '$date_pretty' AND majors_id = '$majors_id'";
                      
    //         $result1 = mysqli_query($koneksi,$hadir);
    //         while($row = mysqli_fetch_assoc($result1)){
    //             $masuk = $row['data_waktu_masuk'];
    //             $pulang = $row['data_waktu_pulang'];
    //         }
            
    //         $status_absen=$type;
            
    //         if ($status_absen=='DATANG'){

    //         //Datang
    //         if($time <= $masuk) {
    //             $status_hadir = 'Tepat Waktu';
    //         }else{
    //             $status_hadir = 'Terlambat';
    //         }
    //         }else if ($status_absen=='PULANG'){
            
    //         //Pulang
    //         if($time < $pulang){
    //             $status_hadir =  'Pulang Awal';
    //         }else{
    //             $status_hadir =  'Pulang';
    //         }
                
    //         } else {
    //             $status_hadir ='Tidak Masuk';
    //         }
            
    //         $result = mysqli_query($koneksi, "SHOW COLUMNS FROM data_absensi LIKE 'statushadir'");
    //         $result_exists = (mysqli_num_rows($result))?TRUE:FALSE;
    //         $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
    //         $lokasi = GetValue("area_absensi","nama_area","id_area='$area_absen'");
    //         $created_date   = date("Y-m-d H:i:s");   
    //         $bulan          = date("Y-m");
    //         $date           = date("Y-m-d");
    //         $time           = date("H:i:s");

    //         //response
    //         $response['data_waktu'] = 'data waktu ada';
    //         //variabel sql
    //         $response['id']         = $majors_id;
    //         $response['id_pegawai'] = $id_pegawai;
    //         $response['area']       = $area_absen;
    //         $response['bulan']      = $bulan;
    //         $response['tanggal']    = $date;
    //         $response['time']       = $time;
    //         $response['query']      = $hadir;
    //         $response['date']       = $date_pretty;
    //         $response['masuk']      = $masuk;
    //         $response['pulang']     = $pulang;
    //         $response['exists']     = $result_exists;
    //         $response['lokasi']     = $lokasi;
    //         $response['kehadiran']  = $status_hadir;
    //         $response['created']    = $created_date;
    //     }else{
    //         $response['data_waktu'] =  'data waktu tidak ada';
    //     }
        
        
    // }else{
    //     $response['validasi'] = $v;
    // }
        
    // // Echo final json response to client
    // echo json_encode($response);
    
    //cek dan input db
    
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
                      WHERE day_name = '$date_pretty' AND majors_id = '$majors_id'";
                      
            $result1 = mysqli_query($koneksi,$hadir);
            while($row = mysqli_fetch_assoc($result1)){
                $masuk = $row['data_waktu_masuk'];
                $pulang = $row['data_waktu_pulang'];
            }
            
            $result = mysqli_query($koneksi, "SHOW COLUMNS FROM data_absensi LIKE 'statushadir'");
            $result_exists = (mysqli_num_rows($result))?TRUE:FALSE;
            
            $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
            // $lokasi = GetValue("area_absensi","nama_area","id_area='$area_absen'");

            $created_date   = date("Y-m-d H:i:s");   
            $bulan          = date("Y-m");
            $date           = date("Y-m-d");
            $time           = date("H:i:s");
            $jam_masuk      = $masuk;
            $jam_pulang     = $pulang;
            $status_absen   = $type;
            
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
            
            $file_path = $file_upload_url . $safeFileName;
            if ($status == "GAGAL_UPLOAD3") $file_path = "../uploads/absensi/no_image.jpg";

            $id_pegawai     = $id_pegawai;  
            $jenis_absen    = $type;  
            $foto           = $file_path;  
            $catatan_absen  = $keterangan;  
            $remark         = $status;  
            // $lokasi_absen   = $lokasi;
            $kehadiran      = $status_hadir;
            
            $result = mysqli_query($koneksi, "SHOW COLUMNS FROM data_absensi LIKE 'statushadir'");
            $exists = (mysqli_num_rows($result))?TRUE:FALSE;

            $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
            
            $koneksi->query("delete from ".$database."data_absensi where id_pegawai='$id_pegawai' and tanggal='$date' and (jenis_absen='SAKIT' or jenis_absen='IJIN' or jenis_absen='CUTI')");

            $koneksi->query("SET foreign_key_checks = 0");

            $sql = "insert into ".$database."data_absensi set 
                    id_pegawai = '$id_pegawai', 
                    area_absen = '$area_absen', 
                    bulan = '$bulan', 
                    tanggal = '$date', 
                    time = '$time', 
                    jenis_absen = '$jenis_absen',
                    statushadir = '$kehadiran', 
                    longi = '$longi', 
                    lati = '$lati', 
                    foto = '$foto', 
                    catatan_absen = '$catatan_absen', 
                    lokasi = '$lokasi', 
                    remark = '$remark', 
                    created_by = '$created_by',
                    created_date = '$created_date'";
            $rs = mysqli_query($koneksi,$sql);
            $koneksi->query("SET foreign_key_checks = 1");
            $response["sql"]        =$rs;
            $response["area_absen"] =$area_absen;
            // $response["kehadiran"]  =$kehadiran;
        
        // Echo final json response to client
        echo json_encode($response);

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
                
                $result = mysqli_query($koneksi, "SHOW COLUMNS FROM data_absensi LIKE 'statushadir'");
                $result_exists = (mysqli_num_rows($result))?TRUE:FALSE;
                
                $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
                // $lokasi = GetValue("area_absensi","nama_area","id_area='$area_absen'");

                $created_date   = date("Y-m-d H:i:s");   
                $bulan          = date("Y-m");
                $date           = date("Y-m-d");
                $time           = date("H:i:s");
                $jam_masuk      = $masuk;
                $jam_pulang     = $pulang;
                $status_absen   = $type;
                
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
                
                $file_path = $file_upload_url . $safeFileName;
                if ($status == "GAGAL_UPLOAD3") $file_path = "../uploads/absensi/no_image.jpg";

                $id_pegawai     = $id_pegawai;  
                $jenis_absen    = $type;  
                $foto           = $file_path;  
                $catatan_absen  = $keterangan;  
                $remark         = $status;  
                // $lokasi_absen   = $lokasi;
                $kehadiran      = $status_hadir;
                
                $result = mysqli_query($koneksi, "SHOW COLUMNS FROM data_absensi LIKE 'statushadir'");
                $exists = (mysqli_num_rows($result))?TRUE:FALSE;

                $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
                
                $koneksi->query("delete from ".$database."data_absensi where id_pegawai='$id_pegawai' and tanggal='$date' and (jenis_absen='SAKIT' or jenis_absen='IJIN' or jenis_absen='CUTI')");

                $koneksi->query("SET foreign_key_checks = 0");

                $sql = "insert into ".$database."data_absensi set 
                        id_pegawai = '$id_pegawai', 
                        area_absen = '$area_absen', 
                        bulan = '$bulan', 
                        tanggal = '$date', 
                        time = '$time', 
                        jenis_absen = '$jenis_absen',
                        statushadir = '$kehadiran', 
                        longi = '$longi', 
                        lati = '$lati', 
                        foto = '$foto', 
                        catatan_absen = '$catatan_absen', 
                        lokasi = '$lokasi', 
                        remark = '$remark', 
                        created_by = '$created_by',
                        created_date = '$created_date'";
                $rs = mysqli_query($koneksi,$sql);
                $koneksi->query("SET foreign_key_checks = 1");
                $response["sql"]        =$rs;
                $response["area_absen"] =$area_absen;
                // $response["kehadiran"]  =$kehadiran;
            
            // Echo final json response to client
            echo json_encode($response);

            }
            
        }else{
        // tulis data ke database dulu
        $created_date   = date("Y-m-d H:i:s");   
        $bulan          = date("Y-m");
        $date           = date("Y-m-d");
        $time           = date("H:i:s");
        $file_path      = $img;
        if ($status == "GAGAL_UPLOAD3") $file_path = null;
        
        $file_path = $file_upload_url . $safeFileName;
        if ($status == "GAGAL_UPLOAD3") $file_path = "../uploads/absensi/no_image.jpg";

        $id_pegawai = $id_pegawai;  
        $jenis_absen = $type;  
        $foto = $file_path;  
        $catatan_absen = $keterangan;  
        $remark = $status;  
        // $lokasi_absen = $lokasi;

        $area_absen = GetValue("".$database."employee","area_absen","employee_id='$id_pegawai'");
        
        // $lokasi_absen = GetValue("area_absensi","nama_area","id_area='$area_absen'");

        $koneksi->query("delete from ".$database."data_absensi where id_pegawai='$id_pegawai' and tanggal='$date' and (jenis_absen='SAKIT' or jenis_absen='IJIN' or jenis_absen='CUTI')");
        
        $koneksi->query("SET foreign_key_checks = 0");

        $sql = "insert into ".$database."data_absensi set 
                id_pegawai = '$id_pegawai', 
                area_absen = '$area_absen', 
                bulan = '$bulan', 
                tanggal = '$date', 
                time = '$time', 
                jenis_absen = '$jenis_absen', 
                longi = '$longi', 
                lati = '$lati', 
                foto = '$foto', 
                catatan_absen = '$catatan_absen', 
                lokasi = '$lokasi', 
                remark = '$remark', 
                created_by = '$created_by',
                created_date = '$created_date'";
        $rs = mysqli_query($koneksi,$sql);
        $koneksi->query("SET foreign_key_checks = 1");
        $response["sql"]=$rs;
        $response["area_absen"]=$area_absen;
    
    // Echo final json response to client
    echo json_encode($response);
    }
?>
