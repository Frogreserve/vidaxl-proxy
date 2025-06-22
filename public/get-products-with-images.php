<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

$url = 'https://b2b.vidaxl.com/api_customer/products?limit=5';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// DEBUG: Falls Fehler
if ($error || $httpCode !== 200) {
    echo json_encode([
        'error' => 'API Fehler',
        'http_code' => $httpCode,
        'curl_error' => $error,
        'response' => $response
    ]);
    exit;
}

// Versuche JSON zu dekodieren
$data = json_decode($response, true);

echo json_encode([
    'debug' => 'Antwort erfolgreich empfangen',
    'original' => $data
]);
