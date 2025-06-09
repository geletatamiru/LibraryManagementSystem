<?php
include_once "header.php";
date_default_timezone_set('Africa/Addis_Ababa');

$sql = "
SELECT 
    br.id AS borrowing_id,
    u.name AS user_name,
    b.title AS book_title,
    br.borrow_date,
    br.due_date
FROM borrowings br
JOIN users u ON br.user_id = u.id
JOIN books b ON br.book_id = b.id
WHERE br.due_date IS NOT NULL 
AND br.due_date < CURDATE() 
AND br.status = 'approved'
ORDER BY br.due_date ASC;
";

$result = mysqli_query($conn, $sql);
?>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="overdue-cont">
        <h1 style="text-align: center;">Overdue books</h1>
        <?php if (isset($_GET['reminder']) && $_GET['reminder'] === "sent"): ?>
            <p style="font-weight: bold;">✅ Reminder Sent.</p>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($result)): 
            $bookTitle = htmlspecialchars($row['book_title']);
            $userName = htmlspecialchars($row['user_name']);
            $borrowedOn = date("F j, Y", strtotime($row['borrow_date']));
            $dueOn = date("F j, Y", strtotime($row['due_date']));
            $borrowingId = $row['borrowing_id'];
        ?>
        <div class="overdue-container">
            <div class="overdue-card">
                <h4><?= $bookTitle ?></h4>
                <p>User: <?= $userName ?></p>
                <p>Borrowed on: <?= $borrowedOn ?></p>
                <p class="overdue-text">Due on: <?= $dueOn ?></p>

                <form method="POST">
                    <input type="hidden" name="user_name" value="<?= $userName ?>">
                    <input type="hidden" name="book_title" value="<?= $bookTitle ?>">
                    <input type="hidden" name="borrowing_id" value="<?= $borrowingId ?>">
                    <button type="submit" class="reminder-btn">Send Reminder</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="no-overdue">No overdue books found.</p>
<?php endif; ?>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $book_title = $_POST['book_title'];
    $borrowing_id = $_POST['borrowing_id'];

    $message = "Reminder: Please return the overdue book '$book_title'.";
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("SELECT user_id FROM borrowings WHERE id = ?");
    $stmt->bind_param("i", $borrowing_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $message, $created_at);
    $stmt->execute();
    $stmt->close();

    header("Location: overdue.php?reminder=sent");
    exit();
}
mysqli_close($conn);
?>
