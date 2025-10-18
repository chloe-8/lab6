<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory System</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="brand">INVENTORY SYSTEM</div>
    <ul class="nav">
        <?php
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];

            if ($role === 'Admin') {
                echo '<li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>';
                echo '<li><a href="manage_user.php"><i class="fas fa-users"></i> User Management</a></li>';
                echo '<li><a href="category.php"><i class="fas fa-th-large"></i> Categories</a></li>';
                echo '<li><a href="manage_products.php"><i class="fas fa-box-open"></i> Products</a></li>';
                echo '<li><a href="manage_sales.php"><i class="fas fa-shopping-cart"></i> Sales</a></li>';
                echo '<li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>';
            } elseif ($role === 'User') {
                echo '<li><a href="manage_sales.php"><i class="fas fa-shopping-cart"></i> Sales</a></li>';
                echo '<li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>';
            } elseif ($role === 'Special User') {
                echo '<li><a href="category.php"><i class="fas fa-th-large"></i> Categories</a></li>';
                echo '<li><a href="manage_products.php"><i class="fas fa-box-open"></i> Products</a></li>';
            }
        }
        ?>
    </ul>

    <div class="logo">
        <img src="../uploads/LOGO.png" class="min-img" alt="Example Image">
    </div>
</div>

<div class="main">
    <div class="header">
        <div class="date"><?= date("F d, Y") ?></div>
        <div class="profile">
            <i class="fas fa-user-circle"></i>
            <?= isset($_SESSION['logged_user']) ? htmlspecialchars($_SESSION['logged_user']) : 'Guest' ?>
            <i class="fas fa-chevron-down"></i>
            <button style="margin-left: 10px; background: none; border: none;">
                <a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a>
            </button>
        </div>
    </div>

    <div class="content">
        <!-- Page content here -->
