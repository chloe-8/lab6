<?php
session_start();
include("header.php");
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Get product price
    $stmt = $conn->prepare("SELECT price, quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product && $quantity > 0 && $quantity <= $product['quantity']) {
        $total = $product['price'] * $quantity;

        // Insert sale
        $insert = $conn->prepare("INSERT INTO sales (product_id, quantity, total_price) VALUES (?, ?, ?)");
        $insert->bind_param("iid", $productId, $quantity, $total);
        $insert->execute();

        // Decrease stock
        $update = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $update->bind_param("ii", $quantity, $productId);
        $update->execute();

        header("Location: manage_sales.php");
        exit;
    } else {
        echo "Invalid quantity or product.";
    }
}
?>
