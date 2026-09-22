<?php
session_start();

require_once "config.php";

// Create the shopping cart if it does not already exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Load products from the MySQL database
$stmt = $pdo->query("SELECT * FROM products ORDER BY ProductID");
$productRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert database products into the same format used by the website
$products = [];

foreach ($productRows as $row) {
    $products[$row['ProductID']] = [
        'name' => $row['ProductName'],
        'description' => $row['ProductDescription'],
        'cost' => $row['ProductCost']
    ];
}

// Handle cart changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int) $_POST['product_id'];
    $action = $_POST['action'];

    // Make sure the product actually exists
    if (isset($products[$productId])) {

        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        if ($action === 'add' || $action === 'increase') {
            $_SESSION['cart'][$productId]++;
        }

        if ($action === 'decrease' && $_SESSION['cart'][$productId] > 0) {
            $_SESSION['cart'][$productId]--;
        }

        if ($action === 'remove') {
            $_SESSION['cart'][$productId] = 0;
        }
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Accessories Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>Gaming Accessories Store</h1>
    <p>Upgrade Your Setup</p>

    <a href="cart.php" class="cart-link">
        View Shopping Cart
    </a>

    <a href="manage_products.php" class="cart-link">
        Manage Products
    </a>
</header>

<main class="catalog">

    <h2>Product Catalog</h2>

    <?php foreach ($products as $id => $product): ?>

        <?php
        $quantity = $_SESSION['cart'][$id] ?? 0;
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
                <strong>Description:</strong>
                <?php echo htmlspecialchars($product['description']); ?>
            </p>

            <p>
                <strong>Price:</strong>
                $<?php echo number_format($product['cost'], 2); ?>
            </p>

            <p>
                <strong>Quantity in Cart:</strong>
                <?php echo $quantity; ?>
            </p>

            <form method="post">

                <input type="hidden"
                       name="product_id"
                       value="<?php echo $id; ?>">

                <button type="submit" name="action" value="add">
                    Add to Cart
                </button>

                <button type="submit" name="action" value="increase">
                    +
                </button>

                <button type="submit" name="action" value="decrease">
                    -
                </button>

                <button type="submit" name="action" value="remove">
                    Remove
                </button>

            </form>

        </div>

    <?php endforeach; ?>

</main>

</body>
</html>