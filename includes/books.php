<!DOCTYPE html>
<html>
<head>
    <title>Library Managment | Explore Books</title>
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
