<?php
  // categories.php
  require_once 'db.php';
  try {
      // Fetch unique lowercase categories
      $categoryStmt = $pdo->query("SELECT DISTINCT LOWER(category) AS category FROM books WHERE category IS NOT NULL AND category != ''");
      $rawCategories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
      $categories = array_unique(array_map('strtolower', $rawCategories));
      sort($categories);

      // Selected category or search term
      $selectedCategory = isset($_GET['category']) ? strtolower(trim($_GET['category'])) : null;
      $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

      if (!empty($searchQuery)) {
          $stmt = $pdo->prepare("SELECT title, author, category, price, file_path FROM books 
                                WHERE title LIKE :search OR author LIKE :search OR category LIKE :search 
                                ORDER BY created_at DESC");
          $stmt->execute(['search' => '%' . $searchQuery . '%']);
          $heading = "Search results for: <em>" . htmlspecialchars($searchQuery) . "</em>";

      } elseif ($selectedCategory === 'all') {
          $stmt = $pdo->query("SELECT title, author, category, price, file_path FROM books ORDER BY created_at DESC");
          $heading = "All Books";

      } elseif ($selectedCategory && in_array($selectedCategory, $categories)) {
          $stmt = $pdo->prepare("SELECT title, author, category, price, file_path FROM books 
                                WHERE LOWER(category) = :cat ORDER BY created_at DESC");
          $stmt->execute(['cat' => $selectedCategory]);
          $heading = "Books in " . ucfirst($selectedCategory);

      } else {
          $stmt = $pdo->query("SELECT title, author, category, price, file_path FROM books ORDER BY created_at DESC LIMIT 6");
          $heading = "Featured Books";
      }

      $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $heading ?></title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

  <header>
    <nav class="navbar">
      <div class="logo">eBookStore</div>
      <div class="search-bar">
        <form method="GET" action="categories.php">
          <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($searchQuery) ?>" />
          <button type="submit">Search</button>
        </form>
      </div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
          <a href="#">Categories</a>
          <ul class="dropdown-content">
            <li><a href="categories.php?category=all">All</a></li>
            <?php foreach ($categories as $cat): ?>
              <li>
                <a href="categories.php?category=<?= urlencode($cat) ?>" class="<?= ($selectedCategory === $cat) ? 'active-category' : '' ?>">
                  <?= ucfirst(htmlspecialchars($cat)) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li><a href="login.php">Login</a></li>
      </ul>
    </nav>
  </header>

    <section class="featured-books">
      <h2><?= $heading ?></h2>
      <div class="book-grid">
        <?php if ($books): ?>
          <?php foreach ($books as $book): ?>
            <div class="book-card">
              <iframe src="<?= htmlspecialchars($book['file_path']) ?>"></iframe>
              <h3><?= htmlspecialchars($book['title']) ?></h3>
              <p><?= htmlspecialchars($book['author']) ?></p>
              <p><strong>Price: $<?= number_format($book['price'], 2) ?></strong></p>
              <p><strong>Category:</strong> <?= ucfirst($book['category']) ?></p>
              <form method="POST" action="cart.php">
                <input type="hidden" name="title" value="<?= htmlspecialchars($book['title']) ?>">
                <input type="hidden" name="author" value="<?= htmlspecialchars($book['author']) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($book['price']) ?>">
                <input type="hidden" name="category" value="<?= htmlspecialchars($book['category']) ?>">
                <input type="hidden" name="file_path" value="<?= htmlspecialchars($book['file_path']) ?>">
                <button type="submit">Buy Now</button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No books found.</p>
        <?php endif; ?>
      </div>
    </section>

  <footer class="footer">
    <p>&copy; 2025 Student eBook Store. All rights reserved.</p>
  </footer>

  </body>
  </html>
