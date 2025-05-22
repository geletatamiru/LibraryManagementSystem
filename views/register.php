<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/register.css">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <h1>Welcome to the Library system</h1>
    <form action="../auth/register_action.php" method="POST">
        <h2>Register</h2>
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>       
        <label for="confirm_password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        <button type="submit" class="btn btn-primary">SignUp</button>
        <p>Already have an Account? <a href="login.php">Login</a></p>
        <?php if (isset($_GET['error'])): ?>
          <div style="color: red; margin-bottom: 10px;">
              <?php
                  if ($_GET['error'] === "registration_error") {
                      echo "Registration error. Please try again.";
                  } 
              ?>
          </div>
        <?php endif; ?>
    </form>
</div>

</body>
</html> 
