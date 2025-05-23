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

<h2>📚 Return Books</h2>

<?php
// Fetch borrowed books for this user
$sql = "
    SELECT br.id, b.title, b.author, br.borrow_date 
    FROM borrowings br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ? AND returned = 0
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
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='borrowing_id' value='" . $row['id'] . "' />";
        echo "<button type='submit'>📘 Return</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "<p class='no-books'>You haven't borrowed any books yet.</p>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrowing_id'])) {
    $borrowing_id = $_POST['borrowing_id'];

    // 1. Check if the borrowing exists and is not already returned
    $check = $conn->prepare("SELECT book_id FROM borrowings WHERE id = ? AND user_id = ? AND returned = 0");
    $check->bind_param("ii", $borrowing_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];

        // 2. Update the borrowings table to set return_date
        $updateBorrowing = $conn->prepare("UPDATE borrowings SET returned = 1 WHERE id = ?");
        $updateBorrowing->bind_param("i", $borrowing_id);
        $updateBorrowing->execute();

        // 3. Increment the available copies of the book
        $updateBook = $conn->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
        $updateBook->bind_param("i", $book_id);
        $updateBook->execute();

        echo "<p style='color: green;'>✅ Book returned successfully.</p>";
    } else {
        echo "<p style='color: red;'>❌ Invalid or already returned book.</p>";
    }

    $check->close();
}
$conn->close();
mysqli_stmt_close($stmt);
?>

</body>
</html>
