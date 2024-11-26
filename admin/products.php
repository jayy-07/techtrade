<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';

// Check if user is logged in and is an administrator
/* if (!is_logged_in() || !check_user_role('Administrator')) {
    redirect('../login.php');
} */

$controller = new ProductController();
$products = $controller->getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Products</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class='text-center mb-3'>
            <a class="navbar-brand font-weight-bold d-flex align-items-center justify-content-center" id="logo-text" href="home.php">
                <img src="../images/header_logo.png" alt="Logo" style="width: 25px; height: 25px; margin-right: 10px;" />
                TechTrade
            </a>
        </h1>

        <!-- Navigation Pills -->
        <ul class="nav nav-pills mt-4 mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="users.php">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Reports</a>
            </li>
        </ul>

        <div class="d-flex justify-content-between mb-3">
            <h2>Products</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProductModal">
                Add Product
            </button>
        </div>

        <!-- Main Content -->
        <div id="main-content">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr data-product-id="<?= $product['product_id'] ?>">
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['brand_id']) ?></td>
                                <td><?= htmlspecialchars($product['category_id']) ?></td>
                                <td><?= htmlspecialchars($product['status']) ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-product"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editProductModal"
                                        data-product-id="<?= $product['product_id'] ?>"
                                        data-product-name="<?= htmlspecialchars($product['name']) ?>"
                                        data-product-description="<?= htmlspecialchars($product['description']) ?>"
                                        data-product-brand="<?= htmlspecialchars($product['brand_id']) ?>"
                                        data-product-category="<?= htmlspecialchars($product['category_id']) ?>"
                                        data-product-status="<?= htmlspecialchars($product['status']) ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-product"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteProductModal"
                                        data-product-id="<?= $product['product_id'] ?>"
                                        data-product-name="<?= htmlspecialchars($product['name']) ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="productId" name="product_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-select" id="productBrand" name="brand_id" required>
                                <!-- Populate brands dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="productCategory" name="category_id" required>
                                <!-- Populate categories dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="productStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Images</label>
                            <input type="file" class="form-control" id="productImages" name="images[]" multiple>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProductChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteProductName"></strong>?
                    This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteProduct">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Message -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Dynamic message goes here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/products.js"></script>
</body>

</html>
