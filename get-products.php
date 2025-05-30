<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// vidaXL-Zugangsdaten
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// API-Endpunkt
$url = 'https://b2b.vidaxl.com/api_customer/products';

// Anfrage vorbereiten
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Nur die ersten 50 Produkte zeigen
if (isset($data['data'])) {
    $limited = array_slice($data['data'], 0, 50);
    echo json_encode($limited);
} else {
    echo json_encode(["error" => "Fehler beim Abrufen der Produkte"]);
}
