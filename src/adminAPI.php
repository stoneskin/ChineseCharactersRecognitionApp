<?php
require_once "_needSession.php";
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require_once '_incFunctions.php';
require "connect.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $eventName = isset($_POST['eventName']) ? $_POST['eventName'] : '';
        $accessKey = isset($_POST['accessKey']) ? $_POST['accessKey'] : '';
        $activeDate = isset($_POST['activeDate']) ? $_POST['activeDate'] : '';
        $expiredDate = isset($_POST['expiredDate']) ? $_POST['expiredDate'] : '';
        $isPrivate = isset($_POST['isPrivate']) ? intval($_POST['isPrivate']) : 0;

        if(empty($id)){   
            // Insert event
            $sql = "INSERT INTO `event` (EventName, AccessKey, ActiveDate, ExpiredDate, isPrivate) VALUES (?, ?, ?, ?, ?);";         
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $eventName, $accessKey, $activeDate, $expiredDate, $isPrivate);
        }else{
            // Update event
            $sql = "UPDATE `event` SET EventName = ?, AccessKey = ?, ActiveDate = ?, ExpiredDate = ?, isPrivate = ? WHERE ID = ?";        
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssii", $eventName, $accessKey, $activeDate, $expiredDate, $isPrivate, $id);
        }
        $stmt->execute();
        $message = 'The event is saved successfully!';
    }
    catch (Exception $e) {
        $message =  'There is an error occured while saving the event.'.$e;
    }
    $stmt->close();
}
else
{
    $message =  'There is an error occured while saving the event.';
}
echo json_encode($message);

//echo $message;
?>