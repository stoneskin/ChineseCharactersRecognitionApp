<?php require "_sessionHeader.php" ?>
<?php require_once '_incFunctions.php';
$myemail = $_SESSION["loginUser"];
$userType =$_SESSION["userType"];



$studentSql = "SELECT StudentID, GradeID FROM student WHERE Email = '$myemail'";
$result = $conn->query($studentSql);
$row = $result->fetch_object();
if($row==null)
    {
        $error = "User $myemail is not a student. ";
        header("Location: error.php?error=" . urlencode($error));
    }
$studentID = $row->StudentID;
$grade = "N/A";
if ($row != null && $row->GradeID != null) {
    $grade = $row->GradeID;
}

$activitySql = "SELECT ActivityID, Level, FinalScore, TimeSpent FROM activities WHERE StudentID = $studentID ORDER BY ActivityID DESC";
$activities = $conn->query($activitySql);
?>

<div class="container">
    <div class="row">
        <div class="frame-main col-md-12 col-sm-12">
            <div class="form-title">Account Info</div>
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="label">UserName:</div>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="label">
                        <?php echo $_SESSION["loginUser"] ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="label">Grade:</div>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="label">
                        <?php echo $grade ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="label">
                        <a href="editAccount.php">Edit Account</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="label">Previous Tests:</div>
                    <table>
                        <tr>
                            <th>Test #</th>
                            <th>Grade Level</th>
                            <th>Score</th>
                            <th>Time Spent</th>
                        </tr>
                        <?php
                        while ($row = $activities->fetch_object()) {
                            echo '<tr>';
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
</div>

<?php require "_footer.php" ?>