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
        $sql = "SELECT p.product_id, p.name, p.description, c.name AS category_name, b.name AS brand_name, c.category_id AS category_id, b.brand_id AS brand_id
                FROM products p
                JOIN categories c ON p.category_id = c.category_id
                JOIN brands b ON p.brand_id = b.brand_id 
                WHERE p.product_id = '$product_id'";
        return
            $this->db_fetch_one($sql);
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
        try {
            $this->db_connect();
            
            $sql = "SELECT image_path FROM product_images 
                    WHERE product_id = ? 
                    ORDER BY is_primary DESC, image_id ASC";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in get_product_images: " . $e->getMessage());
            return [];
        }
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


    public function get_seller_products($seller_id)
    {
        $sql = "SELECT p.product_id, p.name AS product_name, c.name AS category_name, b.name AS brand_name, sp.price, sp.stock_quantity, p.description, sp.discount 
            FROM products p
            JOIN sellers_products sp ON p.product_id = sp.product_id
            JOIN categories c ON p.category_id = c.category_id 
            JOIN brands b ON p.brand_id = b.brand_id
            WHERE sp.seller_id = '$seller_id'";

        return $this->db_fetch_all($sql);
    }

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

    public function get_other_sellers($product_id, $cheapest_seller_id)
    {
        $sql = "SELECT sp.seller_id, sp.price, sp.discount, sp.product_id, 
                    CONCAT(u.first_name, ' ', u.last_name) AS seller_name 
                FROM sellers_products sp
                JOIN users u ON sp.seller_id = u.user_id
                WHERE sp.product_id = '$product_id' 
                    AND sp.seller_id != '$cheapest_seller_id'";
        return $this->db_fetch_all($sql);
    }

    public function get_cheapest_offer($product_id)
    {
        $sql = "SELECT sp.seller_id, sp.price, sp.discount, CONCAT(u.first_name, ' ', u.last_name) AS seller_name 
                FROM sellers_products sp
                JOIN users u ON sp.seller_id = u.user_id
                WHERE sp.product_id = '$product_id'
                ORDER BY sp.price ASC
                LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    public function searchProducts($query, $categoryId = null, $brandId = null, $priceRange = null, $sortBy = null) {
        $query = trim($query);
        
        $sql = "SELECT 
                p.product_id,
                p.name,
                MIN(sp.price) as min_price,
                MAX(sp.discount) as max_discount,
                pi.image_path,
                b.name as brand_name
            FROM products p
            JOIN sellers_products sp ON p.product_id = sp.product_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            LEFT JOIN brands b ON p.brand_id = b.brand_id
            WHERE (
                p.name LIKE '%$query%' 
                OR p.description LIKE '%$query%'
                OR b.name LIKE '%$query%'
            )";

        // Add category filter if specified
        if ($categoryId) {
            $sql .= " AND p.category_id = '$categoryId'";
        }

        // Add brand filter if specified
        if ($brandId) {
            $sql .= " AND p.brand_id = '$brandId'";
        }

        // Group products before applying price filter
        $sql .= " GROUP BY p.product_id, p.name, pi.image_path, b.name";

        // Add price range filter if specified
        if ($priceRange) {
            list($min, $max) = explode('-', $priceRange);
            if ($max === '+') {
                $sql .= " HAVING min_price >= $min";
            } else {
                $sql .= " HAVING min_price BETWEEN $min AND $max";
            }
        }

        // Add sorting
        switch ($sortBy) {
            case 'price-asc':
                $sql .= " ORDER BY min_price ASC";
                break;
            case 'price-desc':
                $sql .= " ORDER BY min_price DESC";
                break;
            case 'discount':
                $sql .= " ORDER BY max_discount DESC";
                break;
            default:
                // Default sorting by relevance (name matching) and then price
                $sql .= " ORDER BY 
                        CASE 
                            WHEN p.name LIKE '$query%' THEN 1
                            WHEN p.name LIKE '%$query%' THEN 2
                            ELSE 3 
                        END,
                        min_price ASC";
        }

        return $this->db_fetch_all($sql);
    }
}
