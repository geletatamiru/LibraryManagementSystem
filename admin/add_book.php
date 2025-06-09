<?php
include_once 'header.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $category_id = (int) $_POST['category_id'];
    $total_copies_input = trim($_POST['total_copies']);
    $image_url = trim($_POST['image_url']);

    if (!is_numeric($total_copies_input) || (int) $total_copies_input < 1) {
        $error = "Total copies must be a positive number.";
    } elseif (empty($title) || empty($author) || empty($isbn) || empty($category_id)) {
        $error = "Please fill in all required fields.";
    } else {
        $total_copies = (int) $total_copies_input;

        $title_esc = mysqli_real_escape_string($conn, $title);
        $author_esc = mysqli_real_escape_string($conn, $author);
        $isbn_esc = mysqli_real_escape_string($conn, $isbn);
        $image_url_esc = mysqli_real_escape_string($conn, $image_url);

        $check_sql = "SELECT id, total_copies, available_copies FROM books WHERE isbn = '$isbn_esc' LIMIT 1";
        $check_result = mysqli_query($conn, $check_sql);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $book = mysqli_fetch_assoc($check_result);
            $new_total = $book['total_copies'] + $total_copies;
            $new_available = $book['available_copies'] + $total_copies;

            $update_sql = "UPDATE books SET total_copies = $new_total, available_copies = $new_available WHERE id = " . $book['id'];

            if (mysqli_query($conn, $update_sql)) {
                $success = "Book already exists (by ISBN). Copies updated successfully!";
            } else {
                $error = "Error updating book copies: " . mysqli_error($conn);
            }
        } else {
            $insert_sql = "INSERT INTO books (title, author, category_id, available_copies, total_copies, image_url, isbn)
                           VALUES ('$title_esc', '$author_esc', $category_id, $total_copies, $total_copies, '$image_url_esc', '$isbn_esc')";

            if (mysqli_query($conn, $insert_sql)) {
                $success = "Book added successfully!";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Add New Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background-color: #f5f5f5;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <h2>Add a New Book</h2>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="add_book.php">

        <label>Title</label>
        <input type="text" name="title" required>
        <label>ISBN</label>
        <input type="text" name="isbn" required>
        <label>Author</label>
        <input type="text" name="author" required>

        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <option value="1">Fiction</option>
            <option value="2">Science</option>
            <option value="3">Technology</option>
        </select>

        <label>Total Copies</label>
        <input type="text" name="total_copies" placeholder="Enter a positive number" required>

        <label>Image URL</label>
        <input type="text" name="image_url" placeholder="https://example.com/book.jpg">

        <button type="submit" class="btn">Add Book</button>
        <a href="manage_books.php" class="btn" style="background-color: grey; margin-left: 10px;">Cancel</a>
    </form>

</body>
</html>