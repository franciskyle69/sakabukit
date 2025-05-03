<?php
include '../includes/auth_check.php';
if (!isset($_GET['order_id'])) {
    header('Location: products.php');
    exit();
}
$orderId = intval($_GET['order_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .success-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            text-align: center;
        }

        .success-icon {
            font-size: 3rem;
            color: #28a745;
        }

        .btn-shop {
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="success-box">
        <div class="success-icon mb-3">✅</div>
        <h2 class="mb-3">Thank you for your purchase!</h2>
        <p class="lead">Your order has been placed successfully.</p>
        <p class="text-muted">Order ID: <strong>#<?= htmlspecialchars($orderId) ?></strong></p>
        <a href="products.php" class="btn btn-primary btn-shop">🛍️ Shop Again</a>
    </div>
</body>

</html>