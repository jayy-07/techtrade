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
                                <?= "my_name" ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person-circle me-2"></i> My account
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-cart me-2"></i> Cart
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