    <?php
    require_once 'db.php';

    $token = $_GET['token'] ?? '';

    if (!$token) {
        die("Invalid or missing token.");
    }

    try {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            echo "✅ Email verified successfully! You can now <a href='login.php'>login</a>.";
        } else {
            echo "❌ Invalid or expired verification token.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
    ?>
