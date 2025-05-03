<?php
include '../includes/db.php';
include '../includes/auth_check.php';
// Handle Add/Edit Product or Stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        $formType = $_POST['form_type'];

        if ($formType === 'add_product') {
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $category = $_POST['product_category'];
            $image = $_FILES['product_image'];

            $imageName = time() . '_' . basename($image['name']);
            $imagePath = '../assets/images/' . $imageName;
            if (!is_dir('../assets/images'))
                mkdir('../assets/images', 0777, true);

            move_uploaded_file($image['tmp_name'], $imagePath);

            $stmt = $pdo->prepare("INSERT INTO products (name, price, category, image, stock) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $category, $imagePath, 1]);

        } elseif ($formType === 'edit_product') {
            $id = $_POST['product_id'];
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $category = $_POST['product_category'];

            if (!empty($_FILES['product_image']['name'])) {
                $image = $_FILES['product_image'];
                $imageName = time() . '_' . basename($image['name']);
                $imagePath = '../assets/images/' . $imageName;
                move_uploaded_file($image['tmp_name'], $imagePath);

                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category=?, image=? WHERE id=?");
                $stmt->execute([$name, $price, $category, $imagePath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category=? WHERE id=?");
                $stmt->execute([$name, $price, $category, $id]);
            }

        } elseif ($formType === 'add_stock') {
            $productId = $_POST['product_id'];
            $quantity = (int) $_POST['quantity'];
            if ($quantity > 0) {
                $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                $stmt->execute([$quantity, $productId]);
            }
        }

        header("Location: products.php");
        exit;
    } elseif (isset($_POST['delete_product_id'])) {
        $productId = $_POST['delete_product_id'];

        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if ($product && file_exists($product['image']))
            unlink($product['image']);

        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$productId]);

        header("Location: products.php");
        exit;
    }
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../user/styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <style>
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Manage Products</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($products as $row): ?>
                <div class="col">
                    <div class="card product-card h-100">
                        <img src="<?= htmlspecialchars($row['image']) ?>" loading="lazy" class="card-img-top"
                            alt="Product Image">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="text-muted">Category: <?= htmlspecialchars($row['category']) ?></p>
                                <p class="text-muted">Stock: <?= $row['stock'] ?></p>
                                <p><strong>₱<?= number_format($row['price'], 2) ?></strong></p>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal"
                                    data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-price="<?= $row['price'] ?>"
                                    data-category="<?= htmlspecialchars($row['category']) ?>">
                                    Edit / Add Stock
                                </button>
                                <form method="POST" action="products.php" onsubmit="return confirm('Delete product?');">
                                    <input type="hidden" name="delete_product_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Add Product Card -->
            <div class="col">
                <div class="card h-100 text-center"
                    style="border: 2px dashed #ccc; background: #f9f9f9; cursor: pointer;" data-bs-toggle="modal"
                    data-bs-target="#addModal">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title">+ Add Product</h5>
                        <p class="text-muted">Click to add</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shared Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="products.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product / Add Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_type" id="form_type" value="edit_product">
                        <input type="hidden" name="product_id" id="edit_product_id">

                        <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" name="product_name" id="edit_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Price (₱)</label>
                            <input type="number" step="0.01" name="product_price" id="edit_price" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Category</label>
                            <input type="text" name="product_category" id="edit_category" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Change Image (optional)</label>
                            <input type="file" name="product_image" class="form-control" accept="image/*">
                        </div>
                        <hr>
                        <h6>Add Stock</h6>
                        <div class="input-group">
                            <input type="number" name="quantity" min="1" class="form-control">
                            <button type="submit" class="btn btn-success"
                                onclick="document.getElementById('form_type').value='add_stock'">Add Stock</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('form_type').value='edit_product'">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="products.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_type" value="add_product">
                        <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Price (₱)</label>
                            <input type="number" step="0.01" name="product_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Category</label>
                            <input type="text" name="product_category" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Product Image</label>
                            <input type="file" name="product_image" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            const category = button.getAttribute('data-category');

            document.getElementById('edit_product_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = category;
        });
    </script>
</body>

</html>