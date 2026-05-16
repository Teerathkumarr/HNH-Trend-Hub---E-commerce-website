<?php
// === ADMIN DASHBOARD ===
// File: admin/dashboard.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

include '../includes/config.php';

$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard - HNH Products</h2>
    <a href="add_product.php" class="btn btn-success mb-3">Add Product</a>
    <a href="logout.php" class="btn btn-secondary mb-3">Logout</a>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="../images/<?= $row['image'] ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                         <div class="card-body">
                            <h5 class="card-title"><?= $row['name'] ?></h5>
                                 <p class="card-text">₨ <?= $row['price'] ?></p>
                             <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mb-1">Edit</a>
                         <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
