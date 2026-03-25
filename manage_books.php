    <?php
    // manage_books.php
    session_start();
    require 'admin_auth.php';

    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }

    require_once 'db.php';
    $books = $pdo->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Manage Books - Admin</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>
        <h2>Manage Books</h2>
        <a href="admin_dashboard.php">⬅ Back to Dashboard</a>

        <!-- Upload Book Form -->
        <h3>Upload New Book</h3>
        <form action="upload_book.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" required placeholder="Book Title">
            <input type="text" name="author" required placeholder="Author">
            <input type="text" name="price" required placeholder="Price">
            <input type="text" name="category" required placeholder="Category">
            <input type="text" name="description" required placeholder="Description">
            <input type="file" name="book_file" required accept=".pdf">
            <button type="submit">Upload Book</button>
        </form>

        <!-- Existing Books Table -->
        <h3>Existing Books</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Price</th>
                <th>Category</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars( "$" . number_format($book['price'], 2))?></td>
                <td><?= htmlspecialchars($book['category']) ?></td>
                <td><?= htmlspecialchars($book['description']) ?></td>
                <td>
                    <a href="edit_book.php?id=<?= $book['id'] ?>">Edit</a> |
                    <a href="delete_book.php?id=<?= $book['id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
    </html>
