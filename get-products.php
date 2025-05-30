<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten von vidaXL
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// API-Endpunkt
$url = 'https://b2b.vidaxl.com/api_customer/products';

// HTTP-Anfrage vorbereiten
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Nur die ersten 50 Produkte laden
if (isset($data['data'])) {
    $limited = array_slice($data['data'], 0, 50);

    // Optional: nur Produkte mit "lamp" im Namen (groß/klein ignorieren)
    $lamps = array_filter($limited, function($product) {
        return strpos(strtolower($product['name']), 'lamp') !== false;
    });

    echo json_encode(array_values($lamps)); // Zurückgeben als JSON
} else {
    echo json_encode(["error" => "Fehler beim Laden der Produktdaten."]);
}
