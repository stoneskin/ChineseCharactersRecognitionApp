<?php
require_once '_incFunctions.php';
include "connect.php";
$sql = "SELECT Grade FROM grade";
$result = $conn->query($sql);
?>

<?php require "_header.php" ?>
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
                            <input type="email" class="textbox-frame form-control" id="txtUserName"  placeholder="Enter Student Name">
                            
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
                            <div class="button" onclick="(()=>{window.location.assign('startTest.php')})()">
                                <div class="submit">Enter</div>
                            </div>
                        </div>
                    </div>
                </form>
      
            </div>
           
          
            
            </div>
        </div>
   
<?php require "_footer.php" ?>
