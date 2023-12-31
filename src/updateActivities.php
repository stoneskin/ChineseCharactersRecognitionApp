
<?php
require_once "_needSession.php";
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require_once '_incFunctions.php';
require "connect.php";

$activityidFromSession = $_SESSION["activityid"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $resultlist = json_decode($_POST['data'], TRUE);
    //echo $_POST['data'];
    $questionsCorrect = 0;
    $totalTime=0;
    //save to records table
    $DataArr = array();
    foreach($resultlist as $row){
        $WordID = $conn->real_escape_string($row['id']);
        $Passed = $row['passed']? 1:0;
        $TimeElapsed =$conn->real_escape_string($row['timeElapsed']);    
        $DataArr[] = "($activityidFromSession, $WordID, $Passed, $TimeElapsed)";
        $totalTime+= (int)$TimeElapsed;

        if($Passed == 1){
            $questionsCorrect+=1;
        }
    }
    if(count($DataArr)>0) {
    
        $totalPercent = (int)round($questionsCorrect / count($DataArr) * 100);
        $sql = "INSERT INTO `records` (`ActivityID`, `WordID`, `Passed`, `TimeElapsed`) VALUES  ";
        $sql .= implode(',', $DataArr);
        mysqli_query($conn, $sql); 

        //update activity table
        $sqlUpdateActivity = "UPDATE `activities` SET CompletedTime = CURRENT_TIMESTAMP, FinalScore = ?,TimeSpent = ? WHERE ActivityID = ? ";
        if($stmt = $conn->prepare($sqlUpdateActivity)){
            $stmt->bind_param("iii", $totalPercent,$totalTime, $activityidFromSession);
            $stmt->execute();
            echo "OK! totalPercent=".$totalPercent." , totalTime=".$totalTime;
        }else{
            die("Errormessage: ". $conn->error);
        }
    
   
    }
    
    //header("Location: endTest.php");

}else{
    echo "Error: method no support";
}
?>


