<?php
require_once '../classes/Order.php';

/**
 * Controller class for managing order operations
 */
class OrderController {
    /** @var Order Instance of Order class */
    private $order;

    /**
     * Constructor initializes Order instance
     */
    public function __construct() {
        $this->order = new Order();
    }

    /**
     * Creates a new order with the provided data
     * @param array $orderData Order data including user_id, total_amount, shipping_address, etc.
     * @return bool Success/failure of order creation
     */
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

    /**
     * Retrieves all orders for a specific user
     * @param int $user_id ID of the user
     * @return array Array of user's orders or empty array on error
     */
    public function getUserOrders($user_id) {
        try {
            error_log("OrderController - Getting orders for user: " . $user_id);
            return $this->order->getUserOrders($user_id);
        } catch (Exception $e) {
            error_log("OrderController - Error getting user orders: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves a specific order by ID
     * @param int $order_id ID of the order
     * @return array|bool Order data or false if not found
     */
    public function getOrder($order_id) {
        return $this->order->getOrder($order_id);
    }

    /**
     * Updates the status of an order
     * @param int $order_id ID of the order
     * @param string $status New status value
     * @return bool Success/failure of status update
     */
    public function updateOrderStatus($order_id, $status) {
        try {
            return $this->order->updateOrderStatus($order_id, $status);
        } catch (Exception $e) {
            error_log("Error updating order status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validates order data before creation
     * @param array $orderData Order data to validate
     * @return bool True if valid, false otherwise
     */
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

    /**
     * Retrieves all orders in the system
     * @return array Array of all orders or empty array on error
     */
    public function getAllOrders() {
        try {
            error_log("OrderController - Getting all orders");
            return $this->order->getAllOrders();
        } catch (Exception $e) {
            error_log("OrderController - Error getting all orders: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gets detailed information for a specific order
     * @param int $orderId ID of the order
     * @return array|bool Order details or false on error
     */
    public function getOrderDetails($orderId) {
        try {
            return $this->order->getOrderDetails($orderId);
        } catch (Exception $e) {
            error_log("Error getting order details: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all orders for a specific seller
     * @param int $sellerId ID of the seller
     * @return array Array of seller's orders
     */
    public function getSellerOrders($sellerId) {
        return $this->order->getSellerOrders($sellerId);
    }

    /**
     * Gets detailed information for a seller's specific order
     * @param int $orderId ID of the order
     * @param int $sellerId ID of the seller
     * @return array|bool Order details or false if not found
     */
    public function getSellerOrderDetails($orderId, $sellerId) {
        return $this->order->getSellerOrderDetails($orderId, $sellerId);
    }
}