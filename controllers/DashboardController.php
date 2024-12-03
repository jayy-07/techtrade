<?php
require_once '../classes/Dashboard.php';

class DashboardController {
    private $dashboard;

    public function __construct() {
        $this->dashboard = new Dashboard();
    }

    // Admin Methods
    public function getTotalUsers() {
        return $this->dashboard->getTotalUsers();
    }

    public function getTotalProducts() {
        return $this->dashboard->getTotalProducts();
    }

    public function getTotalOrders() {
        return $this->dashboard->getTotalOrders();
    }

    public function getTotalRevenue() {
        return $this->dashboard->getTotalRevenue();
    }

    public function getRecentOrders() {
        return $this->dashboard->getRecentOrders();
    }

    public function getSalesByCategory() {
        return $this->dashboard->getSalesByCategory();
    }

    public function getMonthlyRevenue() {
        return $this->dashboard->getMonthlyRevenue();
    }

    // Seller Methods
    public function getSellerTotalProducts($seller_id) {
        return $this->dashboard->getSellerTotalProducts($seller_id);
    }

    public function getSellerTotalOrders($seller_id) {
        return $this->dashboard->getSellerTotalOrders($seller_id);
    }

    public function getSellerTotalRevenue($seller_id) {
        return $this->dashboard->getSellerTotalRevenue($seller_id);
    }

    public function getSellerMonthlyRevenue($seller_id) {
        return $this->dashboard->getSellerMonthlyRevenue($seller_id);
    }

    public function getSellerRecentOrders($seller_id) {
        return $this->dashboard->getSellerRecentOrders($seller_id);
    }

    public function getSellerTopProducts($seller_id) {
        return $this->dashboard->getSellerTopProducts($seller_id);
    }

    // Helper method to get all seller dashboard stats at once
    public function getSellerDashboardStats($seller_id) {
        return [
            'total_products' => $this->getSellerTotalProducts($seller_id),
            'total_orders' => $this->getSellerTotalOrders($seller_id),
            'total_revenue' => $this->getSellerTotalRevenue($seller_id),
            'monthly_revenue' => $this->getSellerMonthlyRevenue($seller_id),
            'recent_orders' => $this->getSellerRecentOrders($seller_id),
            'top_products' => $this->getSellerTopProducts($seller_id)
        ];
    }

    // Helper method to get all admin dashboard stats at once
    public function getAdminDashboardStats() {
        return [
            'total_users' => $this->getTotalUsers(),
            'total_products' => $this->getTotalProducts(),
            'total_orders' => $this->getTotalOrders(),
            'total_revenue' => $this->getTotalRevenue(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'recent_orders' => $this->getRecentOrders(),
            'sales_by_category' => $this->getSalesByCategory()
        ];
    }
} 