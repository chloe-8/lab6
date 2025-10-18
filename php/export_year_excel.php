<?php
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] === 'Special User') {
    die("Access denied.");}

// Set headers for Excel file download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sales_year_report.xls");

// Output column headers for the report
echo "Year\tProduct Name\tQuantity\tTotal Sales\n";

// Query for the current year sales data
$query = $conn->prepare("
    SELECT 
        YEAR(sale_date) AS sale_year, 
        products.name AS product_name, 
        sales.quantity, 
        SUM(sales.total_price) AS total_sales
    FROM sales
    JOIN products ON sales.product_id = products.id
    WHERE YEAR(sale_date) = ?
    GROUP BY YEAR(sale_date), products.name
    ORDER BY sale_year DESC, product_name ASC
");

// Bind the current year to the query
$currentYear = date('Y');
$query->bind_param("i", $currentYear);
$query->execute();
$result = $query->get_result();

// Output each row in Excel format
while ($row = $result->fetch_assoc()) {
    echo "{$row['sale_year']}\t{$row['product_name']}\t{$row['quantity']}\t{$row['total_sales']}\n";
}
?>
