<?php
// === ADMIN ADD PRODUCT ===
// File: admin/add_product.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_FILES['image']['name'];

    if (!is_dir('../images')) {
        mkdir('../images');
    }

    $target = "../images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $desc, $image);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Image upload failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">


</head>
<body>
<div class="container mt-5">
    <h2>Add New Product</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" class="form-control mb-3" required>
        <input type="number" name="price" step="0.01" placeholder="Price" class="form-control mb-3" required>
        <textarea name="description" class="form-control mb-3" placeholder="Description"></textarea>
        <input type="file" name="image" class="form-control mb-3" required>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
</body>
</html>

<?php