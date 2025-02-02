<?php require "_adminSessionHeader.php" ?>
<?php require_once '_incFunctions.php';

$userID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get user details
$sql = "SELECT user.*, grade.GradeName 
        FROM user 
        LEFT JOIN grade ON user.GradeID = grade.GradeID 
        WHERE user.ID = ?";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();
    
    if (!$user) {
        header("Location: error.php?error=" . urlencode("User not found"));
        exit;
    }
} else {
    die("Error preparing statement: " . $conn->error);
}
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>User Details</h2>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>User Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user->Email); ?></p>
                            <p><strong>User Type:</strong> <?php echo ucfirst(htmlspecialchars($user->UserType)); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Grade Level:</strong> <?php echo htmlspecialchars($user->GradeName ?? 'N/A'); ?></p>
                            <p><strong>Admin Status:</strong> <?php echo $user->IsAdmin ? 'Yes' : 'No'; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'includes/_activityDetails.php'; ?>

            <div class="mt-3">
                <a href="useradmin.php" class="btn btn-secondary">Back to User List</a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
}

.table th {
    background-color: #f8f9fa;
}
</style>

<?php require "_footer.php" ?> 