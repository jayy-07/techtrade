<?php
require_once '../classes/Order.php';

class OrderController {
    private $order;

    public function __construct() {
        $this->order = new Order();
    }

    public function createOrder($orderData) {
        // Debug incoming data
        error_log("OrderController - Received Data: " . print_r($orderData, true));

        // Validate order data
        if (!$this->validateOrderData($orderData)) {
            error_log("OrderController - Validation Failed");
            return false;
        }

        error_log("OrderController - Validation Passed, Creating Order");
        return $this->order->createOrder($orderData);
    }

    public function getUserOrders($user_id) {
        try {
            error_log("OrderController - Getting orders for user: " . $user_id);
            return $this->order->getUserOrders($user_id);
        } catch (Exception $e) {
            error_log("OrderController - Error getting user orders: " . $e->getMessage());
            return [];
        }
    }

    public function getOrder($order_id) {
        return $this->order->getOrder($order_id);
    }

    public function updateOrderStatus($order_id, $status) {
        try {
            return $this->order->updateOrderStatus($order_id, $status);
        } catch (Exception $e) {
            error_log("Error updating order status: " . $e->getMessage());
            return false;
        }
    }

    private function validateOrderData($orderData) {
        // Check required fields
        $required_fields = ['user_id', 'total_amount', 'shipping_address', 'phone_number'];
        foreach ($required_fields as $field) {
            if (!isset($orderData[$field]) || empty($orderData[$field])) {
                error_log("OrderController - Missing required field: $field");
                return false;
            }
        }

        // Validate amount
        if (!is_numeric($orderData['total_amount']) || $orderData['total_amount'] <= 0) {
            error_log("OrderController - Invalid total amount: " . $orderData['total_amount']);
            return false;
        }

        // Validate phone number (basic validation)
        if (!preg_match("/^[0-9+\-\s()]*$/", $orderData['phone_number'])) {
            error_log("OrderController - Invalid phone number format: " . $orderData['phone_number']);
            return false;
        }

        error_log("OrderController - All validations passed");
        return true;
    }

    public function getAllOrders() {
        try {
            error_log("OrderController - Getting all orders");
            return $this->order->getAllOrders();
        } catch (Exception $e) {
            error_log("OrderController - Error getting all orders: " . $e->getMessage());
            return [];
        }
    }
} 