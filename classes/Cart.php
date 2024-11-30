<?php

require_once '../settings/db_class.php';

class Cart extends db_connection {

    public function addToCart($userId, $productId, $sellerId, $price, $tradeInDetails = null) {
        try {
            // Start transaction
            $this->db_connect();
            $this->db->begin_transaction();

            // Debug log
            error_log("Adding to cart - User: $userId, Product: $productId, Price: $price");

            // Check if cart exists for the user
            $sql = "SELECT cart_id FROM carts WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart = $result->fetch_assoc();

            if (!$cart) {
                // Create a new cart if it doesn't exist
                $sql = "INSERT INTO carts (user_id) VALUES (?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $cartId = $this->db->insert_id;
                error_log("Created new cart with ID: $cartId");
            } else {
                $cartId = $cart['cart_id'];
                error_log("Using existing cart with ID: $cartId");
            }

            // Calculate trade-in value if applicable
            $tradeInId = null;
            if ($tradeInDetails) {
                $tradeInValue = $this->calculateTradeInValue($tradeInDetails);
                $tradeInId = $this->addTradeIn($tradeInDetails, $tradeInValue);
                $price -= $tradeInValue; // Deduct trade-in value from price
                error_log("Applied trade-in with ID: $tradeInId, Value: $tradeInValue");
            }

            // Check if item already exists in cart
            $sql = "SELECT cart_item_id, quantity FROM cart_items 
                    WHERE cart_id = ? AND product_id = ? AND COALESCE(trade_in_id, 0) = COALESCE(?, 0)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iii", $cartId, $productId, $tradeInId);
            $stmt->execute();
            $existingItem = $stmt->get_result()->fetch_assoc();

            if ($existingItem) {
                // Update existing item quantity
                $sql = "UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $existingItem['cart_item_id']);
                error_log("Updating existing cart item: " . $existingItem['cart_item_id']);
            } else {
                // Insert new cart item
                $sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price, trade_in_id) 
                        VALUES (?, ?, 1, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iidi", $cartId, $productId, $price, $tradeInId);
                error_log("Adding new item to cart");
            }

            $stmt->execute();

            // Commit transaction
            $this->db->commit();
            error_log("Cart operation completed successfully");

            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error in addToCart: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function addTradeIn($tradeInDetails, $tradeInValue) {
        // Insert trade-in details and return the trade_in_id
        $sql = "INSERT INTO trade_ins (device_type, device_condition, usage_duration, purchase_price, trade_in_value) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssdd", $tradeInDetails['device_type'], $tradeInDetails['device_condition'], $tradeInDetails['usage_duration'], $tradeInDetails['purchase_price'], $tradeInValue);
        $stmt->execute();

        return $this->get_insert_id();
    }

    private function calculateTradeInValue($tradeInDetails) {
        // Basic trade-in value calculation based on condition and usage
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

        return $baseValue * $conditionValue * $usageValue;
    }

    public function getCartItems($userId) {
        try {
            $this->db_connect();
            
            $sql = "SELECT 
                    ci.cart_item_id, 
                    ci.quantity, 
                    ci.price as discounted_price, 
                    p.name as product_name, 
                    p.product_id, 
                    pi.image_path,
                    CONCAT(u.first_name, ' ', u.last_name) as seller_name,
                    sp.stock_quantity,
                    sp.discount,
                    ci.trade_in_id, 
                    t.device_type, 
                    t.device_condition, 
                    t.trade_in_value,
                    (ci.price * ci.quantity) as total_discounted_price,
                    ROUND(ci.price / (1 - COALESCE(sp.discount, 0) / 100), 2) as original_unit_price,
                    ROUND((ci.price / (1 - COALESCE(sp.discount, 0) / 100)) * ci.quantity, 2) as total_original_price,
                    CASE WHEN t.trade_in_id IS NOT NULL THEN 1 ELSE 0 END as trade_in
                    FROM carts c
                    JOIN cart_items ci ON c.cart_id = ci.cart_id
                    JOIN products p ON ci.product_id = p.product_id
                    LEFT JOIN sellers_products sp ON p.product_id = sp.product_id
                    LEFT JOIN users u ON sp.seller_id = u.user_id AND u.role = 'Seller'
                    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                    LEFT JOIN trade_ins t ON ci.trade_in_id = t.trade_in_id
                    WHERE c.user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $items = $result->fetch_all(MYSQLI_ASSOC);
            
            // Ensure trade_in is set for all items
            foreach ($items as &$item) {
                if (!isset($item['trade_in'])) {
                    $item['trade_in'] = 0;
                }
            }
            
            return $items;
        } catch (Exception $e) {
            error_log("Error in getCartItems: " . $e->getMessage());
            return [];
        }
    }

    public function getCartTotal($userId) {
        try {
            $this->db_connect();
            
            $sql = "SELECT 
                    SUM(ROUND(ci.price / (1 - COALESCE(sp.discount, 0) / 100), 2) * ci.quantity) as subtotal,
                    SUM(ROUND(ci.price / (1 - COALESCE(sp.discount, 0) / 100), 2) * ci.quantity - (ci.price * ci.quantity)) as total_discount,
                    SUM(COALESCE(t.trade_in_value, 0)) as total_trade_in
                    FROM carts c
                    JOIN cart_items ci ON c.cart_id = c.cart_id
                    LEFT JOIN sellers_products sp ON ci.product_id = sp.product_id
                    LEFT JOIN trade_ins t ON ci.trade_in_id = t.trade_in_id
                    WHERE c.user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $totals = $result->fetch_assoc();
            
            // Handle NULL values from the database
            $totals['subtotal'] = $totals['subtotal'] ?? 0;
            $totals['total_discount'] = $totals['total_discount'] ?? 0;
            $totals['total_trade_in'] = $totals['total_trade_in'] ?? 0;
            $totals['final_total'] = $totals['subtotal'] - $totals['total_discount'] - $totals['total_trade_in'];
            
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

    public function updateCartItemQuantity($cartItemId, $quantity) {
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

    public function removeCartItem($cartItemId) {
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

    public function clearCart($userId) {
        try {
            $this->db_connect();
            
            // Get the cart ID first
            $sql = "SELECT cart_id FROM carts WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart = $result->fetch_assoc();
            
            if ($cart) {
                // Start transaction
                $this->db->begin_transaction();
                
                // Delete cart items first (due to foreign key constraint)
                $sql = "DELETE FROM cart_items WHERE cart_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $cart['cart_id']);
                $stmt->execute();
                
                // Then delete the cart itself
                $sql = "DELETE FROM carts WHERE cart_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $cart['cart_id']);
                $stmt->execute();
                
                $this->db->commit();
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            error_log("Error in clearCart: " . $e->getMessage());
            return false;
        }
    }
}