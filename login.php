<?php session_start();

$siteKey = '6Ld6kyQrAAAAAMBiCoKtNOCZpZ5J-UgTDbPjZ_GM';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
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
            <img src="assets/images/logo.png" alt="This is the logo"
                style="width: 200px; height: 200px; display: block; margin: 0 auto;">

            <div class="card-body">

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>a
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                <h2 class="custom-heading">Login</h2>
                <form action="login_validate.php" method="POST">
                    <div class="custom-input-container">
                        <input type="text" class="form-control mb-2" placeholder="Username" name="username" required>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="mb-3 d-flex justify-content-center w-100">
                            <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($siteKey) ?>"></div>
                        </div>


                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"
                            style="font-size: 1rem;">
                            <i class="mdi mdi-login me-2"></i> Login
                        </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-3" style="font-size:13px">Or</p>
                    <a href="googleAuth/google-login.php"
                        class="btn btn-light border d-flex align-items-center justify-content-center gap-3 py-2 px-3 shadow-sm"
                        style="font-weight: 500; transition: background-color 0.3s;">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg"
                            alt="Google Icon" width="22" height="22">
                        <span>Sign in with Google</span>
                    </a>
                </div>

                <hr class="custom-divider">
                <p><a href="forgot-password.php">Forgot password?</a></p>
                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>