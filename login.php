    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login - eBookStore</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="login-container">
            <h2>Login to eBookStore</h2>
            <?php
            if (isset($_GET['error'])) {
                echo "<p style='color:red;'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
            ?>
            <form method="POST" action="login_process.php">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit">Login</button>
            </form>
            <p><a href="forgot_password.php">Forgot Password</a> <br>Don't have an account? <a href="register.php">Register here </a></p>
        </div>
    </body>
    </html>
