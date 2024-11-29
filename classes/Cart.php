<?php

require_once '../settings/db_class.php';

class Cart extends db_connection {

    public function addToCart($userId, $productId, $sellerId, $price, $tradeInDetails = null) {
        try {
            // Start transaction
            $this->db_connect();
            $this->db->begin_transaction();

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
                $cartId = $this->get_insert_id();
            } else {
                $cartId = $cart['cart_id'];
            }

            // Calculate trade-in value if applicable
            $tradeInId = null;
            if ($tradeInDetails) {
                $tradeInValue = $this->calculateTradeInValue($tradeInDetails);
                $tradeInId = $this->addTradeIn($tradeInDetails, $tradeInValue);
                $price -= $tradeInValue; // Deduct trade-in value from price
            }

            // Insert cart item
            $sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price, trade_in_id) VALUES (?, ?, 1, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iidi", $cartId, $productId, $price, $tradeInId);
            $stmt->execute();

            // Commit transaction
            $this->db->commit();

            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollback();
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
}