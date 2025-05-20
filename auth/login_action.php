<?php
session_start();
require '../db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];

        if ($user['is_admin']) {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../index.php");
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
