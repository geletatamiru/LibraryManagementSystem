<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/auth.css">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <h1>Welcome to <span>the Library System</span></h1>
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
                  switch ($_GET['error']) {
                      case 'password_mismatch':
                          echo "Passwords do not match.";
                          break;
                      case 'weak_password':
                          echo "Password must be at least 6 characters long and contain both letters and numbers.";
                          break;
                      case 'user_exists':
                          echo "An account with this email already exists.";
                          break;
                      case 'registration_error':
                      default:
                          echo "Registration error. Please try again.";
                          break;
                  }
              ?>
          </div>
        <?php endif; ?>

    </form>
</div>

</body>
</html> 
