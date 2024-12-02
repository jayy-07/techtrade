<?php

require_once '../settings/db_class.php';

/**
 * Class for managing brand operations
 * Extends database connection class
 */
class Brand extends db_connection
{
    /**
     * Adds a new brand to the database
     * @param string $brand_name Name of the brand to add
     * @return bool Success/failure of operation
     */
    public function add_brand($brand_name)
    {
        // Add a new brand to the database
        $sql = "INSERT INTO brands (`name`) 
                VALUES ('$brand_name')";
        return $this->db_query($sql);
    }

    /**
     * Retrieves all brands from database ordered by name ascending
     * @return array Array of all brands
     */
    public function get_all_brands()
    {
        // Retrieve all brands from the database
        $sql = "SELECT * FROM brands ORDER BY `name` ASC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Alternative method to get all brands ordered by name
     * @return array Array of all brands
     */
    public function getAllBrands() {
        $sql = "SELECT * FROM brands ORDER BY name";
        return $this->db_fetch_all($sql);
    }

    /**
     * Gets products filtered by brand and optional parameters
     * @param int $brandId ID of the brand to filter by
     * @param int|null $categoryId Optional category ID filter
     * @param string|null $priceRange Optional price range filter (format: "min-max" or "min-+")
     * @param string|null $sortBy Optional sort parameter (price-asc, price-desc, discount)
     * @return array Array of filtered products with details
     */
    public function getProductsByBrand($brandId, $categoryId = null, $priceRange = null, $sortBy = null) {
        // Build base query with joins for product details
        $sql = "SELECT 
                p.product_id,
                p.name,
                MIN(sp.price) as min_price,
                MAX(sp.discount) as max_discount,
                pi.image_path,
                c.name as category_name
            FROM products p
            JOIN sellers_products sp ON p.product_id = sp.product_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.brand_id = '$brandId'";

        // Add category filter if specified
        if ($categoryId) {
            $sql .= " AND p.category_id = '$categoryId'";
        }

        // Group products before applying price filter
        $sql .= " GROUP BY p.product_id, p.name, pi.image_path, c.name";

        // Add price range filter if specified
        if ($priceRange) {
            list($min, $max) = explode('-', $priceRange);
            if ($max === '+') {
                $sql .= " HAVING min_price >= $min";
            } else {
                $sql .= " HAVING min_price BETWEEN $min AND $max";
            }
        }

        // Add sorting based on specified parameter
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