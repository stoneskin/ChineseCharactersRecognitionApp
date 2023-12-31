<?php
require_once '_incFunctions.php';
require_once "connect.php";
require_once "modules/MySessionHandler.php";
$session = new MySessionHandler($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // username and password sent from form 
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $myemail = $conn->real_escape_string(trim($_POST["email"]));
        $mypassword = $conn->real_escape_string(trim($_POST["password"])); 

        $sql = "SELECT id, isAdmin FROM user WHERE Email = '$myemail' and Password = '$mypassword'";
        $result = $conn->query($sql);
        $row = $result->fetch_object();
        if ($row != null) {
            
            $_SESSION["SID"] = session_id();
            $_SESSION["loginUser"] = $myemail;
            $_SESSION["userType"]= "parent";
            $_SESSION["IsAdmin"]= $row->isAdmin;
            $_SESSION["Id"]= $row->id;
            header("Location: studentInfo.php");
            exit();        
        } 

        $sql = "SELECT StudentId FROM student WHERE Email = '$myemail' and Password = '$mypassword'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
        
            $_SESSION["SID"] = session_id();
            $_SESSION["loginUser"] = $myemail;
            $_SESSION["userType"]= "student";
            header("Location: studentInfo.php?type=student");
            exit();        
        } 
        
        else {
            $error = "Invalid email or password ";
            header("Location: login.php?error=" . urlencode($error));
            
            //echo $error. $sql;
            exit();
        }
    }
}

$email = '';
if (isset($_GET['email'])) {
    $email = sanitizeHTML($_GET['email']); 
}
?>

<?php require "_header.php" ?>
        <div class="two-column-frame container">
            <div class="row">
            <?php
                if ($email !=='')
                    echo "<div style='color: red;' class='label'>$email Registration successful!</div>";
            ?>
            <div class="frame col-md-5 col-sm-8">
                <form action="login.php" method="post">
                    <div class="form-title">Login</div>

                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">UserName</div>
                        </div>
                        <div >
                            <input type="text" name="email" class="textbox-frame form-control" id="txtEmail" aria-describedby="emailHelp" placeholder="Enter Email or UserName" 
                                value=" <?php echo $email ?>" />
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
                            $error = sanitizeHTML($_GET['error']);
                            echo "<p style='color: red;'>$error</p>";
                        }
                    ?>
                    <div class="frame-botton">
                        <div class="frame-botton2">
                            <button class="button submit" type="submit">Log In</button>
                        </div>
                    </div>
                </form>
                <div class="don-t-have-an-account-sign-up">
                    <div class="don-t-have-an-account-sign-up2">
                        <span><span class="don-t-have-an-account-sign-up-2-span">Don’t have an account? </span><span
                                class="don-t-have-an-account-sign-up-2-span2"><a href="signup.php">Sign Up</a></span></span>
                    </div>
                </div>
            </div>

            <div class="frame col-md-5 col-sm-8">
                <?php require "_guestLogin.php" ?>
            </div>
            
        </div>

<?php require "_footer.php" ?>