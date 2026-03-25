    <?php
    session_start();
    require 'admin_auth.php';

    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }

    require_once 'db.php';

    $id = $_GET['id'] ?? null;
    if (!$id) {
        header("Location: manage_books.php?error=Invalid book ID");
        exit();
    }

    // Delete book file if exists
    $stmt = $pdo->prepare("SELECT file_path FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();

    if ($book && file_exists($book['file_path'])) {
        unlink($book['file_path']); // delete PDF file
    }

    // Delete from database
    $delete = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $delete->execute([$id]);

    header("Location: manage_books.php?success=Book deleted successfully");
    exit();
