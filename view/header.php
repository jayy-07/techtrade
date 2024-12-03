<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button
                class="btn me-3"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasMenu"
                aria-controls="offcanvasMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand font-weight-bold d-flex align-items-center" id="logo-text" href="<?= $_SESSION['role'] === 'administrator' ? '../admin/users.php' : ($_SESSION['role'] === 'seller' ? '../seller/seller_inventory.php' : '../view/home.php') ?>">
                <img src="../images/header_logo.png" alt="Logo" style="width: 25px; height: 25px; margin-right: 10px;" />
                TechTrade
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto d-flex align-items-center" id="navbar-right">
                    <li class="nav-item dropdown" id="dropdown-menu">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> Hi, <?= htmlspecialchars($_SESSION['first_name']) ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <?php if ($_SESSION['role'] === 'Administrator'): ?>
                                <a class="dropdown-item" href="../admin/dashboard.php">
                                    <i class="bi bi-speedometer2 me-2"></i> Admin Dashboard
                                </a>
                            <?php elseif ($_SESSION['role'] === 'Seller'): ?>
                                <a class="dropdown-item" href="../seller/dashboard.php">
                                    <i class="bi bi-shop me-2"></i> Seller Dashboard
                                </a>
                            <?php endif; ?>
                            <a class="dropdown-item" href="../view/account.php">
                                <i class="bi bi-person-circle me-2"></i> My Account
                            </a>
                            <a class="dropdown-item" href="../view/cart.php">
                                <i class="bi bi-cart me-2"></i> Cart
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../view/orders.php">
                                <i class="bi bi-box me-2"></i> Your Orders
                            </a>
                            <a class="dropdown-item" href="../view/wishlist.php">
                                <i class="bi bi-heart me-2"></i> Wishlist
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../login/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 d-flex" style="width: 350px;" method="get" action="search.php">
                    <input
                        id="search-input"
                        class="form-control me-2"
                        type="search"
                        name="q"
                        placeholder="Search products..."
                        aria-label="Search"
                        value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" />
                    <button id="search-btn" class="btn btn-primary-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
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
                <?php
                require_once '../controllers/CategoryController.php';
                $categoryController = new CategoryController();
                $categories = $categoryController->getCategory()->getAllCategories();

                foreach ($categories as $category):
                ?>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <a class="nav-link text-white" href="listing.php?category=<?= $category['category_id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Shop by Brand Section -->
            <h6 class="text-uppercase mt-4 mb-3">Shop by Brand</h6>
            <ul class="nav flex-column">
                <?php
                require_once '../controllers/BrandController.php';
                $brandController = new BrandController();
                $brands = $brandController->getBrand()->getAllBrands();

                foreach ($brands as $brand):
                ?>
                    <li class="nav-item d-flex align-items-center mb-2">
                        <a class="nav-link text-white" href="listing.php?brand=<?= $brand['brand_id'] ?>">
                            <?= htmlspecialchars($brand['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</header>