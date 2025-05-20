<?php
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Due Dates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .due-card {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f5f5f5;
        }
        h2 {
            margin-bottom: 20px;
        }
        .overdue {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>📅 Books and Due Dates</h2>

<?php
$sql = "
    SELECT b.title, b.author, br.borrow_date, br.return_date
    FROM borrowings br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ?
    ORDER BY br.return_date ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $due = strtotime($row['return_date']);
        $isOverdue = $due < time();

        echo "<div class='due-card'>";
        echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
        echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
        echo "<p>Borrowed on: " . date("F j, Y", strtotime($row['borrow_date'])) . "</p>";
        echo "<p>Due on: <span class='" . ($isOverdue ? "overdue" : "") . "'>" . date("F j, Y", $due) . "</span></p>";
        echo "</div>";
    }
} else {
    echo "<p>You have no books with due dates.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

</body>
</html>
