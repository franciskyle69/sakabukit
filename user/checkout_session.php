<?php
require '../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);



header('Content-Type: application/json');

$domain = 'http://localhost/saka-bukit/user/'; // Adjust based on your local setup

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'usd', // ✅ peso
                'product_data' => ['name' => $item['name']],
                'unit_amount' => intval($item['price'] * 100), // ✅ convert ₱ to centavos
            ],

            'quantity' => 'product_quantity',
        ]
    ],
    'mode' => 'payment', // ✅ payment
    'success_url' => $domain . 'checkout_success.php',
    'cancel_url' => $domain . 'cancel.html',
]);

echo json_encode(['id' => $session->id]);
