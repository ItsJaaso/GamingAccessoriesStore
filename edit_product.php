<?php

require_once "config.php";

if (!isset($_GET['id'])) {
    header('Location: manage_products.php');
    exit;
}

$productId = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * FROM products
    WHERE ProductID = ?
");

$stmt->execute([$productId]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $cost = trim($_POST['cost']);

    if ($name !== "" && $description !== "" && is_numeric($cost)) {

        $stmt = $pdo->prepare("
            UPDATE products
            SET ProductName = ?,
                ProductDescription = ?,
                ProductCost = ?
            WHERE ProductID = ?
        ");

        $stmt->execute([
            $name,
            $description,
            $cost,
            $productId
        ]);

        $message = "Product updated successfully!";

        $stmt = $pdo->prepare("
            SELECT * FROM products
            WHERE ProductID = ?
        ");

        $stmt->execute([$productId]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>Edit Product</h1>
    <p>Gaming Accessories Store</p>
</header>

<main class="catalog">

    <div class="product">

        <?php if ($message !== ""): ?>

            <p>
                <strong>
                    <?php echo htmlspecialchars($message); ?>
                </strong>
            </p>

        <?php endif; ?>

        <form method="post">

            <p>
                <label>
                    <strong>Product Name:</strong>
                </label>
                <br>

                <input
                    type="text"
                    name="name"
                    value="<?php echo htmlspecialchars($product['ProductName']); ?>"
                    required>
            </p>

            <p>
                <label>
                    <strong>Description:</strong>
                </label>
                <br>

                <input
                    type="text"
                    name="description"
                    value="<?php echo htmlspecialchars($product['ProductDescription']); ?>"
                    required>
            </p>

            <p>
                <label>
                    <strong>Product Cost:</strong>
                </label>
                <br>

                <input
                    type="number"
                    name="cost"
                    step="0.01"
                    min="0"
                    value="<?php echo $product['ProductCost']; ?>"
                    required>
            </p>

            <button type="submit">
                Update Product
            </button>

        </form>

    </div>

    <a href="manage_products.php" class="cart-link">
        Back to Manage Products
    </a>

</main>

</body>
</html>