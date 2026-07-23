<?php if (!defined('BASEPATH')) exit('NO direct script access allowed');

function kirim_pesan($no_wa, $psn)
{
    $number_to = str_replace('+', '', $no_wa);
    $data = [
        'api_key' => 'yQQcVtMVmE2R532CqXNolvD8rt5hjyEI',
        'sender'  => '6281233315959',
        'number'  => $number_to,
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

    return curl_exec($ch);
    curl_close($ch);
}
