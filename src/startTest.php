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


<html>
<head>
    <title> MLCCC 识字比赛
    </title>
 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <link rel="stylesheet" type="text/css" href="css\styles.css" />
</head>

<body>

    <div class="responsive">
        <div class="header">
            <div class="header2">
                <div class="logo">
                    <img class="logo2" src="images/logo.png" />
                </div>
                <div class="mlccc-words-test">MLCCC Words Test</div>
                <ul class="nav navbar-nav navbar-right">
                <li><span> <?php echo "$studentName" ?> </span> </li>
                <li><span> Level <?php echo "$grade" ?> </span> </li>
                <li> <a href="login.html"> Logout</a></li>
              </ul>
            </div>
      
        </div>
        <div class="container">
            <div class="row">
         
            <div class="frame-main col-md-12 col-sm-12">
               
                    <div class="label wrap">Welcome to Level <?php echo "$grade" ?>! In this level, the students will identify <?php echo "$numberOfWords" ?> Chinese characters,
                        If the student identifies the character correctly, press the green button, if they are incorrect, press the red button. 
                        Each character will be displayed for <?php echo "$timeLimit" ?> seconds, if the time runs out, it will be considered incorrect.
                    </div>
              
          
              
   
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button button-tall" onclick="(()=>{window.location.assign('testBoard.html')})()">
                            <div class="submit">Start</div>
                        </div>
                    </div>
                </div>
      
            </div>
           
          
            
            </div>
        </div>
   
        <div class="footer">
            <div class="c-mlccc-2023">
                <div class="mlccc-2023">© Mlccc 2023</div>
            </div>
        </div>
    </div>
</body>

</html>