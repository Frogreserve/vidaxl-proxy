<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten (deine echten Daten einfügen)
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// API-Endpunkt
$url = 'https://b2b.vidaxl.com/api_customer/products?limit=30';

// cURL Setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

// Prüfen ob API Antwort gültig ist
$data = json_decode($response, true);
if (!is_array($data)) {
    echo json_encode([]);
    exit;
}

// Produkte extrahieren
$products = array_map(function ($item) {
    return [
        'id' => $item['id'] ?? '',
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?? $item['images'][0] ?? "https://via.placeholder.com/150?text=Kein+Bild"
    ];
}, array_slice($data, 0, 30));

echo json_encode($products);
