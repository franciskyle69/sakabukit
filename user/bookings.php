<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Adventure - Saka Buk IT</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="assets/css/responsive.css">




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

            <form action="/saka-bukit/user/process_booking.php" method="POST" class="booking-form">
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

    <footer>
    <div class="container text-center mt-5">
            <p class="mb-0" style="color: orange;">&copy; <?= date('Y'); ?> Saka Buk IT. All rights reserved.</p>
            <small>Climb mountains not so the world can see you, but so you can see the world.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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