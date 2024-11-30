<?php
require_once '../controllers/CartController.php';
require_once '../settings/core.php';

// Initialize cart controller
$cartController = new CartController();
$cartItems = $cartController->getCartItems($_SESSION['user_id']);
$cartTotal = $cartController->getCartTotal($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <h2 class="mb-4">Shopping Cart</h2>
        
        <?php if (empty($cartItems)): ?>
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">
                        Start shopping to add items to your cart.
                    </p>
                    <a href="home.php" class="btn btn-primary">
                        <i class="bi bi-shop"></i> Continue Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="card mb-3 cart-item" data-cart-item-id="<?= htmlspecialchars($item['cart_item_id']) ?>">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <div class="image-container">
                                        <a href="product.php?productid=<?= htmlspecialchars($item['product_id']) ?>" class="d-block h-100">
                                            <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>" 
                                                 class="img-fluid rounded-start w-100 h-100" 
                                                 alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                 style="object-fit: contain; padding: 10px;">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body h-100 d-flex flex-column">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title text-truncate" title="<?= htmlspecialchars($item['product_name']) ?>">
                                                <a href="product.php?product_id=<?= htmlspecialchars($item['product_id']) ?>" class="text-decoration-none text-dark">
                                                    <?= htmlspecialchars($item['product_name']) ?>
                                                </a>
                                            </h5>
                                            <button class="btn btn-link text-danger remove-item" title="Remove item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <p class="text-muted text-truncate" title="Sold by: <?= htmlspecialchars($item['seller_name']) ?>">
                                            Sold by: <?= htmlspecialchars($item['seller_name']) ?>
                                        </p>
                                        
                                        <?php if (isset($item['trade_in']) && $item['trade_in']): ?>
                                            <div class="alert alert-info mb-2 small">
                                                <i class="bi bi-arrow-left-right"></i> Trade-in Applied
                                                <ul class="mb-0 mt-1">
                                                    <li>Device: <?= htmlspecialchars($item['device_type']) ?></li>
                                                    <li>Condition: <?= htmlspecialchars($item['device_condition']) ?></li>
                                                    <li>Trade-in Value: $<?= number_format($item['trade_in_value'], 2) ?></li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mt-auto d-flex align-items-center">
                                            <div class="input-group" style="width: 130px;">
                                                <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease">-</button>
                                                <input type="number" class="form-control text-center quantity-input" 
                                                       value="<?= $item['quantity'] ?>" 
                                                       min="1" 
                                                       max="<?= $item['stock_quantity'] ?>">
                                                <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase">+</button>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <p class="h5 mb-0">$<?= number_format($item['total_discounted_price'], 2) ?></p>
                                                <?php if ($item['discount'] > 0): ?>
                                                    <small class="text-muted">
                                                        <del>$<?= number_format($item['total_original_price'], 2) ?></del>
                                                        (<?= number_format($item['discount'], 0) ?>% off)
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Order Summary</h5>
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
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h5 mb-0">Total</span>
                                <span class="h5 mb-0">$<?= number_format($cartTotal['final_total'], 2) ?></span>
                            </div>
                            <button class="btn btn-primary w-100" id="checkoutBtn">
                                <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCartItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Item from Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove <strong id="deleteItemName"></strong> from your cart?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteItem">Remove</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/cart.js"></script>
</body>
</html> 