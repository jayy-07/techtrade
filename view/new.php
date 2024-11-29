<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';

// Check if the product_id is provided in the URL

    $product_id = 58;

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['name'] ?> - TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet" />
    <style>
        .product-image {
            max-width: 100%;
            height: auto;
        }

        .other-sellers .card {
            width: 200px;
            /* Adjust width as needed */
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        </header>

    <main>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php 
                            // Dynamically generate carousel indicators based on the number of images
                            for ($i = 0; $i < count($productImages); $i++) : 
                            ?>
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>" aria-current="true" aria-label="Slide <?= $i + 1 ?>"></button>
                            <?php endfor; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php 
                            // Dynamically generate carousel items with product images
                            foreach ($productImages as $index => $image) : 
                            ?>
                                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                    <img src="<?= $image['image_path'] ?>" class="d-block w-100" alt="Product Image <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Category: <?= $product['category_name'] ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $product['brand_name'] ?></li>
                        </ol>
                    </nav>
                    <h2 class="product-title"><?= $product['name'] ?></h2>
                    <p class="text-muted">Sold by: <strong><?= $cheapestSeller['seller_name'] ?? 'N/A' ?></strong></p>
                    <p class="product-description"><?= $product['description'] ?></p>

                    <div class="mb-3">
                        <h4 class="product-price">$<?= $cheapestSeller['price'] ?? 'N/A' ?></h4>
                        <?php if (isset($cheapestSeller['discount']) && $cheapestSeller['discount'] > 0) : ?>
                            <p class="text-muted"><del>$<?= number_format($cheapestSeller['price'] / (1 - ($cheapestSeller['discount'] / 100)), 2) ?></del> | <?= $cheapestSeller['discount'] ?>% OFF</p>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tradeInModal">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <h4>Other Sellers on TechTrade</h4>
                    <div class="other-sellers-container">
                        <div class="other-sellers-row">
                            <?php foreach ($otherSellers as $seller) : ?>
                                <div class="seller-card">
                                    <p class="card-text text-success">$<?= $seller['price'] ?></p>
                                    <?php if (isset($seller['discount']) && $seller['discount'] > 0) : ?>
                                        <p class="text-muted"><del>$<?= number_format($seller['price'] / (1 - ($seller['discount'] / 100)), 2) ?></del> | <?= $seller['discount'] ?>% OFF</p>
                                    <?php endif; ?>
                                    <p><strong>Seller:</strong> <?= $seller['seller_name'] ?></p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tradeInModal">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <h3>Reviews</h3>
                    </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="tradeInModal" tabindex="-1" aria-labelledby="tradeInModalLabel" aria-hidden="true">
        </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>