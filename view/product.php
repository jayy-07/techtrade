<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/WishlistController.php';

// Check if the product_id is provided in the URL

$product_id = $_GET['product_id'];

// Fetch product details using the ProductController
$productController = new ProductController();
$product = $productController->getProduct()->get_product_by_id($product_id);

// Fetch cheapest seller and other sellers for this product
$cheapestSeller = $productController->getProduct()->get_cheapest_offer($product_id);
$otherSellers = $productController->getProduct()->get_other_sellers($product_id, $cheapestSeller['seller_id']);

// Fetch product images
$productImages = $productController->getProduct()->get_product_images($product_id);

// Error handling if the product is not found
if (!$product) {
    echo "Product not found.";
    exit;
}

$wishlistController = new WishlistController();
$isInWishlist = isset($_SESSION['user_id']) ? 
    $wishlistController->isInWishlist($_SESSION['user_id'], $product['product_id']) : 
    false;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['name'] ?></title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <div id="productCarousel" class="carousel slide mb-3">
                    <div class="carousel-inner">
                        <?php if (!empty($productImages)): ?>
                            <?php foreach ($productImages as $index => $image): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= htmlspecialchars($image['image_path']) ?>" 
                                         class="d-block w-100" 
                                         alt="Product Image <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img src="../images/placeholder.png" class="d-block w-100" alt="No Image Available">
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <div class="carousel-indicators">
                        <?php if (!empty($productImages)): ?>
                            <?php foreach ($productImages as $index => $image): ?>
                                <button type="button" 
                                        data-bs-target="#productCarousel" 
                                        data-bs-slide-to="<?= $index ?>" 
                                        class="<?= $index === 0 ? 'active' : '' ?>"
                                        aria-current="<?= $index === 0 ? 'true' : 'false' ?>" 
                                        aria-label="Slide <?= $index + 1 ?>">
                                    <img src="<?= htmlspecialchars($image['image_path']) ?>" 
                                         alt="Thumbnail <?= $index + 1 ?>">
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <button type="button" 
                                    data-bs-target="#productCarousel" 
                                    data-bs-slide-to="0" 
                                    class="active"
                                    aria-current="true" 
                                    aria-label="Slide 1">
                                <img src="../images/placeholder.png" alt="No Image Available">
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="listing.php?category=<?= $product['category_id'] ?>">
                                <?= $product['category_name'] ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="listing.php?brand=<?= $product['brand_id'] ?>">
                                <?= $product['brand_name'] ?>
                            </a>
                        </li>
                    </ol>
                </nav>
                <h2 class="product-title"><?= $product['name'] ?></h2>
                <p class="text-muted">Sold by: <strong><?= $cheapestSeller['seller_name'] ?? 'N/A' ?></strong></p>
                <p class="product-description"><?= $product['description'] ?></p>

                <div class="mb-3">
                    <h4 class="product-price">$<?= $cheapestSeller['price'] ?? 'N/A' ?></h4>
                    <?php if (isset($cheapestSeller['discount']) && $cheapestSeller['discount'] > 0) : ?>
                        <p class="text-muted"><del>$<?= number_format($cheapestSeller['price'] / (1 - ($cheapestSeller['discount'] / 100)), 2) ?></del> | <?= round($cheapestSeller['discount'])  ?>% OFF</p>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button
                        type="button"
                        class="btn add-to-cart-btn btn-techtrade-primary flex-grow-1"
                        data-bs-toggle="modal"
                        data-bs-target="#tradeInModal"
                        data-product-id="<?= $product['product_id'] ?>"
                        data-seller-id="<?= $cheapestSeller['seller_id'] ?? '' ?>"
                        data-product-name="<?= $product['name'] ?>"
                        data-seller-name="<?= $cheapestSeller['seller_name'] ?? '' ?>"
                        data-price="<?= $cheapestSeller['price'] ?? '' ?>">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button 
                            class="btn btn-link wishlist-btn p-0"
                            style="margin-left: 1rem;"
                            data-product-id="<?= $product['product_id'] ?>"
                            title="<?= $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?>">
                            <i class="bi bi-heart<?= $isInWishlist ? '-fill text-danger' : '' ?> fs-4"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <?php if (!empty($otherSellers)): ?>
            <h4>Other Sellers on TechTrade</h4>
        <?php endif; ?>
        <div class="other-sellers-container">
            <button class="scroll-btn prev-btn" style="display: none;" onclick="scrollSellersLeft()">
                <span>&#10094;</span>
            </button>
            <div class="other-sellers-row">
                <?php foreach ($otherSellers as $seller) : ?>
                    <div class="seller-card">
                        <p class="card-text text-success">$<?= $seller['price'] ?></p>
                        <?php if (isset($seller['discount']) && $seller['discount'] > 0) : ?>
                            <p class="card-text text-muted">
                                <del>$<?= number_format($seller['price'] / (1 - ($seller['discount'] / 100)), 2) ?></del>
                            </p>
                            <p class="card-text">
                                <span class="badge bg-success"><?= round($seller['discount']) ?>% Off</span>
                            </p>
                        <?php endif; ?>
                        <p><strong>Seller:</strong> <?= $seller['seller_name'] ?></p>
                        <button
                            type="button"
                            class="btn add-to-cart-btn btn-techtrade-primary flex-grow-1"
                            data-bs-toggle="modal"
                            data-bs-target="#tradeInModal"
                            data-product-id="<?= $seller['product_id'] ?>"
                            data-seller-id="<?= $seller['seller_id'] ?? '' ?>"
                            data-product-name="<?= $product['name'] ?>"
                            data-seller-name="<?= $seller['seller_name'] ?? '' ?>"
                            data-price="<?= $seller['price'] ?? '' ?>">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="scroll-btn next-btn" onclick="scrollSellersRight()">
                <span>&#10095;</span>
            </button>
        </div>
    </div>

    <div class="modal fade" id="tradeInModal" tabindex="-1" aria-labelledby="tradeInModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tradeInModalLabel">Add to Cart - Trade-In Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modalTradeInToggle">
                        <label class="form-check-label" for="modalTradeInToggle">
                            <i class="bi bi-arrow-left-right"></i> Trade-in your device
                        </label>
                    </div>

                    <div id="modalDeviceTypeDropdown" class="mt-3" style="display: none;">
                        <label for="modalDeviceType" class="form-label">Select your device type:</label>
                        <select id="modalDeviceType" class="form-select">
                            <option selected disabled>Select your device</option>
                            <option value="phone">Phone</option>
                        </select>
                    </div>

                    <div id="modalDeviceConditionDropdown" class="mt-3" style="display: none;">
                        <label for="modalDeviceCondition" class="form-label">Device Condition:</label>
                        <select id="modalDeviceCondition" class="form-select">
                            <option selected disabled>Select condition</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>

                    <div class="mt-3" id="usageDurationField" style="display: none;">
                        <label for="modalUsageDuration" class="form-label">Usage Duration:</label>
                        <select id="modalUsageDuration" class="form-select">
                            <option selected disabled>Select duration</option>
                            <option value="Less than 6 months">Less than 6 months</option>
                            <option value="6-12 months">6-12 months</option>
                            <option value="1-2 years">1-2 years</option>
                            <option value="2-3 years">2-3 years</option>
                            <option value="More than 3 years">More than 3 years</option>
                        </select>
                    </div>
                    <div class="mt-3" id="purchasePriceField" style="display: none;">
                        <label for="modalPurchasePrice" class="form-label">Purchase Price:</label>
                        <input type="number" class="form-control" id="modalPurchasePrice">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmTradeInButton" class="btn btn-primary"><i class="bi bi-cart me-2"></i>Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>


    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/product_page.js"></script>


</body>

</html>