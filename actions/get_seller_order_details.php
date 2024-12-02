<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';
check_seller();

if (!isset($_GET['order_id'])) {
    echo "Order ID not provided";
    exit;
}

$orderController = new OrderController();
$orderDetails = $orderController->getSellerOrderDetails($_GET['order_id'], $_SESSION['user_id']);

if (!$orderDetails) {
    echo "Order not found or you don't have permission to view it";
    exit;
}

$totalAmount = 0;
foreach ($orderDetails['items'] as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>

<div class="order-details">
    <div class="customer-info mb-4">
        <h6>Customer Information</h6>
        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($orderDetails['customer_name']) ?></p>
        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($orderDetails['customer_email']) ?></p>
        <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($orderDetails['customer_phone']) ?></p>
        <p class="mb-1"><strong>Shipping Address:</strong> <?= htmlspecialchars($orderDetails['shipping_address']) ?></p>
    </div>

    <div class="payment-info mb-4">
        <h6>Payment Information</h6>
        <p class="mb-1"><strong>Status:</strong> 
            <span class="badge bg-<?= $orderDetails['payment_status'] === 'Completed' ? 'success' : 'warning' ?>">
                <?= htmlspecialchars($orderDetails['payment_status']) ?>
            </span>
        </p>
    </div>

    <div class="order-items">
        <h6>Your Items in This Order</h6>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Trade-in</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDetails['items'] as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?= htmlspecialchars($item['image_path']) ?>" 
                                             alt="Product" 
                                             style="width: 50px; height: 50px; object-fit: contain; margin-right: 10px;">
                                    <?php endif; ?>
                                    <div class="text-truncate" 
                                         data-bs-toggle="tooltip" 
                                         data-bs-placement="top" 
                                         title="<?= htmlspecialchars($item['product_name']) ?>"
                                         style="max-width: 200px;">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <?php if (!empty($item['trade_in_details'])): 
                                    $tradeIn = json_decode($item['trade_in_details'], true);
                                ?>
                                    <small class="text-info">
                                        <i class="bi bi-arrow-left-right"></i>
                                        Device: <?= htmlspecialchars($tradeIn['device_type']) ?><br>
                                        Value: $<?= number_format($tradeIn['trade_in_value'], 2) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="order-summary mt-3">
        <div class="d-flex justify-content-between">
            <strong>Total Amount for Your Items:</strong>
            <strong>$<?= number_format($totalAmount, 2) ?></strong>
        </div>
    </div>
</div> 