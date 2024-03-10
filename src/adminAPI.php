<?php
require_once "_needSession.php";
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require_once '_incFunctions.php';
require "connect.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{
        $id = $_POST['id'];
        $eventName = $_POST['eventName'];
        $accessKey = $_POST['accessKey'];
        $activeDate = $_POST['activeDate'];
        $expiredDate = $_POST['expiredDate'];

        if(empty($id)){   
            // Insert event
            $sql = "INSERT INTO `event` (`eventName`, `accessKey`, `activeDate`, `expiredDate`) VALUES (?, ?, ?, ?);";         
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $eventName, $accessKey, $activeDate, $expiredDate);
            $stmt->execute();
        }else{
            // Update event
            $sql = "UPDATE `event` SET eventName = ?,accessKey = ?, activeDate= ?, expiredDate =? WHERE ID = ?";        
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $eventName, $accessKey, $activeDate, $expiredDate, $id);
            $stmt->execute();
        }
        $message = 'The event is saved successfully!';
    }
    catch (Exception $e) {
        $message =  'There is an error occured while saving the event.'.$e;
    }
}
else
{
    $message =  'There is an error occured while saving the event.';
}
echo json_encode($message);

//echo $message;
?>