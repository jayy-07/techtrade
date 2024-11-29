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

$tradeInDetails = $trade_in ? [
    'device_type' => $data['device_type'],
    'device_condition' => $data['device_condition'],
    'usage_duration' => $data['usage_duration'],
    'purchase_price' => $data['purchase_price']
] : null;

// Validate purchase price
if ($trade_in && $tradeInDetails['purchase_price'] <= 0) {
    echo json_encode(['success' => false, 'error' => 'Purchase price must be greater than zero.']);
    exit;
}

// Calculate trade-in value
function calculateTradeInValue($tradeInDetails) {
    $baseValue = $tradeInDetails['purchase_price'];
    $conditionMultiplier = [
        'Excellent' => 0.8,
        'Good' => 0.6,
        'Fair' => 0.4,
        'Poor' => 0.2
    ];
    $usageMultiplier = [
        'Less than 6 months' => 1.0,
        '6-12 months' => 0.9,
        '1-2 years' => 0.7,
        '2-3 years' => 0.5,
        'More than 3 years' => 0.3
    ];

    $conditionValue = $conditionMultiplier[$tradeInDetails['device_condition']] ?? 0;
    $usageValue = $usageMultiplier[$tradeInDetails['usage_duration']] ?? 0;

    return $baseValue * $conditionValue * $usageValue;
}

if ($trade_in) {
    $tradeInValue = calculateTradeInValue($tradeInDetails);

    // Validate trade-in value
    if ($tradeInValue > $price) {
        echo json_encode(['success' => false, 'error' => 'Trade-in value cannot exceed the product price.']);
        exit;
    }

    // Validate final price
    if (($price - $tradeInValue) <= 0) {
        echo json_encode(['success' => false, 'error' => 'Final price must be greater than zero.']);
        exit;
    }
}

// Add to cart logic
try {
    $result = $cartController->addToCart(
        $_SESSION['user_id'], // Assuming user_id is stored in session
        $product_id,
        $seller_id,
        $price,
        $tradeInDetails
    );

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}