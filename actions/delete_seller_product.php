<?php
require_once '../controllers/SellerProductController.php';
require_once '../settings/core.php';

// ... (log_error function)

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
?>