  <?php
  // reset_password.php
  session_start();
  $error   = $_GET['error']   ?? '';
  $success = $_GET['success'] ?? '';
  $token   = $_GET['token']   ?? '';
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Reset Password – eBookStore</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div class="auth-form">
      <h2>Reset Your Password</h2>

      <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
      <?php elseif ($success): ?>
        <p class="success-message"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <?php if ($token): ?>
        <form method="POST" action="reset_process.php">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

          <label for="new_password">New Password</label>
          <input
            type="password"
            id="new_password"
            name="new_password"
            required
            placeholder="••••••••"
          >

          <label for="confirm_password">Confirm New Password</label>
          <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            required
            placeholder="••••••••"
          >

          <button type="submit">Reset Password</button>
        </form>
      <?php else: ?>
        <p class="error-message">Invalid or missing token.</p>
      <?php endif; ?>

      <p><a href="login.php">Back to Login</a></p>
    </div>
  </body>
  </html>
