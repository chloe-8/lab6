<?php
include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'User') {
    die("Access denied.");}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid category ID'); window.location.href='category.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Update category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_category"])) {
    $new_category = trim($_POST["category"]);
    if (!empty($new_category)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_category, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Category updated successfully'); window.location.href='category.php';</script>";
        } else {
            echo "<script>alert('Error: Could not update category');</script>";
        }
    } else {
        echo "<script>alert('Category name cannot be empty');</script>";
    }
}

// Fetch current category
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Category not found'); window.location.href='category.php';</script>";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="../css/add_category.css">
</head>
<body>

    <h2>Edit Category</h2>

    <form method="POST">
        <label>Category Name:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($row['name']) ?>" required>
        <button type="submit" name="update_category">Update</button>
        <button type="button" onclick="window.location.href='category.php'">Back</button>
    </form>

</body>
</html>