<?php require "_sessionHeader.php" ?>
<?php require_once '_incFunctions.php';
$eventID = $_GET['event'];
$eventSql = "SELECT EventName FROM event WHERE ID = $eventID";
$result = $conn->query($eventSql);
$row = $result->fetch_object();
if ($row==null)
    {
        $eventName = "No event found";
    }
if ($row != null && $row->EventName != null) {
    $eventName = $row->EventName;
}

$activitySql = "SELECT StudentName, StudentID, ActivityID, Level, FinalScore, TimeSpent FROM activities WHERE EventID = $eventID ORDER BY ActivityID DESC";
$activities = $conn->query($activitySql);
?>

<div class="container">
    <div class="row">
        <div class="frame-main col-md-12 col-sm-12">

        <h2 class="border-bottom border-dark"><?php echo $eventName." Activity List" ?></h2>
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
                    while ($row = $activities->fetch_object()) {
                        echo '<tr>';
                        echo '<th>' . $row->StudentName . '</th>';
                        echo '<th>' . $row->StudentID . '</th>';
                        echo '<th>' . $row->ActivityID . '</th>';
                        echo '<th>' . $row->Level . '</th>';
                        echo '<th>' . $row->FinalScore . '</th>';
                        echo '<th>' . $row->TimeSpent . '</th>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require "_footer.php" ?>