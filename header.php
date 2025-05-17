<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/header.css">
  <title>Header</title>
  <style>
    .navigation {
      display: flex;
      justify-content: space-between;
      color: white;
      background: rgba(128, 128, 128, 0.516);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      box-sizing: border-box;
      font-weight: bold;
      padding: 20px 10px;
    }
    .logo {
      align-self: flex-start;
    }
    a {
      text-decoration: none;
      color: white;
    }
    .menu {
      display: none;
      flex-direction: column;
    }
    .menu.active {
      display: flex;
      gap: 10px;
      padding-top: 30px;
      width: 100%;
      align-items: center;
    }
    .hamburger {
      display: flex;
      cursor: pointer;
      align-self: flex-start;
    }
    @media (min-width: 768px){
      .navigation {
        align-items: center;
      }
      .hamburger {
        display: none;
      }

      .menu {
        display: flex;
        flex-direction: row;
        gap: 50px;
        margin-right: 20px;
      }
    }
  </style>
</head>
<body>
  <nav class="navigation">
    <a href="index.php" class="logo">Library</a>
    <div class="menu" id="navMenu">
      <a href="/">Home</a>
      <a href="/#about">About</a>
      <a href="/#">Contact</a>
      <?php if (isset($_SESSION['user_id'])): ?>
          <a href="users_dashboard.php">Dashboard</a>
          <a href="auth/logout_action.php">Logout</a>
      <?php else: ?>
          <a href="views/login.php">Login</a>
      <?php endif; ?>
    </div>
    <div class="hamburger" onclick="toggleMenu()">hamburger</div>
  </nav>
  <script>
     function toggleMenu() {
        document.getElementById("navMenu").classList.toggle("active");
    }
  </script>
  </body>
</html>