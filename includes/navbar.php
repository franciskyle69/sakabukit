<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'db.php';

$role = $_SESSION['role'] ?? 'guest';
?>

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    nav.navbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background-color: #051922;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg navbar-dark px-4 py-2 shadow-sm">
  <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="#">
    <img src="../assets/img/logos.png" alt="This is the logo"
      style="width: 60px; height: 60px; display: block; margin: 0 auto;">
    SAKA BUKIT
  </a>

  <?php if ($role !== 'guest'): ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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

        <i class="bi bi-person-circle me-3" style="font-size: 1.6rem;"></i>

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