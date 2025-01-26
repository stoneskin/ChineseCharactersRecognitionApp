<?php
require_once "../_needSession.php";
require_once '../_incFunctions.php';
require "../connect.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $userType = isset($_POST['userType']) ? $_POST['userType'] : 'parent';
        $gradeId = isset($_POST['gradeId']) ? intval($_POST['gradeId']) : 0;
        $isAdmin = isset($_POST['isAdmin']) ? intval($_POST['isAdmin']) : 0;

        // Validate email
        // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     throw new Exception("Invalid email format");
        // }
        $email=sanitizeHTML($email);
        $password=sanitizeHTML($password);

        // Validate required fields
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }

        // For student accounts, grade is required
        if ($userType === 'student' && $gradeId === 0) {
            throw new Exception("Grade level is required for student accounts");
        }

        if (empty($id)) {
            // Check if email already exists
            $checkSql = "SELECT ID FROM user WHERE Email = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                throw new Exception("Email already exists");
            }
        } else {
            // Check if email exists for other users
            $checkSql = "SELECT ID FROM user WHERE Email = ? AND ID != ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("si", $email, $id);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                throw new Exception("Email already exists for another user");
            }
        }

        if (empty($id)) {
            // Insert new user
            $sql = "INSERT INTO user (Email, Password, UserType, GradeID, IsAdmin) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $email, $password, $userType, $gradeId, $isAdmin);
        } else {
            // Update existing user
            $sql = "UPDATE user SET Email = ?, Password = ?, UserType = ?, GradeID = ?, IsAdmin = ? WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiii", $email, $password, $userType, $gradeId, $isAdmin, $id);
        }

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'User saved successfully!'
            ]);
        } else {
            throw new Exception("Database error: " . $stmt->error);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?> 