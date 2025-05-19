<?php
include '../includes/db.php';
include '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports</title>
  <!-- External Stylesheets (refactored for consistency) -->
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../styles/styles.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/all.min.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/owl.carousel.css">
  <link rel="stylesheet" href="../assets/css/magnific-popup.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
  <link rel="stylesheet" href="../assets/css/meanmenu.min.css">
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/responsive.css">
  <link rel="icon" type="image/png" href="../assets/images/logo.png">
  <!-- DataTables CSS & JS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <style>
    html {
      font-size: 16px;
    }
    body {
      font-family: 'Open Sans', 'Poppins', Arial, sans-serif;
      font-size: 1rem;
    }
  </style>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables Initialization -->
  <script>
    $(document).ready(function () {
      $('#ordersTable').DataTable();
      $('#orderItemsTable').DataTable();
      $('#usersTable').DataTable();
    });
  </script>
</head>

<body>
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <h1 class="text-center mb-4 animate__animated animate__fadeInDown">Reports</h1>

    <form method="POST" action="../admin/simple_print.php" target="_blank" class="text-center mt-5">
      <button type="submit" class="btn btn-primary btn-lg px-5 animate__animated animate__pulse animate__delay-1s">
        Print Products
      </button>
    </form>

    <!-- Orders Summary -->
    <h3 class="mt-5 animate__animated animate__fadeInLeft">Orders</h3>
    <table id="ordersTable" class="table table-bordered display animated-table">
      <thead class="table-dark">
        <tr>
          <th>Order ID</th>
          <th>Order Date</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
        while ($order = $orders->fetch()) {
          echo '<tr class="table-row-transition">
            <td>' . $order['id'] . '</td>
            <td>' . $order['order_date'] . '</td>
            <td>₱' . number_format($order['total_amount'], 2) . '</td>
          </tr>';
        }
        ?>
      </tbody>
    </table>

    <!-- Order Items Detail -->
    <h3 class="mt-5 animate__animated animate__fadeInLeft">Order Items</h3>
    <table id="orderItemsTable" class="table table-bordered display animated-table">
      <thead class="table-light">
        <tr>
          <th>Order ID</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Price Each</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $items = $pdo->query("SELECT * FROM order_items ORDER BY id DESC");
        while ($item = $items->fetch()) {
          echo '<tr class="table-row-transition">
            <td>' . $item['order_id'] . '</td>
            <td>' . htmlspecialchars($item['product_name']) . '</td>
            <td>' . $item['quantity'] . '</td>
            <td>₱' . number_format($item['price'], 2) . '</td>
          </tr>';
        }
        ?>
      </tbody>
    </table>

    <!-- Users List -->
    <h3 class="mt-5 animate__animated animate__fadeInLeft">Registered Users</h3>
    <table id="usersTable" class="table table-bordered display animated-table">
      <thead class="table-primary">
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created On</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $users = $pdo->query("SELECT * FROM users ORDER BY id DESC");
        while ($user = $users->fetch()) {
          echo '<tr class="table-row-transition">
            <td>' . $user['id'] . '</td>
            <td>' . htmlspecialchars($user['username']) . '</td>
            <td>' . htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']) . '</td>
            <td>' . htmlspecialchars($user['email']) . '</td>
            <td>' . htmlspecialchars($user['role']) . '</td>
            <td>' . date('F j, Y g:i A', strtotime($user['created_at'])) . '</td>
          </tr>';
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Animate.css CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    .animated-table {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.4,0,0.2,1);
    }
    .animated-table.visible {
      opacity: 1;
      transform: translateY(0);
    }
    .table-row-transition {
      transition: background-color 0.4s, color 0.4s;
    }
    .table-row-transition:hover {
      background-color: #f0f8ff !important;
      color: #007bff;
    }
  </style>
  <script>
    // Reveal tables with transition on scroll
    $(document).ready(function () {
      function revealTables() {
        $('.animated-table').each(function () {
          var tableTop = $(this).offset().top;
          var windowBottom = $(window).scrollTop() + $(window).height();
          if (windowBottom > tableTop + 50) {
            $(this).addClass('visible');
          }
        });
      }
      revealTables();
      $(window).on('scroll', revealTables);
    });
  </script>
</body>

</html>