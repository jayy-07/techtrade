<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';


if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    error_log("Redirecting to home: Missing session user_id or order_id");
    header("Location: home.php");
    exit;
}

$orderController = new OrderController();
$order = $orderController->getOrder($_GET['order_id']);

if (!$order) {
    error_log("Redirecting to home: Order not found");
    header("Location: home.php");
    exit;
}

if ($order['user_id'] != $_SESSION['user_id']) {
    error_log("Redirecting to home: User ID mismatch");
    error_log("Order user_id: " . $order['user_id']);
    error_log("Session user_id: " . $_SESSION['user_id']);
    header("Location: home.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <style>
        .success-icon {
            font-size: 5rem;
            color: #198754;
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="text-center mb-4">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h2 class="mt-3">Order Successful!</h2>
            <p class="text-muted">Thank you for your purchase. Your order has been confirmed.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="order-details">
                    <h4 class="mb-3">Order Details</h4>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Order Number:</strong></p>
                            <p class="text-muted">#<?= str_pad($order['order_id'], 8, '0', STR_PAD_LEFT) ?></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Order Date:</strong></p>
                            <p class="text-muted"><?= date('F j, Y', strtotime($order['order_date'])) ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Items Ordered</h5>
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="d-flex align-items-center mb-2 p-2 bg-white rounded">
                                <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>" 
                                     alt="<?= htmlspecialchars($item['product_name']) ?>"
                                     class="me-3" style="width: 50px; height: 50px; object-fit: contain;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= htmlspecialchars($item['product_name']) ?></h6>
                                    <small class="text-muted">Quantity: <?= $item['quantity'] ?></small>
                                </div>
                                <div class="text-end">
                                    <span>$<?= number_format($item['price'], 2) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-end mb-4">
                        <p class="mb-1">Subtotal: $<?= number_format($order['subtotal'], 2) ?></p>
                        <?php if ($order['discount_total'] > 0): ?>
                            <p class="mb-1 text-success">Discount: -$<?= number_format($order['discount_total'], 2) ?></p>
                        <?php endif; ?>
                        <?php if ($order['trade_in_total'] > 0): ?>
                            <p class="mb-1 text-info">Trade-in Credit: -$<?= number_format($order['trade_in_total'], 2) ?></p>
                        <?php endif; ?>
                        <h5>Total: $<?= number_format($order['total_amount'], 2) ?></h5>
                    </div>

                    <div class="text-center mt-4">
                        <a href="orders.php" class="btn btn-primary me-2">View All Orders</a>
                        <a href="home.php" class="btn btn-outline-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 