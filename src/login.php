<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // username and password sent from form 
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $myemail = $_POST["email"];
        $mypassword = $_POST["password"]; 

        $sql = "SELECT id FROM user WHERE Email = '$myemail' and Password = '$mypassword'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            header("Location: studentInfo.html");
            exit();        
        } else {
            $error = "Invalid email or password";
            header("Location: login.php?error=" . urlencode($error));
            exit();
        }
    }
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
            </div>
        </div>
        <div class="two-column-frame container">
            <div class="row">
         
            <div class="frame col-md-5 col-sm-8">
                <form action="login.php" method="post">
                    <div class="form-title">Login</div>
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Email</div>
                        </div>
                        <div >
                            <input type="email" name="email" class="textbox-frame form-control" id="txtEmail" aria-describedby="emailHelp" placeholder="Enter email" />
                        </div>
                    </div>
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Password</div>
                        </div>
                        <div >
                            <input type="password" name="password" class="textbox-frame form-control" id="txtPassword" placeholder="Password" />
                        </div>
                    </div>
                    <?php
                        if (isset($_GET['error'])) {
                            $error = $_GET['error'];
                            echo "<p style='color: red;'>$error</p>";
                        }
                    ?>
                    <div class="frame-botton">
                        <div class="frame-botton2">
                            <button class="button submit" type="submit">Log In</button>
                        </div>
                    </div>
                    <div class="don-t-have-an-account-sign-up">
                        <div class="don-t-have-an-account-sign-up2">
                            <span><span class="don-t-have-an-account-sign-up-2-span">Don’t have an account? </span><span
                                    class="don-t-have-an-account-sign-up-2-span2"><a href="signup.html">Sign Up</a></span></span>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="frame col-md-5 col-sm-8">
                <div class="form-title">Guest Judge Login</div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">UserName</div>
                    </div>
                    <div>
                        <input  class="textbox-frame form-control" id="txtUserName"  placeholder="" />
                
                    </div>
                </div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">AccessKey</div>
                    </div>
                    <div >
                        <input type="password" class="textbox-frame form-control" id="txtAccessKey" placeholder="access key" />
                    </div>
                </div>
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button button-wide" onclick="(()=>{window.location.assign('studentInfo.html')})()">
                            <div class="submit">Guest Log In</div>
                        </div>
                    </div>
                </div>
            </div>
            
            </div>
        </div>
   
        <div class="footer">
            <div class="c-mlccc-2023">
                <div class="mlccc-2023">© MLCCC 2023</div>
            </div>
        </div>
    </div>
</body>

</html>