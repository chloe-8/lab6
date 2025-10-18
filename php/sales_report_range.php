<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

$totalRangeSales = 0;
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$rangeSales = [];

if ($startDate && $endDate) {
    $rangeQuery = $conn->prepare("
        SELECT sales.sale_date, products.name AS product_name, SUM(sales.quantity) AS total_quantity, SUM(sales.total_price) AS total_price
        FROM sales
        JOIN products ON sales.product_id = products.id
        WHERE sales.sale_date BETWEEN ? AND ?
        GROUP BY sales.sale_date, products.name
        ORDER BY sales.sale_date DESC
    ");
    $rangeQuery->bind_param("ss", $startDate, $endDate);
    $rangeQuery->execute();
    $result = $rangeQuery->get_result();

    while ($row = $result->fetch_assoc()) {
        $rangeSales[] = $row;
        $totalRangeSales += $row['total_price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Date Range</title>
    <link rel="stylesheet" href="../css/sales_report_day.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align:center;">Sales by Date Range</h2>
        <h3 style="text-align:center;">Total Sales: ₱<?= number_format($totalRangeSales, 2) ?></h3>

        <!-- Date Range Filter Form -->
        <form method="GET" action="" style="text-align:center; margin-bottom:20px;">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>" required>
            <button type="submit" class="btn">Filter</button>
        </form>

        <?php if ($startDate && $endDate && count($rangeSales) > 0): ?>
            <form method="POST" action="export_range_excel.php" style="text-align:center; margin-bottom:20px;">
                <input type="hidden" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
                <input type="hidden" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
                <button type="submit" class="btn">Export to Excel</button>
            </form>

            <table class="report-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rangeSales as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['sale_date']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= (int)$row['total_quantity'] ?></td>
                            <td>₱<?= number_format($row['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($startDate && $endDate): ?>
            <p style="text-align:center;">No sales found for this date range.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="sales_report.php">Back to Main Report</a>
        </div>
    </div>
</body>
</html>
