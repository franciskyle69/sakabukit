<?php
require '../vendor/autoload.php';
include '../includes/auth_check.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

header('Content-Type: application/json');

$domain = 'http://localhost/sakabukit/user/';

$lineItems = [];

foreach ($_SESSION['cart'] as $item) {
    $lineItems[] = [
        'price_data' => [
            'currency' => 'php',
            'product_data' => ['name' => $item['name']],
            'unit_amount' => intval($item['price'] * 100),
        ],
        'quantity' => $item['quantity'],
    ];
}

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $lineItems,
    'mode' => 'payment',
    'success_url' => $domain . 'checkout_success.php',
    'cancel_url' => $domain . 'cancel.html',
]);

echo json_encode(['id' => $session->id]);
