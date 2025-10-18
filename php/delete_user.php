<?php
session_start();
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied. Admins only.");
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        header("Location: ../php/manage_user.php"); // change this filename if needed
        exit;
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "User ID not provided.";
}
?>
