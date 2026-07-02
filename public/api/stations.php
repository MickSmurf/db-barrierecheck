<?php
// CORS Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Suchstting von Vue Abfrage
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (empty($search)) {
    echo json_encode(["error" => "Kein Suchbegriff angegeben!"]);
    exit;
}

require_once __DIR__ . '/config.php';

//API Keys & url
$clientId = DB_CLIENT_ID;
$apiKey =DB_API_KEY;

$apiSearchTerm ="*".$search."*";
$url = "https://apis.deutschebahn.com/db-api-marketplace/apis/station-data/v2/stations?searchstring=" . urlencode($apiSearchTerm);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "DB-Client-Id: " . $clientId,
    "DB-API-Key: " . $apiKey,
    "Accept: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);

    // Wir mappen 'result' einfach auf den Key 'stations', den Vue erwartet
    $output = [
        'stations' => isset($data['result']) ? $data['result'] : []
    ];

    header("Content-Type: application/json");
    echo json_encode($output);
    exit;
} else {
    http_response_code(500);
    echo json_encode(["error" => "Keine Antwort von der DB API"]);
    exit;
}

