<?php
session_start();
include '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, name, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if (mysqli_num_rows($result) === 1) {
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: login.php?error=invalid_password");
        exit();
    }
  } else {
      header("Location: login.php?error=admin_not_found");
      exit();
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/auth.css">
  <title>Admin Login Page</title>
</head>
<body>
  <div class="container">
    <form action="" method="POST">
        <h2>Admin Login</h2>
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>       
        <button type="submit" class="btn btn-primary">Login</button>
        <?php if (isset($_GET['error'])): ?>
          <div style="color: red; margin-bottom: 10px;">
              <?php
                  if ($_GET['error'] === "invalid_password") {
                      echo "Invalid Credentials. Please try again.";
                  } elseif ($_GET['error'] === "admin_not_found") {
                      echo "Invalid Credentials. Please try again.";
                  }
              ?>
          </div>
        <?php endif; ?>
  </div>
    </form>
    

</body>
</html> 

