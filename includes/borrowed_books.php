<?php
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Borrowed Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .book-card {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f8f8f8;
        }
        h2 {
            margin-bottom: 20px;
        }
        .no-books {
            background-color: #fff3cd;
            padding: 10px;
            border: 1px solid #ffeeba;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2>📚 My Borrowed Books</h2>

<?php
// Fetch borrowed books for this user
$sql = "
    SELECT b.title, b.author, br.borrow_date 
    FROM borrowings br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ?
    ORDER BY br.borrow_date DESC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='book-card'>";
        echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
        echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
        echo "<p>Borrowed on: " . date('F j, Y', strtotime($row['borrow_date'])) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p class='no-books'>You haven't borrowed any books yet.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

</body>
</html>
