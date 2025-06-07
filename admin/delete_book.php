<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = (int) $_POST['book_id'];

    $sql_check = "SELECT COUNT(*) as count FROM borrowings WHERE book_id = $book_id";
    $result = mysqli_query($conn, $sql_check);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        // Borrowings exist, don't delete
        echo "<p style='color: red; font-weight: bold;margin-top: 50px;'>Cannot delete this book because there are active borrowings linked to it.</p>";
        echo "<p><a href='manage_books.php' style='color: black; font-weight: bold;margin-top:50px;'>Go back to book list</a></p>";
        exit;
    } else {
        // No borrowings, safe to delete
        $sql_delete = "DELETE FROM books WHERE id = $book_id";
        if (mysqli_query($conn, $sql_delete)) {
            header("Location: manage_books.php?deleted=1");
            exit;
        } else {
            echo "Error deleting book: " . mysqli_error($conn);
        }
    }
}
?>

