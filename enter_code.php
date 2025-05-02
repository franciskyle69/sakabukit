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
    <style>
        /* Base Styles */
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

        h1 {
            font-size: 22px;
            color: #4CAF50;
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

        /* Alert Boxes */
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
            <h1>Enter Code</h1>

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

            <form action="enter_code.php" method="POST">
                <div class="input-container">
                    <input type="text" placeholder="Enter code" name="code" required>
                </div>

                <button type="submit">Submit</button><br>
                <p>Don't have a code? <a href="forgot-password.php">Request a new code</a></p>
            </form>
        </div>
    </div>
</body>
</html>
