<?php
include 'includes/config.php';

// Initialize variables
$products = [];
$categories = [];

try {
    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            $categories[] = $row['Category'];
        }
        
        $categories = array_unique($categories);
    } else {
        throw new Exception("Database query failed: " . $conn->error);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    // You might want to display a user-friendly error message here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HNH Trend Hub - Premium Fashion & Accessories</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Discover premium watches, perfumes, jewelry and accessories at HNH Trend Hub">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ffc107;
      --secondary-color: #212529;
      --accent-color: #e83e8c;
      --light-bg: #f8f9fa;
      --dark-bg: #212529;
    }
    
    body { 
      font-family: 'Poppins', sans-serif; 
      background-color: var(--light-bg);
      transition: all 0.3s ease;
    }
    
    body.dark-mode {
      background-color: var(--dark-bg);
      color: #f8f9fa;
    }
    
    .dark-mode .card {
      background-color: #343a40;
      color: #f8f9fa;
    }
    
    .dark-mode .card-title,
    .dark-mode .card-text {
      color: #f8f9fa !important;
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
    }
    
    .card { 
      transition: transform 0.3s ease, box-shadow 0.3s ease; 
      border: none;
      overflow: hidden;
    }
    
    .card:hover { 
      transform: translateY(-5px); 
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .btn-warning { 
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    footer { 
      background: var(--secondary-color); 
      color: #ccc; 
      padding: 40px 0; 
    }
    
    .top-controls { 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
    }
    
    #searchInput { 
      max-width: 400px; 
      border-radius: 50px;
      padding: 10px 20px;
    }
    
    .carousel-caption {
      background: rgba(0,0,0,0.5);
      border-radius: 10px;
      padding: 20px;
      bottom: 30%;
    }
    
    .category-card {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
    }
    
    .category-card img {
      transition: transform 0.5s ease;
    }
    
    .category-card:hover img {
      transform: scale(1.1);
    }
    
    .category-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0,0,0,0.7));
      padding: 20px;
      color: white;
    }
    
    .product-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: var(--accent-color);
      color: white;
      padding: 5px 10px;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
    }
    
    .social-icons a {
      color: #ccc;
      margin: 0 10px;
      font-size: 1.2rem;
      transition: color 0.3s ease;
    }
    
    .social-icons a:hover {
      color: var(--primary-color);
    }
    
    @media (max-width: 768px) {
      .top-controls {
        flex-direction: column;
        gap: 10px;
      }
      
      #searchInput {
        max-width: 100%;
      }
      
      .carousel-caption {
        bottom: 20%;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand text-warning" href="#">
      <i class="bi bi-gem me-2"></i>HNH Trend Hub
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#products">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#categories">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        <input type="text" id="searchInput" class="form-control me-3" placeholder="Search products...">
        <a href="cart.php" class="btn btn-outline-warning me-2 position-relative">
          <i class="bi bi-cart3"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            0
          </span>
        </a>
        <button id="toggleBtn" class="btn btn-sm btn-outline-light">
          <i class="bi bi-moon-fill"></i> Dark Mode
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/banner1.jpg" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Luxury Watches">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Discover Timeless Luxury</h1>
        <p class="lead">Premium watches crafted with precision</p>
        <a href="#products" class="btn btn-warning btn-lg mt-3 px-4">Shop Now</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="images/banner2.jpg" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Elegant Perfumes">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Elegance Redefined</h1>
        <p class="lead">Signature fragrances for every occasion</p>
        <a href="#products" class="btn btn-warning btn-lg mt-3 px-4">Browse Collection</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="images/banner3.jpg" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Stylish Accessories">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Complete Your Look</h1>
        <p class="lead">Exclusive jewelry and accessories</p>
        <a href="#products" class="btn btn-warning btn-lg mt-3 px-4">View Accessories</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Featured Categories -->
<section class="container py-5" id="categories">
  <div class="text-center mb-5">
    <h2 class="display-5 fw-bold">Shop by Category</h2>
    <p class="lead text-muted">Browse our premium collections</p>
  </div>
  <div class="row g-4">
    <div class="col-md-3 col-6">
      <a href="#products" class="text-decoration-none category-card">
        <div class="card shadow-sm h-100 border-0">
          <img src="images/category-watches.jpg" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Watches">
          <div class="category-overlay">
            <h5 class="card-title mb-0 fw-bold">Watches</h5>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 col-6">
      <a href="#products" class="text-decoration-none category-card">
        <div class="card shadow-sm h-100 border-0">
          <img src="images/category-perfumes.jpg" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Perfumes">
          <div class="category-overlay">
            <h5 class="card-title mb-0 fw-bold">Perfumes</h5>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 col-6">
      <a href="#products" class="text-decoration-none category-card">
        <div class="card shadow-sm h-100 border-0">
          <img src="images/category-accessories.jpg" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Accessories">
          <div class="category-overlay">
            <h5 class="card-title mb-0 fw-bold">Accessories</h5>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 col-6">
      <a href="#products" class="text-decoration-none category-card">
        <div class="card shadow-sm h-100 border-0">
          <img src="images/category-jewellery.jpg" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Jewelry">
          <div class="category-overlay">
            <h5 class="card-title mb-0 fw-bold">Jewelry</h5>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold">Featured Products</h2>
      <p class="lead text-muted">Our most popular items this season</p>
    </div>
    
    <!-- Category Filter -->
    <div class="row mb-4 justify-content-center">
      <div class="col-md-6 col-lg-4">
        <select id="categoryFilter" class="form-select shadow-sm">
          <option value="All">All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="row g-4" id="productGrid">
      <?php foreach ($products as $row): ?>
        <div class="col-lg-3 col-md-4 col-6 product-item" data-category="<?= $row['Category'] ?>">
          <div class="card h-100 shadow-sm position-relative">
            <?php if($row['price'] > 50000): ?>
              <span class="product-badge">Premium</span>
            <?php endif; ?>
            <img src="images/<?= $row['image'] ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= $row['name'] ?>">
            <div class="card-body text-center">
              <h5 class="card-title"><?= $row['name'] ?></h5>
              <p class="card-text text-success fw-bold">₨ <?= number_format($row['price'], 2) ?></p>
              <div class="d-grid gap-2">
                <a href="product.php?id=<?= $row['id'] ?>" class="btn btn-outline-dark">View Details</a>
                <a href="add_to_cart.php?id=<?= $row['id'] ?>" class="btn btn-warning">
                  <i class="bi bi-cart-plus"></i> Add to Cart
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-5">
      <a href="#products" class="btn btn-outline-dark btn-lg px-4">View All Products</a>
    </div>
  </div>
</section>

<!-- Newsletter -->
<section class="py-5 bg-warning text-dark">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 text-center">
        <h2 class="fw-bold mb-3">Stay Updated</h2>
        <p class="lead mb-4">Subscribe to our newsletter for exclusive offers and new arrivals</p>
        <form class="row g-2 justify-content-center">
          <div class="col-md-8">
            <input type="email" class="form-control form-control-lg" placeholder="Your email address">
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-dark btn-lg w-100">Subscribe</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4">
        <h5 class="text-warning mb-3">HNH Trend Hub</h5>
        <p>Premium fashion and accessories for the discerning customer. Quality products with timeless designs.</p>
        <div class="social-icons mt-3">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-twitter"></i></a>
          <a href="#"><i class="bi bi-pinterest"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <h5 class="text-light mb-3">Shop</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-secondary">Watches</a></li>
          <li><a href="#" class="text-secondary">Perfumes</a></li>
          <li><a href="#" class="text-secondary">Jewelry</a></li>
          <li><a href="#" class="text-secondary">Accessories</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <h5 class="text-light mb-3">Company</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-secondary">About Us</a></li>
          <li><a href="#" class="text-secondary">Blog</a></li>
          <li><a href="#" class="text-secondary">Careers</a></li>
          <li><a href="#" class="text-secondary">Contact</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-4 mb-4">
        <h5 class="text-light mb-3">Contact Info</h5>
        <ul class="list-unstyled text-secondary">
          <li><i class="bi bi-geo-alt me-2"></i> 123 Fashion Street, Karachi</li>
          <li><i class="bi bi-telephone me-2"></i> +92 300 1234567</li>
          <li><i class="bi bi-envelope me-2"></i> info@hnhtrendhub.com</li>
        </ul>
      </div>
    </div>
    <hr class="my-4 bg-secondary">
    <div class="row">
      <div class="col-md-6 text-center text-md-start">
        <p class="mb-0">&copy; <?= date('Y') ?> HNH Trend Hub. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-center text-md-end">
        <p class="mb-0">
          <a href="#" class="text-secondary">Privacy Policy</a> | 
          <a href="#" class="text-secondary">Terms of Service</a>
        </p>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts -->
<script>
  // 🔍 Live search
  document.getElementById('searchInput').addEventListener('input', function() {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(function(item) {
      const title = item.querySelector('.card-title').textContent.toLowerCase();
      const category = item.getAttribute('data-category').toLowerCase();
      item.style.display = (title.includes(value) || category.includes(value)) ? '' : 'none';
    });
  });

  // 🔔 Toast on Add to Cart
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-warning') && !e.target.closest('.btn-warning').classList.contains('disabled')) {
      const btn = e.target.closest('.btn-warning');
      btn.classList.add('disabled');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
      
      // Simulate API call
      setTimeout(() => {
        showToast("Item added to cart!");
        updateCartCount();
        btn.classList.remove('disabled');
        btn.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart';
      }, 1000);
    }
  });

  function showToast(message) {
    // Remove existing toasts
    document.querySelectorAll('.toast').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = 'toast show align-items-center text-white bg-success border-0';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-check-circle me-2"></i> ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>`;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
  }

  function updateCartCount() {
    const badge = document.querySelector('.cart-badge');
    let count = parseInt(badge.textContent) || 0;
    badge.textContent = count + 1;
    badge.classList.add('animate__animated', 'animate__bounceIn');
    setTimeout(() => badge.classList.remove('animate__animated', 'animate__bounceIn'), 1000);
  }

  // 🌙 Dark/Light Mode
  const toggleBtn = document.getElementById('toggleBtn');
  const body = document.body;
  
  // Check for saved user preference
  if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    toggleBtn.innerHTML = '<i class="bi bi-sun-fill"></i> Light Mode';
  }
  
  toggleBtn.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    
    if (body.classList.contains('dark-mode')) {
      localStorage.setItem('darkMode', 'enabled');
      toggleBtn.innerHTML = '<i class="bi bi-sun-fill"></i> Light Mode';
    } else {
      localStorage.setItem('darkMode', 'disabled');
      toggleBtn.innerHTML = '<i class="bi bi-moon-fill"></i> Dark Mode';
    }
  });

  // 🔄 Category filter
  document.getElementById('categoryFilter').addEventListener('change', function() {
    const selected = this.value;
    document.querySelectorAll('.product-item').forEach(item => {
      const cat = item.getAttribute('data-category');
      item.style.display = (selected === 'All' || cat === selected) ? '' : 'none';
    });
  });

  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
</script>
</body>
</html>