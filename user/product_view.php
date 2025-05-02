<?php
include '../includes/db.php';

// Check if there is an ID in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Make sure it's an integer

    // Get the product from database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Product found, display it
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>

<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="text-muted">Category: <?php echo htmlspecialchars($product['category']); ?></p>
                <h3 class="text-danger">₱<?php echo number_format($product['price'], 2); ?></h3>
                <p>Available Stock: <?php echo $product['stock']; ?></p>

                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="product_image"
                        value="<?php echo htmlspecialchars($product['image']); ?>">
                    <input type="hidden" name="product_quantity" value="1">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>