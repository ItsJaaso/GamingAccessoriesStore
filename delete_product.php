<?php

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productId = (int) $_POST['product_id'];

    $stmt = $pdo->prepare("
        DELETE FROM products
        WHERE ProductID = ?
    ");

    $stmt->execute([$productId]);
}

header('Location: manage_products.php');
exit;

?>