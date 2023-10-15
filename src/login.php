<?php include "header.php" ?>
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
                <div class="form-title">Guest Judge Login</div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">UserName</div>
                    </div>
                    <div>
                        <input  class="textbox-frame form-control" id="txtUserName"  placeholder="">
                
                    </div>
                </div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">AccessKey</div>
                    </div>
                    <div >
                        <input type="password" class="textbox-frame form-control" id="txtAccessKey" placeholder="access key">
                    </div>
                </div>
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button button-wide" onclick="(()=>{window.location.assign('studentInfo.php')})()">
                            <div class="submit">Guest Log In</div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
   
<?php include "footer.php" ?>