<?php
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

// Set headers to prompt Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sales_day_report.xls");

// Print column headers
echo "Date & Time\tProduct Name\tQuantity\tTotal Price\n";

// Get today's date in YYYY-MM-DD format
$today = date('Y-m-d');

// Query to get all sales for the current day
$query = $conn->prepare("
    SELECT s.sale_date, p.name AS product_name, s.quantity, s.total_price
    FROM sales s
    JOIN products p ON s.product_id = p.id
    WHERE DATE(s.sale_date) = ?
    ORDER BY s.sale_date DESC
");
$query->bind_param("s", $today);
$query->execute();
$result = $query->get_result();

// Output each row
while ($row = $result->fetch_assoc()) {
    echo "{$row['sale_date']}\t{$row['product_name']}\t{$row['quantity']}\t{$row['total_price']}\n";
}
?>
