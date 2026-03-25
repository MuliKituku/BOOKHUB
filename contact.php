<?php
    require_once 'mailer.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name    = $_POST['name'];
        $email   = $_POST['email'];
        $subject = $_POST['subject'];
        $body    = $_POST['message'];

        if (!$name || !$email || !$subject || !$body) {
            $message = '<p style="color:red;">All fields are required.</p>';
        } else {
            try {
                $mail = getMailer();
                // Recipients
                $mail->setFrom($email, $name);
                $mail->addAddress($_ENV['SMTP_USER'], 'Admin');

                // Content
                $mail->Subject = $subject;
                $mail->Body    = $body;

                $mail->send();
                $message = '<p style="color:green;">✅ Message sent successfully!</p>';
            } catch (Exception $e) {
                $message = '<p style="color:red;">❌ Mail error: ' . $mail->ErrorInfo . '</p>';
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Contact Us</title>
        <link rel="stylesheet" href="style.css">
        <style>
            body { font-family: Arial; padding: 20px; max-width: 600px; margin: auto; }
            form { display: flex; flex-direction: column; gap: 10px; }
            input, textarea { padding: 8px; font-size: 16px; }
            button { padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer; }
            button:hover { background-color: #0056b3; }
        </style>
    </head>
    <body>
        <h2><img src="assets/contact.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;"> Contact Us</h2>
        <?= $message ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </body>
    </html>
