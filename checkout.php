<?php
session_start();

require_once "config.php";

// Make sure the cart exists and contains items
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// Load products from the database
$stmt = $pdo->query("SELECT * FROM products ORDER BY ProductID");
$productRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];

foreach ($productRows as $row) {
    $products[$row['ProductID']] = [
        'name' => $row['ProductName'],
        'cost' => $row['ProductCost']
    ];
}

// Calculate order totals
$subtotal = 0;

foreach ($_SESSION['cart'] as $productId => $quantity) {

    if ($quantity > 0 && isset($products[$productId])) {
        $subtotal += $products[$productId]['cost'] * $quantity;
    }
}

$tax = $subtotal * 0.05;
$shipping = $subtotal * 0.10;
$orderTotal = $subtotal + $tax + $shipping;

try {

    // Start a database transaction
    $pdo->beginTransaction();

    // Save the order
    $orderStmt = $pdo->prepare("
        INSERT INTO orders
        (OrderDate, Subtotal, Tax, Shipping, OrderTotal)
        VALUES
        (NOW(), ?, ?, ?, ?)
    ");

    $orderStmt->execute([
        $subtotal,
        $tax,
        $shipping,
        $orderTotal
    ]);

    // Get the OrderID that MySQL just created
    $orderId = $pdo->lastInsertId();

    // Prepare the OrderItems insert
    $itemStmt = $pdo->prepare("
        INSERT INTO orderitems
        (OrderID, ProductID, Quantity, ProductCost)
        VALUES
        (?, ?, ?, ?)
    ");

    // Save each product from the cart
    foreach ($_SESSION['cart'] as $productId => $quantity) {

        if ($quantity > 0 && isset($products[$productId])) {

            $itemStmt->execute([
                $orderId,
                $productId,
                $quantity,
                $products[$productId]['cost']
            ]);
        }
    }

    // Save everything permanently
    $pdo->commit();

    // Clear the cart only after the order saves successfully
    $_SESSION['cart'] = [];

} catch (PDOException $e) {

    // Undo everything if something fails
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    die("Checkout failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order Complete</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>Order Complete</h1>
    <p>Thank You for Your Purchase!</p>
</header>

<main class="catalog">

    <div class="product">

        <h2>Your order was successfully placed.</h2>

        <p>
            <strong>Order Number:</strong>
            <?php echo $orderId; ?>
        </p>

        <p>
            <strong>Subtotal:</strong>
            $<?php echo number_format($subtotal, 2); ?>
        </p>

        <p>
            <strong>Tax:</strong>
            $<?php echo number_format($tax, 2); ?>
        </p>

        <p>
            <strong>Shipping:</strong>
            $<?php echo number_format($shipping, 2); ?>
        </p>

        <p>
            <strong>Order Total:</strong>
            $<?php echo number_format($orderTotal, 2); ?>
        </p>

    </div>

    <a href="index.php" class="cart-link">
        Return to Store
    </a>

</main>

</body>
</html>