<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_key(){
    $host   = '103.41.206.228';
    $user   = 'indoweb_admin';
    $pass   = '$qlC9I)CJ;r&rzIavW';
    $db     = 'indoweb_wacenter';
    
    $conn   = mysqli_connect($host, $user, $pass, $db);
    
    $query  = mysqli_query($conn, "SELECT wa_key FROM datas WHERE subdomain = 'other' AND provider='dripsender'");
    
    $res    = mysqli_fetch_array($query);
    
    return $res['wa_key'];
}

function send_whatsapp($no_wa, $psn)
{
    
    $key = get_key();
    
    $url    = 'https://api.dripsender.id/send';
    $data   = array(
        "phone"     => $no_wa,
        "api_key"   => $key,
        "text"      => $psn
    );
    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        )
    );
    return $res = curl_exec($ch);
    curl_close($ch);
}

    function send_telegram($telegram_id, $psn){
        $secret_token = "5777086871:AAHeNMB2mZJanX2-ckqySkh8sknmSdMCSQs";
        $url = "https://api.telegram.org/bot" . $secret_token . "/sendMessage?parse_mode=markdown";
        $url = $url . "&chat_id=" . $telegram_id;
        $url = $url . "&text=" . urlencode($psn);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($url)
            )
        );
        return $res = curl_exec($ch);
        curl_close($ch);
    }

?> 
