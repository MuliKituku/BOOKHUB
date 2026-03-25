    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once 'db.php';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+2 hour')); //check the time zone to know whether the reset link is valid. run the command (php check_timezone.php)
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token, $expiry, $email]);

        // Setup mailer.php
        require_once 'mailer.php';
        $mail = getMailer();

        try {
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click this link to reset your password:<br><a href='{$_ENV['APP_URL']}/reset_password.php?token=$token'>Reset Password</a> 
            Expires in the next 1 hour<br>";

            $mail->send();
            echo "Reset email sent.";
        } catch (Exception $e) {
            echo "Email not sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "If your email is in our system, you'll receive a reset link.";
    }
