<?php
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require "connect.php";
$error = '';
$studentName = '';
$grade = '';

if (isset($_POST['submit'])) {
    $ok = true;
    if (!isset($_POST['studentName']) || $_POST['studentName'] === '') {
        $ok = false;
    } else {
        $studentName = $_POST['studentName'];
    };
    if (!isset($_POST['grade']) || $_POST['grade'] === '') {
        $ok = false;
    } else {
        $grade = $_POST['grade'];
    };

    if ($ok) {
        header("Location: startTest.php?studentName=" . urlencode($studentName) . "&grade=" . urlencode($grade));
    }
    else
    {
        $error = "Invalid Student Name or Grade";
    }
}
?>

<?php require "_sessionHeader.php" ?>
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
                        <input class="textbox-frame form-control" id="txtUserName" name="studentName" placeholder="Enter Student Name">
                        
                    </div>
                </div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">Grade</div>
                    </div>
                    <div  >
                        <select class="form-select form-select-lg mb-3 textbox-frame form-control" name="grade"  aria-label=".form-select-lg example">
                            <option selected>Select Grade</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                          </select>
                      </div>
                </div>
   
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <button class="button" name="submit" type="submit"><div class="submit">Enter</div></button>
                    </div> 
                </div>
      
            </div>
           
          
            
            </div>
        </div>
    </form> 
<?php require "_footer.php" ?>