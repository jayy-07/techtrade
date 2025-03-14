<?php
// Set response header to JSON
header('Content-Type: application/json');

// Include required files
require_once '../controllers/WishlistController.php';
require_once '../settings/core.php';

// Check if user is logged in
check_login();

// Initialize wishlist controller
$wishlistController = new WishlistController();

// Get action and product ID from POST data with null coalescing
$action = $_POST['action'] ?? '';
$productId = $_POST['product_id'] ?? '';

// Handle add/remove actions
if ($action === 'add') {
    $result = $wishlistController->addToWishlist($_SESSION['user_id'], $productId);
} else if ($action === 'remove') {
    $result = $wishlistController->removeFromWishlist($_SESSION['user_id'], $productId);
}

// Return JSON response
echo json_encode($result); 