<?php
require_once '../settings/core.php';
require_once '../controllers/ProductController.php';
check_admin();

if (!isset($_GET['product_id'])) {
    echo json_encode([]);
    exit;
}

$productController = new ProductController();
$images = $productController->getProduct()->get_product_images($_GET['product_id']);
echo json_encode($images);
?>