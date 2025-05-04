<?php
session_start();
require 'includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $reset_code = rand(100000, 999999);

        $update = $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
        $update->execute([$reset_code, $email]);

        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host  = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'franciskyle6969@gmail.com';
            $mail->Password = 'xgeu eemb lhnt ocha'; // Be careful with this!
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('franciskyle6969@gmail.com', 'Francis Kyle Arranchado');
            $mail->addAddress($email, 'User');

            $mail->isHTML(true);
            $mail->Subject = 'Password Verification Code';
            $mail->Body = "
                <div style='background-color: #f4f4f4; padding: 30px; font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 20px;'>
                        <h2 style='color: #333333;'>Password Reset Request</h2>
                        <p style='font-size: 16px; color: #555555;'>Hello,</p>
                        <p style='font-size: 16px; color: #555555;'>Use the code below to reset your password:</p>
                        <div style='margin: 20px 0; padding: 15px; background-color: #f0f0f0; border-left: 5px solid #4CAF50; font-size: 24px; font-weight: bold; color: #4CAF50; text-align: center;'>
                            $reset_code
                        </div>
                        <p style='font-size: 14px; color: #888888;'>If you did not request a password reset, you can safely ignore this email.</p>
                    </div>
                </div>";
            $mail->AltBody = "Hello user, use this code to reset your password: {$reset_code}";

            $mail->send();

            $_SESSION['success'] = "A verification code has been sent to your email.";
            header('Location: enter_code.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Failed to send verification code. Please try again.";
            header('Location: forgot-password.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that email.";
        header('Location: forgot-password.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
                <h2 class="custom-heading">Forgot Password</h2>
                <p class="text-muted mb-4">Enter your email to reset your password</p>
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
                <form action="forgot-password.php" method="POST">
                    <div class="custom-input-container">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn custom-login-btn mb-2">
                        <i class="mdi mdi-email me-2"></i> Send Reset Link
                    </button>
                    <p class="mt-3 mb-0"><a href="login.php">Back to Login</a></p>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
