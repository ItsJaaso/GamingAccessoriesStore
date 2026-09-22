<?php

require_once "config.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $cost = trim($_POST['cost']);

    if ($name !== "" && $description !== "" && is_numeric($cost)) {

        $stmt = $pdo->prepare("
            INSERT INTO products
            (ProductName, ProductDescription, ProductCost)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $description,
            $cost
        ]);

        $message = "Product added successfully!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>Add Product</h1>
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
                    required>
            </p>

            <button type="submit">
                Add Product
            </button>

        </form>

    </div>

    <a href="manage_products.php" class="cart-link">
        Back to Manage Products
    </a>

</main>

</body>
</html>