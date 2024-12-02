<?php
require_once '../controllers/ProductController.php';
require_once '../settings/core.php';

function log_error($error_message)
{
    $error_log_file = '../error/product_errors.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $productController = new ProductController();
    $product_id = $_GET['product_id'];

    try {
        $response = $productController->delete($product_id);

        if (is_string($response) && strpos($response, 'successfully') !== false) {
            // Product deleted successfully
            $jsonResponse = [
                'success' => true,
                'message' => $response,
                'product_id' => $product_id,
                'action' => 'delete' // Adding action for products.js
            ];

            echo json_encode($jsonResponse); // Echo the entire JSON response
        } else {
            // Error occurred
            $jsonResponse = [
                'success' => false,
                'message' => $response
            ];

            echo json_encode($jsonResponse); // Echo the JSON response with the error
        }
    } catch (Exception $e) {
        log_error('Error deleting product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
