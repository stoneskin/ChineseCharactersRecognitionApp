<?php
session_start();
if (!isset($_SESSION["SID"]) and !isset($_SESSION["loginUser"]) and !isset($_SESSION["userType"])) {
    header("Location:login.php");
}

?>