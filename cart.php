<?php
session_start();

$products = [
    1 => [
        'name' => 'Mechanical Gaming Keyboard',
        'cost' => 79.99
    ],
    2 => [
        'name' => 'Gaming Mouse',
        'cost' => 49.99
    ],
    3 => [
        'name' => 'Wireless Gaming Headset',
        'cost' => 89.99
    ],
    4 => [
        'name' => 'RGB Mouse Pad',
        'cost' => 29.99
    ],
    5 => [
        'name' => 'USB Gaming Controller',
        'cost' => 39.99
    ]
];

$subtotal = 0;
$totalItems = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>Your Shopping Cart</h1>
    <p>Review Your Order</p>
</header>

<main class="catalog">

    <?php
    $cartHasItems = false;

    foreach ($products as $id => $product):

        $quantity = $_SESSION['cart'][$id] ?? 0;

        if ($quantity > 0):

            $cartHasItems = true;
            $productTotal = $product['cost'] * $quantity;

            $subtotal += $productTotal;
            $totalItems += $quantity;
    ?>

        <div class="product">

            <h3>
                <?php echo htmlspecialchars($product['name']); ?>
            </h3>

            <p>
                <strong>Product ID:</strong>
                <?php echo $id; ?>
            </p>

            <p>
                <strong>Quantity Ordered:</strong>
                <?php echo $quantity; ?>
            </p>

            <p>
                <strong>Product Cost:</strong>
                $<?php echo number_format($product['cost'], 2); ?>
            </p>

            <p>
                <strong>Product Total:</strong>
                $<?php echo number_format($productTotal, 2); ?>
            </p>

        </div>

    <?php
        endif;
    endforeach;
    ?>

    <?php if ($cartHasItems): ?>

        <?php
        $tax = $subtotal * 0.05;
        $shipping = $subtotal * 0.10;
        $orderTotal = $subtotal + $tax + $shipping;
        ?>

        <div class="product">

            <h2>Order Summary</h2>

            <p>
                <strong>Total Items Ordered:</strong>
                <?php echo $totalItems; ?>
            </p>

            <p>
                <strong>Subtotal:</strong>
                $<?php echo number_format($subtotal, 2); ?>
            </p>

            <p>
                <strong>Tax (5%):</strong>
                $<?php echo number_format($tax, 2); ?>
            </p>

            <p>
                <strong>Shipping & Handling (10%):</strong>
                $<?php echo number_format($shipping, 2); ?>
            </p>

            <p>
                <strong>Order Total:</strong>
                $<?php echo number_format($orderTotal, 2); ?>
            </p>

        </div>

        <a href="index.php" class="cart-link">
            Continue Shopping
        </a>

        <a href="checkout.php" class="cart-link">
            Check Out
        </a>

    <?php else: ?>

        <div class="product">
            <h2>Your cart is empty.</h2>

            <p>
                Add products from the catalog to begin your order.
            </p>
        </div>

        <a href="index.php" class="cart-link">
            Return to Catalog
        </a>

    <?php endif; ?>

</main>

</body>
</html>