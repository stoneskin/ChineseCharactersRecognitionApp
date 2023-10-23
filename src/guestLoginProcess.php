<?php
    include "connect.php";
    $guestError="";
    $username=$_POST["username"];;
    $accessKey=$_POST["accessKey"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (empty($username)) {
            $guestError = "Username is required";
            header("Location: login.php?guestError=" . urlencode($guestError));
            exit();
        }
        
        if (empty($accessKey)) {
            $guestError = "Access key is required";
            header("Location: login.php?guestError=" . urlencode($guestError));
            exit();
        }
        
        $sql = "SELECT id FROM event WHERE AccessKey = '$accessKey'";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            header("Location: studentInfo.php");
            exit();        
        } else {
            $guestError = "Invalid access key";
            header("Location: login.php?guestError=" . urlencode($guestError));
            exit();
        }
    }
?>