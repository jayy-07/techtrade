<?php
// Include required controllers
require_once '../controllers/CategoryController.php';
require_once '../controllers/BrandController.php';

// Initialize controllers
$categoryController = new CategoryController();
$brandController = new BrandController();

// Get filter parameters from URL query string
$categoryId = isset($_GET['category']) ? $_GET['category'] : null;
$brandId = isset($_GET['brand']) ? $_GET['brand'] : null;
$priceRange = isset($_GET['price']) ? $_GET['price'] : null;
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : null;

// Get filtered products based on category or brand
$products = [];
if ($categoryId) {
    // Get products filtered by category and optional brand/price/sort
    $products = $categoryController->getCategory()->getProductsByCategory($categoryId, $brandId, $priceRange, $sortBy);
} elseif ($brandId) {
    // Get products filtered by brand and optional category/price/sort
    $products = $brandController->getBrand()->getProductsByBrand($brandId, $categoryId, $priceRange, $sortBy);
}

// Display empty state if no products found
if (empty($products)) {
?>
    <div class="col-12 text-center py-5">
        <div class="empty-state">
            <!-- Empty state icon -->
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3>No Products Found</h3>
            <!-- Custom message based on filter state -->
            <p class="text-muted">
                <?php if ($categoryId || $brandId): ?>
                    We couldn't find any products matching your current filters.
                    Try adjusting your filters or browsing all categories.
                <?php else: ?>
                    Please select a category or brand to start browsing products.
                <?php endif; ?>
            </p>
            <!-- Show reset button only if filters are applied -->
            <?php if ($categoryId || $brandId || $priceRange || $sortBy): ?>
                <button class="btn btn-techtrade-outline" onclick="resetFilters()">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php
    exit;
}

// Display product grid
foreach ($products as $product): ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <!-- Product card with link to detail page -->
        <a href="product.php?product_id=<?= $product['product_id'] ?>"
            class="card product-card text-center shadow-sm" ,
            style="width: 250px;">
            <!-- Product image container -->
            <div class="product-card-img-wrapper">
                <img src="<?= htmlspecialchars($product['image_path']) ?>"
                    class="card-img-top"
                    alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <!-- Product details -->
            <div class="card-body">
                <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                <p class="card-text text-success"><span class="currency-symbol">₵</span><?= number_format($product['min_price'], 2) ?></p>
                <!-- Show discount info if applicable -->
                <?php if ($product['max_discount'] > 0): ?>
                    <p class="card-text text-muted">
                        <del>₵<?= number_format($product['min_price'] / (1 - ($product['max_discount'] / 100)), 2) ?></del>
                    </p>
                    <p class="card-text">
                        <span class="badge bg-success"><?= round($product['max_discount']) ?>% Off</span>
                    </p>
                <?php endif; ?>
            </div>
        </a>
    </div>
<?php endforeach; ?>