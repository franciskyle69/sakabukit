<?php
session_start();
require 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $code = $_POST['code'];
    $email = $_SESSION['email'];

    if(!isset($_SESSION['email'])){
        $_SESSION['error'] = "No email found: Please try again. :>";
        header('Location: forgot-password.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT reset_code FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        if($code === $user['reset_code']){
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_code_verified'] = true;
            header('Location: reset_password.php');
            exit();
        } else {
            $_SESSION['error'] = "Invalid code. Please try again.";
        }
    } else {
        $_SESSION['error'] = "No user found with that email";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter Code</title>
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
                <h2 class="custom-heading">Enter Code</h2>
                <p class="text-muted mb-4">Enter the code sent to your email</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                <form action="enter_code.php" method="POST">
                    <div class="custom-input-container">
                        <input type="text" placeholder="Enter code" name="code" class="form-control" required>
                    </div>
                    <button type="submit" class="btn custom-login-btn mb-2">
                        <i class="mdi mdi-check me-2"></i> Submit
                    </button><br>
                    <p class="mt-3 mb-0">Don't have a code? <a href="forgot-password.php">Request a new code</a></p>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
