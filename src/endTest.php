<?php
    require_once "_needSession.php";
    require_once '_incFunctions.php';
    require "connect.php";

    $activityidFromSession = $_SESSION["activityid"];
    $activitiesSql = "SELECT StudentName, Level, FinalScore, TimeSpent FROM activities WHERE ActivityID = '$activityidFromSession'";
    $result = $conn->query($activitiesSql);
    $row = $result->fetch_object();
    
    $studentName = $row->StudentName;
    $level = $row->Level;
    $finalScore = $row->FinalScore;
    $timeSpent = $row->TimeSpent;
?>

<?php require "_sessionHeader.php" ?>
        <div class="container">
            <div class="row">
         
            <div class="frame-main col-md-12 col-sm-12">
               
                    <div class="label wrap">Student: <?php echo $studentName ?>
                    </div>
                    <div class="label wrap">Level: <?php echo $level ?>
                    </div>
                    <div class="label wrap">Score: <?php echo $finalScore ?>%
                    </div>
                    <div class="label wrap">Time used: <?php echo $timeSpent ?> seconds
                    </div>
              
          
              
   
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button button-tall" onclick="(()=>{window.location.assign('studentInfo.php')})()">
                            <div class="submit">Next Test</div>
                        </div>
                    </div>
                </div>
      
            </div>
           
          
            
            </div>
        </div>

        <?php require "_footer.php" ?>