<?php
  require_once "connect.php";
  require_once "modules/MySessionHandler.php";
  $session = new MySessionHandler($conn);

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
            
        $sql = sprintf("SELECT Id FROM event WHERE AccessKey = '%s' and ExpiredDate> CURRENT_DATE() and ActiveDate<=CURRENT_DATE() order by Id desc",$conn->real_escape_string($accessKey));
        
        $result = $conn->query($sql);
        $row = $result->fetch_object();

        if ($row != null) {
          
            $_SESSION["SID"] = session_id();
            $_SESSION["loginUser"] = $username;
            $_SESSION["userType"]= "guest";
           // $_SESSION["EventId"]= $row->Id;
            header("Location: studentInfo.php");
            exit();  
        } else {
            $guestError = "Invalid access key";
            header("Location: login.php?guestError=" . urlencode($guestError));
            exit();
        }
    }
?>