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
                <a class="navbar-brand font-weight-bold d-flex align-items-center" id="logo-text" href="home.php">
                    <img src="../images/header_logo.png" alt="Logo" style="width: 25px; height: 25px; margin-right: 10px;" />
                    TechTrade
                </a>

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
    <div class="container my-4">
        <!-- Filter and Sort Options -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-4">
                <h5>Filters</h5>

                <!-- Category Filter -->
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select mb-3" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="phones">Phones</option>
                    <option value="laptops">Laptops</option>
                    <option value="tablets">Tablets</option>
                </select>

                <!-- Price Filter -->
                <label for="priceFilter" class="form-label">Price Range</label>
                <select class="form-select mb-3" id="priceFilter">
                    <option value="">All Prices</option>
                    <option value="0-100">$0 - $100</option>
                    <option value="100-500">$100 - $500</option>
                    <option value="500-1000">$500 - $1000</option>
                    <option value="1000+">$1000+</option>
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
                    <!-- Example Product Card -->
                    <a href="/product-page-1" class="card product-card text-center shadow-sm mx-2 mt-3" style="width: 270px;">
                        <div class="product-card-img-wrapper">
                            <img src="https://m.media-amazon.com/images/I/61-oTP1X4rL._AC_SL1500_.jpg" class="card-img-top" alt="Product Image">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Apple iPhone 15 128GB (Unlocked)</h6>
                            <p class="card-text text-success">$559.99</p>
                            <p class="card-text text-muted"><del>$799.99</del></p>
                            <p class="card-text"><span class="badge bg-success">10% Off</span></p>
                        </div>
                    </a>

                    <a href="/product-page-1" class="card product-card text-center shadow-sm mx-2 mt-3" style="width: 270px;">
                        <div class="product-card-img-wrapper">
                            <img src="https://m.media-amazon.com/images/I/61-oTP1X4rL._AC_SL1500_.jpg" class="card-img-top" alt="Product Image">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Apple iPhone 15 128GB (Unlocked)</h6>
                            <p class="card-text text-success">$559.99</p>
                            <p class="card-text text-muted"><del>$799.99</del></p>
                            <p class="card-text"><span class="badge bg-success">10% Off</span></p>
                        </div>
                    </a>

                    <a href="/product-page-1" class="card product-card text-center shadow-sm mx-2 mt-3" style="width: 270px;">
                        <div class="product-card-img-wrapper">
                            <img src="https://m.media-amazon.com/images/I/61-oTP1X4rL._AC_SL1500_.jpg" class="card-img-top" alt="Product Image">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Apple iPhone 15 128GB (Unlocked)</h6>
                            <p class="card-text text-success">$559.99</p>
                            <p class="card-text text-muted"><del>$799.99</del></p>
                            <p class="card-text"><span class="badge bg-success">10% Off</span></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


</body>
<script src="../js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../js/jquery.min.js"></script>