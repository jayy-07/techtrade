<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';

// Ensure only admin users can access this page
check_admin();

// Validate order ID is provided in URL parameters
if (!isset($_GET['order_id'])) {
    echo "Order ID not provided";
    exit;
}

// Initialize controller and fetch order details
$orderController = new OrderController();
$orderDetails = $orderController->getOrderDetails($_GET['order_id']);

// Check if order exists
if (!$orderDetails) {
    echo "Order not found";
    exit;
}
?>

<!-- Order Details Container -->
<div class="order-details">
    <!-- Customer Information Section -->
    <div class="customer-info mb-4">
        <h6>Customer Information</h6>
        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($orderDetails['customer_name']) ?></p>
        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($orderDetails['customer_email']) ?></p>
        <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($orderDetails['customer_phone']) ?></p>
        <p class="mb-1"><strong>Shipping Address:</strong> <?= htmlspecialchars($orderDetails['shipping_address']) ?></p>
    </div>

    <!-- Payment Status Section -->
    <div class="payment-info mb-4">
        <h6>Payment Information</h6>
        <p class="mb-1"><strong>Status:</strong>
            <span class="badge bg-<?= $orderDetails['payment_status'] === 'Completed' ? 'success' : 'warning' ?>">
                <?= htmlspecialchars($orderDetails['payment_status']) ?>
            </span>
        </p>
    </div>

    <!-- Order Items Table Section -->
    <div class="order-items">
        <h6>Order Items</h6>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 35%">Product</th>
                        <th style="width: 20%">Seller</th>
                        <th style="width: 10%">Quantity</th>
                        <th style="width: 15%">Price</th>
                        <th style="width: 20%">Trade-in</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDetails['items'] as $item): ?>
                        <tr>
                            <!-- Product Column with Image and Name -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?= htmlspecialchars($item['image_path']) ?>"
                                            alt="Product"
                                            style="width: 50px; height: 50px; object-fit: contain; margin-right: 10px;">
                                    <?php endif; ?>
                                    <div class="text-truncate"
                                        style="max-width: 200px;"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="<?= htmlspecialchars($item['product_name']) ?>">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </div>
                                </div>
                            </td>
                            <!-- Seller Name -->
                            <td class="text-truncate" style="max-width: 150px;">
                                <?= htmlspecialchars($item['seller_name']) ?>
                            </td>
                            <!-- Quantity and Price -->
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td><span class="currency-symbol">₵</span><?= number_format($item['price'], 2) ?></td>
                            <!-- Trade-in Details if Available -->
                            <td>
                                <?php if (!empty($item['trade_in_details'])):
                                    $tradeIn = json_decode($item['trade_in_details'], true);
                                ?>
                                    <small class="text-info">
                                        <i class="bi bi-arrow-left-right"></i>
                                        Device: <?= htmlspecialchars($tradeIn['device_type']) ?><br>
                                        Value: <span class="currency-symbol">₵</span><?= number_format($tradeIn['trade_in_value'], 2) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Total Summary -->
    <div class="order-summary mt-3">
        <div class="d-flex justify-content-between">
            <strong>Total Amount:</strong>
            <strong>$<?= number_format($orderDetails['total_amount'], 2) ?></strong>
        </div>
    </div>
</div>

<script>
    // Initialize Bootstrap tooltips for truncated product names
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>