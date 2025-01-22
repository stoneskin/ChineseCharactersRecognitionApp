<?php
require "../connect.php";

require_once "../modules/MySessionHandler.php";
$session = new MySessionHandler($conn);

if (!isset($_SESSION["SID"]) and !isset($_SESSION["loginUser"]) and !isset($_SESSION["userType"])) {
    header("Location:login.php");
}

?>