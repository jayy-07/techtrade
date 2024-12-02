<?php
// Include required controller and core settings
require_once '../controllers/SellerProductController.php';
require_once '../settings/core.php';

/* if (!is_logged_in() || !check_user_role('seller')) {
    redirect('../login/login.php');
} */

/**
 * Logs error messages to a file
 * @param string $error_message The error message to log
 * @return void
 */
function log_error($error_message)
{
    $error_log_file = '../error/product_errors.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}

// Check if request is GET and product_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    // Initialize controller and get product ID
    $sellerProductController = new SellerProductController();
    $product_id = $_GET['product_id'];

    try {
        // Attempt to delete the product for the current seller
        $response = $sellerProductController->delete_product($_SESSION['user_id'], $product_id);

        // Check if deletion was successful
        if (is_string($response) && strpos($response, 'successfully') !== false) {
            // Prepare success response
            $jsonResponse = [
                'success' => true,
                'message' => $response,
                'product_id' => $product_id,
                'action' => 'delete'
            ];

            echo json_encode($jsonResponse);
        } else {
            // Return error response if deletion failed
            echo json_encode(['success' => false, 'message' => $response]);
        }
    } catch (Exception $e) {
        // Log and return any exceptions that occur
        log_error('Error deleting seller product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
