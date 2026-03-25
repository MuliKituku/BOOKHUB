    <?php
    require_once 'db.php';

    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $fileName = $_FILES['book_file']['name'];
    $fileTmp = $_FILES['book_file']['tmp_name'];
    $destination = 'uploads/' . basename($fileName);

    // Move the uploaded file
    if (move_uploaded_file($fileTmp, $destination)) {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO books (title, author, description, file_path, price, category) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $description, $destination, $price, $category]);

        // Redirect to manage_books.php
        header("Location: manage_books.php");
        exit();
    } else {
        echo "❌ Upload failed.";
    }
    ?>
