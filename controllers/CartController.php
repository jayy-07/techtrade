<?php

require_once '../classes/Cart.php';

class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function addToCart($userId, $productId, $price, $tradeInDetails = null) {
        return $this->cart->addToCart($userId, $productId, $price, $tradeInDetails);
    }

    public function getCartItems($userId) {
        // Validate input
        if (empty($userId)) {
            return [];
        }

        // Get cart items with all required fields
        $cartItems = $this->cart->getCartItems($userId);
        
        if (!$cartItems) {
            return [];
        }

        return $cartItems;
    }

    public function getCartTotal($userId) {
        return $this->cart->getCartTotal($userId);
    }

    public function updateQuantity($cartItemId, $quantity) {
        return $this->cart->updateCartItemQuantity($cartItemId, $quantity);
    }

    public function removeItem($cartItemId) {
        return $this->cart->removeCartItem($cartItemId);
    }

    public function emptyCart($userId) {
        try {
            $db = $this->cart->db_connect();
            
            // Get the cart ID
            $cartSql = "SELECT cart_id FROM carts WHERE user_id = ?";
            $stmt = $db->prepare($cartSql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart = $result->fetch_assoc();

            if ($cart) {
                // Delete all items from the cart
                $deleteSql = "DELETE FROM cart_items WHERE cart_id = ?";
                $stmt = $db->prepare($deleteSql);
                $stmt->bind_param("i", $cart['cart_id']);
                return $stmt->execute();
            }

            return true; // Return true if no cart exists
            
        } catch (Exception $e) {
            error_log("Failed to empty cart: " . $e->getMessage());
            return false;
        }
    }

    public function clearCart($userId) {
        $cart = new Cart();
        return $cart->clearCart($userId);
    }

    public function calculateTradeInValue($tradeInDetails, $originalPrice) {
        return $this->cart->calculateTradeInValue($tradeInDetails, $originalPrice);
    }
}