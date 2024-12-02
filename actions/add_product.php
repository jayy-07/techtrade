<?php
require_once '../controllers/ProductController.php';
require_once '../controllers/BrandController.php';
require_once '../controllers/CategoryController.php';
require_once '../settings/core.php';

function log_error($error_message) {
    $error_log_file = '../error/product_errors.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productController = new ProductController();

    try {
        $response = $productController->create();

        if (is_string($response) && strpos($response, 'successfully') !== false) {
            echo $response; 
        } else {
            echo $response; 
        }
    } catch (Exception $e) {
        log_error('Error adding product: ' . $e->getMessage());
        // Send a generic error message to the user
        echo 'Failed to add product.'; 
    }
}
?>