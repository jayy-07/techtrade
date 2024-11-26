<?php

require_once '../classes/Product.php';

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function getProduct() {
        return $this->product;
    }

    public function index()
    {
        $products = $this->product->get_all_products();
        return $products;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'],
                'product_name' => $_POST['product_name'],
                'description' => $_POST['description']
            ];

            $image_urls = $this->handle_image_urls();

            if ($this->product->add_product($data)) {
                $product_id = $this->product->get_insert_id();
                $this->save_product_images($product_id, $image_urls);
                return "Product added successfully!";
            } else {
                return "Failed to add product.";
            }
        }
    }

    public function edit($product_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'product_id' => $product_id,
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'],
                'product_name' => $_POST['product_name'],
                'description' => $_POST['description']
            ];

            $image_urls = $this->handle_image_urls();

            if ($this->product->update_product($data)) {
                // Delete existing images and add new ones
                $this->product->delete_product_images($product_id);
                $this->save_product_images($product_id, $image_urls);

                return "Product updated successfully!";
            } else {
                return "Failed to update product.";
            }
        }
    }

    public function delete($product_id)
    {
        if ($this->product->delete_product($product_id)) {
            return "Product deleted successfully!";
        } else {
            return "Failed to delete product.";
        }
    }

    private function handle_image_urls()
    {
        $image_urls = [];
        if (isset($_POST['images']) && !empty($_POST['images'])) {
            foreach ($_POST['images'] as $image_url) {
                $image_urls[] = $image_url;
            }
        }
        return $image_urls;
    }

    private function save_product_images($product_id, $image_urls)
    {
        foreach ($image_urls as $index => $url) {
            $is_primary = ($index == 0) ? 1 : 0;
            $this->product->add_product_image($product_id, $url, $is_primary);
        }
    }
}