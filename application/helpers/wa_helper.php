<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


function send_notif_whatsapp($no_wa, $psn, $set_key, $set_wa)
{

    $number_to = str_replace('+', '', $no_wa);
    $data = [
        'api_key' => $set_key,
        'sender' => $set_wa,
        'number' =>  $number_to,
        'message' => $psn
    ];

    $url    = 'https://indoweb.notifwa.com/send-message';

    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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
    echo $res = curl_exec($ch);
    curl_close($ch);

    // echo $res;
}

function send_notif_telegram($telegram_id, $psn)
{
    $secret_token = "5480929592:AAHBD70FilwKnmeosl-nxVChvMo8R7P2Kpw";
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
    // echo $res = curl_exec($ch);
    curl_close($ch);
}
