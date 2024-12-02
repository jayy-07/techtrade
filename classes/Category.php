<?php

require_once '../settings/db_class.php';

/**
 * Class for managing category operations
 * Extends database connection class
 */
class Category extends db_connection
{
    /**
     * Adds a new category to the database
     * @param string $category_name Name of the category to add
     * @return bool Success/failure of operation
     */
    public function add_category($category_name)
    {
        $sql = "INSERT INTO categories (`name`) 
                VALUES ('$category_name')";
        return $this->db_query($sql);
    }

    /**
     * Retrieves all categories from database ordered by name ascending
     * @return array Array of all categories
     */
    public function get_all_categories()
    {
        $sql = "SELECT * FROM categories ORDER BY `name` ASC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Alternative method to get all categories ordered by name
     * @return array Array of all categories
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name";
        return $this->db_fetch_all($sql);
    }

    /**
     * Gets products filtered by category and optional parameters
     * @param int $categoryId ID of the category to filter by
     * @param int|null $brandId Optional brand ID filter
     * @param string|null $priceRange Optional price range filter (format: "min-max" or "min-+")
     * @param string|null $sortBy Optional sort parameter (price-asc, price-desc, discount)
     * @return array Array of filtered products with details
     */
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

        // Add sorting based on the specified criteria
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