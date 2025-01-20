<?php
$host = "your_host";
$port = "5432";
$dbname = "your_dbname";
$user = "your_user";
$password = "your_password";

$conn_pg = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn_pg) {
    die("Connection failed: " . pg_last_error());
}
?>
