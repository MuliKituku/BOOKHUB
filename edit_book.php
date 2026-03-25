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
            echo "Invalid book ID.";
            exit();
        }

        // Fetch book details
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();

        if (!$book) {
            echo "Book not found.";
            exit();
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            $update = $pdo->prepare("UPDATE books SET title = ?, author = ?, price = ?, category = ?, description = ? WHERE id = ?");
            $update->execute([$title, $author, $price, $category, $description, $id]);

            header("Location: manage_books.php?success=Book updated successfully");
            exit();
        }
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Book</title>
            <link rel="stylesheet" href="admin.css">
        </head>
        <body>
            <h2>Edit Book</h2>
            <a href="manage_books.php">⬅ Back to Manage Books</a>

            <form method="POST">
                <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                <input type="text" name="price" value="<?= htmlspecialchars($book['price']) ?>" required>
                <input type="text" name="category" value="<?= htmlspecialchars($book['category']) ?>" required>
                <textarea name="description" required><?= htmlspecialchars($book['description']) ?></textarea>
                <button type="submit">Update Book</button>
            </form>
        </body>
        </html>
