<?php
require_once '../includes/auth_check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If no role is set, treat as guest
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

$role = $_SESSION['role'];
?>
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
        html {
            font-size: 16px;
        }
        body {
            font-family: 'Open Sans', 'Poppins', Arial, sans-serif;
            font-size: 1rem;
        }
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
            transition: transform 0.3s cubic-bezier(.4, 2, .6, 1), box-shadow 0.3s cubic-bezier(.4, 2, .6, 1);
        }

        .single-logo-item img:hover {
            transform: scale(1.15) rotate(-2deg);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            z-index: 2;
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s forwards;
        }
        .fade-in.delay-1 { animation-delay: 0.1s; }
        .fade-in.delay-2 { animation-delay: 0.2s; }
        .fade-in.delay-3 { animation-delay: 0.5s; }
        .fade-in.delay-4 { animation-delay: 0.8; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            animation: scaleIn 0.8s forwards;
        }
        .scale-in.delay-1 { animation-delay: 0.5s; }
        .scale-in.delay-2 { animation-delay: 0.8s; }
        .scale-in.delay-3 { animation-delay: 1.1s; }
        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

    <div style=""> <?php include '../includes/navbar.php'; ?> </div>

    <div class="hero-area hero-bg fade-in delay-1">
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height: 100%;">
                <div class="col-lg-9 text-center">
                    <div class="hero-text">
                        <div class="hero-text-tablecell">
                            <p class="subtitle fade-in delay-2">SAKA BUK-IT</p>
                            <h1 class="fade-in delay-3">Explore more, Worry Less</h1>
                            <div class="hero-btns fade-in delay-4">
                                <?php if ($role === 'guest'): ?>
                                    <a href="../login.php" class="boxed-btn scale-in delay-1">Login</a>
                                    <a href="../signup.php" class="bordered-btn scale-in delay-2">Sign Up</a>
                                <?php else: ?>
                                    <a href="products.php" class="boxed-btn scale-in delay-1">Gear Collection</a>
                                    <a href="bookings.php" class="bordered-btn scale-in delay-2">Book With Us</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- products -->
    <div class="product-section mt-150 mb-150 fade-in delay-2">
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
                    $delay = 1;
                    while ($product = mysqli_fetch_assoc($result)): ?>
                        <div class="col-lg-4 col-md-6 text-center d-flex align-items-stretch fade-in delay-<?= $delay ?>">
                            <div class="single-product-item w-100"
                                style="display: flex; flex-direction: column; height: 100%; min-height: 420px; max-width: 350px; margin: 0 auto;">
                                <div class="product-image"
                                    style="flex: 0 0 300px; display: flex; align-items: center; justify-content: center; height: 300px; overflow: hidden;">
                                    <a href="product_view.php?id=<?= $product['id'] ?>"
                                        style="display: block; width: 100%; height: 100%;">
                                        <img src="<?= htmlspecialchars($product['image']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>"
                                            style="max-width: 100%; max-height: 100%; object-fit: cover; width: 100%; height: 100%;">
                                    </a>
                                </div>
                                <div
                                    style="flex: 1 1 auto; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div>
                                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                                        <p class="product-price">₱<?= htmlspecialchars($product['price']) ?></p>
                                    </div>
                                    <form method="post" action="cart.php" style="margin-top: auto;">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="product_name"
                                            value="<?= htmlspecialchars($product['name']) ?>">
                                        <input type="hidden" name="product_price"
                                            value="<?= htmlspecialchars($product['price']) ?>">
                                        <button type="submit" class="cart-btn btn btn-primary mt-2 scale-in delay-<?= $delay ?>">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php $delay++; endwhile;
                else: ?>
                    <div class="col-12 text-center fade-in delay-1">
                        <p>No products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center my-5 fade-in delay-3">
        <div class="row" style="width: 100%; max-width: 1500px;">
            <!-- Card Content -->
            <div class="col-md-4">
                <div class="card scale-in delay-1" style="height: 700px;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="card-title">MT.KULAGO</h3>
                            <p class="card-text" style="text-align: justify; line-height: 1.5;">Mt. Kulago is located in
                                Impasugong, Bukidnon. Famous for its scenic grassland,
                                river trekking, and its open trail that truly encapsulates the beauty of the landscape
                                of Bukidnon.
                                Mt. Kulago has an elevation of approximately 913 meters above sea level (MASL) with 6/­9
                                difficulty and 1-3 class of trail.</p>
                            <p>INCLUSIONS:</p>
                            <p>☑️ Registration Fee</p>
                            <p>☑️ Guide fee</p>
                            <p>☑️ Local fee & community fee</p>
                            <p>☑️ 2 hosted meals (dinner & breakfast)</p>
                            <p>☑️ Roundtrip Transportation (Dvo-Bukidnon v.v) or (CDO-Bukidnon v.v)</p>
                            <p>☑️ Ground Rental fee</p>
                            <p>☑️ Souviner sticker</p>
                        </div>
                        <a href="bookings.php" class="btn btn-primary mt-3 scale-in delay-2">Book Your Adventure</a>
                    </div>
                </div>
            </div>
            <!-- Video Frame -->
            <div class="col-md-8">
                <div class="embed-responsive embed-responsive-16by9 scale-in delay-3" style="width: 100%; height: 700px;">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/TFXkecXJXZE"
                        allowfullscreen style="width: 100%; height: 100%;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center my-5 fade-in delay-4">
        <div class="row" style="width: 100%; max-width: 1500px;">
            <!-- Card Content -->
            <div class="col-md-4">
                <div class="card scale-in delay-1" style="height: 700px;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="card-title">LAKE HOLON</h3>
                            <p class="card-text" style="text-align: justify; line-height: 1.5;">Lake Holon is a volcanic
                                crater lake located in T'boli, South Cotabato, Philippines. It is considered the
                                cleanest inland body
                                of water in the Philippines and is known for its scenic beauty. Lake Holon is also
                                recognized as one of the world's "Top 100 Sustainable Destinations".</p>
                            <p>INCLUSIONS:</p>
                            <p>☑️ Registration Fee</p>
                            <p>☑️ Guide fee</p>
                            <p>☑️ Local fee & community fee</p>
                            <p>☑️ 2 hosted meals (dinner & breakfast)</p>
                            <p>☑️ Roundtrip Transportation (Dvo-T'Boli v.v) or (CDO-T'Boli v.v) </p>
                            <p>☑️ Ground Rental fee</p>
                            <p>☑️ Souviner sticker</p>
                        </div>
                        <a href="bookings.php" class="btn btn-primary mt-3 scale-in delay-2">Book Your Adventure</a>
                    </div>
                </div>
            </div>
            <!-- Video Frame -->
            <div class="col-md-8">
                <div class="embed-responsive embed-responsive-16by9 scale-in delay-3" style="width: 100%; height: 700px;">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/gYE59nkBTUQ"
                        allowfullscreen style="width: 100%; height: 100%;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: center; align-items: center;" class="fade-in delay-1">
        <h1>Tour Organizer/Guides</h1>
    </div>

    <div class="logo-carousel-section fade-in delay-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner d-flex justify-content-between align-items-center">
                        <div class="single-logo-item scale-in delay-1">
                            <a href="https://www.facebook.com/iTREKKERSph" target="_blank">
                                <img src="../assets/img/company-logos/iT.jpg" style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <div class="single-logo-item scale-in delay-2">
                            <a href="https://www.facebook.com/profile.php?id=61561342811020" target="_blank">
                                <img src="../assets/img/company-logos/logo.jpg" style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <div class="single-logo-item scale-in delay-3">
                            <a href="https://www.facebook.com/AngtadmountaineersMountainTrip" target="_blank">
                                <img src="../assets/img/company-logos/EXP.jpg" style="border-radius: 50%;" alt="">
                            </a>
                        </div>
                        <div class="single-logo-item scale-in delay-2">
                            <a href=" https://www.facebook.com/profile.php?id=61563526834293" target="_blank">
                            <img src="../assets/img/company-logos/PB.jpg" style="border-radius: 50%;" alt="">
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

    <footer style="background-color: #051922;">
        <div class="footer-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 fade-in delay-1">
                        <div class="footer-box about-widget">
                            <h2 class="widget-title">About us</h2>
                            <p>Saka Bukit is your trusted destination for eCommerce and booking services.
                                We offer a seamless shopping experience and easy reservations for various
                                services. Our platform combines convenience, quality, and reliability to
                                serve individuals and businesses across the region. Shop and book with confidence at
                                Saka Bukit.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 fade-in delay-2">
                        <div class="footer-box get-in-touch">
                            <h2 class="widget-title">Get in Touch</h2>
                            <ul>
                                <li> Fortich Street, Barangay 3, Malaybalay City, Bukidnon</li>
                                <li>support@sakabukit.com</li>
                                <li>+00 111 222 3333</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 fade-in delay-3">
                        <div class="footer-box subscribe">
                            <h2 class="widget-title">Subscribe</h2>
                            <p>Subscribe to our mailing list to get the latest updates.</p>
                            <form action="#">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="email" placeholder="Email"
                                        style="flex: 1; height: 40px; padding: 0 10px;">
                                    <button type="submit"
                                        style="height: 40px; display: flex; align-items: center; justify-content: center; padding: 0 15px;">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright text-center fade-in delay-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-12">
                    <p>Copyrights &copy; 2025 - <a href="../user/index.php">SAKA BUKIT</a>, All Rights
                        Reserved.<br>
                        Climb mountains not so the world can see you, but so you can see the world
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Animate on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.fade-in, .scale-in');
            const windowHeight = window.innerHeight;
            elements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < windowHeight - 50) {
                    el.style.animationPlayState = 'running';
                }
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            // Set animation-play-state to paused initially
            document.querySelectorAll('.fade-in, .scale-in').forEach(el => {
                el.style.animationPlayState = 'paused';
            });
            animateOnScroll();
        });
        window.addEventListener('scroll', animateOnScroll);

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