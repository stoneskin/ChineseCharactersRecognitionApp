<div class="form-title">Guest Judge Login</div>
<form action="guestLoginProcess.php" method="post">
    <div class="input-component">
        <div class="label-frame">
            <div class="label">UserName</div>
        </div>
        <div>
            <input name="username" class="textbox-frame form-control" id="txtUserName" placeholder="">
        </div>
    </div>
    <div class="input-component">
        <div class="label-frame">
            <div class="label">AccessKey</div>
        </div>
        <div>
            <input type="password" name="accessKey" class="textbox-frame form-control" id="txtAccessKey"
                placeholder="access key">
        </div>
    </div>
    <?php
        if (!empty($_GET['guestError'])) {
            $error = $_GET['guestError'];
            echo "<p style='color: red;'>$error</p>";
        }
    ?>
    <div class="frame-botton">
        <div class="frame-botton2">
            <button class="button submit" type="submit">Log In</button>
        </div>
    </div>
</form>