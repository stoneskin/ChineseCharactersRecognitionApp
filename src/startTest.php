<?php
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require "connect.php";
$error = '';
$studentName = '';
$grade = '';
$numberOfWords = 0;
$timeLimit = 0;

    try {
        if (isset($_GET['studentName'])) {
            $studentName = $_GET['studentName'];
        };
        if (isset($_GET['grade'])) {
            $grade = $_GET['grade'];

            $sql = sprintf(
                "SELECT NumberOfWords, TimeLimit FROM grade WHERE grade = %s",
                $conn->real_escape_string($grade));

            $result = $conn->query($sql);
            $row = $result->fetch_object();

            if ($row != null) {
                $numberOfWords = $row->NumberOfWords;
                $timeLimit = $row->TimeLimit;

                //continue to get word list from database based on grade and NumberOfWords
                $sql_words = sprintf(
                    "SELECT ID, Words FROM wordslibrary WHERE Level = %s  ORDER BY RAND() limit %s",
                    $conn->real_escape_string($grade),
                    $conn->real_escape_string($numberOfWords));

                $result = $conn->query($sql_words);       
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                setcookie('wordlist', serialize($rows), time()+3600);
                setcookie('timeLimit', $timeLimit, time()+3600);


            } else {
                $error = 'Invalid grade.';
            }
            $conn->close();
        };
    }
    catch (Exception $e) {
        $error = "Invalid grade";
    }
?>


<?php require "_sessionHeader.php" ?>


        <div class="container">
            <div class="row">
         
            <div class="frame-main col-md-12 col-sm-12">
               
                
                <div class="label" style="margin-bottom:10px;">Welcome to  Level &nbsp <b><?php echo "$grade" ?></b>&nbsp! 
                </div>
                <div class="label"  style="margin-top:10px;margin-bottom:10px;">In this level, the students will identify &nbsp <b><?php echo "$numberOfWords" ?></b> &nbsp Chinese characters,
                </div>
                <div class="label"  style="margin-top:10px;margin-bottom:10px;">
                    If the student identifies the character correctly, press the green button, if they are incorrect, press the red button. 
                    </div>
                <div class="label"  style="margin-top:10px;;margin-bottom:10px;">
                    Each character will be displayed for &nbsp <b> <?php echo "$timeLimit" ?> </b>&nbsp seconds,
                    </div>
                <div class="label"  style="margin-top:10px;">
                    if the time runs out, it will be considered incorrect.
                </div>
                
              
   
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button button-tall" onclick="(()=>{window.location.assign('testBoard.php')})()">
                            <div class="submit">Start</div>
                        </div>
                    </div>
                </div>
      
            </div>
           
          
            
            </div>
        </div>
   
<?php require "_footer.php" ?>