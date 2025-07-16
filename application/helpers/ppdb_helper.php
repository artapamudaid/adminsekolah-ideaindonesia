<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ppdb_data($url, $uri, $data)
{
    
    $postData = json_encode($data);

    $url = $url . "/api/" . $uri;
    
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Content-Length: " . strlen($postData)
    ]);

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }
    
    curl_close($ch);
    
    return $response;
}

?> 
