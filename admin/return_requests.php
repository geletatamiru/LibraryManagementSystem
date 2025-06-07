<?php
include_once 'header.php';
// Fetch all return requests
$sql = "SELECT b.id, b.book_id, bk.title, u.name, b.borrow_date, b.due_date 
        FROM borrowings b
        JOIN books bk ON b.book_id = bk.id
        JOIN users u ON b.user_id = u.id
        WHERE b.status = 'return_requested'
        ORDER BY b.borrow_date ASC";

$result = $conn->query($sql);
?>
<div class="return-container">
  <h2>📦 Pending Return Requests</h2>
   <?php 
    if (isset($_GET['status']) && $_GET['status'] === 'approved') {
      echo "<p style='color: green;'>Borrow request approved!</p>";
    }
   ?>
<?php if ($result->num_rows > 0): ?>
    <table class="admin-table" >
        <tr>
            <th>User</th>
            <th>Book</th>
            <th>Borrowed On</th>
            <th>Due Date</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= date("F j, Y", strtotime($row['borrow_date'])) ?></td>
                <td><?= date("F j, Y", strtotime($row['due_date'])) ?></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="borrow_id" value="<?= $row['id'] ?>">
                        <button name="action" value="approve_return">✅ Approve</button>
                        <button name="action" value="reject_return">❌ Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No return requests yet.</p>
<?php endif; ?>

</div>

<?php
$statusMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_id'], $_POST['action'])) {
    $borrow_id = intval($_POST['borrow_id']);
    $action = $_POST['action'];

    // Get user and book ID for this borrow
    $stmt = $conn->prepare("SELECT user_id, book_id FROM borrowings WHERE id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $book_id = $row['book_id'];

        if ($action === 'approve_return') {
            // 1. Update borrowings
            $update = $conn->prepare("UPDATE borrowings SET status = 'returned', return_date = NOW() WHERE id = ?");
            $update->bind_param("i", $borrow_id);
            $update->execute();

            // 2. Update book copies
            $bookUpdate = $conn->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
            $bookUpdate->bind_param("i", $book_id);
            $bookUpdate->execute();

            // 3. Notify user
            $msg = "✅ Your return request (ID: $borrow_id) has been approved. Thank you!";
            $statusMessage = "approved";
        } else if ($action === 'reject_return') {
            // Set return_requested = 0 so user can request again
            $update = $conn->prepare("UPDATE borrowings SET status = 'rejected' WHERE id = ?");
            $update->bind_param("i", $borrow_id);
            $update->execute();

            $msg = "❌ Your return request (ID: $borrow_id) was rejected by the admin.";
            $statusMessage = "rejected";
        }

        // Insert notification
        $notify = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $notify->bind_param("is", $user_id, $msg);
        $notify->execute();
        header("Location: return_requests.php?status=$statusMessage");
        exit();
    }
}
?>

