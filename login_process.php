<?php
    session_start();
    require_once 'db.php';
    require 'vendor/autoload.php'; // Load PHPMailer

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $user_password = $_POST['password'];

        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                header("Location: register.php?error=Email not found. Please register.");
                exit();
            }

            // Check password
            if (!password_verify($user_password, $user['password'])) {
                header("Location: login.php?error=Invalid email or password");
                exit();
            }

            // Not verified? Resend verification email
            if (!$user['is_verified']) {
                $verification_token = bin2hex(random_bytes(16));

                // Update token
                $stmt = $pdo->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
                $stmt->execute([$verification_token, $user['id']]);

                // Send email using mailer.php
                require_once 'mailer.php';
                $mail = getMailer();
                try {
                    $mail->addAddress($user['email'], $user['name']);
                    $mail->isHTML(true);
                    $mail->Subject = 'Verify Your Email Address';
                    $mail->Body = "
                        <p>Hello {$user['name']},</p>
                        <p>Please verify your email by clicking the link below:</p>
                        <p><a href='{$_ENV['APP_URL']}/verify_email.php?token=$verification_token'>Verify Email</a></p>
                        <p>Thank you,<br>eBookStore Team</p>
                    ";

                    $mail->send();
                    header("Location: login.php?error=Email not verified. A verification link has been sent.");
                    exit();
                } catch (Exception $e) {
                    header("Location: login.php?error=Email sending failed: {$mail->ErrorInfo}");
                    exit();
                }
            }

            // Verified? Log in
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];

            header("Location: dashboard.php");
            exit();

        } catch (PDOException $e) {
            header("Location: login.php?error=Database connection error");
            exit();
        }
    } else {
        header("Location: login.php");
        exit();
    }
