<?php require "_sessionHeader.php" ?>
<?php require_once '_incFunctions.php';
$myemail = $_SESSION["loginUser"];
$userType = $_SESSION["userType"];

$sql = "SELECT ID, GradeID FROM user WHERE Email = '$myemail'";
$result = $conn->query($sql);
$row = $result->fetch_object();
if ($row==null)
    {
        $error = "There are no users with the email $myemail. ";
        header("Location: error.php?error=" . urlencode($error));
    }
$ID = $row->ID;
$grade = "0";
if ($row != null && $row->GradeID != null) {
    $grade = $row->GradeID;
}


//get activity list by StudentID or JudgeName
if ($userType == "student")
{
    $activitySql = "SELECT EventName, Level, FinalScore, StartTime, TimeSpent FROM activities INNER JOIN event on activities.EventID=event.ID WHERE StudentID = ? ORDER BY ActivityID DESC LIMIT 50";
    if($stmtActivity = $conn->prepare($activitySql)){
        $stmtActivity->bind_param("i", $ID);
        $stmtActivity->execute();
    }else{
        die("Errormessage: ". $conn->error);
    }
    $resultActivity = $stmtActivity->get_result();
}
else
{
    $activitySql = "SELECT EventName, Level, FinalScore,  StartTime, TimeSpent, StudentName FROM activities INNER JOIN event on activities.EventID=event.ID WHERE JudgeName = ? ORDER BY ActivityID DESC";
    if($stmtActivity = $conn->prepare($activitySql)){
        $stmtActivity->bind_param("s", $myemail);
        $stmtActivity->execute();
    }else{
        die("Errormessage: ". $conn->error);
    }
    $resultActivity = $stmtActivity->get_result();
}



?>

<script>
$(document).ready(function () {
    $('th').each(function (col) {
        $(this).hover(
                function () {
                    $(this).addClass('focus');
                },
                function () {
                    $(this).removeClass('focus');
                }
        );
        $(this).click(function () {
            if ($(this).is('.asc')) {
                $(this).removeClass('asc');
                $(this).addClass('desc selected');
                sortOrder = -1;
            } else {
                $(this).addClass('asc selected');
                $(this).removeClass('desc');
                sortOrder = 1;
            }
            $(this).siblings().removeClass('asc selected');
            $(this).siblings().removeClass('desc selected');
            var arrData = $('table').find('tbody >tr:has(td)').get();
            arrData.sort(function (a, b) {
                var val1 = $(a).children('td').eq(col).text().toUpperCase();
                var val2 = $(b).children('td').eq(col).text().toUpperCase();
                if ($.isNumeric(val1) && $.isNumeric(val2))
                    return sortOrder == 1 ? val1 - val2 : val2 - val1;
                else
                    return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
            });
            $.each(arrData, function (index, row) {
                $('tbody').append(row);
            });
        });
    });
});

</script>
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

            <br />

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="label">Previous Tests:</div>
                    <table>
                        <tr>
                            <th class="text-center p-1 sortableHeader">Event Name</th>
                            <?php
                            if ($userType == "parent")
                            {
                                echo '<th class="text-center p-1 sortableHeader">Student Name</th>';
                            }
                            ?>
                            <th class="text-center p-1 sortableHeader">Grade Level</th>
                            <th class="text-center p-1 sortableHeader">Score</th>
                            <th class="text-center p-1 sortableHeader">Start Time</th>
                            <th class="text-center p-1 sortableHeader">Time Spent</th>
                        </tr>
                        <?php
                        while ($row = $resultActivity->fetch_object()) {
                            echo '<tr>';
                            echo '<td>' . $row->EventName . '</td>';
                            if ($userType == "parent")
                            {
                                echo '<td>' . $row->StudentName . '</td>';
                            }
                            $level = $row->Level;
                            $gradeSql = "SELECT GradeName FROM grade WHERE GradeID = $level";
                            $gradeResult = $conn->query($gradeSql);
                            $gradeRow = $gradeResult->fetch_assoc();
                            $gradeName = $gradeRow["GradeName"];
                            echo '<td>' . $gradeName . '</td>';
                            echo '<td>' . $row->FinalScore . '</td>';
                            echo '<td>' . $row->StartTime . '</td>';
                            echo '<td>' . $row->TimeSpent . '</td>';
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