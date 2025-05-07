<?php

include '../includes/db.php'; // Ensure this connects to your DB
include '../includes/auth_check.php';

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = isset($_POST['product_quantity']) ? max(1, intval($_POST['product_quantity'])) : 1;

    // Check if product already exists in cart
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing_item = $stmt->fetch();

    if ($existing_item) {
        // Update quantity if product exists
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $user_id, $product_id]);
    } else {
        // Add new item if product doesn't exist
        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
    
    header('Location: cart.php');
    exit();
}

// Handle Quantity Adjustments
if (isset($_GET['action'], $_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    if ($_GET['action'] === 'increase') {
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } elseif ($_GET['action'] === 'decrease') {
        // First check current quantity
        $stmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $item = $stmt->fetch();
        
        if ($item && $item['quantity'] > 1) {
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        } else {
            // Remove item if quantity would become 0
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        }
    }
    header('Location: cart.php');
    exit();
}

// Handle Clear Cart
if (isset($_GET['clear'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$user_id]);
    header('Location: cart.php');
    exit();
}

// Get cart items with product details
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image 
    FROM cart_items c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
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
        <p class="text-muted">Note: Minimum of ₱50 to proceed checkout.</p>
        <?php if (empty($cart_items)): ?>
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
                    foreach ($cart_items as $item): ?>
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
                                <a href="cart.php?action=increase&id=<?= $item['product_id'] ?>" class="btn btn-success btn-sm"><i
                                        class="bi bi-plus"></i></a>
                                <a href="cart.php?action=decrease&id=<?= $item['product_id'] ?>" class="btn btn-warning btn-sm"><i
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