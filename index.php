<!DOCTYPE html>
<html lang="en">
<head>
  <title>Zentra – Mental Health Platform</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff1eb, #ace0f9);
    }
    .hero {
      text-align: center;
      padding: 50px 20px;
      background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
      color: white;
    }
    .login-card {
      transition: transform 0.3s ease;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .login-card:hover {
      transform: scale(1.03);
    }
    .carousel-item img {
      border-radius: 20px;
      height: 400px;
      object-fit: cover;
    }
  </style>
</head>
<body>

<!-- HERO SECTION -->
<section class="hero">
  <h1 class="display-5 fw-bold"><i class="bi bi-heart-pulse-fill"></i> Zentra</h1>
  <p class="lead">Your companion for mental wellness and emotional well-being</p>
</section>

<!-- SLIDER -->
<div class="container my-5">
  <div id="zenSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner rounded">
      <div class="carousel-item active">
      <img src="images/slider1.jpeg" class="d-block w-100" alt="Slide 1" />      </div>
      <div class="carousel-item">
      <img src="images/slider2.jpeg" class="d-block w-100" alt="Slide 2" />      </div>
      <div class="carousel-item">
      <img src="images/slider3.jpg" class="d-block w-100" alt="Slide 3" />      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#zenSlider" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#zenSlider" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

<!-- LOGIN OPTIONS -->
<div class="container my-5">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card login-card p-4 text-center">
        <h4><i class="bi bi-person-circle text-primary fs-1"></i><br>User Login</h4>
        <p class="text-muted">Access your personalized wellness dashboard</p>
        <a href="auth.php" class="btn btn-primary mt-2"><i class="bi bi-box-arrow-in-right"></i> Login / Register</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card login-card p-4 text-center">
        <h4><i class="bi bi-shield-lock-fill text-dark fs-1"></i><br>Admin Login</h4>
        <p class="text-muted">Manage users, insights, and platform resources</p>
        <a href="admin_login.php" class="btn btn-dark mt-2"><i class="bi bi-box-arrow-in-right"></i> Admin Login</a>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center py-4 text-muted bg-light">
  &copy; <?= date("Y") ?> Zentra – Built for peace of mind 💚
</footer>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
