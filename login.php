<?php

require_once 'includes/auth_check.php';
$siteKey = '6Ld6kyQrAAAAAMBiCoKtNOCZpZ5J-UgTDbPjZ_GM';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url("assets/images/background.jpg");
            /* <-- your image path */
            background-size: cover;
            /* make it fill the screen */
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
        }

        .custom-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .custom-card {
            text-align: center;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px 20px;
            background-color: rgb(255, 255, 255);
        }

        .custom-logo {
            width: 100%;
            max-width: 120px;
            margin: 0 auto 20px auto;
            display: block;
        }

        .custom-heading {
            color: #4CAF50;
            font-size: 26px;
            margin-bottom: 20px;
        }

        .custom-input-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        .custom-input-container input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .custom-login-btn {
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .custom-google-btn {
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #DB4437 !important;
            color: white;
        }

        .custom-google-btn:hover {
            background-color: #c1351d !important;
            color: white;
        }

        .custom-divider {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        .alert {
            font-size: 14px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container custom-container">
        <div class="card custom-card">
            <img src="assets/images/logo.png" alt="Logo" class="custom-logo">

            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <h2 class="custom-heading">Welcome Back</h2>
                <p class="text-muted mb-4">Please sign in to continue</p>

                <form action="login_validate.php" method="POST">
                    <div class="custom-input-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="mdi mdi-account"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Username" name="username" required>
                        </div>
                    </div>

                    <div class="custom-input-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="mdi mdi-lock"></i>
                            </span>
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                    </div>

                    <div class="mb-3 d-flex justify-content-center w-100">
                        <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($siteKey) ?>"></div>
                    </div>

                    <button type="submit" class="btn custom-login-btn">
                        <i class="mdi mdi-login me-2"></i> Sign In
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-3 text-muted">Or continue with</p>
                    <a href="googleAuth/google-login.php" class="btn custom-google-btn">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg"
                            alt="Google Icon" width="20" height="20">
                        <span>Google</span>
                    </a>
                </div>

                <hr class="custom-divider">

                <div class="text-center">
                    <p class="mb-2">
                        <a href="forgot-password.php" class="text-decoration-none">
                            <i class="mdi mdi-lock-reset me-1"></i> Forgot password?
                        </a>
                    </p>
                    <p class="mb-0">
                        Don't have an account?
                        <a href="signup.php" class="text-decoration-none">
                            <i class="mdi mdi-account-plus me-1"></i> Sign up
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>