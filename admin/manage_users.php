<?php
include_once 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$users = $conn->query("SELECT id, name, email FROM users ORDER BY id ASC");
?>
<div class="user-container">
  <h2 style="text-align:center;">👥 Manage Users</h2>
  <table class="user-table">
      <thead>
          <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
          </tr>
      </thead>
      <tbody>
          <?php if ($users->num_rows > 0): ?>
              <?php while ($user = $users->fetch_assoc()): ?>
                  <tr>
                      <td><?= htmlspecialchars($user['id']) ?></td>
                      <td><?= htmlspecialchars($user['name']) ?></td>
                      <td><?= htmlspecialchars($user['email']) ?></td>
                  </tr>
              <?php endwhile; ?>
          <?php else: ?>
              <tr><td colspan="5">No users found.</td></tr>
          <?php endif; ?>
      </tbody>
  </table>
</div>
    