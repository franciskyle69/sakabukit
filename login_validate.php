<?php
session_start();
require 'includes/db.php';

$recaptchaSecret = '6Ld6kyQrAAAAAKeE4q0x4s0fISlWohZ4BBz7OXWp'; // Secret Key

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SESSION['login_attempts'] === 0 || $_SESSION['login_attempts'] >= 3) {
        if (empty($_POST['g-recaptcha-response'])) {
            $_SESSION['error'] = "Please complete the reCAPTCHA.";
            header("Location: login.php");
            exit();
        }

        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($recaptchaSecret) . '&response=' . urlencode($recaptchaResponse);

        $verifyResponse = file_get_contents($verifyUrl);
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            $_SESSION['error'] = "reCAPTCHA verification failed. Please try again.";
            header("Location: login.php");
            exit();
        }
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['login_attempts'] = 0; // ✅ Reset attempts after success
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['firstname'] . ' ' . $user['lastname'];
        $_SESSION['user_id'] = $user['id'];

        if ($user['role'] === 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit();
    } else {
        // Failed login
        $_SESSION['login_attempts']++; // Increase failed count
        $_SESSION['error'] = "Invalid Username or Password.";
        header("Location: login.php");
        exit();
    }
}
?>