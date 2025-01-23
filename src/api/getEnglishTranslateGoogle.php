<?php
require "_needSession.php";
require "incKeys.php";
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if text parameter exists
if (!isset($_GET['text'])) {
    echo json_encode(['error' => 'No text provided']);
    exit;
}

$text = $_GET['text'];
$url = "https://translation.googleapis.com/language/translate/v2";// . urlencode($text);

error_log("Url: " . $url);

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'q' => [
        $text
    ],
    'target' => 'en'
  ]),
  CURLOPT_HTTPHEADER => [
    "x-goog-api-key: $google_api_key",
    "content-type: application/json; charset=utf-8"    
  ],
]);

// Execute the request
$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curl_error = curl_error($curl);
curl_close($curl);

// Check for errors and return response
if ($http_code === 200) {
    echo $response;
} else {
    error_log("REsponse: " . $response);
    error_log("Error: " . $curl_error);
    error_log("HTTP Code: " . $http_code);
    echo json_encode([
        'error' => 'Translation failed',
        'status' => $http_code,
        'curl_error' => $curl_error,
        'url' => $url
    ]);
}
?>