<?php
header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/CartController.php';

$cartController = new CartController();

// Retrieve data from AJAX request
$data = $_POST;

$product_id = $data['product_id'];
$seller_id = $data['seller_id'];
$product_name = $data['product_name'];
$seller_name = $data['seller_name'];
$price = $data['price'];
$trade_in = isset($data['trade_in']) && $data['trade_in'] === 'true';

// Get original price from sellers_products
try {
    $db = new db_connection();
    $db->db_connect();
    $sql = "SELECT price FROM sellers_products WHERE product_id = ?";
    $stmt = $db->db->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $productData = $result->fetch_assoc();
    $originalPrice = $productData['price'];

    if ($trade_in) {
        $tradeInDetails = [
            'device_type' => $data['device_type'],
            'device_condition' => $data['device_condition'],
            'usage_duration' => $data['usage_duration'],
            'purchase_price' => $data['purchase_price']
        ];

        // Validate purchase price
        if ($tradeInDetails['purchase_price'] <= 0) {
            echo json_encode(['success' => false, 'error' => 'Purchase price must be greater than zero.']);
            exit;
        }

        // Calculate trade-in value
        $tradeInValue = $cartController->calculateTradeInValue($tradeInDetails, $originalPrice);

        // Validate trade-in value against original price
        if ($tradeInValue >= $originalPrice * 0.9) {
            echo json_encode(['success' => false, 'error' => 'Trade-in value cannot exceed 90% of product price.']);
            exit;
        }

        // Validate final price
        if (($price - $tradeInValue) <= 0) {
            echo json_encode(['success' => false, 'error' => 'Final price must be greater than zero.']);
            exit;
        }
    } else {
        $tradeInDetails = null;
    }

    // Add to cart
    $result = $cartController->addToCart(
        $_SESSION['user_id'],
        $product_id,
        $seller_id,
        $price,
        $tradeInDetails
    );

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
