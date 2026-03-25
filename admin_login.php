    <!-- admin_login.php -->
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Admin Login - eBookStore</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="login-container">
            <h2>Admin Login</h2>
            <?php
            if (isset($_GET['error'])) {
                echo "<p style='color:red;'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
            ?>
            <form method="POST" action="admin_process.php">
                <label for="email">Admin Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
