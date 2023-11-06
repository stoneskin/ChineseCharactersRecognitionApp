<?php require "_sessionHeader.php" ?>
        <div class="two-column-frame container">
            <div class="row">
         
            <div class="frame col-md-5 col-sm-8">
                <div class="form-title">Student Info</div>             
          
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">Student</div>
                    </div>
                    <div >
                        <input type="email" class="textbox-frame form-control" id="txtUserName"  placeholder="Enter Student Name">
                        
                    </div>
                </div>
                <div class="input-component">
                    <div class="label-frame">
                        <div class="label">Grade</div>
                    </div>
                    <div  >
                        <select class="form-select form-select-lg mb-3 textbox-frame form-control" aria-label=".form-select-lg example">
                            <option selected>Select Grade</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                          </select>
                      </div>
                </div>
   
                <div class="frame-botton">
                    <div class="frame-botton2">
                        <div class="button" onclick="(()=>{window.location.assign('startTest.html')})()">
                            <div class="submit">Enter</div>
                        </div>
                    </div>
                </div>
      
            </div>
           
          
            
            </div>
        </div>
   
        <?php require "_footer.php" ?>