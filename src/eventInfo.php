<?php require "_adminSessionHeader.php" ?>
<?php require_once '_incFunctions.php';

//get event name
$eventID = $_GET['event'];
$eventSql = "SELECT EventName FROM event WHERE ID = ?";      
if($stmt = $conn->prepare($eventSql)){
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
}else{
    die("Errormessage: ". $conn->error);
}
$row = $stmt->get_result()->fetch_assoc();
if ($row==null) {
    $tableTitle = "No event found";
}
if ($row != null && $row['EventName'] != null) {
    $tableTitle = $row['EventName']. " Activity List";
}

//get activity list by EventID
$activitySql = "SELECT StudentName, StudentID, ActivityID, Level, FinalScore, TimeSpent FROM activities WHERE EventID = ? ORDER BY ActivityID DESC";
if($stmtActivity = $conn->prepare($activitySql)){
    $stmtActivity->bind_param("i", $eventID);
    $stmtActivity->execute();
}else{
    die("Errormessage: ". $conn->error);
}
$resultActivity = $stmtActivity->get_result();


?>

<div class="container">
    <div class="row">
        <div class="frame-main col-md-12 col-sm-12">

        <h2 class="border-bottom border-dark"><?php echo $tableTitle?></h2>
            <div class="col-md-12 col-sm-12">
                <table>
                    <tr>
                        <th class="text-center p-1">Student Name #</th>
                        <th class="text-center p-1">Student ID</th>
                        <th class="text-center p-1">Activity #</th>
                        <th class="text-center p-1">Grade Level</th>
                        <th class="text-center p-1">Score</th>
                        <th class="text-center p-1">Time Spent</th>
                    </tr>
                    <?php
                    while ($row = $resultActivity->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["StudentName"] . '</td>';
                        echo '<td>' . $row["StudentID"] . '</td>';
                        echo '<td>' . $row["ActivityID"] . '</td>';
                        $level = $row["Level"];
                        $gradeSql = "SELECT GradeName FROM grade WHERE GradeID = $level";
                        $gradeResult = $conn->query($gradeSql);
                        $gradeRow = $gradeResult->fetch_assoc();
                        $gradeName = $gradeRow["GradeName"];
                        echo '<td>' . $gradeName . '</td>';
                        echo '<td>' . $row["FinalScore"] . '</td>';
                        echo '<td>' . $row["TimeSpent"] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require "_footer.php" ?>