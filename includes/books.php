<?php

$sql = "SELECT * FROM books ORDER BY RAND() LIMIT 20";
$result = mysqli_query($conn, $sql);

echo "<h3>Explore Books</h3>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='book-card'>";
    echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
    echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
    echo "<p>Category ID: " . $row['category_id'] . "</p>";
    echo "</div>";
}
?>
