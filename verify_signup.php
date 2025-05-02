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
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 450px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .resend-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .resend-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .resend-link a:hover {
            text-decoration: underline;
        }

        .timer {
            margin-top: 15px;
            font-size: 14px;
            color: #888;
        }

        .alert {
            margin-top: 10px;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
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
        <h2>Email Verification</h2>

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

        <form method="POST" action="verify_signup.php">
            <input type="text" name="code" placeholder="Enter Verification Code" required>
            <button type="submit">Verify</button>
        </form>

        <div class="resend-link">
            Didn't receive the code? <a href="resend_code.php">Resend Code</a>
        </div>

        <div class="timer">
            Code expires in <span id="countdown">10:00</span> minutes
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

</body>

</html>