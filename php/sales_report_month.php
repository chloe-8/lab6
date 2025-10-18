<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Query to get all sales for the current month
$query = $conn->prepare("
    SELECT s.id, s.sale_date, s.quantity, s.total_price, p.name AS product_name
    FROM sales s
    JOIN products p ON s.product_id = p.id
    WHERE MONTH(s.sale_date) = ? AND YEAR(s.sale_date) = ?
    ORDER BY s.sale_date DESC
");
$query->bind_param("ii", $currentMonth, $currentYear);
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
    <title>Sales Report - Current Month</title>
    <link rel="stylesheet" href="../css/sales_report_month.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align:center;">Sales - <?= date('F Y') ?></h2>
        <h3 style="text-align:center;">Total Sales: ₱<?= number_format($totalSales, 2) ?></h3>

        <?php if (!empty($report)): ?>
            <div class="buttons" style="text-align: center; margin-bottom: 15px;">
                <form method="POST" action="export_month_excel.php">
                    <button type="submit" class="btn">Export to Excel</button>
                </form>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th>Date</th>
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
            <p>No transactions found for <?= date('F Y') ?>.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="sales_report.php">Back to Main Report</a>
        </div>
    </div>
</body>
</html>
