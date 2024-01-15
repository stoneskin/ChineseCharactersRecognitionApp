<?php
require_once '_incFunctions.php';
require "connect.php";
$error = '';
$email = '';
$password = '';

if (isset($_POST['submit'])) {
    $ok = true;
    if (!isset($_POST['email']) || $_POST['email'] === '') {
        $ok = false;
    } else {
        $email = $_POST['email'];
    };
    if (!isset($_POST['password']) || $_POST['password'] === '') {
        $ok = false;
    } else {
        $password = $_POST['password'];
    };

    if ($ok) {
      //to do next: encrypt password
      //$password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = sprintf(
                "INSERT INTO user (Email, Password, UserType) VALUES ('%s', '%s', '%s')",
                $conn->real_escape_string($email),
                $conn->real_escape_string($password),
                ($_POST['userOptions'] === 'student') ? 'student' : 'parent'
            );

            if (!$conn->query($sql)) {
                $error = $conn->error;
            }
            $conn->close();
            if($error == '') {
                header("Location: login.php?email=" . urlencode(sanitizeHTML($email)));
            }
        }
        catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    else
    {
        $error = "Invalid email or password";
    }
}
?>

<?php require "_header.php" ?>
<form action="" method="post">
            <div class="two-column-frame container">
                <div class="row">
            
                <div class="frame col-md-5 col-sm-8">
                    <div class="form-title">Sign Up</div>             
                    
                    <div class="input-component padding20">
                        <div >
                            <input class="form-check-input" type="radio" name="userOptions" id="inlineRadio1" value="student" checked>
                            <label class="form-check-label" for="inlineRadio1">Student</label>
                        </div>
                        <div >
                            <input class="form-check-input" type="radio" name="userOptions" id="inlineRadio2" value="judge">
                            <label class="form-check-label" for="inlineRadio2">Judge/Parent</label>
                        </div>
                    </div>
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Email/UserName</div>
                        </div>
                        <div >
                            <input class="textbox-frame form-control" id="txtEmail" name="email" aria-describedby="emailHelp" placeholder="Enter email or username">
                        </div>
                    </div>
                    <!--
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Username</div>
                        </div>
                        <div >
                            <input type="email" class="textbox-frame form-control" id="txtUserName"  placeholder="Enter UserName">
                            
                        </div>
                    </div>
                    -->
                    
                    <div class="input-component">
                        <div class="label-frame">
                            <div class="label">Password</div>
                        </div>
                        <div >
                            <input type="password" class="textbox-frame form-control" id="txtPassword" name="password" placeholder="Password">
                        </div>
                    </div>
                    <?php
                        if ($error !== '') {
                            echo "<p style='color: red;'>$error</p>";
                        }
                    ?>
                    <div class="frame-botton">
                        <div class="frame-botton2">
                            <!--
                            <div class="button">
                                <div class="submit">Sign Up</div>         
                            </div>
                            -->
                            <button class="button" name="submit" type="submit"><div class="submit">Sign Up</div></button>
                        </div>
                    
                    </div>
                </div>
           

            </form>
   
<?php require "_footer.php" ?>