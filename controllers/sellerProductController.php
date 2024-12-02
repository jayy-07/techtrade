<?php

require_once '../classes/SellerProduct.php';

// SellerProductController handles operations related to seller products
class SellerProductController
{
    private $sellerProduct;

    // Constructor initializes the SellerProduct object
    public function __construct()
    {
        $this->sellerProduct = new SellerProduct();
    }

    // Adds a product to the seller's inventory
    public function add_product($data)
    {
        $seller_id = $_SESSION['user_id'];
        $product_id = $data['product_id'];
        $price = $data['price'];
        $stock_quantity = $data['stock_quantity'];
        $discount = isset($data['discount']) ? $data['discount'] : 0.00;

        // Validate price and stock quantity
        if (!is_numeric($price) || $price <= 0) {
            return "Price must be a positive number.";
        }
        if (!is_numeric($stock_quantity) || $stock_quantity <= 0) {
            return "Stock quantity must be a positive integer.";
        }

        if ($this->sellerProduct->add_product($seller_id, $product_id, $price, $stock_quantity, $discount)) {
            return "Product added to inventory successfully!";
        } else {
            return "Failed to add product to inventory.";
        }
    }

    // Updates a product in the seller's inventory
    public function update_product($data)
    {
        $seller_id = $_SESSION['user_id'];
        $product_id = $data['product_id'];
        $price = $data['price'];
        $stock_quantity = $data['stock_quantity'];
        $discount = isset($data['discount']) ? $data['discount'] : 0.00;

        // Validate price, stock quantity, and discount
        if (!is_numeric($price) || $price <= 0) {
            return "Price must be a positive number.";
        }
        if (!is_numeric($stock_quantity) || $stock_quantity <= 0) {
            return "Stock quantity must be a positive integer.";
        }
        if (!is_numeric($discount) || $discount < 0 || $discount >= 100) {
            return "Discount must be between 0 and 99.99%.";
        }

        if ($this->sellerProduct->update_product($seller_id, $product_id, $price, $stock_quantity, $discount)) {
            return "Product updated successfully!";
        } else {
            return "Failed to update product.";
        }
    }

    // Deletes a product from the seller's inventory
    public function delete_product($seller_id, $product_id)
    {
        if ($this->sellerProduct->delete_product($seller_id, $product_id)) {
            return "Product deleted successfully!";
        } else {
            return "Failed to delete product.";
        }
    }

    public function get_product_by_id($seller_id, $product_id)
    {
        return $this->sellerProduct->get_product_by_id($seller_id, $product_id);
    }
}