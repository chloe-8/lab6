<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}
// Get today's date in YYYY-MM-DD format
$today = date('Y-m-d');

// Query to get all sales for the current day
$query = $conn->prepare("
    SELECT s.id, s.sale_date, s.quantity, s.total_price, p.name AS product_name
    FROM sales s
    JOIN products p ON s.product_id = p.id
    WHERE DATE(s.sale_date) = ?
    ORDER BY s.sale_date DESC
");
$query->bind_param("s", $today);
$query->execute();
$sales = $query->get_result();

$totalSales = 0;
$report = [];

while ($row = $sales->fetch_assoc()) {
    $report[] = $row;
    $totalSales += $row['total_price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Today</title>
    <link rel="stylesheet" href="../css/sales_report_day.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align:center;">Sales - <?= date('F j, Y') ?></h2>
        <h3 style="text-align:center;">Total Sales: ₱<?= number_format($totalSales, 2) ?></h3>

        <div class="buttons">
            <form method="POST" action="export_day_excel.php">
                <button type="submit" class="btn">Export to Excel</button>
            </form>
        </div>

        <?php if (!empty($report)): ?>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['sale_date']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>₱<?= number_format($row['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions found for today.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="sales_report.php">Back to Main Report</a>
        </div>
    </div>
</body>
</html>
