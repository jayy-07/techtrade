<?php

require_once '../classes/Product.php';

/**
 * Controller class for managing product operations
 */
class ProductController
{
    /** @var Product Instance of Product class */
    private $product;

    /**
     * Constructor initializes Product instance
     */
    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * Gets the Product instance
     * @return Product The product instance
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Retrieves all products
     * @return array Array of all products
     */
    public function index()
    {
        $products = $this->product->get_all_products();
        return $products;
    }

    /**
     * Creates a new product with uploaded images
     * @return string Success/failure message
     */
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

    /**
     * Updates an existing product and its images
     * @param int $product_id ID of product to update
     * @return string Success/failure message
     */
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

    /**
     * Deletes a product
     * @param int $product_id ID of product to delete
     * @return string Success/failure message
     */
    public function delete($product_id)
    {
        if ($this->product->delete_product($product_id)) {
            return "Product deleted successfully!";
        } else {
            return "Failed to delete product.";
        }
    }

    /**
     * Processes uploaded image URLs from POST data
     * @return array Array of image URLs
     */
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

    /**
     * Saves product images to database
     * @param int $product_id ID of product to associate images with
     * @param array $image_urls Array of image URLs to save
     */
    private function save_product_images($product_id, $image_urls)
    {
        foreach ($image_urls as $index => $url) {
            $is_primary = ($index == 0) ? 1 : 0;
            $this->product->add_product_image($product_id, $url, $is_primary);
        }
    }
}