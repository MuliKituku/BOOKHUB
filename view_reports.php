    <?php
    // view_reports.php
    session_start();
    require 'admin_auth.php';

    require_once 'db.php';

    // Get summary counts
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $bookCount = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();

    // Get all users and books
    $allUsers = $pdo->query("SELECT id, name, email, is_verified FROM users")->fetchAll(PDO::FETCH_ASSOC);
    $allBooks = $pdo->query("SELECT id, title, author, price, category, description, created_at FROM books")->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Reports - Admin</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>

        <h2><img src="assets/report.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                        Reports Overview</h2>

        <div class="top-links">
            <a href="generate_report.php" target="_blank"><img src="assets/download.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;"> Download PDF Report</a> |
            <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
        </div>

        <!-- Summary Table -->
        <h3 class="section-title">Summary</h3>
        <table>
            <tr>
                <th>Total Users</th>
                <th>Total Books</th>
            </tr>
            <tr>
                <td><?= $userCount ?></td>
                <td><?= $bookCount ?></td>
            </tr>
        </table>

        <!-- Users Table -->
        <h3 class="section-title">Registered Users</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Verified</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_verified'] ? 'Yes' : 'No' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Books Table -->
        <h3 class="section-title">Uploaded Books</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Uploaded On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allBooks as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['id']) ?></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars( "$" . number_format($book['price'], 2))?></td>
                    <td><?= htmlspecialchars($book['category']) ?></td>
                    <td><?= htmlspecialchars($book['description']) ?></td>
                    <td><?= htmlspecialchars($book['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </body>
    </html>
