<?php
require_once '../settings/db_class.php';

/**
 * Class for managing orders in the e-commerce system
 * Extends database connection class
 */
class Order extends db_connection {
    
    /**
     * Creates a new order from cart items
     * @param array $orderData Order details including user_id, total_amount, trade_in_credit, shipping_address, phone_number
     * @return int|bool Order ID on success, false on failure
     */
    public function createOrder($orderData) {
        try {
            error_log("Order Class - Starting order creation");
            
            $this->db_connect();
            error_log("Order Class - Database connected");

            $this->db->begin_transaction();
            error_log("Order Class - Transaction started");

            // Insert into orders table
            $sql = "INSERT INTO orders (
                user_id, 
                total_amount, 
                trade_in_credit, 
                shipping_address, 
                phone_number, 
                payment_status,
                created_at
            ) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";

            error_log("Order Class - SQL Query: " . $sql);
            error_log("Order Class - Order Data: " . print_r($orderData, true));

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "iddss",
                $orderData['user_id'],
                $orderData['total_amount'],
                $orderData['trade_in_credit'],
                $orderData['shipping_address'],
                $orderData['phone_number']
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create order: " . $stmt->error);
            }

            $order_id = $this->db->insert_id;
            error_log("Order Class - Order created with ID: " . $order_id);

            // Get cart items with seller information
            $cart = new Cart();
            $cartItems = $cart->getCartItems($orderData['user_id']);
            error_log("Order Class - Cart items retrieved: " . count($cartItems));

            // Debug cart items
            error_log("Order Class - Cart Items Data: " . print_r($cartItems, true));

            // Get seller information for each product
            $sellerSql = "SELECT sp.seller_id 
                         FROM sellers_products sp 
                         WHERE sp.product_id = ?";
            $sellerStmt = $this->db->prepare($sellerSql);

            // Insert order items
            $itemSql = "INSERT INTO order_items (
                order_id, 
                product_id, 
                seller_id, 
                quantity, 
                price, 
                trade_in_details
            ) VALUES (?, ?, ?, ?, ?, ?)";

            $itemStmt = $this->db->prepare($itemSql);

            foreach ($cartItems as $item) {
                // Get seller_id for the product
                $sellerStmt->bind_param("i", $item['product_id']);
                $sellerStmt->execute();
                $sellerResult = $sellerStmt->get_result();
                $sellerData = $sellerResult->fetch_assoc();

                if (!$sellerData || !$sellerData['seller_id']) {
                    throw new Exception("No seller found for product ID: " . $item['product_id']);
                }

                error_log("Order Class - Found seller_id: " . $sellerData['seller_id'] . " for product: " . $item['product_id']);

                $tradeInDetails = null;
                if (!empty($item['trade_in_id'])) {
                    $tradeInDetails = json_encode([
                        'trade_in_id' => $item['trade_in_id'],
                        'device_type' => $item['device_type'],
                        'device_condition' => $item['device_condition'],
                        'trade_in_value' => $item['trade_in_value']
                    ]);
                }

                $itemStmt->bind_param(
                    "iiiids",
                    $order_id,
                    $item['product_id'],
                    $sellerData['seller_id'],
                    $item['quantity'],
                    $item['discounted_price'],
                    $tradeInDetails
                );

                if (!$itemStmt->execute()) {
                    throw new Exception("Failed to insert order item: " . $itemStmt->error);
                }
                error_log("Order Class - Order item inserted for product: " . $item['product_id']);
            }

            $this->db->commit();
            error_log("Order Class - Transaction committed successfully");
            return $order_id;

        } catch (Exception $e) {
            error_log("Order Class - Error: " . $e->getMessage());
            if ($this->db && $this->db->ping()) {
                $this->db->rollback();
                error_log("Order Class - Transaction rolled back");
            }
            return false;
        }
    }

    /**
     * Retrieves an order by its ID with basic information
     * @param int $order_id ID of the order to retrieve
     * @return array|bool Order details on success, false on failure
     */
    public function getOrder($order_id) {
        try {
            $this->db_connect();
            
            // Get main order information
            $sql = "SELECT o.order_id,
                    o.user_id,
                    o.total_amount,
                    o.trade_in_credit,
                    o.shipping_address,
                    o.phone_number,
                    o.payment_status,
                    o.created_at,
                    u.first_name, 
                    u.last_name, 
                    u.email,
                    (o.total_amount + COALESCE(o.trade_in_credit, 0)) as subtotal  -- Add trade-in back to get original subtotal
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    WHERE o.order_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $order = $result->fetch_assoc();
            
            if ($order) {
                // Get order items
                $itemsSql = "SELECT oi.*,
                            p.name as product_name,
                            pi.image_path,
                            CONCAT(u.first_name, ' ', u.last_name) as seller_name,
                            JSON_UNQUOTE(JSON_EXTRACT(oi.trade_in_details, '$.trade_in_value')) as trade_in_value,
                            JSON_UNQUOTE(JSON_EXTRACT(oi.trade_in_details, '$.device_type')) as device_type,
                            JSON_UNQUOTE(JSON_EXTRACT(oi.trade_in_details, '$.device_condition')) as device_condition
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.product_id
                            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                            LEFT JOIN users u ON oi.seller_id = u.user_id
                            WHERE oi.order_id = ?";
                
                $itemsStmt = $this->db->prepare($itemsSql);
                $itemsStmt->bind_param("i", $order_id);
                $itemsStmt->execute();
                $order['items'] = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

                // Calculate final total
                $order['final_total'] = $order['total_amount'];
            }
            
            return $order;
            
        } catch (Exception $e) {
            error_log("Order Class - Error getting order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves detailed order information including customer and payment details
     * @param int $orderId ID of the order
     * @return array|null Order details on success, null on failure
     */
    public function getOrderDetails($orderId) {
        try {
            $this->db_connect();
            
            // Get order header information
            $sql = "SELECT 
                    o.*,
                    CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                    u.email as customer_email,
                    u.phone as customer_phone,
                    p.payment_status
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    LEFT JOIN (
                        SELECT 
                            order_id,
                            payment_status,
                            ROW_NUMBER() OVER (PARTITION BY order_id ORDER BY updated_at DESC, created_at DESC) as rn
                        FROM payments
                    ) p ON o.order_id = p.order_id AND p.rn = 1
                    WHERE o.order_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();
            
            if (!$order) {
                return null;
            }
            
            // Get order items
            $itemsSql = "SELECT 
                        oi.*,
                        p.name as product_name,
                        pi.image_path,
                        CONCAT(u.first_name, ' ', u.last_name) as seller_name,
                        oi.trade_in_details
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.product_id
                        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                        LEFT JOIN users u ON oi.seller_id = u.user_id
                        WHERE oi.order_id = ?";
            
            $itemsStmt = $this->db->prepare($itemsSql);
            $itemsStmt->bind_param("i", $orderId);
            $itemsStmt->execute();
            $order['items'] = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            // Set default value if payment status is null
            $order['payment_status'] = $order['payment_status'] ?? 'Pending';
            
            return $order;
        } catch (Exception $e) {
            error_log("Error in getOrderDetails: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper function to retrieve items for a specific order
     * @param int $orderId ID of the order
     * @return array Array of order items
     */
    private function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.image_path,
                CONCAT(u.first_name, ' ', u.last_name) as seller_name
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                JOIN users u ON oi.seller_id = u.user_id
                WHERE oi.order_id = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Updates the payment status of an order
     * @param int $order_id ID of the order
     * @param string $status New payment status
     * @return bool Success/failure of update operation
     */
    public function updateOrderStatus($order_id, $status) {
        try {
            // Establish database connection
            $this->db_connect();
            
            error_log("Order Class - Updating order status: Order ID = $order_id, Status = $status");
            
            // Begin transaction
            $this->db->begin_transaction();
            
            // Update orders table
            $orderSql = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
            $orderStmt = $this->db->prepare($orderSql);
            
            if (!$orderStmt) {
                throw new Exception("Failed to prepare order status update: " . $this->db->error);
            }
            
            $orderStmt->bind_param("si", $status, $order_id);
            
            if (!$orderStmt->execute()) {
                throw new Exception("Failed to update order status: " . $orderStmt->error);
            }
            
            // Check if any rows were affected
            if ($orderStmt->affected_rows === 0) {
                throw new Exception("No order found with ID: " . $order_id);
            }
            
            // Commit transaction
            $this->db->commit();
            
            error_log("Order Class - Order status updated successfully");
            return true;
            
        } catch (Exception $e) {
            // Rollback on error
            if ($this->db && $this->db->ping()) {
                $this->db->rollback();
            }
            error_log("Order Class - Error updating order status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all orders for a specific user
     * @param int $user_id ID of the user
     * @return array Array of user's orders
     */
    public function getUserOrders($user_id) {
        try {
            $this->db_connect();
            
            $sql = "SELECT o.*, 
                    (SELECT SUM(quantity) 
                     FROM order_items 
                     WHERE order_id = o.order_id) as total_items,
                    COALESCE(
                        (SELECT payment_status 
                         FROM payments 
                         WHERE order_id = o.order_id 
                         ORDER BY updated_at DESC, created_at DESC 
                         LIMIT 1),
                        o.payment_status
                    ) as payment_status,
                    (SELECT payment_reference 
                     FROM payments 
                     WHERE order_id = o.order_id 
                     ORDER BY updated_at DESC, created_at DESC 
                     LIMIT 1) as payment_reference,
                    (SELECT pi.image_path 
                     FROM order_items oi2 
                     JOIN products pr ON oi2.product_id = pr.product_id
                     LEFT JOIN product_images pi ON pr.product_id = pi.product_id AND pi.is_primary = 1
                     WHERE oi2.order_id = o.order_id 
                     LIMIT 1) as first_item_image
                    FROM orders o
                    WHERE o.user_id = ?
                    ORDER BY o.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                // Get items for each order
                $row['items'] = $this->getOrderItems($row['order_id']);
                $orders[] = $row;
            }
            
            error_log("Order Class - Retrieved " . count($orders) . " orders");
            return $orders;
            
        } catch (Exception $e) {
            error_log("Order Class - Error getting user orders: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves all orders in the system
     * @return array Array of all orders
     */
    public function getAllOrders() {
        try {
            $this->db_connect();
            
            $sql = "SELECT 
                    o.order_id,
                    o.user_id,
                    o.created_at,
                    o.shipping_address,
                    o.total_amount,
                    CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                    u.email as customer_email,
                    COALESCE(
                        (SELECT payment_status 
                         FROM payments 
                         WHERE order_id = o.order_id 
                         ORDER BY updated_at DESC, created_at DESC 
                         LIMIT 1),
                        'Pending'
                    ) as payment_status,
                    COUNT(oi.order_item_id) as total_items
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    JOIN order_items oi ON o.order_id = oi.order_id
                    GROUP BY o.order_id, o.user_id, o.created_at, o.shipping_address, 
                             o.total_amount, customer_name, u.email
                    ORDER BY o.created_at DESC";
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllOrders: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves orders for a specific seller
     * @param int $sellerId ID of the seller
     * @return array Array of seller's orders
     */
    public function getSellerOrders($sellerId) {
        try {
            $this->db_connect();
            
            $sql = "SELECT 
                    o.order_id,
                    o.created_at,
                    oi.quantity,
                    oi.price,
                    p.name as product_name,
                    CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                    COALESCE(
                        (SELECT payment_status 
                         FROM payments 
                         WHERE order_id = o.order_id 
                         ORDER BY updated_at DESC, created_at DESC 
                         LIMIT 1),
                        'Pending'
                    ) as payment_status
                    FROM orders o
                    JOIN order_items oi ON o.order_id = oi.order_id
                    JOIN products p ON oi.product_id = p.product_id
                    JOIN users u ON o.user_id = u.user_id
                    WHERE oi.seller_id = ?
                    ORDER BY o.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $sellerId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getSellerOrders: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves detailed order information for a specific seller
     * @param int $orderId ID of the order
     * @param int $sellerId ID of the seller
     * @return array|null Order details on success, null on failure
     */
    public function getSellerOrderDetails($orderId, $sellerId) {
        try {
            $this->db_connect();
            
            // Get order header information
            $sql = "SELECT 
                    o.*,
                    CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                    u.email as customer_email,
                    u.phone as customer_phone,
                    COALESCE(
                        (SELECT payment_status 
                         FROM payments 
                         WHERE order_id = o.order_id 
                         ORDER BY updated_at DESC, created_at DESC 
                         LIMIT 1),
                        'Pending'
                    ) as payment_status
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    WHERE o.order_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();
            
            if (!$order) {
                return null;
            }
            
            // Get only this seller's items from the order
            $itemsSql = "SELECT 
                        oi.*,
                        p.name as product_name,
                        pi.image_path,
                        oi.trade_in_details
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.product_id
                        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                        WHERE oi.order_id = ? AND oi.seller_id = ?";
            
            $itemsStmt = $this->db->prepare($itemsSql);
            $itemsStmt->bind_param("ii", $orderId, $sellerId);
            $itemsStmt->execute();
            $order['items'] = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            return $order;
        } catch (Exception $e) {
            error_log("Error in getSellerOrderDetails: " . $e->getMessage());
            return null;
        }
    }
}