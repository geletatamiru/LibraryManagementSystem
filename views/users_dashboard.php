
<?php
include '../db.php';
include '../includes/header.php'; // shared nav

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}


// Fetch available books
$sql = "SELECT books.id, books.title, books.author, categories.name AS category
        FROM books
        JOIN categories ON books.category_id = categories.id
        WHERE books.available_copies > 0";
$result = $conn->query($sql);
?>

<div style="padding: 20px;">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
    <h3 style="margin-top: 20px;">Available Books</h3>

    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 10px; width: 100%;">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php while($book = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= htmlspecialchars($book['category']) ?></td>
            <td>
                <form action="../actions/borrow_action.php" method="POST">
                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                    <button type="submit">Borrow</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
