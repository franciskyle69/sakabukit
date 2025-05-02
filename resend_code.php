<?php
session_start();
require 'includes/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (!isset($_SESSION['signup_data'])) {
    $_SESSION['error'] = "Session expired. Please signup again.";
    header('Location: signup.php');
    exit();
}

$email = $_SESSION['signup_data']['email'];
$firstname = $_SESSION['signup_data']['firstname'];
$lastname = $_SESSION['signup_data']['lastname'];

// Generate new code
$new_code = rand(100000, 999999);

// Update session data
$_SESSION['signup_data']['code'] = $new_code;
$_SESSION['signup_data']['code_sent_time'] = time();

// Send email again
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'franciskyle6969@gmail.com';
    $mail->Password = 'xgeu eemb lhnt ocha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('franciskyle6969@gmail.com', 'Francis Kyle Arranchado');
    $mail->addAddress($email, $firstname . ' ' . $lastname);

    $mail->isHTML(true);
    $mail->Subject = 'Resent Signup Verification Code';
    $mail->Body = "
        <div style='background-color: #f4f4f4; padding: 30px; font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 20px;'>
                <h2 style='color: #333333;'>New Verification Code</h2>
                <p style='font-size: 16px; color: #555555;'>Hello,</p>
                <p style='font-size: 16px; color: #555555;'>Here is your new verification code:</p>
                <div style='margin: 20px 0; padding: 15px; background-color: #f0f0f0; border-left: 5px solid #4CAF50; font-size: 24px; font-weight: bold; color: #4CAF50; text-align: center;'>
                    $new_code
                </div>
            </div>
        </div>
    ";

    $mail->AltBody = "Hello, your new verification code is: {$new_code}";

    $mail->send();

    $_SESSION['success'] = "A new verification code has been sent.";
    header('Location: verify_signup.php');
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to resend code. Mailer Error: {$mail->ErrorInfo}";
    header('Location: verify_signup.php');
    exit();
}
?>