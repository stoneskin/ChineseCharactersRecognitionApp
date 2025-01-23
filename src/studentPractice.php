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

    function  setTestWord(){
        document.getElementById("boxTestword").innerHTML = testList[current].word;
        document.getElementById("boxCounter").innerHTML = (current + 1) + "/" + (testList.length);
        remain = <?php echo $timeLimit ?>;
        timeElapsed = 0;
        currentWord=testList[current].word;
        setEnglishWord(currentWord);
        setEnglishWordAi(currentWord);
        if(timer){
            clearTimeout(timer);
        }
        setTimer();
    }
    function  setEnglishWord(chineseWord){

        const url = `https://clients5.google.com/translate_a/t?client=dict-chrome-ex&sl=auto&tl=en&q=${chineseWord}`;
        
           fetch(url)
            .then(response => response.json())
            .then(translationData => {
                // Process the JSON data
                console.log(translationData);
                if (translationData && translationData[0] && translationData[0][0]) {

                const englishWord = translationData[0][0];
                document.getElementById("boxEnglishWord").innerHTML=englishWord;            

                }

               
            });      
          

        }
    function  setEnglishWordAi(chineseWord){
        //chineseWord=testList[current].word
        const url = `api/getEnglishTranslateAi.php?text=${encodeURIComponent(chineseWord)}`;
        console.log("setEnglishWord2",url);
        fetch(url)
        .then(response => response.json())
        .then(translationData => {
            // Process the JSON data
            console.log("setEnglishWord2=",translationData);
            if (translationData && translationData[0]&& translationData[0].translation_text) {

            const englishWord = translationData[0].translation_text;
            document.getElementById("boxEnglishWord2").innerHTML=" ( "+ englishWord+" )";            

            }

        
        });     

    }

    

    function setTimer(){
        document.getElementById("boxTimer").innerHTML = remain;
        
        if(remain >= 0){
            remain -= 1;
            totalTime += 1;
            timeElapsed += 1;
           // timer = setTimeout(setTimer, 1000);
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
                <div style="display: inline-block; vertical-align: top; margin-left: 5%;">
                    <button id="mic-btn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Speaker_Icon.svg/1024px-Speaker_Icon.svg.png" height="50px" width="50px" alt="Microphone">
                    </button>
                </div>
            </div> <div class="translate-word">
                    <span id="boxEnglishWord"></span> <span  id="boxEnglishWord2" style="color:#a94c94"></span>
                </div>
            <div class="label wrap" style="margin-top:1%">
                <div class="test-word" id="boxTestword">
                    测试
                </div>
            
    </div>
            <div class="row">
                <div class="frame-button2 col-xs-6">
                    <div class="button button-tall button-wrong" onclick="nextItem(false)">
                        <div class="submit">Wrong</div>
                    </div>
                </div>
                <div class="frame-button2 col-xs-6">
                    <div class="button button-tall button-green" onclick="nextItem(true)">
                        <div class="submit">Correct</div>
                    </div>
                </div>
            </div>
        
        </div>
        <div class="col-sm-2 side-bar"><div onclick="nextItem(false)" class="label wrap">></div>
        </div>
            
    </div>
</div>
<?php require "_footer.php" ?>
<script>
    setTestWord();
</script>