<?php
require_once '../settings/db_class.php';

class Dashboard extends db_connection {
    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM products";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getTotalOrders() {
        $sql = "SELECT COUNT(*) as total FROM orders";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getTotalRevenue() {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'Paid'";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getRecentOrders($limit = 5) {
        $sql = "SELECT o.*, u.first_name, u.last_name, COUNT(oi.order_item_id) as total_items 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id 
                LEFT JOIN order_items oi ON o.order_id = oi.order_id 
                GROUP BY o.order_id 
                ORDER BY o.created_at DESC 
                LIMIT $limit";
        return $this->db_fetch_all($sql);
    }

    public function getSalesByCategory() {
        $sql = "SELECT c.name, COUNT(oi.order_item_id) as total_sales, 
                SUM(oi.price * oi.quantity) as revenue 
                FROM categories c 
                LEFT JOIN products p ON c.category_id = p.category_id 
                LEFT JOIN order_items oi ON p.product_id = oi.product_id 
                GROUP BY c.category_id 
                ORDER BY total_sales DESC";
        $result = $this->db_fetch_all($sql);
        return is_array($result) ? $result : [];
    }

    public function getMonthlyRevenue() {
        $sql = "SELECT DATE_FORMAT(updated_at, '%Y-%m') as month, 
                SUM(amount) as revenue 
                FROM payments 
                WHERE payment_status = 'Completed' 
                GROUP BY month 
                ORDER BY month DESC 
                LIMIT 6";
        $result = $this->db_fetch_all($sql);
        return is_array($result) ? $result : [];
    }

    public function getSellerTotalProducts($seller_id) {
        $sql = "SELECT COUNT(*) as total 
                FROM sellers_products 
                WHERE seller_id = $seller_id";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getSellerTotalOrders($seller_id) {
        $sql = "SELECT COUNT(DISTINCT o.order_id) as total 
                FROM orders o 
                JOIN order_items oi ON o.order_id = oi.order_id 
                WHERE oi.seller_id = $seller_id";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getSellerTotalRevenue($seller_id) {
        $sql = "SELECT COALESCE(SUM(oi.price * oi.quantity), 0) as total 
                FROM orders o 
                JOIN order_items oi ON o.order_id = oi.order_id 
                JOIN payments p ON o.order_id = p.order_id 
                WHERE oi.seller_id = $seller_id 
                AND p.payment_status = 'Completed'";
        return $this->db_fetch_one($sql)['total'];
    }

    public function getSellerMonthlyRevenue($seller_id) {
        $sql = "SELECT 
                    DATE_FORMAT(p.updated_at, '%Y-%m') as month,
                    COALESCE(SUM(oi.price * oi.quantity), 0) as revenue
                FROM payments p
                JOIN orders o ON p.order_id = o.order_id
                JOIN order_items oi ON o.order_id = oi.order_id
                WHERE oi.seller_id = $seller_id
                AND p.payment_status = 'Completed'
                GROUP BY month
                ORDER BY month DESC
                LIMIT 6";
        $result = $this->db_fetch_all($sql);
        return is_array($result) ? $result : [];
    }

    public function getSellerRecentOrders($seller_id, $limit = 5) {
        $sql = "SELECT 
                    o.order_id,
                    o.created_at,
                    u.first_name,
                    u.last_name,
                    p.name as product_name,
                    oi.quantity,
                    oi.price,
                    pay.payment_status
                FROM orders o
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                JOIN users u ON o.user_id = u.user_id
                LEFT JOIN payments pay ON o.order_id = pay.order_id
                WHERE oi.seller_id = $seller_id
                ORDER BY o.created_at DESC
                LIMIT $limit";
        return $this->db_fetch_all($sql);
    }

    public function getSellerTopProducts($seller_id) {
        $sql = "SELECT 
                    p.name as name,
                    COUNT(oi.order_item_id) as total_sales,
                    SUM(oi.price * oi.quantity) as revenue
                FROM products p
                JOIN sellers_products sp ON p.product_id = sp.product_id
                LEFT JOIN order_items oi ON sp.seller_id = oi.seller_id AND p.product_id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.order_id
                LEFT JOIN payments pay ON o.order_id = pay.order_id
                WHERE sp.seller_id = $seller_id
                AND pay.payment_status = 'Completed'
                GROUP BY p.product_id
                ORDER BY revenue DESC
                LIMIT 5";
        $result = $this->db_fetch_all($sql);
        return is_array($result) ? $result : [];
    }
} 