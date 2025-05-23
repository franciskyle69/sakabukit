<?php
require_once __DIR__ . '/auth_check.php';
$profilePhoto = isset($_SESSION['profile_photo']) && $_SESSION['profile_photo'] ? $_SESSION['profile_photo'] : '../assets/img/profile-placeholder.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- fontawesome -->
    <link rel="stylesheet" href="assets/css/all.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!-- owl carousel -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <!-- magnific popup -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <!-- animate css -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- mean menu css -->
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">
    <!-- main style -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- responsive -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-font: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            --heading-font: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            font-family: var(--primary-font);
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: var(--heading-font);
        }

        nav.navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background-color: #051922;
            font-family: var(--primary-font);
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #F28123 !important;
        }

        .navbar-brand img {
            width: 150px;
            height: 50px;
            display: block;
            margin-left: 200px;
        }

        @media (max-width: 991.98px) {
            .navbar-brand img {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-4 py-2 shadow-sm">
  <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="index.php">
    <img src="../assets/img/SAKA_BUK_it-removebg-preview.png" alt="SAKA BUKIT Logo">
    <!-- SAKA BUKIT -->
  </a>

  <?php if ($role !== 'guest'): ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
        <li class="nav-item">
          <a class="nav-link text-white"
            href="<?= $role === 'admin' ? '../admin/index.php' : '../user/index.php' ?>">Home</a>
        </li>

        <?php if ($role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link text-white" href="../admin/products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../admin/accounts.php">Accounts</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../admin/settings.php">Settings</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../admin/reports.php">Reports</a></li>

        <?php elseif ($role === 'user'): ?>
          <li class="nav-item"><a class="nav-link text-white" href="../user/bookings.php">Bookings</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../user/my_bookings.php">My Bookings</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../user/products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../user/settings.php">Settings</a></li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center text-white">
        <?php if ($role === 'user'): ?>
          <a href="../user/cart.php" class="me-3 text-white text-decoration-none">
            <i class="bi bi-cart" style="font-size: 1.4rem;"></i>
          </a>
          <a href="../user/my_orders.php" class="me-3 text-white text-decoration-none">
            <i class="bi bi-bag-check" style="font-size: 1.4rem;"></i> My Purchases
          </a>
        <?php endif; ?>

        <?php if (isset($_SESSION['full_name'])): ?>
          <span class="me-2 fw-semibold"><?= htmlspecialchars($_SESSION['full_name']); ?></span>
        <?php endif; ?>

        <?php if (isset($_SESSION['profile_photo']) && $_SESSION['profile_photo']): ?>
          <img src="<?= htmlspecialchars($_SESSION['profile_photo']) ?>" alt="Profile" class="rounded-circle me-3" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #fff;">
        <?php else: ?>
          <img src="../assets/images/default-profile.png" alt="Profile" class="rounded-circle me-3" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #fff;">
        <?php endif; ?>

        <a href="#" class="btn btn-outline-light btn-sm" onclick="confirmLogout(event)">Logout</a>
        <script>
          function confirmLogout(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to log out?")) {
              window.location.href = "../logout.php";
            }
          }
        </script>
      </div>
    </div>

  <?php else: ?>
    <div class="ms-auto">
      <a href="../login.php" class="btn btn-outline-light btn-sm">Login</a>
    </div>
  <?php endif; ?>
</nav>
</body>
</html>