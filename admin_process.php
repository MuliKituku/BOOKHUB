<?php
    session_start();

    require_once 'db.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email']);
        $passwordInput = $_POST['password'];

        if (empty($email) || empty($passwordInput)) {
            header("Location: admin_login.php?error=Please enter both email and password");
            exit();
        }

        try {
            // Look up admin account
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($passwordInput, $admin['password'])) {
                // Successful login
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['name'];
                header("Location: admin_dashboard.php"); // Change this to your admin page
                exit();
            } else {
                header("Location: admin_login.php?error=Invalid email or password");
                exit();
            }

        } catch (PDOException $e) {
            header("Location: admin_login.php?error=Database error: " . $e->getMessage());
            exit();
        }
    } else {
        header("Location: admin_login.php");
        exit();
    }
