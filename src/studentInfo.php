<?php
require_once '_incFunctions.php';
include "connect.php";

$sql = "SELECT Grade FROM grade";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    if (isset($_POST["student"]) && isset($_POST["grade"])) {
        $student = $conn->real_escape_string(trim($_POST["student"]));
        $grade = $conn->real_escape_string(trim($_POST["grade"]));
        $_SESSION["student"] = $student;
        $_SESSION["grade"] = $grade;
        header("Location: startTest.php".'?studentname='.$student.'&grade='.$grade);
        exit();
    }
}
?>

<?php require "_sessionHeader.php" ?>
        <div class="two-column-frame container">
            <div class="row">
         
            <div class="frame col-md-5 col-sm-8">
                <form action="studentInfo.php" method="post">
                    <div class="form-title">Student Info</div>             
            
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Student</div>
                        </div>
                        <div >
                            <input type="text" name="student" class="textbox-frame form-control" id="txtUserName"  placeholder="Enter Student Name">
                        </div>
                    </div>
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Grade</div>
                        </div>
                        <div  >
                            <?php
                            $options = "<option selected>Select Grade</option>";
                            while ($row = $result->fetch_assoc()) {
                                $optionValue = $row["Grade"];
                                $options .= "<option value=\"$optionValue\">Grade $optionValue</option>";
                            }
                            echo '<select name="grade" class="form-select form-select-lg mb-3 textbox-frame form-control" aria-label=".form-select-lg example">';
                            echo $options;
                            echo '</select>';
                            ?>
                        </div>
                    </div>
                    <div class="frame-botton">
                        <div class="frame-botton2">
                            <button class="button submit" type="submit">Enter</button>
                        </div>
                    </div>
                </form>
      
            </div>
           
          
            
            </div>
        </div>
   
        <?php require "_footer.php" ?>