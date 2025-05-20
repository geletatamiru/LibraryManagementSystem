<?php include 'header.php'; ?> 
<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login.php?error=unauthorized");
    exit();
}
?>
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      display: flex;
    }

    .dashboard-container {
      display: flex;
      width: 100%;
      padding-top: 58px;
    }

    .sidebar {
      width: 220px;
      background-color: #34495e;
      color: white;
      min-height: 100vh;
      padding: 20px 15px;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 4px;
    }

    .sidebar a:hover {
      background-color: #2c3e50;
    }

    .main-content {
      flex: 1;
      padding: 30px;
    }
  </style>
</head>
<body>

  <div class="dashboard-container">

    <!-- Sidebar -->
    <div class="sidebar">
      <h2>📚 Dashboard</h2>
      <a href="?section=all">📖 View All Books</a>
      <a href="?section=search">🔍 Search & Borrow Book</a>
       <a href="?section=borrowed">📦 Borrowed Books</a>
      <a href="?section=due_dates">👤 Due Dates</a>
      <a href="?section=return">👤 Return Books</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
      <?php
        if (isset($_GET['section'])) {
          $section = $_GET['section'];
          switch ($section) {
            case 'all':
              include 'includes/books.php';
              break;
            case 'search':
              include 'includes/search_books.php';
              break;
            case 'borrowed':
              include 'includes/borrowed_books.php';
              break;
            case 'due_dates':
              include 'includes/due_dates.php';
              break;
            case 'return':
              include 'includes/return_books.php';
              break;
            default:
              include 'includes/dashboard_home.php';
          }
        } else {
          include 'includes/dashboard_home.php';
        }
      ?>
    </div>

  </div>

</body>
</html>