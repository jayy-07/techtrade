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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sellerProductController = new SellerProductController();

    try {
        // Add product to seller's inventory
        $response = $sellerProductController->add_product($_POST);

        if (is_string($response) && strpos($response, 'successfully') !== false) {
            // Product added successfully
            echo $response;
        } else {
            // Error occurred
            echo $response;
        }
    } catch (Exception $e) {
        log_error('Error adding seller product: ' . $e->getMessage());
        echo 'Failed to add product to inventory.';
    }
}
