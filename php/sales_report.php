<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) =='Special User') {
    die("Access denied.");
}

// Calculate Total Sales (for all data)
$totalSalesQuery = $conn->prepare("SELECT SUM(total_price) AS total_sales FROM sales");
$totalSalesQuery->execute();
$totalSalesResult = $totalSalesQuery->get_result();
$totalSales = $totalSalesResult->fetch_assoc()['total_sales'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/sales_report.css">
    <title>Sales Report</title>
</head>
<body>
    <h2>Sales Report</h2>
  

    <!-- Display Total Sales -->
    <h3>Total Sales: ₱<?= number_format($totalSales, 2) ?></h3>

    <!-- Links to other report sections -->
    <ul>
        <li><a href="sales_report_year.php">Sales by Year</a></li>
        <li><a href="sales_report_month.php">Sales by Month</a></li>
        <li><a href="sales_report_day.php">Sales by Day</a></li>
        <li><a href="sales_report_range.php">Sales by Date Range</a></li>
    </ul>

</form>

</body>
</html>
