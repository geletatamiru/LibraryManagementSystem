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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'], $_POST['notification_id'])) {
  $notification_id = $_POST['notification_id'];
  echo "Notification ID: " . htmlspecialchars($notification_id);
  $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $notification_id, $_SESSION['user_id']);
  $stmt->execute();
  $stmt->close();

  header("Location: users_dashboard.php?section=notifications");
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="assets/css/userDashboard.css">
</head>
<body>

  <div class="dashboard-container">

    <button class="open-sidebar">
       <img src="./assets/images/right-arrow.png" alt="right-arrow">
    </button>
    <div class="sidebar">
      <a href="users_dashboard.php">📚 Dashboard</a>
      <a href="?section=all">📖 View All Books</a>
      <a href="?section=search">🔍 Search & Borrow Book</a>
      <a href="?section=due_dates">👤 Due Dates</a>
      <a href="?section=return">📦 Return Books</a>
      <a href="?section=pending">📦 My Pending Requests</a>
      <a href="?section=notifications">🔔 Notifications<?= $unread_count > 0 ? " ($unread_count)" : "" ?></a>
    </div>

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
            case 'pending':
              include 'includes/pending_requests.php';
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