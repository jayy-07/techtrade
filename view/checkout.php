<?php
require_once '../settings/core.php';
require_once '../classes/Cart.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize cart and get items
$cart = new Cart();
$cartItems = $cart->getCartItems($_SESSION['user_id']);
$cartTotal = $cart->getCartTotal($_SESSION['user_id']);

// Redirect if cart is empty
if (empty($cartItems)) {
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
    <style>
        .order-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .price-breakdown {
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
            margin: 15px 0;
        }
        .checkout-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .checkout-item:last-child {
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
        <h2 class="mb-4">Checkout</h2>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Shipping Information</h5>
                        <form id="checkoutForm" class="mt-3">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="<?= htmlspecialchars($_SESSION['user_email']) ?>" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                    value="<?= htmlspecialchars($_SESSION['user_phone'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Items</h5>
                        <div id="orderItems">
                            <?php foreach ($cartItems as $item): ?>
                            <div class="checkout-item d-flex align-items-center">
                                <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>" 
                                     alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                     class="product-image me-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= htmlspecialchars($item['product_name']) ?></h6>
                                    <small class="text-muted">Quantity: <?= $item['quantity'] ?></small>
                                    <?php if (!empty($item['trade_in_id'])): ?>
                                    <br>
                                    <small class="text-info">
                                        <i class="bi bi-arrow-left-right"></i> 
                                        Trade-in Applied: $<?= number_format($item['trade_in_value'], 2) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <div class="h6 mb-0">$<?= number_format($item['total_discounted_price'], 2) ?></div>
                                    <?php if ($item['discount'] > 0): ?>
                                    <small class="text-muted">
                                        <del>$<?= number_format($item['total_original_price'], 2) ?></del>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="order-summary">
                    <h5 class="mb-4">Order Summary</h5>
                    <div class="price-breakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>$<?= number_format($cartTotal['subtotal'], 2) ?></span>
                        </div>
                        <?php if ($cartTotal['total_discount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount</span>
                            <span>-$<?= number_format($cartTotal['total_discount'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($cartTotal['total_trade_in'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-info">
                            <span>Trade-in Credit</span>
                            <span>-$<?= number_format($cartTotal['total_trade_in'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">Total</span>
                        <span class="h5">$<?= number_format($cartTotal['final_total'], 2) ?></span>
                    </div>
                    <button type="button" id="payButton" class="btn btn-primary w-100">
                        <i class="bi bi-credit-card me-2"></i>Proceed to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script>
        const PAYSTACK_PUBLIC_KEY = '<?php echo 'pk_test_57eea14bbe9565b4e145426b118d39f67b2527ec'; ?>';
    </script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/checkout.js"></script>
</body>
</html>
