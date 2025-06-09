<?php
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

$sql_books = "SELECT COUNT(*) as count FROM books";
$result_books = mysqli_query($conn, $sql_books);
$total_books = 0;
if ($result_books && $row = mysqli_fetch_assoc($result_books)) {
    $total_books = $row['count'];
}

$sql_borrowings = "SELECT COUNT(*) as count FROM borrowings WHERE user_id = ? AND status IN ('approved', 'return_requested')";
$stmt = mysqli_prepare($conn, $sql_borrowings);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result_borrowings = mysqli_stmt_get_result($stmt);
$borrowed_books = 0;
if ($result_borrowings && $row = mysqli_fetch_assoc($result_borrowings)) {
    $borrowed_books = $row['count'];
}

$due_message = "🎉 You have no due books right now.";
$sql_due = "
    SELECT COUNT(*) as overdue 
    FROM borrowings 
    WHERE user_id = ? 
      AND status = 'approved' 
      AND borrow_date IS NOT NULL 
      AND DATE_ADD(borrow_date, INTERVAL 15 DAY) < NOW()
";
$stmt_due = mysqli_prepare($conn, $sql_due);
mysqli_stmt_bind_param($stmt_due, "i", $user_id);
mysqli_stmt_execute($stmt_due);
$result_due = mysqli_stmt_get_result($stmt_due);

if ($result_due && $row = mysqli_fetch_assoc($result_due)) {
    if ($row['overdue'] > 0) {
        $due_message = "⚠️ You have overdue books. Please return them.";
    }
}
?>

<head>
  
<style>
  .welcome-heading {
    text-align: center;
    margin-top: 30px;
    font-size: 28px;
    color: #2c3e50;
  }

  .subheading {
    text-align: center;
    margin-bottom: 30px;
    color: #7f8c8d;
  }

  .dashboard-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin: 0 auto;
    max-width: 900px;
    padding: 0 20px;
  }

  .card {
    flex: 1 1 250px;
    background-color: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 25px;
    text-align: center;
    transition: transform 0.2s;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .card h3 {
    color: #34495e;
    margin-bottom: 10px;
  }

  .card p {
    font-size: 18px;
    color: #2c3e50;
  }

  .card p:has(span.overdue-warning) {
    color: #e74c3c;
    font-weight: bold;
  }
</style>

</head>
<h2 class="welcome-heading">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
<p class="subheading">Here’s a quick overview of your account:</p>

<div class="dashboard-cards">
  <div class="card">
    <h3>Total Books</h3>
    <p><?php echo $total_books; ?></p>
  </div>
  <div class="card">
    <h3>Books You Borrowed</h3>
    <p><?php echo $borrowed_books; ?></p>
  </div>
  <div class="card">
    <h3>Due Status</h3>
    <p><?php echo $due_message; ?></p>
  </div>
</div>
