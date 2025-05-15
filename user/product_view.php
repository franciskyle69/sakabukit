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

                    <div class="mt-4" style="margin-bottom:10px;">
                        <h5>Available Sizes</h5>
                        <div class="row">
                            <?php
                            // Define which categories use sizes
                            $footwearCategories = ['Footwear', 'Shoes', 'Slippers', 'Sandals']; // adjust as needed
                            $clothingCategories = ['Clothing', 'Shirts', 'Pants', 'Jackets', 'T-Shirts']; // adjust as needed

                            $category = strtolower(trim($product['category']));

                            // Check if product is footwear or clothing
                            $isFootwear = in_array(ucfirst($category), $footwearCategories);
                            $isClothing = in_array(ucfirst($category), $clothingCategories);

                            if ($isFootwear || $isClothing) {
                                if (!empty($product['sizes'])) {
                                    $sizes = array_map('trim', explode(',', $product['sizes']));
                                    ?>
                                    <div class="row" id="size-options">
                                        <?php foreach ($sizes as $size): ?>
                                            <div class="col-4 mb-2">
                                                <label class="card text-center size-card" style="cursor:pointer;">
                                                    <input type="radio" name="selected_size" value="<?php echo htmlspecialchars($size); ?>" class="d-none" required>
                                                    <div class="card-body p-2">
                                                        <span class="fw-bold"><?php echo htmlspecialchars($size); ?></span>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <script>
                                    // Highlight selected card
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const cards = document.querySelectorAll('.size-card input[type="radio"]');
                                        cards.forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                document.querySelectorAll('.size-card').forEach(function(card) {
                                                    card.classList.remove('border-primary');
                                                });
                                                if (radio.checked) {
                                                    radio.closest('.size-card').classList.add('border-primary');
                                                }
                                            });
                                        });
                                    });
                                    </script>
                                    <?php
                                } else {
                                    echo '<div class="col-12"><p>No sizes available.</p></div>';
                                }
                            } else {
                                echo '<div class="col-12"><p>Sizes not applicable for this product.</p></div>';
                            }
                            ?>
                        </div>
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
                <div class="col-md-3 d-flex justify-content-center mb-4">
                    <div class="card mx-auto" style="width: 20rem; margin: 0;">
                        <img src="<?php echo htmlspecialchars($related['image']); ?>" class="card-img-top" alt="Related Product" style="height: 250px; object-fit: cover;">
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