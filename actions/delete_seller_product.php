<?php
require_once '../controllers/SellerProductController.php';
require_once '../settings/core.php';

/* if (!is_logged_in() || !check_user_role('seller')) {
    redirect('../login/login.php');
} */

function log_error($error_message)
{
    $error_log_file = '../error/product_errors.log';
    $log_message = date('Y-m-d H:i:s') . ' - ' . $error_message . PHP_EOL;
    error_log($log_message, 3, $error_log_file);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $sellerProductController = new SellerProductController();
    $product_id = $_GET['product_id'];

    try {
        $response = $sellerProductController->delete_product($_SESSION['user_id'], $product_id);

        if (is_string($response) && strpos($response, 'successfully') !== false) {
            $jsonResponse = [
                'success' => true,
                'message' => $response,
                'product_id' => $product_id,
                'action' => 'delete'
            ];

            echo json_encode($jsonResponse);
        } else {
            echo json_encode(['success' => false, 'message' => $response]);
        }
    } catch (Exception $e) {
        log_error('Error deleting seller product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
