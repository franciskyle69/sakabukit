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

        h2 {
            color: #4CAF50;
            font-size: 26px;
            margin-bottom: 20px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        input[type="password"]:focus {
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
            <h2>Reset Password</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form action="reset_password.php" method="POST">
                <input type="password" placeholder="New Password" name="new_password" required><br>
                <input type="password" placeholder="Confirm Password" name="confirm_password" required><br>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>

</html>