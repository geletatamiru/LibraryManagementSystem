<?php
$user_id = $_SESSION['user_id'];

// Get pending borrow requests
$borrow_stmt = $conn->prepare("
    SELECT b.id, bk.title, bk.author, bk.image_url 
    FROM borrowings b
    JOIN books bk ON b.book_id = bk.id
    WHERE b.user_id = ? AND b.status = 'pending'
");
$borrow_stmt->bind_param("i", $user_id);
$borrow_stmt->execute();
$pending_borrows = $borrow_stmt->get_result();

// Get pending return requests
$return_stmt = $conn->prepare("
    SELECT b.id, bk.title, bk.image_url, b.borrow_date, b.due_date
    FROM borrowings b
    JOIN books bk ON b.book_id = bk.id
    WHERE b.user_id = ? AND b.status = 'return_requested'
");
$return_stmt->bind_param("i", $user_id);
$return_stmt->execute();
$pending_returns = $return_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Pending Requests</title>
    <style>
        h2 {
            text-align: center;
            margin-top: 40px;
        }
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .book-card {
            width: 220px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
            transition: transform 0.2s;
        }
        .book-card:hover {
            transform: scale(1.03);
        }
        .book-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .book-info {
            padding: 15px;
        }
        .book-info h3 {
            font-size: 16px;
            margin: 0 0 10px;
        }
        .book-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>📚 My Pending Borrow Requests</h2>
    <div class="cards-container">
        <?php if ($pending_borrows->num_rows > 0): ?>
            <?php while ($row = $pending_borrows->fetch_assoc()): ?>
                <div class="book-card">
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Book Cover">
                    <div class="book-info">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><strong>Author:</strong> <?= $row['author'] ?></p> 
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center;">No pending borrow requests.</p>
        <?php endif; ?>
    </div>

    <h2>🔁 My Pending Return Requests</h2>
    <div class="cards-container">
        <?php if ($pending_returns->num_rows > 0): ?>
            <?php while ($row = $pending_returns->fetch_assoc()): ?>
                <div class="book-card">
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Book Cover">
                    <div class="book-info">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><strong>Borrowed:</strong> <?= $row['borrow_date'] ?></p>
                        <p><strong>Due:</strong> <?= $row['due_date'] ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center;">No pending return requests.</p>
        <?php endif; ?>
    </div>
</body>
</html>
