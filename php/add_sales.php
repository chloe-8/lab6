<?php
session_start();
include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchQuery) {
    $productData = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? OR description LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $productData->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
} else {
    $productData = $conn->prepare("SELECT * FROM products");
}

$productData->execute();
$result = $productData->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/add_sales.css">
    <title>Add Sale from Product List</title>
    <style>
        img.product-image { width: 100px; height: 100px; object-fit: cover; }
    </style>
</head>
<body>
<form method="GET" action="">
    <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search products...">
    <button type="submit">Search</button>
    <button type="button" onclick="window.location.href='manage_sales.php'">Back</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>IMAGE</th>
            <th>NAME</th>
            <th>CATEGORY</th>
            <th>DESCRIPTION</th>
            <th>PRICE</th>
            <th>AVAILABLE QUANTITY</th>
            <th>QUANTITY TO PURCHASE</th>
            <th>ADD SALE</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    $imagePath = "../uploads/" . htmlspecialchars($row["image"]);
                    $imageDisplay = file_exists($imagePath) ? $imagePath : '../uploads/default.jpg';
                ?>
                <tr>
                    <form method="POST" action="add_sales_handler.php">
                        <td><img src="<?= $imageDisplay ?>" class="product-image" alt="Product Image"></td>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["category"]) ?></td>
                        <td><?= htmlspecialchars($row["description"]) ?></td>
                        <td><?= htmlspecialchars($row["price"]) ?></td>
                        <td><?= htmlspecialchars($row["quantity"]) ?></td>
                        <td>
                            <input type="number" name="quantity" min="1" max="<?= $row['quantity'] ?>" required>
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        </td>
                        <td>
                            <button type="submit">Add Sale</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No results found</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>
