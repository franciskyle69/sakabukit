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
            $description = $_POST['product_description'];
            $image = $_FILES['product_image'];

            // Handle sizes
            $sizes = isset($_POST['product_sizes']) ? json_encode($_POST['product_sizes']) : null;

            $imageName = time() . '_' . basename($image['name']);
            $imagePath = '../assets/images/' . $imageName;
            if (!is_dir('../assets/images'))
                mkdir('../assets/images', 0777, true);

            compressAndResizeImage($image['tmp_name'], $imagePath, 70, 1000);

            $stmt = $pdo->prepare("INSERT INTO products (name, price, category, description, image, stock, sizes) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $category, $description, $imagePath, 1, $sizes]);

        } elseif ($formType === 'edit_product') {
            $id = $_POST['product_id'];
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $category = $_POST['product_category'];
            $description = $_POST['product_description'];

            // Handle sizes
            $sizes = isset($_POST['product_sizes']) ? json_encode($_POST['product_sizes']) : null;

            if (!empty($_FILES['product_image']['name'])) {
                $image = $_FILES['product_image'];
                $imageName = time() . '_' . basename($image['name']);
                $imagePath = '../assets/images/' . $imageName;

                compressAndResizeImage($image['tmp_name'], $imagePath, 70, 1000);

                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category=?, description=?, image=?, sizes=? WHERE id=?");
                $stmt->execute([$name, $price, $category, $description, $imagePath, $sizes, $id]);
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
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
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
                        <img src="<?= htmlspecialchars($row['image']) ?>" loading="lazy" class="card-img-top" alt="Product Image" style="height:180px;object-fit:cover;">
                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="card-title mb-1"><?= htmlspecialchars($row['name']) ?></h6>
                                <div class="small text-muted mb-1"><?= htmlspecialchars($row['category']) ?> | Stock: <?= $row['stock'] ?></div>
                                <?php if (!empty($row['sizes'])): ?>
                                    <?php
                                        $sizes = json_decode($row['sizes'], true);
                                        $availableSizes = is_array($sizes) ? implode(', ', $sizes) : $row['sizes'];
                                    ?>
                                    <div class="small text-muted mb-1">Sizes: <?= htmlspecialchars($availableSizes) ?></div>
                                <?php endif; ?>
                                <div class="fw-bold mb-2">₱<?= number_format($row['price'], 2) ?></div>
                            </div>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-info flex-fill" data-bs-toggle="modal" data-bs-target="#editModal"
                                    data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-price="<?= $row['price'] ?>"
                                    data-category="<?= htmlspecialchars($row['category']) ?>">
                                    Edit
                                </button>
                                <form method="POST" action="products.php" onsubmit="return confirm('Delete product?');" class="flex-fill">
                                    <input type="hidden" name="delete_product_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger w-100">Delete</button>
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
                            <select name="product_category" id="edit_category" class="form-select" onchange="toggleEditSizeOptions(this.value)">
                                <option value="" disabled>Select a category</option>
                                <option value="Jackets">Jackets</option>
                                <option value="Backpacks">Backpacks</option>
                                <option value="Footwear">Footwear</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Bundles">Bundles</option>
                            </select>
                            </div>
                            <!-- Clothing Sizes -->
                            <div class="mb-3" id="edit-clothing-size-group" style="display: none;">
                                <label>Clothing Sizes</label>
                                <select name="product_sizes[]" id="edit_clothing_sizes" class="form-select" multiple>
                                    <option value="Small">Small</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Large">Large</option>
                                    <option value="Extra Large">Extra Large</option>
                                </select>
                                <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple sizes.</small>
                            </div>
                            <!-- Shoe Sizes -->
                            <div class="mb-3" id="edit-shoe-size-group" style="display: none;">
                                <label>Shoe Sizes (US)</label>
                                <select name="product_sizes[]" id="edit_shoe_sizes" class="form-select" multiple>
                                    <option value="6">6</option>
                                    <option value="6.5">6.5</option>
                                    <option value="7">7</option>
                                    <option value="7.5">7.5</option>
                                    <option value="8">8</option>
                                    <option value="8.5">8.5</option>
                                    <option value="9">9</option>
                                    <option value="9.5">9.5</option>
                                    <option value="10">10</option>
                                    <option value="10.5">10.5</option>
                                    <option value="11">11</option>
                                    <option value="11.5">11.5</option>
                                    <option value="12">12</option>
                                    <option value="12.5">12.5</option>
                                    <option value="13">13</option>
                                </select>
                                <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple sizes.</small>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="product_description" id="edit_description" class="form-control" rows="3"></textarea>
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
                            <script>
                            function toggleEditSizeOptions(category) {
                                const clothingGroup = document.getElementById('edit-clothing-size-group');
                                const shoeGroup = document.getElementById('edit-shoe-size-group');
                                if (category === 'Footwear') {
                                    clothingGroup.style.display = 'none';
                                    shoeGroup.style.display = 'block';
                                } else if (category === 'Jackets' || category === 'Bundles') {
                                    clothingGroup.style.display = 'block';
                                    shoeGroup.style.display = 'none';
                                } else {
                                    clothingGroup.style.display = 'none';
                                    shoeGroup.style.display = 'none';
                                }
                            }

                            // Populate modal fields on show
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

                                toggleEditSizeOptions(category);

                                // Optionally, set the selected sizes if you have them in your data attributes
                                // let sizes = ...; // get from data attribute or ajax
                                // const clothingSelect = document.getElementById('edit_clothing_sizes');
                                // const shoeSelect = document.getElementById('edit_shoe_sizes');
                                // for (let option of clothingSelect.options) {
                                //     option.selected = sizes && sizes.includes(option.value);
                                // }
                                // for (let option of shoeSelect.options) {
                                //     option.selected = sizes && sizes.includes(option.value);
                                // }
                            });
                            </script>

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
                            <select name="product_category" class="form-select" required onchange="toggleSizeOptions(this.value)">
                                <option value="" disabled selected>Select a category</option>
                                <option value="Jackets">Jackets</option>
                                <option value="Backpacks">Backpacks</option>
                                <option value="Footwear">Footwear</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Bundles">Bundles</option>
                            </select>
                        </div>
                        <!-- Clothing Sizes -->
                        <div class="mb-3" id="clothing-size-options" style="display: none;">
                            <label>Clothing Sizes</label>
                            <select name="product_sizes[]" class="form-select" multiple>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>
                                <option value="Extra Large">Extra Large</option>
                            </select>
                            <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple sizes.</small>
                        </div>
                        <!-- Shoe Sizes -->
                        <div class="mb-3" id="shoe-size-options" style="display: none;">
                            <label>Shoe Sizes (US)</label>
                            <select name="product_sizes[]" class="form-select" multiple>
                                <option value="6">6</option>
                                <option value="6.5">6.5</option>
                                <option value="7">7</option>
                                <option value="7.5">7.5</option>
                                <option value="8">8</option>
                                <option value="8.5">8.5</option>
                                <option value="9">9</option>
                                <option value="9.5">9.5</option>
                                <option value="10">10</option>
                                <option value="10.5">10.5</option>
                                <option value="11">11</option>
                                <option value="11.5">11.5</option>
                                <option value="12">12</option>
                                <option value="12.5">12.5</option>
                                <option value="13">13</option>
                            </select>
                            <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple sizes.</small>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="product_description" class="form-control" rows="3" required></textarea>
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
        function toggleSizeOptions(category) {
            const clothingSizeOptions = document.getElementById('clothing-size-options');
            const shoeSizeOptions = document.getElementById('shoe-size-options');

            if (category === 'Footwear') {
                clothingSizeOptions.style.display = 'none';
                shoeSizeOptions.style.display = 'block';
            } else if (category === 'Jackets' || category === 'Bundles') {
                clothingSizeOptions.style.display = 'block';
                shoeSizeOptions.style.display = 'none';
            } else {
                clothingSizeOptions.style.display = 'none';
                shoeSizeOptions.style.display = 'none';
            }
        }
    </script>

    <script>
        function toggleSizeOptions(category) {
            const sizeOptions = document.getElementById('size-options');
            const shoeSizeOptions = document.getElementById('shoe-size-options');
            const multiSizeOptions = document.getElementById('multi-size-options');

            if (category === 'Footwear') {
                sizeOptions.style.display = 'none';
                shoeSizeOptions.style.display = 'none';
                multiSizeOptions.style.display = 'block';
            } else if (category === 'Jackets' || category === 'Bundles') {
                sizeOptions.style.display = 'none';
                shoeSizeOptions.style.display = 'none';
                multiSizeOptions.style.display = 'block';
            } else {
                sizeOptions.style.display = 'none';
                shoeSizeOptions.style.display = 'none';
                multiSizeOptions.style.display = 'none';
            }
        }
    </script>

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