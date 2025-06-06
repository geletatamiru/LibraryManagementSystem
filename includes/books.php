<!DOCTYPE html>
<html>
<head>
    <title>Due Dates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .book-card {
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
<?php

$sql = "select title,author,name from books join categories on books.category_id = categories.id ORDER BY RAND() LIMIT 20";
$result = mysqli_query($conn, $sql);

echo "<h3>Explore Books</h3>";
if(mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='book-card'>";
        echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
        echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
        echo "<p>Category: " . $row['name'] . "</p>";
        echo "</div>";
    }
}
?>
</body>
</html>
