<?php
include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {

    if(isset($_SESSION['role']) || $_SESSION['role'] === 'Special User'){
        header("location: category.php");
        exit();
    }
    
   

}


// Fetch sales data for the chart
$salesQuery = $conn->query("SELECT DATE(sale_date) as sale_day, SUM(total_price) as total_sales FROM sales GROUP BY sale_day ORDER BY sale_day ASC");

$sale_days = [];
$sale_totals = [];

while ($row = $salesQuery->fetch_assoc()) {
    $sale_days[] = $row['sale_day'];
    $sale_totals[] = $row['total_sales'];
}

// Dashboard stats
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$salesCount = $conn->query("SELECT COUNT(*) AS total FROM sales")->fetch_assoc()['total'];
$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$totalSales = $conn->query("SELECT SUM(total_price) AS total FROM sales")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 40px auto;
            max-width: 1000px;
            justify-content: center;
            
        }

        .card {
            margin: 2%;
            flex: 1 1 220px;
            background: linear-gradient(135deg, rgb(231 220 199), #c5a96f, rgb(179 153 92), #71603e);
            color: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,7);
            text-align: center;
        }

        .card i {
            font-size: 36px;
            margin-bottom: 10px;
            color: #fffacd;
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 26px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="main-content">
    <h2 style="text-align:center;">Sales Overview</h2>
    <canvas id="salesChart" width="350" height="100"></canvas>

    <div class="cards">
        <div class="card">
            <i class="fas fa-users"></i>
            <h3>Total Users</h3>
            <p><?= $userCount ?></p>
        </div>
        <div class="card">
            <i class="fas fa-receipt"></i>
            <h3>Total Sales Transactions</h3>
            <p><?= $salesCount ?></p>
        </div>
        <div class="card">
            <i class="fas fa-boxes"></i>
            <h3>Total Products</h3>
            <p><?= $productCount ?></p>
        </div>
        <div class="card">
            <i class="fas fa-coins"></i>
            <h3>Total Revenue</h3>
            <p>₱<?= number_format($totalSales, 2) ?></p>
        </div>
    </div>
</div>


<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($sale_days) ?>,
            datasets: [{
                label: 'Total Sales',
                data: <?= json_encode($sale_totals) ?>,
                backgroundColor: 'rgba(83, 63, 34, 0.6)',
                borderColor: 'rgb(88, 83, 72)',
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(63, 52, 37, 0.8)',
                hoverBorderColor: 'rgb(73, 62, 48)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Sales (₱)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
</script>

</body>
</html>
