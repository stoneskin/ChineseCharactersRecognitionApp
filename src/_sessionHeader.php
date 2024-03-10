<?php
require_once "_needSession.php";
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
                    <img class="logo2" src="images/logo.png" style="cursor:pointer" onclick="(()=>{window.location.assign('studentInfo.php')})()" />
                </div>
                <div class="mlccc-words-test" style="cursor:pointer" onclick="(()=>{window.location.assign('studentInfo.php')})()" >识字比赛<br>Character Recognition Contest</div>

                <ul class="nav navbar-nav navbar-right">
                    <li><span> <?php
                    if (isset($_SESSION["student"])){
                        echo $_SESSION["student"];
                    }                    
                    ?> </span> </li>
                    <li><span>   <?php 
                    if (isset($_SESSION["grade"])){
                        $sql = sprintf("SELECT GradeName FROM grade WHERE GradeId = %s", $_SESSION["grade"]);
                        $result = $conn->query($sql);
                        $row = $result->fetch_object();
                        echo "[Grade $row->GradeName]";
                    }                    
                    ?> </span> </li>

                    <?php
                    if (isset($_SESSION["IsAdmin"]) && $_SESSION["IsAdmin"]){
                        echo "<li> <a href='admin.php'>Admin</a></li>";
                    } else {
                        echo "<li> <a href='personalAccount.php'> MyAccount</a></li>";
                    }
                    ?>  
                    


                    <li> <a href="logout.php">Logout</a></li>
                </ul>
        </div>
        </div>
    