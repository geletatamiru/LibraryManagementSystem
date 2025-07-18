<?php 
$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Search</title>
    <link rel="stylesheet" href="../assets/css/search.css">
</head>
<body>
    <form method='GET' action='' style='display: flex; gap: 10px; align-items: center; margin-bottom: 20px; max-width: 500px;'>
        <input type='hidden' name='section' value='search'>
        <input
            type='text'
            name='query'
            placeholder='Search books by title or author'
            required
            style='flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px;'
        />
        <button
            type='submit'
            style='padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;'
        >
            🔍 Search
        </button>
    </form>
    <?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
      if (isset($_GET['query'])) {
          $search = '%' . $_GET['query'] . '%';

          $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
          $stmt = mysqli_prepare($conn, $sql);

          if ($stmt) {
              mysqli_stmt_bind_param($stmt, "ss", $search, $search);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);

              echo "<h2>Search Results for: <em>" . htmlspecialchars($_GET['query']) . "</em></h2>";

              if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo "<div class='search-card'>";
                      echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
                      echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
                      echo "<p>Available Copies: " . $row['available_copies'] . "</p>";
                      if ($row['available_copies'] > 0 && $user_id) {
                            echo "<form method='POST' action=''>";
                            echo "<input type='hidden' name='book_id' value='" . $row['id'] . "' />";
                            echo "<button type='submit'>📘 Borrow</button>";
                            echo "</form>";
                        } else if ($row['available_copies'] <= 0) {
                            echo "<p style='color: red;'>Unavailable</p>";
                        } else if (!$user_id) {
                            echo "<p style='color: orange;'>Login to borrow</p>";
                        }
                      echo "</div><hr/>";
                  }
              } else {
                  echo "<p class='no-results'>No books found matching your search.</p>";
              }

              mysqli_stmt_close($stmt);
          } else {
              echo "<p class='error-message'>Error in query preparation: " . mysqli_error($conn) . "</p>";
          }
      }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id']) && $user_id) {
        $book_id = $_POST['book_id'];

        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM borrowings WHERE user_id = ? AND status IN ('pending', 'approved', 'return_requested')");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res['count'] >= 2) {
            echo "<p class='error-message'>❌ You can only have up to 2 active or pending borrow requests.</p>";
            exit;
        }
        $stmt = $conn->prepare("SELECT * FROM borrowings WHERE user_id = ? AND book_id = ? AND status IN ('pending', 'approved', 'return_requested')");
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $check_duplicate = $stmt->get_result();

        if ($check_duplicate->num_rows > 0) {
            echo "<p class='error-message'>❌ You already borrowed or requested the book and haven’t returned it.</p>";
            exit;
        }


        $check = $conn->prepare("SELECT available_copies FROM books WHERE id = ?");
        $check->bind_param("i", $book_id);
        $check->execute();
        $result = $check->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if ($row['available_copies'] > 0) {
                $insert = $conn->prepare("
                    INSERT INTO borrowings (user_id, book_id, status)
                    VALUES (?, ?, 'pending')
                ");
                $insert->bind_param("ii", $user_id, $book_id);
                $insert->execute();

                echo "<p class='success-message'>✅ Borrow request sent! Waiting for admin approval.</p>";
            } else {
                echo "<p class='error-message'>❌ Book is not available.</p>";
            }
        } else {
            echo "<p class='error-message'>❌ Book not found.</p>";
        }

    }


    mysqli_close($conn);
    ?>
</body>
</html>
