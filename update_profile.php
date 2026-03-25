  <?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit();
  }

  require_once 'db.php';

  $userId = $_SESSION['user_id'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = trim($_POST['name']);
      $email = trim($_POST['email']);

      $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
      $stmt->execute([$name, $email, $userId]);

      header("Location: account.php");
      exit();
  } else {
      // Get current user details
      $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
      $stmt->execute([$userId]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

  <header>
    <nav class="navbar">
      <div class="logo">eBookStore</div>
      <ul class="nav-links">
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="purchases.php">Purchases</a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="update-form-container">
    <h2>Update Profile</h2>
    <form method="post" action="update_profile.php">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name']) ?>">

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">

      <button type="submit" name="update_profile">Save Changes</button>
    </form>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Student eBook Store. All rights reserved.</p>
  </footer>

  </body>
  </html>
