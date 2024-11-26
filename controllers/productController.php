<?php
require_once '../settings/core.php';
require_once '../classes/Product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function addProduct($data) { 
        // Assuming 'status' is included in $data for seller requests
        if ($this->productModel->add_product($data)) {
            $product_id = $this->productModel->db->insert_id; 
            
            // Add product images (similar to previous example)
            // ... 
            return true;
        }
        return false;
    }

    public function getAllProducts() {
        return $this->productModel->get_all_products();
    }

    public function getProductById($product_id) {
        return $this->productModel->get_product_by_id($product_id);
    }

    public function updateProduct($data) {
        // Assuming 'status' is included in $data for admin updates
        return $this->productModel->update_product($data); 
    }

    public function deleteProduct($product_id) {
        return $this->productModel->delete_product($product_id);
    }

}