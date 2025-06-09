<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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

// 2. CSV laden (nur einmal in Speicher)
$csvUrl = 'http://transport.productsup.io/e9ba40e8e3597b1588a0/channel/188050/vidaXL_ch_de_dropshipping.csv';
$csvData = [];
if (($handle = fopen($csvUrl, 'r')) !== false) {
    $headers = fgetcsv($handle, 0, ',');
    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        $entry = array_combine($headers, $row);
        if (isset($entry['id']) && isset($entry['Image url'])) {
            $csvData[trim($entry['id'])] = $entry['Image url'];
        }
    }
    fclose($handle);
}

// 3. Daten kombinieren + Lampen filtern
$lampProducts = array_filter($apiData, function ($item) {
    $category = strtolower($item['category_path'] ?? '');
    return str_contains($category, 'lamp') || str_contains($category, 'light') || str_contains($category, 'leuchte');
});

// 4. Produkte umwandeln
$products = array_map(function ($item) use ($csvData) {
    $id = $item['id'] ?? '';
    $fallbackImage = $csvData[$id] ?? null;

    return [
        'id' => $id,
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?? $fallbackImage
    ];
}, array_slice($lampProducts, 0, 30));

// 5. JSON-Ausgabe
echo json_encode($products);
