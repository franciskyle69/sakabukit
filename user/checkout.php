<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/auth_check.php';
include '../includes/db.php'; // For future use, if needed

require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Check if cart exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$cart = $_SESSION['cart'];

// Stripe line items format
$lineItems = [];
foreach ($cart as $item) {
    $lineItems[] = [
        'price_data' => [
            'currency' => 'php',
            'product_data' => ['name' => $item['name']],
            'unit_amount' => intval($item['price'] * 100), // price in cents
        ],
        'quantity' => $item['quantity'],
    ];
}

// Handle AJAX request to create Stripe checkout session
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    try {
        $domain = 'http://localhost/saka-bukit/user/'; // change to your domain if live

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $domain . 'checkout_success.php',
            'cancel_url' => $domain . 'cancel.html',
        ]);

        // Return the session ID to redirect
        header('Content-Type: application/json');
        echo json_encode(['id' => $session->id]);
        exit();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Checkout failed: ' . $e->getMessage()]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout | Stripe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .checkout-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .checkout-container h2 {
            margin-bottom: 20px;
        }

        .btn-pay {
            font-size: 1.2rem;
            padding: 12px 25px;
        }
    </style>
</head>

<body>
    <div class="checkout-container">
        <img src="https://stripe.com/img/v3/newsroom/social.png" alt="Stripe" class="img-fluid mb-4" width="120">
        <h2>Complete Your Payment</h2>
        <p class="mb-4">Secure checkout powered by Stripe</p>
        <button id="checkout-button" class="btn btn-primary btn-pay w-100">Pay Now</button>
    </div>

    <script>
        const stripe = Stripe('<?= $_ENV['STRIPE_PUBLISHABLE_KEY'] ?>'); // set in your .env

        document.getElementById('checkout-button').addEventListener('click', function () {
            fetch('checkout.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(session => stripe.redirectToCheckout({ sessionId: session.id }))
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                });
        });

    </script>
</body>

</html>