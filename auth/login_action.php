<?php
session_start();
include '../db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt,"s", $email);
$stmt->execute();

$result = mysqli_stmt_get_result( $stmt );

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['is_admin'] = $user['role'];

        if ($user['is_admin'] === "admin") {
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
mysqli_close( $conn );
exit();
?>
