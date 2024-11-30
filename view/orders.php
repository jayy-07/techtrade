<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$orderController = new OrderController();
$orders = $orderController->getUserOrders($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <style>
        .order-card {
            transition: transform 0.2s;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .order-items {
            max-height: 150px;
            overflow-y: auto;
        }
        .order-item-image {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .order-date {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Orders</h2>
            <a href="home.php" class="btn btn-outline-primary">
                <i class="bi bi-cart-plus"></i> Continue Shopping
            </a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted"></i>
                <h3 class="mt-3">No Orders Yet</h3>
                <p class="text-muted">You haven't placed any orders yet.</p>
                <a href="home.php" class="btn btn-primary mt-3">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($orders as $order): ?>
                    <div class="col-12 mb-4">
                        <div class="card order-card">
                            <div class="card-body">
                                <div class="status-badge">
                                    <?php
                                    $statusClass = match($order['status']) {
                                        'Paid' => 'success',
                                        'Pending' => 'warning',
                                        'Cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge text-bg-<?= $statusClass ?>">
                                        <?= htmlspecialchars($order['status']) ?>
                                    </span>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <h5 class="mb-1">Order #<?= str_pad($order['order_id'], 8, '0', STR_PAD_LEFT) ?></h5>
                                        <p class="order-date mb-2">
                                            <i class="bi bi-calendar3"></i>
                                            <?= date('F j, Y', strtotime($order['order_date'])) ?>
                                        </p>
                                        <h6 class="mb-0">Total: $<?= number_format($order['total_amount'], 2) ?></h6>
                                    </div>

                                    <div class="col-md-7">
                                        <h6 class="mb-2">Order Items (<?= count($order['items']) ?>)</h6>
                                        <div class="order-items">
                                            <?php foreach ($order['items'] as $item): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>"
                                                         alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                         class="order-item-image me-2">
                                                    <div>
                                                        <div class="fw-semibold"><?= htmlspecialchars($item['product_name']) ?></div>
                                                        <small class="text-muted">
                                                            Qty: <?= $item['quantity'] ?> × $<?= number_format($item['price'], 2) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-2 text-md-end">
                                        <a href="order_details.php?order_id=<?= $order['order_id'] ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 