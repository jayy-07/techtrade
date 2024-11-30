<?php
require_once '../settings/core.php';
require_once '../controllers/WishlistController.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$wishlistController = new WishlistController();
$items = $wishlistController->getWishlistItems($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <h2 class="mb-4">My Wishlist</h2>
        
        <?php if (empty($items)): ?>
            <div class="text-center py-5">
                <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Your wishlist is empty</h4>
                <p class="text-muted">Browse our products and add items you like to your wishlist!</p>
                <a href="home.php" class="btn btn-techtrade-primary mt-3">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($items as $item): ?>
                    <div class="col">
                        <div class="card h-100 product-card shadow-sm">
                            <div class="position-relative product-card-img-wrapper">
                                <a href="product.php?product_id=<?= $item['product_id'] ?>">
                                    <img src="<?= htmlspecialchars($item['image_path'] ?? '../images/placeholder.png') ?>" 
                                         class="card-img-top product-image" 
                                         alt="<?= htmlspecialchars($item['product_name']) ?>">
                                </a>
                                <button 
                                    class="btn wishlist-remove-btn position-absolute"
                                    data-product-id="<?= $item['product_id'] ?>"
                                    title="Remove from Wishlist">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate mb-2">
                                    <a href="product.php?product_id=<?= $item['product_id'] ?>" 
                                       class="text-decoration-none text-dark"
                                       title="<?= htmlspecialchars($item['product_name']) ?>">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </a>
                                </h5>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0 product-price">$<?= number_format($item['min_price'], 2) ?></h5>
                                            <?php if ($item['max_discount'] > 0): ?>
                                                <small class="text-success">
                                                    <?= round($item['max_discount']) ?>% OFF
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="product.php?product_id=<?= $item['product_id'] ?>" 
                                       class="btn btn-techtrade-primary w-100">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
    <script src="../js/wishlist.js"></script>
</body>
</html>