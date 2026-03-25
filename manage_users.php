    <?php
    // manage_users.php
    session_start();
    require 'admin_auth.php'; // Ensure only admins can access

    require_once 'db.php';
    $users = $pdo->query("SELECT id, name, email, is_verified FROM users")->fetchAll(PDO::FETCH_ASSOC);

    // Handle flash messages
    $success = $_GET['success'] ?? '';
    $error = $_GET['error'] ?? '';
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Manage Users - Admin</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>
        <h2>Manage Users</h2>
        <a href="admin_dashboard.php">⬅ Back to Dashboard</a>

        <!-- Display success or error messages -->
        <?php if ($success): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Verified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_verified'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>">✏️ Edit</a> | 
                        <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">🗑️ Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>
