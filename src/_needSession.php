<?php
session_start();
if (!isset($_SESSION["SID"]) and !isset($SESSION["loginUser"]) and !isset($SESSION["userType"])) {
    header("Location:login.php");
}

?>