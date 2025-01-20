<?php
$host = "ccqnant9i80rgh.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$port = "5432";
$dbname = "d5o0uel6beunq7";
$user = "u7cifl6aggh7mr";
$password = "pc93d52331178c261f14c7c3bd07c19c8d5b1016a50c599d939ce08cf9bef95f8";

$conn_pg = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn_pg) {
    die("Connection failed: " . pg_last_error());
}
?>
