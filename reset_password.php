<?php
session_start();

require 'includes/db.php'; // Assuming db.php is in the includes directory

if (!isset($_SESSION['email']) || !isset($_SESSION['reset_code_verified']) || !$_SESSION['reset_code_verified']) {
    header('Location: enter_code.php'); // Redirect to login if not verified
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $_SESSION['reset_email']]);

        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code_verified']);

        $_SESSION['success'] = "Password reset successfully. You can now log in.";
        header('Location: login.php'); // Redirect to login page after successful password reset
        exit();
    } else {
        $_SESSION['error'] = "Passwords do not match. Please try again.";

    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
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
                <h2 class="custom-heading">Reset Password</h2>
                <p class="text-muted mb-4">Enter your new password below</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                <form action="reset_password.php" method="POST">
                    <div class="custom-input-container">
                        <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                    </div>
                    <div class="custom-input-container">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn custom-login-btn mb-2">
                        <i class="mdi mdi-lock-reset me-2"></i> Reset Password
                    </button>
                    <p class="mt-3 mb-0"><a href="login.php">Back to Login</a></p>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>