<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet" />
</head>


<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button
                    class="btn me-3"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand font-weight-bold" id="logo-text" href="home.php">TechTrade</a>
                <ul class="navbar-nav w-100 d-flex align-items-center" id="navbar-right">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown" id="dropdown-menu">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="#" class="mr-3 rounded-circle d-block" alt="Profile Photo" style="width: 30px; height: 30px; margin-right: 15px;" />
                                <?= "my_name" ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person-circle me-2"></i> My account
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-box me-2"></i> Your orders
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-star me-2"></i> Reviews
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-heart me-2"></i> Wishlist
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../login/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                </a>
                            </div>

                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0 d-flex" method="get" action="#">
                        <input id="search-input" class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search" />
                        <button id="search-btn" class="btn" type="submit">Search</button>
                    </form>
                </ul>
            </div>
        </nav>

        <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <!-- Categories Section -->
                <h6 class="text-uppercase mb-3">Categories</h6>
                <ul class="nav flex-column">
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Laptops</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Smartphones Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Smartphones</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Accessories Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Accessories</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="TVs & Monitors Icon" class="me-2">
                        <a class="nav-link text-white" href="#">TVs & Monitors</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Cameras Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Cameras</a>
                    </li>
                </ul>

                <!-- Shop by Brand Section -->
                <h6 class="text-uppercase mt-4 mb-3">Shop by Brand</h6>
                <ul class="nav flex-column">
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Apple</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Samsung</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Sony</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Dell</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">Microsoft</a>
                    </li>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/30" alt="Laptops Icon" class="me-2">
                        <a class="nav-link text-white" href="#">HP</a>
                    </li>
                </ul>
            </div>
        </div>

    </header>
    <div class="container mt-5" id="review-container">

        <!-- Start of a Single Review Card -->
        <div class="row mb-4">
            <!-- Game Image -->
            <div class="col-md-2">
                <a href="product_page.php?product_id=101">
                    <img src="https://via.placeholder.com/200" class="img-fluid" style="object-fit: cover; width: 200px; height: 200px; border-radius: 5px;" alt="Product Image">
                </a>
            </div>

            <!-- Review Content -->
            <div class="col-md-8">
                <!-- Product Title -->
                <a href="product_page.php?product_id=101" style="text-decoration: none; color: inherit;">
                    <p style="font-size: 30px;"><strong>Apple iPhone 15 128GB (Unlocked)</strong></p>
                </a>

                <!-- Star Rating -->
                <div class="rating-group">
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star text-secondary"></span>
                </div>

                <!-- Review Text -->
                <div class="review">
                    <p style="margin-top: 10px;">Fantastic camera and battery life, but a bit pricey.</p>
                </div>

                <!-- Edit and Delete Buttons -->
                <br>
                <div class="buttons">
                    <button type="button" id="editButton" class="btn btn-primary" data-review-id="101" data-bs-toggle="modal" data-bs-target="#editReviewModal">Edit your review</button>
                    <button type="button" id="deleteButton" style="margin-left: 10px;" class="btn btn-danger" data-review-id="101" data-bs-toggle="modal" data-bs-target="#deleteReviewModal">Delete your review</button>
                </div>
            </div>
        </div>
        <hr class="mb-4">

        <!-- Repeat this block for additional reviews -->

        <div class="row mb-4">
            <div class="col-md-2">
                <a href="product_page.php?product_id=102">
                    <img src="https://via.placeholder.com/200" class="img-fluid" style="object-fit: cover; width: 200px; height: 200px; border-radius: 5px;" alt="Product Image">
                </a>
            </div>
            <div class="col-md-8">
                <a href="product_page.php?product_id=102" style="text-decoration: none; color: inherit;">
                    <p style="font-size: 30px;"><strong>Samsung Galaxy S23 Ultra</strong></p>
                </a>
                <div class="rating-group">
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                </div>
                <div class="review">
                    <p style="margin-top: 10px;">Absolutely love the display and performance. Worth every penny!</p>
                </div>
                <br>
                <div class="buttons">
                    <button type="button" id="editButton" class="btn btn-primary" data-review-id="102" data-bs-toggle="modal" data-bs-target="#editReviewModal">Edit your review</button>
                    <button type="button" id="deleteButton" style="margin-left: 10px;" class="btn btn-danger" data-review-id="102" data-bs-toggle="modal" data-bs-target="#deleteReviewModal">Delete your review</button>
                </div>
            </div>
        </div>
        <hr class="mb-4">

    </div>



    <!-- Edit Review Modal -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">Edit Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Star Rating -->
                    <div class="rating-group">
                        <input class="rating__input rating__input--none" name="rating" id="rating-none" value="0" type="radio">
                        <label aria-label="No rating" class="rating__label" for="rating-none"><i class="rating__icon rating__icon--none fa fa-ban"></i></label>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <label aria-label="<?= $i ?> star" class="rating__label" for="rating-<?= $i ?>"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                            <input class="rating__input" name="rating" id="rating-<?= $i ?>" value="<?= $i ?>" type="radio">
                        <?php endfor; ?>
                    </div>
                    <!-- Review Text -->
                    <textarea id="reviewText" class="form-control mt-3" rows="3" maxlength="4000" placeholder="What did you think about the product?"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Review Modal -->
    <div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-labelledby="deleteReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteReviewModalLabel">Delete Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this review?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../js/jquery.min.js"></script>