<?php include 'header.php'; ?> 
<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login.php?error=unauthorized");
    exit();
}
include_once "db.php";

$count_sql = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0");
$count_sql->bind_param("i", $_SESSION['user_id']);
$count_sql->execute();
$count_result = $count_sql->get_result();
$unread_count = $count_result->fetch_assoc()['unread_count'];

?>
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
      margin-top: 16px;
      width: 220px;
      background-color: #34495e;
      color: white;
      min-height: 100vh;
      padding: 20px 15px;
      position: fixed;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .open-sidebar {
      position: fixed;
      top: 220px;
      left: 0px;
      cursor: pointer;
      z-index: 10;
      background-color:rgb(173, 169, 169,0.3);
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
      display: none;
      border: 0;

    }
    .open-sidebar img {
      width: 30px;
      height: 30px;
      transition: transform 0.3s ease;
    }
    .open-sidebar:hover img {
      transform: rotate(360deg);
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
      margin-left: 220px;
      padding: 30px;
      padding-left: 60px;
    }
    /* notifications */
    .notification {
      padding: 12px;
      margin-bottom: 10px;
      border-radius: 6px;
      border-left: 5px solid;
    }

    .notification.unread {
        background-color: #e6f4ff;
        border-left-color: #007bff;
        font-weight: bold;
    }

    .notification.read {
        background-color: #f5f5f5;
        border-left-color: #ccc;
        font-weight: normal;
    }

    @media screen and (max-width:500px){
          .open-sidebar {
              display: flex;
          }
         .sidebar{
            display:none;
         }
         .main-content{
            margin-left:0;
            padding-left: 40px;
         }
         .opened-sidebar.sidebar{
            display: block;
         }
    }
  </style>
</head>
<body>

  <div class="dashboard-container">

    <!-- Sidebar -->
    <button class="open-sidebar">
       <img src="./assets/images/right-arrow.png" alt="right-arrow">
    </button>
    <div class="sidebar">
      <a href="users_dashboard.php">📚 Dashboard</a>
      <a href="?section=all">📖 View All Books</a>
      <a href="?section=search">🔍 Search & Borrow Book</a>
      <a href="?section=due_dates">👤 Due Dates</a>
      <a href="?section=return">📦 Return Books</a>
      <a href="?section=notifications">🔔 Notifications<?= $unread_count > 0 ? " ($unread_count)" : "" ?></a>
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
            case 'due_dates':
              include 'includes/due_dates.php';
              break;
            case 'return':
              include 'includes/return_books.php';
              break;
            case 'notifications':
              include 'includes/notifications.php';
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
 <script>
    const openSidebar = document.querySelector('.open-sidebar');
    const sidebar = document.querySelector('.sidebar');
    openSidebar.onclick = function() {
      sidebar.classList.toggle('opened-sidebar');
      if (sidebar.classList.contains('opened-sidebar')) {
        openSidebar.querySelector('img').src = './assets/images/left-arrow.png';
      } else {
        openSidebar.querySelector('img').src = './assets/images/right-arrow.png';
      }
    };
 </script>
</body>
</html>