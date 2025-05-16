<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// If no role is set, treat as guest
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

$role = $_SESSION['role'];

// Allow guests only on these pages
$guest_allowed = [
    'index.php',
    'login.php',
    'signup.php',
    'signup_validate.php',
    'verify_signup.php',
    'forgot-password.php',
    'reset_password.php',
    'resend_code.php',
    'enter_code.php',
    'login_validate.php',
    'user/index.php'
];

$current_file = basename($_SERVER['PHP_SELF']);

// Redirect guests if accessing a restricted page
if ($role === 'guest' && !in_array($current_file, $guest_allowed)) {
    header("Location: /user/index.php");
    exit();
}

// Optional: redirect to default dashboard ONLY if currently on login.php or index.php
// Prevents looping behavior when already navigating internally
if ($current_file === 'login.php' || $current_file === 'index.php') {
    if ($role === 'admin' && $current_file !== 'index.php') {
        header("Location: ../admin/index.php");
        exit();
    } elseif ($role === 'user' && $current_file !== 'index.php') {
        header("Location: ../user/index.php");
        exit();
    }
}
?>