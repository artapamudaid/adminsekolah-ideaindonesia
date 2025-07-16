<?php
date_default_timezone_set('Asia/Jakarta');

$koneksi = @mysqli_connect("localhost", "adminsek_userdemo", "PzKyeml8k3)^" ,"adminsek_demo");
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}

header("Content-type:application/json");


// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$data = file_get_contents('php://input');

$ip = get_client_ip();

$sql = "insert into data_briva_notif (txt,ip) values ('$data','$ip')";
$rs = mysqli_query($koneksi,$sql);

$tag = explode("&", $data);
foreach($tag as $t)
{
    $x=explode("=",$t);
	$a=$x[0];
	$b=$x[1];
	${$a}=$b;
}

		   $obj = (object) [
			   'responseCode' => '0000',
			   'responseDescription' => 'Success'
				];

echo json_encode($obj, JSON_PRETTY_PRINT);
//tutup koneksi ke database
mysqli_close($koneksi);

?>