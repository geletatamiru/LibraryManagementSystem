<?php
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Due Dates</title>
    <style>
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: start;
            padding: 20px 0;
        }
        .due-card {
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: #fff;
            transition: transform 0.2s;
        }
        .due-card:hover {
            transform: scale(1.02);
        }
        .due-card img {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }
        .due-card .card-body {
            padding: 15px;
        }
        .due-card h4 {
            margin: 0 0 8px;
            font-size: 18px;
        }
        .due-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
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
        SELECT b.title, b.author, b.image_url, br.borrow_date, br.due_date
        FROM borrowings br
        JOIN books b ON br.book_id = b.id
        WHERE br.user_id = ? AND status = 'approved'
        ORDER BY br.due_date ASC
    ";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="cards-container">
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $due = strtotime($row['due_date']);
        $isOverdue = $due < time();
        ?>
        <div class="due-card">
            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Book cover">
            <div class="card-body">
                <h4><?= htmlspecialchars($row['title']) ?></h4>
                <p><strong>Author:</strong> <?= htmlspecialchars($row['author']) ?></p>
                <p><strong>Borrowed:</strong> <?= date("F j, Y", strtotime($row['borrow_date'])) ?></p>
                <p><strong>Due:</strong> 
                    <span class="<?= $isOverdue ? 'overdue' : '' ?>">
                        <?= date("F j, Y", $due) ?>
                    </span>
                </p>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>You have no books with due dates.</p>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
</div>


</body>
</html>
