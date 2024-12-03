<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';
require_once 'header.php';
check_seller();

$orderController = new OrderController();
$orders = $orderController->getSellerOrders($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller - Orders</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
    <ul class="nav nav-pills mt-4 mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="seller_inventory.php">Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="seller_orders.php">Orders</a>
            </li>
        </ul>

        <div class="d-flex justify-content-between mb-3">
            <h2>Orders</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <div class="text-truncate" 
                                     data-bs-toggle="tooltip" 
                                     data-bs-placement="top" 
                                     title="<?= htmlspecialchars($order['product_name']) ?>"
                                     style="max-width: 200px;">
                                    <?= htmlspecialchars($order['product_name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                            <td>$<?= number_format($order['price'] * $order['quantity'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= $order['payment_status'] === 'Completed' ? 'success' : 'warning' ?>">
                                    <?= htmlspecialchars($order['payment_status']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary view-order"
                                    data-bs-toggle="modal"
                                    data-bs-target="#orderDetailsModal"
                                    data-order-id="<?= $order['order_id'] ?>">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/tooltips.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-order').on('click', function() {
                const orderId = $(this).data('order-id');
                $('#orderDetailsContent').load('../actions/get_seller_order_details.php?order_id=' + orderId);
            });
        });
    </script>
</body>
</html> 