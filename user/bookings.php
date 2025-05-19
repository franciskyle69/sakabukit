<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Adventure - Saka Buk IT</title>
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

    <main class="container mt-4 mb-5">
        <div class="content p-4 fade-in">
            <div class="text-center mb-5">
                <h2 class="display-4 mb-3">Book Your Adventure</h2>
                <p class="lead text-muted">Experience the beauty of nature with our guided tours</p>
            </div>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success fade-in">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger fade-in">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="process_booking.php" method="POST" class="booking-form">
            <form action="../user/process_booking.php" method="POST" class="booking-form">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="destination" class="form-label">
                            <i class="fas fa-mountain me-2"></i>Destination
                        </label>
                        <select class="form-select" id="destination" name="destination" required>
                            <option value="">Select a destination</option>
                            <option value="kulago">Mount Kulago</option>
                            <option value="holon">Lake Holon</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="date" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Date
                        </label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="participants" class="form-label">
                            <i class="fas fa-users me-2"></i>Number of Participants
                        </label>
                        <input type="number" class="form-control" id="participants" name="participants" min="1" max="20" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="package" class="form-label">
                            <i class="fas fa-box-open me-2"></i>Package Type
                        </label>
                        <select class="form-select" id="package" name="package" required>
                            <option value="">Select a package</option>
                            <option value="basic">Basic Package</option>
                            <option value="premium">Premium Package</option>
                            <option value="deluxe">Deluxe Package</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="special_requests" class="form-label">
                        <i class="fas fa-comment-alt me-2"></i>Special Requests
                    </label>
                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" 
                              placeholder="Any special requirements or requests?"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Submit Booking
                    </button>
                </div>
            </form>

            <!-- Package Information Cards -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card package-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-hiking fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Basic Package</h5>
                            <p class="text-muted">Perfect for budget-conscious adventurers</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Professional Guide</li>
                                <li><i class="fas fa-check text-success me-2"></i>Basic Meals</li>
                                <li><i class="fas fa-check text-success me-2"></i>Transportation</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card package-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-campground fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Premium Package</h5>
                            <p class="text-muted">Enhanced experience for serious hikers</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Professional Guide</li>
                                <li><i class="fas fa-check text-success me-2"></i>Premium Meals</li>
                                <li><i class="fas fa-check text-success me-2"></i>Equipment Rental</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card package-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-crown fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Deluxe Package</h5>
                            <p class="text-muted">Luxury experience for the ultimate adventure</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Professional Guide</li>
                                <li><i class="fas fa-check text-success me-2"></i>Premium Meals</li>
                                <li><i class="fas fa-check text-success me-2"></i>Photography Service</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer style="background-color: #051922;">
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
        <div class="copyright text-center">
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

    <script>
        // Set minimum date to today
        document.getElementById('date').min = new Date().toISOString().split('T')[0];

        // Add hover effects to package cards
        document.querySelectorAll('.package-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html> 