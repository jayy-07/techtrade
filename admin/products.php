<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../controllers/CategoryController.php';

// Check if user is logged in and is an administrator
/* if (!is_logged_in() || !check_user_role('administrator')) {
    redirect('../login.php');
} */

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
    <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                Add Product
            </button>
        </div>

        <div id="main-content">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr data-product-id="<?= $product['product_id'] ?>">
                                <td><?= htmlspecialchars($product['name']) ?></td>
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
                            <textarea class="form-control" id="description" name="description"></textarea>
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
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
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

    <script src="../js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/products.js"></script>
    <script>
        $(document).ready(function() {
            // Open Edit Modal
            $(".edit-product").on("click", function() {
                const productId = $(this).data("product-id");
                const productName = $(this).data("product-name");
                const productCategoryId = $(this).data("product-category-id");
                const productBrandId = $(this).data("product-brand-id");
                const productDescription = $(this).data("product-description");

                $("#editProductForm #edit_product_id").val(productId);
                $("#editProductForm #edit_product_name").val(productName);
                $("#editProductForm #edit_category_id").val(productCategoryId);
                $("#editProductForm #edit_brand_id").val(productBrandId);
                $("#editProductForm #edit_description").val(productDescription);

                // Fetch and populate image URLs
                $.ajax({
                    url: `../actions/get_product_images.php?product_id=${productId}`,
                    type: 'GET',
                    success: function(response) {
                        const images = JSON.parse(response);
                        const imageInputs = $(".edit-image-url");
                        images.forEach((image, index) => {
                            $(imageInputs[index]).val(image.image_path);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $("#toastMessage").text("An error occurred while fetching images.");
                        $("#toastContainer").toast("show");
                    }
                });
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