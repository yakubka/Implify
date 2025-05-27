<!-- 
    Authors: 
    IbrokhimN (2027'): https://github.com/LokiChan69
    YakubN (2023'): https://github.com/yakubka
-->
<?php

const AUTH_URL   = 'http://localhost:8080/api/auth';
 // ПОКА ЧТО ДАННОГО МИКРОСЕРВИСА НЕТ 

function api_request(string $method, string $url, array $data = null, string $token = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true);
    return $decoded ?? ['error' => 'HTTP ' . $httpCode, 'message' => $response];
}
?>

<style>
    ::selection {
        background-color: #363636;
        color: #ffffff;
    }
</style>