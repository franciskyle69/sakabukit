<?php
session_start();
include '../includes/db.php';
include '../includes/auth_check.php';

$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">🧾 My Purchases</h2>
        <p class="text-muted">Here are your recent purchases.</p>


        <?php if ($result->num_rows > 0): ?>
            <table id="ordersTable" class="table table-bordered table-hover">
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

    <!-- DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#ordersTable').DataTable({
                "order": [[1, "desc"]] // Order by Order Date descending
            });
        });
    </script>
</body>

</html>