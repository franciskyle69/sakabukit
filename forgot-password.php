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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            text-align: center;
        }

        .card img {
            width: 80%;
            max-width: 150px;
            margin: 20px 0;
        }

        h3 {
            color: #4CAF50;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .input-container {
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        .input-container input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            font-size: 14px;
            margin-top: 10px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <img src="assets/images/logo.png" alt="This is the logo">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <h3>Enter your email to continue</h3>
        <form action="forgot-password.php" method="POST">
            <div class="input-container">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit">Send Code</button>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>
</body>
</html>
