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
    <h1 class="text-center mb-4">Reports</h1>

    <form method="POST" action="../admin/simple_print.php" target="_blank" class="text-center mt-5">
      <button type="submit" class="btn btn-primary btn-lg px-5">
        Print Products
      </button>
    </form>

    <!-- Orders Summary -->
    <h3 class="mt-5">Orders</h3>
    <table id="ordersTable" class="table table-bordered display">
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
          echo '<tr>
            <td>' . $order['id'] . '</td>
            <td>' . $order['order_date'] . '</td>
            <td>₱' . number_format($order['total_amount'], 2) . '</td>
          </tr>';
        }
        ?>
      </tbody>
    </table>

    <!-- Order Items Detail -->
    <h3 class="mt-5">Order Items</h3>
    <table id="orderItemsTable" class="table table-bordered display">
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
          echo '<tr>
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
    <h3 class="mt-5">Registered Users</h3>
    <table id="usersTable" class="table table-bordered display">
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
          echo '<tr>
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
</body>

</html>