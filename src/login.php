<?php require "_header.php" ?>
        <div class="two-column-frame container">
            <div class="row">
         
            <div class="frame col-md-5 col-sm-8">
                <div class="form-title">Login</div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">Email</div>
                    </div>
                    <div >
                        <input type="email" class="textbox-frame form-control" id="txtEmail" aria-describedby="emailHelp" placeholder="Enter email">
                        
                    </div>
                </div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">Password</div>
                    </div>
                    <div >
                        <input type="password" class="textbox-frame form-control" id="txtPassword" placeholder="Password">
                    </div>
                </div>
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button" onclick="(()=>{window.location.assign('studentInfo.php')})()">
                            <div class="submit" >Log In</div>
                        </div>
                    </div>
                </div>
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