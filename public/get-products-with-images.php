<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// 1. API-Daten abrufen
$apiUrl = 'https://b2b.vidaxl.com/api_customer/products?limit=100';
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);
$response = curl_exec($ch);
curl_close($ch);

$apiData = json_decode($response, true);
if (!is_array($apiData)) {
    echo json_encode(['error' => 'Fehler beim Abrufen der API-Daten.']);
    exit;
}

// 2. Nur Lampenprodukte filtern
$lampProducts = array_filter($apiData, function ($item) {
    $category = strtolower($item['category_path'] ?? '');
    return str_contains($category, 'lamp') ||
           str_contains($category, 'light') ||
           str_contains($category, 'leuchte') ||
           str_contains($category, 'beleuchtung');
});

// 3. Produkte umwandeln (Platzhalterbild wenn leer)
$products = array_map(function ($item) {
    return [
        'id' => $item['id'] ?? '',
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?: 'https://via.placeholder.com/300x300?text=Kein+Bild'
    ];
}, array_slice($lampProducts, 0, 30));

// 4. Ausgabe als JSON
echo json_encode($products);
