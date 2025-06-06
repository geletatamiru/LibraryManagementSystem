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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "
        SELECT br.id, b.title, b.author, br.borrow_date 
        FROM borrowings br
        JOIN books b ON br.book_id = b.id
        WHERE br.user_id = ? AND br.status = 'approved'
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
            echo "<button type='submit'>📘 Request Return</button>"; // changed label
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p class='no-books'>You haven't borrowed any books yet.</p>";
    }

    mysqli_stmt_close($stmt);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrowing_id'])) {
    $borrowing_id = $_POST['borrowing_id'];

    // 1. Check if the borrowing exists, belongs to the user, and is in 'approved' status
    $stmt = mysqli_prepare($conn, "SELECT book_id FROM borrowings WHERE id = ? AND user_id = ? AND status = 'approved'");
    mysqli_stmt_bind_param($stmt, "ii", $borrowing_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $book_id = $row['book_id'];

        // 2. Update the borrowing status to 'return_requested'
        $updateBorrowing = mysqli_prepare($conn, "UPDATE borrowings SET status = 'return_requested' WHERE id = ?");
        mysqli_stmt_bind_param($updateBorrowing, "i", $borrowing_id);
        mysqli_stmt_execute($updateBorrowing);
        mysqli_stmt_close($updateBorrowing);

        echo "<p style='color: green;'>✅ Return request sent. Please wait for admin approval.</p>";
    } else {
        echo "<p style='color: red;'>❌ Invalid request or already returned/requested.</p>";
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

?>

</body>
</html>
