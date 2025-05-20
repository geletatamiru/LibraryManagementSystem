<?php
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrowing_id'])) {
    $borrowing_id = $_POST['borrowing_id'];

    // 1. Check if the borrowing exists and is not already returned
    $check = $conn->prepare("SELECT book_id FROM borrowings WHERE id = ? AND user_id = ? AND return_date IS NULL");
    $check->bind_param("ii", $borrowing_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];

        // 2. Update the borrowings table to set return_date
        $updateBorrowing = $conn->prepare("UPDATE borrowings SET return_date = NOW() WHERE id = ?");
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
} else {
    echo "<p style='color: red;'>❌ Invalid request.</p>";
}

$conn->close();
?>
