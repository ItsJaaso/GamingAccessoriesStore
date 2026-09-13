<?php
session_start();

// Clear all items from the cart
$_SESSION['cart'] = [];

// Send the user back to the catalog page
header('Location: index.php');
exit;