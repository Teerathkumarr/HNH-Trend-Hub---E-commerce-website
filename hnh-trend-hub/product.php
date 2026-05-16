<?php
include 'includes/config.php';

if (!isset($_GET['id'])) {
  echo "Product not found.";
  exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  echo "Product not found.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $product['name'] ?> - HNH Trend Hub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .card {
      border: none;
    }
    footer {
      background: #111;
      color: #ccc;
      padding: 30px 0;
      text-align: center;
    }
    .btn-warning {
      font-weight: 500;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand text-warning" href="index.php">HNH Trend Hub</a>
    <a href="cart.php" class="btn btn-outline-warning">Cart</a>
  </div>
</nav>

<!-- Product Detail -->
<div class="container py-5">
  <div class="row">
    <div class="col-md-6 mb-4">
      <img src="images/<?= $product['image'] ?>" class="img-fluid rounded shadow" alt="<?= $product['name'] ?>">
    </div>
    <div class="col-md-6">
      <h2><?= $product['name'] ?></h2>
      <h4 class="text-success mb-3">₨ <?= number_format($product['price'], 2) ?></h4>
      <p><?= $product['description'] ?></p>
      <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-lg">Add to Cart</a>
      <a href="index.php" class="btn btn-outline-secondary btn-lg ms-2">Back to Shop</a>
    </div>
  </div>
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
