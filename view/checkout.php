<?php
require_once '../settings/core.php';
require_once '../classes/Cart.php';

// Check if user is logged in
check_login();

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
    <title>Checkout</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
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
                                <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($_SESSION['user_address'] ?? '') ?></textarea>
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
                                        <div class="text-truncate" 
                                             data-bs-toggle="tooltip" 
                                             data-bs-placement="top" 
                                             title="<?= htmlspecialchars($item['product_name']) ?>"
                                             style="max-width: 300px;">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </div>
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
                                        <?php 
                                            // Calculate the original price and discounted price
                                            $originalPrice = $item['original_unit_price'] / (1 - ($item['discount'] / 100));
                                            $discountedPrice = $item['original_unit_price'];
                                        ?>
                                        <div class="h6 mb-0">$<?= number_format($discountedPrice, 2) ?></div>
                                        <?php if ($item['discount'] > 0): ?>
                                            <small class="text-muted">
                                                <del>₵<?= number_format($originalPrice, 2) ?></del>
                                                (<?= number_format($item['discount'], 0) ?>% off)
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
                            <span><span class="currency-symbol">₵</span><?= number_format($cartTotal['subtotal'], 2) ?></span>
                        </div>
                        <?php if ($cartTotal['total_discount'] > 0): ?>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount</span>
                                <span>-<span class="currency-symbol">₵</span><?= number_format($cartTotal['total_discount'], 2) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($cartTotal['total_trade_in'] > 0): ?>
                            <div class="d-flex justify-content-between mb-2 text-info">
                                <span>Trade-in Credit</span>
                                <span>-<span class="currency-symbol">₵</span><?= number_format($cartTotal['total_trade_in'], 2) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">Total</span>
                        <span class="h5"><span class="currency-symbol">₵</span><?= number_format($cartTotal['final_total'], 2) ?></span>
                    </div>
                    <button type="button" id="payButton" class="btn btn-techtrade-primary w-100">
                        <i class="bi bi-credit-card me-2"></i>Proceed to Payment
                    </button>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center text-center">
                            <div>
                                <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                                <p class="mb-1 mt-2">Secure Payment by</p>
                                <a><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 157 28" alt="Paystack Logo"
                                        style="height: 30px; filter: brightness(0); margin-bottom: 15px;">
                                        <defs></defs>
                                        <g>
                                            <path d="M22.32 2.663H1.306C.594 2.663 0 3.263 0 3.985v2.37c0 .74.594 1.324 1.307 1.324h21.012c.73 0 1.307-.602 1.324-1.323V4.002c0-.738-.594-1.34-1.323-1.34zm0 13.192H1.306a1.3 1.3 0 00-.924.388 1.33 1.33 0 00-.383.935v2.37c0 .74.594 1.323 1.307 1.323h21.012c.73 0 1.307-.584 1.324-1.322v-2.371c0-.739-.594-1.323-1.323-1.323zm-9.183 6.58H1.307c-.347 0-.68.139-.924.387a1.33 1.33 0 00-.383.935v2.37c0 .74.594 1.323 1.307 1.323H13.12c.73 0 1.307-.6 1.307-1.322v-2.371a1.29 1.29 0 00-1.29-1.323zM23.643 9.258H1.307c-.347 0-.68.14-.924.387a1.33 1.33 0 00-.383.936v2.37c0 .739.594 1.323 1.307 1.323h22.32c.73 0 1.306-.601 1.306-1.323v-2.37a1.301 1.301 0 00-1.29-1.323z" fill="#00C3F7"></path>
                                            <path d="M48.101 8.005a6.927 6.927 0 00-2.274-1.563 7.041 7.041 0 00-2.716-.55 5.767 5.767 0 00-2.63.567c-.55.263-1.046.63-1.46 1.082V7.13a.876.876 0 00-.22-.567.721.721 0 00-.56-.258h-2.937a.697.697 0 00-.56.258.796.796 0 00-.221.567v19.566c0 .206.085.412.22.566a.776.776 0 00.56.224h2.971c.204 0 .39-.086.543-.224a.7.7 0 00.238-.566v-6.683c.424.464.967.808 1.561 1.014.781.292 1.596.43 2.427.43.95 0 1.884-.173 2.75-.55a6.859 6.859 0 002.308-1.58 7.45 7.45 0 001.562-2.457 8.34 8.34 0 00.577-3.213 8.761 8.761 0 00-.577-3.229A7.775 7.775 0 0048.1 8.005zm-2.681 7.077a3.33 3.33 0 01-.696 1.117 3.177 3.177 0 01-2.36 1.013c-.458 0-.899-.086-1.306-.275a3.324 3.324 0 01-1.07-.738 3.673 3.673 0 01-.713-1.117 3.837 3.837 0 010-2.748c.153-.412.408-.79.713-1.1a3.576 3.576 0 011.07-.755 2.888 2.888 0 011.306-.275c.459 0 .9.086 1.324.274.39.19.747.43 1.053.74.305.326.526.686.696 1.099a3.976 3.976 0 01-.017 2.765zm20.808-8.778h-2.953a.728.728 0 00-.543.24.823.823 0 00-.237.585v.36a4.143 4.143 0 00-1.341-1.03 5.652 5.652 0 00-2.58-.567 7.222 7.222 0 00-5.075 2.096 7.733 7.733 0 00-1.63 2.456 8.036 8.036 0 00-.61 3.23 8.15 8.15 0 00.61 3.23 7.88 7.88 0 001.613 2.456 6.959 6.959 0 005.058 2.112c.9.018 1.782-.171 2.597-.567.509-.257.984-.6 1.358-1.03v.395c0 .206.084.412.237.567.153.137.34.223.543.223h2.953a.855.855 0 00.56-.223.768.768 0 00.221-.567V7.129a.796.796 0 00-.22-.567.697.697 0 00-.56-.258zm-3.988 8.761a3.33 3.33 0 01-.696 1.117 3.83 3.83 0 01-1.052.755c-.832.378-1.8.378-2.631 0a3.575 3.575 0 01-1.07-.755 3.326 3.326 0 01-.695-1.117 3.976 3.976 0 010-2.731c.152-.412.39-.773.696-1.1.305-.309.661-.566 1.069-.755a3.194 3.194 0 012.63 0c.391.189.748.429 1.053.738.289.327.526.687.696 1.1.34.893.34 1.872 0 2.748zm33.437-1.77a4.794 4.794 0 00-1.443-.875 10.054 10.054 0 00-1.731-.516l-2.258-.446c-.577-.103-.984-.258-1.205-.447a.712.712 0 01-.305-.567c0-.24.136-.446.424-.618.39-.206.815-.31 1.256-.275.577 0 1.154.12 1.68.343.51.224 1.019.482 1.477.79.662.413 1.222.344 1.612-.12l1.087-1.236c.203-.207.322-.481.34-.773a1.06 1.06 0 00-.408-.773c-.459-.395-1.188-.825-2.156-1.237-.967-.412-2.19-.636-3.632-.636a8.343 8.343 0 00-2.597.378 6.273 6.273 0 00-1.986 1.03 4.552 4.552 0 00-1.273 1.564 4.417 4.417 0 00-.441 1.907c0 1.22.373 2.216 1.103 2.954.73.739 1.698 1.22 2.903 1.46l2.342.516c.51.086 1.018.24 1.494.464.254.103.424.36.424.652 0 .258-.136.498-.424.705-.289.206-.764.343-1.375.343a4.051 4.051 0 01-1.85-.412 6.792 6.792 0 01-1.51-.996 2.037 2.037 0 00-.68-.378c-.271-.086-.594 0-.95.292l-1.29.979a1.147 1.147 0 00-.458 1.134c.067.43.424.858 1.086 1.357a9.543 9.543 0 005.516 1.632 8.993 8.993 0 002.699-.378 6.83 6.83 0 002.087-1.048c.56-.43 1.036-.98 1.358-1.615a4.543 4.543 0 00.475-2.01 4.168 4.168 0 00-.373-1.82 4.638 4.638 0 00-1.018-1.323zm12.899 3.574a.857.857 0 00-.645-.43c-.271 0-.543.086-.764.24a2.43 2.43 0 01-1.205.396c-.136 0-.288-.017-.424-.052a.777.777 0 01-.39-.206 1.43 1.43 0 01-.323-.446 2.092 2.092 0 01-.136-.79v-5.36h3.836a.86.86 0 00.594-.258.77.77 0 00.255-.567V7.13a.773.773 0 00-.255-.584.833.833 0 00-.577-.24h-3.836v-3.66a.736.736 0 00-.237-.584.814.814 0 00-.544-.223h-2.987a.817.817 0 00-.577.223.838.838 0 00-.254.584v3.66h-1.698a.697.697 0 00-.56.257.876.876 0 00-.22.567v2.267c0 .206.084.413.22.567a.65.65 0 00.56.258h1.698v6.373a5.14 5.14 0 00.441 2.199 4.575 4.575 0 001.137 1.477c.475.395 1.035.67 1.612.842a6.125 6.125 0 001.851.275 7.73 7.73 0 002.427-.396 4.802 4.802 0 001.918-1.202.999.999 0 00.101-1.271l-1.018-1.65zm16.175-10.565h-2.953a.728.728 0 00-.543.24.822.822 0 00-.238.585v.36a4.13 4.13 0 00-1.341-1.03 5.67 5.67 0 00-2.596-.567 7.152 7.152 0 00-5.058 2.096 7.468 7.468 0 00-1.63 2.456 8.017 8.017 0 00-.611 3.212 8.156 8.156 0 00.611 3.23c.374.91.934 1.752 1.613 2.456a7.006 7.006 0 005.041 2.13 5.884 5.884 0 002.596-.55c.51-.257.985-.6 1.358-1.03v.378c.002.21.084.41.23.557a.783.783 0 00.551.233h2.97a.78.78 0 00.781-.773V7.13a.795.795 0 00-.221-.567.696.696 0 00-.56-.258zm-3.988 8.761a3.34 3.34 0 01-.696 1.117 3.83 3.83 0 01-1.053.755 2.907 2.907 0 01-1.323.275c-.459 0-.9-.103-1.307-.275a3.576 3.576 0 01-1.07-.755 3.34 3.34 0 01-.696-1.117 3.982 3.982 0 010-2.731 3.27 3.27 0 01.696-1.1c.306-.309.662-.566 1.07-.755a3.077 3.077 0 011.307-.275c.458 0 .899.086 1.323.274.391.19.747.43 1.053.74.305.326.543.686.696 1.099a3.67 3.67 0 010 2.748zm20.198 1.615l-1.698-1.306c-.322-.257-.628-.326-.899-.223a1.82 1.82 0 00-.628.447 6.03 6.03 0 01-1.29 1.168c-.509.292-1.07.43-1.647.395a3.165 3.165 0 01-1.855-.575 3.224 3.224 0 01-1.183-1.555 4.046 4.046 0 01-.237-1.34c0-.464.067-.928.237-1.374.153-.413.374-.79.679-1.1.306-.309.662-.567 1.052-.739a3.175 3.175 0 011.324-.291 3.06 3.06 0 011.647.412 5.61 5.61 0 011.29 1.168c.169.189.373.343.611.447.271.103.577.034.882-.224l1.698-1.288c.203-.138.373-.344.441-.584a.923.923 0 00-.068-.79 7.35 7.35 0 00-2.614-2.457c-1.12-.635-2.461-.962-3.955-.962a8.163 8.163 0 00-3.072.601 7.65 7.65 0 00-2.495 1.65 7.357 7.357 0 00-1.663 2.473 8.154 8.154 0 000 6.133c.39.927.95 1.769 1.663 2.456a7.876 7.876 0 005.567 2.25c1.494 0 2.835-.326 3.955-.962a7.307 7.307 0 002.631-2.473.886.886 0 00.068-.773 1.167 1.167 0 00-.441-.584zm15.716 3.057l-4.667-6.854 3.989-5.273a.978.978 0 00.169-.86c-.068-.205-.254-.429-.746-.429h-3.157a1.39 1.39 0 00-.527.12 1.058 1.058 0 00-.458.447l-3.191 4.467h-.764V.79a.794.794 0 00-.22-.567.78.78 0 00-.56-.223h-2.954a.856.856 0 00-.56.223.72.72 0 00-.237.567v19.48c0 .223.084.43.237.567a.778.778 0 00.56.223h2.954a.856.856 0 00.56-.223.794.794 0 00.22-.567v-5.153h.849l3.479 5.342c.204.378.595.618 1.019.618h3.31c.509 0 .712-.24.797-.446a.933.933 0 00-.102-.894zM83.015 6.304h-3.31a.852.852 0 00-.662.258 1.178 1.178 0 00-.305.55l-2.445 9.104H75.7l-2.613-9.104a1.54 1.54 0 00-.255-.533.756.756 0 00-.594-.275h-3.429c-.44 0-.712.138-.831.43-.085.257-.085.55 0 .807l4.192 12.798c.068.189.17.378.323.515.17.155.39.24.627.223h1.766l-.153.413-.39 1.185c-.12.36-.34.687-.645.927a1.58 1.58 0 01-.985.327c-.305 0-.61-.069-.882-.19a3.618 3.618 0 01-.781-.463 1.29 1.29 0 00-.747-.24h-.034a.908.908 0 00-.747.463l-1.052 1.546c-.424.67-.187 1.1.085 1.34a5.36 5.36 0 001.952 1.151 7.679 7.679 0 002.495.412c1.51 0 2.783-.412 3.75-1.236a7.067 7.067 0 002.122-3.333l4.855-15.838c.102-.275.119-.567.017-.842-.085-.189-.272-.395-.73-.395z" fill="#011B33"></path>
                                        </g>
                                    </svg></a>
                                <p class="text-muted small mt-2">
                                    Your payment information is processed securely by Paystack.
                                    We do not store any payment card details.
                                </p>
                            </div>
                        </div>
                    </div>
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
    <script src="../js/tooltips.js"></script>
</body>

</html>