<?php
// Header für CORS und JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// vidaXL API-URL
$apiUrl = 'https://b2b.vidaxl.com/api_customer/products?limit=100';

// cURL Setup
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

// Fehler abfangen
$apiData = json_decode($response, true);
if (!is_array($apiData)) {
    echo json_encode(['error' => 'Fehler beim Abrufen der Daten.']);
    exit;
}

// 🔍 Nur Lampenprodukte (Lampen, Leuchten, Licht, LED etc.)
$lampProducts = array_filter($apiData, function ($item) {
    $category = strtolower($item['category_path'] ?? '');
    return str_contains($category, 'lamp') ||
           str_contains($category, 'leuchte') ||
           str_contains($category, 'licht') ||
           str_contains($category, 'beleuchtung') ||
           str_contains($category, 'led');
});

// 🔧 Daten extrahieren
$results = array_map(function ($item) {
    // Beste Bild-URL wählen
    $mainImage = $item['main_image'] ?? null;
    $firstImage = $item['images'][0] ?? null;
    $imageUrl = $mainImage ?: $firstImage;

    return [
        'id' => $item['id'] ?? '',
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $imageUrl ?: 'https://via.placeholder.com/150?text=Kein+Bild'
    ];
}, array_slice($lampProducts, 0, 30));

// ✅ Ausgabe
echo json_encode(array_values($results));
