<?php
require_once "connect_pg.php";

header('Content-Type: application/json');

$sql = "SELECT first_name, last_name FROM recent_ch_students";
$result = pg_query($conn_pg, $sql);

$students = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $students[] = $row['first_name'] . ' ' . $row['last_name'];
    }
}

echo json_encode($students);
?>
