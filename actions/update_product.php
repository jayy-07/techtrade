<?php
// Include required controllers and settings
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../controllers/CategoryController.php';
require_once '../settings/core.php';

/**
 * Logs error messages to a file
 * @param string $error_message The error message to log
 */
function log_error($error_message)
{
    $error_log_file = '../error/product_errors.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize product controller
    $productController = new ProductController();

    try {
        // Attempt to update the product
        $response = $productController->edit($_POST['product_id']);

        // Check if update was successful
        if (is_string($response) && strpos($response, 'successfully') !== false) {
            $product_id = $_POST['product_id'];
            // Get updated product details
            $product = $productController->getProduct()->get_product_by_id($product_id);

            // Get categories and brands for reference
            $categoryController = new CategoryController();
            $categories = $categoryController->index();

            $brandController = new BrandController();
            $brands = $brandController->index();

            // Find category name from category ID
            $categoryName = null;
            foreach ($categories as $category) {
                if ($category['category_id'] == $product['category_id']) {
                    $categoryName = $category['name'];
                    break;
                }
            }

            // Find brand name from brand ID
            $brandName = null;
            foreach ($brands as $brand) {
                if ($brand['brand_id'] == $product['brand_id']) {
                    $brandName = $brand['name'];
                    break;
                }
            }

            // Prepare success response with updated product details
            $jsonResponse = [
                'success' => true,
                'message' => $response,
                'product_id' => $product_id,
                'product_name' => $product['name'],
                'category_name' => $categoryName,
                'brand_name' => $brandName,
                'category_id' => $product['category_id'],
                'brand_id' => $product['brand_id'],
                'description' => $product['description'],
                'action' => 'edit'
            ];

            echo json_encode($jsonResponse);
        } else {
            // Return error response if update failed
            $jsonResponse = [
                'success' => false,
                'message' => $response
            ];

            echo json_encode($jsonResponse);
        }
    } catch (Exception $e) {
        // Log any exceptions and return generic error message
        log_error('Error updating product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
