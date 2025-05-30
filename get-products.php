<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// CSV-Feed-URL (Deutsch, Schweiz)
$csvUrl = "http://transport.productsup.io/e9ba40e8e3597b1588a0/channel/188050/vidaXL_ch_de_dropshipping.csv";

// CSV herunterladen
$csv = file_get_contents($csvUrl);
if (!$csv) {
    echo json_encode(["error" => "CSV konnte nicht geladen werden."]);
    exit;
}

// CSV in ein Array umwandeln
$rows = array_map("str_getcsv", explode("\n", $csv));
$header = array_map("trim", $rows[0]);
$data = [];

foreach (array_slice($rows, 1, 20) as $row) {
    if (count($row) < count($header)) continue;
    $item = array_combine($header, $row);
    $data[] = [
        "name" => $item["title"],
        "price" => $item["price"],
        "currency" => "CHF",
        "image" => $item["image_link"]
    ];
}

echo json_encode($data);
