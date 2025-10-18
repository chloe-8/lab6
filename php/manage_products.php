<?php

include("header.php");
include("database.php");

$successMessage = '';
if (isset($_SESSION['delete_success'])) {
    $successMessage = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']);
}

$updateMessage = '';
if (isset($_SESSION['update_success'])) {
    $updateMessage = $_SESSION['update_success'];
    unset($_SESSION['update_success']);
}


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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/manage_product.css">
    <title>Product Management</title>
    <style>
        .hidden { display: none; }
        img.product-image { width: 100px; height: 100px; object-fit: cover; }
    </style>
</head>
<body>
    <h2>Manage Product</h2><br>
<form method="GET" action="">
    <input type="text" id="searchInput" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search products..." oninput="filterTable()">
    <button type="submit">Search</button>

    <button type="button" onclick="window.location.href='add_product.php'">Add Product</button>

</form>

<table id="productTable" border="1">
    <thead>
        <tr>
            <th>IMAGE</th>
            <th>NAME</th>
            <th>CATEGORY</th>
            <th>DESCRIPTION</th>
            <th>PRICE</th>
            <th>QUANTITY</th>
            <th>CREATED AT</th>
            <th>UPDATE</th>
            <th>DELETE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = "../uploads/" . htmlspecialchars($row["image"]);
                $imageDisplay = file_exists($imagePath) ? $imagePath : '../uploads/default.jpg';

                echo "<tr class='productRow'>
                        <td><img src='$imageDisplay' alt='Product Image' class='product-image'></td>
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>" . htmlspecialchars($row["category"]) . "</td>
                        <td>" . htmlspecialchars($row["description"]) . "</td>
                        <td>" . htmlspecialchars($row["price"]) . "</td>
                        <td>" . htmlspecialchars($row["quantity"]) . "</td>
                        <td>" . htmlspecialchars($row["created_at"]) . "</td>
                        <td>
                            <button class='edit-btn' data-id='{$row['id']}'>Edit</button>
                        </td>
                            <td>
                            <button class='delete-btn' data-id='{$row['id']}'>Delete</button>
                            </td>
                         </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No results found</td></tr>";
        }
        ?>
    </tbody>
</table>
<div id="cancelConfirmModal" class="modal hidden">
    <div class="modal-content">
        <h3>Delete Product</h3>
        <p>Are you sure you want to delete this product?</p>
        <div style="text-align: right;">
            <button class="confirm-btn" onclick="confirmCancel()">Yes</button>
            <button class="cancel-modal-btn" onclick="closeModalById('cancelConfirmModal')">No</button>
        </div>
    </div>
    <input type="hidden" id="productId">
</div>

<?php if (!empty($successMessage)): ?>
<div id="successModal" class="modal">
    <div class="modal-content">
        <h3>Success</h3>
        <p><?= htmlspecialchars($successMessage) ?></p>
        <div style="text-align: right;">
            <button onclick="closeModalById('successModal')" style="background-color: green; color: white; padding: 8px 12px; border: none; border-radius: 5px;">OK</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($updateMessage)): ?>
<div id="updateModal" class="modal">
    <div class="modal-content">
        <h3>Success</h3>
        <p><?= htmlspecialchars($updateMessage) ?></p>
        <div style="text-align: right;">
            <button onclick="closeModalById('updateModal')" style="background-color: green; color: white; padding: 8px 12px; border: none; border-radius: 5px;">OK</button>
        </div>
    </div>
</div>
<?php endif; ?>


<script src="../js/manage_product.js"></script>

</body>
</html>
