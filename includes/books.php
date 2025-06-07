<!DOCTYPE html>
<html>
<head>
    <title>Due Dates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .book-list{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .book-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .book-card img{
            height: 250px;
            object-fit: cover;
            max-width: 100%;
            border-radius: 8px;
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

$sql = "select title,author,name,image_url from books join categories on books.category_id = categories.id ORDER BY RAND() LIMIT 20";
$result = mysqli_query($conn, $sql);

echo "<h3>Explore Books</h3>";
echo "<div class='book-list'>";
if(mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='book-card'>";
        echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['image_url']) . " Cover'>";
        echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
        echo "<p>Category: " . $row['name'] . "</p>";
        echo "</div>";
    }
}
echo "</div>";
?>
</body>
</html>
