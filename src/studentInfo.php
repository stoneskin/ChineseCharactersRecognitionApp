<?php require "_sessionHeader.php" ?>
<?php
require_once '_incFunctions.php';
require_once "connect.php";

$errorStudent = '';
$errorGrade = '';
$errorEvent='';
$_SESSION["activityid"]=null;


//clear selected students if return the student info page
if(isset($_SESSION["student"]) || isset($_SESSION["grade"])){
    $_SESSION["student"]=null;
    $_SESSION["grade"] = null;
    header("Location: studentInfo.php");
}


$student="";// isset($_SESSION["student"]) ? sanitizeHTML($_SESSION["student"]) : "";
$grade="";//isset($_SESSION["grade"]) ? sanitizeHTML($_SESSION["grade"]) : "";

$userType=  $_SESSION["userType"];
if($userType == 'student'){
    $student=$_SESSION["loginUser"];  
}





// load Grade
$sql = "SELECT GradeId, GradeName FROM grade";
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

$student = $_SESSION["userType"] == 'student' ? sanitizeHTML($_SESSION["loginUser"]) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $errorEvent=='') {  
    if($_SESSION["userType"] != 'student' && isset($_POST["student"])) {
        $student = $conn->real_escape_string(trim(sanitizeHTML($_POST["student"])));
    }
    $grade = $conn->real_escape_string(trim(sanitizeHTML($_POST["grade"])));
    if($grade==''){
        $errorGrade="Please Select grade!";
    }

    if($errorStudent=='' && $errorGrade==''){
        $_SESSION["student"] = $student;
        $_SESSION["grade"] = $grade;
        $sql = sprintf("SELECT GradeName FROM grade WHERE GradeId = %s", $grade);
        $result = $conn->query($sql);
        $row = $result->fetch_object();
        $_SESSION["gradeName"] = $row->GradeName;

        $username = $_SESSION["loginUser"];
        $findStudentSql = "SELECT ID FROM user WHERE Email = '$student'";
        $resultStu = $conn->query($findStudentSql);
        $studentID=null;
        $msg="check student";
        if($rowStu = $resultStu->fetch_object()){
            $studentID=$rowStu->ID;
            $msg= "GetStudentId=".$studentID;
        }
        else{
            // insert a row for student
            $sql = sprintf(
                "INSERT INTO user (Email, Password, UserType,GradeID) VALUES ('%s', '%s', '%s',%u)",
                $student,
                generateRandomString(6),
                'student',
                $grade
            );

            if (!$conn->query($sql)) {
                $error = $conn->error;
                throw new Exception($error);
            }

            $resultStu = $conn->query($findStudentSql);                    
            if($rowStu = $resultStu->fetch_object()){
                $studentID=$rowStu->ID;
            }
        }

        $activitySql = "INSERT INTO `activities` (`EventID`, `StudentName`, `StudentID`, `JudgeName`, `Level`,`isPractice`) VALUES (?, ?, ?, ?, ?,?);";

        $isPractice= ($_SESSION["userType"] == 'student' || isset($_POST['practice'])) ? 1 : 0;
        
        if($stmt = $conn->prepare($activitySql)){
            $judge = $username;
            $stmt->bind_param("isisii", $eventID, $student, $studentID, $judge, $grade,$isPractice);
            $stmt->execute();
        }else{
            die("Errormessage: ". $conn->error);
        }

        $_SESSION["activityid"] =  mysqli_insert_id( $conn);

        if ($isPractice==1) {
            header("Location: startPractice.php".'?studentname='.$student.'&grade='.$grade);
        } else {
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
                            <div class="label">Grade</div>
                        </div>
                        <div  >
                            <?php
                            $options = "<option  value=''>Select Grade</option>";
                            while ($row = $result->fetch_assoc()) {
                                $optionValue = $row["GradeId"];
                                $optionName = $row["GradeName"];
                                $Selected="";
                                if($optionValue==$grade)
                                    $Selected="selected";
                                $options .= "<option value=\"$optionValue\" ".$Selected.">$optionName</option>";
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

                        ?>
                     
            
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">Student Name</div>
                    </div>

                    <?php if ($_SESSION["userType"] == 'student'): ?>
                        <?php echo $student ?>
                    <?php else: ?>
                        <div class="input-component position-relative">
                            <input type='text' name='student' class='textbox-frame form-control' id='txtUserName'  placeholder='Enter Student Name' value="">
                            <div id="studentList" class="dropdown-menu position-absolute w-100"></div>
                        </div>          
                    <?php endif; ?>

                </div>     
                <?php
                    if ($errorStudent!='') {
                        echo "<div class='errorMessage'>$errorStudent</div>";
                    }
                    ?>     
                    <?php
                        if ($errorEvent!='') {
                            echo "<div class='errorMessage'>$errorEvent</div>";
                        }
                    ?>       
                    <div class="frame-button">
                        <div class="frame-button2">
                            <button class="button submit" type="submit">Enter</button>
                            <?php if ($_SESSION["userType"] != 'student'): ?>
                                <button class="button submit" type="submit" name="practice">Practice</button>
                            <?php endif; ?>
                        </div>
                    </div>
      
           
           
          
            
            </div>
        </div>
</form> 
<script>
    //clear sessionStorage
    sessionStorage.clear();

    document.addEventListener('DOMContentLoaded', function() {
        const studentInput = document.getElementById('txtUserName');
        const studentList = document.getElementById('studentList');

        studentInput.addEventListener('input', function() {
            const query = studentInput.value;
            if (query.length > 0) {

                if (sessionStorage.getItem('recentStudents')) {
                    const data = JSON.parse(sessionStorage.getItem('recentStudents'));
                    let matches = data.filter(student => (student.toLowerCase().startsWith(query.toLowerCase()) || student.toLowerCase().includes(' ' + query.toLowerCase())));
                    displayMatches(matches);
                } else {
                    fetch('api/getRecentStudents.php')
                        .then(response => response.json())
                        .then(data => {
                            sessionStorage.setItem('recentStudents', JSON.stringify(data));
                            let matches = data.filter(student => (student.toLowerCase().startsWith(query.toLowerCase()) || student.toLowerCase().includes(' ' + query.toLowerCase())));
                            displayMatches(matches);
                        });
                }

            } else {
                studentList.innerHTML = '';
                studentList.classList.remove('show');
            }
        });

        function displayMatches(matches) {
            if (matches.length > 0) {

                studentList.innerHTML = matches.map(match => `<a class="dropdown-item d-block">${match}</a>`).join(' ');

                studentList.classList.add('show');
                document.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function() {
                        studentInput.value = this.textContent;
                        studentList.innerHTML = '';
                        studentList.classList.remove('show');
                    });
                });
            } else {
                studentList.innerHTML = '';
                studentList.classList.remove('show');
            }
        }
    });
</script>
<?php require "_footer.php" ?>
