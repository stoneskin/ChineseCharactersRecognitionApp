<?php require "_sessionHeader.php" ?>
<?php
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require_once '_incFunctions.php';
require_once "connect.php";

$errorStudent = '';
$errorGrade = '';
$errorEvent='';

$student= isset($_SESSION["student"]) ? sanitizeHTML($_SESSION["student"]) : "";
$grade=isset($_SESSION["grade"]) ? sanitizeHTML($_SESSION["grade"]) : "";

// load Grade
$sql = "SELECT Grade FROM grade";
$result = $conn->query($sql);
$sqlEvent="SELECT Id FROM `event` WHERE ExpiredDate> CURRENT_DATE() and ActiveDate<=CURRENT_DATE() ORDER by Id DESC";
$resultEvent = $conn->query($sqlEvent);
$rowEvent = $resultEvent->fetch_object();
if ($rowEvent!=null) {
    $eventID = $rowEvent->Id;
    $_SESSION["EventId"]= $eventID;
    
}else{
    $errorEvent="No Active Event Available.";
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $errorEvent=='') {  
    if (isset($_POST["student"]) && isset($_POST["grade"]) ) {
        $student = $conn->real_escape_string(trim(sanitizeHTML($_POST["student"])));
        $grade = $conn->real_escape_string(trim(sanitizeHTML($_POST["grade"])));
    
        if($student==''){
            $errorStudent="Please Input Student Name!";
        }

        if($grade==''){
            $errorGrade="Please Select grade!";
        }

        if($errorStudent=='' && $errorGrade==''){
            $_SESSION["student"] = $student;
            $_SESSION["grade"] = $grade;
            $username = $_SESSION["loginUser"];
            $findStudentSql = "SELECT StudentID FROM student WHERE Email = $username";
            $studentID = $conn->query($sql);


            $activitySql = "INSERT INTO `ccrApp`.`activities` (`EventID`, `StudentName`, `StudentID`, `JudgeName`, `Level`) VALUES (?, ?, ?, ?, ?);";
            
            $stmt = $conn->prepare($activitySql);

            $judge = $username;
            $stmt->bind_param("isisi", $eventID, $_SESSION["loginUser"], $studentID, $judge, $grade);
            $stmt->execute();

            $_SESSION["activityid"] =  mysqli_insert_id( $conn);

            header("Location: startTest.php".'?studentname='.$student.'&grade='.$grade);
        }

    }

}
?>


<form action="" method="post">
        <div class="two-column-frame container">
            <div class="row">

            <div class="frame col-md-5 col-sm-8">

                    <div class="form-title">Student Info</div>             
            
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Student</div>
                        </div>
                        <div >
                            <input type='text' name='student' class='textbox-frame form-control' id='txtUserName'  placeholder='Enter Student Name' value="<?php  echo $student ?>">
                            
                        </div>

                    </div>     <?php
                            if ($errorStudent!='') {
                                echo "<div class='errorMessage'>$errorStudent</div>";
                            }
                            ?>
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Grade</div>
                        </div>
                        <div  >
                            <?php
                            $options = "<option  value=''>Select Grade</option>";
                            while ($row = $result->fetch_assoc()) {
                                $optionValue = $row["Grade"];
                                $Selected="";
                                if($optionValue==$grade)
                                    $Selected="selected";
                                $options .= "<option value=\"$optionValue\" ".$Selected.">Grade $optionValue</option>";
                            }
                            echo '<select name="grade" class="form-select form-select-lg mb-3 textbox-frame form-control" aria-label=".form-select-lg example">';
                            echo $options;
                            echo '</select>';
                            ?>

                        </div>
                    </div>
                        <?php
                            if ($errorGrade!='') {
                                echo "<div class='errorMessage'>$errorGrade</div>";
                            }

                            if ($errorEvent!='') {
                                echo "<div class='errorMessage'>$errorEvent</div>";
                            }

                        ?>
             
                    <div class="frame-botton">
                        <div class="frame-botton2">
                            <button class="button submit" type="submit">Enter</button>
                        </div>
                    </div>
             

      
            </div>
           
          
            
            </div>
        </div>
    </form> 
<?php require "_footer.php" ?>