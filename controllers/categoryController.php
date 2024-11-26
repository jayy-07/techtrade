<?php
require_once '../settings/core.php';
require_once '../classes/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function getAllCategories() {
        return $this->categoryModel->get_all_categories();
    }

    // Add other controller methods as needed
}