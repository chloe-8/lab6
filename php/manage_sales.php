<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");
}

$stmt = $conn->prepare("
    SELECT sales.*, products.name AS product_name, products.image AS product_image
    FROM sales 
    JOIN products ON sales.product_id = products.id 
    ORDER BY sales.sale_date DESC
");

$stmt->execute();
$sales = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sales</title>
    <link rel="stylesheet" href="../css/manage_sales.css">
    <style>
      
    </style>
</head>
<body>
    <h2>Sales Records</h2>
    <input type="text" id="searchInput" placeholder="Search product..." oninput="filterTable()">
    <button type="button" onclick="window.location.href='add_sales.php'">Add Sale</button>
    <button type="button" onclick="window.location.href='index.php'">Back</button>
  <!-- Pagination buttons will appear here -->
  <div id="pagination"></div>
    <table id="salesTable" border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>PRODUCT</th>
                <th>IMAGE</th>
                <th>QUANTITY</th>
                <th>TOTAL PRICE</th>
                <th>SALE DATE</th>
                <th>UPDATE</th>
                <th>DELETE</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sales->num_rows > 0): ?>
                <?php while ($row = $sales->fetch_assoc()): ?>
                    <?php
                        $imagePath = "../uploads/" . htmlspecialchars($row['product_image']);
                        $imageDisplay = file_exists($imagePath) ? $imagePath : '../uploads/default.jpg';
                    ?>
                    <tr class="salesRow" style="display: none;">
                        <td class="rowNumber"></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><img src="<?= $imageDisplay ?>" class="product-image" alt="Product Image"></td>
                        <td><?= (int)$row['quantity'] ?></td>
                        <td>₱<?= number_format($row['total_price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['sale_date']) ?></td>
                        <td>
                        
                        <button><a href="edit_sales.php?id=<?= $row['id'] ?>">Edit</a></button> 
                       
                        </td>
                        <td>
                        <button><a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a></button>
                    
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No sales found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

  

    <script src="../js/manage_sales.js"></script>

</body>
</html>
