<?php
header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/WishlistController.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Please login first']);
    exit;
}

$wishlistController = new WishlistController();
$action = $_POST['action'] ?? '';
$productId = $_POST['product_id'] ?? '';

if ($action === 'add') {
    $result = $wishlistController->addToWishlist($_SESSION['user_id'], $productId);
} else if ($action === 'remove') {
    $result = $wishlistController->removeFromWishlist($_SESSION['user_id'], $productId);
}

echo json_encode($result); 