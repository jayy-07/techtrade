<?php
require_once '../controllers/CategoryController.php';
require_once '../controllers/BrandController.php';

$categoryController = new CategoryController();
$brandController = new BrandController();

// Get filter parameters
$categoryId = isset($_GET['category']) ? $_GET['category'] : null;
$brandId = isset($_GET['brand']) ? $_GET['brand'] : null;
$priceRange = isset($_GET['price']) ? $_GET['price'] : null;
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : null;

// Get products based on filters
$products = [];
if ($categoryId) {
    $products = $categoryController->getCategory()->getProductsByCategory($categoryId, $brandId, $priceRange, $sortBy);
} elseif ($brandId) {
    $products = $brandController->getBrand()->getProductsByBrand($brandId, $categoryId, $priceRange, $sortBy);
}

// If no products found, show empty state
if (empty($products)) {
    ?>
    <div class="col-12 text-center py-5">
        <div class="empty-state">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3>No Products Found</h3>
            <p class="text-muted">
                <?php if ($categoryId || $brandId): ?>
                    We couldn't find any products matching your current filters.
                    Try adjusting your filters or browsing all categories.
                <?php else: ?>
                    Please select a category or brand to start browsing products.
                <?php endif; ?>
            </p>
            <?php if ($categoryId || $brandId || $priceRange || $sortBy): ?>
                <button class="btn btn-outline-primary" onclick="resetFilters()">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php
    exit;
}

// Display products
foreach ($products as $product): ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="product.php?product_id=<?= $product['product_id'] ?>" 
           class="card product-card text-center shadow-sm",
           style="width: 250px;">
            <div class="product-card-img-wrapper">
                <img src="<?= htmlspecialchars($product['image_path']) ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="card-body">
                <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                <p class="card-text text-success">$<?= number_format($product['min_price'], 2) ?></p>
                <?php if ($product['max_discount'] > 0): ?>
                    <p class="card-text text-muted">
                        <del>$<?= number_format($product['min_price'] / (1 - ($product['max_discount'] / 100)), 2) ?></del>
                    </p>
                    <p class="card-text">
                        <span class="badge bg-success"><?= round($product['max_discount']) ?>% Off</span>
                    </p>
                <?php endif; ?>
            </div>
        </a>
    </div>
<?php endforeach; ?>
