<?php

require_once '../classes/SellerProduct.php';

class SellerProductController
{
    private $sellerProduct;

    public function __construct()
    {
        $this->sellerProduct = new SellerProduct();
    }

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

    public function update_product($data)
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

        if ($this->sellerProduct->update_product($seller_id, $product_id, $price, $stock_quantity, $discount)) {
            return "Product updated successfully!";
        } else {
            return "Failed to update product.";
        }
    }

    public function delete_product($seller_id, $product_id)
    {
        if ($this->sellerProduct->delete_product($seller_id, $product_id)) {
            return "Product deleted from inventory successfully!";
        } else {
            return "Failed to delete product from inventory.";
        }
    }

    public function get_product_by_id($seller_id, $product_id)
    {
        return $this->sellerProduct->get_product_by_id($seller_id, $product_id);
    }
}