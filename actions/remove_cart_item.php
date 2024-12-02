<?php
require_once '../controllers/CartController.php';
require_once '../settings/core.php';

// Set response header to JSON
header('Content-Type: application/json');

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize cart controller
    $cartController = new CartController();
    
    // Get cart item ID from POST data
    $cartItemId = $_POST['cart_item_id'];
    
    // Attempt to remove item from cart
    $result = $cartController->removeItem($cartItemId);
    
    // Return JSON response indicating success/failure
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Item removed successfully' : 'Failed to remove item'
    ]);
} 