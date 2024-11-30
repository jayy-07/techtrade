<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$orderController = new OrderController();
$order = $orderController->getOrder($_GET['order_id']);

if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
    header("Location: orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <style>
        .order-status {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .order-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="orders.php" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
                <h2 class="mt-2">Order #<?= str_pad($order['order_id'], 8, '0', STR_PAD_LEFT) ?></h2>
            </div>
            <?php
            $statusClass = match($order['status']) {
                'Paid' => 'success',
                'Pending' => 'warning',
                'Cancelled' => 'danger',
                default => 'secondary'
            };
            ?>
            <span class="order-status bg-<?= $statusClass ?> text-white">
                <?= htmlspecialchars($order['status']) ?>
            </span>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="order-section">
                    <h4 class="mb-4">Order Items</h4>
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>"
                                         alt="<?= htmlspecialchars($item['product_name']) ?>"
                                         class="product-image me-3">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title"><?= htmlspecialchars($item['product_name']) ?></h5>
                                        <p class="card-text mb-1">
                                            Quantity: <?= $item['quantity'] ?>
                                        </p>
                                        <p class="card-text">
                                            Price: $<?= number_format($item['price'], 2) ?>
                                        </p>
                                        <?php if (isset($item['trade_in_value']) && $item['trade_in_value'] > 0): ?>
                                            <p class="card-text text-info">
                                                <i class="bi bi-arrow-left-right"></i>
                                                Trade-in Value: $<?= number_format($item['trade_in_value'], 2) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end">
                                        <h5>$<?= number_format($item['price'] * $item['quantity'], 2) ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="order-section">
                    <h4 class="mb-3">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>$<?= number_format($order['subtotal'], 2) ?></span>
                    </div>
                    <?php if ($order['discount_total'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount</span>
                            <span>-$<?= number_format($order['discount_total'], 2) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['trade_in_total'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-info">
                            <span>Trade-in Credit</span>
                            <span>-$<?= number_format($order['trade_in_total'], 2) ?></span>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong>$<?= number_format($order['total_amount'], 2) ?></strong>
                    </div>
                </div>

                <div class="order-section">
                    <h4 class="mb-3">Shipping Details</h4>
                    <p class="mb-1"><strong>Address:</strong></p>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                    <p class="mb-1"><strong>Email:</strong></p>
                    <p class="text-muted"><?= htmlspecialchars($order['email']) ?></p>
                    <p class="mb-1"><strong>Phone:</strong></p>
                    <p class="text-muted mb-0"><?= htmlspecialchars($order['phone']) ?></p>
                </div>

                <div class="order-section">
                    <h4 class="mb-3">Order Information</h4>
                    <p class="mb-1"><strong>Order Date:</strong></p>
                    <p class="text-muted"><?= date('F j, Y g:i A', strtotime($order['order_date'])) ?></p>
                    <p class="mb-1"><strong>Order Status:</strong></p>
                    <p class="mb-0">
                        <span class="badge text-bg-<?= $statusClass ?>">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 