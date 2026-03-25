  <?php
  session_start();

  // Redirect if not logged in
  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit();
  }

  require_once 'db.php';

  try {
      $userId = $_SESSION['user_id'];

      // Fetch user details
      $stmtUser = $pdo->prepare("SELECT name, email, is_verified, created_at FROM users WHERE id = ?");
      $stmtUser->execute([$userId]);
      $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
      $email = $user['email']; // Store user's email for later queries (e.g., orders)

      // Fetch last 5 purchases
      $stmtPurchases = $pdo->prepare("SELECT id, book_title, amount, purchase_date FROM purchases WHERE user_id = ? ORDER BY purchase_date DESC LIMIT 5");
      $stmtPurchases->execute([$userId]);
      $purchases = $stmtPurchases->fetchAll(PDO::FETCH_ASSOC);

      // Fetch last 5 orders
      $stmtOrders = $pdo->prepare("SELECT id, name, email, phone, total, created_at, status FROM orders WHERE email = ? ORDER BY created_at DESC LIMIT 5");
      $stmtOrders->execute([$email]);
      $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

  } catch (PDOException $e) {
      die("Database error: " . $e->getMessage());
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

  <header>
    <nav class="navbar">
      <div class="logo">eBookStore</div>
      <ul class="nav-links">
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="purchases.php">Purchases</a></li>
        <li><a href="account.php" class="active">Account</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="account-container">
    <section class="user-details">
      <h2>User Details</h2>

      <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
      <?php endif; ?>

      <?php if ($user): ?>
        <ul>
          <li><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></li>
          <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
          <li><strong>Verified:</strong> <?= htmlspecialchars($user['is_verified']) ?></li>
          <li><strong>Member Since:</strong> <?= date("F j, Y", strtotime($user['created_at'])) ?></li>
        </ul>

        <a href="update_profile.php">
          <button type="button">Update Profile</button>
        </a>

      <?php else: ?>
        <p>User details not found.</p>
      <?php endif; ?>
    </section>

    <section class="user-purchases">
      <h2>Recent Purchases</h2>
      <?php if ($purchases): ?>
        <table>
          <thead>
            <tr>
              <th>Book Title</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Receipt</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($purchases as $purchase): ?>
              <tr>
                <td><?= htmlspecialchars($purchase['book_title']) ?></td>
                <td>$ <?= number_format($purchase['amount'], 2) ?></td>
                <td><?= date("F j, Y", strtotime($purchase['purchase_date'])) ?></td>
                <td><a href="download_receipt.php?id=<?= $purchase['id'] ?>" target="_blank">Download</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No recent purchases found.</p>
      <?php endif; ?>
    </section>

    <section class="user-orders">
      <h2>Recent Orders</h2>
      <?php if ($orders): ?>
        <table style="border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #ccc; padding: 8px;">Order ID</th>
        <th style="border: 1px solid #ccc; padding: 8px;">Name</th>
        <th style="border: 1px solid #ccc; padding: 8px;">Email</th>
        <th style="border: 1px solid #ccc; padding: 8px;">Phone</th>
        <th style="border: 1px solid #ccc; padding: 8px;">Total</th>
        <th style="border: 1px solid #ccc; padding: 8px;">Date</th>
        <th style="border: 1px solid #ccc; padding: 8px;">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
        <tr>
          <td style="border: 1px solid #ccc; padding: 8px;">#<?= htmlspecialchars($order['id']) ?></td>
          <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($order['name']) ?></td>
          <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($order['email']) ?></td>
          <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($order['phone']) ?></td>
          <td style="border: 1px solid #ccc; padding: 8px;">$ <?= number_format($order['total'], 2) ?></td>
          <td style="border: 1px solid #ccc; padding: 8px;"><?= date("F j, Y", strtotime($order['created_at'])) ?></td>
          <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars(ucfirst($order['status'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

      <?php else: ?>
        <p>No recent orders found.</p>
      <?php endif; ?>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Student eBook Store. All rights reserved.</p>
  </footer>

  </body>
  </html>
