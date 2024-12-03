<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';
check_login();

$orderController = new OrderController();
$orders = $orderController->getUserOrders($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <style>
        .order-card {
            transition: transform 0.2s;
        }

        .order-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <main>
        <div class="container my-5">
            <h2 class="mb-4">My Orders</h2>

            <?php if (empty($orders)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-bag-x" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">No Orders Yet</h4>
                    <p class="text-muted">Start shopping to see your orders here!</p>
                    <a href="home.php" class="btn btn-techtrade-primary mt-3">
                        <i class="bi bi-shop me-2"></i>Browse Products
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($orders as $order): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card order-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Order #<?= htmlspecialchars($order['order_id']) ?></h5>
                                        <span class="badge bg-<?= $order['payment_status'] === 'Completed' ? 'success' : 'warning' ?>">
                                            <?= htmlspecialchars($order['payment_status'] ?: 'Pending') ?>
                                        </span>
                                    </div>

                                    <p class="text-muted mb-3">
                                        <i class="bi bi-calendar3 me-2"></i>
                                        <?= date('F j, Y', strtotime($order['created_at'])) ?>
                                    </p>

                                    <div class="d-flex align-items-center mb-3">
                                        <?php if (!empty($order['first_item_image'])): ?>
                                            <img src="<?= htmlspecialchars($order['first_item_image']) ?>"
                                                alt="Order item"
                                                class="product-image me-3">
                                        <?php endif; ?>
                                        <div>
                                            <p class="mb-1"><?= $order['total_items'] ?> item(s)</p>
                                            <h6 class="mb-0"><span class="currency-symbol">₵</span><?= number_format($order['total_amount'], 2) ?></h6>
                                        </div>
                                    </div>

                                    <a href="order_success.php?order_id=<?= $order['order_id'] ?>"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>