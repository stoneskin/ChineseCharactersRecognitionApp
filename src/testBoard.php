<?php require "_sessionHeader.php" ?>
<?php
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
require_once '_incFunctions.php';
require "connect.php";

$activityidFromSession = $_SESSION["activityid"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultlist = json_decode($_POST['data'], TRUE);
    echo $_POST['data'];
    $questionsCorrect = 0;
    //save to records table
    $DataArr = array();
    foreach($resultlist as $row){
        $WordID = $row['id'];
        $Passed = $row['passed']? 1:0;
        $TimeElapsed =$row['timeElapsed'];    
        $DataArr[] = "($activityidFromSession, $WordID, $Passed, $TimeElapsed)";

        if($Passed == 1){
            $questionsCorrect+=1;
        }
    }
    $totalPercent = (int)round($questionsCorrect / count($DataArr) * 100);
    $sql = "INSERT INTO `ccrApp`.`records` (`ActivityID`, `WordID`, `Passed`, `TimeElapsed`) VALUES  ";
    $sql .= implode(',', $DataArr);
    mysqli_query($conn, $sql); 

    //update activity table
    $sqlUpdateActivity = "UPDATE `ccrApp`.`activities` SET CompletedTime = CURRENT_TIMESTAMP, FinalScore = ? WHERE ActivityID = ? ";
    $stmt = $conn->prepare($sqlUpdateActivity);
    $stmt->bind_param("ii", $totalPercent, $activityidFromSession);
    $stmt->execute();

    //header("Location: endTest.php");
}
?>

<script>
    // var studentName="studentName"
    // var testLevel="3"
   // var testList=["中国","测试","新年好","花好月圆"];
  
   var testList = [];
   var wordItem;
   <?php 
   $list = unserialize(sanitizeHTML($_COOKIE['wordlist']));
   $timeLimit=sanitizeHTML($_COOKIE['timeLimit']);
   foreach ($list as $item) : 
   ?>
    wordItem = {
        id: <?php echo $item['ID']?>,
        word: "<?php echo $item['Words']?>",
        passed:null,
        timeElapsed:null
        };

    testList.push(wordItem);
   <?php endforeach; ?>


    var current=0;
    var remain=-1; 
    var timeElapsed=0;
    var totalTime=0;
    var timer;

    function previousItem(){
        if(current>0){
            current-=1; 
            setTestWord()
        }        
    }
    function nextItem(passed){
        testList[current].passed=passed;
        testList[current].timeElapsed=timeElapsed; 

        if((current+1)<testList.length){
            current+=1;
            setTestWord();
        }else{  
            if(timer){
                clearTimeout(timer);
            }
            $.post('testBoard.php', {
                data: JSON.stringify(testList)
            }, function(response) {
                //console.log(response);
                window.location.assign('endTest.php')
            });
        }
    }

    function setTestWord(){
        document.getElementById("boxTestword").innerHTML=testList[current].word;
        document.getElementById("boxCounter").innerHTML= (current+1)+"/"+(testList.length);
        remain=<?php echo $timeLimit?>;
        timeElapsed=0;
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
            timeElapsed+=1
            timer= setTimeout(setTimer,1000);
        }else{
            nextItem(false)
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
                            <div class="button button-tall button-wrong" onclick="nextItem(false)">
                                <div class="submit">Wrong</div>
                            </div>
                        </div>
                        <div class="frame-botton2 col-xs-6">
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