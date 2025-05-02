<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        $stmt->execute([$quantity, $productId]);
    }

    header("Location: products.php");
    exit;
}
?>
