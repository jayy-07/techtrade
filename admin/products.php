<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../controllers/CategoryController.php';
check_admin();

$productController = new ProductController();
$products = $productController->index();

$brandController = new BrandController();
$brands = $brandController->index();

$categoryController = new CategoryController();
$categories = $categoryController->index();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Products</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/home.css" rel="stylesheet">
</head>

<?php include 'header.php'; ?>

<body>
    <div class="container mt-4">
        <ul class="nav nav-pills mt-4 mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="users.php">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_orders.php">Orders</a>
            </li>
        </ul>

        <div class="d-flex justify-content-between mb-3">
            <h2>Products</h2>
            <button type="button" class="btn btn-techtrade-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                Add Product
            </button>
        </div>

        <div id="main-content">
            <div class="table-responsive">
                <table class="table table-striped admin-table">
                    <thead>
                        <tr>
                            <th style="width: 35%">Name</th>
                            <th style="width: 20%">Category</th>
                            <th style="width: 20%">Brand</th>
                            <th style="width: 25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr data-product-id="<?= $product['product_id'] ?>">
                                <td class="product-name-cell">
                                    <div class="text-truncate" 
                                         data-bs-toggle="tooltip" 
                                         data-bs-placement="top" 
                                         title="<?= htmlspecialchars($product['name']) ?>"
                                         style="max-width: 250px;">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    // Find the category name
                                    $categoryId = $product['category_id'];
                                    $categoryName = null;
                                    foreach ($categories as $category) {
                                        if ($category['category_id'] == $categoryId) { // Use 'category_id'
                                            $categoryName = $category['name'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($categoryName);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Find the brand name
                                    $brandId = $product['brand_id'];
                                    $brandName = null;
                                    foreach ($brands as $brand) {
                                        if ($brand['brand_id'] == $brandId) { // Use 'brand_id'
                                            $brandName = $brand['name'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($brandName);
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-product" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-id="<?= $product['product_id'] ?>" data-product-name="<?= htmlspecialchars($product['name']) ?>" data-product-category-id="<?= htmlspecialchars($product['category_id']) ?>" data-product-brand-id="<?= htmlspecialchars($product['brand_id']) ?>" data-product-description="<?= htmlspecialchars($product['description']) ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-product" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-product-id="<?= $product['product_id'] ?>" data-product-name="<?= htmlspecialchars($product['name']) ?>">
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
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" disabled selected>Select category</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= htmlspecialchars($category['category_id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-select" id="brand_id" name="brand_id" required>
                                <option value="" disabled selected>Select brand</option>
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?= htmlspecialchars($brand['brand_id']) ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Images</label>
                            <?php for ($i = 0; $i < 4; $i++) : ?>
                                <input type="text" class="form-control mb-2" name="images[]" placeholder="Enter image URL <?= $i + 1 ?>">
                            <?php endfor; ?>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProduct">Save Product</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_product_id" name="product_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="" disabled selected>Select category</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= htmlspecialchars($category['category_id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-select" id="edit_brand_id" name="brand_id" required>
                                <option value="" disabled selected>Select brand</option>
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?= htmlspecialchars($brand['brand_id']) ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Images</label>
                            <?php for ($i = 0; $i < 4; $i++) : ?>
                                <input type="text" class="form-control mb-2 edit-image-url" name="images[]" placeholder="Enter image URL <?= $i + 1 ?>">
                            <?php endfor; ?>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateProduct">Update Product</button>
                </div>
            </div>
        </div>
    </div>

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

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/products.js"></script>
    <script src="../js/tooltips.js"></script>
</body>

</html>