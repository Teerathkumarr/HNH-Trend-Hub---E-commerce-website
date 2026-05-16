<?php
session_start();
include 'includes/config.php';

// If cart is empty, redirect
if (empty($_SESSION['cart'])) {
  header("Location: cart.php");
  exit();
}

// Handle order submission
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];
  $total = 0;

  $ids = implode(',', array_keys($_SESSION['cart']));
  $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

  $order_items = [];
  while ($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['id']];
    $subtotal = $qty * $row['price'];
    $total += $subtotal;
    $order_items[] = [
      'product_name' => $row['name'],
      'price' => $row['price'],
      'qty' => $qty
    ];
  }

  // Save order
  $stmt = $conn->prepare("INSERT INTO orders (customer_name, address, phone, total) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssd", $name, $address, $phone, $total);
  $stmt->execute();
  $order_id = $conn->insert_id;

  // Save order items
  $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
  foreach ($order_items as $item) {
    $stmt_item->bind_param("isdi", $order_id, $item['product_name'], $item['price'], $item['qty']);
    $stmt_item->execute();
  }

  $_SESSION['cart'] = []; // Clear cart
  $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - HNH Trend Hub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    .btn-warning { font-weight: 500; }
    footer { background: #111; color: #ccc; padding: 30px 0; text-align: center; }
    label { font-weight: 500; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand text-warning" href="index.php">HNH Trend Hub</a>
    <a href="cart.php" class="btn btn-outline-warning">Back to Cart</a>
  </div>
</nav>

<!-- Checkout Form -->
<div class="container py-5">
  <h2 class="text-center mb-4">Checkout</h2>

  <?php if ($success): ?>
    <div class="alert alert-success text-center">
      Thank you! Your order has been placed successfully.
    </div>
  <?php else: ?>
    <form method="POST" class="row g-3 bg-white p-4 shadow-sm rounded">
      <div class="col-md-6">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" id="name" required class="form-control">
      </div>
      <div class="col-md-6">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" name="phone" id="phone" required class="form-control">
      </div>
      <div class="col-12">
        <label for="address" class="form-label">Shipping Address</label>
        <textarea name="address" id="address" required class="form-control" rows="3"></textarea>
      </div>
      <div class="col-12 text-center">
        <button type="submit" class="btn btn-warning btn-lg px-5">Place Order (COD)</button>
      </div>
    </form>
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
