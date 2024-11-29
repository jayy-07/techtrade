<?php

require_once '../settings/db_class.php';

class Category extends db_connection
{

    public function add_category($category_name)
    {
        // Add a new category to the database
        $sql = "INSERT INTO categories (`name`) 
                VALUES ('$category_name')";
        return $this->db_query($sql);
    }

    public function get_all_categories()
    {
        // Retrieve all categories from the database
        $sql = "SELECT * FROM categories ORDER BY `name` ASC";
        return $this->db_fetch_all($sql);
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name";
        return $this->db_fetch_all($sql);
    }

    public function getProductsByCategory($categoryId, $brandId = null, $priceRange = null, $sortBy = null) {
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
            WHERE p.category_id = '$categoryId'";

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
                $sql .= " ORDER BY p.name ASC";
        }

        return $this->db_fetch_all($sql);
    }
}