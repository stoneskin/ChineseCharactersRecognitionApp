<?php
//require_once '_incFunctions.php';
require "../connect.php";

if (!isset($_GET['activityId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Activity ID is required']);
    exit;
}

$activityId = intval($_GET['activityId']);

$sql = "SELECT w.Words, r.Passed, r.TimeElapsed 
        FROM records r 
        INNER JOIN wordslibrary w ON r.WordID = w.ID 
        WHERE r.ActivityID = ?
        ORDER BY r.TimeElapsed";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $activityId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $words = [];
    while ($row = $result->fetch_assoc()) {
        $words[] = $row;
    }
    
    echo json_encode($words);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
} 