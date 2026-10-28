<?php if (!defined('BASEPATH')) exit('NO direct script access allowed');

    function send_whatsapp($no_wa, $psn, $set_key, $set_wa)
    {

        $url = "https://wa-scheduler.indoweb.id/api/queue";

        $token = "19a90a35eeb8ceb11e0141ed997a871149dadcb3"; // Bearer Token

        $receiver    = str_replace('+', '', $no_wa);
        $data = [
            "apikey"   => $set_key,
            "sender"   => $set_wa,
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

    function send_telegram($telegram_id, $psn)
    {
        $secret_token = "5480929592:AAHBD70FilwKnmeosl-nxVChvMo8R7P2Kpw";
        $url = "https://api.telegram.org/bot" . $secret_token . "/sendMessage?parse_mode=markdown&chat_id=" . $telegram_id;
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
        echo $res = curl_exec($ch);
    }

    function send_to_demots($data) {
        $baseUrl = 'https://demots.indoweb.id/api/demo-request';
        $apiKey = '406bd53df9fb7eb540b2837bc1300c914542c2a7498e7cf06088f43e27f4b559';
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Timeout 30 detik
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-api-key: ' . $apiKey,
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            echo "Response:\n";
            echo $response;
        }
    }
