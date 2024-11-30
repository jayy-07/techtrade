<?php
require_once '../classes/Wishlist.php';

class WishlistController {
    private $wishlist;

    public function __construct() {
        $this->wishlist = new Wishlist();
    }

    public function addToWishlist($userId, $productId) {
        try {
            if (!$userId || !$productId) {
                return ['success' => false, 'error' => 'Invalid request parameters'];
            }

            error_log("WishlistController - Adding to wishlist: User ID = $userId, Product ID = $productId");
            
            $result = $this->wishlist->addToWishlist($userId, $productId);
            
            if ($result) {
                error_log("WishlistController - Successfully added to wishlist");
                return ['success' => true];
            } else {
                error_log("WishlistController - Failed to add to wishlist");
                return ['success' => false, 'error' => 'Failed to add to wishlist'];
            }
        } catch (Exception $e) {
            error_log("WishlistController - Error: " . $e->getMessage());
            return ['success' => false, 'error' => 'An error occurred'];
        }
    }

    public function removeFromWishlist($userId, $productId) {
        try {
            if (!$userId || !$productId) {
                return ['success' => false, 'error' => 'Invalid request parameters'];
            }

            error_log("WishlistController - Removing from wishlist: User ID = $userId, Product ID = $productId");
            
            $result = $this->wishlist->removeFromWishlist($userId, $productId);
            
            if ($result) {
                error_log("WishlistController - Successfully removed from wishlist");
                return ['success' => true];
            } else {
                error_log("WishlistController - Failed to remove from wishlist");
                return ['success' => false, 'error' => 'Failed to remove from wishlist'];
            }
        } catch (Exception $e) {
            error_log("WishlistController - Error: " . $e->getMessage());
            return ['success' => false, 'error' => 'An error occurred'];
        }
    }

    public function getWishlistItems($userId) {
        try {
            if (!$userId) {
                return [];
            }

            error_log("WishlistController - Getting wishlist items for user: $userId");
            
            $items = $this->wishlist->getWishlistItems($userId);
            error_log("WishlistController - Retrieved " . count($items) . " items");
            
            return $items;
        } catch (Exception $e) {
            error_log("WishlistController - Error: " . $e->getMessage());
            return [];
        }
    }

    public function isInWishlist($userId, $productId) {
        try {
            if (!$userId || !$productId) {
                return false;
            }

            error_log("WishlistController - Checking wishlist status: User ID = $userId, Product ID = $productId");
            
            return $this->wishlist->isInWishlist($userId, $productId);
        } catch (Exception $e) {
            error_log("WishlistController - Error: " . $e->getMessage());
            return false;
        }
    }
} 