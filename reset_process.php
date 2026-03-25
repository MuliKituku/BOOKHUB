    <?php
    // reset_process.php

    require_once 'db.php';

    // Sanitize input
    $token = $_POST['token'] ?? '';
    $pass1 = $_POST['new_password'] ?? '';
    $pass2 = $_POST['confirm_password'] ?? '';

    if (!$token) {
        exit("Missing token.");
    }

    if ($pass1 !== $pass2) {
        exit("Passwords do not match.");
    }

    // Check if token exists and has not expired
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Token is valid, update password
        $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt->execute([$hashedPassword, $user['id']]);

        echo "✅ Password reset successful. <a href='login.php'>Click here to login</a>";
    } else {
        echo "❌ Invalid or expired token.";
    }
