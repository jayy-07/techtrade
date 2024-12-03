<?php
require_once '../controllers/SectionController.php';
require_once '../settings/core.php';
check_login();

$sectionController = new SectionController();
$sectionsWithProducts = $sectionController->getAllSectionsWithProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet" />
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="homeCarousel" class="carousel slide container-xxl" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../images/pexels-fauxels-3184451.jpg" class="d-block w-100" alt="First Slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Welcome to TechTrade</h5>
                    <p>Your one stop shop for all your tech needs</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../images/confidence.jpeg" class="d-block w-100" alt="Second Slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Shop with confidence</h5>
                    <p>We offer a wide range of products from top brands</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../images/pexels-shvetsa-4482896.jpg" class="d-block w-100" alt="Third Slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Secure</h5>
                    <p>We use the latest security measures to ensure your data is safe</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <?php foreach ($sectionsWithProducts as $section): ?>
        <div class="container mt-4">
            <div class="scrollable-product-list position-relative">
                <h4><?= htmlspecialchars($section['section']['name']) ?></h4>

                <button class="scroll-btn prev-btn" style="display: none;" onclick="scrollRowLeft(this.nextElementSibling)">
                    <span>&#10094;</span>
                </button>

                <div class="product-row d-flex overflow-auto" onscroll="updateScrollButtons(this)">
                    <?php foreach ($section['products'] as $product): ?>
                        <a href="product.php?product_id=<?= $product['product_id'] ?>"
                            class="card product-card text-center shadow-sm mx-2"
                            style="width: 250px;">
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
                    <?php endforeach; ?>
                </div>
                <button class="scroll-btn next-btn" onclick="scrollRowRight(this.previousElementSibling)">
                    <span>&#10095;</span>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    <?php include 'footer.php'; ?>

</body>

<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/jquery.min.js"></script>
<script>
    // Scroll the product row to the right
    function scrollRowRight(prevBtn) {
        const container = prevBtn.parentElement.querySelector(".product-row");
        container.scrollLeft += 300; // Adjust scroll distance as needed
    }


    // Scroll the product row to the left
    function scrollRowLeft(nextBtn) {
        // Changed to use scrollLeft property directly
        const container = nextBtn.parentElement.querySelector(".product-row");
        container.scrollLeft -= 300; // Adjust scroll distance as needed
    }

    // Show or hide scroll buttons based on scroll position
    function updateScrollButtons(container) {
        const prevBtn = container.parentElement.querySelector(".prev-btn");
        const nextBtn = container.parentElement.querySelector(".next-btn");

        // Show 'prev' button if content has scrolled right
        prevBtn.style.display = container.scrollLeft > 0 ? "block" : "none";

        // Show 'next' button if content is still scrollable to the right
        const maxScrollLeft = container.scrollWidth - container.clientWidth;
        nextBtn.style.display = container.scrollLeft < maxScrollLeft ? "block" : "none";
    }

    // Call updateScrollButtons immediately after DOM is ready
    document.addEventListener("DOMContentLoaded", function() {
        const containers = document.querySelectorAll(".product-row");
        containers.forEach(container => updateScrollButtons(container));
    });

    // Add scroll event listener to update button visibility on manual scroll
    document.querySelectorAll(".product-row").forEach(container => container.addEventListener("scroll", function() {
        updateScrollButtons(container);
    }));

    // Update on window resize
    window.addEventListener("resize", function() {
        const containers = document.querySelectorAll(".product-row");
        containers.forEach(container => updateScrollButtons(container));
    });
</script>