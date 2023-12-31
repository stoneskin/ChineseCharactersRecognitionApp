<?php
    require_once "connect.php";
    require_once "modules/MySessionHandler.php";
    $session = new MySessionHandler($conn);
    //session_start();
    session_destroy();
    header("Location:login.php");
?>