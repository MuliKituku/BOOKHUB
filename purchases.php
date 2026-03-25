  <?php
  session_start();

  // Redirect to login if not authenticated
  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit();
  }

  require_once 'db.php';

  try {

      $userId = $_SESSION['user_id'];

      // Fetch all purchases for this user
      $stmt = $pdo->prepare("SELECT id, book_title, amount, purchase_date FROM purchases WHERE user_id = ? ORDER BY purchase_date DESC");
      $stmt->execute([$userId]);
      $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>All Purchases</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

  <header>
    <nav class="navbar">
      <div class="logo">eBookStore</div>
      <ul class="nav-links">
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="purchases.php" class="active">Purchases</a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="purchases-container">
    <h2>Your Purchase History</h2>

    <?php if ($purchases): ?>
      <table>
        <thead>
          <tr>
            <th>Book Title</th>
            <th>Amount (KES)</th>
            <th>Date</th>
            <th>Receipt</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($purchases as $purchase): ?>
            <tr>
              <td><?= htmlspecialchars($purchase['book_title']) ?></td>
              <td><?= number_format($purchase['amount'], 2) ?></td>
              <td><?= date("F j, Y", strtotime($purchase['purchase_date'])) ?></td>
              <td><a href="download_receipt.php?id=<?= $purchase['id'] ?>" target="_blank">Download</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>You have not made any purchases yet.</p>
    <?php endif; ?>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Student eBook Store. All rights reserved.</p>
  </footer>

  </body>
  </html>
