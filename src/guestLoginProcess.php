<?php
    include "connect.php";

    $guestError="";
    $username=trim($_POST["username"]);;
    $accessKey=trim($_POST["accessKey"]);
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
            
        $sql = sprintf("SELECT id FROM event WHERE AccessKey = '%s'",$conn->real_escape_string($accessKey));
        
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            session_start();
            $_SESSION["SID"] = session_id();
            $_SESSION["loginUser"] = $username;
            $_SESSION["userType"]= "guest";
            header("Location: studentInfo.php");
            exit();  
        } else {
            $guestError = "Invalid access key";
            header("Location: login.php?guestError=" . urlencode($guestError));
            exit();
        }
    }
?>