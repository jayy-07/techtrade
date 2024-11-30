<?php
require_once '../controllers/CartController.php';
require_once '../settings/core.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartController = new CartController();
    
    $cartItemId = $_POST['cart_item_id'];
    $quantity = $_POST['quantity'];
    
    $result = $cartController->updateQuantity($cartItemId, $quantity);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Quantity updated successfully' : 'Failed to update quantity'
    ]);
} 