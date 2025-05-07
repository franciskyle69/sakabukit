<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/auth_check.php';
include '../includes/db.php';

require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Get cart items from database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price 
    FROM cart_items c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Check if cart is empty
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// Stripe line items format
$lineItems = [];
foreach ($cart_items as $item) {
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
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Checkout</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($cart_items as $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₱<?= number_format($item['price'], 2) ?></td>
                                    <td>₱<?= number_format($subtotal, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-secondary">
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td><strong>₱<?= number_format($total, 2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment</h5>
                        <p class="card-text">Click the button below to proceed with payment.</p>
                        <button id="checkout-button" class="btn btn-primary w-100">Proceed to Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var stripe = Stripe('<?= $_ENV['STRIPE_PUBLISHABLE_KEY'] ?>');
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