  <?php
  // cart.php
  session_start();

  // Initialize cart if it doesn't exist
  if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
  }

  // Handle adding item via POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'] ?? '';
      $author = $_POST['author'] ?? '';
      $price = $_POST['price'] ?? '';
      $category = $_POST['category'] ?? '';
      $file_path = $_POST['file_path'] ?? './uploads/default.jpg';

      // Check for duplicates
      $exists = false;
      foreach ($_SESSION['cart'] as $item) {
          if ($item['title'] === $title) {
              $exists = true;
              break;
          }
      }

      if (!$exists) {
          $_SESSION['cart'][] = [
              'title' => $title,
              'author' => $author,
              'price' => $price,
              'category' => $category,
              'file_path' => $file_path
          ];
      }
  }

  // Handle item removal
  if (isset($_GET['remove'])) {
      $titleToRemove = $_GET['remove'];
      $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($titleToRemove) {
          return $item['title'] !== $titleToRemove;
      });
  }

  // Calculate total
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
      $total += floatval($item['price']);
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
      body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 40px 20px;
    background-color: #f2f2f2;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    text-align: center;
    flex-direction: column;
  }

  h2 {
    margin-bottom: 30px;
    font-size: 28px;
    width: 100%;
  }
    </style>
  </head>
  <body>
    <h2>Your Shopping Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
      <p>Your cart is currently empty.</p>
      <a href="categories.php">Continue Shopping</a>
    <?php else: ?>
      <div class="cart-container">
        <?php foreach ($_SESSION['cart'] as $item): ?>
          <div class="cart-item">
            <iframe src="<?= htmlspecialchars($item['file_path']) ?>"></iframe>
            <div class="cart-info">
              <h3><?= htmlspecialchars($item['title']) ?></h3>
              <p><strong>Author:</strong> <?= htmlspecialchars($item['author']) ?></p>
              <p><strong>Category:</strong> <?= ucfirst($item['category']) ?></p>
              <p><strong>Price:</strong> $<?= number_format($item['price'], 2) ?></p>
              <a class="remove-btn" href="cart.php?remove=<?= urlencode($item['title']) ?>">Remove</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <p class="total">Total: $<?= number_format($total, 2) ?></p>
      <div class="actions">
        <a href="categories.php">Continue Shopping</a>
        <a href="checkout.php">Proceed to Checkout</a>
      </div>
    <?php endif; ?>
  </body>
  </html>
