<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// API-Zugang
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// API-Daten abrufen
$apiUrl = 'https://b2b.vidaxl.com/api_customer/products?limit=30';
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);
$apiResponse = curl_exec($ch);
curl_close($ch);
$apiData = json_decode($apiResponse, true);

// CSV-Feed laden (Deutsch)
$csvUrl = 'http://transport.productsup.io/e9ba40e8e3597b1588a0/channel/188050/vidaXL_ch_de_dropshipping.csv';
$csvData = [];
if (($handle = fopen($csvUrl, 'r')) !== false) {
    $header = fgetcsv($handle, 0, ',');
    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        $item = array_combine($header, $row);
        $csvData[$item['id']] = $item; // Key: Produkt-ID
    }
    fclose($handle);
}

// Produkte kombinieren
$products = array_map(function ($item) use ($csvData) {
    $id = $item['id'];
    $imageFromCsv = $csvData[$id]['Image url'] ?? null;

    return [
        'id' => $id,
        'name' => $item['name'] ?? '',
        'price' => $item['price'] ?? '',
        'currency' => $item['currency'] ?? '',
        'image_url' => $item['main_image'] ?? $imageFromCsv ?? null
    ];
}, array_slice($apiData, 0, 30));

echo json_encode($products);
