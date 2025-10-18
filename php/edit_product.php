<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'User') {
    die("Access denied.");}

    


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("No product ID provided.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if ($_FILES['image']['name']) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imagePath = '../uploads/' . $imageName;

        if (move_uploaded_file($imageTmp, $imagePath)) {
            $updateQuery = "UPDATE products SET name = ?, category = ?, description = ?, price = ?, quantity = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ssssdsi", $name, $category, $description, $price, $quantity, $imageName, $id);
        } else {
            echo "Error uploading the image.";
        }
    } else {
        $updateQuery = "UPDATE products SET name = ?, category = ?, description = ?, price = ?, quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssdi", $name, $category, $description, $price, $quantity, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['update_success'] = "Product updated successfully.";
        header("Location: manage_products.php");
        exit;
    } else {
        echo "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edit_product.css">
    <title>Edit Product</title>
</head>
<body>
    <h2 style="text-align:center;">Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <!-- Left Column -->
            <div class="form-column">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                <label>Category:</label>
                <select name="category" required>
                    <?php
                    $categoryStmt = $conn->prepare("SELECT * FROM categories");
                    $categoryStmt->execute();
                    $categoryResult = $categoryStmt->get_result();
                    while ($category = $categoryResult->fetch_assoc()) {
                        $selected = $category['name'] === $product['category'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($category['name']) . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>

                <label>Description:</label>
                <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <!-- Right Column -->
            <div class="form-column">
                <label>Price:</label>
                <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required step="0.01">

                <label>Quantity:</label>
                <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>

                <label>Image (optional):</label>
                <input type="file" name="image">
                <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="100">
            </div>
        </div>

        <br>
        <input type="submit" value="Update Product">
        <button type="button" onclick="window.location.href='manage_products.php'">Back</button>
    </form>
</body>
</html>
