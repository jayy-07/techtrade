<?php
// Include required controller and settings
require_once '../controllers/CartController.php';
require_once '../settings/core.php';

// Set response header to JSON
header('Content-Type: application/json');

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize cart controller
    $cartController = new CartController();
    
    // Get cart item ID and new quantity from POST data
    $cartItemId = $_POST['cart_item_id'];
    $quantity = $_POST['quantity'];
    
    // Attempt to update item quantity in cart
    $result = $cartController->updateQuantity($cartItemId, $quantity);
    
    // Return JSON response indicating success/failure
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Quantity updated successfully' : 'Failed to update quantity'
    ]);
} 