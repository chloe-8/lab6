<?php

include("database.php");


if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $conn->query("DELETE FROM products WHERE id = $id");
        $_SESSION['delete_success'] = "Product deleted successfully.";
    }
    header("Location: manage_products.php");
}
?>
