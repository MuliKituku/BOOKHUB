  <?php
  session_start();
  require 'vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require_once('vendor/tecnickcom/tcpdf/tcpdf.php'); // Adjust path if needed
  // use TCPDF;

  require_once 'db.php';

  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
      header('Location: cart.php');
      exit();
  }

  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
      $total += floatval($item['price']);
  }

  $successMessage = '';
  $errors = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $phone = trim($_POST['phone'] ?? '');

      if (empty($name)) $errors[] = "Name is required.";
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
      if (!preg_match('/^\d{10,15}$/', $phone)) $errors[] = "Invalid phone number.";

      if (empty($errors)) {
          $stmt = $pdo->prepare("INSERT INTO orders (name, email, phone, total) VALUES (?, ?, ?, ?)");
          $stmt->execute([$name, $email, $phone, $total]);
          $orderId = $pdo->lastInsertId();

          // Generate PDF Invoice
          $pdf = new TCPDF();
          $pdf->AddPage();
          $pdf->SetFont('helvetica', '', 12);
          $html = "<h2>Invoice #$orderId</h2>";
          $html .= "<p><strong>Name:</strong> $name<br><strong>Email:</strong> $email<br><strong>Phone:</strong> $phone</p>";
          $html .= "<h4>Items:</h4><ul>";

          foreach ($_SESSION['cart'] as $item) {
              $html .= "<li>" . htmlspecialchars($item['title']) . " — $" . number_format($item['price'], 2) . "</li>";
          }

          $html .= "</ul><strong>Total:</strong> $" . number_format($total, 2);
          $pdf->writeHTML($html);
          $pdfPath = __DIR__ . "/invoice_$orderId.pdf";
          $pdf->Output($pdfPath, 'F');

          // Send email to customer using mailer.php
          require_once 'mailer.php';
          $mail = getMailer();
          try {
              $mail->addAddress($email, $name);
              $mail->addAttachment($pdfPath);

              $mail->isHTML(true);
              $mail->Subject = "Order Confirmation - Invoice #$orderId";
              $mail->Body = "Thank you for your order, $name. Please find your invoice attached.";

              $mail->send();
          } catch (Exception $e) {
              $errors[] = "Mail to customer failed: {$mail->ErrorInfo}";
          }

          // Send email to admin
          try {
              $adminMail = getMailer();
              $adminMail->addAddress($_ENV['SMTP_USER'], 'Store Admin'); // Admin email
              $adminMail->addAttachment($pdfPath);

              $adminMail->isHTML(true);
              $adminMail->Subject = "New Order Received - #$orderId";
              $adminMail->Body = "A new order has been placed by $name. Invoice is attached.";

              $adminMail->send();
          } catch (Exception $e) {
              $errors[] = "Mail to admin failed: {$adminMail->ErrorInfo}";
          }

          // Clear cart
          $_SESSION['cart'] = [];
          unlink($pdfPath); // Optional: Delete invoice after sending
          $successMessage = "✅ Thank you, $name! Your order has been placed successfully. An invoice has been sent to $email.";
      }
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
      body { font-family: Arial; padding: 20px; }
      .checkout-box {
        background: #f4f4f4; padding: 20px; border-radius: 10px;
        max-width: 500px; margin: auto;
      }
      .form-group { margin-bottom: 15px; }
      input[type="text"], input[type="email"] {
        width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;
      }
      .error { color: red; margin-bottom: 10px; }
      .success { color: green; font-weight: bold; }
      .btn {
        background-color: #007BFF; color: white; padding: 10px 20px;
        border: none; border-radius: 5px; cursor: pointer;
      }
      .btn:hover { background-color: #0056b3; }
    </style>
  </head>
  <body>

  <div class="checkout-box">
    <h2>Checkout</h2>

    <?php if ($successMessage): ?>
      <p class="success"><?= $successMessage ?></p>
      <a href="index.php">← Return to Store</a>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <div class="error"><?= implode('<br>', $errors) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label for="name">Full Name:</label>
          <input type="text" name="name" id="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="phone">Phone Number:</label>
          <input type="text" name="phone" id="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>

        <h4>Order Summary</h4>
        <ul>
          <?php foreach ($_SESSION['cart'] as $item): ?>
            <li><?= htmlspecialchars($item['title']) ?> — $<?= number_format($item['price'], 2) ?></li>
          <?php endforeach; ?>
        </ul>
        <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>

        <button type="submit" class="btn">Place Order</button>
      </form>
    <?php endif; ?>
  </div>

  </body>
  </html>
