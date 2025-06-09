<?php
$user_id = $_SESSION['user_id']; 


$sql = "SELECT id,message, created_at, is_read FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$markRead = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
$markRead->bind_param("i", $user_id);
$markRead->execute();
$markRead->close();

?>

<h2>🔔 Notifications</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <form method="POST" >
            <div class="notification <?= $row['is_read'] ? 'read' : 'unread' ?>">
                <div>
                    <p><?= htmlspecialchars($row['message']) ?></p>
                    <small><?= date("F j, Y, g:i A", strtotime($row['created_at'])) ?></small>
                </div>
                <input type="hidden" name="notification_id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete">
                    <img src="/assets/images/remove.png" alt="Delete">
                </button>
            </div>
        </form>
    <?php endwhile; ?>
<?php else: ?>
    <p>No notifications yet.</p>
<?php endif; ?>
