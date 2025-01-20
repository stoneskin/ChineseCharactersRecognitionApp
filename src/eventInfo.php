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
$activitySql = "SELECT StudentName, StudentID, ActivityID, g.GradeName, FinalScore, TimeSpent,isPractice FROM activities a join grade g on g.GradeId=a.level WHERE EventID = ? ORDER BY ActivityID DESC";
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
                        <th class="text-center p-1">IsPractice</th>
                    </tr>
                    <?php
                    while ($row = $resultActivity->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["StudentName"] . '</td>';
                        echo '<td>' . $row["StudentID"] . '</td>';
                        echo '<td>' . $row["ActivityID"] . '</td>';                     
                        echo '<td>' . $row["GradeName"] . '</td>';
                        echo '<td>' . $row["FinalScore"] . '</td>';
                        echo '<td>' . $row["TimeSpent"] . '</td>';
                        echo '<td>' . $row["isPractice"] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require "_footer.php" ?>