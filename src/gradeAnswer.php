<?php
require 'vendor/autoload.php';

use OpenAI\Client;

function gradeAnswer($recognizedText, $correctAnswer) {
    $client = new Client('YOUR_OPENAI_API_KEY');
    $prompt = "Grade the recognized text based on tone. If the tone is not correct for one character, take off 0.2 of the 1 point. If both are wrong, the score is 0.6. If the word is completely wrong, it is 0 points. Return the recognized text, correct answer, and score.\n\nRecognized text: \"$recognizedText\"\nCorrect answer: \"$correctAnswer\"";

    $response = $client->completions()->create([
        'model' => 'text-davinci-003',
        'prompt' => $prompt,
        'max_tokens' => 50,
    ]);

    $result = $response['choices'][0]['text'];
    return json_decode($result, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $recognizedText = $data['recognizedText'];
    $correctAnswer = $data['correctAnswer'];

    $result = gradeAnswer($recognizedText, $correctAnswer);
    echo json_encode($result);
}
?>