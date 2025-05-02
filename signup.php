<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign up</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card {
            text-align: center;
        }

        .card img {
            width: 100px;
            margin-bottom: 20px;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
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
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        p {
            font-size: 14px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <img src="assets/images/logo.png" alt="This is the logo">
            <h2>Sign up</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <form action="signup_validate.php" method="POST">
                <input type="text" placeholder="First Name" name="firstname" required><br>
                <input type="text" placeholder="Last Name" name="lastname" required><br>
                <input type="text" placeholder="Username" name="username" required><br>
                <input type="email" placeholder="Email" name="email" required><br>
                <input type="password" placeholder="Password" name="password" required><br>
                <input type="password" placeholder="Confirm Password" name="confirm_password" required><br>
                <button type="submit">Sign up</button>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>