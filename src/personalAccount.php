<?php require "_sessionHeader.php" ?>
<?php require_once '_incFunctions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // username and password sent from form 
    if (isset($_POST["oldPassword"])) {
        $myOldPassword = $conn->real_escape_string(trim($_POST["oldPassword"])); 
        $myNewPassword = $conn->real_escape_string(trim($_POST["newPassword"])); 
        $myNewPasswordRetyped = $conn->real_escape_string(trim($_POST["newPasswordRetyped"])); 
        $myemail = $_SESSION["loginUser"];
        $sql = "SELECT StudentId FROM student WHERE Email = '$myemail' and Password = '$myOldPassword'";
        $result = $conn->query($sql);
        $row = $result->fetch_object();
        
        if ($row != null) {
            if ($myNewPassword == $myNewPasswordRetyped && $myNewPassword != null) {
                $sql = "UPDATE student SET Password = '$myNewPassword' WHERE Email = '$myemail'";
                $conn->query($sql);
                $error = "Password successfully updated";     
            } else if ($myNewPassword != $myNewPasswordRetyped) {
                $error = "New password does not match retyped password";
            } else {
                $error = "New password cannot be empty";
            }
        } else {
            $error = "Incorrect password";
        }
        header("Location: personalaccount.php?error=" . urlencode($error));
        exit();
    }
}
?>

<div class="two-column-frame container">
    <div class="frame col-md-5 col-sm-8">
        <form action="" method="post">
            <div class="form-title">Account Info</div>

            <div class="label-frame">
                <div class="label">UserName:</div>
                <div class="label"><?php echo $_SESSION["loginUser"]?></div>
            </div>
            <div class="input-component">
                <div class="label-frame">
                    <div class="label">Old Password</div>
                </div>
                <div >
                    <input type="password" name="oldPassword" class="textbox-frame form-control" id="txtPassword" placeholder="Old Password" />
                </div>
            </div>
            <div class="input-component">
                <div class="label-frame">
                    <div class="label">New Password</div>
                </div>
                <div >
                    <input type="password" name="newPassword" class="textbox-frame form-control" id="txtPassword" placeholder="New Password" />
                </div>
            </div>
            <div class="input-component">
                <div class="label-frame">
                    <div class="label">Retype New <br> Password</div>
                </div>
                <div >
                    <input type="password" name="newPasswordRetyped" class="textbox-frame form-control" id="txtPassword" placeholder="New Password" />
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
                    <button class="button submit" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require "_footer.php" ?>