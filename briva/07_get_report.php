 <?php

    /*Generate Token*/
    function BRIVAgenerateToken($client_id, $secret_id){
        $url ="https://sandbox.partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
        $data = "client_id=".$client_id."&client_secret=".$secret_id;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  //for updating we have to use PUT method.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($result, true);
        $accesstoken = $json['access_token'];

        return $accesstoken;
    }

    /*Generate signature*/
    function BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret){
         $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=";
        $signPayload = hash_hmac('sha256', $payloads, $secret, true);
        return base64_encode($signPayload);
    }

    function BrivaUpdate($tanggal_awal, $tanggal_akhir, $time_awal, $time_akhir,$brivaNo,$institutionCode){

        $client_id = 'Viov9F9r8AwnWCRz4WSY5NCHcdIzUqEc';
        $secret_id = 'KATPVp9km2jATXvG';
        $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
        $secret = $secret_id;
        //generate token
        $token = BRIVAgenerateToken($client_id,$secret_id);

        $startDate = $tanggal_awal;
        $endDate = $tanggal_akhir;

            $payload = null;
            $path = "/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;

            #$path = "/v1/briva/report_time/".$institutionCode."/".$brivaNo."/".$startDate."/".$time_awal."/".$endDate."/".$time_akhir;

            $verb = "GET";
            //generate signature
            $base64sign = BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

            $request_headers = array(
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $base64sign,
                            );

            $urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET"); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);

            $jsonPost = json_decode($resultPost, true);

$text = "curl -X GET https://sandbox.partner.api.bri.co.id/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate." -H 'Authorization: Bearer ".$token."' \
<br>  -H 'BRI-Signature: ".$base64sign."' \
<br>  -H 'BRI-Timestamp: ".$timestamp."'
";
echo $text;
            echo "<br/> <br/>";
            echo "Response Post : ".$resultPost;
    }

$simpan  = $_REQUEST['simpan'];


$tanggal_awal  = date("Ymd");   
if (isset($_REQUEST["tanggal_awal"]))    
    $tanggal_awal  = $_REQUEST['tanggal_awal'];

$time_awal  = "01:00";   
if (isset($_REQUEST["time_awal"]))    
    $time_awal  = $_REQUEST['time_awal'];

$tanggal_akhir  = date("Ymd");   
if (isset($_REQUEST["tanggal_akhir"]))    
    $tanggal_akhir  = $_REQUEST['tanggal_akhir'];

$time_akhir  = "23:00";   
if (isset($_REQUEST["time_akhir"]))    
    $time_akhir  = $_REQUEST['time_akhir'];

$institutionCode = "J104408";
if (isset($_REQUEST["institutionCode"]))    
    $institutionCode  = $_REQUEST['institutionCode'];

$brivaNo = "77777";
if (isset($_REQUEST["brivaNo"]))    
    $brivaNo  = $_REQUEST['brivaNo'];

    ?>

<html>
<head>

</head>
<body >
            <form action="" method="post" enctype="multipart/form-data">

<table align=center>
	<tr>
		<td colspan=3>Reporting<br><br><br></td>
	</tr>
	<tr>
		<td >institutionCode</td>
		<td >:</td>
		<td ><input type=text  id=institutionCode name=institutionCode value="<?php echo $institutionCode?>">
	</td>
	</tr>
	<tr>
	<tr>
		<td >brivaNo</td>
		<td >:</td>
		<td ><input type=text  id=brivaNo name=brivaNo value="<?php echo $brivaNo?>">
	</td>
	</tr>
	<tr>


	<tr>
		<td >Tanggal Awal</td>
		<td >:</td>
		<td ><input type=text  id=tanggal_awal name=tanggal_awal value="<?php echo $tanggal_awal?>">
	
		</td>
	</tr>
	<tr>

	<tr>
		<td >Tanggal Akhir</td>
		<td >:</td>
		<td ><input type=text  id=tanggal_akhir name=tanggal_akhir value="<?php echo $tanggal_akhir?>">
	</td>
	</tr>
	<tr>

		<td >
		<a href="index.php"><< Back</a>
		</td>
		<td ></td>
		<td >
		<input type="submit" id=simpan name=simpan value="VIEW"></td>
	</tr>
	
	
</table>
</form>
<hr>
<p>
<?php
if ($simpan == 'VIEW')
{
    BrivaUpdate($tanggal_awal,$tanggal_akhir,$time_awal, $time_akhir, $brivaNo,$institutionCode );
}
?>
</p>

</body>
</html>
	