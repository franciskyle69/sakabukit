<?php
require_once '../vendor/autoload.php';
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
require_once '../includes/db.php';

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT']);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $email = $userInfo->email;

        // Check if user exists in your database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Auto-register Google user
            $nameParts = explode(" ", $userInfo->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
            $stmt->execute([
                $firstName,
                $lastName,
                $email, // Using email as username fallback
                $email
            ]);

            $userId = $pdo->lastInsertId();

            // Fetch the newly inserted user
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }


        // Set session values
        $_SESSION['user_type'] = 'google';
        $_SESSION['user_name'] = $userInfo->name;
        $_SESSION['full_name'] = $userInfo->name; // 👈 Needed for navbar
        $_SESSION['user_email'] = $email;
        $_SESSION['user_image'] = $userInfo->picture;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id']; // Optional: user ID
        $_SESSION['success'] = 'Login with Google successful!';

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../user/index.php');
        }
        exit();

    } else {
        $_SESSION['error'] = 'Login failed!';
        header('Location: ../login.php');
        exit();
    }

} else {
    $_SESSION['error'] = 'Invalid login attempt!';
    header('Location: ../login.php');
    exit();
}
