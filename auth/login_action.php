<?php
session_start();
require '../db.php'; // include your database connection

// Get email and password from the form
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute SQL to find the user by email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify the password using password_verify
    if (password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];

        // Redirect based on role
        if ($user['is_admin']) {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../views/users_dashboard.php");
        }
        exit();
    } else {
        header("Location: ../views/login.php?error=invalid_password");
        exit();
    }
} else {
    header("Location: ../views/login.php?error=user_not_found");
}
$conn->close();
exit();
?>
