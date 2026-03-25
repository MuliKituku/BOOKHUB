    <?php
    // delete_user.php
    session_start();
    require 'admin_auth.php'; // Ensure only logged-in admins can access

    require_once 'db.php';

    // Get user ID from query parameter
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        header("Location: manage_users.php?error=Invalid user ID");
        exit();
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: manage_users.php?error=User not found");
        exit();
    }

    // Prevent admin from deleting their own account
    if ($_SESSION['admin_id'] == $id) {
        header("Location: manage_users.php?error=You cannot delete your own account");
        exit();
    }

    // Delete user
    $delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $delete->execute([$id]);

    header("Location: manage_users.php?success=User deleted successfully");
    exit();
