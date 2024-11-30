<?php
require_once '../settings/db_class.php';

class Wishlist extends db_connection {
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

    public function getWishlistItems($userId) {
        try {
            $this->db_connect();
            
            $sql = "SELECT w.*, p.name as product_name, 
                    pi.image_path,
                    MIN(sp.price) as min_price,
                    MAX(sp.discount) as max_discount
                    FROM wishlists w
                    JOIN products p ON w.product_id = p.product_id
                    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                    LEFT JOIN sellers_products sp ON p.product_id = sp.product_id
                    WHERE w.user_id = ?
                    GROUP BY w.product_id
                    ORDER BY w.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting wishlist: " . $e->getMessage());
            return [];
        }
    }

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