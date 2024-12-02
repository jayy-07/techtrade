<?php
require_once '../settings/db_class.php';

/**
 * Section class for managing deal sections and their products
 * Handles CRUD operations for sections and section-product relationships
 * Extends database connection class
 */
class Section extends db_connection {
    
    /**
     * Retrieves all sections from the deal_sections table
     * @return array Array of all sections
     */
    public function getAllSections() {
        $sql = "SELECT * FROM deal_sections";
        return $this->db_fetch_all($sql);
    }

    /**
     * Retrieves products associated with a specific section
     * Gets product details including minimum price, maximum discount and primary image
     * Limited to 10 products per section
     * @param int $sectionId Section ID to get products for
     * @return array Array of products in the section
     */
    public function getProductsBySection($sectionId) {
        $sql = "SELECT 
                p.product_id,
                p.name,
                MIN(sp.price) as min_price,
                MAX(sp.discount) as max_discount,
                pi.image_path
            FROM section_products sec_p
            JOIN products p ON sec_p.product_id = p.product_id
            JOIN sellers_products sp ON p.product_id = sp.product_id
            LEFT JOIN (
                SELECT product_id, image_path 
                FROM product_images 
                WHERE is_primary = 1
            ) pi ON p.product_id = pi.product_id
            WHERE sec_p.deal_section_id = '$sectionId'
            GROUP BY p.product_id, p.name, pi.image_path
            LIMIT 10";
        
        return $this->db_fetch_all($sql);
    }

    /**
     * Adds a new section to the deal_sections table
     * @param string $name Name of the section to add
     * @return bool True on success, false on failure
     */
    public function addSection($name) {
        $sql = "INSERT INTO deal_sections (name) VALUES ('$name')";
        return $this->db_query($sql);
    }

    /**
     * Adds a product to a specific section
     * @param int $sectionId Section ID to add product to
     * @param int $productId Product ID to add
     * @return bool True on success, false on failure
     */
    public function addProductToSection($sectionId, $productId) {
        $sql = "INSERT INTO section_products (deal_section_id, product_id) 
                VALUES ('$sectionId', '$productId')";
        return $this->db_query($sql);
    }
}