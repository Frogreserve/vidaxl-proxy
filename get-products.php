<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten (Token bitte später ersetzen)
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be'; // <<< Ersetze bei Bedarf durch deinen echten
$auth = base64_encode("$email:$token");

// API-URL (auf 30 Produkte beschränkt)
$page = 1;
$limit = 30;
$url = "https://b2b.vidaxl.com/api_customer/products?page=$page&limit=$limit";

// cURL-Request konfigurieren
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
    echo json_encode([
        'error' => 'Fehler beim Abrufen der Produktdaten.',
        'http_code' => $httpCode
    ]);
    exit;
}

$data = json_decode($response, true);

// Produkte extrahieren
$products = array_map(function ($item) {
    return [
        'id' => $item['id'] ?? '',
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?? 'https://via.placeholder.com/150?text=Kein+Bild'
    ];
}, $data);

// Ausgabe
echo json_encode($products);
