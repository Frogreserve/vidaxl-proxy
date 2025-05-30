<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten
$email = 'lichterae@gmail.com';
$token = 'dein-vidaxl-api-token-hier';
$auth = base64_encode("$email:$token");

// API-Endpoint
$url = 'https://b2b.vidaxl.com/api_customer/products';

// cURL-Setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

// Daten decodieren
$data = json_decode($response, true);

// Nur relevante Daten extrahieren
$products = array_map(function ($item) {
    return [
        'id' => $item['id'] ?? '',
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?? $item['images'][0] ?? null
    ];
}, array_slice($data, 0, 30)); // Begrenze auf 30 Produkte für Performance

echo json_encode($products);
