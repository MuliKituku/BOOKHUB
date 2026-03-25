    <?php
    // edit_user.php
    session_start();
    require 'admin_auth.php'; // Only allow logged-in admins

    require_once 'db.php';

    // Get user ID from query
    $id = $_GET['id'] ?? null;
    if (!$id || !is_numeric($id)) {
        header("Location: manage_users.php?error=Invalid user ID");
        exit();
    }

    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: manage_users.php?error=User not found");
        exit();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $is_verified = isset($_POST['is_verified']) ? 1 : 0;

        if (empty($name) || empty($email)) {
            $error = "Name and email are required.";
        } else {
            $update = $pdo->prepare("UPDATE users SET name = ?, email = ?, is_verified = ? WHERE id = ?");
            $update->execute([$name, $email, $is_verified, $id]);

            header("Location: manage_users.php?success=User updated successfully");
            exit();
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit User</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>
        <h2>Edit User</h2>
        <a href="manage_users.php">⬅ Back to Manage Users</a>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

            <label>
                <input type="checkbox" name="is_verified" <?= $user['is_verified'] ? 'checked' : '' ?>>
                Verified
            </label><br><br>

            <button type="submit">Update User</button>
        </form>
    </body>
    </html>
