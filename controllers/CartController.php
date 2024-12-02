<?php

require_once '../classes/Cart.php';

/**
 * Controller class for managing shopping cart operations
 */
class CartController {
    /** @var Cart Instance of Cart class */
    private $cart;

    /**
     * Constructor initializes Cart instance
     */
    public function __construct() {
        $this->cart = new Cart();
    }

    /**
     * Adds an item to user's cart
     * @param int $userId ID of the user
     * @param int $productId ID of the product
     * @param int $sellerId ID of the seller
     * @param float $price Price of the product
     * @param array|null $tradeInDetails Optional trade-in details
     * @return bool Success/failure of operation
     */
    public function addToCart($userId, $productId, $sellerId, $price, $tradeInDetails = null) {
        return $this->cart->addToCart($userId, $productId, $sellerId, $price, $tradeInDetails);
    }

    /**
     * Retrieves all items in user's cart
     * @param int $userId ID of the user
     * @return array Array of cart items or empty array if none found
     */
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

    /**
     * Calculates total value of items in user's cart
     * @param int $userId ID of the user
     * @return float Total cart value
     */
    public function getCartTotal($userId) {
        return $this->cart->getCartTotal($userId);
    }

    /**
     * Updates quantity of a cart item
     * @param int $cartItemId ID of the cart item
     * @param int $quantity New quantity
     * @return bool Success/failure of operation
     */
    public function updateQuantity($cartItemId, $quantity) {
        return $this->cart->updateCartItemQuantity($cartItemId, $quantity);
    }

    /**
     * Removes a specific item from cart
     * @param int $cartItemId ID of the cart item to remove
     * @return bool Success/failure of operation
     */
    public function removeItem($cartItemId) {
        return $this->cart->removeCartItem($cartItemId);
    }

    /**
     * Empties user's cart by removing all items
     * @param int $userId ID of the user
     * @return bool Success/failure of operation
     */
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

    /**
     * Alternative method to clear user's cart
     * @param int $userId ID of the user
     * @return bool Success/failure of operation
     */
    public function clearCart($userId) {
        $cart = new Cart();
        return $cart->clearCart($userId);
    }

    /**
     * Calculates trade-in value for an item
     * @param array $tradeInDetails Details of item being traded in
     * @param float $originalPrice Original price of the item
     * @return float Calculated trade-in value
     */
    public function calculateTradeInValue($tradeInDetails, $originalPrice) {
        return $this->cart->calculateTradeInValue($tradeInDetails, $originalPrice);
    }
}