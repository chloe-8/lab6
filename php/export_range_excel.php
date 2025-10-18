<?php
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

$startDate = $_POST['start_date'] ?? '';
$endDate = $_POST['end_date'] ?? '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sales_range_report.xls");
echo "Date\tProduct Name\tQuantity\tTotal Price\n";

if ($startDate && $endDate) {
    $query = $conn->prepare("
        SELECT sales.sale_date, products.name AS product_name, SUM(sales.quantity) AS total_quantity, SUM(sales.total_price) AS total_price
        FROM sales
        JOIN products ON sales.product_id = products.id
        WHERE sales.sale_date BETWEEN ? AND ?
        GROUP BY sales.sale_date, products.name
        ORDER BY sales.sale_date DESC
    ");
    $query->bind_param("ss", $startDate, $endDate);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "{$row['sale_date']}\t{$row['product_name']}\t{$row['total_quantity']}\t{$row['total_price']}\n";
    }
}
?>
