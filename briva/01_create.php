<?php

    /*Generate Token */
    function BRIVAgenerateToken($client_id, $secret_id){
        $url ="https://sandbox.partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
        $data = "client_id=".$client_id."&client_secret=".$secret_id;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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
        $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=$payload";
        $signPayload = hash_hmac('sha256', $payloads, $secret, true);
        return base64_encode($signPayload);
    }

    function BrivaUpdate($custCode,$nama,$amount,$keterangan,$brivaNo,$institutionCode){

        $client_id = 'Viov9F9r8AwnWCRz4WSY5NCHcdIzUqEc';
        $secret_id = 'KATPVp9km2jATXvG';
        $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
        $secret = $secret_id;
        //generate token
        $token = BRIVAgenerateToken($client_id,$secret_id);
		
	//	echo "tokennya : ".$token;
	//	echo "<br>";

    //    $institutionCode = "J104408";
    //    $brivaNo = "77777";
		$expiredDate1  = date("Y-m-d");
		$expiredDate2 = date('Y-m-d', strtotime($expiredDate1. ' + 88 days'));
		$expiredDate = $expiredDate2." 23:59:00";

        $datas = array('institutionCode' => $institutionCode ,
            'brivaNo' => $brivaNo,
            'custCode' => $custCode,
            'nama' => urldecode($nama),
            'amount' => $amount,
            'keterangan' => urldecode($keterangan),
            'expiredDate' => $expiredDate);

            $payload = json_encode($datas, true);
            $path = "/v1/briva";
            $verb = "POST";
            //generate signature
            $base64sign = BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
			
	//	echo "<br>signatur : ".$base64sign;
	//	echo "<br>";
			

            $request_headers = array(
                                "Content-Type:"."application/json",
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $base64sign,
                            );

            $urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva";
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);


            $jsonPost = json_decode($resultPost, true);



$text ="<br>curl -X POST 'https://sandbox.partner.api.bri.co.id/v1/briva' \
<br>  -H 'Authorization: Bearer ". $token. "' \
<br>  -H 'BRI-Signature: ". $base64sign. "' \
<br>  -H 'BRI-Timestamp: ". $timestamp. "' \
<br>  -H 'Content-Type: application/json' \
<br>  -d '
<br>{";

$text .='<br>"institutionCode": "'.$institutionCode.'",
<br> "brivaNo": "'.$brivaNo.'",
<br> "custCode": "'.$custCode.'",
<br> "nama": "'.$nama.'",
<br> "amount": "'.$amount.'",
<br> "keterangan": "'.$keterangan.'",
<br> "expiredDate": "'.$expiredDate.'"';
 
$text .="
<br>}'";

//            echo "<br/> <br/>";
            echo $text;

            echo "<br/> <br/>";
            echo "Response Post : ".$resultPost;
    }

$simpan  = $_REQUEST['simpan'];
$custCode  = $_REQUEST['custCode'];
$nama  = $_REQUEST['nama'];
$amount  = $_REQUEST['amount'];
$keterangan  = $_REQUEST['keterangan'];

$brivaNo = "77777";
if (isset($_REQUEST["brivaNo"]))    
    $brivaNo = $_REQUEST['brivaNo'];

$institutionCode = "J104408";
if (isset($_REQUEST["institutionCode"]))    
    $institutionCode = $_REQUEST['institutionCode'];

?>


<html>
<head>

</head>
<body >
            <form action="" method="post" enctype="multipart/form-data">

<table align=center>
	<tr>
		<td colspan=3>Form Create BRIVA<br><br><br></td>
	</tr>
	
	<tr>
		<td >institutionCode</td>
		<td >:</td>
		<td ><input type=text required id=institutionCode name=institutionCode value="<?php echo $institutionCode?>"></td>
	</tr>
	<tr>
		<td >Briva No</td>
		<td >:</td>
		<td ><input type=text required id=brivaNo name=brivaNo value="<?php echo $brivaNo?>"></td>
	</tr>
	<tr>
		<td >Custcode</td>
		<td >:</td>
		<td ><input type=text required id=custCode name=custCode value="<?php echo $custCode?>"></td>
	</tr>
	<tr>
		<td >Nama</td>
		<td >:</td>
		<td ><input type=text  id=nama name=nama value="<?php echo $nama?>"></td>
	</tr>
	<tr>
		<td >Amount</td>
		<td >:</td>
		<td ><input type=text  id=amount name=amount value="<?php echo $amount?>"></td>
	</tr>
	<tr>
		<td >Keterangan</td>
		<td >:</td>
		<td ><input type=text required id=keterangan name=keterangan value="<?php echo $keterangan?>"></td>
	</tr>
	<tr>
		<td >
		<a href="index.php"><< Back</a>
		</td>
		<td ></td>
		<td >
		<input type="submit" id=simpan name=simpan value="CREATE"></td>
	</tr>
	
	
</table>
</form>
<hr>
<p>
<?php
if ($simpan == 'CREATE')
{
    BrivaUpdate($custCode,urlencode($nama),$amount,urlencode($keterangan),$brivaNo,$institutionCode);
}
?>
</p>

</body>
</html>

