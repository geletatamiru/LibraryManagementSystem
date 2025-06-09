<?php
include_once 'header.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bookId = (int) $_POST['book_id'];
    $action = $_POST['action']; 

    $sql = "SELECT available_copies, total_copies FROM books WHERE id = $bookId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
        $available = $book['available_copies'];
        $total = $book['total_copies'];

        if ($action === "increment") {
            $newAvailable = $available + 1;
            $newTotal = $total + 1;
        } elseif ($action === "decrement") {
            if ($available <= 0 || $total <= 0) {
                echo "<p style='color: red; font-weight: bold;margin-top: 50px;'>Cannot Decrement Below Zero</p>";
                echo "<p><a href='manage_books.php' style='color: black; font-weight: bold;margin-top:50px;'>Go back to book list</a></p>";
                exit;
            }
            $newAvailable = $available - 1;
            $newTotal = $total - 1;
        } else {
            header("Location: manage_books.php?error=Invalid action.");
            exit();
        }

        $updateSql = "UPDATE books SET available_copies = $newAvailable, total_copies = $newTotal WHERE id = $bookId";
        if (mysqli_query($conn, $updateSql)) {
            header("Location: manage_books.php?success=Copies updated.");
        } else {
            header("Location: manage_books.php?error=Update failed.");
        }
    } else {
        echo "<p style='color: red; font-weight: bold;margin-top: 50px;'>Book not Found</p>";
        echo "<p><a href='manage_books.php' style='color: black; font-weight: bold;margin-top:50px;'>Go back to book list</a></p>";
        exit;   
     }
} else {
    header("Location: manage_books.php");
}
exit();
