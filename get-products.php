<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// vidaXL Zugangsdaten
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// API-Endpunkt
$url = 'https://b2b.vidaxl.com/api_customer/products';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo $response;
} else {
    echo json_encode(["error" => "Fehler $httpCode"]);
}
