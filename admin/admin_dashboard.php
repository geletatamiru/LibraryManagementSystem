<?php
include_once 'header.php';
?>
<div class="dashboard">
    <h1>📊 Admin Dashboard</h1>

    <div class="dashboard-cards">
        <?php
        $books = $conn->query("SELECT COUNT(*) AS total FROM books")->fetch_assoc()['total'];

        $users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

        $borrowed = $conn->query("SELECT COUNT(*) AS total FROM borrowings WHERE status = 'approved'")->fetch_assoc()['total'];

        $pending_borrows = $conn->query("SELECT COUNT(*) AS total FROM borrowings WHERE status = 'pending'")->fetch_assoc()['total'];

        $return_requests = $conn->query("SELECT COUNT(*) AS total FROM borrowings WHERE status = 'return_requested'")->fetch_assoc()['total'];

        $overdue = $conn->query("SELECT COUNT(*) AS total FROM borrowings WHERE status = 'approved' AND due_date < NOW()")->fetch_assoc()['total'];
        ?>

        <div class="card">📚 Total Books: <strong><?= $books ?></strong></div>
        <div class="card">👤 Total Users: <strong><?= $users ?></strong></div>
        <div class="card">📖 Borrowed Books: <strong><?= $borrowed ?></strong></div>
        <div class="card">⏳ Pending Requests: <strong><?= $pending_borrows ?></strong></div>
        <div class="card">📥 Return Requests: <strong><?= $return_requests ?></strong></div>
        <div class="card">⚠️ Overdue Books: <strong><?= $overdue ?></strong></div>
    </div>
</div>
    
</body>
</html>
