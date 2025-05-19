<?php
session_start();

require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to make a booking.";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_id = $_SESSION['user_id'];
    $destination = $_POST['destination'];
    $date = $_POST['date'];
    $participants = $_POST['participants'];
    $package = $_POST['package'];
    $special_requests = $_POST['special_requests'];
    $status = 'pending'; // Default status
    $created_at = date('Y-m-d H:i:s');

    // Validate date
    if (strtotime($date) < strtotime('today')) {
        $_SESSION['error_message'] = "Please select a future date.";
        header("Location: bookings.php");
        exit();
    }

    // Validate participants
    if ($participants < 1 || $participants > 20) {
        $_SESSION['error_message'] = "Number of participants must be between 1 and 20.";
        header("Location: bookings.php");
        exit();
    }

    try {
        // Insert booking into database
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, destination, date, participants, package, special_requests, status, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([$user_id, $destination, $date, $participants, $package, $special_requests, $status, $created_at]);

        $_SESSION['success_message'] = "Your booking has been submitted successfully! We will contact you shortly.";
        header("Location: bookings.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "An error occurred while processing your booking. Please try again later.";
        header("Location: bookings.php");
        exit();
    }
} else {
    header("Location: bookings.php");
    exit();
} 