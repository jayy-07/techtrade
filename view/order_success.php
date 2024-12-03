<?php
require_once '../settings/core.php';
require_once '../classes/Order.php';
check_login();

// Get order ID from URL parameter
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;

if (!$order_id) {
    header("Location: orders.php");
    exit;
}

// Get order details
$order = new Order();
$orderDetails = $order->getOrder($order_id);

// Verify order exists and belongs to current user
if (!$orderDetails || $orderDetails['user_id'] != $_SESSION['user_id']) {
    header("Location: orders.php");
    exit;
}

error_log("=== Starting order_success.php ===");
error_log("SESSION data: " . print_r($_SESSION, true));
error_log("GET data: " . print_r($_GET, true));

error_log("Attempting to get order details for order_id: " . $order_id);

error_log("Order details returned: " . print_r($orderDetails, true));

error_log("Order verification passed - proceeding to display order");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <style>
        .success-icon {
            font-size: 4rem;
            color: #198754;
        }
        .order-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .order-item {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <?php include_once 'header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <i class="bi bi-check-circle-fill success-icon"></i>
                    <h2 class="mt-3">Order Placed Successfully!</h2>
                    <p class="text-muted">Thank you for your purchase. Your order has been confirmed.</p>
                </div>

                <div class="order-summary mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Order Details</h4>
                        <span class="badge bg-success">Order #<?= htmlspecialchars($order_id) ?></span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Date:</strong></p>
                            <p class="text-muted"><?= date('F j, Y', strtotime($orderDetails['created_at'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Shipping Address:</strong></p>
                            <p class="text-muted"><?= htmlspecialchars($orderDetails['shipping_address']) ?></p>
                        </div>
                    </div>

                    <div class="items-section">
                        <h5 class="mb-3">Items Ordered</h5>
                        <?php foreach ($orderDetails['items'] as $item): ?>
                        <div class="order-item">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>" 
                                         alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                         class="product-image">
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-1"><?= htmlspecialchars($item['product_name']) ?></h6>
                                    <p class="text-muted mb-0">Sold by: <?= htmlspecialchars($item['seller_name']) ?></p>
                                    <p class="text-muted mb-0">Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                                    <?php if (!empty($item['trade_in_value'])): ?>
                                        <small class="text-info">
                                            <i class="bi bi-arrow-left-right"></i>
                                            Trade-in Applied: <span class="currency-symbol">₵</span><?= number_format($item['trade_in_value'], 2) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-4 text-end">
                                    <p class="mb-0"><span class="currency-symbol">₵</span><?= number_format($item['price'], 2) ?></p>
                                    <?php if (!empty($item['discount'])): ?>
                                        <small class="text-muted">
                                            <del>₵<?= number_format($item['price'] / (1 - $item['discount']/100), 2) ?></del>
                                            (<?= number_format($item['discount'], 0) ?>% off)
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="price-summary mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span><span class="currency-symbol">₵</span><?= number_format($orderDetails['subtotal'], 2) ?></span>
                        </div>
                        <?php if (!empty($orderDetails['total_discount'])): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount</span>
                            <span>-<span class="currency-symbol">₵</span><?= number_format($orderDetails['total_discount'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($orderDetails['trade_in_credit'] != 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-info">
                            <span>Trade-in Credit</span>
                            <span>-<span class="currency-symbol">₵</span><?= number_format($orderDetails['trade_in_credit'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                            <strong>Total</strong>
                            <strong><span class="currency-symbol">₵</span><?= number_format($orderDetails['final_total'], 2) ?></strong>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="orders.php" class="btn btn-techtrade-primary me-2">
                        <i class="bi bi-list-ul me-2"></i>View All Orders
                    </a>
                    <a href="home.php" class="btn btn-techtrade-outline">
                        <i class="bi bi-shop me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 