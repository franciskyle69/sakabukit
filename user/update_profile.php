<?php
session_start();
require '../includes/db.php'; // make sure your DB connection is correct

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Get the user from the session (you may also store user ID or username in session)
$role = $_SESSION['role'];
$old_username = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Validate password match if provided
    if ($password !== '' && $password !== $confirm) {
        $_SESSION['message'] = "New passwords do not match.";
        header("Location: settings.php");
        exit();
    }

    // Check if username is taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND username != ?");
    $stmt->execute([$username, $old_username]);
    if ($stmt->fetch()) {
        $_SESSION['message'] = "Username is already taken.";
        header("Location: settings.php");
        exit();
    }

    // Build update query
    if ($password !== '') {
        $new_hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET username = ?, firstname = ?, lastname = ?, password = ? WHERE username = ?");
        $update->execute([$username, $firstname, $lastname, $new_hashed, $old_username]);
    } else {
        $update = $pdo->prepare("UPDATE users SET username = ?, firstname = ?, lastname = ? WHERE username = ?");
        $update->execute([$username, $firstname, $lastname, $old_username]);
    }

    // Update session username if changed
    $_SESSION['user'] = $username;
    $_SESSION['message'] = "Profile updated successfully.";
    header("Location: settings.php");
    exit();
}
