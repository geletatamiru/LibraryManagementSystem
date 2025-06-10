<?php 
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name             = trim($_POST['name']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirmPassword  = $_POST['confirm_password'];
    if ($password !== $confirmPassword) {
        header("Location: ../views/register.php?error=password_mismatch");
        exit();
    }
    if (strlen($password) < 6) {
        header("Location: ../views/register.php?error=weak_password");
        exit();
    }
    $checkSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        header("Location: ../views/register.php?error=user_exists");
        exit();
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../views/login.php?registered=1");
    } else {
        header("Location: ../views/register.php?error=registration_error");
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($checkStmt);
    mysqli_close($conn);
    exit();
}
?>
