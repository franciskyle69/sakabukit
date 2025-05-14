<?php
include '../includes/db.php';
include '../includes/auth_check.php';
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
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
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

                    <div class="mt-4">
                        <label for="product_size">Size:</label>
                        <div class="d-flex flex-wrap">
                            <?php 
                            // Fetch sizes from the database
                            $stmt = $pdo->prepare("SELECT size FROM product_sizes WHERE product_id = ?");
                            $stmt->execute([$product['id']]);
                            $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            if ($sizes) {
                                foreach ($sizes as $size) { ?>
                                    <div class="card m-2" style="width: 8rem; cursor: pointer;" onclick="selectSize('<?php echo $size; ?>')">
                                        <div class="card-body text-center">
                                            <h5 class="card-title"><?php echo htmlspecialchars($size); ?></h5>
                                        </div>
                                    </div>
                                <?php }
                            } else {
                                echo "<p>No sizes available for this product.</p>";
                            }
                            ?>
                        </div>
                        <input type="hidden" name="product_size" id="product_size" value="">
                        </div>

                        <script>
                        function selectSize(size) {
                            document.getElementById('product_size').value = size;
                            const cards = document.querySelectorAll('.card');
                            cards.forEach(card => card.classList.remove('border-primary'));
                            event.currentTarget.classList.add('border-primary');
                        }
                        </script>
                    </div>
            
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h4>Product Description</h4>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
        </div>
    </div>
   
</div>

    

</body>
 <div class="text-center">
        <h4>Related Products</h4>
        <div class="row justify-content-center">
        <?php
        // Fetch related products from the same category
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
        $stmt->execute([$product['category'], $product['id']]);
        $relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($relatedProducts) {
            foreach ($relatedProducts as $related) {
                ?>
                <div class="col-md-2">
                    <div class="card mx-auto" style="width: 15rem; margin: 0; margin-bottom: 0;">
                        <img src="<?php echo htmlspecialchars($related['image']); ?>" class="card-img-top" alt="Related Product" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($related['name']); ?></h5>
                            <p class="card-text text-danger">₱<?php echo number_format($related['price'], 2); ?></p>
                            <a href="product_view.php?id=<?php echo $related['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No related products found.</p>";
        }
        ?>
    </div>

</html>