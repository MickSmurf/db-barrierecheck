<?php
// CORS Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Suchstting von Vue Abfrage
$search = isset($_GET['search']) ? urlencode($_GET['search']) : "";

if (empty($search)) {
    echo json_encode(["error" => "Kein Suchbegriff angegeben!"]);
    exit;
}


//API Keys & url
$clientId = "f1533e6317231e50c6da6a98cfe685c5";
$apiKey ="40a7a9bc25829e6b7163883cb4aa8a0c";
$url = "https://apis.deutschebahn.com/db-api-marketplace/apis/station-data/v2/stations?searchstring=" . $search;

// cURL Request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "DB.Client-Id: " . $clientId,
    "DB-Api-Key: " . $apiKey,
    "Accept: application/json"
));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Status an das Frontend weitergeben und JSON ausgeben
http_response_code($httpcode);
echo $response;
