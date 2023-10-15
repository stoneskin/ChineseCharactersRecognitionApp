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
            header("Location: studentInfo.php");
            exit();        
        } else {
            $error = "Invalid email or password";
            header("Location: login.php?error=" . urlencode($error));
            exit();
        }
    }
}
?>

<?php require "_header.php" ?>
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