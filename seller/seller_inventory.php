<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../controllers/CategoryController.php';
require_once 'header.php';
check_seller();


// Get all products
$productController = new ProductController();
$products = $productController->index();

// Get all brands
$brandController = new BrandController();
$brands = $brandController->index();

// Get all categories
$categoryController = new CategoryController();
$categories = $categoryController->index();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller - Inventory</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <ul class="nav nav-pills mt-4 mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="seller_inventory.php">Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="seller_orders.php">Orders</a>
            </li>
        </ul>

        <div class="d-flex justify-content-between mb-3">
            <h2>Inventory</h2>
            <button type="button" class="btn btn-techtrade-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                Add Product
            </button>
        </div>

        <div id="main-content">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 25%">Product Name</th>
                            <th style="width: 15%">Category</th>
                            <th style="width: 15%">Brand</th>
                            <th style="width: 10%">Price</th>
                            <th style="width: 10%">Discount</th>
                            <th style="width: 10%">Stock</th>
                            <th style="width: 15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sellerId = $_SESSION['user_id'];
                        $sellerProducts = $productController->getProduct()->get_seller_products($sellerId);

                        foreach ($sellerProducts as $product) :
                        ?>
                            <tr data-product-id="<?= $product['product_id'] ?>">
                                <td class="product-name-cell">
                                    <div class="text-truncate"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="<?= htmlspecialchars($product['product_name']) ?>">
                                        <?= htmlspecialchars($product['product_name']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($product['category_name']) ?></td>
                                <td><?= htmlspecialchars($product['brand_name']) ?></td>
                                <td><span class="currency-symbol">₵</span><?= number_format($product['price'], 2) ?></td>
                                <td><?= $product['discount'] ?>%</td>
                                <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-product" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                        data-product-id="<?= $product['product_id'] ?>"
                                        data-product-name="<?= htmlspecialchars($product['product_name']) ?>"
                                        data-product-price="<?= htmlspecialchars($product['price']) ?>"
                                        data-product-stock="<?= htmlspecialchars($product['stock_quantity']) ?>"
                                        data-product-discount="<?= htmlspecialchars($product['discount']) ?>"
                                        data-product-description="<?= htmlspecialchars($product['description']) ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-product" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-product-id="<?= $product['product_id'] ?>" data-product-name="<?= htmlspecialchars($product['product_name']) ?>">
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

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product to Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="" disabled selected>Select product</option>
                                <?php foreach ($products as $product) : ?>
                                    <option value="<?= htmlspecialchars($product['product_id']) ?>"><?= htmlspecialchars($product['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (<span class="currency-symbol">₵</span>)</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount" step="0.01" min="0" max="99.99" value="0">
                        </div>
                        <p class="text-danger" id="error-message"></p>
                        <button type="submit" class="btn btn-primary" id="saveProduct">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product in Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_product_id" name="product_id">
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price (<span class="currency-symbol">₵</span>)</label>
                            <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount" class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" id="edit_discount" name="discount" step="0.01" min="0" max="99.99">
                        </div>
                        <p class="text-danger" id="error-message"></p>
                        <button type="submit" class="btn btn-primary" id="updateProduct">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Product from Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteProductName"></strong> from your inventory?
                    This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteProduct">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/seller_inventory.js"></script>
    <script src="../js/tooltips.js"></script>
    <script>
        $(document).ready(function() {
            // Open Edit Modal
            $(".edit-product").on("click", function() {
                const productId = $(this).data("product-id");
                const productName = $(this).data("product-name");
                const productPrice = $(this).data("product-price");
                const productStock = $(this).data("product-stock");

                $("#editProductForm #edit_product_id").val(productId);
                $("#editProductForm #edit_product_name").val(productName);
                $("#editProductForm #edit_price").val(productPrice);
                $("#editProductForm #edit_stock_quantity").val(productStock);
            });

            // Open Delete Modal
            $(".delete-product").on("click", function() {
                const productId = $(this).data("product-id");
                const productName = $(this).data("product-name");

                $("#deleteProductName").text(productName);
                $("#confirmDeleteProduct").data("product-id", productId);
            });
        });
    </script>
</body>

</html>