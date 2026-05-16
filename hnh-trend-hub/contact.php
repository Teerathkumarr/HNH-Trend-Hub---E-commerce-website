<!DOCTYPE html>
<html>
<head>
  <title>Contact Us - HNH Trend Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="index.php">HNH Trend Hub</a>
  </div>
</nav>

<div class="container mt-5">
  <h2>Contact Us</h2>
  <form method="POST">
    <input type="text" name="name" class="form-control mb-3" placeholder="Your Name" required>
    <input type="email" name="email" class="form-control mb-3" placeholder="Your Email" required>
    <textarea name="message" class="form-control mb-3" placeholder="Your Message" required></textarea>
    <button type="submit" class="btn btn-warning">Send Message</button>
  </form>
</div>

<footer class="bg-dark text-light text-center py-4 mt-5">
  &copy; <?= date('Y') ?> HNH Trend Hub — All rights reserved
</footer>
</body>
</html>
