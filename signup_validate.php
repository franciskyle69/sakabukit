<?php
session_start();
require 'includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: signup.php');
        exit();
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Username already exists.";
        header('Location: signup.php');
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email already exists.";
        header('Location: signup.php');
        exit();
    }

    // Generate verification code
    $verification_code = rand(100000, 999999);

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store the user temporarily in session
    $_SESSION['signup_data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'code' => $verification_code,
        'code_sent_time' => time() // Save the current timestamp
    ];

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'franciskyle6969@gmail.com'; // your gmail
        $mail->Password = 'xgeu eemb lhnt ocha'; // your app password (NEVER your real gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('franciskyle6969@gmail.com', 'Francis Kyle Arranchado');
        $mail->addAddress($email, $firstname . ' ' . $lastname);

        $mail->isHTML(true);
        $mail->Subject = 'Signup Verification Code';
        $mail->Body = "
            <div style='background-color: #f4f4f4; padding: 30px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 20px;'>
                    <h2 style='color: #333333;'>Verify Your Saka Buk-IT Account.</h2>
                    <p style='font-size: 16px; color: #555555;'>Hello,</p>
                    <p style='font-size: 16px; color: #555555;'>Use the code below to verify your email and complete registration:</p>
                    <div style='margin: 20px 0; padding: 15px; background-color: #f0f0f0; border-left: 5px solid #4CAF50; font-size: 24px; font-weight: bold; color: #4CAF50; text-align: center;'>
                        $verification_code
                    </div>
                    <p style='font-size: 14px; color: #888888;'>If you did not sign up for an account, please ignore this email.</p>
                </div>
            </div>
        ";
        $mail->AltBody = "Hello, use this code to verify your signup: {$verification_code}";

        $mail->send();

        $_SESSION['success'] = "A verification code has been sent to your email.";
        header('Location: verify_signup.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send verification code. Mailer Error: {$mail->ErrorInfo}";
        header('Location: signup.php');
        exit();
    }
} else {
    header('Location: signup.php');
    exit();
}
?>