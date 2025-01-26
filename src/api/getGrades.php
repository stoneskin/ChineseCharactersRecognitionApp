<?php
require_once "../_needSession.php";

require "../connect.php";

header('Content-Type: application/json');

$query = $conn->query("SELECT GradeID, GradeName FROM grade ORDER BY GradeID");
$grades = [];

while ($row = $query->fetch_assoc()) {
    $grades[] = $row;
}

echo json_encode($grades);
?> 