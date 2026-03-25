  <?php
  // index.php
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
    <title>Student eBook Store</title>
    <link rel="stylesheet" href="style.css" />
    <style>
      .search-bar form {
        display: flex;
        gap: 1px;
      }

      .search-bar input[type="text"] {
        padding: 8px;
        width: 200px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        flex: 1;
      }

      .search-bar button {
        padding: 8px 12px;
        font-size: 14px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
      }

      .search-bar button:hover {
        background-color: #0056b3;
      }

      .active-category {
        font-weight: bold;
        color: darkblue;
      }

      .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
      }

      .book-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 16px;
        background: #f9f9f9;
        text-align: center;
      }

      .book-card iframe {
        width: 100%;
        height: 250px;
        border: none;
        margin-bottom: 10px;
      }

      .explore-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #0056b3;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
      }

      .explore-btn:hover {
        background-color: #003f7f;
      }
    </style>
  </head>
  <body>

    <!-- Header -->
    <header>
      <nav class="navbar">
        <div class="logo">eBookStore</div>
        <div class="search-bar">
          <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($searchQuery) ?>" />
            <button type="submit">Search</button>
          </form>
        </div>
        <div class="nav-toggle">☰</div>
        <ul class="nav-links">
          <li><a href="index.php" class="<?= (!$selectedCategory && empty($searchQuery)) ? 'active' : '' ?>">Home</a></li>
          <li class="dropdown">
            <a href="#">Categories</a>
            <ul class="dropdown-content">
              <li><a href="categories.php?category=all" class="<?= ($selectedCategory === 'all') ? 'active-category' : '' ?>">All</a></li>
              <?php foreach ($categories as $cat): ?>
                <li>
                  <a href="categories.php?category=<?= urlencode($cat) ?>" class="<?= ($selectedCategory === $cat) ? 'active-category' : '' ?>">
                    <?= ucfirst($cat) ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
          <li><a href="login.php">My Account</a></li>
          <li><a href="login.php">Login</a></li>
          <li><a href="contact.php" target="_blank">Contact Us</a></li>
          <li><a href="cart.php">Cart</a></li>
        </ul>
      </nav>
    </header>

    <?php if (empty($searchQuery)): ?>
    <section class="intro-section">
      <div class="intro-left">
        <h1>Welcome to the <span>Student eBook Store</span></h1>
        <p>Browse our digital collection for affordable academic success.</p>
        <a href="categories.php?category=all" class="explore-btn" target="_blank">Explore Books</a>
      </div>
      <div class="intro-right">
        <img src="./assets/hero.PNG" alt="Students reading books" />
      </div>
    </section>
    <?php endif; ?>

    <!-- Book Listing -->
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

    <!-- Footer -->
    <footer class="footer">
      <p>&copy; 2025 Student eBook Store. All rights reserved.</p>
    </footer>

    <script>
      const toggle = document.querySelector(".nav-toggle");
      const navLinks = document.querySelector(".nav-links");
      toggle.addEventListener("click", () => {
        navLinks.classList.toggle("show");
      });
    </script>
  </body>
  </html>
