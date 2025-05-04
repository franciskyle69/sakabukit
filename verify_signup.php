<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['signup_data'])) {
    $_SESSION['error'] = "Session expired. Please signup again.";
    header('Location: signup.php');
    exit();
}

// Set code expiration time (seconds)
$codeExpiration = 600; // 10 minutes

// Check if code expired
if (time() - $_SESSION['signup_data']['code_sent_time'] > $codeExpiration) {
    unset($_SESSION['signup_data']); // Clear signup session
    $_SESSION['error'] = "Verification code expired. Please signup again.";
    header('Location: signup.php');
    exit();
}

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_code = trim($_POST['code']);
    $correct_code = $_SESSION['signup_data']['code'];

    if ($user_code == $correct_code) {
        // Verification successful - Insert into database
        $data = $_SESSION['signup_data'];

        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$data['firstname'], $data['lastname'], $data['username'], $data['email'], $data['password']])) {
            unset($_SESSION['signup_data']);
            $_SESSION['success'] = "Your account has been created successfully!";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = "Database error. Please try again.";
            header('Location: signup.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid verification code. Please try again.";
        header('Location: verify_signup.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Verify Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
</head>

<body>
    <div class="container custom-container">
        <div class="card custom-card">
            <img src="assets/images/logo.png" alt="Logo" class="custom-logo">
            <div class="card-body">
                <h2 class="custom-heading">Email Verification</h2>
                <p class="text-muted mb-4">Enter the code sent to your email</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="verify_signup.php">
                    <div class="custom-input-container">
                        <input type="text" name="code" class="form-control" placeholder="Enter Verification Code" required>
                    </div>
                    <button type="submit" class="btn custom-login-btn mb-2">
                        <i class="mdi mdi-check me-2"></i> Verify
                    </button>
                </form>
                <div class="mt-3">
                    <span class="text-muted">Didn't receive the code?</span> <a href="resend_code.php">Resend Code</a>
                </div>
                <div class="mt-2">
                    <span class="text-muted">Code expires in <span id="countdown">10:00</span> minutes</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Countdown timer (10 minutes)
        let seconds = <?= (600 - (time() - $_SESSION['signup_data']['code_sent_time'])) ?>;
        function updateTimer() {
            let minutes = Math.floor(seconds / 60);
            let secs = seconds % 60;
            document.getElementById('countdown').innerHTML =
                (minutes < 10 ? "0" + minutes : minutes) + ":" + (secs < 10 ? "0" + secs : secs);
            if (seconds > 0) {
                seconds--;
                setTimeout(updateTimer, 1000);
            } else {
                document.getElementById('countdown').innerHTML = "EXPIRED";
            }
        }
        updateTimer();
    </script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>