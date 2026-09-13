<?php

// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "gaming_store";

$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>