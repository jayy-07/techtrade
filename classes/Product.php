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

    // In Product.php

    public function get_seller_products($seller_id)
    {
        $sql = "SELECT p.product_id, p.name AS product_name, c.name AS category_name, b.name AS brand_name, sp.price, sp.stock_quantity, p.description
            FROM products p
            JOIN sellers_products sp ON p.product_id = sp.product_id
            JOIN categories c ON p.category_id = c.category_id  -- Changed c.id to c.category_id
            JOIN brands b ON p.brand_id = b.brand_id
            WHERE sp.seller_id = '$seller_id'";

        return $this->db_fetch_all($sql);
    }

    // ... (other methods)

    public function add_seller_product($data)
    {
        // Add product to the sellers_products table
        $seller_id = $_SESSION['user']['user_id'];
        $product_id = $data['product_id'];
        $price = $data['price'];
        $stock_quantity = $data['stock_quantity'];

        $sql = "INSERT INTO sellers_products (`seller_id`, `product_id`, `price`, `stock_quantity`) 
            VALUES ('$seller_id', '$product_id', '$price', '$stock_quantity')";

        return $this->db_query($sql) ? "Product added to inventory successfully!" : "Failed to add product to inventory.";
    }

    public function update_seller_product($data)
    {
        // Update product in the sellers_products table
        $seller_id = $_SESSION['user']['user_id'];
        $product_id = $data['product_id'];
        $price = $data['price'];
        $stock_quantity = $data['stock_quantity'];

        $sql = "UPDATE sellers_products 
            SET `price` = '$price', `stock_quantity` = '$stock_quantity'
            WHERE `seller_id` = '$seller_id' AND `product_id` = '$product_id'";

        return $this->db_query($sql) ? "Product updated successfully!" : "Failed to update product.";
    }

    public function delete_seller_product($seller_id, $product_id)
    {
        // Delete product from the sellers_products table
        $sql = "DELETE FROM sellers_products 
            WHERE `seller_id` = '$seller_id' AND `product_id` = '$product_id'";

        return $this->db_query($sql) ? "Product deleted from inventory successfully!" : "Failed to delete product from inventory.";
    }

    public function get_seller_product_by_id($seller_id, $product_id)
    {
        // Get a specific product from the seller's inventory
        $sql = "SELECT p.name AS product_name, c.name AS category_name, b.name AS brand_name, sp.price, sp.stock_quantity
            FROM sellers_products sp
            JOIN products p ON sp.product_id = p.product_id
            JOIN categories c ON p.category_id = c.id
            JOIN brands b ON p.brand_id = b.id
            WHERE sp.seller_id = '$seller_id' AND sp.product_id = '$product_id'";

        return $this->db_fetch_one($sql);
    }
}
