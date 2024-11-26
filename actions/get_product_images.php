<?php
require_once '../classes/Product.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $product = new Product();
    $product_id = $_GET['product_id'];

    try {
        $images = $product->get_product_images($product_id);
        echo json_encode($images); 
    } catch (Exception $e) {
        // Handle errors (e.g., log the error)
        echo json_encode([]); // Return an empty array in case of an error
    }
}
?>