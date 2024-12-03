<?php
require_once '../controllers/CategoryController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../settings/core.php';
check_login();

$categoryController = new CategoryController();
$brandController = new BrandController();

$categories = $categoryController->getCategory()->getAllCategories();
$brands = $brandController->getBrand()->getAllBrands();

// Get initial filters from URL
$currentCategoryId = isset($_GET['category']) ? $_GET['category'] : null;
$currentBrandId = isset($_GET['brand']) ? $_GET['brand'] : null;

// Get initial products based on filters
$products = [];
if ($currentCategoryId) {
    $products = $categoryController->getCategory()->getProductsByCategory($currentCategoryId);
} elseif ($currentBrandId) {
    $products = $brandController->getBrand()->getProductsByBrand($currentBrandId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Listing</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet" />
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="container my-4">
            <div class="row mb-4">
                <!-- Filters Column -->
                <div class="col-lg-3 col-md-4">
                    <h5>Filters</h5>

                    <!-- Category Filter -->
                    <label for="categoryFilter" class="form-label">Category</label>
                    <select class="form-select mb-3" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>"
                                <?= $currentCategoryId == $category['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Brand Filter -->
                    <label for="brandFilter" class="form-label">Brand</label>
                    <select class="form-select mb-3" id="brandFilter">
                        <option value="">All Brands</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['brand_id'] ?>"
                                <?= $currentBrandId == $brand['brand_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($brand['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Price Filter -->
                    <label for="priceFilter" class="form-label">Price Range</label>
                    <select class="form-select mb-3" id="priceFilter">
                        <option value="">All Prices</option>
                        <option value="0-100"><span class="currency-symbol">₵</span>0 - <span class="currency-symbol">₵</span>100</option>
                        <option value="100-500"><span class="currency-symbol">₵</span>100 - <span class="currency-symbol">₵</span>500</option>
                        <option value="500-1000"><span class="currency-symbol">₵</span>500 - <span class="currency-symbol">₵</span>1000</option>
                        <option value="1000-+"><span class="currency-symbol">₵</span>1000+</option>
                    </select>

                    <!-- Sort Options -->
                    <label for="sortOptions" class="form-label">Sort by</label>
                    <select class="form-select" id="sortOptions">
                        <option value="default">Default</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="discount">Best Discounts</option>
                    </select>
                </div>

                <!-- Product Listings Section -->
                <div class="col-lg-9 col-md-8">
                    <div class="row" id="productListing">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <a href="product.php?product_id=<?= $product['product_id'] ?>"
                                        class="card product-card text-center shadow-sm">
                                        <div class="product-card-img-wrapper">
                                            <img src="<?= htmlspecialchars($product['image_path']) ?>"
                                                class="card-img-top"
                                                alt="<?= htmlspecialchars($product['name']) ?>">
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                                            <p class="card-text text-success"><span class="currency-symbol">₵</span><?= number_format($product['min_price'], 2) ?></p>
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
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                                    <h3>No Products Found</h3>
                                    <p class="text-muted">
                                        <?php if ($currentCategoryId || $currentBrandId): ?>
                                            We couldn't find any products matching your current filters.
                                            Try adjusting your filters or browsing all categories.
                                        <?php else: ?>
                                            Please select a category or brand to start browsing products.
                                        <?php endif; ?>
                                    </p>
                                    <?php if ($currentCategoryId || $currentBrandId): ?>
                                        <button class="btn btn-techtrade-outline" onclick="resetFilters()">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Loading State -->
                    <div id="loadingState" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading products...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/listing.js"></script>
</body>

</html>