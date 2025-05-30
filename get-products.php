<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten (VIDAXL B2B Zugang)
$email = 'lichterae@gmail.com';
$token = 'DEIN-TOKEN-HIER'; // <<< hier echten Token einsetzen
$auth = base64_encode("$email:$token");

// API-URL mit Paginierung (nur 30 Produkte abrufen)
$page = 1;
$limit = 30;
$url = "https://b2b.vidaxl.com/api_customer/products?page=$page&limit=$limit";

// cURL-Request
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic $auth",
        "Accept: application/json"
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Fehlerbehandlung
if ($httpCode !== 200 || !$response) {
    echo json_encode(['error' => 'Fehler beim Abrufen der Produktdaten.']);
    exit;
}

$data = json_decode($response, true);

// Extrahiere nur relevante Felder
$products = array_map(function ($item) {
    return [
        'id' => $item['id'] ?? '',
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?? ($item['images'][0] ?? null)
    ];
}, $data);

echo json_encode($products);
