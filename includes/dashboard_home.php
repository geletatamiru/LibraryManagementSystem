<?php
// You can later replace these with actual queries if needed
$total_books = 1200;
$borrowed_books = 3;
?>

<h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
<p>Here’s a quick overview of your account:</p>

<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
  <div style="background-color: #ecf0f1; padding: 20px; border-radius: 10px; flex: 1;">
    <h3>Total Books</h3>
    <p><?php echo $total_books; ?></p>
  </div>
  <div style="background-color: #ecf0f1; padding: 20px; border-radius: 10px; flex: 1;">
    <h3>Books You Borrowed</h3>
    <p><?php echo $borrowed_books; ?></p>
  </div>
  <div style="background-color: #ecf0f1; padding: 20px; border-radius: 10px; flex: 1;">
    <h3>Due Dates</h3>
    <p>You have no due books right now.</p>
  </div>
</div>
