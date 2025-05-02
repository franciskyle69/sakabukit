<?php
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products</title>

  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../user/styles/styles.css">
  <link rel="icon" type="image/png" href="../assets/images/logo.png">

  <style>
    .product-card {
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      font-size: 1rem;
      font-weight: 600;
    }

    .btn-block {
      font-weight: bold;
    }

    .sidebar-categories {
      background-color: rgba(200, 200, 200, 0.3);
      padding: 1rem;
      border-radius: 10px;
    }

    .category-link {
      color: #333;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease-in-out;
    }

    .category-link:hover {
      color: #007bff;
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <?php include '../includes/navbar.php'; ?>

  <main class="container mt-5 mb-5 flex-grow-1">
    <h2 class="text-center mb-4 font-weight-bold">
      <?php
      if (isset($_GET['category'])) {
        echo htmlspecialchars($_GET['category']);
      } else {
        echo 'All Products';
      }
      ?>
    </h2>

    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 mb-4">
        <div class="sidebar-categories">
          <h5 class="font-weight-bold mb-3">Categories</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="products.php" class="category-link">All</a></li>
            <li class="mb-2"><a href="products.php?category=Jackets" class="category-link">Jackets</a></li>
            <li class="mb-2"><a href="products.php?category=Backpacks" class="category-link">Backpacks</a></li>
            <li class="mb-2"><a href="products.php?category=Footwear" class="category-link">Footwear</a></li>
            <li class="mb-2"><a href="products.php?category=Accessories" class="category-link">Accessories</a></li>
            <li class="mb-2"><a href="products.php?category=Bundles" class="category-link">Bundles</a></li>
          </ul>
        </div>
      </div>

      <!-- Product Listing -->
      <div class="col-md-9">
        <div class="row">
          <?php
          if (isset($_GET['category']) && !empty($_GET['category'])) {
            $selectedCategory = $_GET['category'];
            $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
            $stmt->execute([$selectedCategory]);
          } else {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
          }

          while ($row = $stmt->fetch()) {
            ?>
            <div class="col-md-4 mb-4">
              <a href="product_view.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm border-0 product-card">
                  <img src="<?php echo htmlspecialchars($row["image"]); ?>" class="card-img-top" alt="Product Image">
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                      <h5 class="card-title"><?php echo htmlspecialchars($row["name"]); ?></h5>
                      <p class="mb-1 fw-bold"><?php echo htmlspecialchars($row["category"]); ?></p>
                      <p class="text-muted mb-1">₱<?php echo number_format($row["price"], 2); ?></p>
                      <div class="text-warning mb-2">★★★★☆</div>
                    </div>
                    <button class="btn btn-primary btn-block mt-auto">Add to Cart</button>
                  </div>
                </div>
              </a>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
  </main>

  <footer class="text-center">
    <div class="container">
      <p class="mb-0">&copy; <?= date('Y'); ?> Saka Buk IT. All rights reserved.</p>
      <small>Climb mountains not so the world can see you, but so you can see the world.</small>
    </div>
  </footer>

</body>

</html>