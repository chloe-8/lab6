<?php
session_start();
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied. Admins only.");
}

if (isset($_GET['id'])) {
    $saleId = $_GET['id'];

    // Prepare delete statement
    $deleteStmt = $conn->prepare("DELETE FROM sales WHERE id = ?");
    $deleteStmt->bind_param("i", $saleId);

    if ($deleteStmt->execute()) {
        // Redirect back to sales management page after deletion
        header("Location: manage_sales.php");
        exit;
    } else {
        echo "Error deleting sale.";
    }
} else {
    echo "Sale ID not provided.";
}
?>
