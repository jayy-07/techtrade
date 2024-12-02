<?php

require_once '../settings/db_class.php';

/**
 * SellerProduct class for managing seller's product inventory
 * Handles CRUD operations for products in seller's inventory
 * Extends database connection class
 */
class SellerProduct extends db_connection
{
    /**
     * Adds a new product to the seller's inventory
     * @param int $seller_id ID of the seller
     * @param int $product_id ID of the product to add
     * @param float $price Price of the product
     * @param int $stock_quantity Available stock quantity
     * @param float $discount Discount percentage (0-100)
     * @return bool True on success, false on failure
     */
    public function add_product($seller_id, $product_id, $price, $stock_quantity, $discount)
    {
        $sql = "INSERT INTO sellers_products (`seller_id`, `product_id`, `price`, `stock_quantity`, `discount`) 
                VALUES ('$seller_id', '$product_id', '$price', '$stock_quantity', '$discount')";
        return $this->db_query($sql);
    }

    /**
     * Updates an existing product in the seller's inventory
     * @param int $seller_id ID of the seller
     * @param int $product_id ID of the product to update
     * @param float $price New price of the product
     * @param int $stock_quantity New stock quantity
     * @param float $discount New discount percentage
     * @return bool True on success, false on failure
     */
    public function update_product($seller_id, $product_id, $price, $stock_quantity, $discount)
    {
        $sql = "UPDATE sellers_products 
                SET `price` = '$price', `stock_quantity` = '$stock_quantity', `discount` = '$discount' 
                WHERE `seller_id` = '$seller_id' AND `product_id` = '$product_id'";
        return $this->db_query($sql);
    }

    /**
     * Deletes a product from the seller's inventory
     * @param int $seller_id ID of the seller
     * @param int $product_id ID of the product to delete
     * @return bool True on success, false on failure
     */
    public function delete_product($seller_id, $product_id)
    {
        $sql = "DELETE FROM sellers_products 
                WHERE `seller_id` = '$seller_id' AND `product_id` = '$product_id'";
        return $this->db_query($sql);
    }

    /**
     * Retrieves a specific product from the seller's inventory
     * Gets product details including name, category, brand, price and stock
     * @param int $seller_id ID of the seller
     * @param int $product_id ID of the product to retrieve
     * @return array|bool Product details or false if not found
     */
    public function get_product_by_id($seller_id, $product_id)
    {
        $sql = "SELECT p.name AS product_name, c.name AS category_name, b.name AS brand_name, sp.price, sp.stock_quantity, sp.discount
                FROM sellers_products sp
                JOIN products p ON sp.product_id = p.product_id
                JOIN categories c ON p.category_id = c.category_id
                JOIN brands b ON p.brand_id = b.brand_id
                WHERE sp.seller_id = '$seller_id' AND sp.product_id = '$product_id'";
        return $this->db_fetch_one($sql);
    }
}
