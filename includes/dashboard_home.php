<?php
    $user_id = $_SESSION['user_id']; 
    $user_name = $_SESSION['name'];
    $sql_books = "SELECT COUNT(*) as count FROM books";
    $result_books = mysqli_query($conn, $sql_books);
    $total_books = 0;
    if ($result_books && $row = mysqli_fetch_assoc($result_books)) {
        $total_books = $row['count'];
    }


    $sql_borrowings = "SELECT COUNT(*) as count FROM borrowings WHERE user_id = ? AND status IN ('approved', 'return_requested') ";
    $stmt = mysqli_prepare($conn, $sql_borrowings);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result_borrowings = mysqli_stmt_get_result($stmt);
    $borrowed_books = 0;
    if ($result_borrowings && $row = mysqli_fetch_assoc($result_borrowings)) {
        $borrowed_books = $row['count'];
    }

    $due_message = "You have no due books right now.";
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


    <h2>Welcome, <?php echo  $user_name?>!</h2>
    <p>Here’s a quick overview of your account:</p>

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
      <div style="background-color: #ecf0f1; padding: 20px; border-radius: 10px; flex: 1;">
        <h3>Total Books</h3>
        <p><?php echo $total_books; ?></p>
      </div>
      <div style="background-color: #ecf0f1; padding: 20px; border-radius: 10px; flex: 1;">
        <h3>Books You Borrowed</h3>
        <p><?php echo $borrowed_books; ?></p>
      </div>
      <div style="background-color: #ecf0f1; padding: 20px; border-radius: 10px; flex: 1;">
        <h3>Due Dates</h3>
        <p><?php echo $due_message; ?></p>
      </div>
    </div>
