<?php require "_sessionHeader.php" ?>
<?php
require_once '_incFunctions.php';

$timeLimit = isset($_COOKIE['timeLimit']) ? sanitizeHTML($_COOKIE['timeLimit']) : 'default_value';
?>

<script>
    var testList = JSON.parse(sessionStorage.getItem("wordlist"));
    var wordItem;

    var current = 0;
    var remain = -1; 
    var timeElapsed = 0;
    var totalTime = 0;
    var timer;

    function readAloud() {
        var testWord = document.getElementById('boxTestword').innerText;
        var utterance = new SpeechSynthesisUtterance(testWord);
        utterance.lang = 'zh-CN'; // Set the language to Chinese
        speechSynthesis.speak(utterance);
    }

    function previousItem(){
        if(current > 0){
            current -= 1; 
            setTestWord();
        }        
    }

    function nextItem(passed){
        testList[current].passed = passed;
        testList[current].timeElapsed = timeElapsed; 

        if((current + 1) < testList.length){
            current += 1;
            setTestWord();
        } else {  
            if(timer){
                clearTimeout(timer);
            }

            sessionStorage.setItem("testResult", JSON.stringify(testList));
            $.post('updateActivities.php', {
                data: JSON.stringify(testList)
            }, function(response) {
                console.log(response);
                if(response.includes("OK!")){
                    window.location.assign('endPractice.php');
                } else {
                    alert(response);
                }
            });
        }
    }

    function setTestWord(){
        document.getElementById("boxTestword").innerHTML = testList[current].word;
        document.getElementById("boxCounter").innerHTML = (current + 1) + "/" + (testList.length);
        remain = <?php echo $timeLimit ?>;
        timeElapsed = 0;
        if(timer){
            clearTimeout(timer);
        }
        setTimer();
    }

    function setTimer(){
        document.getElementById("boxTimer").innerHTML = remain;
        
        if(remain >= 0){
            remain -= 1;
            totalTime += 1;
            timeElapsed += 1;
            timer = setTimeout(setTimer, 1000);
        } else {
            nextItem(false);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var micBtn = document.getElementById('mic-btn');
        micBtn.addEventListener('click', readAloud);
        var nextBtn = document.getElementById('next-btn');
        nextBtn.addEventListener('click', function() {
            nextItem(false);
        });
        setTestWord();
    });

</script>

<div class="container">
    <div class="row main">
        <div class="col-sm-2 side-bar">
            <div onclick="previousItem()" class="label wrap"><</div>
        </div>
        <div class="col-sm-8 center-board">
            <div class="row">
                <div class="col-xs-6">
                    <div class="time-block" id="boxCounter">[1/10]</div>
                </div>
                <div style="display: inline-block;">
                    <div class="time-block" id="boxTimer">[time]</div>
                </div>
                <div style="display: inline-block; vertical-align: top; margin-left: 15px;">
                    <button id="mic-btn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Speaker_Icon.svg/1024px-Speaker_Icon.svg.png" height="50px" width="50px" alt="Microphone">
                    </button>
                </div>
            </div>
            <div class="label wrap">
                <div class="test-word" id="boxTestword">
                    测试
                </div>
            </div>
        </div>
        <div class="col-sm-2 side-bar">
            <div id="next-btn" class="label wrap">></div>
        </div>
    </div>
</div>
<?php require "_footer.php" ?>
<script>
    setTestWord();
</script>