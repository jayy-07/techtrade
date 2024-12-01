<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';

// Ensure admin access
check_admin();

if (!isset($_GET['order_id'])) {
    http_response_code(400);
    exit('Order ID not provided');
}

$orderController = new OrderController();
$order = $orderController->getOrder($_GET['order_id']);

if (!$order) {
    http_response_code(404);
    exit('Order not found');
}

// Format the order details as HTML
?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h6>Customer Information</h6>
            <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
            <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
        </div>
        <div class="col-md-6">
            <h6>Order Information</h6>
            <p class="mb-1"><strong>Order ID:</strong> #<?= htmlspecialchars($order['order_id']) ?></p>
            <p class="mb-1"><strong>Date:</strong> <?= date('M j, Y H:i', strtotime($order['created_at'])) ?></p>
            <p class="mb-1">
                <strong>Status:</strong> 
                <span class="badge bg-<?= $order['payment_status'] === 'Completed' ? 'success' : 'warning' ?>">
                    <?= htmlspecialchars($order['payment_status']) ?>
                </span>
            </p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h6>Order Items</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image_path']) ?>" 
                                             alt="<?= htmlspecialchars($item['product_name']) ?>"
                                             class="me-2"
                                             style="width: 40px; height: 40px; object-fit: contain;">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </div>
                                </td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h6>Shipping Address</h6>
            <p class="mb-0"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
        </div>
    </div>
</div> 