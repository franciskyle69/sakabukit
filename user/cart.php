<?php
session_start();
include '../includes/db.php'; // Ensure this connects to your DB

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = floatval($_POST['product_price']);
    $quantity = isset($_POST['product_quantity']) ? max(1, intval($_POST['product_quantity'])) : 1;
    $image = isset($_POST['product_image']) ? $_POST['product_image'] : 'placeholder.png';

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image
        ];
    }
    header('Location: cart.php');
    exit();
}

// Handle Quantity Adjustments
if (isset($_GET['action'], $_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action'] === 'increase') {
        $_SESSION['cart'][$id]['quantity']++;
    } elseif ($_GET['action'] === 'decrease' && $_SESSION['cart'][$id]['quantity'] > 1) {
        $_SESSION['cart'][$id]['quantity']--;
    } elseif ($_GET['action'] === 'decrease') {
        unset($_SESSION['cart'][$id]);
    }
    header('Location: cart.php');
    exit();
}

// Handle Clear Cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit();
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .cart-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">🛒 Your Shopping Cart</h2>
        <?php if (empty($cart)): ?>
            <div class="alert alert-info">Your cart is empty.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0;
                    foreach ($cart as $id => $item): ?>
                        <?php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        $imageName = !empty($item['image']) ? basename($item['image']) : 'placeholder.png';
                        $imagePath = '../assets/images/' . $imageName;
                        ?>
                        <tr>
                            <td>
                                <?php if (file_exists($imagePath)): ?>
                                    <img src="<?= htmlspecialchars($imagePath) ?>" class="cart-img rounded" alt="Product Image">
                                <?php else: ?>
                                    <img src="../assets/images/placeholder.png" class="cart-img rounded" alt="No Image">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <a href="cart.php?action=increase&id=<?= $id ?>" class="btn btn-success btn-sm"><i
                                        class="bi bi-plus"></i></a>
                                <a href="cart.php?action=decrease&id=<?= $id ?>" class="btn btn-warning btn-sm"><i
                                        class="bi bi-dash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-secondary">
                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                        <td colspan="2"><strong>₱<?= number_format($total, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <a href="cart.php?clear=1" class="btn btn-danger me-2">🗑 Clear Cart</a>
            <form action="checkout.php" method="POST" class="d-inline">
                <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
            </form>

        <?php endif; ?>
        <a href="products.php" class="btn btn-secondary mt-3">
            <img src="../assets/images/products.png" alt="Back to Products" style="width: 24px; height: 24px;"> Back to
            Products
        </a>
    </div>
</body>

</html>