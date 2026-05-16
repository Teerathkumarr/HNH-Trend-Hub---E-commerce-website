<?php
session_start();
include 'includes/config.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Handle removal
if (isset($_GET['remove'])) {
  $id = $_GET['remove'];
  unset($_SESSION['cart'][$id]);
  header("Location: cart.php");
  exit();
}

// Fetch product details
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
  $ids = implode(',', array_keys($_SESSION['cart']));
  $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

  while ($row = $result->fetch_assoc()) {
    $qty = is_array($_SESSION['cart'][$row['id']]) ? $_SESSION['cart'][$row['id']]['qty'] : $_SESSION['cart'][$row['id']];
    $row['qty'] = (int)$qty;
    $row['subtotal'] = $row['qty'] * (float)$row['price'];

    $total += $row['subtotal'];
    $cartItems[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - HNH Trend Hub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .btn-warning {
      font-weight: 500;
    }
    footer {
      background: #111;
      color: #ccc;
      padding: 30px 0;
      text-align: center;
    }
    table th, table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand text-warning" href="index.php">HNH Trend Hub</a>
    <a href="checkout.php" class="btn btn-outline-warning">Checkout</a>
  </div>
</nav>

<!-- Cart Content -->
<div class="container py-5">
  <h2 class="mb-4 text-center">Your Shopping Cart</h2>

  <?php if (empty($cartItems)): ?>
    <div class="alert alert-info text-center">Your cart is empty.</div>
  <?php else: ?>
    <table class="table table-bordered bg-white shadow-sm">
      <thead class="table-light">
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cartItems as $item): ?>
          <tr>
            <td>
              <img src="images/<?= $item['image'] ?>" width="70" class="me-2">
              <?= $item['name'] ?>
            </td>
            <td><?= $item['qty'] ?></td>
            <td>₨ <?= number_format($item['price'], 2) ?></td>
            <td>₨ <?= number_format($item['subtotal'], 2) ?></td>
            <td>
              <a href="?remove=<?= $item['id'] ?>" class="btn btn-sm btn-danger">Remove</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-end fw-bold">Total:</td>
          <td class="fw-bold text-success">₨ <?= number_format($total, 2) ?></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
    <div class="text-center mt-4">
      <a href="checkout.php" class="btn btn-warning btn-lg">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
</div>

<!-- Footer -->
<footer class="mt-5">
  <div class="container">
    <div class="row text-start">
      <div class="col-md-4">
        <h6 class="text-light">Customer Service</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-decoration-none text-secondary">Contact Us</a></li>
          <li><a href="#" class="text-decoration-none text-secondary">Return Policy</a></li>
          <li><a href="#" class="text-decoration-none text-secondary">Shipping Info</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="text-light">Company</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-decoration-none text-secondary">About Us</a></li>
          <li><a href="#" class="text-decoration-none text-secondary">Careers</a></li>
          <li><a href="#" class="text-decoration-none text-secondary">Press</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="text-light">Stay Updated</h6>
        <p>Subscribe to our newsletter for updates.</p>
        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#newsletterModal">Subscribe</button>
      </div>
    </div>
    <hr class="border-secondary">
    <p class="text-center">&copy; <?= date('Y') ?> HNH Trend Hub — All rights reserved.</p>
  </div>
</footer>

<!-- Newsletter Modal -->
<div class="modal fade" id="newsletterModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Subscribe to Newsletter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning">Subscribe</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
