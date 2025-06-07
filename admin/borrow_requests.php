<?php
include_once 'header.php';

// Fetch all pending borrow requests
$sql = "
    SELECT br.id, u.name, b.title
    FROM borrowings br
    JOIN users u ON br.user_id = u.id
    JOIN books b ON br.book_id = b.id
    WHERE br.status = 'pending'
";

$result = $conn->query($sql);
?>
<div class="borrow-container">
  <h1>📥 Borrow Requests</h1>
  <?php 
    if (isset($_GET['status']) && $_GET['status'] === 'approved') {
      echo "<p style='color: green;'>Borrow request approved!</p>";
    }
  ?>
  <?php if ($result->num_rows > 0): ?>
      <table class="admin-table">
          <thead>
              <tr>
                  <th>User</th>
                  <th>Book</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['title']) ?></td>
                  <td>
                      <form method="post" action="" style="display: inline-block;">
                          <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                          <button name="action" value="approve">✅ Approve</button>
                          <button name="action" value="reject">❌ Reject</button>
                      </form>
                  </td>
              </tr>
          <?php endwhile; ?>
          </tbody>
      </table>
  <?php else: ?>
      <p>No pending borrow requests at the moment.</p>
  <?php endif; ?>

</div>

<?php
$statusMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $id = intval($_POST['request_id']);
    $action = $_POST['action'];

    // Fetch user_id and book_id in one query
    $stmt = $conn->prepare("SELECT user_id, book_id FROM borrowings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $book_id = $row['book_id'];
        $message = "";

         $book_check = $conn->prepare("SELECT available_copies,title FROM books WHERE id = ?");
         $book_check->bind_param("i", $book_id);
         $book_check->execute();
         $res = $book_check->get_result()->fetch_assoc();
         $available = $res['available_copies'];
         $title = $res['title'];

        if ($action === 'approve') {
            // Check book availability
            if ($available > 0) {
                // Approve request & update borrow + due date
                $update = $conn->prepare("UPDATE borrowings SET status = 'approved', borrow_date = NOW(), due_date = DATE_ADD(NOW(), INTERVAL 15 DAY) WHERE id = ?");
                $update->bind_param("i", $id);
                $update->execute();

                // Decrease available copies
                $decrease = $conn->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
                $decrease->bind_param("i", $book_id);
                $decrease->execute();

                $message = "📘 Your borrow request ( $title) has been approved!";
                $statusMessage = "approved";
            } else {
                $message = "❌ Your borrow request ($title) has been rejected. Book is not available.";
                $statusMessage = "❌ Request ID $id could not be approved. Book is unavailable.";
            }

        } elseif ($action === 'reject') {
            $update = $conn->prepare("UPDATE borrowings SET status = 'rejected' WHERE id = ?");
            $update->bind_param("i", $id);
            $update->execute();

            $message = "❌ Your borrow request ($title) has been rejected.";
            $statusMessage = "rejected";

        }

        // Send notification
        $notify = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $notify->bind_param("is", $user_id, $message);
        $notify->execute();
        $notify->close();
        $stmt->close();
        header("Location: borrow_requests.php?status=$statusMessage");
        exit();
    }

}
?>
