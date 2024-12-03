<?php
require_once '../settings/db_class.php';


/**
 * Wishlist class for managing user wishlists
 * Handles CRUD operations for wishlists and wishlist items
 * Extends database connection class
 */
class Wishlist extends db_connection {
    
    /**
     * Adds a product to the user's wishlist
     * If product already exists, only updates timestamp
     * @param int $userId ID of the user
     * @param int $productId ID of the product to add
     * @return bool True on success, false on failure
     */
    public function addToWishlist($userId, $productId) {
        try {
            $this->db_connect();
            
            $sql = "INSERT INTO wishlists (user_id, product_id) 
                    VALUES (?, ?) 
                    ON DUPLICATE KEY UPDATE created_at = created_at";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error adding to wishlist: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Removes a product from the user's wishlist
     * @param int $userId ID of the user
     * @param int $productId ID of the product to remove
     * @return bool True on success, false on failure
     */
    public function removeFromWishlist($userId, $productId) {
        try {
            $this->db_connect();
            
            $sql = "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error removing from wishlist: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all items in the user's wishlist
     * Gets product details including name, image, minimum price and maximum discount
     * @param int $userId ID of the user
     * @return array Array of wishlist items sorted by creation date
     */
    public function getWishlistItems($userId) {
        try {
            $this->db_connect();
            
            $sql = "SELECT 
                    w.product_id,
                    MAX(w.wishlist_id) as wishlist_id,
                    MAX(w.created_at) as created_at,
                    p.name as product_name,
                    MAX(pi.image_path) as image_path,
                    MIN(sp.price) as min_price,
                    MAX(sp.discount) as max_discount
                    FROM wishlists w
                    JOIN products p ON w.product_id = p.product_id
                    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                    LEFT JOIN sellers_products sp ON p.product_id = sp.product_id
                    WHERE w.user_id = ?
                    GROUP BY w.product_id
                    ORDER BY MAX(w.created_at) DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting wishlist: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Checks if a product is in the user's wishlist
     * @param int $userId ID of the user
     * @param int $productId ID of the product to check
     * @return bool True if product is in wishlist, false if not
     */
    public function isInWishlist($userId, $productId) {
        try {
            $this->db_connect();
            
            $sql = "SELECT 1 FROM wishlists WHERE user_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
            
            return $stmt->get_result()->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error checking wishlist: " . $e->getMessage());
            return false;
        }
    }
}