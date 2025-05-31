<?php
// Wichtig: keine Leerzeichen oder Zeilen vor diesem <?php!

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Zugangsdaten zur vidaXL API
$email = 'lichterae@gmail.com';
$token = '6bf9794d-199d-4fa3-926a-3f94ac9620be';
$auth = base64_encode("$email:$token");

// API-Endpunkt
$url = 'https://b2b.vidaxl.com/api_customer/products?limit=30';

// Anfrage an vidaXL API senden
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Accept: application/json"
]);

$response = curl_exec($ch);
