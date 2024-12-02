<?php

require_once '../settings/db_class.php';

/**
 * Class for managing shopping cart functionality
 * Extends database connection class
 */
class Cart extends db_connection
{
    /**
     * Adds a product to the user's cart with optional trade-in
     * @param int $userId ID of the user adding to cart
     * @param int $productId ID of the product being added
     * @param int $sellerId ID of the seller offering the product
     * @param float $price Price of the product
     * @param array|null $tradeInDetails Optional trade-in details
     * @return array Success status and any error messages
     */
    public function addToCart($userId, $productId, $sellerId, $price, $tradeInDetails = null)
    {
        try {
            $this->db_connect();
            $this->db->begin_transaction();

            // Verify the seller exists and is valid
            $sellerSql = "SELECT user_id FROM users WHERE user_id = ? AND role = 'Seller'";
            $sellerStmt = $this->db->prepare($sellerSql);
            $sellerStmt->bind_param("i", $sellerId);
            $sellerStmt->execute();
            if ($sellerStmt->get_result()->num_rows === 0) {
                throw new Exception("Invalid seller");
            }

            // Check if a cart exists for the user
            $sql = "SELECT cart_id FROM carts WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart = $result->fetch_assoc();

            // If no cart exists, create one
            if (!$cart) {
                $sql = "INSERT INTO carts (user_id) VALUES (?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $cartId = $this->db->insert_id;
            } else {
                $cartId = $cart['cart_id'];
            }

            // Get original price and discount from the seller's product
            $sql = "SELECT sp.price, sp.discount 
                    FROM sellers_products sp 
                    JOIN users u ON sp.seller_id = u.user_id 
                    WHERE sp.product_id = ? AND sp.seller_id = ? AND u.role = 'Seller'";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $productId, $sellerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $productData = $result->fetch_assoc();

            if (!$productData) {
                throw new Exception("Product not found for this seller");
            }

            $originalPrice = $productData['price'];
            $discount = $productData['discount'];
            $discountedPrice = $originalPrice * (1 - ($discount / 100));

            // Handle trade-in details if provided
            $tradeInId = null;
            $finalPrice = $discountedPrice; // Default final price

            if ($tradeInDetails) {
                $tradeInValue = $this->calculateTradeInValue($tradeInDetails, $originalPrice);
                if ($tradeInValue >= $discountedPrice * 0.9) {
                    $tradeInValue = $discountedPrice * 0.9;
                }
                $finalPrice = $discountedPrice - $tradeInValue;
                if ($finalPrice <= 0) {
                    throw new Exception("Final price must be greater than zero");
                }
                $tradeInId = $this->addTradeIn($tradeInDetails, $tradeInValue);
            }

            // Check if the item already exists in the cart with the same seller
            $sql = "SELECT cart_item_id, quantity FROM cart_items 
                    WHERE cart_id = ? AND product_id = ? AND seller_id = ? AND COALESCE(trade_in_id, 0) = COALESCE(?, 0)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iiii", $cartId, $productId, $sellerId, $tradeInId);
            $stmt->execute();
            $existingItem = $stmt->get_result()->fetch_assoc();

            // Update quantity if item exists, otherwise insert new item
            if ($existingItem) {
                $sql = "UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $existingItem['cart_item_id']);
            } else {
                $sql = "INSERT INTO cart_items (cart_id, product_id, seller_id, quantity, price, trade_in_id) 
                        VALUES (?, ?, ?, 1, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iiidi", $cartId, $productId, $sellerId, $finalPrice, $tradeInId);
            }

            $stmt->execute();
            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error in addToCart: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Adds trade-in details to the database
     * @param array $tradeInDetails Details of the trade-in device
     * @param float $tradeInValue Calculated value of the trade-in
     * @return int ID of the inserted trade-in record
     * @throws Exception If insert fails
     */
    private function addTradeIn($tradeInDetails, $tradeInValue)
    {
        try {
            $sql = "INSERT INTO trade_ins (
                device_type, 
                device_condition, 
                usage_duration, 
                purchase_price, 
                trade_in_value
            ) VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "sssdd", 
                $tradeInDetails['device_type'],
                $tradeInDetails['device_condition'],
                $tradeInDetails['usage_duration'],
                $tradeInDetails['purchase_price'],
                $tradeInValue
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert trade-in details");
            }
            
            return $this->db->insert_id;
        } catch (Exception $e) {
            error_log("Error in addTradeIn: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Calculates trade-in value based on device details and original price
     * @param array $tradeInDetails Details of the trade-in device
     * @param float $originalPrice Original price of the product
     * @return float Calculated trade-in value
     */
    public function calculateTradeInValue($tradeInDetails, $originalPrice)
    {
        $baseValue = $tradeInDetails['purchase_price'];
        $conditionMultiplier = [
            'Excellent' => 0.8,
            'Good' => 0.6,
            'Fair' => 0.4,
            'Poor' => 0.2
        ];
        $usageMultiplier = [
            'Less than 6 months' => 1.0,
            '6-12 months' => 0.9,
            '1-2 years' => 0.7,
            '2-3 years' => 0.5,
            'More than 3 years' => 0.3
        ];

        $conditionValue = $conditionMultiplier[$tradeInDetails['device_condition']] ?? 0;
        $usageValue = $usageMultiplier[$tradeInDetails['usage_duration']] ?? 0;

        $tradeInValue = $baseValue * $conditionValue * $usageValue;

        // Calculate discounted price
        $discountedPrice = $originalPrice * 0.8; // Assuming 20% discount

        // Cap trade-in value at 90% of discounted price
        $maxTradeIn = $discountedPrice * 0.9;
        return min($tradeInValue, $maxTradeIn);
    }

    /**
     * Retrieves all items in the user's cart with detailed information
     * @param int $userId ID of the user
     * @return array Array of cart items with details
     */
    public function getCartItems($userId)
    {
        try {
            $this->db_connect();

            $sql = "SELECT 
                    ci.cart_item_id, 
                    ci.quantity, 
                    ci.price as discounted_price,
                    sp.price as original_unit_price, 
                    p.name as product_name, 
                    p.product_id,
                    ci.seller_id, 
                    pi.image_path,
                    CONCAT(u.first_name, ' ', u.last_name) as seller_name,
                    sp.stock_quantity,
                    sp.discount,
                    ci.trade_in_id, 
                    t.device_type, 
                    t.device_condition, 
                    t.trade_in_value,
                    (CASE 
                        WHEN t.trade_in_value IS NOT NULL 
                        THEN ci.price 
                        ELSE sp.price * (1 - sp.discount/100)
                    END) as final_price,
                    (ci.quantity * sp.price) as total_original_price,
                    (ci.quantity * (CASE 
                        WHEN t.trade_in_value IS NOT NULL 
                        THEN ci.price 
                        ELSE sp.price * (1 - sp.discount/100)
                    END)) as total_final_price
                    FROM carts c
                    JOIN cart_items ci ON c.cart_id = ci.cart_id
                    JOIN products p ON ci.product_id = p.product_id
                    JOIN sellers_products sp ON ci.product_id = sp.product_id AND ci.seller_id = sp.seller_id
                    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                    LEFT JOIN users u ON ci.seller_id = u.user_id
                    LEFT JOIN trade_ins t ON ci.trade_in_id = t.trade_in_id
                    WHERE c.user_id = ?
                    ORDER BY ci.cart_item_id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCartItems: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculates total cost of items in cart including discounts and trade-ins
     * @param int $userId ID of the user
     * @return array Array containing subtotal, discounts, trade-ins and final total
     */
    public function getCartTotal($userId)
    {
        try {
            $this->db_connect();

            $sql = "SELECT 
                    SUM(sp.price * ci.quantity) as subtotal,
                    SUM(sp.price * ci.quantity * (sp.discount / 100)) as total_discount,
                    SUM(CASE 
                        WHEN t.trade_in_value IS NOT NULL 
                        THEN t.trade_in_value 
                        ELSE 0 
                    END) as total_trade_in
                    FROM carts c
                    JOIN cart_items ci ON c.cart_id = ci.cart_id
                    JOIN sellers_products sp ON ci.product_id = sp.product_id AND ci.seller_id = sp.seller_id
                    LEFT JOIN trade_ins t ON ci.trade_in_id = t.trade_in_id
                    WHERE c.user_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $totals = $result->fetch_assoc();

            // Handle NULL values
            $totals['subtotal'] = $totals['subtotal'] ?? 0;
            $totals['total_discount'] = $totals['total_discount'] ?? 0;
            $totals['total_trade_in'] = $totals['total_trade_in'] ?? 0;

            // Calculate final total
            $afterDiscount = $totals['subtotal'] - $totals['total_discount'];
            $finalTotal = $afterDiscount - $totals['total_trade_in'];

            // Ensure final total cannot be less than 1% of after-discount price
            $totals['final_total'] = max($afterDiscount * 0.01, $finalTotal);

            return $totals;
        } catch (Exception $e) {
            error_log("Error in getCartTotal: " . $e->getMessage());
            return [
                'subtotal' => 0,
                'total_discount' => 0,
                'total_trade_in' => 0,
                'final_total' => 0
            ];
        }
    }

    /**
     * Updates quantity of a specific cart item
     * @param int $cartItemId ID of the cart item
     * @param int $quantity New quantity value
     * @return bool Success/failure of update operation
     */
    public function updateCartItemQuantity($cartItemId, $quantity)
    {
        try {
            // Establish database connection first
            $this->db_connect();

            $sql = "UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $quantity, $cartItemId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateCartItemQuantity: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Removes a specific item from the cart
     * @param int $cartItemId ID of the cart item to remove
     * @return bool Success/failure of removal operation
     */
    public function removeCartItem($cartItemId)
    {
        try {
            // Establish database connection first
            $this->db_connect();

            $sql = "DELETE FROM cart_items WHERE cart_item_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $cartItemId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in removeCartItem: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clears all items from user's cart and removes cart
     * @param int $userId ID of the user
     * @return bool Success/failure of cart clearing operation
     */
    public function clearCart($userId)
    {
        try {
            // Establish database connection first
            $this->db_connect();

            // Get the cart ID first
            $cartSql = "SELECT cart_id FROM carts WHERE user_id = ?";
            $cartStmt = $this->db->prepare($cartSql);
            $cartStmt->bind_param("i", $userId);
            $cartStmt->execute();
            $result = $cartStmt->get_result();

            if ($cart = $result->fetch_assoc()) {
                // Start transaction
                $this->db->begin_transaction();

                // Delete all cart items first
                $itemsSql = "DELETE FROM cart_items WHERE cart_id = ?";
                $itemsStmt = $this->db->prepare($itemsSql);
                $itemsStmt->bind_param("i", $cart['cart_id']);

                if (!$itemsStmt->execute()) {
                    throw new Exception("Failed to clear cart items");
                }

                // Delete the cart itself
                $cartDeleteSql = "DELETE FROM carts WHERE cart_id = ?";
                $cartDeleteStmt = $this->db->prepare($cartDeleteSql);
                $cartDeleteStmt->bind_param("i", $cart['cart_id']);

                if (!$cartDeleteStmt->execute()) {
                    throw new Exception("Failed to delete cart");
                }

                // Commit transaction
                $this->db->commit();
                return true;
            }

            return true; // Return true if no cart exists

        } catch (Exception $e) {
            // Rollback on error
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            error_log("Clear cart error: " . $e->getMessage());
            return false;
        }
    }
}
