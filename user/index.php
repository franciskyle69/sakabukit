<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

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
	<link rel="stylesheet" href="../ssets/css/owl.carousel.css">
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
        #fullscreenPlayer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: translateY(100%);
            transition: transform 0.5s ease;
            text-align: center;
            padding: 20px;
        }

        #fullscreenPlayer h2 {
            color: white;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        #fullscreenPlayer p {
            color: lightgray;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        #fullscreenVideo {
            width: 90%;
            max-height: 70%;
            border-radius: 10px;
        }

        .single-logo-item img {
            transition: transform 0.3s cubic-bezier(.4,2,.6,1), box-shadow 0.3s cubic-bezier(.4,2,.6,1);
        }
        .single-logo-item img:hover {
            transform: scale(1.15) rotate(-2deg);
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            z-index: 2;
        }
    </style>
</head>

<body>

    


    <div  style="" > <?php include '../includes/navbar.php'; ?> </div>

    <div class="hero-area hero-bg">
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height: 100%;">
                <div class="col-lg-9 text-center">
                    <div class="hero-text">
                        <div class="hero-text-tablecell">
                            <p class="subtitle">SAKA BUK-IT</p>
                            <h1>Explore more, Worry Less</h1>
                            <div class="hero-btns">
                                <a href="products.php" class="boxed-btn">Gear Collection</a>
                                <a href="bookings.php" class="bordered-btn">Book With Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    
    <!-- products -->
    <div class="product-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="section-title">    
                        <h3><span class="orange-text">Our</span> Products</h3>
                        <p>Explore our range of products tailored for your adventures.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                // Include database connection
                include '../includes/db.php';

                // Fetch only 3 products from the database
                $query = "SELECT * FROM products LIMIT 3";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0):
                    while ($product = mysqli_fetch_assoc($result)): ?>
                        <div class="col-lg-4 col-md-6 text-center">
                            <div class="single-product-item">
                                <div class="product-image">
                                    <a href="single-product.php?id=<?= $product['id'] ?>">
                                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    </a>
                                </div>
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-price">₱<?= htmlspecialchars($product['price']) ?></p>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                                    <input type="hidden" name="product_price" value="<?= htmlspecialchars($product['price']) ?>">
                                    <button type="submit" class="cart-btn btn btn-primary mt-2">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile;
                else: ?>
                    <div class="col-12 text-center">
                        <p>No products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
   

    <div class="d-flex justify-content-center align-items-center my-5" >
        <div class="row" style="width: 100%; max-width: 1500px;">
            <!-- Card Content -->
            <div class="col-md-4" style="margin-left: px;">
                <div class="card" style="height: 600px;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="card-title">MT.KULAGO</h3>
                            <p class="card-text" style="text-align: justify; line-height: 1.5;">Mt. Kulago is located in Impasugong, Bukidnon. Famous for its scenic grassland, 
                                river trekking, and its open trail that truly encapsulates the beauty of the landscape of Bukidnon.
                                Mt. Kulago has an elevation of approximately 913 meters above sea level (MASL) with 6/­9 difficulty and 1-3 class of trail.</p>
                                <p>INCLUSIONS:</p>
                                <p>☑️ Registration Fee</p>
                                <p>☑️ Guide fee</p>
                                <p>☑️ Local fee & community fee</p>
                                <p>☑️ 2 hosted meals (dinner & breakfast)</p>
                                <p>☑️ Roundtrip Transportation (Dvo-Bukidnon v.v)</p>
                                <p>☑️ Ground Rental fee</p>
                                <p>☑️ Souviner sticker</p>

                        </div>
                        <a href="bookings.php" class="btn btn-primary mt-3">Book Your Adventure</a>
                    </div>
                </div>
            </div>
            <!-- Video Frame -->
            <div class="col-md-8">
                <div class="embed-responsive embed-responsive-16by9" style="width: 100%; height: 600px;">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/TFXkecXJXZE" allowfullscreen style="width: 100%; height: 100%;"></iframe>
                </div>
            </div>
        </div>
    </div>

    
    <main class="container mt-3 mb-4">
        <div class="content p-5">
            <?php if ($role === 'user'): ?>
                <p>Welcome <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>!</p>
            <?php else: ?>
                <p>Welcome, Guest!</p>
                <a href="../login.php">Login</a> or <a href="../signup.php">Sign up</a>
            <?php endif; ?>
            <p class="text-muted">Your partner in the mountain!</p>
            <br>

            <!-- video start -->

            <div class="row">
                <!-- First Video -->
                <div class="col-md-6 mb-4">
                    <div class="card product-card text-center">
                        <h5 class="card-title mt-3">Kulago</h5>
                        <div class="card-body d-flex justify-content-center">
                            <div class="video-wrapper" data-title="Kulago"
                                data-description="Discover the peaceful beauty of Mount Kulago, where every step brings you closer to nature's calm and endless adventure.">
                                <video class="w-100 video-hover" style="max-height: 400px; object-fit: cover;">
                                    <source src="../assets/videos/promote.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                        <p class="text-muted">Discover the peaceful beauty of Mount Kulago, where every step brings you
                            closer to nature's calm and endless adventure.</p>
                    </div>
                </div>

                <!-- Second Video -->
                <div class="col-md-6 mb-4">
                    <div class="card product-card text-center">
                        <h5 class="card-title mt-3">Holon</h5>
                        <div class="card-body d-flex justify-content-center">
                            <div class="video-wrapper" data-title="Holon"
                                data-description="Journey to the breathtaking Lake Holon, hidden in the heart of the mountains — a perfect escape for your soul and spirit.">
                                <video class="w-100 video-hover" style="max-height: 400px; object-fit: cover;">
                                    <source src="../assets/videos/holon.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                        <p class="text-muted">Journey to the breathtaking Lake Holon, hidden in the heart of the
                            mountains — a perfect escape for your soul and spirit.</p>
                    </div>
                </div>
            </div>
            <!-- video end -->

            <script>
                document.querySelectorAll('.video-hover').forEach(video => {
                    video.addEventListener('mouseenter', () => {
                        video.play();
                    });
                    video.addEventListener('mouseleave', () => {
                        video.pause();
                        video.currentTime = 0;
                    });
                });
            </script>

        </div>
    </main>

    <div style="display: flex; justify-content: center; align-items: center;">
		<h1>Tour Organizer/Guides</h1>
	</div>

    
    <div class="logo-carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner d-flex justify-content-between align-items-center">
                        <div class="single-logo-item">
                            <a href="https://www.facebook.com/iTREKKERSph" target="_blank">
                                <img src="../assets/img/company-logos/iT.jpg"style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <div class="single-logo-item">
                            <a href="https://www.facebook.com/profile.php?id=61561342811020" target="_blank">
                                <img src="../assets/img/company-logos/logo.jpg" style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <div class="single-logo-item">
                            <a href="https://www.facebook.com/AngtadmountaineersMountainTrip" target="_blank">
                                <img src="../assets/img/company-logos/EXP.jpg" style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <div class="single-logo-item"">
                            <a href="https://www.facebook.com/profile.php?id=61563526834293" target="_blank">
                                <img src="../assets/img/company-logos/PB.jpg"  style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <!-- <div class="single-logo-item">
                            <img src="assets/img/company-logos/5.png" alt="">
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Player Overlay -->
    <div id="fullscreenPlayer">
        <h2 id="fullscreenTitle"></h2>
        <p id="fullscreenDesc"></p>
        <video id="fullscreenVideo" controls></video>
    </div>
    

    <footer style>
    <div class="footer-area" >
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">About us</h2>
						<p>Saka Bukit is your trusted destination for eCommerce and booking services. We offer a seamless shopping experience and easy reservations for various services. Our platform combines convenience, quality, and reliability to serve individuals and businesses across the region. Shop and book with confidence at Saka Bukit.</p>
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
        <div class="container text-center mt-5">
            <p class="mb-0" style="color: orange;">&copy; <?= date('Y'); ?> Saka Buk IT. All rights reserved.</p>
            <small>Climb mountains not so the world can see you, but so you can see the world.</small>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.querySelectorAll('.video-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', () => {
                const videoSrc = wrapper.querySelector('video source').src;
                const title = wrapper.getAttribute('data-title');
                const description = wrapper.getAttribute('data-description');

                const fullscreenVideo = document.getElementById('fullscreenVideo');
                const fullscreenTitle = document.getElementById('fullscreenTitle');
                const fullscreenDesc = document.getElementById('fullscreenDesc');
                const fullscreenPlayer = document.getElementById('fullscreenPlayer');

                fullscreenVideo.src = videoSrc;
                fullscreenTitle.textContent = title;
                fullscreenDesc.textContent = description;

                fullscreenPlayer.style.transform = 'translateY(0%)'; // Slide up
                fullscreenVideo.play();
            });
        });

        // Close when clicking outside
        document.getElementById('fullscreenPlayer').addEventListener('click', (e) => {
            if (e.target.id === 'fullscreenPlayer') {
                const fullscreenVideo = document.getElementById('fullscreenVideo');
                fullscreenVideo.pause();
                fullscreenVideo.currentTime = 0;
                fullscreenVideo.src = '';
                document.getElementById('fullscreenPlayer').style.transform = 'translateY(100%)'; // Slide down
            }
        });
    </script>
    

</body>

</html>