<!-- favicon -->
<link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="../assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="../assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="../assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="../assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="../assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="../assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">

    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <!-- fontawesome -->
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <!-- owl carousel -->
    <link rel="stylesheet" href="../assets/css/owl.carousel.css">
    <!-- magnific popup -->
    <link rel="stylesheet" href="../assets/css/magnific-popup.css">
    <!-- animate css -->
    <link rel="stylesheet" href="../assets/css/animate.css">
    <!-- mean menu css -->
    <link rel="stylesheet" href="../assets/css/meanmenu.min.css">
    <!-- main style -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- responsive -->
    <link rel="stylesheet" href="../assets/css/responsive.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <style>
        html {
            font-size: 16px;
        }
        body {
            font-family: 'Open Sans', 'Poppins', Arial, sans-serif;
            font-size: 1rem;
        }
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

    <!-- jQuery -->
    <script src="../assets/js/jquery-1.11.3.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mean Menu JS -->
    <script src="../assets/js/jquery.meanmenu.min.js"></script>
    
    <!-- Sticky JS -->
    <script src="../assets/js/sticker.js"></script>
    
    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>
</head>

<body>
    <div style=""> <?php include '../includes/navbar.php'; ?> </div>

    <!-- <div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row" style="height: 10px;">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Fresh and Organic</p>
						<h1>Cart</h1>
					</div>
				</div>
			</div>
		</div>
	</div> -->

    <div class="cart-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="cart-table-wrap">
                        <table class="cart-table">
                            <thead class="cart-table-head">
                                <tr class="table-head-row">
                                    <th class="product-remove"></th>
                                    <th class="product-image">Product Image</th>
                                    <th class="product-name">Name</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php if (empty($cart_items)): ?>
                                    <tr class="table-body-row">
                                        <td colspan="6" class="text-center">Your cart is empty.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cart_items as $item): ?>
                                        <?php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                        $imageName = !empty($item['image']) ? basename($item['image']) : 'placeholder.png';
                                        $imagePath = '../assets/images/' . $imageName;
                                        ?>
                                        <tr class="table-body-row">
                                            <td class="product-remove">
                                                <a href="cart.php?action=decrease&id=<?= $item['product_id'] ?>"><i class="far fa-window-close"></i></a>
                                            </td>
                                            <td class="product-image">
                                                <?php if (file_exists($imagePath)): ?>
                                                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="Product Image">
                                                <?php else: ?>
                                                    <img src="../assets/images/placeholder.png" alt="No Image">
                                                <?php endif; ?>
                                            </td>
                                            <td class="product-name"><?= htmlspecialchars($item['name']) ?></td>
                                            <td class="product-price">₱<?= number_format($item['price'], 2) ?></td>
                                            <td class="product-quantity">
                                                <a href="cart.php?action=increase&id=<?= $item['product_id'] ?>" class="btn btn-success btn-sm">+</a>
                                                <?= $item['quantity'] ?>
                                                <a href="cart.php?action=decrease&id=<?= $item['product_id'] ?>" class="btn btn-warning btn-sm">-</a>
                                            </td>
                                            <td class="product-total">₱<?= number_format($subtotal, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="total-section">
                        <table class="total-table">
                            <thead class="total-table-head">
                                <tr class="table-total-row">
                                    <th>Total</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="total-data">
                                    <td><strong>Subtotal: </strong></td>
                                    <td>₱<?= number_format($total, 2) ?></td>
                                </tr>
                                <tr class="total-data">
                                    <td><strong>Shipping: </strong></td>
                                    <td>₱<?= empty($cart_items) ? '0.00' : '45.00' ?></td>
                                </tr>
                                <tr class="total-data">
                                    <td><strong>Total: </strong></td>
                                    <td>₱<?= empty($cart_items) ? '0.00' : number_format($total + 45, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="cart-buttons">
                            <a href="products.php" class="btn btn-secondary btn-block">Back to Products</a>
                            <a href="cart.php?clear=1" class="btn btn-danger btn-block">Clear Cart</a>
                            <form action="checkout.php" method="POST" class="d-inline">
                                <button type="submit" class="btn btn-primary btn-block" <?= empty($cart_items) ? 'disabled' : '' ?>>Check Out</button>
                            </form>
                        </div>
                    </div>

                    <div class="coupon-section">
                        <h3>Apply Coupon</h3>
                        <div class="coupon-form-wrap">
                            <form action="#" method="POST">
                                <p><input type="text" name="coupon_code" placeholder="Coupon"></p>
                                <p><input type="submit" value="Apply"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>