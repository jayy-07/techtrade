<?php

require_once '../settings/db_class.php';

class Product extends db_connection
{
    public function add_product($data)
    {
        $sql = "INSERT INTO products (`category_id`, `brand_id`, `name`, `description`) 
                VALUES ('{$data['category_id']}', '{$data['brand_id']}', '{$data['product_name']}', '{$data['description']}')";
        return $this->db_query($sql);
    }

    public function get_all_products()
    {
        $sql = "SELECT * FROM products ORDER BY `name` ASC";
        return $this->db_fetch_all($sql);
    }

    public function get_product_by_id($product_id)
    {
        $sql = "SELECT * FROM products WHERE `product_id` = '$product_id'";
        return $this->db_fetch_one($sql);
    }

    public function update_product($data)
    {
        $sql = "UPDATE products 
                SET `category_id` = '{$data['category_id']}', 
                    `brand_id` = '{$data['brand_id']}', 
                    `name` = '{$data['product_name']}', 
                    `description` = '{$data['description']}' 
                WHERE `product_id` = '{$data['product_id']}'";
        return $this->db_query($sql);
    }

    public function delete_product($product_id)
    {
        $sql = "DELETE FROM products WHERE `product_id` = '$product_id'";
        return $this->db_query($sql);
    }

    public function get_product_images($product_id)
    {
        $sql = "SELECT * FROM product_images WHERE `product_id` = '$product_id'";
        return $this->db_fetch_all($sql);
    }

    public function add_product_image($product_id, $image_path, $is_primary = false)
    {
        $sql = "INSERT INTO product_images (`product_id`, `image_path`, `is_primary`) 
                VALUES ('$product_id', '$image_path', '$is_primary')";
        return $this->db_query($sql);
    }

    public function delete_product_images($product_id)
    {
        $sql = "DELETE FROM product_images WHERE `product_id` = '$product_id'";
        return $this->db_query($sql);
    }
}