<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function send_whatsapp($no_wa, $psn, $set_key, $set_wa)
    {

        $url = "https://wa-scheduler.indoweb.id/api/queue";

        $token = "19a90a35eeb8ceb11e0141ed997a871149dadcb3"; // Bearer Token

        $receiver    = str_replace('+', '', $no_wa);
        $data = [
            "apikey"   => 'VWEq8UuGKCoSRBYGXVWWQ3CdSHBbsp',
            "sender"   => '6282229129292',
            "receiver" => $receiver,
            "message"  => $psn
        ];

        $payload = json_encode($data);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json",
            "Content-Length: " . strlen($payload)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            echo "Response:\n";
            echo $response;
        }
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
