<?php
require_once '../controllers/CartController.php';
require_once '../settings/core.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartController = new CartController();
    
    $cartItemId = $_POST['cart_item_id'];
    $result = $cartController->removeItem($cartItemId);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Item removed successfully' : 'Failed to remove item'
    ]);
} 