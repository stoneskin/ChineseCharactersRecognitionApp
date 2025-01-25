<?php
require_once '_incFunctions.php';
require_once "connect.php";
require_once "modules/MySessionHandler.php";
$session = new MySessionHandler($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // username and password sent from form 
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $myEmail = $conn->real_escape_string(trim($_POST["email"]));
        $mypassword = $conn->real_escape_string(trim($_POST["password"])); 

        $sql = "SELECT id, isAdmin, UserType FROM user WHERE Email = '$myEmail' and Password = '$mypassword'";
        $result = $conn->query($sql);
        $row = $result->fetch_object();
        if ($row != null) {
            
            $_SESSION["SID"] = session_id();
            $_SESSION["loginUser"] = $myEmail;
            $_SESSION["userType"]= $row->UserType;
            $_SESSION["IsAdmin"]= $row->isAdmin;
            $_SESSION["Id"]= $row->id;
            header("Location: studentInfo.php");
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
    $email = sanitizeHTML(trim($_GET['email'])); 
}
?>

<?php require "_header.php"?>
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
                                value="<?php echo $email ?>" />
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
                    <div class="frame-button">
                        <div class="frame-button2">
                            <button class="button submit" type="submit">Log In</button>
                        </div>
                    </div>
                </form>
               
                <div class="don-t-have-an-account-sign-up">
                    <div class="don-t-have-an-account-sign-up2">
                        <span><span class="don-t-have-an-account-sign-up-2-span">Don't have an account? </span><span
                                class="don-t-have-an-account-sign-up-2-span2"><a href="signup.php">Sign Up</a></span>
                                <span class="don-t-have-an-account-sign-up-2-span"> <a href="#" id="forgotPasswordLink">Forgot Password?</a> </span></span>
                    </div>
                    
                </div>
            </div>

            <div class="frame col-md-5 col-sm-8">
                <?php require "_guestLogin.php" ?>
            </div>
            
        </div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title label" id="forgotPasswordModalLabel">Forgot Password</h5>
            </div>
            <div class="modal-body" >
            <div id="forgotPasswordMessage"></div>
                <form id="forgotPasswordForm">
                    <div class="form-group" >
                        <label for="recoveryEmail" class="forgotPassword-label"  >Please enter your email address:</label>       
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control textbox-frame" id="recoveryEmail" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary button forgotPassword-button" >Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#forgotPasswordLink').click(function(e) {
        e.preventDefault();
        $('#forgotPasswordModal').modal('show');
    });

    $('#forgotPasswordForm').submit(function(e) {
        e.preventDefault();
        const email = $('#recoveryEmail').val();
        
        $.ajax({
            url: 'api/recoverPassword.php',
            method: 'POST',
            data: { email: email },
            dataType: 'json', // Specify that we expect JSON response
            success: function(result) {
                $('#forgotPasswordMessage')
                    .removeClass('alert-success alert-danger')
                    .addClass(result.success ? 'alert alert-success' : 'alert alert-danger')
                    .html(result.message)
                    .show();
                
                if (result.success) {
                    $('#forgotPasswordForm').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Password recovery error details:', xhr.responseJSON?.errorDetails);
                $('#forgotPasswordMessage')
                    .removeClass('alert-success')
                    .addClass('alert alert-danger')
                    .html('An error occurred. Please try again later.')
                    .show();
            }
        });
    });
});
</script>



<?php require "_footer.php" ?>