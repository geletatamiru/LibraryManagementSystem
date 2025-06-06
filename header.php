<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/header.css">
  <title>Library Managment</title>
  <style>
    .navigation {
      display: flex;
      justify-content: space-between;
      align-items: center;
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
    .logo{
      font-weight: 400;
      font-size: 1.8rem;
      color: white;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
      font-variant: small-caps;
      letter-spacing: 2px;
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
      position: absolute;
      background-color: rgba(128, 128, 128, 0.816);
      top: 80px;
      left: 0;
      z-index: 99;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
      border-radius: 0 0 10px 10px;
      padding-bottom: 20px;
      margin: 0;
    }
    .hamburger-menu {
      width: 35px;
      position: relative;
      display: block;
    }
  .burger,
  .hamburger-menu::after,
  .hamburger-menu::before {
    display: block;
    content: "";
    width: 100%;
    height: 5px;
    border-radius: 5px;
    background-color: #0E2148;
    margin: 6px 0px;
    transition: 0.5s;
  }
  .active.burger {
    opacity: 0;
  }
  .active.hamburger-menu::before {
    transform: rotate(-45deg) translate(-8px, 6px);
  }
  .active.hamburger-menu::after {
    transform: rotate(45deg) translate(-9px, -8px);
  }
    @media (min-width: 768px){
      .navigation {
        align-items: center;
      }
      .hamburger-menu {
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
    <div class="hamburger-menu" onclick="toggleMenu()"> 
        <div class="burger"></div>
    </div>
  </nav>
  <script>
     function toggleMenu() {
        document.getElementById("navMenu").classList.toggle("active");
        document.querySelector(".burger").classList.toggle("active");
        document.querySelector(".hamburger-menu").classList.toggle("active");
    }
  </script>
  </body>
</html>