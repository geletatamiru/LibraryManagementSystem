<?php
include_once 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$query = "
    SELECT 
        users.id,
        users.name,
        users.email,
        COUNT(borrowings.id) AS total_borrowings,
        SUM(CASE WHEN borrowings.status = 'approved' THEN 1 ELSE 0 END) AS active_borrowings
    FROM users
    LEFT JOIN borrowings ON users.id = borrowings.user_id
    GROUP BY users.id, users.name, users.email
    ORDER BY users.id ASC
";
$users = $conn->query($query);
?>
<div class="user-container">
  <h2 style="text-align:center;">👥 Manage Users</h2>
  <table class="user-table">
      <thead>
          <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Total Borrowings</th>
              <th>Active Borrowings</th>
          </tr>
      </thead>
      <tbody>
          <?php if ($users->num_rows > 0): ?>
              <?php while ($user = $users->fetch_assoc()): ?>
                  <tr>
                      <td><?= htmlspecialchars($user['id']) ?></td>
                      <td><?= htmlspecialchars($user['name']) ?></td>
                      <td><?= htmlspecialchars($user['email']) ?></td>
                      <td><?= htmlspecialchars($user['total_borrowings']) ?></td>
                      <td><?= htmlspecialchars($user['active_borrowings']) ?></td>
                  </tr>
              <?php endwhile; ?>
          <?php else: ?>
              <tr><td colspan="5">No users found.</td></tr>
          <?php endif; ?>
      </tbody>
  </table>
</div>
    