<?php
include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'User') {
    die("Access denied.");

}
    
    
// Add category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_category"])) {
    $category = trim($_POST["category"]);
    if (!empty($category)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category);
        if ($stmt->execute()) {
            // Redirect to category page with added flag
            echo "<script>window.location.href='category.php?added=1';</script>";
            exit;
        } else {
            echo "<script>alert('Error: Could not add category');</script>";
        }
    }
}
// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");

// Delete category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    echo "<script>window.location.href='category.php?deleted=1';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="../css/add_category.css">
</head>
<body>

    <h2>Add Category</h2>

    <form method="POST">
        <label>Category Name:</label>
        <input type="text" name="category" required>
        <button type="submit" name="add_category">Add</button>
        <button type="button" onclick="window.location.href='manage_products.php'">Back</button>
    </form>

    <h2>Category List</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
        <?php if ($categories->num_rows > 0): ?>
            <?php while($row = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <button onclick="window.location.href='edit_category.php?id=<?= $row['id'] ?>'">Edit</button>
                        <button class="cancel-btn" onclick="openCancelModal(<?= $row['id'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">No categories found.</td></tr>
        <?php endif; ?>
    </table>

    <div id="cancelConfirmModal" class="modal">
    <div class="modal-content">
        <h3>Delete Category</h3>
        <p>Are you sure you want to delete this category?</p>
        <button class="confirm-btn" onclick="confirmCancel()">Yes</button>
        <button class="cancel-modal-btn" onclick="closeModalById('cancelConfirmModal')">No</button>
    </div>
    <input type="hidden" id="categoryId">
</div>


<div id="successModal" class="modal">
    <div class="modal-content">
        <h3>Success</h3>
        <p>Category added successfully.</p>
        <button class="success-ok-btn" onclick="closeModalById('successModal')">OK</button>
    </div>
</div>


<script src="../js/category.js"></script>
</body>
</html>
