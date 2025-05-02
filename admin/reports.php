<?php
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="styles/styles.css">
  <link rel="icon" type="image/png" href="../assets/images/logo.png">
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
    <h3>Orders</h3>
    <table class="table table-bordered">
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
    <table class="table table-bordered">
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
    <table class="table table-bordered">
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