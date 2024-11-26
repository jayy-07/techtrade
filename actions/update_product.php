<?php
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../controllers/CategoryController.php';
require_once '../settings/core.php';

function log_error($error_message)
{
    $error_log_file = '../error/product_errors.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productController = new ProductController();

    try {
        $response = $productController->edit($_POST['product_id']);

        if (is_string($response) && strpos($response, 'successfully') !== false) {
            $product_id = $_POST['product_id'];
            $product = $productController->getProduct()->get_product_by_id($product_id);

            $categoryController = new CategoryController();
            $categories = $categoryController->index();

            $brandController = new BrandController();
            $brands = $brandController->index();

            // Get category name
            $categoryName = null;
            foreach ($categories as $category) {
                if ($category['category_id'] == $product['category_id']) {
                    $categoryName = $category['name'];
                    break;
                }
            }

            // Get brand name
            $brandName = null;
            foreach ($brands as $brand) {
                if ($brand['brand_id'] == $product['brand_id']) {
                    $brandName = $brand['name'];
                    break;
                }
            }

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
            // Error occurred
            $jsonResponse = [
                'success' => false,
                'message' => $response
            ];

            echo json_encode($jsonResponse);
        }
    } catch (Exception $e) {
        log_error('Error updating product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
