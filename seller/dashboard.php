<?php
require_once '../controllers/DashboardController.php';
require_once '../settings/core.php';
check_login();
check_seller();

$dashboardController = new DashboardController();
$stats = $dashboardController->getSellerDashboardStats($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <ul class="nav nav-pills mt-4 mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="seller_inventory.php">Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="seller_orders.php">Orders</a>
            </li>
        </ul>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <a href="seller_inventory.php" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Products</h6>
                            <h2 class="card-title mb-0"><?= number_format($stats['total_products']) ?></h2>
                            <i class="bi bi-box-seam position-absolute top-0 end-0 m-3 text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="seller_orders.php" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Orders</h6>
                            <h2 class="card-title mb-0"><?= number_format($stats['total_orders']) ?></h2>
                            <i class="bi bi-cart-check position-absolute top-0 end-0 m-3 text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="seller_orders.php" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Revenue</h6>
                            <h2 class="card-title mb-0"><span class="currency-symbol">₵</span><?= number_format($stats['total_revenue'], 2) ?></h2>
                            <i class="bi bi-currency-dollar position-absolute top-0 end-0 m-3 text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Revenue</h5>
                        <div style="height: 300px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top Products</h5>
                        <div style="height: 300px;">
                            <canvas id="productsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Recent Orders</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recent_orders'] as $order): ?>
                                <tr>
                                    <td>#<?= $order['order_id'] ?></td>
                                    <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                                    <td><?= $order['quantity'] ?></td>
                                    <td><span class="currency-symbol">₵</span><?= number_format($order['price'] * $order['quantity'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $order['payment_status'] === 'Completed' ? 'success' : 'warning' ?>">
                                            <?= $order['payment_status'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column(array_reverse($stats['monthly_revenue']), 'month')) ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?= json_encode(array_column(array_reverse($stats['monthly_revenue']), 'revenue')) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₵' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Top Products Chart
        const productsCtx = document.getElementById('productsChart').getContext('2d');
        new Chart(productsCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($stats['top_products'], 'name')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($stats['top_products'], 'revenue')) ?>,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>

</html>