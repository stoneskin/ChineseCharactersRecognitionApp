<?php require "_sessionHeader.php" ?>
<?php require_once '_incFunctions.php';
$myemail = $_SESSION["loginUser"];
$sql = "SELECT GradeID FROM student WHERE Email = '$myemail'";
$result = $conn->query($sql);
$row = $result->fetch_object();
$grade = "N/A";
if ($row != null && $row->GradeID != null) {
    $grade = $row->GradeID;
}
?>

<div class="two-column-frame container">
    <div class="frame col-md-5 col-sm-8">
        <div class="form-title">Account Info</div>
        <div class="label-frame">
            <div class="label">UserName:</div>
            <div class="label"><?php echo $_SESSION["loginUser"]?></div>
        </div>
        <div class="label-frame">
            <div class="label">Grade:</div>
            <div class="label"><?php echo $grade?></div>
        </div>
        <div class="label">
            <a href="editAccount.php"> Edit Account </a> 
        </div>
        <div class="label">
            <a href="changePassword.php"> View Previous Tests </a> 
        </div>
    </div>
</div>

<?php require "_footer.php" ?>