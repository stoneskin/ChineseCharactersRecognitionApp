<?php require "_sessionHeader.php" ?>
<script>
    // var studentName="studentName"
    // var testLevel="3"
    var testList=["中国","测试","新年好","花好月圆"];
    var current=0;
    var remain=10;
    var totalTime=0;
    var timer;
    function previousItem(){
        if(current>0){
            current-=1; 
            setTestWord()
        }
        
    }
    function nextItem(){
        
        if((current+1)<testList.length){
            current+=1;
            setTestWord();
        }else{            
            window.location.assign('endTest.php')

        }
    }
    function setTestWord(){
        document.getElementById("boxTestword").innerHTML=testList[current];
        document.getElementById("boxCounter").innerHTML= (current+1)+"/"+(testList.length);
        remain=10;
        if(timer){
            clearTimeout(timer);
        }
        setTimer();
    }
    function setTimer(){
        document.getElementById("boxTimer").innerHTML=remain;
        
        if(remain>=0){
            remain-=1
            totalTime+=1
            timer= setTimeout(setTimer,1000);
        }else{
            nextItem()
        }

    }
    
</script>

        <div class="container">
            <div class="row main">
                <div class="col-sm-2 side-bar">
                    <div onclick="previousItem()" class="label wrap"><</div>
                </div>
                <div class=" col-sm-8  center-board">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <div class="time-block" id="boxCounter">[1/10]</div>
                        </div>
                        <div class="col-xs-6 ">
                            <div class="time-block" id="boxTimer">[time]</div>
                        </div>
                        
                    </div>
                    <div class="label wrap">
                        <div class="test-word" id="boxTestword">
                            测试
                        </div>

                    </div>
                    <div class="row">
                        <div class="frame-botton2 col-xs-6">
                            <div class="button button-tall button-wrong" onclick="nextItem()">
                                <div class="submit">Wrong</div>
                            </div>
                        </div>
                        <div class="frame-botton2 col-xs-6">
                            <div class="button button-tall button-green" onclick="nextItem()">
                                <div class="submit">Correct</div>
                            </div>
                        </div>
                    </div>
        
                </div>
                <div class="col-sm-2 side-bar"><div onclick="nextItem()" class="label wrap">></div>
                </div>
            
            </div>
        </div>
        <div class="footer">
            <div class="c-mlccc-2023">
                <div class="mlccc-2023">© Mlccc 2023</div>
            </div>
        </div>
    </div>
</body>
<script>
    setTestWord();
</script>
</html>