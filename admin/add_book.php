<?php
include_once 'header.php';

$error = "";
$success = "";

function validateBookData($title, $author, $isbn, $category_id, $total_copies_input) {
    if (empty($title) || empty($author) || empty($isbn) || empty($category_id)) {
        return "Please fill in all required fields.";
    }
    if (!is_numeric($total_copies_input) || (int)$total_copies_input < 1) {
        return "Total copies must be a positive number.";
    }
    return "";
}

function addOrUpdateBook($conn, $title, $author, $isbn, $category_id, $total_copies_input, $image_url) {
    $total_copies = (int)$total_copies_input;

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
            return ["success" => true, "message" => "Book already exists (by ISBN). Copies updated successfully!"];
        } else {
            return ["success" => false, "message" => "Error updating book copies: " . mysqli_error($conn)];
        }
    } else {
        $insert_sql = "INSERT INTO books (title, author, category_id, available_copies, total_copies, image_url, isbn)
                       VALUES ('$title_esc', '$author_esc', $category_id, $total_copies, $total_copies, '$image_url_esc', '$isbn_esc')";
        if (mysqli_query($conn, $insert_sql)) {
            return ["success" => true, "message" => "Book added successfully!"];
        } else {
            return ["success" => false, "message" => "Database error: " . mysqli_error($conn)];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['upload_csv']) && isset($_FILES['csv_file'])) {
        $file = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($file, "r")) !== false) {
            $header = fgetcsv($handle); // Skip header row
            $rowCount = 0;
            $errorCount = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                list($title, $author, $isbn, $category_id, $total_copies_input, $image_url) = array_map('trim', $data);

                $validationError = validateBookData($title, $author, $isbn, $category_id, $total_copies_input);
                if ($validationError) {
                    $errorCount++;
                    continue;
                }

                $result = addOrUpdateBook($conn, $title, $author, $isbn, (int)$category_id, $total_copies_input, $image_url);
                if (!$result['success']) {
                    $errorCount++;
                }
                $rowCount++;
            }

            fclose($handle);
            $success = "$rowCount records processed. " . ($errorCount ? "$errorCount had issues." : "All successful!");
        } else {
            $error = "Failed to open the uploaded CSV.";
        }
    } else {
        // Single book input from form
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $isbn = trim($_POST['isbn']);
        $category_id = (int)$_POST['category_id'];
        $total_copies_input = trim($_POST['total_copies']);
        $image_url = trim($_POST['image_url']);

        $validationError = validateBookData($title, $author, $isbn, $category_id, $total_copies_input);
        if ($validationError) {
            $error = $validationError;
        } else {
            $result = addOrUpdateBook($conn, $title, $author, $isbn, $category_id, $total_copies_input, $image_url);
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add New Book</title>
    <link rel="stylesheet" href="styles/addBook.css">
</head>
<body>

    <h2>Add a New Book</h2>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

<div class="form-container">
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
    <form method="POST" action="add_book.php" enctype="multipart/form-data" style="margin-top: 20px;">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" name="upload_csv" class="btn">Upload CSV</button>
    </form>
</div>

</body>
</html>