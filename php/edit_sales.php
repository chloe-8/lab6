<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

if (isset($_GET['id'])) {
    $saleId = $_GET['id'];

    // Fetch the sale data and product price
    $stmt = $conn->prepare("SELECT sales.*, products.name AS product_name, products.price AS product_price 
                            FROM sales 
                            JOIN products ON sales.product_id = products.id 
                            WHERE sales.id = ?");
    $stmt->bind_param("i", $saleId);
    $stmt->execute();
    $saleData = $stmt->get_result()->fetch_assoc();
    if (!$saleData) {
        die("Sale not found.");
    }
} else {
    die("Sale ID not provided.");
}

// Handle the update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $totalPrice = $saleData['product_price'] * $quantity; // Calculate total price based on product price and quantity

    // Update the sale record with the new quantity and calculated total price
    $updateStmt = $conn->prepare("UPDATE sales SET quantity = ?, total_price = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $updateStmt->bind_param("dii", $quantity, $totalPrice, $saleId);
    if ($updateStmt->execute()) {
        header("Location: manage_sales.php"); // Redirect to the sales management page
        exit;
    } else {
        $error = "Error updating sale.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sale</title>
    <link rel="stylesheet" href="../css/edit_sales.css">
    <script>
        // JavaScript to update total price automatically when quantity changes
        function updateTotalPrice() {
            var quantity = document.getElementById('quantity').value;
            var productPrice = <?= $saleData['product_price'] ?>; // product price from the database
            var totalPrice = quantity * productPrice;
            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
    <h2 style="text-align:center;">Edit Sale</h2>
    <form method="POST">
        <label>Product:</label>
        <input type="text" value="<?= htmlspecialchars($saleData['product_name']) ?>" disabled><br>

        <label>Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?= (int)$saleData['quantity'] ?>" required oninput="updateTotalPrice()"><br>

        <label>Total Price (₱):</label>
        <input type="number" id="total_price" name="total_price" value="<?= number_format($saleData['total_price'], 2) ?>" readonly><br>

        <input type="submit" value="Save Changes">
        <button type="button" onclick="window.location.href='manage_sales.php'">Back</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
</body>
</html>
