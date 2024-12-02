<?php
// Include required controllers and settings
require_once '../controllers/SellerProductController.php';
require_once '../settings/core.php';

/**
 * Logs error messages to a file
 * @param string $error_message The error message to log
 */
function log_error($error_message)
{
    $error_log_file = '../error/php-error.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}

/* if (!is_logged_in() || !check_user_role('seller')) {
    redirect('../login/login.php');
}
 */

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize seller product controller
    $sellerProductController = new SellerProductController();

    try {
        // Attempt to update the product with POST data
        $response = $sellerProductController->update_product($_POST);

        // Check if update was successful
        if (is_string($response) && strpos($response, 'successfully') !== false) {
            // Get product ID from POST data
            $product_id = $_POST['product_id'];
            
            // Fetch updated product details
            $product = $sellerProductController->get_product_by_id($_SESSION['user_id'], $product_id);

            // Prepare success response with updated product details
            $jsonResponse = [
                'success' => true,
                'message' => $response,
                'product_id' => $product_id,
                'product_name' => $product['product_name'],
                'category_name' => $product['category_name'],
                'brand_name' => $product['brand_name'],
                'price' => $product['price'],
                'stock_quantity' => $product['stock_quantity'],
                'discount' => $product['discount'], // Include discount in response
                'action' => 'edit'
            ];

            // Return success response
            echo json_encode($jsonResponse);
        } else {
            // Return error response if update failed
            echo json_encode(['success' => false, 'message' => $response]);
        }
    } catch (Exception $e) {
        // Log any errors and return generic error message
        log_error('Error updating seller product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
