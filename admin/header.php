<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include_once '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/dashboard.css">
  <title>Admin Panel</title>
</head>
<body>
  <nav class="navigation">
    <a href="admin_dashboard.php">🏠 Dashboard</a>
    <a href="borrow_requests.php">📥 Borrow Requests</a>
    <a href="return_requests.php">📘 Return Requests</a>
    <div class="menu" id="navMenu">
      <a href="manage_books.php">📚 Books</a>
      <a href="manage_users.php">👥 Users</a>
      <a href="logout.php" class="logout-link">🔒 Logout</a>
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
