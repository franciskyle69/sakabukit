<?php include '../includes/navbar.php';
include '../includes/auth_check.php';
include '../includes/db.php';

// Get current user info
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
} else {
    $user = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../styles/styles.css">


	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="../assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="../assets/css/owl.carousel.css">
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
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

</head>

<body>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4 text-center">Update Profile</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info"><?= $_SESSION['message'];
                    unset($_SESSION['message']); ?></div>
                <?php endif; ?>
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-3">
                        <img id="profilePreview" src="<?= htmlspecialchars($user['profile_photo'] ?? '../assets/images/default-profile.png') ?>" alt="Profile Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control" id="profilePhotoInput" onchange="previewProfilePhoto(event)">
                    </div>
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</body>
<footer style>
    <div class="footer-area" >
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">About us</h2>
                        <p style="text-align: justify;">Saka Bukit is your trusted destination for eCommerce and booking services. We offer a seamless shopping experience and easy reservations for various services. Our platform combines convenience, quality, and reliability to serve individuals and businesses across the region. Shop and book with confidence at Saka Bukit.</p>
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
						<form action="index.html">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="email" placeholder="Email" style="flex: 1; height: 40px;">
                                <button type="submit" style="height: 40px;"><i class="fas fa-paper-plane"></i></button>
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

</html>