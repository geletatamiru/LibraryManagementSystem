<?php
include_once 'header.php';
?>


<!DOCTYPE html>
<html>

<head>
    <title>Library Managment | Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .book-list {
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
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .addbookBtn-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 40px;
        }

        h2 {
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.2rem;
        }

        .overdue {
            color: red;
            font-weight: bold;
        }
        .editBtns {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .addbtn,.incrementBtn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .addbtn:hover,.incrementBtn:hover {
            background-color: #0056b3;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete {
            background-color:rgb(226, 75, 75);
        }
        .btn-secondary {
            background-color: gray;
        }
        .editBtns .btn-secondary,.editBtns .btn-secondary {
            padding: 5px;
            width: 40px;
            height: 40px;
            display: flex;
            font-weight: bold;
            font-size: 1.5rem;
            justify-content: center;
            align-items: center;
        }
        .btn-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <?php

    $sql = "select books.id,title,author,name,available_copies,total_copies,image_url from books join categories on books.category_id = categories.id ORDER BY books.title LIMIT 20";
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