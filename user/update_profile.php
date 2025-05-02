<?php
session_start();
require '../includes/db.php'; // make sure your DB connection is correct

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Get the user from the session (you may also store user ID or username in session)
$role = $_SESSION['role'];
$username = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        $_SESSION['message'] = "New passwords do not match.";
        header("Location: settings.php");
        exit();
    }

    // Fetch current password hash from DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password'])) {
        $_SESSION['message'] = "Current password is incorrect.";
        header("Location: settings.php");
        exit();
    }

    // Update with new password
    $new_hashed = password_hash($new, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $update->execute([$new_hashed, $username]);

    $_SESSION['message'] = "Password updated successfully.";
    header("Location: settings.php");
    exit();
}
