<?php
require_once "config.php";

// Get all products from the database
$stmt = $pdo->query("SELECT * FROM products ORDER BY ProductID");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Products</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>Manage Products</h1>
    <p>Gaming Accessories Store</p>

    <a href="index.php" class="cart-link">
        Return to Store
    </a>

    <a href="add_product.php" class="cart-link">
        Add New Product
    </a>
</header>

<main class="catalog">

    <h2>Current Products</h2>

    <?php foreach ($products as $product): ?>

        <div class="product">

            <h3>
                <?php echo htmlspecialchars($product['ProductName']); ?>
            </h3>

            <p>
                <strong>Product ID:</strong>
                <?php echo $product['ProductID']; ?>
            </p>

            <p>
                <strong>Description:</strong>
                <?php echo htmlspecialchars($product['ProductDescription']); ?>
            </p>

            <p>
                <strong>Price:</strong>
                $<?php echo number_format($product['ProductCost'], 2); ?>
            </p>

            <a href="edit_product.php?id=<?php echo $product['ProductID']; ?>" class="cart-link">
                Edit Product
            </a>

            <form method="post" action="delete_product.php"
                onsubmit="return confirm('Are you sure you want to delete this product?');">

            <input
                type="hidden"
                name="product_id"
                value="<?php echo $product['ProductID']; ?>">

            <button type="submit">
                Delete Product
            </button>

</form>

        </div>

    <?php endforeach; ?>

</main>

</body>
</html>