  <?php
  // forgot_password.php
  $error   = $_GET['error']   ?? '';
  $success = $_GET['success'] ?? '';
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Forgot Password – eBookStore</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div class="auth-form">
      <h2>Forgot Password</h2>

      <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
      <?php elseif ($success): ?>
        <p class="success-message"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <form action="forgot_process.php" method="POST">
        <label for="email">Enter your email</label>
        <input
          type="email"
          id="email"
          name="email"
          required
          placeholder="you@example.com"
        >

        <button type="submit">Send Reset Link</button>
        <p><a href="login.php">Back to Login</a></p>
      </form>
    </div>
  </body>
  </html>
