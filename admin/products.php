<?php
include '../includes/db.php';
include '../includes/auth_check.php';

// Helper: Compress and Resize Image
function compressAndResizeImage($sourcePath, $destinationPath, $quality = 70, $maxWidth = 1000)
{
    $info = getimagesize($sourcePath);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($sourcePath);
            break;
        default:
            move_uploaded_file($sourcePath, $destinationPath);
            return;
    }

    $width = imagesx($image);
    $height = imagesy($image);

    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = floor($height * ($maxWidth / $width));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        $image = $resized;
    }

    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagejpeg($image, $destinationPath, $quality);
    } else {
        imagejpeg($image, $destinationPath, $quality);
    }

    imagedestroy($image);
}

// Handle Add/Edit Product or Stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        $formType = $_POST['form_type'];

        if ($formType === 'add_product') {
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $category = $_POST['product_category'];
            $image = $_FILES['product_image'];
            $description = $_POST['description'];
            $sizes = $_POST['sizes'];

            $imageName = time() . '_' . basename($image['name']);
            $imagePath = '../assets/images/' . $imageName;
            if (!is_dir('../assets/images'))
                mkdir('../assets/images', 0777, true);

            compressAndResizeImage($image['tmp_name'], $imagePath, 70, 1000);

            $stmt = $pdo->prepare("INSERT INTO products (name, price, category, image, stock, description, sizes) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $category, $imagePath, 1, $description, $sizes]);

        } elseif ($formType === 'edit_product') {
            $id = $_POST['product_id'];
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $category = $_POST['product_category'];
            $description = $_POST['description'];
            $sizes = $_POST['sizes'];

            if (!empty($_FILES['product_image']['name'])) {
                $image = $_FILES['product_image'];
                $imageName = time() . '_' . basename($image['name']);
                $imagePath = '../assets/images/' . $imageName;

                compressAndResizeImage($image['tmp_name'], $imagePath, 70, 1000);

                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category=?, image=?, description=?, sizes=? WHERE id=?");
                $stmt->execute([$name, $price, $category, $imagePath, $description, $sizes, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category=?, description=?, sizes=? WHERE id=?");
                $stmt->execute([$name, $price, $category, $description, $sizes, $id]);
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
    <!-- External Stylesheets (refactored for consistency) -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.css">
    <link rel="stylesheet" href="../assets/css/magnific-popup.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
    <link rel="stylesheet" href="../assets/css/meanmenu.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <style>
        html {
            font-size: 16px;
        }
        body {
            font-family: 'Open Sans', 'Poppins', Arial, sans-serif;
            font-size: 1rem;
        }
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
                                <p class="text-muted">Description: <?= htmlspecialchars($row['description']) ?></p>
                                <p class="text-muted">Sizes: <?= htmlspecialchars($row['sizes']) ?></p>
                                <p><strong>₱<?= number_format($row['price'], 2) ?></strong></p>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal"
                                    data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-price="<?= $row['price'] ?>"
                                    data-category="<?= htmlspecialchars($row['category']) ?>"
                                    data-description="<?= htmlspecialchars($row['description']) ?>"
                                    data-sizes="<?= htmlspecialchars($row['sizes']) ?>">
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

    <!-- Edit Modal -->
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
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Available Sizes</label><br>
                            <div id="edit-sizes-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="S" id="edit-size-s">
                                    <label class="form-check-label" for="edit-size-s">S</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="M" id="edit-size-m">
                                    <label class="form-check-label" for="edit-size-m">M</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="L" id="edit-size-l">
                                    <label class="form-check-label" for="edit-size-l">L</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="XL" id="edit-size-xl">
                                    <label class="form-check-label" for="edit-size-xl">XL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="XXL" id="edit-size-xxl">
                                    <label class="form-check-label" for="edit-size-xxl">XXL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="None" id="edit-size-none">
                                    <label class="form-check-label" for="edit-size-none">No sizes available</label>
                                </div>
                            </div>
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
                            <select name="product_category" class="form-select" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Jackets">Jackets</option>
                                <option value="Backpacks">Backpacks</option>
                                <option value="Footwear">Footwear</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Bundles">Bundles</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Available Sizes</label><br>
                            <div id="add-sizes-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="S" id="add-size-s">
                                    <label class="form-check-label" for="add-size-s">S</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="M" id="add-size-m">
                                    <label class="form-check-label" for="add-size-m">M</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="L" id="add-size-l">
                                    <label class="form-check-label" for="add-size-l">L</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="XL" id="add-size-xl">
                                    <label class="form-check-label" for="add-size-xl">XL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="XXL" id="add-size-xxl">
                                    <label class="form-check-label" for="add-size-xxl">XXL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="None" id="add-size-none">
                                    <label class="form-check-label" for="add-size-none">No sizes available</label>
                                </div>
                            </div>
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
            const description = button.getAttribute('data-description');
            const sizes = button.getAttribute('data-sizes');

            document.getElementById('edit_product_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_description').value = description;
            const sizesArray = sizes ? sizes.split(',') : [];
            ['S','M','L','XL','XXL','None'].forEach(size => {
                const cb = document.getElementById('edit-size-' + size.toLowerCase());
                if (cb) cb.checked = sizesArray.includes(size);
            });
        });

        // Add Modal: Disable other checkboxes if 'No sizes available' is checked
        const addNone = document.getElementById('add-size-none');
        const addSizeCheckboxes = Array.from(document.querySelectorAll('#add-sizes-group input[type=checkbox]')).filter(cb => cb.value !== 'None');
        addNone.addEventListener('change', function() {
            if (this.checked) {
                addSizeCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = true; });
            } else {
                addSizeCheckboxes.forEach(cb => { cb.disabled = false; });
            }
        });
        addSizeCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) addNone.checked = false;
            });
        });

        // Edit Modal: Same logic
        const editNone = document.getElementById('edit-size-none');
        const editSizeCheckboxes = Array.from(document.querySelectorAll('#edit-sizes-group input[type=checkbox]')).filter(cb => cb.value !== 'None');
        editNone.addEventListener('change', function() {
            if (this.checked) {
                editSizeCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = true; });
            } else {
                editSizeCheckboxes.forEach(cb => { cb.disabled = false; });
            }
        });
        editSizeCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) editNone.checked = false;
            });
        });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>