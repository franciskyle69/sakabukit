<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign up</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
</head>

<body>
    <div class="container custom-container">
        <div class="card custom-card">
            <img src="assets/images/logo.png" alt="Logo" class="custom-logo">
            <div class="card-body">
                <h2 class="custom-heading">Create Account</h2>
                <p class="text-muted mb-4">Sign up to get started</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <form action="signup_validate.php" method="POST">
                    <div class="custom-input-container">
                        <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="custom-input-container">
                        <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                    </div>
                    <div class="custom-input-container">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="custom-input-container">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="custom-input-container">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="custom-input-container">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn custom-login-btn mb-2">
                        <i class="mdi mdi-account-plus me-2"></i> Sign up
                    </button>
                    <p class="mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>