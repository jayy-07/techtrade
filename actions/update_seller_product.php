<?php
require_once '../controllers/SellerProductController.php';
require_once '../settings/core.php';

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sellerProductController = new SellerProductController();

    try {
        $response = $sellerProductController->update_product($_POST);

        if (is_string($response) && strpos($response, 'successfully') !== false) {
            $product_id = $_POST['product_id'];
            $product = $sellerProductController->get_product_by_id($_SESSION['user_id'], $product_id);

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

            echo json_encode($jsonResponse);
        } else {
            echo json_encode(['success' => false, 'message' => $response]);
        }
    } catch (Exception $e) {
        log_error('Error updating seller product: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
}
