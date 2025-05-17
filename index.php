<?php
  include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>
<main class="container">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
          <h1>📚 Discover Your Next Great Read</h1>
          <p class="hero-subtitle">Easy borrowing, instant access, and a world of knowledge at your fingertips.</p>
          <div class="buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="users_dashboard.php" class="btn btn-secondary">Get Started</a>
            <?php else: ?>
                <a href="views/login.php" class="btn btn-primary">Login</a>
                <a href="views/register.php" class="btn btn-secondary">Register</a>
            <?php endif; ?>
            
          </div>
        </div>
      </section>

    <!-- About Section -->
    <section class="about" id="about">
      <div class="about-container">
        <div class="about-image">
          <img src="assets/images/lib3.jpg" alt="Library Image" />
        </div>
        <div class="about-text">
          <h2>About Our Library</h2>
          <p>
            Our digital library platform empowers students and readers to explore thousands of books anytime, anywhere. 
            Easily browse, borrow, and manage your reading experience with user-friendly features designed for your convenience.
          </p>
          <p>
            We strive to make knowledge accessible and foster a community of lifelong learners through technology.
          </p>
        </div>
      </div>
    </section>


    <!-- Features -->
    <section class="features">
      <h2>Why Choose Our Library System?</h2>
      <div class="feature-cards">
        <div class="feature-card">
          <div class="icon">📚</div>
          <h3>Easy Book Browsing</h3>
          <p>Quickly search and browse through thousands of books with intuitive filters.</p>
        </div>
        <div class="feature-card">
          <div class="icon">🔄</div>
          <h3>Seamless Borrow & Return</h3>
          <p>Borrow your favorite books and return them effortlessly with our smooth process.</p>
        </div>
        <div class="feature-card">
          <div class="icon">⏰</div>
          <h3>Due Date Tracking</h3>
          <p>Stay on top of your borrowings with clear due date reminders and overdue alerts.</p>
        </div>
        <div class="feature-card">
          <div class="icon">🛠️</div>
          <h3>Admin Management</h3>
          <p>Admins can easily manage book records and monitor user transactions efficiently.</p>
        </div>
      </div>
    </section>


    <!-- Categories -->
    <section class="categories">
      <h2>Explore Book Categories</h2>
      <div class="category-cards">
        <div class="category-card">
          <h3>Fiction</h3>
          <p>Immerse yourself in imaginative stories and narratives.</p>
        </div>
        <div class="category-card">
          <h3>Science</h3>
          <p>Discover books on physics, biology, technology, and more.</p>
        </div>
        <div class="category-card">
          <h3>History</h3>
          <p>Learn about the past through detailed historical accounts.</p>
        </div>
        <div class="category-card">
          <h3>Art & Culture</h3>
          <p>Explore creativity, art history, and cultural studies.</p>
        </div>
      </div>
    </section>

<!-- Footer -->
  <footer>
    <p>&copy; 2025 My Library System. All rights reserved.</p>
  </footer>

</body>
</html>