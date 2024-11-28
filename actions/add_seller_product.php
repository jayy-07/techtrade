<?php
require_once '../controllers/SellerProductController.php';
require_once '../settings/core.php';

// Assuming you have a function to handle error logging (e.g., in core.php)
// function log_error($error_message) { ... }

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
?>