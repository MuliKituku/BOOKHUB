<?php
// mailer.php
require_once 'db.php'; // Ensures .env is loaded

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Get a configured PHPMailer instance using environment variables.
 * @return PHPMailer
 */
function getMailer() {
    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $_ENV['SMTP_PORT'];

    // Default sender
    $mail->setFrom($_ENV['SMTP_USER'], 'eBookStore');

    return $mail;
}
?>
