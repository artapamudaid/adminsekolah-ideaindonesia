<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('curl_request')) {
    /**
     * Send HTTP request using CURL in CI3.
     *
     * @param string $url
     * @param string $method GET|POST|PATCH|PUT|DELETE
     * @param array|null $data
     * @param array $headers
     * @return array ['status' => bool, 'http_code' => int, 'response' => string]
     */

    function build_url_with_params($url, $params = [])
    {
        if (empty($params)) return $url;

        $query = http_build_query($params);
        $separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';

        return $url . $separator . $query;
    }

    function curl_request($url, $method = 'GET', $data = null, $headers = [])
    {
        $ch = curl_init();

        $defaultHeaders = ['Content-Type: application/json'];
        $headers = array_merge($defaultHeaders, $headers);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if (!empty($data)) {
            $payload = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        return $response;
    }
}
