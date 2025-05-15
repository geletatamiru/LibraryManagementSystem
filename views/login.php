
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/login.css">
  <title>Login Page</title>
</head>
<body>
  <div class="container">
    <h1>Welcome to the Library system</h1>
    <form action="../auth/login_action.php" method="POST">
        <h2>Login</h2>
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>       
        <button type="submit" class="btn btn-primary">Login</button>
        <p>Don't have an Account? <a href="register.php">SignUp</a></p>
        <?php if (isset($_GET['error'])): ?>
          <div style="color: red; margin-bottom: 10px;">
              <?php
                  if ($_GET['error'] === "invalid_password") {
                      echo "Incorrect password. Please try again.";
                  } elseif ($_GET['error'] === "user_not_found") {
                      echo "No user found with that email.";
                  }
              ?>
          </div>
        <?php endif; ?>
  </div>
    </form>
    

</body>
</html> 
