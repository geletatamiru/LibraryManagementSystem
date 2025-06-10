<?php
include_once 'header.php';
?>


<!DOCTYPE html>
<html>

<head>
    <title>Library Managment | Books</title>
    <link rel="stylesheet" href="styles/manageBooks.css">
</head>
<body>
    <?php

    $sql = "select books.id,title,author,name,available_copies,total_copies,image_url from books join categories on books.category_id = categories.id ORDER BY books.title";
    $result = mysqli_query($conn, $sql);

    echo "<div class='addbookBtn-container'><h3>Total Books</h3>" . "<a href='add_book.php' class='addbtn'>Add Book</a></div>";
    echo "<div class='book-list'>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $deleteBtn = "<button class='btn btn-delete open-modal' data-id='{$row['id']}' data-title='" . htmlspecialchars($row['title']) . "'>
            Delete Book </button>";
            echo "<div class='book-card'>";
            echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
            echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
            echo "<p>Category: " . $row['name'] . "</p>";
            echo "<p>Available Copies: " . htmlspecialchars($row['available_copies']) . "</p>";
            echo "<p>Total Copies: " . htmlspecialchars($row['total_copies']) . "</p>";
            echo "<div class='btn-container'>{$deleteBtn}<form method=\"POST\" action=\"modify_book_copies.php\" class='editBtns'>
                        <input type=\"hidden\" name=\"book_id\" value=\"{$row['id']}\"/>
                        <button type=\"submit\" class=\"btn btn-secondary\" name='action' value='increment'>+</button>
                        <p>Copies</p>
                        <button type=\"submit\" class=\"btn btn-secondary\" name='action' value='decrement'>-</button>
                    </form></div>";
            echo "</div>";
        }
    }
    else {
        echo "<p>No books found.</p>";
    }
    echo "</div>";
    ?>
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete <strong id="modalBookTitle"></strong>?</p>
            <form id="deleteForm" method="POST" action="delete_book.php">
                <input type="hidden" name="book_id" id="modalBookId">
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
                    <button type="submit" class="btn btn-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.open-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const bookId = btn.getAttribute('data-id');
                const bookTitle = btn.getAttribute('data-title');
                document.getElementById('modalBookId').value = bookId;
                document.getElementById('modalBookTitle').innerText = bookTitle;
                document.getElementById('deleteModal').style.display = 'flex';
            });
        });

        document.getElementById('cancelModal').addEventListener('click', () => {
            document.getElementById('deleteModal').style.display = 'none';
        });
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

</body>

</html>