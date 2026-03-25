    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register - eBookStore</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="register-container">
            <h2>Create an Account</h2>
            <?php
            if (isset($_GET['error'])) {
                echo "<p style='color:red;'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
            if (isset($_GET['success'])) {
                echo "<p style='color:green;'>Account created! <a href='login.php'>Login now</a>.</p>";
            }
            ?>
            <form action="register_process.php" method="POST">
                <label for="name">Full Name:</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br><br>

                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </body>
    </html>
