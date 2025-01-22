<?php
require "_needSession.php";
require "incKeys.php";
header('Content-Type: application/json');

// Check if text parameter exists
if (!isset($_GET['text'])) {
    echo json_encode(['error' => 'No text provided']);
    exit;
}

$text = $_GET['text'];
$api_url = 'https://api-inference.huggingface.co/models/Helsinki-NLP/opus-mt-zh-en'; //first time load is slow, and it hav 1000 request/day limit for free acct
$api_key = $hf_api_key; // Replace with your actual API key


// Prepare the request
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['inputs' => $text]),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]
]);

// Execute the request
$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// Check for errors and return response
if ($http_code === 200) {
    echo $response;
} else {
    echo json_encode([
        'error' => 'Translation failed',
        'status' => $http_code
    ]);
}
?>
