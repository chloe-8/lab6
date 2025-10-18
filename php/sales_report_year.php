<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

$currentYear = date('Y');
$report = [];
$totalYearSales = 0;

$stmt = $conn->prepare("
    SELECT 
        sales.sale_date,
        products.name AS product_name,
        sales.quantity,
        sales.total_price
    FROM sales
    JOIN products ON sales.product_id = products.id
    WHERE YEAR(sales.sale_date) = ?
    ORDER BY sales.sale_date DESC
");
$stmt->bind_param("i", $currentYear);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $report[] = $row;
    $totalYearSales += $row['total_price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Current Year</title>
    <link rel="stylesheet" href="../css/sales_report_year.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align:center;">Sales Transactions - <?= $currentYear ?></h2>
        <h3 style="text-align:center;">Total Sales: ₱<?= number_format($totalYearSales, 2) ?></h3>

        <?php if (!empty($report)): ?>
            <div class="buttons">
                <form method="POST" action="export_year_excel.php">
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
            <p>No sales transactions found for <?= $currentYear ?>.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="sales_report.php">Back to Main Report</a>
        </div>
    </div>
</body>
</html>
