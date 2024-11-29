<?php
require_once '../settings/db_class.php';

class Section extends db_connection {
    
    public function getAllSections() {
        $sql = "SELECT * FROM deal_sections";
        return $this->db_fetch_all($sql);
    }

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

    public function addSection($name) {
        $sql = "INSERT INTO deal_sections (name) VALUES ('$name')";
        return $this->db_query($sql);
    }

    public function addProductToSection($sectionId, $productId) {
        $sql = "INSERT INTO section_products (deal_section_id, product_id) 
                VALUES ('$sectionId', '$productId')";
        return $this->db_query($sql);
    }
}