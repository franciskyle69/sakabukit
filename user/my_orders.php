<?php
session_start();
include '../includes/db.php'; // Your database connection

// Fetch all orders
$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">🧾 My Purchases</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>View Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                            <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <a href="order_details.php?order_id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No purchases yet.</div>
        <?php endif; ?>

        <a href="products.php" class="btn btn-secondary mt-3">← Back to Products</a>
    </div>
</body>

</html>