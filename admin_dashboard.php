    <?php
    session_start();

    // Ensure admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Admin Dashboard - eBookStore</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="dashboard-container">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></h1>
            <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['admin_email']) ?></strong></p>

            <hr>

            <h2>Admin Panel</h2>
            <ul>
                <li><a href="manage_users.php"><img src="assets/user.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                        Manage Users</a></li>
                <li><a href="manage_books.php"><img src="assets/book.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                        Manage Books</a></li>
                <li><a href="view_reports.php"><img src="assets/report.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                        View Reports</a></li>
                <li><a href="admin_logout.php">
                        <img src="assets/logout.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                        Logout
                    </a></li>
            </ul>
        </div>
    </body>
    </html>
