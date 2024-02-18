<?php require "_needSession.php";?>


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
                <div class="logo" style="cursor:pointer" onclick="(()=>{window.location.assign('studentInfo.php')})()">
                    <img class="logo2" src="images/logo.png" />
                </div>
                <div class="mlccc-words-test" style="cursor:pointer" onclick="(()=>{window.location.assign('studentInfo.php')})()">识字比赛<br>Character Recognition Contest</div>

                <ul class="nav navbar-nav navbar-right">
                <?php
                    if (isset($_SESSION["IsAdmin"])){
                        echo "<li> <a href='admin.php'>Admin</a></li>";
                    }                    
                    ?>  
                    <?php
                        if (isset($_SESSION["userType"]) && $_SESSION["userType"]!= "guest"){
                            echo "<li> <a href='personalAccount.php'> Account</a></li>";
                        }
                    ?>   
                    <li> <a href="logout.php"> Logout</a></li>
                </ul>
        </div>
        </div>
        <div>
            <ul class="adminMenu">
                <li><a href="admin.php">Events</a></li>
                <li><a href="adminWords.php">Words</a></li>
            </ul>
        </div>
        <div>
            <P>
                <?php
                if (!$_SESSION["IsAdmin"]=='1') {
                    $error = "Only admins can access this page.";
                    header("Location: error.php?error=" . urlencode($error));
                } else {
                    echo "Welcome, admin.";
                }
                ?>
            </P>
        </div>
    