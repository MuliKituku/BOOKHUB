require_once 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password1 = $_POST["password"];
        $password2 = $_POST["confirm_password"];

        if (empty($name) || empty($email) || empty($password1) || empty($password2)) {
            header("Location: register.php?error=Please fill all fields");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: register.php?error=Invalid email format");
            exit();
        }

        if ($password1 !== $password2) {
            header("Location: register.php?error=Passwords do not match");
            exit();
        }

        if (strlen($password1) < 6) {
            header("Location: register.php?error=Password must be at least 6 characters");
            exit();
        }

        $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));

        try {

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                header("Location: register.php?error=Email already registered");
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, verification_token) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword, $token]);

            // Send verification email
            require_once 'mailer.php';
            $verify_link = $_ENV['APP_URL'] . "/verify_email.php?token=" . $token;

            $mail = getMailer();
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = "Verify your email address";
            $mail->Body = "
                <h3>Hi $name,</h3>
                <p>Thanks for registering at eBookStore.</p>
                <p>Click the link below to verify your email:</p>
                <p><a href='$verify_link'>$verify_link</a></p>
                <p>If you did not request this, you can ignore this email.</p>
            ";

            $mail->send();
            header("Location: register.php?success=Check your email to verify your account.");
            exit();

        } catch (Exception $e) {
            header("Location: register.php?error=Email could not be sent. Mail error: {$mail->ErrorInfo}");
            exit();
        } catch (PDOException $e) {
            header("Location: register.php?error=Database error: " . $e->getMessage());
            exit();
        }
    } else {
        header("Location: register.php");
        exit();
    }
