<?php
session_start();
include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'User') {
    die("Access denied.");
}

$errorMessage = '';
$nameError = '';
$categoryError = '';
$quantityError = '';
$priceError = '';
$imageError = '';
$descriptionError = '';

// Define default values for form inputs
$name = '';
$description = '';
$category_id = null;
$quantity = 0;
$price = 0;
$status = 'Active';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category'] ?? null;
    $quantity = isset($_POST['quantity']) && $_POST['quantity'] !== '' ? intval($_POST['quantity']) : 0;
    $price = $_POST['price'] ?? 0;
    $status = $_POST['status'] ?? 'Active';
    $imageName = null;

    // Validation for required fields
    if (empty($name)) {
        $nameError = "Name is required.";
    }

    if (empty($category_id)) {
        $categoryError = "Category is required.";
    }

    if (empty($quantity)) {
        $quantityError = "Quantity is required.";
    }

    if (empty($price)) {
        $priceError = "Price is required.";
    }

    if ($_FILES['image']['error'] === 4) {
        $imageError = "Image is required.";
    }

    if (empty($description)) {
        $descriptionError = "Description is required.";
    }

    // If no errors, proceed to handle the image upload and insert the product
    if (empty($nameError) && empty($categoryError) && empty($quantityError) && empty($priceError) && empty($imageError) && empty($descriptionError)) {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = "../uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                die("Failed to upload image.");
            }
        }

        // Insert product into the database
        $stmt = $conn->prepare("INSERT INTO products (name, description, category, quantity, price, image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssidss", $name, $description, $category_id, $quantity, $price, $imageName, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully'); window.location.href='manage_products.php';</script>";
        } else {
            echo "Error adding product: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/add_product.css">
    <style>
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>Add Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"><br>
        <div class="error"><?= $nameError ?></div>

        <label>Description:</label><br>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea><br>
        <div class="error"><?= $descriptionError ?></div>

        <label>Category:</label><br>
        <select name="category">
            <option value="">Select Category</option>
            <?php
            $categories = $conn->query("SELECT id, name FROM categories");
            while ($row = $categories->fetch_assoc()) {
                $selected = $row['id'] == $category_id ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select><br>
        <div class="error"><?= $categoryError ?></div>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" min="0" value="<?= htmlspecialchars($quantity) ?>"><br>
        <div class="error"><?= $quantityError ?></div>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>"><br>
        <div class="error"><?= $priceError ?></div>

        <label>Image:</label><br>
        <input type="file" name="image" accept="image/*"><br>
        <div class="error"><?= $imageError ?></div>

        <label>Status:</label><br>
        <select name="status">
            <option value="Active" <?= $status === 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Inactive" <?= $status === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select><br><br>

        <input type="submit" value="Add Product">
        <button type="button" onclick="window.location.href='manage_products.php'">Back</button>
    </form>
</body>
</html>
