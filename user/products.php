<?php
include '../includes/db.php';
include '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products</title>
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

  <?php include '../includes/navbar.php'; ?>

  <main class="container mt-5 mb-5 flex-grow-1">
    <h2 class="text-center mb-4 font-weight-bold animate__animated animate__fadeInDown">
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
        <div class="sidebar-categories animate__animated animate__fadeInLeft">
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
          $delay = 0;
          if (isset($_GET['category']) && !empty($_GET['category'])) {
            $selectedCategory = $_GET['category'];
            $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
            $stmt->execute([$selectedCategory]);
          } else {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
          }

          while ($row = $stmt->fetch()) {
            $delay += 0.1;
            ?>
            <div class="col-md-4 mb-4">
              <a href="product_view.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm border-0 product-card animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay; ?>s;">
                  <img src="<?php echo htmlspecialchars($row["image"]); ?>" class="card-img-top product-img" alt="Product Image">
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                      <h5 class="card-title"><?php echo htmlspecialchars($row["name"]); ?></h5>
                      <p class="mb-1 fw-bold"><?php echo htmlspecialchars($row["category"]); ?></p>
                      <p class="text-muted mb-1">₱<?php echo number_format($row["price"], 2); ?></p>
                      <div class="text-warning mb-2">★★★★☆</div>
                    </div>
                    <button class="btn btn-primary btn-block mt-auto add-to-cart-btn">Add to Cart</button>
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

 <footer style="background-color: #051922;" class="animate__animated animate__fadeInUp animate__delay-1s">
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box about-widget">
                        <h2 class="widget-title">About us</h2>
                        <p>Saka Bukit is your trusted destination for eCommerce and booking services.
                             We offer a seamless shopping experience and easy reservations for various 
                             services. Our platform combines convenience, quality, and reliability to 
                             serve individuals and businesses across the region. Shop and book with confidence at Saka Bukit.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box get-in-touch">
                        <h2 class="widget-title">Get in Touch</h2>
                        <ul>
                            <li> Fortich Street, Barangay 3, Malaybalay City, Bukidnon</li>
                            <li>support@sakabukit.com</li>
                            <li>+00 111 222 3333</li>
                        </ul>
                        </div>
                    </div>
                    
                
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box subscribe">
                        <h2 class="widget-title">Subscribe</h2>
                        <p>Subscribe to our mailing list to get the latest updates.</p>
                        <form action="#">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="email" placeholder="Email" style="flex: 1; height: 40px; padding: 0 10px;">
                                <button type="submit" style="height: 40px; display: flex; align-items: center; justify-content: center; padding: 0 15px;">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- <div class="container text-center mt-5">
            <p class="mb-0" style="color: orange;">&copy; <?= date('Y'); ?> Saka Buk IT. All rights reserved.</p>
            <small>Climb mountains not so the world can see you, but so you can see the world.</small>
        </div> -->
    </footer>
        <div class="copyright text-center animate__animated animate__fadeIn animate__delay-2s">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-12">
                    <p>Copyrights &copy; 2025 - <a href="../user/index.php">SAKA BUKIT</a>, All Rights Reserved.<br>
                        Climb mountains not so the world can see you, but so you can see the world
                        </p>
                    </div>
                </div>
            </div>
        </div>
  </div>

  <!-- Animate.css CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <script>
    // Add a little interaction animation for Add to Cart button
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          btn.classList.add('animate__animated', 'animate__tada');
          setTimeout(function() {
            btn.classList.remove('animate__animated', 'animate__tada');
          }, 700);
        });
      });
    });
  </script>
</body>

</html>