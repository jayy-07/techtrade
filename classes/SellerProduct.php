<?php

require_once '../settings/db_class.php';

class SellerProduct extends db_connection
{
    public function add_product($seller_id, $product_id, $price, $stock_quantity, $discount)
    {
        $sql = "INSERT INTO sellers_products (`seller_id`, `product_id`, `price`, `stock_quantity`, `discount`) 
                VALUES ('$seller_id', '$product_id', '$price', '$stock_quantity', '$discount')";
        return $this->db_query($sql);
    }

    public function update_product($seller_id, $product_id, $price, $stock_quantity, $discount)
    {
        $sql = "UPDATE sellers_products 
                SET `price` = '$price', `stock_quantity` = '$stock_quantity', `discount` = '$discount' 
                WHERE `seller_id` = '$seller_id' AND `product_id` = '$product_id'";
        return $this->db_query($sql);
    }

    public function delete_product($seller_id, $product_id)
    {
        $sql = "DELETE FROM sellers_products 
                WHERE `seller_id` = '$seller_id' AND `product_id` = '$product_id'";
        return $this->db_query($sql);
    }

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
