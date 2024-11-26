<?php
require_once '../settings/db_class.php';

class Product extends db_connection {

    public function add_product($data) {
        // For seller requests, status will be 'pending' by default
        $sql = "INSERT INTO products (name, description, brand_id, category_id, status) 
                VALUES ('{$data['name']}', '{$data['description']}', 
                        '{$data['brand_id']}', '{$data['category_id']}', 
                        '{$data['status']}')"; 
        return $this->db_query($sql);
    }

    public function get_all_products() {
        $sql = "SELECT 
                    p.product_id, p.name, p.description, p.status,
                    b.name AS brand_name, 
                    c.name AS category_name
                FROM products p
                JOIN brands b ON p.brand_id = b.brand_id
                JOIN categories c ON p.category_id = c.category_id
                ORDER BY p.created_at DESC";
        return $this->db_fetch_all($sql);
    }

    public function get_product_by_id($product_id) {
        $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
        return $this->db_fetch_one($sql);
    }

    public function update_product($data) {
        $sql = "UPDATE products 
                SET 
                    name = '{$data['name']}', 
                    description = '{$data['description']}', 
                    brand_id = '{$data['brand_id']}', 
                    category_id = '{$data['category_id']}',
                    status = '{$data['status']}'  
                WHERE product_id = '{$data['product_id']}'";
        return $this->db_query($sql);
    }

    public function delete_product($product_id) {
        $sql = "DELETE FROM products WHERE product_id = '$product_id'";
        return $this->db_query($sql);
    }

    public function add_product_image($product_id, $image_path, $is_primary = false) {
        // Check if the product already has 4 images
        $sql = "SELECT COUNT(*) as image_count FROM product_images WHERE product_id = '$product_id'";
        $result = $this->db_fetch_one($sql);
        if ($result['image_count'] >= 4) {
            return false; // Cannot add more than 4 images
        }

        // If this is the primary image, set any existing primary image to false
        if ($is_primary) {
            $sql = "UPDATE product_images SET is_primary = FALSE WHERE product_id = '$product_id'";
            $this->db_query($sql);
        }

        $sql = "INSERT INTO product_images (product_id, image_path, is_primary) 
                VALUES ('$product_id', '$image_path', '$is_primary')";
        return $this->db_query($sql);
    }

    public function get_product_images($product_id) {
        $sql = "SELECT * FROM product_images WHERE product_id = '$product_id'";
        return $this->db_fetch_all($sql);
    }

    public function delete_product_image($image_id) {
        $sql = "DELETE FROM product_images WHERE image_id = '$image_id'";
        return $this->db_query($sql);
    }
}