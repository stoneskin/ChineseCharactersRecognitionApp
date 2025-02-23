<?php require "_sessionHeader.php" ?>
<?php
require_once '_incFunctions.php';
require 'vendor/autoload.php';

use OpenAI\Client;

$timeLimit = isset($_COOKIE['timeLimit']) ? sanitizeHTML($_COOKIE['timeLimit']) : 'default_value';

function gradeAnswer($recognizedText, $correctAnswer) {
    $client = new Client('YOUR_OPENAI_API_KEY');
    $prompt = "Grade the recognized text based on tone. If the tone is not correct for one character, take off 0.2 of the 1 point. If both are wrong, the score is 0.6. If the word is completely wrong, it is 0 points. Return the recognized text, correct answer, and score.\n\nRecognized text: \"$recognizedText\"\nCorrect answer: \"$correctAnswer\"";

    $response = $client->completions()->create([
        'model' => 'text-davinci-003',
        'prompt' => $prompt,
        'max_tokens' => 50,
    ]);

    $result = $response['choices'][0]['text'];
    return json_decode($result, true);
}
?>

<script>
    var testList = JSON.parse(sessionStorage.getItem("wordlist"));
    if (!testList || testList.length === 0) {
        console.error("testList is empty or not defined.");
        alert("The word list is empty or not defined. Please ensure the word list is properly loaded.");
    } else {
        console.log("testList:", testList); 
    }
    var wordItem;

    var current = 0;
    var remain = -1; 
    var timeElapsed = 0;
    var totalTime = 0;
    var timer;

    let recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    let textGenerator;
    let allTextDiv;
    let backgroundBlock;
    let languageToggle;
    let allText = '';
    let isRecognizing = false;
    let isEnglish = true; 

    recognition.interimResults = true;
    recognition.maxAlternatives = 1;
    recognition.lang = 'zh-CN';

    recognition.onstart = () => {
        console.log("Speech recognition service has started");
    };

    recognition.onresult = event => {
        let text = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            text += event.results[i][0].transcript;
        }
        console.log("Recognized text:", text); // Debugging statement
        textGenerator.innerText = text;
        
        if (event.results[0].isFinal) {
            allText += text + ' ';
            console.log("Final recognized text:", text); // Debugging statement
            checkAnswer(text); // Check the answer when the speech recognition result is final
        }
    };

    recognition.onerror = event => {
        console.error('Error:', event);
        if (event.error === 'aborted') {
            console.log("Recognition aborted, restarting...");
            setTimeout(startRecognition, 1000); // Retry after 1 second
        } else if (event.error === 'no-speech') {
            console.log("No speech detected, retrying...");
            setTimeout(startRecognition, 1000); // Retry after 1 second
        } else {
            console.error("Speech recognition error:", event.error);
        }
    };

    recognition.onend = () => {
        console.log("Recognition ended, restarting...");
        isRecognizing = false;
        setTimeout(startRecognition, 1000); // Restart recognition after 1 second
    };

    function startRecognition() {
        console.log("Starting recognition...");
        let resultElement = document.getElementById('result');
        resultElement.innerHTML = '<h2 class="form-title">Listening...</h2>'; // Show "Listening..." when recognition starts
        if (!isRecognizing) {
            try {
                recognition.start();
                isRecognizing = true;
                console.log("Recognition started.");
            } catch (error) {
                console.error("Error starting recognition:", error);
            }
        }
    }

    async function checkAnswer(recognizedText) {
        console.log("checkAnswer called with:", recognizedText); // Debugging statement
        if (!testList || testList.length === 0) {
            console.error("testList is empty or not defined.");
            return;
        }

        let correctAnswer = testList[current].word;
        let resultElement = document.getElementById('result');
        
        console.log("Correct answer:", correctAnswer); // Debugging statement
        console.log("Recognized text:", recognizedText); // Debugging statement

        // Call the PHP function to grade the answer
        let response = await fetch('gradeAnswer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ recognizedText, correctAnswer })
        });

        let result = await response.json();
        console.log("Grading result:", result); // Debugging statement

        let score = result.score;
        console.log("Score:", score); // Debugging statement

        console.log("Updating result element..."); // Debugging statement
        if (score === 1) {
            resultElement.innerHTML = '<h2 class="form-title" style="color: #18b740de;">Correct!</h2>';
        } else {
            resultElement.innerHTML = `<h2 class="form-title" style="color: #ba4040;">Incorrect! Score: ${score}</h2>`;
        }
        console.log("Updated result element:", resultElement.innerHTML); // Debugging statement

        // Update the testList with the result
        testList[current].result = score;
        // Save the updated testList to session storage
        sessionStorage.setItem("wordlist", JSON.stringify(testList));

        // Move to the next item
        nextItem(score === 1);
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
        if (testList && testList.length > 0) {
            document.getElementById("boxTestword").innerHTML = testList[current].word;
            document.getElementById("boxCounter").innerHTML = (current + 1) + "/" + (testList.length);
            remain = <?php echo $timeLimit ?>;
            timeElapsed = 0;
            if(timer){
                clearTimeout(timer);
            }
            setTimer();
        } else {
            console.error("testList is empty or not defined.");
        }
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
        textGenerator = document.getElementById('text-generator');
        allTextDiv = document.getElementById('all-text');
        backgroundBlock = document.getElementById('background-block');
        languageToggle = document.getElementById('language-toggle');

        var nextBtn = document.getElementById('next-btn');
        nextBtn.addEventListener('click', function() {
            nextItem(false);
        });
        setTestWord();
        startRecognition(); // Start voice recognition when the page loads
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
                        <img src="https://static.vecteezy.com/system/resources/previews/014/391/889/original/microphone-icon-on-transparent-background-microphone-icon-free-png.png" height="50px" width="80px" alt="Microphone">
                    </button>
                </div>
            </div>
            <div class="label wrap text-center">
                <div class="test-word" id="boxTestword">
                    测试
                </div>
            </div>
            <div id="text-generator" style="display: none;"></div> <!-- Add this element -->
        </div>
        <div class="col-sm-2 side-bar">
            <div id="next-btn" class="label wrap">></div>
        </div>
    </div>
</div>
<div id="result-container" style="display: flex; justify-content: center; align-items: center; margin-top: 5px;">
    <div id="result" class="form-title"></div> <!-- New element to display the result -->
</div>
<?php require "_footer.php" ?>
<script>
    setTestWord();
</script>